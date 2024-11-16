<div class="sidebar sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="javascript:;" class="sidebar-mobile-main-toggle">
            <i class="material-symbols-rounded">arrow_back</i>
        </a>
        Navigation
        <a href="javascript:;" class="sidebar-mobile-expand">
            <i class="material-symbols-rounded">format_indent_increase</i>
            <i class="material-symbols-rounded">format_indent_decrease</i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">
                        <a href="{{ route('my_account') }}"><img src="{{ Usr::getTenantAwarePhoto(auth()->user()->photo) }}" width="38" height="38" class="rounded-circle" alt="photo"></a>
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">{{ auth()->user()->name }}</div>
                        <div class="font-size-sm opacity-50 d-flex">
                            <i class="material-symbols-rounded">person</i> &nbsp;{{ ucwords(str_replace('_', ' ', auth()->user()->user_type)) }}
                        </div>
                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="{{ route('my_account') }}"><i class="material-symbols-rounded">settings</i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile border-0">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['dashboard', 'home']) ? 'active' : '' }}">
                        <i class="material-symbols-rounded">{{ Route::is('dashboard') ? 'dashboard' : 'home' }}</i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{--Academics--}}
                @if(Qs::userIsAcademic())
                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['tt.index', 'ttr.edit', 'ttr.show', 'ttr.manage']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="javascript:;" class="nav-link"><i class="material-symbols-rounded">school</i> <span> Academics</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Manage Academics">
                        {{--Timetables--}}
                        <li class="nav-item"><a href="{{ route('tt.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['tt.index']) ? 'active' : '' }}">Timetables</a></li>
                    </ul>
                </li>
                @endif

                {{--Administrative--}}
                @if(Qs::userIsAdministrative())
                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.invoice', 'payments.receipts', 'payments.edit', 'payments.manage', 'payments.show',]) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="javascript:;" class="nav-link"><i class="material-symbols-rounded">apartment</i> <span> Administrative</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Administrative">

                        {{--Payments--}}
                        @if(Qs::userIsTeamAccount())
                        <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.create', 'payments.edit', 'payments.manage', 'payments.show', 'payments.invoice']) ? 'nav-item-expanded' : '' }}">

                            <a href="javascript:;" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.create', 'payments.manage', 'payments.show', 'payments.invoice']) ? 'active' : '' }}">Payments</a>

                            <ul class="nav nav-group-sub">
                                <li class="nav-item"><a href="{{ route('payments.create') }}" class="nav-link {{ Route::is('payments.create') ? 'active' : '' }}">Create Payment</a></li>
                                <li class="nav-item"><a href="{{ route('payments.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.index', 'payments.edit', 'payments.show']) ? 'active' : '' }}">Manage Payments</a></li>
                                <li class="nav-item"><a href="{{ route('payments.manage') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['payments.manage', 'payments.invoice', 'payments.receipts']) ? 'active' : '' }}">Student Payments</a></li>
                            </ul>

                        </li>
                        @endif
                    </ul>
                </li>
                @endif

                {{--Manage Students--}}
                @if(Qs::userIsTeamSATCL())
                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.create', 'students.list', 'students.edit', 'students.show', 'students.promotion', 'students.promotion_manage', 'students.graduated', 'students.id_cards', 'students.id_cards_manage', 'students.manage_permissions']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="javascript:;" class="nav-link"><i class="material-symbols-rounded">groups</i> <span> Students</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Manage Students">
                        {{--Admit Student--}}
                        @if(Qs::userIsTeamSA())
                        <li class="nav-item">
                            <a href="{{ route('students.create') }}" class="nav-link {{ (Route::is('students.create')) ? 'active' : '' }}">Admit Student</a>
                        </li>
                        @endif

                        {{--Student Information--}}
                        <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.list', 'students.edit', 'students.show']) ? 'nav-item-expanded' : '' }}">
                            <a href="javascript:;" class="nav-link {{ in_array(Route::currentRouteName(), ['students.list', 'students.edit', 'students.show']) ? 'active' : '' }}">Student Information</a>
                            <ul class="nav nav-group-sub">
                                @foreach(App\Models\MyClass::orderBy('name')->get() as $c)
                                <li class="nav-item"><a href="{{ route('students.list', $c->id) }}" class="nav-link ">{{ $c->name }}</a></li>
                                @endforeach
                            </ul>
                        </li>

                        @if(Qs::userIsTeamSA())
                        {{--Student Promotion--}}
                        <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['students.promotion', 'students.promotion_manage']) ? 'nav-item-expanded' : '' }}"><a href="javascript:;" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion', 'students.promotion_manage' ]) ? 'active' : '' }}">Student Promotion</a>
                            <ul class="nav nav-group-sub">
                                <li class="nav-item"><a href="{{ route('students.promotion') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion']) ? 'active' : '' }}">Promote Students</a></li>
                                <li class="nav-item"><a href="{{ route('students.promotion_manage') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.promotion_manage']) ? 'active' : '' }}">Manage Promotions</a></li>
                            </ul>
                        </li>

                        {{--Student Graduated--}}
                        <li class="nav-item"><a href="{{ route('students.graduated') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.graduated' ]) ? 'active' : '' }}">Students Graduated</a></li>
                        {{--Student ID Cards--}}
                        @if(Qs::userIsSuperAdmin())
                        <li class="nav-item"><a href="{{ route('students.id_cards') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['students.id_cards', 'students.id_cards_manage']) ? 'active' : '' }}">Students ID Cards</a></li>
                        @endif

                        @endif
                    </ul>
                </li>
                @endif

                @if(Qs::userIsTeamSA())
                {{--Manage Users--}}
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['users.index', 'users.show', 'users.edit']) ? 'active' : '' }}"><i class="material-symbols-rounded">group</i> <span> Users</span></a>
                </li>

                {{--Manage Classes--}}
                <li class="nav-item">
                    <a href="{{ route('classes.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['classes.index','classes.edit']) ? 'active' : '' }}"><i class="material-symbols-rounded">domain</i> <span> Classes</span></a>
                </li>

                {{--Manage Dorms--}}
                <li class="nav-item">
                    <a href="{{ route('dorms.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['dorms.index','dorms.edit']) ? 'active' : '' }}"><i class="material-symbols-rounded">home_work</i> <span> Dormitories</span></a>
                </li>

                {{--Manage Sections--}}
                <li class="nav-item">
                    <a href="{{ route('sections.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['sections.index','sections.edit',]) ? 'active' : '' }}"><i class="material-symbols-rounded">stacks</i> <span>Sections</span></a>
                </li>

                {{--Manage Subjects--}}
                <li class="nav-item">
                    <a href="{{ route('subjects.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['subjects.index','subjects.edit',]) ? 'active' : '' }}"><i class="material-symbols-rounded">subject</i> <span>Subjects</span></a>
                </li>
                @endif

                {{--Exam--}}
                @if(Qs::userIsTeamSAT())
                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['exams.index', 'exams.edit', 'grades.index', 'grades.edit', 'marks.index', 'marks.convert', 'marks.manage', 'marks.bulk', 'marks.tabulation', 'marks.show', 'marks.batch', 'divisions.index', 'divisions.edit']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="javascript:;" class="nav-link"><i class="material-symbols-rounded">article</i> <span> Exams</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Manage Exams">
                        @if(Qs::userIsTeamSA())

                        {{--Exam list--}}
                        <li class="nav-item">
                            <a href="{{ route('exams.index') }}" class="nav-link {{ (Route::is('exams.index')) ? 'active' : '' }}">Exam List</a>
                        </li>

                        {{--Grades list--}}
                        <li class="nav-item">
                            <a href="{{ route('grades.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['grades.index', 'grades.edit']) ? 'active' : '' }}">Grades</a>
                        </li>

                        {{--Divisions list--}}
                        <li class="nav-item">
                            <a href="{{ route('divisions.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['divisions.index', 'divisions.edit']) ? 'active' : '' }}">Divisions</a>
                        </li>

                        {{--Tabulation Sheet--}}
                        <li class="nav-item">
                            <a href="{{ route('marks.tabulation') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.tabulation']) ? 'active' : '' }}">Tabulation Sheet</a>
                        </li>
                        @endif

                        {{--Marks Manage--}}
                        <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['marks.index', 'marks.convert', 'marks.batch', 'marks.manage']) ? 'nav-item-expanded nav-item-open' : '' }}">
                            <a href="javascript:;" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.index', 'marks.convert']) ? 'active' : '' }}">Marks</a>
                            <ul class="nav nav-group-sub">
                                {{--Marks Batch--}}
                                @if(Qs::userIsTeamSAT())
                                <li class="nav-item">
                                    <a href="{{ route('marks.batch') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.batch']) ? 'active' : '' }}">Batch</a>
                                </li>
                                @endif
                                <li class="nav-item"><a href="{{ route('marks.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.index', 'marks.manage']) ? 'active' : '' }}">Manage</a></li>
                                <li class="nav-item"><a href="{{ route('marks.convert') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.convert']) ? 'active' : '' }}">Convert</a></li>
                            </ul>
                        </li>

                        {{--Marksheet--}}
                        <li class="nav-item">
                            <a href="{{ route('marks.bulk') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['marks.bulk', 'marks.show']) ? 'active' : '' }}">Marksheet</a>
                        </li>
                    </ul>
                </li>
                @endif

                {{--Continous Assessments (CA)--}}
                @if(Qs::userIsTeamSAT())
                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['assessments.list', 'assessments.index', 'assessments.manage', 'assessments.progressive', 'assessments.bulk',  'assessments.year_selector', 'assessments.show']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="javascript:;" class="nav-link"><i class="material-symbols-rounded">folder</i> <span>Assessments</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Manage Assessments">
                        @if(Qs::userIsTeamSA())
                        {{--CA list--}}
                        <li class="nav-item">
                            <a href="{{ route('assessments.list') }}" class="nav-link {{ (Route::is('assessments.list')) ? 'active' : '' }}">CA List</a>
                        </li>
                        @endif

                        {{--Progressive Sheet--}}
                        @if(Qs::userIsClassSectionTeacher() || Qs::userIsTeamSA())
                        <li class="nav-item">
                            <a href="{{ route('assessments.progressive') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['assessments.progressive']) ? 'active' : '' }}">Progressive Sheet</a>
                        </li>
                        @endif

                        {{--Assessments Manage--}}
                        <li class="nav-item">
                            <a href="{{ route('assessments.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['assessments.index', 'assessments.manage']) ? 'active' : '' }}">Assessments</a>
                        </li>

                        {{--Assessment Sheet--}}
                        <li class="nav-item">
                            <a href="{{ route('assessments.bulk') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['assessments.bulk', 'assessments.show', 'assessments.year_selector', 'assessments.show']) ? 'active' : '' }}">Assessment Sheet</a>
                        </li>
                    </ul>
                    @endif
                </li>

                @if(Qs::userIsTeamLibrary())
                {{--Books--}}
                <li class="nav-item nav-item-submenu {{ in_array(Route::currentRouteName(), ['books.index', 'book_requests.index', 'books.edit', 'book_requests.edit']) ? 'nav-item-expanded nav-item-open' : '' }} ">
                    <a href="javascript:;" class="nav-link"><i class="material-symbols-rounded">library_books</i> <span> Books</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Manage Books">
                        {{-- Manage Books  --}}
                        <li class="nav-item">
                            <a href="{{ route('books.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['books.index', 'books.edit']) ? 'active' : '' }}">Manage</a>
                        </li>
                        {{-- Manage Book Requests  --}}
                        <li class="nav-item">
                            <a href="{{ route('book_requests.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['book_requests.index', 'book_requests.edit']) ? 'active' : '' }}">Manage Requests</a>
                        </li>
                    </ul>
                </li>
                @endif

                @if(Qs::userIsTeamSA())
                {{--Query Builder--}}
                <li class="nav-item">
                    <a href="{{ route('query_builder.index') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['query_builder.index', 'query_builder.select']) ? 'active' : '' }}"><i class="material-symbols-rounded">join</i> <span> Query Builder</span></a>
                </li>
                @endif

                @include('pages.'.Qs::getUserType().'.menu')

                {{--Manage Account--}}
                <li class="nav-item">
                    <a href="{{ route('my_account') }}" class="nav-link {{ in_array(Route::currentRouteName(), ['my_account']) ? 'active' : '' }}"><i class="material-symbols-rounded">account_circle</i> <span>My Account</span></a>
                </li>
            </ul>
        </div>
    </div>
</div>
