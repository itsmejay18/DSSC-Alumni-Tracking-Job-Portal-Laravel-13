@props(['action', 'title' => 'Confirm Deletion', 'message' => 'This action cannot be undone.', 'id' => 'confirm-delete-modal'])

<div class="kit-modal" id="{{ $id }}">
    <div class="kit-modal-panel">
        <div class="kit-modal-head">
            <div>
                <strong>{{ $title }}</strong>
                <p class="kit-muted" style="margin: 6px 0 0;">{{ $message }}</p>
            </div>
            <button class="kit-close" type="button" data-modal-close><i class="ph ph-x"></i></button>
        </div>
        <div class="kit-modal-foot">
            <button class="kit-button secondary" type="button" data-modal-close>Cancel</button>
            <form method="POST" action="{{ $action }}">
                @csrf
                @method('DELETE')
                <button class="kit-button danger" type="submit">Delete</button>
            </form>
        </div>
    </div>
</div>
