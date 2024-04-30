<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . 'libraries/Excel/PHPExcel/IOFactory.php';

if (!function_exists('export_excel')) {
    function export_excel($sheets, $filename = 'export_data.xlsx')
    {
        // Turn on output buffering
        ob_start();

        $objPHPExcel = new PHPExcel();

        foreach ($sheets as $sheetData) {
            $data = $sheetData['data'];
            $sheetName = $sheetData['name'];

            // Create a new sheet
            $objPHPExcel->createSheet();
            $objPHPExcel->setActiveSheetIndex($objPHPExcel->getSheetCount() - 1);
            $sheet = $objPHPExcel->getActiveSheet();

            // Set the data in the current sheet
            $sheet->fromArray($data, null, 'A1');

            // Set the sheet name
            $sheet->setTitle($sheetName);
        }

        // Create the writer and save the file
        $writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

        // Clear any previous output in the buffer
        ob_end_clean();

        // Set headers to download the file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        // Output the file
        $writer->save('php://output');
        exit;
    }
}
