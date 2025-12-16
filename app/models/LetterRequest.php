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
        $request = fetchOne(
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

        // Decode and merge both JSON data sources
        if ($request) {
            // Decode request_data JSON (basic form data)
            $requestData = json_decode($request['request_data'] ?? '{}', true);

            // Decode additional_data JSON (type-specific data)
            $additionalData = json_decode($request['additional_data'] ?? '{}', true);

            // Merge all data sources (request table + request_data + additional_data)
            $request = array_merge($request, $requestData, $additionalData);
        }

        return $request;
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

        $requests = fetchAll(
            "SELECT lr.*, lt.name as letter_type_name, lt.code as letter_type_code,
                    u.username, u.full_name as user_full_name, u.email as user_email, u.nik as user_nik,
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

        // Decode request_data JSON and merge with request data
        foreach ($requests as &$request) {
            $requestData = json_decode($request['request_data'] ?? '{}', true);
            $request = array_merge($request, $requestData);
        }

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


        $requests = fetchAll(
            "SELECT lr.*, lt.name as letter_type_name, lt.code as letter_type_code,
                    u.username, u.full_name as user_full_name, u.nik as user_nik, u.email as user_email,
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

        // Decode request_data JSON and merge with request data
        foreach ($requests as &$request) {
            $requestData = json_decode($request['request_data'] ?? '{}', true);
            $request = array_merge($request, $requestData);
        }

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
     * Create request with data separation (Enhanced)
     * @param array $data
     * @return int|bool
     */
    public function createWithDataSeparation($data) {
        try {
            // Separate data into different categories
            $separatedData = $this->separateRequestData($data);

            // Prepare data for insertion
            $insertData = [
                'user_id' => $data['user_id'],
                'letter_type_id' => $data['letter_type_id'],
                'status' => $data['status'] ?? 'pending',
                'request_data' => json_encode($separatedData['request_data']),
                'additional_data' => json_encode($separatedData['additional_data']),
                'admin_notes' => $data['admin_notes'] ?? null,
                'created_at' => date(DATETIME_FORMAT),
                'updated_at' => date(DATETIME_FORMAT)
            ];

            return insert($this->table, $insertData);
        } catch (Exception $e) {
            error_log("Error creating letter request: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Separate form data into request_data and additional_data based on field types
     * @param array $formData
     * @return array
     */
    private function separateRequestData($formData) {
        // Define field categories
        $basicFields = [
            'nama', 'nik', 'alamat', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir',
            'agama', 'pekerjaan', 'keperluan', 'alamat_domisili', 'nama_usaha',
            'jenis_usaha', 'alamat_usaha', 'warganegara'
        ];

        $educationFields = [
            'sekolah', 'nis_nim', 'jurusan', 'semester', 'nama_beasiswa', 'nama_ayah'
        ];

        $businessFields = [
            'luas_usaha', 'mulai_usaha', 'tujuan', 'penghasilan'
        ];

        $familyFields = [
            'nik_pasangan', 'nama_pasangan'
        ];

        $eventFields = [
            'nama_kegiatan', 'tanggal_kegiatan', 'waktu_kegiatan',
            'tempat_kegiatan', 'hiburan', 'umur'
        ];

        $requestData = [];
        $additionalData = [];

        foreach ($formData as $key => $value) {
            // Skip system fields
            if (in_array($key, ['user_id', 'letter_type_id', 'status', 'admin_notes'])) {
                continue;
            }

            // Categorize fields
            if (in_array($key, $basicFields)) {
                $requestData[$key] = $value;
            } elseif (in_array($key, $educationFields)) {
                $additionalData['education'][$key] = $value;
            } elseif (in_array($key, $businessFields)) {
                $additionalData['business'][$key] = $value;
            } elseif (in_array($key, $familyFields)) {
                $additionalData['family'][$key] = $value;
            } elseif (in_array($key, $eventFields)) {
                $additionalData['event'][$key] = $value;
            } else {
                // Default to request_data for unknown fields
                $requestData[$key] = $value;
            }
        }

        return [
            'request_data' => $requestData,
            'additional_data' => $additionalData
        ];
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

    /**
     * Get dashboard statistics
     * @return array
     */
    public function getDashboardStats() {
        return [
            'total' => $this->countAll(),
            'pending' => $this->countAll(STATUS_PENDING),
            'approved' => $this->countAll(STATUS_APPROVED),
            'rejected' => $this->countAll(STATUS_REJECTED),
            'this_month' => $this->countThisMonth(),
            'today' => $this->countToday()
        ];
    }

    /**
     * Get today's statistics
     * @return array
     */
    public function getTodayStats() {
        $today = date('Y-m-d');
        return [
            'pending' => fetchValue(
                "SELECT COUNT(*) FROM {$this->table} WHERE DATE(created_at) = ? AND status = ?",
                [$today, STATUS_PENDING]
            ),
            'approved' => fetchValue(
                "SELECT COUNT(*) FROM {$this->table} WHERE DATE(approved_at) = ? AND status = ?",
                [$today, STATUS_APPROVED]
            )
        ];
    }

    /**
     * Get this month's statistics
     * @return array
     */
    public function getThisMonthStats() {
        $thisMonth = date('Y-m');
        return [
            'total' => fetchValue(
                "SELECT COUNT(*) FROM {$this->table} WHERE DATE_FORMAT(created_at, '%Y-%m') = ?",
                [$thisMonth]
            ),
            'approved' => fetchValue(
                "SELECT COUNT(*) FROM {$this->table} WHERE DATE_FORMAT(approved_at, '%Y-%m') = ? AND status = ?",
                [$thisMonth, STATUS_APPROVED]
            )
        ];
    }

    /**
     * Count requests by status
     * @param string|null $status
     * @return int
     */
    public function countAll($status = null) {
        if ($status) {
            return fetchValue("SELECT COUNT(*) FROM {$this->table} WHERE status = ?", [$status]);
        }
        return fetchValue("SELECT COUNT(*) FROM {$this->table}");
    }

    /**
     * Count this month's requests
     * @return int
     */
    private function countThisMonth() {
        $thisMonth = date('Y-m');
        return fetchValue(
            "SELECT COUNT(*) FROM {$this->table} WHERE DATE_FORMAT(created_at, '%Y-%m') = ?",
            [$thisMonth]
        );
    }

    /**
     * Count today's requests
     * @return int
     */
    private function countToday() {
        $today = date('Y-m-d');
        return fetchValue(
            "SELECT COUNT(*) FROM {$this->table} WHERE DATE(created_at) = ?",
            [$today]
        );
    }

    /**
     * Get recent requests for dashboard
     * @param int $limit
     * @return array
     */
    public function getRecentRequests($limit = 5) {
        $sql = "SELECT lr.*, u.full_name as user_full_name, lt.name as letter_type_name
                FROM {$this->table} lr
                LEFT JOIN users u ON lr.user_id = u.id
                LEFT JOIN letter_types lt ON lr.letter_type_id = lt.id
                ORDER BY lr.created_at DESC
                LIMIT ?";

        return fetchAll($sql, [$limit]);
    }

    /**
     * Get pending requests
     * @param int $limit
     * @return array
     */
    public function getPendingRequests($limit = 10) {
        $sql = "SELECT lr.*, u.full_name as user_full_name, lt.name as letter_type_name
                FROM {$this->table} lr
                LEFT JOIN users u ON lr.user_id = u.id
                LEFT JOIN letter_types lt ON lr.letter_type_id = lt.id
                WHERE lr.status = ?
                ORDER BY lr.created_at DESC
                LIMIT ?";

        return fetchAll($sql, [STATUS_PENDING, $limit]);
    }

    /**
     * Approve request
     * @param int $id Request ID
     * @param int $adminId Admin ID
     * @param string $notes Approval notes
     * @return bool
     */
    public function approve($id, $adminId, $notes = '') {
        return $this->update($id, [
            'status' => STATUS_APPROVED,
            'approved_by' => $adminId,
            'approved_at' => date('Y-m-d H:i:s'),
            'admin_notes' => $notes
        ]);
    }

    /**
     * Reject request
     * @param int $id Request ID
     * @param int $adminId Admin ID
     * @param string $notes Rejection notes
     * @return bool
     */
    public function reject($id, $adminId, $notes = '') {
        return $this->update($id, [
            'status' => STATUS_REJECTED,
            'approved_by' => $adminId,
            'approved_at' => date('Y-m-d H:i:s'),
            'admin_notes' => $notes
        ]);
    }

    /**
     * Update generated file path
     * @param int $id Request ID
     * @param string $filename Generated file name
     * @return bool
     */
    public function updateGeneratedFile($id, $filename) {
        return $this->update($id, ['generated_file' => $filename]);
    }

    /**
     * Mark as Telegram sent
     * @param int $id Request ID
     * @return bool
     */
    public function markAsTelegramSent($id) {
        return $this->update($id, [
            'telegram_sent' => true,
            'telegram_sent_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Delete request (only for pending requests)
     * @param int $id Request ID
     * @return bool
     */
    public function delete($id) {
        $request = $this->findById($id);
        if (!$request || $request['status'] !== STATUS_PENDING) {
            return false;
        }

        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return execute($sql, [$id]);
    }
}
