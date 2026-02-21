<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private string $merchant;

    public function __construct()
    {
        $this->merchant = config('services.zibal.merchant', 'zibal');
    }

    public function create(Request $request)
    {
        $registration = Registration::where('national_id', $request->national_id)
            ->latest()
            ->firstOrFail();

        $tractorCount = is_array($registration->tractors) ? count($registration->tractors) : 1;
        $amount = $tractorCount * 110000000; // ریال

        $parameters = [
            'merchant' => $this->merchant,
            'amount' => $amount,
            'callbackUrl' => route('payment.callback'),
            'description' => "پرداخت دستگاه ویرامپ — {$registration->full_name}",
            'orderId' => "REG-{$registration->id}",
        ];

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://gateway.zibal.ir/v1/request');
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            Log::info('Zibal request', [
                'params' => $parameters,
                'response' => $result,
                'httpCode' => $httpCode,
                'curlError' => $curlError,
            ]);

            if ($curlError) {
                return redirect()->route('payment.failed')
                    ->with('error', 'خطا در اتصال به درگاه: ' . $curlError);
            }

            $data = json_decode($result, true);

            if ($data && $data['result'] == 100) {
                $registration->update([
                    'payment_track_id' => (string)$data['trackId'],
                    'payment_amount' => $amount,
                    'payment_status' => 'pending',
                    'payment_method' => 'online',
                ]);

                return redirect("https://gateway.zibal.ir/start/{$data['trackId']}");
            }

            Log::error('Zibal request failed', ['data' => $data]);
            return redirect()->route('payment.failed')
                ->with('error', 'خطا در ایجاد تراکنش: ' . ($data['message'] ?? 'خطای نامشخص'));

        } catch (\Exception $e) {
            Log::error('Zibal exception: ' . $e->getMessage());
            return redirect()->route('payment.failed')
                ->with('error', 'خطا در اتصال به درگاه پرداخت');
        }
    }

    public function callback(Request $request)
    {
        $trackId = $request->query('trackId');
        $success = $request->query('success');
        $status = $request->query('status');

        Log::info('Zibal callback', [
            'trackId' => $trackId,
            'success' => $success,
            'status' => $status,
        ]);

        $registration = Registration::where('payment_track_id', (string)$trackId)->first();

        if (!$registration) {
            Log::error('Zibal callback: registration not found', ['trackId' => $trackId]);
            return redirect()->route('payment.failed')
                ->with('error', 'تراکنش یافت نشد');
        }

        if ($success == 1) {
            $parameters = [
                'merchant' => $this->merchant,
                'trackId' => $trackId,
            ];

            try {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://gateway.zibal.ir/v1/verify');
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30);

                $result = curl_exec($ch);
                curl_close($ch);

                $data = json_decode($result, true);

                Log::info('Zibal verify response', ['data' => $data]);

                if ($data && $data['result'] == 100) {
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

                $registration->update(['payment_status' => 'unverified']);
                return redirect()->route('payment.failed')
                    ->with('error', 'پرداخت تایید نشد. کد وضعیت: ' . ($data['status'] ?? '—'));

            } catch (\Exception $e) {
                Log::error('Zibal verify exception: ' . $e->getMessage());
                return redirect()->route('payment.failed')
                    ->with('error', 'خطا در تایید پرداخت');
            }
        }

        $statusMsg = match((int)$status) {
            -1 => 'در انتظار پرداخت',
            -2 => 'خطای داخلی',
            3 => 'لغو شده توسط کاربر',
            4 => 'شماره کارت نامعتبر',
            default => 'پرداخت ناموفق (کد: ' . $status . ')',
        };

        $registration->update(['payment_status' => 'failed']);

        return redirect()->route('payment.failed')
            ->with('error', $statusMsg);
    }
}