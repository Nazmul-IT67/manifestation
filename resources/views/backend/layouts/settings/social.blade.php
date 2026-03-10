@extends('backend.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.system.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row p-2">
                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label fw-bold text-muted">Facebook</label>
                                    <input type="text" class="form-control" placeholder="https://www.facebook.com/"
                                        name="facebook" value="{{ $settings['facebook'] ?? '' }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label fw-bold text-muted">Linkedin</label>
                                    <input type="text" class="form-control" placeholder="https://www.linkedin.com/"
                                        name="linkedin" value="{{ $settings['linkedin'] ?? '' }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label fw-bold text-muted">Twitter</label>
                                    <input type="text" class="form-control" placeholder="https://www.twitter.com/"
                                        name="twitter" value="{{ $settings['twitter'] ?? '' }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label fw-bold text-muted">Instagram</label>
                                    <input type="text" class="form-control" placeholder="https://www.instagram.com/"
                                        name="instagram" value="{{ $settings['instagram'] ?? '' }}">
                                </div>

                            </div>
                            <div class="button p-2">
                                <button class="btn btn-primary" type="submit">Submit</button>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-danger">Cancle</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
