@extends('backend.app')
@section('page_title', 'Manage Journal')

@section('content')
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                        <h5 class="mb-0">All Journals</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-hover table-bordered w-100" id="journal-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>SL</th>
                                    <th>User Name</th>
                                    <th>Title</th>
                                    <th>Mood</th>
                                    <th>Created At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
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
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });

            // ১. Journal Type Table DataTable
            $('#journal-type-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('journal.index') }}",
                    data: {
                        table: 'journal_type'
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'description',
                        name: 'description'
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
                    }
                ]
            });

            // ২. Journal Table DataTable
            $('#journal-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('journal.index') }}",
                    data: {
                        table: 'journal'
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_id',
                        name: 'user_id.name'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'mood_tag',
                        name: 'mood_tag'
                    },
                    {
                        data: 'created_at',
                        name: 'created_at'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });

        // Status Change Confirm Alert
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

        function statusChange(id) {
            let url = '{{ route('journal.status', ':id') }}';

            $.ajax({
                type: "PATCH",
                url: url.replace(':id', id),
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(resp) {
                    $('#data-table').DataTable().ajax.reload(null, false);
                    toastr.success('Status updated!');
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                    toastr.error('Something went wrong!');
                }
            });
        }

        // Delete Confirm Alert
        function showDeleteConfirm(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteJournal(id);
                }
            });
        }

        // Delete Logic
        function deleteJournal(id) {
            let url = '{{ route('journal.destroy', ':id') }}';

            $.ajax({
                type: "DELETE",
                url: url.replace(':id', id),
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(resp) {
                    if (resp.success) {
                        $('#journal-table').DataTable().ajax.reload(null, false);
                        toastr.success(resp.message);
                    } else {
                        toastr.error(resp.message);
                    }
                },
                error: function(xhr) {
                    toastr.error('Error: Could not delete the item.');
                }
            });
        }
    </script>
@endpush
