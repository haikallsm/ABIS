<?php
/**
 * User Model
 * ABIS - Aplikasi Desa Digital
 */

class User {
    private $table = 'users';

    /**
     * Find user by ID
     * @param int $id
     * @return array|null
     */
    public function findById($id) {
        return fetchOne(
            "SELECT id, username, email, full_name, role, phone, address, created_at, updated_at, is_active
             FROM {$this->table}
             WHERE id = ? AND is_active = 1",
            [$id]
        );
    }

    /**
     * Find user by username
     * @param string $username
     * @return array|null
     */
    public function findByUsername($username) {
        // Check if input looks like NIK (16 digits) or username
        if (is_numeric($username) && strlen($username) === 16) {
            // Search by NIK
            return fetchOne(
                "SELECT id, username, email, password, full_name, nik, role, phone, address, created_at, updated_at, is_active
                 FROM {$this->table}
                 WHERE nik = ? AND is_active = 1",
                [$username]
            );
        } else {
            // Search by username
            return fetchOne(
                "SELECT id, username, email, password, full_name, nik, role, phone, address, created_at, updated_at, is_active
                 FROM {$this->table}
                 WHERE username = ? AND is_active = 1",
                [$username]
            );
        }
    }

    /**
     * Find user by email
     * @param string $email
     * @return array|null
     */
    public function findByEmail($email) {
        return fetchOne(
            "SELECT id, username, email, password, full_name, role, phone, address, created_at, updated_at, is_active
             FROM {$this->table}
             WHERE email = ? AND is_active = 1",
            [$email]
        );
    }

    /**
     * Create new user
     * @param array $data
     * @return int|false
     */
    public function create($data) {
        try {
            // Hash password
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            $data['created_at'] = date(DATETIME_FORMAT);
            $data['updated_at'] = date(DATETIME_FORMAT);

            // Debug: Log data sebelum insert
            error_log("User create: Attempting to insert data: " . json_encode($data));

            $result = insert($this->table, $data);

            // Debug: Log hasil insert
            error_log("User create: Insert result: " . ($result ? "SUCCESS (ID: $result)" : "FAILED"));

            return $result;
        } catch (Exception $e) {
            // Debug: Log exception detail
            error_log("User create: Exception caught: " . $e->getMessage());
            error_log("User create: Exception trace: " . $e->getTraceAsString());
            return false;
        }
    }

    /**
     * Update user
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
     * Update password
     * @param int $id
     * @param string $password
     * @return bool
     */
    public function updatePassword($id, $password) {
        try {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            return $this->update($id, ['password' => $hashedPassword]);
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Soft delete user
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
     * Verify password
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }

    /**
     * Get all users with pagination
     * @param int $page
     * @param int $limit
     * @param string $search
     * @return array
     */
    public function getAll($page = 1, $limit = ITEMS_PER_PAGE, $search = '') {
        $offset = ($page - 1) * $limit;

        $whereClause = "is_active = 1";
        $params = [];

        if (!empty($search)) {
            $whereClause .= " AND (username LIKE ? OR email LIKE ? OR full_name LIKE ?)";
            $searchParam = "%{$search}%";
            $params = [$searchParam, $searchParam, $searchParam];
        }

        $users = fetchAll(
            "SELECT id, username, email, full_name, role, phone, address, created_at
             FROM {$this->table}
             WHERE {$whereClause}
             ORDER BY created_at DESC
             LIMIT {$limit} OFFSET {$offset}",
            $params
        );

        $total = fetchValue(
            "SELECT COUNT(*) FROM {$this->table} WHERE {$whereClause}",
            $params
        );

        return [
            'users' => $users,
            'total' => $total,
            'pages' => ceil($total / $limit),
            'current_page' => $page
        ];
    }

    /**
     * Get all users without pagination (for management)
     * @param string $search
     * @return array
     */
    public function getAllUsers($search = '') {
        $whereClause = "is_active = 1";
        $params = [];

        if (!empty($search)) {
            $whereClause .= " AND (username LIKE ? OR email LIKE ? OR full_name LIKE ?)";
            $searchParam = "%{$search}%";
            $params = [$searchParam, $searchParam, $searchParam];
        }

        $users = fetchAll(
            "SELECT id, username, email, full_name, role, phone, address, created_at
             FROM {$this->table}
             WHERE {$whereClause}
             ORDER BY created_at DESC",
            $params
        );

        return [
            'users' => $users,
            'total' => count($users),
            'pages' => 1,
            'current_page' => 1
        ];
    }

    /**
     * Get users by role
     * @param string $role
     * @return array
     */
    public function getByRole($role) {
        return fetchAll(
            "SELECT id, username, email, full_name, phone, address, created_at
             FROM {$this->table}
             WHERE role = ? AND is_active = 1
             ORDER BY full_name ASC",
            [$role]
        );
    }

    /**
     * Check if username exists
     * @param string $username
     * @param int $excludeId
     * @return bool
     */
    public function usernameExists($username, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE username = ? AND is_active = 1";
        $params = [$username];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        return fetchValue($sql, $params) > 0;
    }

    /**
     * Check if email exists
     * @param string $email
     * @param int $excludeId
     * @return bool
     */
    public function emailExists($email, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE email = ? AND is_active = 1";
        $params = [$email];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        return fetchValue($sql, $params) > 0;
    }

    /**
     * Check if NIK exists
     * @param string $nik
     * @param int $excludeId
     * @return bool
     */
    public function nikExists($nik, $excludeId = null) {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE nik = ? AND is_active = 1";
        $params = [$nik];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        return fetchValue($sql, $params) > 0;
    }

    /**
     * Get user statistics
     * @return array
     */
    public function getStats() {
        $totalUsers = fetchValue("SELECT COUNT(*) FROM {$this->table} WHERE is_active = 1");
        $adminUsers = fetchValue("SELECT COUNT(*) FROM {$this->table} WHERE role = 'admin' AND is_active = 1");
        $regularUsers = fetchValue("SELECT COUNT(*) FROM {$this->table} WHERE role = 'user' AND is_active = 1");

        return [
            'total' => $totalUsers,
            'admins' => $adminUsers,
            'users' => $regularUsers
        ];
    }
}
