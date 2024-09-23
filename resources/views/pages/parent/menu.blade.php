{{-- My Children --}}
<li class="nav-item">
    <a href="{{ route('my_children') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_children', 'marks.year_selector', 'assessments.year_selector', 'payments.status', 'students.show', 'marks.show', 'assessments.show']) ? 'active' : '' }}"><i class="material-symbols-rounded">account_child_invert</i><span>My Children</span></a>
</li>