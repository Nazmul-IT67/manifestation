@extends('backend.app')

@section('content')
<div class="container">
    <h1>Edit Category</h1>

    <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="{{ $category->title }}" required>
        </div>

        <div class="form-group mb-3">
            <label>Image</label>
            @if($category->image)
                <img src="{{ asset($category->image) }}" width="50">
            @endif
            <input type="file" name="image" class="form-control">
        </div>

        <div class="form-group mb-3">
            <label>Icon</label>
            <input type="text" name="icon" class="form-control" value="{{ $category->icon }}">
        </div>

        <div class="form-group mb-3">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $category->description }}</textarea>
        </div>

        <div class="form-group mb-3">
            <label>Status</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ $category->is_active ? 'selected' : '' }}>Active</option>
                <option value="0" {{ !$category->is_active ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection