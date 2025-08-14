<?php

// Include PhpSpreadsheet library
require 'vendor/autoload.php';

// Inisialisasi objek Spreadsheet
$spreadsheet = new PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Set judul kolom
$sheet->setCellValue('A1', 'NIS');
$sheet->setCellValue('B1', 'Nama Lengkap');
$sheet->setCellValue('C1', 'Unit');
$sheet->setCellValue('D1', 'Kelas');
$sheet->setCellValue('E1', 'Kelas Pondok');
$sheet->setCellValue('F1', 'Alamat');
$sheet->setCellValue('G1', 'Nama Ayah');
$sheet->setCellValue('H1', 'Nama Ibu');
$sheet->setCellValue('I1', 'No. Whatsapp Ortu');

// Set style header
$headerStyle = [
    'font' => ['bold' => true],
    'alignment' => ['horizontal' => PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
    'borders' => ['allBorders' => ['borderStyle' => PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
    'fill' => ['fillType' => PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '4188e0']],
];

$sheet->getStyle('A1:I1')->applyFromArray($headerStyle);

// Data student
$rowIndex = 2; // Mulai dari baris kedua
foreach ($student as $row) {
    $sheet->setCellValue('A' . $rowIndex, $row['student_nis']);
    $sheet->setCellValue('B' . $rowIndex, $row['student_full_name']);
    $sheet->setCellValue('C' . $rowIndex, $row['majors_short_name']);
    $sheet->setCellValue('D' . $rowIndex, $row['class_name']);
    $sheet->setCellValue('E' . $rowIndex, $row['madin_name']);
    $sheet->setCellValue('F' . $rowIndex, $row['student_address']);
    $sheet->setCellValue('G' . $rowIndex, $row['student_name_of_father']);
    $sheet->setCellValue('H' . $rowIndex, $row['student_name_of_mother']);
    $phoneNumber = $row['student_parent_phone'];
    $sheet->setCellValueExplicit('I' . $rowIndex, $phoneNumber, PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

    $rowIndex++;
}

// Set auto size kolom
foreach (range('A', 'I') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

// Set nama file
$filename = 'Template_Update_No_WhatsApp_Ortu_' . $majors['majors_short_name'] . '_Kelas_' . $kelas['class_name'];

// Set header untuk file Excel
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
header('Cache-Control: max-age=0');

// Ekspor file Excel
$writer = new PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save('php://output');
