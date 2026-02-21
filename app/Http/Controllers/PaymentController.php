<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private string $merchant;
    private string $baseUrl = 'https://gateway.zibal.ir';

    public function __construct()
    {
        $this->merchant = config('services.zibal.merchant', 'zibal');
    }

    /**
     * شروع پرداخت — بعد از ثبت‌نام
     */
    public function create(Request $request)
    {
        $registration = Registration::where('national_id', $request->national_id)
            ->latest()
            ->firstOrFail();

        $tractorCount = is_array($registration->tractors) ? count($registration->tractors) : 1;
        $amount = $tractorCount * 110000000; // ریال (۱۱ میلیون تومان)

        try {
            $response = Http::post("{$this->baseUrl}/v1/request", [
                'merchant' => $this->merchant,
                'amount' => $amount,
                'callbackUrl' => route('payment.callback'),
                'description' => "پرداخت دستگاه ویرامپ — {$registration->full_name}",
                'orderId' => "REG-{$registration->id}",
            ]);

            $data = $response->json();

            if ($data['result'] == 100) {
                $registration->update([
                    'payment_track_id' => $data['trackId'],
                    'payment_amount' => $amount,
                    'payment_status' => 'pending',
                    'payment_method' => 'online',
                ]);

                return redirect("{$this->baseUrl}/start/{$data['trackId']}");
            }

            Log::error('Zibal request failed', $data);
            return redirect()->route('payment.failed')
                ->with('error', 'خطا در اتصال به درگاه: ' . ($data['message'] ?? 'خطای نامشخص'));

        } catch (\Exception $e) {
            Log::error('Zibal connection error: ' . $e->getMessage());
            return redirect()->route('payment.failed')
                ->with('error', 'خطا در اتصال به درگاه پرداخت');
        }
    }

    /**
     * Callback — بازگشت از درگاه
     */
    public function callback(Request $request)
    {
        $trackId = $request->query('trackId');
        $success = $request->query('success');
        $status = $request->query('status');

        $registration = Registration::where('payment_track_id', $trackId)->first();

        if (!$registration) {
            return redirect()->route('payment.failed')
                ->with('error', 'تراکنش یافت نشد');
        }

        if ($success == 1) {
            // Verify پرداخت
            try {
                $response = Http::post("{$this->baseUrl}/v1/verify", [
                    'merchant' => $this->merchant,
                    'trackId' => $trackId,
                ]);

                $data = $response->json();

                if ($data['result'] == 100 && $data['status'] == 1) {
                    $registration->update([
                        'payment_status' => 'paid',
                        'payment_ref_number' => $data['refNumber'] ?? null,
                        'payment_verified_at' => now(),
                    ]);

                    return redirect()->route('payment.success')
                        ->with('registration_id', $registration->id)
                        ->with('ref_number', $data['refNumber'] ?? '—')
                        ->with('amount', $registration->payment_amount);
                }

                // پرداخت شده ولی verify نشده
                $registration->update(['payment_status' => 'unverified']);
                Log::warning('Zibal verify failed', $data);

                return redirect()->route('payment.failed')
                    ->with('error', 'پرداخت تایید نشد. در صورت کسر وجه، طی ۷۲ ساعت به حساب شما برمی‌گردد.');

            } catch (\Exception $e) {
                Log::error('Zibal verify error: ' . $e->getMessage());
                return redirect()->route('payment.failed')
                    ->with('error', 'خطا در تایید پرداخت');
            }
        }

        // پرداخت ناموفق
        $statusMsg = match((int)$status) {
            -1 => 'در انتظار پرداخت',
            -2 => 'خطای داخلی',
            3 => 'لغو شده توسط کاربر',
            4 => 'شماره کارت نامعتبر',
            default => 'پرداخت ناموفق',
        };

        $registration->update(['payment_status' => 'failed']);

        return redirect()->route('payment.failed')
            ->with('error', $statusMsg);
    }
}