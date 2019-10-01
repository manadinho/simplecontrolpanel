<script id="template" type="text/x-handlebars-template">
<div class="row">
    <div class="col-md-4">
        <input type="text" name="indexes[]" class="values form-control" required placeholder="index">
    </div>
    <div class="col-md-6">
        <textarea type="text" name="values[]" class="values form-control" placeholder="value" rows="1"></textarea>
    </div>
    <div class="col-md-2">
        <button type="button" class="addRow btn btn-primary btn-sm"><i class="fa fa-plus"></i></button>
        <button type="button" class="minusRow btn btn-danger btn-sm"><i class="fa fa-minus"></i></button>
    </div>
</div>  
</script>
<script type="text/javascript">
$(function () {
    $(document).on('click','.addRow',function() {
        var template = Handlebars.compile($('#template').html());
        var html = template();
        $(this).closest('.row').after(html);
    });
    $(document).on('click','.minusRow',function() {
        if ($('#multipleValues').find('.row').length > 1) {
            $(this).closest('.row').remove();
        }
    });
    $(document).on('change', '#multipleTypes', function(event) {
        event.preventDefault();
        let isMultiple = $(this).is(':checked');
        if (isMultiple) {
            $('#singleValue').hide();
            $('#multipleValues').show();
        } else {
            $('#singleValue').show();
            $('#multipleValues').hide();
        }
    });
});
</script>