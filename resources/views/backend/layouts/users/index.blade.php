@extends('backend.app')
@section('title', ' || Admin')
@section('content')
    <div class="content-wrapper">
        <x-breadcrumbs title="User Management" subtitle='Manage all users across the platform.' />
        <div class="row">
            <div class="col-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title d-inline-block table-title">User Details</h4>
                        <hr />
                        <div class="table-responsive w-100">
                            <table class="table table-hover" id="data-table">
                                <thead>
                                    <tr>
                                        <th>S\L</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subscribed</th>
                                        <th>Joined</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('modal') <x-status-modal /> @endsection
@section('script')
    @include('backend.layouts.users.partials._usersJS')
@endsection