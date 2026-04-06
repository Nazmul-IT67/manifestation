@extends('backend.app')

@section('page_title', 'Edit Content')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0">Edit Content: {{ $content->title }}</h5>
                    <a href="{{ route('contents.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('contents.update', $content->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Title --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       placeholder="Enter content title" value="{{ old('title', $content->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Sub Title --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Sub Title</label>
                                <input type="text" name="sub_title" class="form-control" 
                                       placeholder="Enter sub title" value="{{ old('sub_title', $content->sub_title) }}">
                            </div>

                            {{-- Category Selection --}}
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="" disabled>Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $content->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Content Type --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Content Type</label>
                                <select name="content_type" class="form-select">
                                    <option value="video" {{ old('content_type', $content->content_type) == 'video' ? 'selected' : '' }}>Video</option>
                                    <option value="audio" {{ old('content_type', $content->content_type) == 'audio' ? 'selected' : '' }}>Audio</option>
                                </select>
                            </div>

                            {{-- Is Premium --}}
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Premium Access</label>
                                <select name="is_premium" class="form-select">
                                    <option value="0" {{ old('is_premium', $content->is_premium) == 0 ? 'selected' : '' }}>Free</option>
                                    <option value="1" {{ old('is_premium', $content->is_premium) == 1 ? 'selected' : '' }}>Premium</option>
                                </select>
                            </div>

                            {{-- Content URL --}}
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Content URL / Link</label>
                                <input type="url" name="content_url" class="form-control" 
                                       placeholder="https://example.com/video" value="{{ old('content_url', $content->content_url) }}">
                            </div>

                            {{-- Thumbnail Upload --}}
                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-bold">Thumbnail Image</label>
                                <div class="d-flex align-items-start gap-3 border p-3 rounded bg-light">
                                    <div class="text-center">
                                        {{-- বর্তমান ইমেজটি দেখাবে, না থাকলে ডিফল্ট ইমেজ --}}
                                        <img id="imagePreview" src="{{ $content->thumbnail ? asset($content->thumbnail) : asset('assets/images/no-image.png') }}" 
                                             class="rounded shadow-sm border bg-white" 
                                             style="width: 120px; height: 80px; object-fit: cover;">
                                        <div class="small text-muted mt-1">Current Image</div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="file" name="thumbnail" class="form-control mb-1" id="imageInput" accept="image/*">
                                        <small class="text-muted d-block">Leave blank if you don't want to change the image.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-top pt-4">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-cloud-arrow-up me-1"></i> Update Content
                            </button>
                            <a href="{{ route('contents.index') }}" class="btn btn-light px-4 ms-2">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // Image Preview Script
    document.getElementById('imageInput').onchange = function (evt) {
        const [file] = this.files;
        if (file) {
            document.getElementById('imagePreview').src = URL.createObjectURL(file);
        }
    }
</script>
@endpush