<script type="text/javascript">
    $(() => $('table').floatThead());

    $('table').on("floatThead", function(e, isFloated, $floatContainer) {
        if (isFloated) {
            $("table tr.hide").hide();
        } else {
            $("table tr.hide").show();
        }
    });
</script>

@if(isset($exam->editable))
@if(!$exam->editable && !Qs::userIsSuperAdmin())

<p class="text-muted text-right pt-1">Opened In Read Only Mode</p>
<script type="text/javascript">
    // Disable all Form Inputs if any exam edit is true
    // Assessment inputs will be disabled also if its respective exam (either terminal or annual) edit is set to true.
    $("form.ajax-update :input").prop('disabled', true).addClass('cursor-not-allowed');
</script>
@endif
@endif
