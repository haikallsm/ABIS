<?php
require_once 'vendor/autoload.php';

try {
    if (class_exists('PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        echo "✅ PhpSpreadsheet is available and working\n";

        $spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Test Export');
        $sheet->setCellValue('B1', 'Success');

        echo "✅ Basic PhpSpreadsheet functionality works\n";

        // Test writing to file
        $writer = PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        $tempFile = tempnam(sys_get_temp_dir(), 'test_excel');
        $writer->save($tempFile);

        if (file_exists($tempFile)) {
            echo "✅ Excel file creation works (size: " . filesize($tempFile) . " bytes)\n";
            unlink($tempFile);
        } else {
            echo "❌ Excel file creation failed\n";
        }

    } else {
        echo "❌ PhpSpreadsheet not found\n";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
