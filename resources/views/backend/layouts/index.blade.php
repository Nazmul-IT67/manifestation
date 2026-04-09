@extends('backend.app')
@section('page_title', 'Dashboard')
@section('content')
    <div class="app-content">
        <div class="container-fluid">
            <div class="row g-4">
                <div class="col-lg-3 col-6">
                    <div class="small-box shadow-sm border-0 rounded-4 overflow-hidden"
                        style="background: linear-gradient(135deg, #363d52 0%, #6274aa 100%); color: white;">
                        <div class="inner p-4">
                            <h3 class="fw-bold mb-1">{{ $totalUsers }}</h3>
                            <p class="opacity-75 mb-0">Total Users</p>
                        </div>
                        <div class="icon position-absolute top-0 end-0 p-3 opacity-25">
                            <i class="bi bi-people-fill fs-1"></i>
                        </div>
                        <a href="{{ route('users.index') }}"
                            class="small-box-footer bg-dark bg-opacity-10 py-2 text-decoration-none text-white-50 transition">
                            View Details <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box shadow-sm border-0 rounded-4 overflow-hidden"
                        style="background: linear-gradient(135deg, #053624 0%, #628077 100%); color: white;">
                        <div class="inner p-4">
                            <h3 class="fw-bold mb-1">{{ $totalPosts }}</h3>
                            <p class="opacity-75 mb-0">Total Posts</p>
                        </div>
                        <div class="icon position-absolute top-0 end-0 p-3 opacity-25">
                            <i class="bi bi-journal-text fs-1"></i>
                        </div>
                        <a href="{{ route('posts.index') }}"
                            class="small-box-footer bg-dark bg-opacity-10 py-2 text-decoration-none text-white-50 transition">
                            View Details <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box shadow-sm border-0 rounded-4 overflow-hidden"
                        style="background: linear-gradient(135deg, #503a04 0%, #dda20a 100%); color: white;">
                        <div class="inner p-4">
                            <h3 class="fw-bold mb-1">{{ $totalSessions }}</h3>
                            <p class="opacity-75 mb-0">Total Sessions</p>
                        </div>
                        <div class="icon position-absolute top-0 end-0 p-3 opacity-25">
                            <i class="bi bi-calendar-check-fill fs-1"></i>
                        </div>
                        <a href="{{ route('session.index') }}"
                            class="small-box-footer bg-dark bg-opacity-10 py-2 text-decoration-none text-dark-50 transition">
                            View Details <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box shadow-sm border-0 rounded-4 overflow-hidden"
                        style="background: linear-gradient(135deg, #550e07 0%, #be2617 100%); color: white;">
                        <div class="inner p-4">
                            <h3 class="fw-bold mb-1">{{ $totalSessions }}</h3>
                            <p class="opacity-75 mb-0">Completed Sessions</p>
                        </div>
                        <div class="icon position-absolute top-0 end-0 p-3 opacity-25">
                            <i class="bi bi-check-all fs-1"></i>
                        </div>
                        <a href="{{ route('session.index') }}"
                            class="small-box-footer bg-dark bg-opacity-10 py-2 text-decoration-none text-white-50 transition">
                            View Details <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
