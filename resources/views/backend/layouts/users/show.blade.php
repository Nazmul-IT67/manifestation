@extends('backend.app')

@section('page_title', 'User Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    {{-- Top Action Bar --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to List
                        </a>
                        <div>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-pencil-square me-1"></i> Edit Profile
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Left Side: User Profile Card --}}
                        <div class="col-lg-4">
                            <div class="card card-outline card-primary shadow-sm mb-4">
                                <div class="card-body pt-4">
                                    <div class="text-center">
                                        <img src="{{ $user->image ? asset($user->image) : asset('backend/assets/images/default-avatar.png') }}"
                                            alt="User profile picture" class="rounded-circle img-thumbnail mb-3"
                                            style="width: 150px; height: 150px; object-fit: cover;">

                                        <h4 class="profile-username fw-bold">{{ $user->name }}</h4>
                                        <p class="text-muted mb-1 small"><i class="bi bi-envelope me-1"></i>{{ $user->email }}</p>
                                        <p class="text-muted small"><i class="bi bi-telephone me-1"></i>{{ $user->phone ?? 'N/A' }}</p>

                                        <hr class="my-3">

                                        <div class="d-flex justify-content-between px-3 mb-2">
                                            <strong>Status</strong>
                                            @if ($user->is_active)
                                                <span class="badge bg-success">Active</span>
                                            @else
                                                <span class="badge bg-danger">Disabled</span>
                                            @endif
                                        </div>

                                        <div class="d-flex justify-content-between px-3">
                                            <strong>Subscription</strong>
                                            @if ($user->hasActiveSubscription())
                                                <span class="badge bg-primary">Premium</span>
                                            @else
                                                <span class="badge bg-secondary">Free Plan</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Activity Stats Card --}}
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-light">
                                    <h6 class="card-title mb-0">Activity Stats</h6>
                                </div>
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Manifests Created
                                            <span class="badge bg-info rounded-pill">{{ $user->details?->stat_manifests ?? 0 }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Current Streak
                                            <span class="badge bg-warning text-dark rounded-pill">{{ $user->details?->stat_streak ?? 0 }} Days</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Total Minutes
                                            <span class="badge bg-success rounded-pill">{{ $user->details?->stat_minutes ?? 0 }} Min</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Right Side: Detailed Info --}}
                        <div class="col-lg-8">
                            <div class="card shadow-sm border-0">
                                <div class="card-header p-3 bg-white border-bottom d-flex align-items-center">
                                    <h5 class="mb-0">
                                        <i class="bi bi-person-lines-fill me-2 text-primary"></i>Detailed Information
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <tbody>
                                                <tr>
                                                    <th style="width: 35%" class="ps-4 py-3">Physical Attributes</th>
                                                    <td class="py-3">
                                                        <span class="me-4"><strong>Height:</strong> {{ $user->details?->height ? $user->details->height . ' cm' : 'N/A' }}</span>
                                                        <span><strong>Weight:</strong> {{ $user->details?->weight ? $user->details->weight . ' kg' : 'N/A' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-4 py-3">Age</th>
                                                    <td class="py-3">{{ $user->details?->age ?? 'N/A' }} Years</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-4 py-3">Experience Level</th>
                                                    <td class="py-3">
                                                        <span class="badge bg-light text-dark border">{{ ucfirst($user->details?->experience_level ?? 'N/A') }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-4 py-3">Primary Goal</th>
                                                    <td class="py-3 text-primary fw-semibold">{{ $user->details?->primary_goal ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-4 py-3">Session Preference</th>
                                                    <td class="py-3">{{ $user->details?->default_session_duration ?? 'N/A' }} Minutes</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-4 py-3">Sound Profile</th>
                                                    <td class="py-3">{{ $user->details?->preferred_sound_profile ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-4 py-3">Reminder Time</th>
                                                    <td class="py-3"><i class="bi bi-alarm me-1 text-danger"></i> {{ $user->details?->daily_reminder_time ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-4 py-3">Location</th>
                                                    <td class="py-3"><i class="bi bi-geo-alt me-1 text-success"></i> {{ $user->details?->location ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-4 py-3">Joined At</th>
                                                    <td class="py-3">{{ $user->created_at?->format('d M Y, h:i A') ?? 'N/A' }}</td>
                                                </tr>
                                                <tr>
                                                    <th class="ps-4 py-3 border-0">Bio</th>
                                                    <td class="py-3 border-0 text-wrap lh-base">{{ $user->details?->bio ?? 'No bio provided.' }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> {{-- End Inner Row --}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection