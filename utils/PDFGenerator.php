<?php
/**
 * PDF Generator Utility
 * Menggunakan DomPDF untuk generate PDF dari HTML template
 * ABIS - Aplikasi Desa Digital
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class PDFGenerator {
    private $dompdf;
    private $templatesDir;
    private $outputDir;

    public function __construct() {
        $this->templatesDir = __DIR__ . '/../templates/';
        $this->outputDir = __DIR__ . '/../generated_pdfs/';

        // Initialize DomPDF with options
        $options = new Options();
        $options->set('isRemoteEnabled', true); // Enable image loading
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('defaultFont', 'Arial');
        $options->set('defaultPaperSize', 'A4');
        $options->set('defaultPaperOrientation', 'portrait');
        $options->set('chroot', __DIR__ . '/../'); // Allow access to project root

        $this->dompdf = new Dompdf($options);
    }

    /**
     * Generate PDF from HTML content
     * @param string $html HTML content
     * @param string $filename Output filename
     * @return string|null Generated file path or null on failure
     */
    public function generateFromHtml($html, $filename) {
        try {
            // Load HTML
            $this->dompdf->loadHtml($html);

            // Render PDF
            $this->dompdf->render();

            // Ensure output directory exists
            if (!is_dir($this->outputDir)) {
                mkdir($this->outputDir, 0755, true);
            }

            // Generate unique filename
            $uniqueFilename = $this->generateUniqueFilename($filename);
            $filePath = $this->outputDir . $uniqueFilename;

            // Save PDF to file
            file_put_contents($filePath, $this->dompdf->output());

            return $uniqueFilename;

        } catch (Exception $e) {
            error_log("PDF Generation Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate PDF from template file
     * @param string $templateName Template filename (without .php extension)
     * @param array $data Data to pass to template
     * @param string $filename Output filename
     * @return string|null Generated file path or null on failure
     */
    public function generateFromTemplate($templateName, $data, $filename) {
        try {
            // Load template
            $templateFile = $this->templatesDir . $templateName . '.php';

            if (!file_exists($templateFile)) {
                error_log("Template not found: " . $templateFile);
                return null;
            }

            // Extract data for template
            extract($data);

            // Capture template output
            ob_start();
            include $templateFile;
            $html = ob_get_clean();

            return $this->generateFromHtml($html, $filename);

        } catch (Exception $e) {
            error_log("Template PDF Generation Error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate unique filename to avoid conflicts
     * @param string $filename Original filename
     * @return string Unique filename
     */
    private function generateUniqueFilename($filename) {
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        $basename = pathinfo($filename, PATHINFO_FILENAME);

        // Add timestamp and random string
        $timestamp = date('Ymd_His');
        $random = substr(md5(uniqid()), 0, 6);

        return $basename . '_' . $timestamp . '_' . $random . '.' . $extension;
    }

    /**
     * Get full file path for download
     * @param string $filename Generated filename
     * @return string Full file path
     */
    public function getFilePath($filename) {
        return $this->outputDir . $filename;
    }

    /**
     * Check if file exists
     * @param string $filename Generated filename
     * @return bool
     */
    public function fileExists($filename) {
        return file_exists($this->getFilePath($filename));
    }

    /**
     * Delete generated PDF file
     * @param string $filename Generated filename
     * @return bool
     */
    public function deleteFile($filename) {
        $filePath = $this->getFilePath($filename);
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }

    /**
     * Clean up old files (older than specified days)
     * @param int $daysOld Files older than this many days will be deleted
     * @return int Number of files deleted
     */
    public function cleanupOldFiles($daysOld = 30) {
        $count = 0;
        $files = glob($this->outputDir . '*.pdf');

        foreach ($files as $file) {
            if (filemtime($file) < strtotime("-{$daysOld} days")) {
                if (unlink($file)) {
                    $count++;
                }
            }
        }

        return $count;
    }

    /**
     * Get file size in human readable format
     * @param string $filename Generated filename
     * @return string Formatted file size
     */
    public function getFileSize($filename) {
        $filePath = $this->getFilePath($filename);
        if (file_exists($filePath)) {
            $bytes = filesize($filePath);
            $units = ['B', 'KB', 'MB', 'GB'];
            $i = 0;
            while ($bytes >= 1024 && $i < count($units) - 1) {
                $bytes /= 1024;
                $i++;
            }
            return round($bytes, 2) . ' ' . $units[$i];
        }
        return '0 B';
    }
}


