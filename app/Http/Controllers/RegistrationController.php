<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'province' => 'required|string',
                'organization' => 'required|string',
                'full_name' => 'required|string|max:255',
                'phone' => 'required|string|size:11',
                'national_id' => 'required|string|size:10|unique:registrations,national_id',
                'city' => 'required|string|max:100',
                'district' => 'required|string|max:100',
                'village' => 'required|string|max:100',
                'installation_address' => 'required|string',
                'tractors' => 'required|array|min:1',
                'payment_method' => 'required|in:online,transfer',
                'contract_accepted' => 'required|in:1',
            ]);

            $organizationMap = [
                'جهاد کشاورزی' => 'jihad',
                'صنعت' => 'industry',
                'شیلات' => 'fisheries',
            ];

            $organizationValue = $organizationMap[$validated['organization']] ?? null;

            if (!$organizationValue) {
                return back()->with('error', 'سازمان انتخاب‌شده معتبر نیست.');
            }

            $tractors = [];
            foreach ($request->tractors as $index => $tractor) {
                $tractorData = [
                    'system' => $tractor['system'] ?? null,
                    'type' => $tractor['type'] ?? null,
                    'cylinders' => $tractor['cylinders'] ?? null,
                ];

                if ($request->hasFile("tractors.{$index}.green_card")) {
                    $file = $request->file("tractors.{$index}.green_card");
                    $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('green-cards', $filename, 'public');
                    $tractorData['green_card_path'] = $path;
                }

                $tractors[] = $tractorData;
            }

            $tractorCount = count($tractors);
            $paymentAmount = $tractorCount * 110000000; // ریال

            $registration = Registration::create([
                'province' => $validated['province'],
                'organization' => $organizationValue,
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'national_id' => $validated['national_id'],
                'city' => $validated['city'],
                'district' => $validated['district'],
                'village' => $validated['village'],
                'installation_address' => $validated['installation_address'],
                'tractors' => $tractors,
                'status' => 'pending',
                'payment_method' => $validated['payment_method'],
                'payment_amount' => $paymentAmount,
                'contract_accepted' => true,
                'contract_accepted_at' => now(),
            ]);

            // آپلود فیش بانکی
            if ($request->hasFile('payment_receipt')) {
                $file = $request->file('payment_receipt');
                $path = $file->storeAs('payment-receipts', time() . '_' . $file->getClientOriginalName(), 'public');
                $registration->update(['payment_receipt' => $path]);
            }

            // اگه پرداخت آنلاین → درگاه زیبال
            if ($validated['payment_method'] === 'online') {
                return app(PaymentController::class)->create(
                    new Request(['national_id' => $registration->national_id])
                );
            }

            return redirect()
                ->route('client.register.success')
                ->with('success', 'ثبت‌نام شما با موفقیت انجام شد!');

        } catch (\Exception $e) {
            return back()->with('error', 'خطا در ثبت اطلاعات: ' . $e->getMessage());
        }
    }
}