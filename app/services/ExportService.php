<?php

/**
 * Export Service - Business Logic Layer
 * Handles data export functionality (Excel, CSV, etc.)
 *
 * ABIS - Aplikasi Desa Digital
 */

class ExportService
{
    /**
     * Export letter requests data to Excel format
     *
     * @param array $requests Letter requests data
     * @param array $filters Applied filters
     * @return string Excel file path
     */
    public function exportLetterRequestsToExcel($requests, $filters = [])
    {
        // Check if PhpSpreadsheet is available
        $spreadsheetPath = 'vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Spreadsheet.php';
        if (!file_exists($spreadsheetPath)) {
            throw new Exception('PhpSpreadsheet library not found. Please install with: composer require phpoffice/phpspreadsheet');
        }

        require_once $spreadsheetPath;
        require_once 'vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Writer/Xlsx.php';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Tanggal Pengajuan');
        $sheet->setCellValue('C1', 'Nama Pemohon');
        $sheet->setCellValue('D1', 'NIK');
        $sheet->setCellValue('E1', 'Jenis Surat');
        $sheet->setCellValue('F1', 'Status');
        $sheet->setCellValue('G1', 'Tanggal Disetujui');
        $sheet->setCellValue('H1', 'Admin Penyetuju');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E3F2FD']
            ]
        ];
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

        // Fill data
        $row = 2;
        foreach ($requests as $index => $request) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, date('d/m/Y', strtotime($request['created_at'])));
            $sheet->setCellValue('C' . $row, $request['user_full_name']);
            $sheet->setCellValue('D' . $row, $request['user_nik']);
            $sheet->setCellValue('E' . $row, $request['letter_type_name']);
            $sheet->setCellValue('F' . $row, $this->getStatusLabel($request['status']));
            $sheet->setCellValue('G' . $row, $request['approved_at'] ? date('d/m/Y', strtotime($request['approved_at'])) : '-');
            $sheet->setCellValue('H' . $row, $request['admin_name'] ?? '-');

            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Generate filename
        $filename = 'data_pengajuan_surat_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filepath = ROOT_DIR . '/storage/exports/' . $filename;

        // Ensure directory exists
        $exportDir = dirname($filepath);
        if (!is_dir($exportDir)) {
            mkdir($exportDir, 0755, true);
        }

        // Save file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filepath);

        return $filepath;
    }

    /**
     * Export user data to Excel format
     *
     * @param array $users User data
     * @return string Excel file path
     */
    public function exportUsersToExcel($users)
    {
        require_once 'vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Spreadsheet.php';
        require_once 'vendor/phpoffice/phpspreadsheet/src/PhpSpreadsheet/Writer/Xlsx.php';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama Lengkap');
        $sheet->setCellValue('C1', 'NIK');
        $sheet->setCellValue('D1', 'Email');
        $sheet->setCellValue('E1', 'No. HP');
        $sheet->setCellValue('F1', 'Alamat');
        $sheet->setCellValue('G1', 'Tanggal Registrasi');
        $sheet->setCellValue('H1', 'Status');

        // Style header
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E3F2FD']
            ]
        ];
        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

        // Fill data
        $row = 2;
        foreach ($users as $index => $user) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $user['full_name']);
            $sheet->setCellValue('C' . $row, $user['nik']);
            $sheet->setCellValue('D' . $row, $user['email']);
            $sheet->setCellValue('E' . $row, $user['phone']);
            $sheet->setCellValue('F' . $row, $user['address']);
            $sheet->setCellValue('G' . $row, date('d/m/Y', strtotime($user['created_at'])));
            $sheet->setCellValue('H' . $row, $user['is_active'] ? 'Aktif' : 'Tidak Aktif');

            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Generate filename
        $filename = 'data_pengguna_' . date('Y-m-d_H-i-s') . '.xlsx';
        $filepath = ROOT_DIR . '/storage/exports/' . $filename;

        // Ensure directory exists
        $exportDir = dirname($filepath);
        if (!is_dir($exportDir)) {
            mkdir($exportDir, 0755, true);
        }

        // Save file
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($filepath);

        return $filepath;
    }

    /**
     * Get status label for display
     *
     * @param string $status Status constant
     * @return string Human-readable status
     */
    private function getStatusLabel($status)
    {
        $labels = [
            STATUS_PENDING => 'Menunggu',
            STATUS_APPROVED => 'Disetujui',
            STATUS_REJECTED => 'Ditolak',
            STATUS_COMPLETED => 'Selesai'
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Export dashboard statistics to PDF report
     *
     * @param array $stats Dashboard statistics
     * @return string PDF file path
     */
    public function exportDashboardStatsToPDF($stats)
    {
        $pdfGenerator = new PDFGenerator();

        // Generate HTML content for stats report
        $htmlContent = $this->generateStatsReportHTML($stats);

        $filename = 'laporan_dashboard_' . date('Y-m-d_H-i-s') . '.pdf';

        return $pdfGenerator->generateFromHtml($htmlContent, $filename);
    }

    /**
     * Generate HTML content for statistics report
     *
     * @param array $stats Statistics data
     * @return string HTML content
     */
    private function generateStatsReportHTML($stats)
    {
        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .header { text-align: center; margin-bottom: 30px; }
                .stats-grid { display: table; width: 100%; margin-bottom: 30px; }
                .stats-row { display: table-row; }
                .stats-cell { display: table-cell; padding: 10px; border: 1px solid #ddd; }
                .stats-header { font-weight: bold; background-color: #f5f5f5; }
                h2 { color: #333; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Laporan Dashboard Sistem Surat Desa</h1>
                <p>Tanggal: ' . date('d F Y') . '</p>
            </div>

            <h2>Statistik Pengguna</h2>
            <div class="stats-grid">
                <div class="stats-row">
                    <div class="stats-cell stats-header">Total Pengguna</div>
                    <div class="stats-cell stats-header">Pengguna Aktif</div>
                    <div class="stats-cell stats-header">Pengguna Bulan Ini</div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell">' . ($stats['users']['total'] ?? 0) . '</div>
                    <div class="stats-cell">' . ($stats['users']['active'] ?? 0) . '</div>
                    <div class="stats-cell">' . ($stats['users']['this_month'] ?? 0) . '</div>
                </div>
            </div>

            <h2>Statistik Pengajuan Surat</h2>
            <div class="stats-grid">
                <div class="stats-row">
                    <div class="stats-cell stats-header">Total Pengajuan</div>
                    <div class="stats-cell stats-header">Menunggu</div>
                    <div class="stats-cell stats-header">Disetujui</div>
                    <div class="stats-cell stats-header">Ditolak</div>
                </div>
                <div class="stats-row">
                    <div class="stats-cell">' . ($stats['requests']['total'] ?? 0) . '</div>
                    <div class="stats-cell">' . ($stats['requests']['pending'] ?? 0) . '</div>
                    <div class="stats-cell">' . ($stats['requests']['approved'] ?? 0) . '</div>
                    <div class="stats-cell">' . ($stats['requests']['rejected'] ?? 0) . '</div>
                </div>
            </div>
        </body>
        </html>';

        return $html;
    }
}
