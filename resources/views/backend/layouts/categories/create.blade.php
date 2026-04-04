@extends('backend.app')

@section('page_title', 'Create Category')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex align-items-center justify-content-end">
                    <a href="{{ route('categories.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                       placeholder="Enter category title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Icon Class</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                    <input type="text" name="icon" class="form-control" placeholder="e.g., bi bi-list" value="{{ old('icon') }}">
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="is_active" class="form-select shadow-none">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Description</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Write description...">{{ old('description') }}</textarea>
                            </div>

                            <div class="col-md-12 mb-4">
                                <label class="form-label fw-bold">Category Image</label>
                                <div class="d-flex align-items-start gap-3 border p-3 rounded bg-light">
                                    <div class="text-center">
                                        <img id="imagePreview" src="{{ asset('assets/images/no-image.png') }}" 
                                             class="rounded shadow-sm border bg-white" 
                                             style="width: 80px; height: 80px; object-fit: cover;">
                                        <div class="small text-muted mt-1">Preview</div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <input type="file" name="image" class="form-control mb-1" id="imageInput" accept="image/*">
                                        <small class="text-muted d-block">Accepted formats: JPG, PNG, GIF. Max size: 2MB.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-top pt-4">
                            <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-cloud-arrow-up me-1"></i> Save Category
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn btn-light px-4 ms-2">Cancel</a>
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
    document.getElementById('imageInput').onchange = function (evt) {
        const [file] = this.files;
        if (file) {
            document.getElementById('imagePreview').src = URL.createObjectURL(file);
        }
    }
</script>
@endpush