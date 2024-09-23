<div style="margin-bottom: 5px; text-align: center">
    <table border="0" cellpadding="5" cellspacing="5" style="text-align: center; margin: 0 auto;">
        <tr>
            <td><strong>KEY TO THE GRADING</strong></td>
            @if(Mk::getGradeList($class_type->id)->count())
                @foreach(Mk::getGradeList($class_type->id) as $gr)
                    <td><strong>{{ $gr->name }}</strong>
                        => {{ $gr->mark_from.' - '.$gr->mark_to }}
                    </td>
                @endforeach
            @endif
        </tr>
    </table>

</div>


<table style="width:100%; border-collapse:collapse; ">
    <tbody>
    <tr>
        <td><strong>NUMBER OF : </strong></td>
        <td><strong>Distinctions:</strong> {{ Mk::countDistinctions($assessments_records) }}</td>
        <td><strong>Credits:</strong> {{ Mk::countCredits($assessments_records) }}</td>
        <td><strong>Passes:</strong> {{ Mk::countPasses($assessments_records) }}</td>
        <td><strong>Failures:</strong> {{ Mk::countFailures($assessments_records) }}</td>
        <td><strong>Subjects Offered:</strong> {{ Mk::countSubjectsOffered($assessments_records) }}</td>
    </tr>

    </tbody>
</table>
