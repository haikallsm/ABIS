<?php
/**
 * Letter Request Model
 * ABIS - Aplikasi Desa Digital
 */

class LetterRequest {
    private $table = 'letter_requests';

    /**
     * Find request by ID
     * @param int $id
     * @return array|null
     */
    public function findById($id) {
        return fetchOne(
            "SELECT lr.*, lt.name as letter_type_name, lt.code as letter_type_code,
                    u.username, u.full_name as user_full_name, u.email as user_email,
                    ua.full_name as approved_by_name, ur.full_name as rejected_by_name
             FROM {$this->table} lr
             LEFT JOIN letter_types lt ON lr.letter_type_id = lt.id
             LEFT JOIN users u ON lr.user_id = u.id
             LEFT JOIN users ua ON lr.approved_by = ua.id
             LEFT JOIN users ur ON lr.rejected_by = ur.id
             WHERE lr.id = ?",
            [$id]
        );
    }

    /**
     * Find requests by user ID
     * @param int $userId
     * @param int $page
     * @param int $limit
     * @return array
     */
    public function findByUserId($userId, $page = 1, $limit = ITEMS_PER_PAGE) {
        $offset = ($page - 1) * $limit;

        $requests = fetchAll(
            "SELECT lr.*, lt.name as letter_type_name, lt.code as letter_type_code
             FROM {$this->table} lr
             LEFT JOIN letter_types lt ON lr.letter_type_id = lt.id
             WHERE lr.user_id = ?
             ORDER BY lr.created_at DESC
             LIMIT {$limit} OFFSET {$offset}",
            [$userId]
        );

        $total = fetchValue(
            "SELECT COUNT(*) FROM {$this->table} WHERE user_id = ?",
            [$userId]
        );

        return [
            'requests' => $requests,
            'total' => $total,
            'pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    }

    /**
     * Get all requests with pagination and filtering
     * @param int $page
     * @param int $limit
     * @param array $filters
     * @return array
     */
    public function getAll($page = 1, $limit = ITEMS_PER_PAGE, $filters = []) {
        $offset = ($page - 1) * $limit;

        $whereClause = "1=1";
        $params = [];

        if (!empty($filters['status'])) {
            $whereClause .= " AND lr.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['letter_type_id'])) {
            $whereClause .= " AND lr.letter_type_id = ?";
            $params[] = $filters['letter_type_id'];
        }

        if (!empty($filters['user_id'])) {
            $whereClause .= " AND lr.user_id = ?";
            $params[] = $filters['user_id'];
        }

        if (!empty($filters['search'])) {
            $whereClause .= " AND (u.username LIKE ? OR u.email LIKE ? OR u.full_name LIKE ? OR lr.id = ?)";
            $searchParam = "%{$filters['search']}%";
            $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $filters['search']]);
        }

        $requests = fetchAll(
            "SELECT lr.*, lt.name as letter_type_name, lt.code as letter_type_code,
                    u.username, u.full_name as user_full_name, u.email as user_email,
                    ua.full_name as approved_by_name, ur.full_name as rejected_by_name
             FROM {$this->table} lr
             LEFT JOIN letter_types lt ON lr.letter_type_id = lt.id
             LEFT JOIN users u ON lr.user_id = u.id
             LEFT JOIN users ua ON lr.approved_by = ua.id
             LEFT JOIN users ur ON lr.rejected_by = ur.id
             WHERE {$whereClause}
             ORDER BY lr.created_at DESC
             LIMIT {$limit} OFFSET {$offset}",
            $params
        );

        $countSql = "SELECT COUNT(*) FROM {$this->table} lr
                     LEFT JOIN users u ON lr.user_id = u.id
                     WHERE {$whereClause}";
        $total = fetchValue($countSql, $params);

        return [
            'requests' => $requests,
            'total' => $total,
            'pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    }

    /**
     * Get all requests for export (without pagination)
     * @param array $filters
     * @return array
     */
    public function getAllForExport($filters = []) {
        $whereClause = "1=1";
        $params = [];

        // Date filters
        if (!empty($filters['dari_tanggal'])) {
            $whereClause .= " AND DATE(lr.created_at) >= ?";
            $params[] = $filters['dari_tanggal'];
        }

        if (!empty($filters['sampai_tanggal'])) {
            $whereClause .= " AND DATE(lr.created_at) <= ?";
            $params[] = $filters['sampai_tanggal'];
        }

        // Letter type filters
        $typeConditions = [];
        if (!empty($filters['jenis_keterangan'])) {
            $typeConditions[] = "lt.code LIKE 'SK%'";
        }
        if (!empty($filters['jenis_pengantar'])) {
            $typeConditions[] = "lt.code LIKE 'SP%'";
        }
        if (!empty($filters['jenis_lainnya'])) {
            $typeConditions[] = "lt.code NOT LIKE 'SK%' AND lt.code NOT LIKE 'SP%'";
        }

        if (!empty($typeConditions)) {
            $whereClause .= " AND (" . implode(" OR ", $typeConditions) . ")";
        }

        $requests = fetchAll(
            "SELECT lr.*, lt.name as letter_type_name, lt.code as letter_type_code,
                    u.username, u.full_name, u.nik, u.email as user_email,
                    ua.full_name as approved_by_name, ur.full_name as rejected_by_name
             FROM {$this->table} lr
             LEFT JOIN letter_types lt ON lr.letter_type_id = lt.id
             LEFT JOIN users u ON lr.user_id = u.id
             LEFT JOIN users ua ON lr.approved_by = ua.id
             LEFT JOIN users ur ON lr.rejected_by = ur.id
             WHERE {$whereClause}
             ORDER BY lr.created_at DESC",
            $params
        );

        return $requests;
    }

    /**
     * Get count by status
     * @param string $status
     * @return int
     */
    public function getCountByStatus($status) {
        $result = fetchValue(
            "SELECT COUNT(*) FROM {$this->table} WHERE status = ?",
            [$status]
        );
        return $result ?? 0;
    }

    /**
     * Create new request
     * @param array $data
     * @return int|false
     */
    public function create($data) {
        try {
            $data['created_at'] = date(DATETIME_FORMAT);
            $data['updated_at'] = date(DATETIME_FORMAT);

            return insert($this->table, $data);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Update request
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        try {
            $data['updated_at'] = date(DATETIME_FORMAT);
            return update($this->table, $data, 'id = ?', ['id' => $id]) > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Approve request
     * @param int $id
     * @param int $approvedBy
     * @param string $notes
     * @return bool
     */
    public function approve($id, $approvedBy, $notes = '') {
        try {
            return $this->update($id, [
                'status' => STATUS_APPROVED,
                'approved_by' => $approvedBy,
                'approved_at' => date(DATETIME_FORMAT),
                'admin_notes' => $notes
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Reject request
     * @param int $id
     * @param int $rejectedBy
     * @param string $notes
     * @return bool
     */
    public function reject($id, $rejectedBy, $notes = '') {
        try {
            return $this->update($id, [
                'status' => STATUS_REJECTED,
                'rejected_by' => $rejectedBy,
                'rejected_at' => date(DATETIME_FORMAT),
                'admin_notes' => $notes
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Mark request as completed
     * @param int $id
     * @return bool
     */
    public function complete($id) {
        try {
            return $this->update($id, [
                'status' => STATUS_COMPLETED
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Update generated file path
     * @param int $id
     * @param string $filePath
     * @return bool
     */
    public function updateGeneratedFile($id, $filePath) {
        try {
            return $this->update($id, [
                'generated_file' => $filePath
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Mark as sent via Telegram
     * @param int $id
     * @return bool
     */
    public function markAsTelegramSent($id) {
        try {
            return $this->update($id, [
                'telegram_sent' => true
            ]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Delete request
     * @param int $id
     * @return bool
     */
    public function delete($id) {
        try {
            return delete($this->table, 'id = ?', [$id]) > 0;
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Get request statistics
     * @return array
     */
    public function getStats() {
        $stats = [];

        $statuses = [STATUS_PENDING, STATUS_APPROVED, STATUS_REJECTED, STATUS_COMPLETED];
        foreach ($statuses as $status) {
            $stats[$status] = fetchValue(
                "SELECT COUNT(*) FROM {$this->table} WHERE status = ?",
                [$status]
            );
        }

        $stats['total'] = array_sum($stats);

        return $stats;
    }

    /**
     * Get requests by status
     * @param string $status
     * @param int $limit
     * @return array
     */
    public function getByStatus($status, $limit = null) {
        $sql = "SELECT lr.*, lt.name as letter_type_name, u.username, u.full_name as user_full_name
                FROM {$this->table} lr
                LEFT JOIN letter_types lt ON lr.letter_type_id = lt.id
                LEFT JOIN users u ON lr.user_id = u.id
                WHERE lr.status = ?
                ORDER BY lr.created_at DESC";

        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }

        return fetchAll($sql, [$status]);
    }

    /**
     * Get pending requests count
     * @return int
     */
    public function getPendingCount() {
        return fetchValue("SELECT COUNT(*) FROM {$this->table} WHERE status = ?", [STATUS_PENDING]);
    }

    /**
     * Check if user can access request
     * @param int $requestId
     * @param int $userId
     * @return bool
     */
    public function canUserAccess($requestId, $userId) {
        return fetchValue(
            "SELECT COUNT(*) FROM {$this->table} WHERE id = ? AND user_id = ?",
            [$requestId, $userId]
        ) > 0;
    }
}
