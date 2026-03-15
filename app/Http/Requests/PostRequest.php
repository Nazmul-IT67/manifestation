<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content'    => 'required_without:media_path|nullable|string',
            'media_path' => 'required_without:content|nullable|file|mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi',
            'post_type'  => 'required|string|in:post,story',
            'background' => 'nullable|string',
            'feelings'   => 'nullable|string',
            'tags'       => 'nullable|array',
            'tags.*'     => 'string',
            'is_active'  => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'content.required_without'    => 'Content is required when no media is provided.',
            'media_path.required_without' => 'Media is required when no content is provided.',
            'post_type.in'                => 'Post type must be post or story.',
            'tags.array'                  => 'Tags must be an array.',
            'tags.*.string'               => 'Each tag must be a string.',
        ];
    }
}
