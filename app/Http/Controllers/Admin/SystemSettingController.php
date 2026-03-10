<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\SystemSetting;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class SystemSettingController extends Controller
{
    public function index()
    {
        $page_title = 'General Settings';
        $settings   = SystemSetting::pluck('value', 'key')->toArray();
        return view('backend.layouts.settings.general', compact('settings', 'page_title'));
    }

    /**
     * Update the system settings.
     */
    public function update(Request $request){
        
        $fields = $request->except('_token');

        try {
            foreach ($fields as $key => $value) {
                
                if ($request->hasFile($key)) {
                    $file = $request->file($key);
                    $filename = time().'_'.$file->getClientOriginalName();
                    $file->move(public_path('uploads/settings/'), $filename);
                    $value = 'uploads/settings/'.$filename;
                }
                
                SystemSetting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }
        
            return back()->with('t-success', 'Updated successfully');
        } catch (Exception) {
            return back()->with('t-error', 'Failed to update');
        }
    }
}
