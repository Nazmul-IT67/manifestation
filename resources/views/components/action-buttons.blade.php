<div class="text-center">
    <div class="btn-group btn-group-sm" role="group">

        <!-- View Details -->
        @if (isset($show))
        <a href="{{ route($show, $id) }}" class="btn btn-sm btn-info" title="View Details">
            <i class="fas fa-eye"></i>
        </a>
        @endif

        <!-- Edit -->
        @if (isset($edit))
        <a href="{{ route($edit, $id) }}" class="btn btn-sm btn-warning" title="Edit">
            <i class="fas fa-edit"></i>
        </a>
        @endif

        @if (isset($scrape) && $scrape)
        <a href="#" class="btn btn-sm btn-primary scrape-btn" title="Scrape Now" data-id="{{ $id }}" data-name="{{ $name ?? '' }}">
            <i class="fa-solid fa-paper-plane"></i>
        </a>
        @endif

        <!-- Delete -->
        @if (isset($delete) && $delete)
        <a href="#" class="btn btn-sm btn-danger deletebtn" title="Delete" data-id="{{ $id }}">
            <i class="fas fa-trash-alt"></i>
        </a>
        @endif

    </div>
</div>