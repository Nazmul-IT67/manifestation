@extends('backend.app')

@section('page_title', 'Edit User')

@section('content')
<div class="container-fluid">
    <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="bi bi-person-badge me-2"></i>Account Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Email Address</label>
                                <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Phone Number</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Role</label>
                                <select name="role" class="form-select">
                                    <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <select name="is_active" class="form-select">
                                    <option value="1" {{ $user->is_active == 1 ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $user->is_active == 0 ? 'selected' : '' }}>Disabled</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="bi bi-graph-up me-2"></i>Activity Statistics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Manifests</label>
                                <input type="number" name="stat_manifests" class="form-control" value="{{ $user->details?->stat_manifests ?? 0 }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Streak (Days)</label>
                                <input type="number" name="stat_streak" class="form-control" value="{{ $user->details?->stat_streak ?? 0 }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label small">Total Min</label>
                                <input type="number" name="stat_minutes" class="form-control" value="{{ $user->details?->stat_minutes ?? 0 }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h6 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>User Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Height (cm)</label>
                                <input type="number" step="0.1" name="height" class="form-control" value="{{ $user->details?->height }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Weight (kg)</label>
                                <input type="number" step="0.1" name="weight" class="form-control" value="{{ $user->details?->weight }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Age</label>
                                <input type="number" name="age" class="form-control" value="{{ $user->details?->age }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Experience</label>
                                <select name="experience_level" class="form-select">
                                    <option value="beginner" {{ $user->details?->experience_level == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                    <option value="intermediate" {{ $user->details?->experience_level == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                    <option value="expert" {{ $user->details?->experience_level == 'expert' ? 'selected' : '' }}>Expert</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Location</label>
                                <input type="text" name="location" class="form-control" value="{{ $user->details?->location }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Sound Profile</label>
                                <input type="text" name="preferred_sound_profile" class="form-control" value="{{ $user->details?->preferred_sound_profile }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Reminder Time</label>
                                <input type="time" name="daily_reminder_time" class="form-control" value="{{ $user->details?->daily_reminder_time }}">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Timezone</label>
                                <select name="timezone" class="form-control select2">
                                    <option value="">Select Timezone</option>
                                    @foreach(timezone_identifiers_list() as $timezone)
                                        <option value="{{ $timezone }}" {{ (old('timezone') ?? $user->details?->timezone) == $timezone ? 'selected' : '' }}>
                                            {{ $timezone }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-bold">Bio</label>
                                <textarea name="bio" class="form-control" rows="2">{{ $user->details?->bio }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <label class="form-label fw-bold">Profile Image</label>
                        <div class="d-flex align-items-center">
                            @if($user->image)
                                <img src="{{ asset($user->image) }}" width="60" height="60" class="rounded-circle me-3 border">
                            @endif
                            <input type="file" name="image" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-12 text-end">
                <hr>
                <a href="{{ route('users.index') }}" class="btn btn-secondary px-4 me-2">Cancel</a>
                <button type="submit" class="btn btn-primary px-5 shadow">Update Changes</button>
            </div>
        </div>
    </form>
</div>
@endsection