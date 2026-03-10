@extends('backend.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.system.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold text-muted">Title</label>
                                        <input type="text" class="form-control" placeholder="Enter Site Title"
                                            name="title" value="{{ $settings['title'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold text-muted">Email</label>
                                        <input type="email" class="form-control" placeholder="Enter Email" name="email"
                                            value="{{ $settings['email'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-muted">Site Name</label>
                                        <input type="text" class="form-control" placeholder="Enter Site Name"
                                            name="name" value="{{ $settings['name'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-muted">Copy Rights Text</label>
                                        <input type="text" class="form-control" placeholder="Copy Rights Text"
                                            name="copyright_text" value="{{ $settings['copyright_text'] ?? '' }}">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-muted">Logo</label>
                                        <input type="file" class="form-control dropify" name="logo" data-height="200"
                                            data-allowed-file-extensions="jpg png jpeg webp" data-max-file-size="2M"
                                            data-default-file="{{ asset($settings['logo'] ?? 'uploads/placeholder/27002.jpg') }}">
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-muted">Favicon</label>
                                        <input type="file" class="form-control dropify" name="favicon" data-height="200"
                                            data-allowed-file-extensions="jpg png jpeg webp" data-max-file-size="2M"
                                            data-default-file="{{ asset($settings['favicon'] ?? 'uploads/placeholder/27002.jpg') }}">
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="form-group">
                                        <label class="form-label fw-bold text-muted">Description</label>
                                        <textarea class="form-control" id="summernote" name="description" rows="4" placeholder="Enter your content here">{{ $settings['description'] ?? '' }}</textarea>
                                    </div>
                                </div>

                            </div>

                            <button class="btn btn-primary" type="submit">Submit</button>
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-danger">Cancel</a>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
