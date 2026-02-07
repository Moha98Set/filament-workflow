<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientRegistrationController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|unique:clients,phone_number',
            'birth_date' => 'nullable|date',
        ]);

        \App\Models\Client::create([
            ...$validated,
            'is_new' => true,
        ]);

        return back()->with('success', 'اطلاعات شما با موفقیت ثبت شد و در صف بررسی قرار گرفت.');
    }
}
