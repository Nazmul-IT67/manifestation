<?php

namespace App\Http\Controllers\Backend\Settings;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function general(){
        $page_title = 'General Setting';
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('backend.layouts.settings.general', compact('page_title', 'settings'));
    }

    public function social(){
        $page_title = 'Social Setting';
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('backend.layouts.settings.social', compact('page_title', 'settings'));
    }

    public function update(Request $request){
        
        $fields = $request->except('_token');
        
        foreach ($fields as $key => $value) {
            
            if ($request->hasFile($key)) {
                $file = $request->file($key);
                $filename = time().'_'.$file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);
                $value = 'uploads/'.$filename;
            }
            
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
        
        return redirect()->back()->with('success', 'Settings saved successfully.');
    }

}
