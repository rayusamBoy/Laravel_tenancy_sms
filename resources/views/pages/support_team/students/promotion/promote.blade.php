{{-- If Active students --}}
@if ($status == 1)
<button class="btn btn-sm btn-info opt-all-as-graduate float-right">Opt all as Graduate</button>
@endif
<form method="post" class="page-block" action="{{ route('students.promote', [$status, $fc, $fs, $tc, $ts]) }}">
    @csrf
    <table class="table table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Photo</th>
                <th>Name</th>
                {{-- If selection was made and the students are graduate --}}
                @if($selected && $status == 0)
                <th>Date Admitted</th>
                <th>Grad Year</th>
                @endif
                <th>Current Session</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students->sortBy('user.name') as $sr)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><img class="rounded-circle" style="height: 30px; width: 30px;" src="{{ tenant_asset($sr->user->photo) }}" alt="img"></td>
                <td>{{ $sr->user->name }}</td>
                @if($selected && $status == 0)
                <td>{{ $sr->date_admitted }}</td>
                <td>{{ $sr->grad_date }}</td>
                @endif
                <td>{{ $sr->session }}</td>
                <td>
                    <select class="form-control select" name="p-{{ $sr->id }}" id="p-{{ $sr->id }}">
                        <option value="P">Promote</option>
                        <option value="D">Don't Promote</option>
                        <option @if ($sr->grad == 1) disabled @endif value="G">Graduated</option>
                    </select>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="text-center mt-3">
        <button class="btn btn-success"><i class="material-symbols-rounded mr-2">upgrade</i> Promote Students</button>
    </div>
</form>
