@extends('backend.app')

@section('page_title', 'Edit Angel Number')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-md border-0">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-end">
                        <a href="{{ route('angle-number.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('angle-number.update', $angleNumber->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Angel Number</label>
                                    <input type="text" name="number"
                                        class="form-control @error('number') is-invalid @enderror" placeholder="e.g., 111"
                                        value="{{ old('number', $angleNumber->number) }}" readonly>
                                    @error('number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Title</label>
                                    <input type="text" name="title"
                                        class="form-control @error('title') is-invalid @enderror"
                                        placeholder="e.g., New Beginnings" value="{{ old('title', $angleNumber->title) }}"
                                        required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tags (Comma Separated)</label>
                                    <input type="text" name="tags" class="form-control"
                                        placeholder="manifestation, luck, growth"
                                        value="{{ old('tags', is_array($angleNumber->tags) ? implode(', ', $angleNumber->tags) : $angleNumber->tags) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <select name="is_active" class="form-select">
                                        <option value="1" {{ $angleNumber->is_active == 1 ? 'selected' : '' }}>Active
                                        </option>
                                        <option value="0" {{ $angleNumber->is_active == 0 ? 'selected' : '' }}>Inactive
                                        </option>
                                    </select>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea name="description" class="form-control" rows="4">{{ old('description', $angleNumber->description) }}</textarea>
                                </div>
                            </div>

                            <div class="border-top pt-4 mt-2">
                                <button type="submit" class="btn btn-primary px-4 shadow-sm">
                                    <i class="bi bi-check-circle me-1"></i> Update Data
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
