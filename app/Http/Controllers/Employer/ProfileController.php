<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('employer.profile.edit', [
            'employer' => auth()->user()->load('employerProfile.industry'),
            'industries' => \App\Models\Industry::query()->where('is_active', true)->orderBy('industry_name')->get(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $employer = $user->employerProfile;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'company_name' => ['required', 'string', 'max:255', 'unique:employers,company_name,'.$employer->id],
            'industry_id' => ['nullable', 'exists:industries,id'],
            'company_size' => ['nullable', 'string', 'max:20'],
            'website' => ['nullable', 'url', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address_line1' => ['nullable', 'string', 'max:255'],
            'address_line2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'province' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'company_logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $employer->update([
            'company_name' => $validated['company_name'],
            'industry_id' => $validated['industry_id'] ?? null,
            'company_size' => $validated['company_size'] ?? null,
            'website' => $validated['website'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'address_line1' => $validated['address_line1'] ?? null,
            'address_line2' => $validated['address_line2'] ?? null,
            'city' => $validated['city'] ?? null,
            'province' => $validated['province'] ?? null,
            'country' => $validated['country'] ?? 'Philippines',
            'postal_code' => $validated['postal_code'] ?? null,
            'company_logo_path' => $request->hasFile('company_logo')
                ? $this->storeOptimizedImage($request->file('company_logo'), 'logos')
                : $employer->company_logo_path,
        ]);

        return redirect()->route('employer.profile.edit')->with('success', 'Employer profile updated successfully.');
    }

    public function verification()
    {
        return view('employer.profile.verification', [
            'employer' => auth()->user()->load('employerProfile.industry'),
        ]);
    }

    protected function storeOptimizedImage($file, string $directory): string
    {
        $manager = new ImageManager(new Driver());
        $path = "{$directory}/".uniqid().'.jpg';
        $image = $manager->read($file->getRealPath())->scaleDown(width: 800);

        Storage::disk('public')->put($path, (string) $image->encode());

        return $path;
    }
}
