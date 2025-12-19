<?php
/**
 * App Settings Model
 * ABIS - Aplikasi Desa Digital
 * FIX: data previously stored in config files - now persisted to database
 */

class AppSettings {
    private $table = 'app_settings';

    /**
     * Get setting value by key
     * @param string $key
     * @return mixed
     */
    public function get($key) {
        $setting = fetchOne(
            "SELECT value, type FROM {$this->table} WHERE setting_key = ?",
            [$key]
        );

        if (!$setting) {
            return null;
        }

        // Convert value based on type
        switch ($setting['type']) {
            case 'boolean':
                return (bool) $setting['value'];
            case 'integer':
                return (int) $setting['value'];
            case 'float':
                return (float) $setting['value'];
            case 'json':
                return json_decode($setting['value'], true);
            default:
                return $setting['value'];
        }
    }

    /**
     * Set setting value by key
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @return bool
     */
    public function set($key, $value, $type = 'string') {
        // Convert value based on type
        switch ($type) {
            case 'boolean':
                $value = $value ? '1' : '0';
                break;
            case 'json':
                $value = json_encode($value);
                break;
            default:
                $value = (string) $value;
        }

        // Check if setting exists
        $existing = fetchOne(
            "SELECT id FROM {$this->table} WHERE setting_key = ?",
            [$key]
        );

        if ($existing) {
            // Update existing
            return update($this->table, [
                'value' => $value,
                'type' => $type,
                'updated_at' => date('Y-m-d H:i:s')
            ], ['id' => $existing['id']]);
        } else {
            // Insert new
            return insert($this->table, [
                'setting_key' => $key,
                'value' => $value,
                'type' => $type,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Get all settings
     * @return array
     */
    public function getAll() {
        $settings = fetchAll("SELECT * FROM {$this->table} ORDER BY setting_key");

        $result = [];
        foreach ($settings as $setting) {
            $result[$setting['setting_key']] = $this->get($setting['setting_key']);
        }

        return $result;
    }

    /**
     * Delete setting by key
     * @param string $key
     * @return bool
     */
    public function delete($key) {
        return delete($this->table, ['setting_key' => $key]);
    }
}
