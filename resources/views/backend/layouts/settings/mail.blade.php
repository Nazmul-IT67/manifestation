@extends('backend.app')
@section('page_title', 'Mail Settings')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Mail Settings</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mail.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Mail Mailer</label>
                                    <input type="text" class="form-control" placeholder="Enter Mail Mailer"
                                        name="mail_mailer" value="{{ old('mail_mailer', env('MAIL_MAILER')) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Mail Host</label>
                                    <input type="text" class="form-control" placeholder="Enter Mail Host"
                                        name="mail_host" value="{{ old('mail_host', env('MAIL_HOST')) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Mail Port</label>
                                    <input type="text" class="form-control" placeholder="Enter Mail Port"
                                        name="mail_port" value="{{ old('mail_port', env('MAIL_PORT')) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Mail Username</label>
                                    <input type="text" class="form-control" placeholder="Enter Mail Username"
                                        name="mail_username" value="{{ old('mail_username', env('MAIL_USERNAME')) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Mail Password</label>
                                    <input type="password" class="form-control" placeholder="Enter Mail Password"
                                        name="mail_password" value="{{ old('mail_password', env('MAIL_PASSWORD')) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Mail Encryption</label>
                                    <input type="text" class="form-control" placeholder="Enter Mail Encryption"
                                        name="mail_encryption" value="{{ old('mail_encryption', env('MAIL_ENCRYPTION')) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">Mail From Address</label>
                                    <input type="text" class="form-control" placeholder="Enter Mail From Address"
                                        name="mail_from_address" value="{{ old('mail_from_address', env('MAIL_FROM_ADDRESS')) }}">
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