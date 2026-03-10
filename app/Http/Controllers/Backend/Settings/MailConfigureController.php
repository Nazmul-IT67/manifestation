<?php

namespace App\Http\Controllers\Backend\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class MailConfigureController extends Controller
{
    public function index()
    {
        $page_title = 'Mail Setting';
        return view('backend.layouts.settings.mail', compact('page_title'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'mail_mailer'       => 'nullable|string',
            'mail_host'         => 'nullable|string',
            'mail_port'         => 'nullable|string',
            'mail_username'     => 'nullable|string',
            'mail_password'     => 'nullable|string',
            'mail_encryption'   => 'nullable|string',
            'mail_from_address' => 'nullable|string',
        ]);

        setEnv([
            'MAIL_MAILER' => $request->mail_mailer,
            'MAIL_HOST'   => $request->mail_host,
            'MAIL_PORT'   => $request->mail_port,
            'MAIL_USERNAME' => $request->mail_username,
            'MAIL_PASSWORD' => $request->mail_password,
            'MAIL_ENCRYPTION' => $request->mail_encryption,
            'MAIL_FROM_ADDRESS' => $request->mail_from_address,
        ]);

        // Laravel cache clear
        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return back()->with('success','Mail settings updated successfully!');
    }
}
