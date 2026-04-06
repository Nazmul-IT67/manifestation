@extends('backend.app')
@section('page_title', 'Edit Journal Type')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-end">
                        <a href="{{ route('journal-type.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('journal.update', $journalType->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                {{-- Title --}}
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror" placeholder="Enter title"
                                        value="{{ old('title', $journalType->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Description (Based on Database Structure) --}}
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3"
                                        placeholder="Enter description">{{ old('description', $journalType->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Icon</label>
                                    <input type="file" name="icon" id="iconInput"
                                        class="form-control @error('icon') is-invalid @enderror"
                                        onchange="previewImage(this)">

                                    <div class="mt-2">
                                        <div id="previewContainer" style="display: none;">
                                            <small class="text-success d-block mb-1">New Selected Icon:</small>
                                            <img id="imagePreview" src="#" alt="Selected Icon"
                                                style="height: 60px; background: #e9ecef; padding: 5px; border-radius: 5px; border: 1px dashed #007bff;">
                                        </div>

                                        @if ($journalType->icon)
                                            <div id="currentIconContainer" class="mt-2">
                                                <small class="text-muted d-block mb-1">Current Icon:</small>
                                                <img src="{{ asset('uploads/journal_types/' . $journalType->icon) }}"
                                                    alt="icon"
                                                    style="height: 50px; background: #f8f9fa; padding: 5px; border-radius: 5px;">
                                            </div>
                                        @endif
                                    </div>

                                    @error('icon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="border-top pt-4 mt-3">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-cloud-arrow-up me-1"></i> Update Content
                                </button>
                                <a href="{{ route('journal.index') }}" class="btn btn-light px-4 ms-2">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    function previewImage(input) {
        const previewContainer = document.getElementById('previewContainer');
        const imagePreview = document.getElementById('imagePreview');
        const currentIconContainer = document.getElementById('currentIconContainer');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.style.display = 'block';

                if (currentIconContainer) {
                    currentIconContainer.style.opacity = '0.5';
                }
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
