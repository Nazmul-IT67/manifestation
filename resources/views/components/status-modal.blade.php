<div class="modal fade bd-example-modal-sm" id="{{ $modalId ?? 'statusModal' }}" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content modal_icon">
            <form id="{{ $formId ?? 'status_form' }}">
                @csrf
                <input type="hidden" id="status_id">
                <input type="hidden" id="status_enabled">

                <div class="modal-title text-center">
                    <img src="{{ asset('backend/assets/icon/warning.png') }}" class="img-fluid" alt="Warning">
                    <br><br>
                    <h5 id="status_title"></h5>
                    <p id="status_description"></p>
                </div>

                <div class="text-center mt-2 mb-2">
                    <button type="button" class="btn btn-secondary btn-sm" style="margin:0 10px"
                        data-bs-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-danger btn-sm" style="margin:0 10px"
                        data-bs-dismiss="modal">Yes</button>
                </div>
            </form>
        </div>
    </div>
</div>