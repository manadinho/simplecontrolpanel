<div class="text-right text-nowrap">
    <a href="{{ route('admin.socialites.read', $user->id) }}" class="btn btn-link text-secondary p-1" title="Read"><i class="fal fa-lg fa-eye"></i></a>
    @if ($user->id != auth()->guard('social')->user()->id)
        @can('Delete Socialite Users')
        <form method="POST" action="{{ route('admin.socialites.delete', $user->id) }}" class="d-inline-block" novalidate data-ajax-form>
            @csrf
            @method('DELETE')
            <button type="submit" name="_submit" class="btn btn-link text-secondary p-1" title="Delete" value="reload_datatables" data-confirm>
                <i class="fal fa-lg fa-trash-alt"></i>
            </button>
        </form>
        @endcan
    @endif
</div>