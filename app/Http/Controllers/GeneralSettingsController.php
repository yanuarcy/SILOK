<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\GeneralSetting;

class GeneralSettingsController extends Controller
{
    public function index()
    {
        $settings = GeneralSetting::getSettings();
        $type_menu = "Settings";
        return view('Settings.General', compact('settings', 'type_menu'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_title' => 'required|string|max:255',
            'organization_name' => 'required|string|max:255',
            'organization_address' => 'required|string|max:500',
            'site_name' => 'nullable|string|max:255',
            'site_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'site_favicon' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg,ico|max:1024',
        ]);

        try {
            $settings = GeneralSetting::getSettings();

            // Handle logo upload
            if ($request->hasFile('site_logo')) {
                // Delete old logo
                if ($settings->site_logo && Storage::disk('public')->exists($settings->site_logo)) {
                    Storage::disk('public')->delete($settings->site_logo);
                }

                $logoPath = $request->file('site_logo')->store('logos', 'public');
                $settings->site_logo = $logoPath;
            }

            // Handle favicon upload
            if ($request->hasFile('site_favicon')) {
                // Delete old favicon
                if ($settings->site_favicon && Storage::disk('public')->exists($settings->site_favicon)) {
                    Storage::disk('public')->delete($settings->site_favicon);
                }

                $faviconPath = $request->file('site_favicon')->store('favicons', 'public');
                $settings->site_favicon = $faviconPath;
            }

            // Update other fields
            $settings->site_title = $request->site_title;
            $settings->organization_name = $request->organization_name;
            $settings->organization_address = $request->organization_address;
            $settings->site_name = $request->site_name;

            $settings->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Settings berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
