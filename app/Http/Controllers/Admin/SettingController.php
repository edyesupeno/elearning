<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'app_name' => Setting::get('app_name', 'E-Learning'),
            'app_description' => Setting::get('app_description', 'Platform pembelajaran online'),
            'app_logo' => Setting::get('app_logo'),
            'app_favicon' => Setting::get('app_favicon'),
        ];
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        try {
            $validated = $request->validate([
                'app_name' => 'required|string|max:255',
                'app_description' => 'nullable|string|max:500',
                'app_logo' => 'nullable|image|mimes:jpeg,png,jpg,svg|max:2048',
                'app_favicon' => 'nullable|image|mimes:ico,png|max:1024',
            ]);

            // Update nama dan deskripsi
            Setting::set('app_name', $request->app_name);
            Setting::set('app_description', $request->app_description ?? '');

            // Handle logo deletion
            if ($request->has('delete_logo')) {
                $oldLogo = Setting::get('app_logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
                Setting::set('app_logo', null);
            }

            // Handle logo upload
            if ($request->hasFile('app_logo')) {
                $oldLogo = Setting::get('app_logo');
                if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                    Storage::disk('public')->delete($oldLogo);
                }
                
                $logoPath = $request->file('app_logo')->store('settings', 'public');
                Setting::set('app_logo', $logoPath);
            }

            // Handle favicon deletion
            if ($request->has('delete_favicon')) {
                $oldFavicon = Setting::get('app_favicon');
                if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                    Storage::disk('public')->delete($oldFavicon);
                }
                Setting::set('app_favicon', null);
            }

            // Handle favicon upload
            if ($request->hasFile('app_favicon')) {
                $oldFavicon = Setting::get('app_favicon');
                if ($oldFavicon && Storage::disk('public')->exists($oldFavicon)) {
                    Storage::disk('public')->delete($oldFavicon);
                }
                
                $faviconPath = $request->file('app_favicon')->store('settings', 'public');
                Setting::set('app_favicon', $faviconPath);
            }
            
            return redirect()->route('admin.settings.index')->with('success', 'Pengaturan berhasil disimpan!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menyimpan pengaturan: ' . $e->getMessage())->withInput();
        }
    }

    public function deleteImage(Request $request)
    {
        $type = $request->type; // 'logo' or 'favicon'
        $key = $type === 'logo' ? 'app_logo' : 'app_favicon';
        
        $imagePath = Setting::get($key);
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
        
        Setting::set($key, null);
        
        return back()->with('success', ucfirst($type) . ' berhasil dihapus');
    }
}
