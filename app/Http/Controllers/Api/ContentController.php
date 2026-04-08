<?php
namespace App\Http\Controllers\Api;

use App\Models\Content;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use App\Models\UserSubscription;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ContentController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $query = Content::query();
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->search) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $contents = $query->with('category')->latest()->get();
        return $this->success($contents, 'Contents retrieved successfully.');
    }

    public function show(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $content = Content::with('category')->find($request->id);
        if (!$content) {
            return $this->error(null, 'Content not found.', 404);
        }

        if ($content->is_premium) {
            $hasSubscription = UserSubscription::where('user_id', auth()->id())
                ->where('status', 'active')
                ->where('end_date', '>', now())
                ->exists();

            if (!$hasSubscription) {
                return $this->error('This is a premium content. Please purchase a subscription.', 403);
            }
        }

        return $this->success($content, 'Content retrieved successfully.');
    }

    public function store(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $validator = Validator::make($request->all(), [
            'category_id'  => 'required|exists:categories,id',
            'title'        => 'required|string|max:255|unique:contents,title',
            'sub_title'    => 'nullable|string|max:255',
            'thumbnail'    => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'content_type' => 'nullable|in:video,audio',
            'content_url'  => 'required|url',
            'duration'     => 'nullable|string',
            'is_premium'   => 'required|boolean'
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first(), 422);
        }

        try {
            $data = $request->all();

            if ($request->hasFile('thumbnail')) {
                $fileName = time().'_thumb.'.$request->thumbnail->extension();
                $request->thumbnail->move(public_path('uploads/contents'), $fileName);
                $data['thumbnail'] = 'uploads/contents/'.$fileName;
            }

            $content = Content::create($data);
            return $this->success($content, 'Content created successfully.', 201);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function update(Request $request)
    {
        if (!auth()->check()) {
            return $this->error('Unauthorized', 401);
        }

        $content = Content::find($request->id);
        if (!$content) return $this->error('Content not found.', 404);

        $validator = Validator::make($request->all(), [
            'category_id'  => 'nullable|exists:categories,id',
            'title'        => 'nullable|string|max:255|unique:contents,title,' . $content->id,
            'sub_title'    => 'nullable|string|max:255',
            'thumbnail'    => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'content_type' => 'nullable|string|in:video,audio',
            'content_url'  => 'nullable|url',
            'duration'     => 'nullable|string',
            'is_premium'   => 'nullable|boolean'
        ]);

        if ($validator->fails()) return $this->error($validator->errors()->first(), 422);

        try {
            $data = $request->all();

            if ($request->hasFile('thumbnail')) {
                if ($content->getRawOriginal('thumbnail') && File::exists(public_path($content->getRawOriginal('thumbnail')))) {
                    File::delete(public_path($content->getRawOriginal('thumbnail')));
                }
                $fileName = time().'_thumb.'.$request->thumbnail->extension();
                $request->thumbnail->move(public_path('uploads/contents'), $fileName);
                $data['thumbnail'] = 'uploads/contents/'.$fileName;
            }

            $content->update($data);
            return $this->success($content, 'Content updated successfully.');
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function destroy(Request $request)
    {
        $content = Content::find($request->id);
        if (!$content) return $this->error('Content not found.', 404);

        if ($content->getRawOriginal('thumbnail') && File::exists(public_path($content->getRawOriginal('thumbnail')))) {
            File::delete(public_path($content->getRawOriginal('thumbnail')));
        }

        $content->delete();
        return $this->success(null, 'Content deleted successfully.');
    }
}