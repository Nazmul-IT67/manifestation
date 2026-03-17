@extends('backend.app')
@section('page_title', 'Social Settings')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Social Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.social.update') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Facebook</label>
                                    <input type="text" class="form-control" placeholder="https://www.facebook.com/"
                                        name="facebook" value="{{ $settings['facebook'] ?? '' }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">LinkedIn</label>
                                    <input type="text" class="form-control" placeholder="https://www.linkedin.com/"
                                        name="linkedin" value="{{ $settings['linkedin'] ?? '' }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Twitter</label>
                                    <input type="text" class="form-control" placeholder="https://www.twitter.com/"
                                        name="twitter" value="{{ $settings['twitter'] ?? '' }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Instagram</label>
                                    <input type="text" class="form-control" placeholder="https://www.instagram.com/"
                                        name="instagram" value="{{ $settings['instagram'] ?? '' }}">
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