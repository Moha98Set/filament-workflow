<?php

namespace App\Traits;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

trait ExportableTable
{
    public function exportToExcel(): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $query = $this->getFilteredTableQuery();
        $records = $query->get();

        if ($records->isEmpty()) {
            \Filament\Notifications\Notification::make()
                ->warning()
                ->title('داده‌ای برای خروجی وجود ندارد')
                ->send();
            return response()->streamDownload(function () {}, 'empty.xlsx');
        }

        $columns = $this->getExportColumns();
        $fileName = $this->getExportFileName();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setRightToLeft(true);

        // هدر
        $col = 1;
        foreach ($columns as $key => $label) {
            $cell = $sheet->getCellByColumnAndRow($col, 1);
            $cell->setValue($label);
            $col++;
        }

        // استایل هدر
        $lastCol = count($columns);
        $headerRange = 'A1:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastCol) . '1';
        $sheet->getStyle($headerRange)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F97316']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // دیتا
        $row = 2;
        foreach ($records as $record) {
            $col = 1;
            foreach ($columns as $key => $label) {
                $value = $this->getExportCellValue($record, $key);
                $sheet->getCellByColumnAndRow($col, $row)->setValue($value);
                $col++;
            }
            $row++;
        }

        // استایل دیتا
        $dataRange = 'A2:' . \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($lastCol) . ($row - 1);
        $sheet->getStyle($dataRange)->applyFromArray([
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN]],
        ]);

        // عرض خودکار
        for ($i = 1; $i <= $lastCol; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
        }

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $fileName . '.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}