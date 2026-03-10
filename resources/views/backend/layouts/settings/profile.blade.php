@extends('backend.app')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('admin.update.profile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row p-2">
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="col-md-12 text-start">
                                    <p class="h4 fw-bold text-muted mb-3">Update Profile</p>
                                    <div class="position-relative d-inline-block">
                                        <img id="profile-picture"
                                            src="{{ Auth::user()->image ? asset(Auth::user()->image) : asset('backend/assets/img/avatars/avatar.jpg') }}"
                                            class="rounded-circle border"
                                            style="width:120px; height:120px; object-fit:cover;">

                                        <input type="file" name="image" id="image" class="d-none" accept="image/*">
                                        <label for="image"
                                            class="position-absolute bottom-0 end-0 bg-secondary rounded-circle d-flex justify-content-center align-items-center"
                                            style="width:38px; height:38px; cursor:pointer;">
                                            <i class="bi bi-cloud-arrow-up text-white"></i>
                                        </label>
                                    </div>
                                    @error('image')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <p class="h4 fw-bold text-muted mb-3">Update Information</p>
                                <div class="col-md-12 mb-3">
                                    <label for="name" class="form-label fw-bold text-muted">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name', Auth::user()->name) }}">
                                    @error('name')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="email" class="form-label fw-bold text-muted">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email', Auth::user()->email) }}">
                                    @error('email')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <p class="h4 fw-bold text-muted mb-3">Update Password</p>
                                
                                <div class="col-md-12 mb-3">
                                    <label for="old_password" class="form-label fw-bold text-muted">Current Password</label>
                                    <input type="password" class="form-control @error('old_password') is-invalid @enderror"
                                        name="old_password" placeholder="Current Password">
                                    @error('old_password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="new_password" class="form-label fw-bold text-muted">New Password</label>
                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                        name="new_password" placeholder="New Password">
                                    @error('new_password')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-12 mb-3">
                                    <label for="new_password_confirmation" class="form-label fw-bold text-muted">Confirm Password</label>
                                    <input type="password" class="form-control"
                                        name="new_password_confirmation" placeholder="Confirm Password">
                                    </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="button p-2">
                        <button class="btn btn-primary" type="submit">Submit</button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-danger">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection