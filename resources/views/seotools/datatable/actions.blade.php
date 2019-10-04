<div class="text-right text-nowrap">
    <a href="{{ route('admin.seotools.read', $seotool->id) }}" class="btn btn-link text-secondary p-1" title="Read"><i class="fal fa-lg fa-eye"></i></a>
    @can('Update Seotools')
        <a href="{{ route('admin.seotools.update', $seotool->id) }}" class="btn btn-link text-secondary p-1" title="Update"><i class="fal fa-lg fa-edit"></i></a>
    @endcan
    @can('Delete Seotools')
        <form method="POST" action="{{ route('admin.seotools.delete', $seotool->id) }}" class="d-inline-block" novalidate data-ajax-form>
            @csrf
            @method('DELETE')
            <button type="submit" name="_submit" class="btn btn-link text-secondary p-1" title="Delete" value="reload_datatables" data-confirm>
                <i class="fal fa-lg fa-trash-alt"></i>
            </button>
        </form>
    @endcan
</div>