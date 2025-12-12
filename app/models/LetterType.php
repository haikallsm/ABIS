<?php
/**
 * Letter Type Model
 * ABIS - Aplikasi Desa Digital
 */

class LetterType {
    private $table = 'letter_types';

    /**
     * Find letter type by ID
     * @param int $id
     * @return array|null
     */
    public function findById($id) {
        return fetchOne(
            "SELECT * FROM {$this->table} WHERE id = ? AND is_active = 1",
            [$id]
        );
    }

    /**
     * Find letter type by code
     * @param string $code
     * @return array|null
     */
    public function findByCode($code) {
        return fetchOne(
            "SELECT * FROM {$this->table} WHERE code = ? AND is_active = 1",
            [$code]
        );
    }

    /**
     * Get all active letter types
     * @return array
     */
    public function getAllActive() {
        return fetchAll(
            "SELECT * FROM {$this->table} WHERE is_active = 1 ORDER BY name ASC"
        );
    }

    /**
     * Get all letter types with pagination
     * @param int $page
     * @param int $limit
     * @param string $search
     * @return array
     */
    public function getAll($page = 1, $limit = ITEMS_PER_PAGE, $search = '') {
        $offset = ($page - 1) * $limit;

        $whereClause = "1=1";
        $params = [];

        if (!empty($search)) {
            $whereClause .= " AND (name LIKE ? OR code LIKE ? OR description LIKE ?)";
            $searchParam = "%{$search}%";
            $params = [$searchParam, $searchParam, $searchParam];
        }

        $letterTypes = fetchAll(
            "SELECT * FROM {$this->table}
             WHERE {$whereClause}
             ORDER BY name ASC
             LIMIT {$limit} OFFSET {$offset}",
            $params
        );

        $total = fetchValue(
            "SELECT COUNT(*) FROM {$this->table} WHERE {$whereClause}",
            $params
        );

        return [
            'letter_types' => $letterTypes,
            'total' => $total,
            'pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    }

    /**
     * Create new letter type
     * @param array $data
     * @return int|false
     */
    public function create($data) {
        try {
            $data['created_at'] = date(DATETIME_FORMAT);
            $data['updated_at'] = date(DATETIME_FORMAT);

            // Convert required_fields array to JSON if it's an array
            if (isset($data['required_fields']) && is_array($data['required_fields'])) {
                $data['required_fields'] = json_encode($data['required_fields']);
            }

            return insert($this->table, $data);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Update letter type
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        try {
            $data['updated_at'] = date(DATETIME_FORMAT);

            // Convert required_fields array to JSON if it's an array
            if (isset($data['required_fields']) && is_array($data['required_fields'])) {
                $data['required_fields'] = json_encode($data['required_fields']);
            }

            return update($this->table, $data, 'id = ?', ['id' => $id]) > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete letter type (soft delete)
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        try {
            return update($this->table, ['is_active' => 0], 'id = ?', ['id' => $id]) > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Check if code exists
     * @param string $code
     * @param int $excludeId
     * @return bool
     */
    public function codeExists($code, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE code = ? AND is_active = 1";
        $params = [$code];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        return fetchValue($sql, $params) > 0;
    }

    /**
     * Get required fields for a letter type
     * @param int $id
     * @return array
     */
    public function getRequiredFields($id) {
        $letterType = $this->findById($id);
        if (!$letterType || empty($letterType['required_fields'])) {
            return [];
        }

        $fields = json_decode($letterType['required_fields'], true);
        return is_array($fields) ? $fields : [];
    }

    /**
     * Get letter type statistics
     * @return array
     */
    public function getStats() {
        $total = fetchValue("SELECT COUNT(*) FROM {$this->table} WHERE is_active = 1");
        $inactive = fetchValue("SELECT COUNT(*) FROM {$this->table} WHERE is_active = 0");

        // Get request counts per letter type
        $requestStats = fetchAll(
            "SELECT lt.name, lt.code, COUNT(lr.id) as request_count
             FROM {$this->table} lt
             LEFT JOIN letter_requests lr ON lt.id = lr.letter_type_id
             WHERE lt.is_active = 1
             GROUP BY lt.id, lt.name, lt.code
             ORDER BY request_count DESC"
        );

        return [
            'total_active' => $total,
            'total_inactive' => $inactive,
            'request_stats' => $requestStats
        ];
    }

    /**
     * Get most requested letter types
     * @param int $limit
     * @return array
     */
    public function getMostRequested($limit = 5) {
        return fetchAll(
            "SELECT lt.*, COUNT(lr.id) as request_count
             FROM {$this->table} lt
             LEFT JOIN letter_requests lr ON lt.id = lr.letter_type_id
             WHERE lt.is_active = 1
             GROUP BY lt.id
             ORDER BY request_count DESC
             LIMIT {$limit}"
        );
    }
}
