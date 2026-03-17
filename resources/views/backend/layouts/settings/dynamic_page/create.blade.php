@extends('backend.app')
@section('page_title', 'Create Dynamic Page')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Create Dynamic Page</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.dynamic_page.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold text-muted">Title</label>
                                    <input type="text" class="form-control" placeholder="Enter Page Title"
                                        name="title" value="{{ old('title') }}">
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label class="form-label fw-bold text-muted">Description</label>
                                    <textarea class="form-control" id="summernote" placeholder="Enter your content here"
                                        name="content">{{ old('description') }}</textarea>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button class="btn btn-primary" type="submit">Submit</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection