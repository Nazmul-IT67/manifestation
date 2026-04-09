@extends('backend.app')
@section('page_title', 'All Bookings')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered" id="data-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>SL</th>
                                        <th>User Name</th>
                                        <th>User Email</th>
                                        <th>Session Time</th>
                                        <th>Booking Time</th>
                                        <th>Status</th>
                                        <th>Action</th>
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

@push('js')
    <script>
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                }
            });

            if (!$.fn.DataTable.isDataTable('#data-table')) {
                let dTable = $('#data-table').DataTable({
                    order: [],
                    lengthMenu: [
                        [10, 25, 50, 100, -1],
                        [10, 25, 50, 100, "All"]
                    ],
                    processing: true,
                    responsive: true,
                    serverSide: true,
                    language: {
                        processing: `<div class="text-center">
                            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>`
                    },
                    ajax: {
                        url: "{{ route('session.index') }}",
                        type: "get",
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'user_name',
                            name: 'users.name'
                        },
                        {
                            data: 'user_email',
                            name: 'users.email'
                        },
                        {
                            data: 'booking_info',
                            name: 'booking_date'
                        },
                        {
                            data: 'joined',
                            name: 'created_at'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ],
                });
            }
        });

        $(document).on('change', '.status-change', function() {
            let status = $(this).val();
            let id = $(this).data('id');
            let selector = $(this);

            $.ajax({
                url: "{{ route('session.updateStatus') }}",
                method: "POST",
                data: {
                    id: id,
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response.status) {
                        // Success hole color update korbe
                        const classes = {
                            'pending': 'bg-warning text-dark',
                            'confirmed': 'bg-info text-white',
                            'completed': 'bg-success text-white',
                            'cancelled': 'bg-danger text-white'
                        };
                        
                        selector.removeClass('bg-warning bg-info bg-success bg-danger text-dark text-white')
                                .addClass(classes[status] || 'bg-secondary');
                        
                        toastr.success(response.message); // Jodi Toastr thake
                    }
                },
                error: function() {
                    alert('Something went wrong!');
                }
            });
        });

        // Delete Confirm Alert
        function showDeleteConfirm(id) {
            if (window.event) window.event.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: 'If you delete this, it will be gone forever.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteItem(id);
                }
            });
        }

        // Delete Logic
        function deleteItem(id) {
            let url = '{{ route('session.destroy', ':id') }}';

            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(resp) {
                    $('#data-table').DataTable().ajax.reload(null, false);

                    if (resp.success) {
                        toastr.success(resp.message);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Internal Server Error!');
                }
            });
        }
    </script>
@endpush
