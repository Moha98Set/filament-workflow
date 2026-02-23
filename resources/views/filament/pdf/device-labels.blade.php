<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        @font-face {
            font-family: 'Vazirmatn';
            src: url('{{ public_path("fonts/Vazirmatn-Regular.ttf") }}') format('truetype');
            font-weight: normal;
        }
        @font-face {
            font-family: 'Vazirmatn';
            src: url('{{ public_path("fonts/Vazirmatn-Bold.ttf") }}') format('truetype');
            font-weight: bold;
        }

        body {
            font-family: 'Vazirmatn', sans-serif;
            direction: rtl;
            width: 21cm;
            padding: 1.30cm 0.70cm;
        }

        table.label-grid {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0.25cm 0;
        }

        td.label-cell {
            width: 6.37cm;
            height: 3.39cm;
            border: 1px solid #bbb;
            border-radius: 6px;
            padding: 0.15cm 0.18cm;
            vertical-align: middle;
            overflow: hidden;
        }

        td.label-empty {
            width: 6.37cm;
            height: 3.39cm;
        }

        .lbl-header {
            display: table;
            width: 100%;
            margin-bottom: 0.07cm;
        }
        .lbl-logo-wrap { display: table-cell; text-align: left; vertical-align: middle; }
        .lbl-title-wrap { display: table-cell; text-align: right; vertical-align: middle; }
        .lbl-logo { height: 0.55cm; width: auto; }
        .lbl-title { font-size: 7pt; font-weight: bold; color: #222; }

        .lbl-body { font-size: 6.5pt; line-height: 1.6; color: #222; margin-bottom: 0.06cm; }
        .lbl-row { display: table; width: 100%; }
        .lbl-key { display: table-cell; color: #666; white-space: nowrap; padding-left: 0.08cm; }
        .lbl-val { display: table-cell; font-weight: bold; text-align: left; direction: ltr; }

        .lbl-barcode { text-align: center; margin-top: 0.04cm; }
        .lbl-barcode img { height: 0.60cm; max-width: 100%; }
        .lbl-barcode-text { font-size: 5.5pt; color: #444; direction: ltr; letter-spacing: 0.4px; }

        tr.label-row-gap td { height: 0; padding: 0; border: none; line-height: 0; font-size: 0; }
    </style>
</head>
<body>
@php
    $chunks = $devices->chunk(3);
    $logoPath = public_path('images/logo.png');
@endphp

<table class="label-grid">
    @foreach($chunks as $row)
        <tr>
            @php $cells = $row->values(); @endphp

            @for($i = 0; $i < 3; $i++)
                @if(isset($cells[$i]))
                    @php $device = $cells[$i]; $reg = $device->assignedToRegistration; @endphp
                    <td class="label-cell">
                        {{-- هدر --}}
                        <div class="lbl-header">
                            <div class="lbl-title-wrap">
                                <span class="lbl-title">ویرامپ</span>
                            </div>
                            <div class="lbl-logo-wrap">
                                <img class="lbl-logo" src="{{ $logoPath }}" alt="logo">
                            </div>
                        </div>

                        {{-- اطلاعات --}}
                        <div class="lbl-body">
                            <div class="lbl-row">
                                <span class="lbl-key">نام:</span>
                                <span class="lbl-val" style="direction:rtl">{{ $reg->last_name ?? '—' }}</span>
                            </div>
                            <div class="lbl-row">
                                <span class="lbl-key">تراکتور:</span>
                                <span class="lbl-val" style="direction:rtl">
                                    {{ ($reg->tractor_system ?? '') . ' ' . ($reg->tractor_type ?? '') ?: '—' }}
                                </span>
                            </div>
                            <div class="lbl-row">
                                <span class="lbl-key">سریال:</span>
                                <span class="lbl-val">{{ $device->serial_number }}</span>
                            </div>
                        </div>

                        {{-- بارکد --}}
                        <div class="lbl-barcode">
                            <img src="{{ $barcodes[$device->serial_number] ?? '' }}" alt="barcode">
                            <div class="lbl-barcode-text">{{ $device->serial_number }}</div>
                        </div>
                    </td>
                @else
                    <td class="label-empty"></td>
                @endif
            @endfor
        </tr>
    @endforeach
</table>
</body>
</html>