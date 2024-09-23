<div>
    <table class="td-left" style="border-collapse:collapse;">
        <tbody>
        <tr>
            <td><strong>CLASS TEACHER'S COMMENT:</strong></td>
            <td>  {{ $asr->t_comment ?: str_repeat('__', 50) }}</td>
        </tr>
        <tr>
            <td><strong>PRINCIPAL'S COMMENT:</strong></td>
            <td>  {{ $asr->p_comment ?: str_repeat('__', 50) }}</td>
        </tr>
        {{--<tr>
            <td><strong>NEXT TERM BEGINS:</strong></td>
            <td>{{ date('l\, jS F\, Y', strtotime($s['term_begins'])) }}</td>
        </tr>
        <tr>
            <td><strong>NEXT TERM FEES:</strong></td>
            <td>TSh {{ $s['next_term_fees_'.strtolower($ct)] }}/-</td>
        </tr>--}}
        </tbody>
    </table>
</div>
