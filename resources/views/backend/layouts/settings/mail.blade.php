@extends('backend.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('mail.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row p-2">
                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label fw-semibold text-muted">MAIL MAILER:</label>
                                    <input type="text" class="form-control" placeholder="Enter Mail Mailer"
                                        name="mail_mailer" value="{{ old('mail_mailer', env('mail_mailer')) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label fw-bold text-muted">MAIL HOST</label>
                                    <input type="text" class="form-control" placeholder="Enter Mail Host"
                                        name="mail_host" value="{{ old('mail_host', env('mail_host')) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label fw-bold text-muted">MAIL PORT</label>
                                    <input type="text" class="form-control" placeholder="Enter Mail Port"
                                        name="mail_port" value="{{ old('mail_port', env('mail_port')) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label fw-bold text-muted">MAIL USERNAME</label>
                                    <input type="text" class="form-control" placeholder="Enter Mail Username"
                                        name="mail_username" value="{{ old('mail_username', env('mail_username')) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label fw-bold text-muted">MAIL PASSWORD</label>
                                    <input type="text" class="form-control" placeholder="Enter Mail Password"
                                        name="mail_password" value="{{ old('mail_password', env('mail_password')) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label fw-bold text-muted">MAIL ENCRYPTION</label>
                                    <input type="text" class="form-control" placeholder="Enter Mail Encryption"
                                        name="mail_encryption" value="{{ old('mail_encryption', env('mail_encryption')) }}">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="" class="form-label fw-bold text-muted">MAIL FROM ADDRESS</label>
                                    <input type="text" class="form-control" placeholder="Enter Mail From Address"
                                        name="mail_from_address"
                                        value="{{ old('mail_from_address', env('mail_from_address')) }}">
                                </div>

                            </div>
                            <div class="button p-2">
                                <button class="btn btn-primary btn-md" type="submit">Submit</button>
                                <a href="{{ route('dashboard') }}" class="btn btn-danger btn-md">Cancle</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
