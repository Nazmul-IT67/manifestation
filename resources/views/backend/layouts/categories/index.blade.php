@extends('backend.app')

@section('content')
<div class="container">
    <h2>Categories</h2>
    <a href="{{ route('categories.create') }}" class="btn btn-success mb-2">Add Category</a>

    <table class="table table-bordered" id="category-table">
        <thead>
            <tr>
                <th>SL</th>
                <th>Title</th>
                <th>Image</th>
                <th>Icon</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $('#category-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('categories.index') }}",
        columns: [
            { data: 'serial', name: 'serial' },
            { data: 'title', name: 'title' },
            { data: 'image', name: 'image', orderable: false, searchable: false },
            { data: 'icon', name: 'icon' },
            { data: 'status', name: 'status', orderable: false, searchable: false },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ]
    });

    // Delete button
    $(document).on('click', '.deletebtn', function(){
        var id = $(this).data('id');
        if(confirm("Are you sure to delete this category?")){
            $.ajax({
                url: "/admin/categories/" + id,
                type: "DELETE",
                data: {_token: "{{ csrf_token() }}"},
                success: function(res){
                    $('#category-table').DataTable().ajax.reload();
                    alert(res.status ? res.status : 'Deleted successfully');
                }
            });
        }
    });

    // Status change
    $(document).on('click', '.change_status', function(e){
        e.preventDefault();
        var id = $(this).data('id');
        var enabled = $(this).data('enabled');

        $.ajax({
            url: "/admin/categories/" + id + "/status",
            type: "PATCH",
            data: { is_active: enabled, _token: "{{ csrf_token() }}" },
            success: function(res){
                $('#category-table').DataTable().ajax.reload();
            }
        });
    });
});
</script>
@endsection