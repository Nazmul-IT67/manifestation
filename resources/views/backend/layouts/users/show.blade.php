@extends('backend.app')
@section('title', 'User Details || Admin')

@section('content')
    <div class="content-wrapper">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="dashboard_header mb_10">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="dashboard_header_title">
                                <h3>User Details</h3>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="dashboard_breadcam text-end">
                                <x-back-button />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- User Info Card --}}
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <img src="{{ $user->image ? asset($user->image) : asset('backend/assets/images/default-avatar.png') }}"
                            alt="Avatar" class="rounded-circle mb-3" width="120" height="120">
                        <h4 class="mb-1">{{ $user->name }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>
                        <p class="text-muted">{{ $user->details?->phone ?? 'N/A' }}</p>
                        <p>Status:
                            @if ($user->status)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Disabled</span>
                            @endif
                        </p>
                        <p>Subscription:
                            @if ($user->hasActiveSubscription())
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            {{-- User Details Card --}}
            <div class="col-lg-8 col-md-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h5 class="mb-0">User Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <tbody>
                                    <tr>
                                        <td>Height</td>
                                        <td>{{ $user->details?->height ? $user->details->height . ' cm' : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Weight</td>
                                        <td>{{ $user->details?->weight ? $user->details->weight . ' kg' : 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Age</td>
                                        <td>{{ $user->details?->age ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Experience Level</td>
                                        <td>{{ ucfirst($user->details?->experience_level ?? 'N/A') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Primary Goal</td>
                                        <td>{{ $user->details?->primary_goal ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Session Duration</td>
                                        <td>{{ $user->details?->default_session_duration ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Sound Profile</td>
                                        <td>{{ $user->details?->preferred_sound_profile ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Daily Reminder</td>
                                        <td>{{ $user->details?->daily_reminder_time ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Location</td>
                                        <td>{{ $user->details?->location ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bio</td>
                                        <td>{{ $user->details?->bio ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <td>Stat Manifests</td>
                                        <td>{{ $user->details?->stat_manifests ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Stat Streak</td>
                                        <td>{{ $user->details?->stat_streak ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Stat Minutes</td>
                                        <td>{{ $user->details?->stat_minutes ?? 0 }}</td>
                                    </tr>
                                    <tr>
                                        <td>Joined</td>
                                        <td>{{ $user->created_at?->format('d M Y') ?? 'N/A' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection