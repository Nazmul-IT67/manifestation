<script>
document.addEventListener('DOMContentLoaded', function () {

    // ------------------- CSRF setup -------------------
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        xhrFields: { withCredentials: true }
    });

    // ------------------- Initialize DataTable -------------------
    const dt = $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        stateSave: true,
        responsive: true,
        ajax: {
            url: '/admin/users',
            type: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        },

        order: [[0, 'desc']],
        columns: [
            { data: 'serial',           name: 'serial',           orderable: false, searchable: false },
            { data: 'name',             name: 'name' },
            { data: 'role',             name: 'role' },
            { data: 'email',            name: 'email' },
            { data: 'phone',            name: 'phone' },
            { data: 'is_active',           name: 'is_active',           orderable: false, searchable: false },
            { data: 'joined',           name: 'joined' },
            { data: 'last_application', name: 'last_application', orderable: false, searchable: false },
            { data: 'action',           name: 'action',           orderable: false, searchable: false }
        ],
  language: {
    paginate: {
        previous: '<i class="fas fa-angle-left"></i>',
        next: '<i class="fas fa-angle-right"></i>'
    },
    processing: '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
}
    });


    // ------------------- Account Status Toggle -------------------
    $(document).on('click', '.change_status', function (e) {
        e.preventDefault();
        $('#status_id').val($(this).data('id'));
        $('#status_enabled').val($(this).data('enabled'));
        $('#status_title').text($(this).data('title'));
        $('#status_description').text($(this).data('description'));
    });

    $('#status_form').on('submit', function (e) {
        e.preventDefault();
        const id       = $('#status_id').val();
        const isActive = $('#status_enabled').val();

        $.ajax({
            url:  '/admin/users/' + id + '/account-status',
            type: 'PATCH',
            data: { status: isActive },
            success: function (res) {
                dt.ajax.reload(null, false);
                successModal('SUCCESSFULLY UPDATED');
            },
            error: function (xhr) {
                errorModal();
                console.error(xhr.responseText);
            }
        });
    });

    // ------------------- Delete User -------------------
    $(document).on('click', '.deletebtn', function (e) {
        e.preventDefault();
        $('#delete_id').val($(this).data('id'));
        $('#deletemodal').modal('show');
    });

    $('#delete_modal_clear').on('submit', function (e) {
        e.preventDefault();
        const id = $('#delete_id').val();

        $.ajax({
            url:  '/admin/users/' + id,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function (res) {
                dt.ajax.reload(null, false);
                successModal('SUCCESSFULLY DELETED');
            },
            error: function (xhr) {
                errorModal();
                console.error(xhr.responseText || xhr.statusText);
            }
        });
    });

});
</script>
