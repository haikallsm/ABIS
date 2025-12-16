<?php

/**
 * Letter Service - Business Logic Layer
 * Handles letter generation, PDF creation, and letter numbering
 *
 * ABIS - Aplikasi Desa Digital
 */

require_once 'utils/PDFGenerator.php';
require_once 'config/letter_constants.php';

/**
 * Get default village information for letter templates
 *
 * @return array Village information
 */
function getDefaultTemplateData() {
    return [
        'village_name' => 'PANGGUNG',
        'village_address' => 'Jl. Raya Panggung No. 1, Kecamatan Panggung, Kabupaten Tanggamus, Lampung',
        'village_phone' => '(0728) 123456',
        'village_head_title' => 'Kepala Desa Panggung',
        'village_head_name' => 'NAMA KEPALA DESA',
        'district_name' => 'Panggung',
        'regency_name' => 'Tanggamus',
        'province_name' => 'Lampung'
    ];
}

class LetterService
{
    private $pdfGenerator;
    private $letterRequestModel;

    public function __construct()
    {
        $this->pdfGenerator = new PDFGenerator();
        $this->letterRequestModel = new LetterRequest();
    }

    /**
     * Generate unique letter number with Roman month format
     *
     * @param array $requestData Letter request data
     * @return string Formatted letter number
     */
    public function generateLetterNumber($requestData)
    {
        $letterType = $requestData['letter_type_code'] ?? 'UNK';
        $villageCode = '01'; // Could be configurable
        $sequential = $this->getNextSequentialNumber($letterType);
        $romanMonth = $this->getRomanMonth(date('n'));
        $year = date('Y');

        return sprintf(LETTER_NUMBER_FORMAT, $villageCode, $sequential, $romanMonth, $year);
    }

    /**
     * Get next sequential number for letter type
     *
     * @param string $letterType Letter type code
     * @return int Next sequential number
     */
    private function getNextSequentialNumber($letterType)
    {
        // Get the highest number for this letter type this year
        $year = date('Y');
        $query = "SELECT MAX(CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(letter_number, '/', 2), '/', -1) AS UNSIGNED)) as max_num
                  FROM letter_requests
                  WHERE letter_number LIKE ?
                  AND YEAR(created_at) = ?";

        $pattern = "%{$letterType}%/{$year}";
        $maxNum = fetchValue($query, [$pattern, $year]);

        return ($maxNum ?? 0) + 1;
    }

    /**
     * Get Roman numeral for month
     *
     * @param int $month Month number (1-12)
     * @return string Roman numeral
     */
    private function getRomanMonth($month)
    {
        $romanMonths = [
            1 => 'I', 'II', 'III', 'IV', 'V', 'VI',
            'VII', 'VIII', 'IX', 'X', 'XI', 'XII'
        ];

        return $romanMonths[$month] ?? 'I';
    }

    /**
     * Generate PDF for approved letter request
     *
     * @param int $requestId Letter request ID
     * @return bool Success status
     * @throws Exception If generation fails
     */
    public function generateLetterPDF($requestId)
    {
        $request = $this->letterRequestModel->findById($requestId);
        if (!$request) {
            throw new Exception('Letter request not found');
        }

        if ($request['status'] !== STATUS_APPROVED) {
            throw new Exception('Letter must be approved before PDF generation');
        }

        // Load letter template based on type
        $template = $this->loadLetterTemplate($request['letter_type_id']);
        if (!$template) {
            throw new Exception('Letter template not found');
        }

        // Prepare data for template
        $templateData = $this->prepareTemplateData($request);

        // Generate HTML content
        $htmlContent = $this->renderTemplate($template, $templateData);

        // Generate PDF
        $fileName = 'surat_' . $requestId . '_' . time() . '.pdf';
        $pdfPath = $this->pdfGenerator->generateFromHtml($htmlContent, $fileName);

        if (!$pdfPath) {
            throw new Exception('Failed to generate PDF');
        }

        // Update request with generated file path
        $this->letterRequestModel->updateGeneratedFile($requestId, $fileName);

        return true;
    }

    /**
     * Load letter template for given type
     *
     * @param int $letterTypeId Letter type ID
     * @return array|null Template data
     */
    private function loadLetterTemplate($letterTypeId)
    {
        $letterTypeModel = new LetterType();
        $letterType = $letterTypeModel->findById($letterTypeId);

        if (!$letterType) {
            return null;
        }

        // For now, return template based on letter type code
        // In a real application, this would load from database or file
        return [
            'type' => $letterType['code'],
            'template_file' => $this->getTemplateFileName($letterType['code'])
        ];
    }

