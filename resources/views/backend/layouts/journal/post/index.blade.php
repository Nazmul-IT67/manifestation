@extends('backend.app')
@section('page_title', 'All Posts')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped table-bordered" id="data-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>SL</th>
                                    <th>Type</th>
                                    <th>Author</th>
                                    <th>Content</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
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
                        url: "{{ route('journal-post.index') }}",
                        type: "GET",
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'type',
                            name: 'post_type'
                        },
                        {
                            data: 'author',
                            name: 'user.name'
                        },
                        {
                            data: 'content',
                            name: 'content',
                            render: function(data) {
                                return data.length > 50 ? data.substr(0, 50) + '...' : data;
                            }
                        },
                        {
                            data: 'date',
                            name: 'created_at'
                        },
                        {
                            data: 'status',
                            name: 'is_active',
                            orderable: false,
                            searchable: false
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

        // Status Change Alert
        function showStatusChangeAlert(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to update the status?',
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    statusChange(id);
                } else {
                    $('#data-table').DataTable().ajax.reload(null, false);
                }
            });
        }

        // Delete Confirm
        function showDeleteConfirm(id) {
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

        function statusChange(id) {
            let url = '{{ route('journal-post.status', ':id') }}';

            $.ajax({
                type: "POST", // অথবা "PATCH"
                url: url.replace(':id', id),
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PATCH'
                },
                success: function(resp) {
                    $('#data-table').DataTable().ajax.reload(null, false);
                    toastr.success('Status updated!');
                },
                error: function(xhr) {
                    toastr.error('Something went wrong!');
                    $('#data-table').DataTable().ajax.reload(null, false);
                }
            });
        }

        function deleteItem(id) {
            let url = '{{ route('journal-post.destroy', ':id') }}';

            $.ajax({
                type: "POST", // অথবা "DELETE"
                url: url.replace(':id', id),
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(resp) {
                    $('#data-table').DataTable().ajax.reload();
                    toastr.success('Item deleted successfully!');
                },
                error: function(xhr) {
                    toastr.error('Internal Server Error!');
                }
            });
        }
    </script>
@endpush
