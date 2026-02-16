<div style="direction: rtl; font-family: Vazirmatn, sans-serif;">
    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead>
            <tr style="background: #f3f4f6;">
                <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: right;">#</th>
                <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: right;">نام مشتری</th>
                <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: right;">تلفن</th>
                <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: right;">سریال دستگاه</th>
                <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: right;">شهرستان</th>
                <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: right;">وضعیت</th>
                <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: right;">تاریخ</th>
                <th style="padding: 10px; border: 1px solid #e5e7eb; text-align: right;">عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($registrations as $index => $reg)
            <tr style="{{ $index % 2 === 0 ? 'background: white;' : 'background: #f9fafb;' }}">
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $index + 1 }}</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $reg->full_name }}</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb; direction: ltr; text-align: right;">{{ $reg->phone }}</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $reg->assignedDevice?->serial_number ?? '—' }}</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $reg->city }}</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">
                    @if($type === 'installed')
                        <span style="background: #dcfce7; color: #166534; padding: 2px 8px; border-radius: 12px; font-size: 12px;">نصب شده</span>
                    @else
                        <span style="background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 12px; font-size: 12px;">در انتظار نصب</span>
                    @endif
                </td>
                <td style="padding: 10px; border: 1px solid #e5e7eb;">{{ $reg->created_at->format('Y/m/d') }}</td>
                <td style="padding: 10px; border: 1px solid #e5e7eb; text-align: center;">
                    @if($type === 'pending')
                    <form method="POST" action="/admin/remove-installer/{{ $reg->id }}" style="display: inline;">
                        @csrf
                        <button type="submit" onclick="return confirm('آیا مطمئنید؟ مشتری به مرحله انتقال به نصاب برمی‌گردد.')" style="background: #fecaca; color: #991b1b; border: none; padding: 4px 12px; border-radius: 8px; cursor: pointer; font-family: Vazirmatn, sans-serif; font-size: 12px; font-weight: 600;">
                            حذف
                        </button>
                    </form>
                    @else
                        —
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div style="margin-top: 12px; padding: 8px; background: #f3f4f6; border-radius: 8px; text-align: center; font-size: 13px; color: #6b7280;">
        مجموع: {{ $registrations->count() }} مورد
    </div>
</div>