    /**
     * Get template file name based on letter type code
     *
     * @param string $letterTypeCode Letter type code
     * @return string Template file name
     */
    private function getTemplateFileName($letterTypeCode)
    {
        $templates = [
            'SKD' => 'surat_keterangan_domisili.php',
            'SKU' => 'surat_keterangan_usaha.php',
            'SKTM' => 'surat_keterangan_tidak_mampu.php',
            'SKBM' => 'surat_keterangan_belum_menikah.php',
            'SIU' => 'surat_izin_usaha.php',
            'SIK' => 'surat_izin_kegiatan.php',
            'SRB' => 'surat_rekomendasi_beasiswa.php',
            'SK' => 'surat_keterangan.php',
            // Add more mappings as needed
        ];

        return $templates[$letterTypeCode] ?? 'surat_keterangan.php';
    }

    /**
     * Prepare data for template rendering
     *
     * @param array $request Letter request data
     * @return array Template data
     */
    private function prepareTemplateData($request)
    {
        // Decode request data
        $requestData = json_decode($request['request_data'], true) ?? [];

        // Get user data
        $userModel = new User();
        $user = $userModel->findById($request['user_id']);

        // Prepare template variables that match existing templates
        return array_merge($requestData, [
            'letter_number' => $request['letter_number'],
            'request' => [
                'user_full_name' => $user['full_name'] ?? '',
                'nik' => $user['nik'] ?? '',
                'address' => $user['address'] ?? '',
                'gender' => $requestData['gender'] ?? '',
                'birth_place' => $requestData['birth_place'] ?? '',
                'birth_date' => $requestData['birth_date'] ?? '',
                'religion' => $requestData['religion'] ?? '',
                'occupation' => $requestData['occupation'] ?? '',
                'warganegara' => $requestData['nationality'] ?? 'WNI',
            ],
            // Village information for templates
            'kabupaten' => 'Magelang',
            'kecamatan' => 'Grabag',
            'desa' => 'Kleteran',
            'alamat_desa' => 'Jl. Telaga Bleder Km.1 Grabag Magelang',
            'kepala_desa' => 'Muhammad Waris Zainal, S.Pd.',
            'keperluan' => $requestData['purpose'] ?? 'PERSYARATAN ADMINISTRASI',
            'alamat_domisili' => $requestData['domicile_address'] ?? $user['address'] ?? '',
        ]);
    }

    /**
     * Render template with data
     *
     * @param array $template Template info
     * @param array $data Template data
     * @return string Rendered HTML
     */
    private function renderTemplate($template, $data)
    {
        $templateFile = ROOT_DIR . '/templates/' . $template['template_file'];

        if (!file_exists($templateFile)) {
            throw new Exception("Template file not found: {$templateFile}");
        }

        // Extract data to make variables available in template
        extract($data);

        // Start output buffering
        ob_start();
        include $templateFile;
        return ob_get_clean();
    }

    /**
     * Process letter approval workflow
     *
     * @param int $requestId Letter request ID
     * @param int $adminId Admin user ID
     * @param string $notes Approval notes
     * @return bool Success status
     */
    public function approveLetter($requestId, $adminId, $notes = '')
    {
        // Update request status
        $success = $this->letterRequestModel->approve($requestId, $adminId, $notes);

        if ($success) {
            try {
                // Generate letter number
                $request = $this->letterRequestModel->findById($requestId);
                $letterNumber = $this->generateLetterNumber($request);
                $this->letterRequestModel->update($requestId, ['letter_number' => $letterNumber]);

                // Generate PDF
                $this->generateLetterPDF($requestId);

                return true;
            } catch (Exception $e) {
                error_log('Letter approval post-processing failed: ' . $e->getMessage());
                // Don't fail the approval if PDF generation fails
                return true;
            }
        }

        return false;
    }

    /**
     * Process letter rejection workflow
     *
     * @param int $requestId Letter request ID
     * @param int $adminId Admin user ID
     * @param string $notes Rejection notes
     * @return bool Success status
     */
    public function rejectLetter($requestId, $adminId, $notes = '')
    {
        return $this->letterRequestModel->reject($requestId, $adminId, $notes);
    }

    /**
     * Get letter file path for download
     *
     * @param int $requestId Letter request ID
     * @return string|null File path or null if not found
     */
    public function getLetterFilePath($requestId)
    {
        $request = $this->letterRequestModel->findById($requestId);

        if (!$request || empty($request['generated_file'])) {
            return null;
        }

        return $this->pdfGenerator->getFilePath($request['generated_file']);
    }
}
