<div class="text-center text-nowrap">
    @if ($minseq != $model->seq)
    <form method="POST" class="no-gutters p-0 m-0" action="{{ $up_url }}" novalidate data-ajax-form>
        @csrf
        <button type="submit" name="_submit" class="btn btn-link text-secondary" title="Move Up" value="reload_datatables">
            <i class="fas fa-lg fa-sort-up fa-2x"></i>
        </button>
    </form>
    @endif

    @if ($maxseq != $model->seq)
    <form method="POST" class="no-gutters p-0 m-0" action="{{ $down_url }}" novalidate data-ajax-form>
        @csrf
        <button type="submit" name="_submit" class="btn btn-link text-secondary" title="Move Down" value="reload_datatables">
            <i class="fas fa-lg fa-sort-down fa-2x"></i>
        </button>
    </form>
    @endif
</div>