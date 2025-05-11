{{--Print Body--}}
{{--If is the last page, do not break page--}}
<div class="print" xmlns:margin-top="http://www.w3.org/1999/xhtml" style="{{ ($page_number === $last_page) ? 'min-height: 100vh; display: block; position: relative' : 'min-height: 100vh; display: block; position: relative; page-break-after: always' }}">
    {{--Top Logo--}}
    <div style="position: relative; text-align: center; ">
        <img src="{{ $logo }}" style="max-width: 60px; max-height: 60px;" />
    </div>
    {{--Headings--}}
    <h2 style="text-align: center;">KNOW OUR STAFF {{ strtoupper(Qs::getSetting('system_name')) }}</h2>
    <h3 style="text-align: center;">STAFF INFORMATION</h3>
    {{--Table Data--}}
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; margin: 10px auto; table-layout: fixed" border="1">
        <tbody>
            @foreach($keys as $key)
            <tr>
                <th style="text-align: start">{{ str_replace('_', ' ', ucfirst($key)) }}</th>
                @foreach($staff as $stf)
                @php $data = $stf->$key @endphp
                @php $data = ($key == 'subjects_studied') ? trim(str_replace('"', ' ', $data), '[]') : $data @endphp
                <td style="text-align: center; word-wrap: break-word">@if($key == 'photo') <img src="{{ $data }}" alt="photo" width="60px" height="60px" style="padding: 3px"> @else {{ $data }} @endif</td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>
    {{-- Footer --}}
    <small style="position: absolute; bottom: 0; right: 0">Page {{ $page_number }}</small>
    <small style="position: absolute; bottom: 0; left: 0">Generated from: {{ route("index") }}, on {{ date('d/m/Y h:i:s a', time()) }}</small>
</div>

{{--Print Body ends--}}
