<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::query()->orderBy('group')->orderBy('setting_key')->get()->groupBy('group');

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request): RedirectResponse
    {
        foreach ($request->input('settings', []) as $id => $value) {
            $setting = Setting::query()->find($id);

            if (! $setting) {
                continue;
            }

            $setting->update([
                'setting_value' => is_array($value) ? json_encode($value) : (string) $value,
            ]);
        }

        ActivityLog::record($request->user(), 'update', 'user', 'Updated portal settings.');

        return back()->with('success', 'Settings updated successfully.');
    }
}
