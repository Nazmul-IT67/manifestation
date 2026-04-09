@extends('backend.app')
@section('page_title', 'Angle Numbers')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-end">
                        <a href="{{ route('categories.create') }}" class="btn btn-sm btn-secondary">
                            <i class="bi bi-plus me-1"></i> Add New
                        </a>
                    </div>
                    <div class="card-body">
                        <table class="table table-striped table-bordered" id="data-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>SL</th>
                                    <th>Title</th>
                                    <th>Number</th>
                                    <th>Description</th>
                                    <th>Tags</th>
                                    <th>Status</th>
                                    <th>Action</th>
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

                    scroller: {
                        loadingIndicator: false
                    },
                    ajax: {
                        url: "{{ route('angle-number.index') }}",
                        type: "get",
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
                            data: 'number',
                            name: 'number',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'description',
                            name: 'description',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'tags',
                            name: 'tags',
                            orderable: false
                        },
                        {
                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        },
                    ]
                });

                dTable.buttons().container().appendTo('#file_exports');
                new DataTable('#example', {
                    responsive: true
                });
            }
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
            let url = '{{ route('angle-number.status', ':id') }}';

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
    </script>
@endpush
