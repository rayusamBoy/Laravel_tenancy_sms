@php
$division_list = Mk::gateDivisionList($class_type->id)
@endphp

@if($division_list->isNotEmpty())
<div style="margin-bottom: 5px; text-align: center">
    <table border="0" cellpadding="5" cellspacing="5" style="text-align: center; margin: 0 auto;">
        <tr>
            <td><strong>KEY TO THE DIVISIONING </strong>[<strong>name</strong> => start - end]</td>

            @foreach($division_list as $dv)
            <td>
                <strong>{{ $dv->name }}</strong> => {{ "{$dv->points_from} - {$dv->points_to}" }}
            </td>
            @endforeach
        </tr>
    </able>
</div>
@endif
