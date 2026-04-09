<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\SocialMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SocialMediaController extends Controller
{
    public function index()
    {
        $page_title = 'Social Settings';
        $social_links = SocialMedia::all();
        $settings = [];
        foreach ($social_links as $link) {
            $settings[$link->social_media] = $link->profile_link;
        }

        return view('backend.layouts.settings.social', compact('settings', 'page_title'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'facebook'  => 'nullable|string',
            'youtube'   => 'nullable|string',
            'tiktok'    => 'nullable|string',
            'instagram' => 'nullable|string',
            'linkedin'  => 'nullable|string',
            'twitter'   => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();

            $platforms = ['facebook', 'youtube', 'tiktok', 'instagram', 'linkedin', 'twitter'];
            foreach ($platforms as $platform) {
                $link = $request->input($platform);

                if ($link) {
                    SocialMedia::updateOrCreate(
                        ['social_media' => $platform],
                        ['profile_link' => $link]
                    );
                }
            }

            DB::commit();
            return back()->with('success', 'Social media links updated successfully.');

        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            SocialMedia::destroy($id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Social media link deleted successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete social media link.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
