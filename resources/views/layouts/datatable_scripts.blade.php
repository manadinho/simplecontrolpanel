<script type="text/javascript">
(function(window, $) {
    let filterBtnHtml = '<button type="button" class="btn btn-outline-secondary btn-sm mr-1" data-toggle="modal" data-target="#filterModalCenter"><i class="fal fa-search mr-2"></i>Advanced Filter</button>';
    let exportBtnHtml = '<button type="button" class="btn btn-outline-secondary btn-sm mr-1" id="exportBtn"><i class="fal fa-file-spreadsheet mr-2"></i>Export</button>';
    let deleteBtnHtml = '<button type="button" class="btn btn-outline-secondary btn-sm mr-1" id="deleteBtn" value="reload_datatables" data-confirm><i class="fal fa-trash-alt mr-2"></i>Delete</button>';
    let toolbarHtml = '<div class="float-right">';
    toolbarHtml = toolbarHtml + filterBtnHtml;
    @if (isset($export_url) && $export_url != '')
    toolbarHtml = toolbarHtml + exportBtnHtml;
    @endif
    @if (isset($batchDelete) && $batchDelete != '')
    toolbarHtml = toolbarHtml + deleteBtnHtml;
    @endif
    toolbarHtml = toolbarHtml + '</div>';

    $("div.toolbar").html(toolbarHtml);
    $(document).on('click', '#filterBtn', function(event) {
        event.preventDefault();
        window.LaravelDataTables['{{ $dtid }}'].draw();
        $('#filterModalCenter').modal('hide');
    });
    @if (isset($export_url) && $export_url != '')
    $(document).on('click', '#exportBtn', function(event) {
        event.preventDefault();
        var recordsDisplay = window.LaravelDataTables["{{ $dtid }}"].page.info().recordsDisplay;
        Swal.fire({
            title: "Are you sure?",
            text: "You may exporting a " + recordsDisplay + " records. Please try to minimize your query to prevent connection time out.",
            type: "warning",
            focusConfirm: false,
            showConfirmButton: true,
            showCancelButton: true
        }).then((result) => {
            if (result.value) {
                let data = {};
                $('.filterInput').each(function(index) {
                    data[$(this).attr('name')] = $(this).val();
                });
                window.open('{{ $export_url }}' + '?filter='+ JSON.stringify(data),'_export');
            }
        });
        window.LaravelDataTables['{{ $dtid }}'].draw();
        $('#filterModalCenter').modal('hide');
    });
    @endif

    @if (isset($reorder_url) && $reorder_url != '')
    $( "#{{ $dtid }}" ).sortable({
        items: "tr",
        cursor: 'move',
        opacity: 0.6,
        start: function (event, ui) {
            seqs = [];
            $('tr.sortable_row').each(function(index,element) {
                seqs.push(
                {
                    seq: $(this).data('seq'),
                });
            });
        },
        update: function (event, ui) {
            var ids = [];
            $('tr.sortable_row').each(function(index,element) {
                ids.push({
                    id: $(this).data('id'),
                });
            });
            $.ajax({
                    type: "POST", 
                    dataType: "json", 
                    url: "{{ $reorder_url }}",
                    data: {
                    ids: ids,
                    seqs: seqs,
                    _token: '{{ csrf_token() }}'
                },
                success: function() {
                    $($.fn.dataTable.tables()).DataTable().ajax.reload(null, false);
                }
            });
            $($.fn.dataTable.tables()).DataTable().ajax.reload(null, false);
        }
    });
    @endif

    $(document).on('click', '#deleteBtn', function(event) {
        event.preventDefault();
        let table = window.LaravelDataTables["{{ $dtid }}"];
        if (table.rows('.selected').data().length > 0) {
            let form = $('#batchDeleteForm');
            let table = window.LaravelDataTables["{{ $dtid }}"];
            $.each(table.rows('.selected').data(), function(index, val) {
                $(form).append(
                    $('<input>').attr('type', 'hidden').attr('name', '{{ $model_primary_attribute }}[]').val(val.{{ $model_primary_attribute }})
                );
            });
            form.submit();
        }
    });
})(window, jQuery);
</script>