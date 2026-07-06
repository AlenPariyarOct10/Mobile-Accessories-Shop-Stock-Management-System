<?php

namespace App\Http\Controllers;

use App\Models\CompanySetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CompanySettingController extends Controller
{
    public function edit(): View
    {
        $setting = CompanySetting::firstOrCreate([
            'id' => 1,
        ], [
            'company_name' => config('app.name', 'Stock Management System'),
        ]);

        return view('company_settings.edit', compact('setting'));
    }

    public function update(Request $request): RedirectResponse
    {
        $setting = CompanySetting::firstOrCreate([
            'id' => 1,
        ], [
            'company_name' => config('app.name', 'Stock Management System'),
        ]);

        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:191'],
            'owner_name' => ['nullable', 'string', 'max:191'],
            'company_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'phone' => ['nullable', 'string', 'max:191'],
            'email' => ['nullable', 'email', 'max:191'],
            'address' => ['nullable', 'string', 'max:191'],
            'footer_text' => ['nullable', 'string', 'max:191'],
        ]);

        if ($request->hasFile('company_logo')) {
            if ($setting->company_logo) {
                Storage::disk('public')->delete($setting->company_logo);
            }

            $data['company_logo'] = $request->file('company_logo')->store('company', 'public');
        }

        $setting->update($data);

        return redirect()->route('company-settings.edit')->with('success', 'Company settings updated successfully.');
    }
}
