<?php

namespace App\Http\Controllers;

use App\Models\Registration;
use Illuminate\Http\Request;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate incoming request
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
            ]);

            // Map Persian organization values to ENUM values
            $organizationMap = [
                'جهاد کشاورزی' => 'jihad',
                'صنعت' => 'industry',
                'شیلات' => 'fisheries',
            ];

            $organizationValue = $organizationMap[$validated['organization']] ?? null;

            if (!$organizationValue) {
                return back()->with('error', 'سازمان انتخاب‌شده معتبر نیست.');
            }

            // Process tractors data
            $tractors = [];

            foreach ($request->tractors as $index => $tractor) {

                $tractorData = [
                    'system' => $tractor['system'] ?? null,
                    'type' => $tractor['type'] ?? null,
                    'cylinders' => $tractor['cylinders'] ?? null,
                ];

                // Upload green card file if exists
                if ($request->hasFile("tractors.{$index}.green_card")) {
                    $file = $request->file("tractors.{$index}.green_card");
                    $filename = time() . '_' . $index . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('green-cards', $filename, 'public');
                    $tractorData['green_card_path'] = $path;
                }

                $tractors[] = $tractorData;
            }

            // Create registration record
            Registration::create([
                'province' => $validated['province'],
                'organization' => $organizationValue, // Save ENUM value
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'national_id' => $validated['national_id'],
                'city' => $validated['city'],
                'district' => $validated['district'],
                'village' => $validated['village'],
                'installation_address' => $validated['installation_address'],
                'tractors' => $tractors,
                'status' => 'pending',
            ]);

            return redirect()
                ->route('client.register.success')
                ->with('success', 'ثبت‌نام شما با موفقیت انجام شد!');

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'خطا در ثبت اطلاعات: ' . $e->getMessage()
            );
        }
    }
}