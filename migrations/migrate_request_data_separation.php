<?php
/**
 * Data Migration: Separate existing request_data into request_data and additional_data
 *
 * This migration processes existing letter_requests and separates the JSON data
 * in request_data column into basic request_data and type-specific additional_data
 * based on the new categorization logic.
 *
 * @created December 2025
 */

require_once __DIR__ . '/../config/database.php';

// Initialize database connection
$pdo = getDBConnection();

try {
    echo "🔄 Starting data migration for request_data separation...\n";

    // Get all existing requests with request_data
    $stmt = $pdo->prepare("
        SELECT id, request_data, letter_type_id
        FROM letter_requests
        WHERE request_data IS NOT NULL
        AND request_data != 'null'
        AND request_data != '{}'
        AND (additional_data IS NULL OR additional_data = '{}')
    ");
    $stmt->execute();
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo "📊 Found " . count($requests) . " requests to migrate\n";

    // Process each request
    $processed = 0;
    $errors = 0;

    foreach ($requests as $request) {
        try {
            $requestId = $request['id'];
            $letterTypeId = $request['letter_type_id'];
            $oldRequestData = json_decode($request['request_data'], true);

            if (empty($oldRequestData)) {
                continue;
            }

            // Separate data using the same logic as LetterRequest model
            $separatedData = separateRequestData($oldRequestData);

            // Update the request with separated data
            $updateStmt = $pdo->prepare("
                UPDATE letter_requests
                SET request_data = ?, additional_data = ?
                WHERE id = ?
            ");
            $updateStmt->execute([
                json_encode($separatedData['request_data']),
                json_encode($separatedData['additional_data']),
                $requestId
            ]);

            $processed++;
            echo "✅ Migrated request ID: $requestId\n";

        } catch (Exception $e) {
            $errors++;
            echo "❌ Error migrating request ID {$request['id']}: " . $e->getMessage() . "\n";
        }
    }

    echo "\n📈 Migration Summary:\n";
    echo "- ✅ Successfully migrated: $processed requests\n";
    echo "- ❌ Errors: $errors requests\n";
    echo "- 📊 Total processed: " . ($processed + $errors) . " requests\n";

    if ($errors > 0) {
        echo "\n⚠️  Some requests had errors during migration. Check the error messages above.\n";
        echo "These requests may need manual review.\n";
    }

    echo "\n🎯 Migration completed successfully!\n";
    echo "All existing request_data has been separated into request_data and additional_data columns.\n";

} catch (Exception $e) {
    echo "❌ Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}

/**
 * Separate form data into request_data and additional_data based on field types
 * This mirrors the logic in LetterRequest::separateRequestData()
 *
 * @param array $formData
 * @return array
 */
function separateRequestData($formData) {
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
        // Skip system fields that shouldn't be in request data
        if (in_array($key, ['user_id', 'letter_type_id', 'status', 'admin_notes', 'csrf_token'])) {
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
?>
