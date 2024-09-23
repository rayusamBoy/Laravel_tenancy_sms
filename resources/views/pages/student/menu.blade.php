{{-- Marksheet --}}
<li class="nav-item">
    <a href="{{ route('marks.year_select', Qs::hash(Auth::user()->id)) }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.show', 'marks.year_selector', 'pins.enter']) ? 'active' : '' }}"><i class="material-symbols-rounded">bottom_sheets</i> <span>Marksheet</span></a>
</li>
{{-- Assessment Sheet --}}
<li class="nav-item">
    <a href="{{ route('assessments.year_selector', Qs::hash(Auth::user()->id)) }}" class="nav-link {{ in_array(Route::currentRouteName(), ['assessments.bulk', 'assessments.show', 'assessments.year_selector', 'assessments.show']) ? 'active' : '' }}"><i class="material-symbols-rounded">two_pager</i> <span>Assessment Sheet</span></a>
</li>