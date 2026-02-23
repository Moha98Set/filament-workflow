<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class LabelPdfController extends Controller
{
    public function generate(Request $request)
    {
        $ids = explode(',', $request->query('ids', ''));
        $ids = array_filter(array_map('intval', $ids));

        if (empty($ids)) {
            abort(400, 'شناسه دستگاهی ارسال نشده.');
        }

        $devices = Device::with('assignedToRegistration')
            ->whereIn('id', $ids)
            ->get();

        // بررسی اعتبار دستگاه‌ها
        $invalid = $devices->filter(function ($device) {
            return empty($device->serial_number) || is_null($device->assigned_to_registration_id);
        });

        if ($invalid->isNotEmpty()) {
            $names = $invalid->pluck('serial_number')->map(fn($s) => $s ?: '(بدون سریال)')->join('، ');
            abort(422, "دستگاه‌های زیر سریال ندارند یا به متقاضی اختصاص داده نشده‌اند: {$names}");
        }

        // ساخت بارکدها
        $generator = new BarcodeGeneratorPNG();
        $barcodes = [];

        foreach ($devices as $device) {
            $png = $generator->getBarcode(
                $device->serial_number,
                BarcodeGeneratorPNG::TYPE_CODE_128,
                1,
                40,
                [0, 0, 0]
            );
            $barcodes[$device->serial_number] = 'data:image/png;base64,' . base64_encode($png);
        }

        $pdf = Pdf::loadView('filament.pdf.device-labels', compact('devices', 'barcodes'))
            ->setPaper('A4', 'portrait')
            ->setOptions([
                'defaultFont'          => 'Vazirmatn',
                'isRemoteEnabled'      => true,
                'isHtml5ParserEnabled' => true,
                'dpi'                  => 150,
                'chroot'               => public_path(),
            ]);

        $filename = 'device-labels-' . now()->format('Ymd-His') . '.pdf';

        return $pdf->download($filename);
    }
}