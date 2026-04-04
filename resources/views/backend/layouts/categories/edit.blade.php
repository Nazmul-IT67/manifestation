@extends('backend.app')

@section('page_title', 'Edit Category')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-md border-0">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-end">
                        <a href="{{ route('categories.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('categories.update', $category->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Category Title <span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        placeholder="Enter category title" value="{{ old('title', $category->title) }}"
                                        required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Icon Class (Optional)</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-tag"></i></span>
                                        <input type="text" name="icon" class="form-control"
                                            placeholder="e.g., bi bi-house" value="{{ old('icon', $category->icon) }}">
                                    </div>
                                    <small class="text-muted">Use Bootstrap Icons or FontAwesome class.</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <select name="is_active" class="form-select shadow-none">
                                        <option value="1" {{ $category->is_active == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ $category->is_active == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea name="description" class="form-control" rows="4" placeholder="Enter brief description">{{ old('description', $category->description) }}</textarea>
                                </div>

                                <div class="col-md-12 mb-4">
                                    <label class="form-label fw-bold">Category Image</label>
                                    <div class="d-flex align-items-start gap-3 border p-3 rounded bg-light">
                                        <div class="text-center">
                                            <img id="imagePreview"
                                                src="{{ $category->image ? asset($category->image) : asset('assets/images/no-image.png') }}"
                                                class="rounded shadow-sm border"
                                                style="width: 80px; height: 80px; object-fit: cover;">
                                            <div class="small text-muted mt-1">Preview</div>
                                        </div>

                                        <div class="flex-grow-1">
                                            <input type="file" name="image" class="form-control mb-1" id="imageInput"
                                                onchange="previewImage(this)">
                                            <small class="text-muted d-block">Accepted formats: JPG, PNG, GIF. Max size:
                                                2MB.</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="border-top pt-4 mt-2">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-check-circle me-1"></i> Save Changes
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
        function previewImage(input) {
            const file = input.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endpush
