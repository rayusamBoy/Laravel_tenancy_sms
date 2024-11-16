<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use App\Repositories\PaymentRepo;
use App\Repositories\StudentRepo;
use App\Repositories\TimeTableRepo;
use App\Helpers\Qs;

Breadcrumbs::for('index', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('index'));
});

Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});

// 2fa (two factor authentication (google)) 
Breadcrumbs::for('account_security.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Account Security', route('account_security.index'));
});

// My Account 
Breadcrumbs::for('my_account', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('My Account', route('my_account'));
});

Breadcrumbs::for('my_account.update', function (BreadcrumbTrail $trail) {
    $trail->parent('my_account');
    $trail->push('Update', route('my_account.update'));
});

Breadcrumbs::for('my_account.change_pass', function (BreadcrumbTrail $trail) {
    $trail->parent('my_account');
    $trail->push('Change Password', route('my_account.change_pass'));
});

// Students
Breadcrumbs::for('students.create', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Admit Student', route('students.create'));
});

Breadcrumbs::for('students.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Admit Student', route('students.index'));
});

Breadcrumbs::for('students.list', function (BreadcrumbTrail $trail, $class_id) {
    $trail->parent('dashboard');
    $trail->push('Students List', route('students.list', $class_id));
});

Breadcrumbs::for('st.reset_pass', function (BreadcrumbTrail $trail, $st_id) {
    $trail->parent('dashboard');
    $trail->push('Reset Student Password', route('st.reset_pass', $st_id));
});

Breadcrumbs::for('students.graduated', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Graduated Students', route('students.graduated'));
});

Breadcrumbs::for('students.id_cards', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Students ID Cards', route('students.id_cards'));
});

Breadcrumbs::for('students.id_cards_manage', function (BreadcrumbTrail $trail) {
    $trail->parent('students.id_cards');
    $trail->push('Manage Students ID Cards', route('students.id_cards_manage'));
});

Breadcrumbs::for('students.show', function (BreadcrumbTrail $trail, $st_id) {
    $class_id = StudentRepo::getRecordValue(Qs::decodeHash($st_id), 'my_class_id');
    $trail->parent('students.list', $class_id);
    $trail->push('Profile View', route('students.show', $st_id));
});

Breadcrumbs::for('students.edit', function (BreadcrumbTrail $trail, $st_id) {
    $class_id = StudentRepo::getRecordValue(Qs::decodeHash($st_id), 'my_class_id');
    $trail->parent('students.list', $class_id);
    $trail->push('Profile Edit', route('students.edit', $st_id));
});

Breadcrumbs::for('students.promotion', function (BreadcrumbTrail $trail, $st_id) {
    $trail->parent('dashboard');
    $trail->push('Promote Student', route('students.promotion', $st_id));
});

Breadcrumbs::for('students.promotion_manage', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Promotion', route('students.promotion_manage'));
});

// Users
Breadcrumbs::for('users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Users', route('users.index'));
});

Breadcrumbs::for('users.edit', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('users.index');
    $trail->push('Edit User Details', route('users.edit', $user));
});

Breadcrumbs::for('users.show', function (BreadcrumbTrail $trail, $user) {
    $trail->parent('users.index');
    $trail->push('View User Profile', route('users.show', $user));
});

// Classes
Breadcrumbs::for('classes.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Classes', route('classes.index'));
});

Breadcrumbs::for('classes.edit', function (BreadcrumbTrail $trail, $class) {
    $trail->parent('classes.index');
    $trail->push('Edit Class', route('classes.edit', $class));
});

// Dorms
Breadcrumbs::for('dorms.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Dorms', route('dorms.index'));
});

Breadcrumbs::for('dorms.edit', function (BreadcrumbTrail $trail, $dorm) {
    $trail->parent('dorms.index');
    $trail->push('Edit Dorm', route('dorms.edit', $dorm));
});

// Sections
Breadcrumbs::for('sections.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Class Sections', route('sections.index'));
});

Breadcrumbs::for('sections.edit', function (BreadcrumbTrail $trail, $section) {
    $trail->parent('sections.index');
    $trail->push('Edit Section', route('sections.edit', $section));
});

// Subjects
Breadcrumbs::for('subjects.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Subjects', route('subjects.index'));
});

Breadcrumbs::for('subjects.edit_record', function (BreadcrumbTrail $trail, $subject) {
    $trail->parent('subjects.index');
    $trail->push('Edit Subject Record', route('subjects.edit_record', $subject));
});

// Exams
Breadcrumbs::for('exams.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Exams', route('exams.index'));
});

Breadcrumbs::for('exams.edit', function (BreadcrumbTrail $trail, $exam) {
    $trail->parent('exams.index');
    $trail->push('Edit Exam', route('exams.edit', $exam));
});

// Grades
Breadcrumbs::for('grades.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Grades', route('grades.index'));
});

Breadcrumbs::for('grades.edit', function (BreadcrumbTrail $trail, $exam) {
    $trail->parent('grades.index');
    $trail->push('Edit Grade', route('grades.edit', $exam));
});

// Divisions
Breadcrumbs::for('divisions.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Divisions', route('divisions.index'));
});

Breadcrumbs::for('divisions.edit', function (BreadcrumbTrail $trail, $exam) {
    $trail->parent('divisions.index');
    $trail->push('Edit Division', route('divisions.edit', $exam));
});

// Marks
Breadcrumbs::for('marks.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Exam Marks', route('marks.index'));
});

Breadcrumbs::for('marks.tabulation', function (BreadcrumbTrail $trail, $exam, $class, $sec_id) {
    $trail->parent('dashboard');
    $trail->push('Tabulation Sheet', route('marks.tabulation', [$exam, $sec_id], $class));
});

Breadcrumbs::for('marks.batch', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Batch Marks', route('marks.batch'));
});

Breadcrumbs::for('marks.manage', function (BreadcrumbTrail $trail, $exam, $class, $section, $subject) {
    $trail->parent('dashboard');
    $trail->push('Manage Exam Marks', route('marks.selector', [$exam, $section, $subject], $class));
});

Breadcrumbs::for('marks.bulk', function (BreadcrumbTrail $trail, $class, $section) {
    $trail->parent('dashboard');
    $trail->push('Select Student Marksheet', route('marks.bulk', $class, $section));
});

Breadcrumbs::for('marks.bulk_select', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Select Student Marksheet', route('marks.bulk_select'));
});

Breadcrumbs::for('marks.year_selector', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('marks.bulk_select');
    $trail->push('Select Exam Year', route('marks.year_selector', $id));
});

Breadcrumbs::for('marks.show', function (BreadcrumbTrail $trail, $id, $year) {
    $sid = Qs::hash($id);
    $trail->parent('marks.year_selector', $sid);
    $trail->push('Student Marksheet', route('marks.index', $id, $year));
});

Breadcrumbs::for('marks.convert', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Convert Exam Marks', route('marks.convert'));
});

// Continous Assessments
Breadcrumbs::for('assessments.list', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Continous Assessments List', route('assessments.list'));
});

Breadcrumbs::for('assessments.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Select Continous Assessments', route('assessments.index'));
});

Breadcrumbs::for('assessments.manage', function (BreadcrumbTrail $trail, $exam, $class, $section, $subject) {
    $trail->parent('dashboard');
    $trail->push('Select Continous Assessments', route('assessments.selector', [$exam, $section, $subject], $class));
});

Breadcrumbs::for('assessments.progressive', function (BreadcrumbTrail $trail, $exam, $class, $sec_id) {
    $trail->parent('dashboard');
    $trail->push('Progressive Sheet', route('assessments.progressive', [$exam, $sec_id], $class));
});

Breadcrumbs::for('assessments.bulk', function (BreadcrumbTrail $trail, $class, $section) {
    $trail->parent('dashboard');
    $trail->push('Select Student Assessmentsheet', route('assessments.bulk', $class, $section));
});

Breadcrumbs::for('assessments.bulk_select', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Select Student Assessmentsheet', route('assessments.bulk_select'));
});

Breadcrumbs::for('assessments.year_selector', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('assessments.bulk_select');
    $trail->push('Select Exam Year', route('assessments.year_selector', $id));
});

Breadcrumbs::for('assessments.show', function (BreadcrumbTrail $trail, $id, $year) {
    $sid = Qs::hash($id);
    $trail->parent('assessments.year_selector', $sid);
    $trail->push('Student Assessmentsheet', route('assessments.index', $id, $year));
});

// Settings
Breadcrumbs::for('settings.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Sytem Settings', route('settings.index'));
});

Breadcrumbs::for('settings_non_tenancy.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Sytem Settings', route('settings_non_tenancy.index'));
});

// Timetable
Breadcrumbs::for('tt.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Manage Timetables', route('tt.index'));
});

Breadcrumbs::for('ttr.show', function (BreadcrumbTrail $trail, $ttr) {
    $trail->parent('tt.index');
    $trail->push('View Timetable', route('ttr.show', $ttr));
});

Breadcrumbs::for('ttr.edit', function (BreadcrumbTrail $trail, $ttr) {
    $trail->parent('tt.index');
    $trail->push('Edit Timetable', route('ttr.edit', $ttr));
});

Breadcrumbs::for('ttr.manage', function (BreadcrumbTrail $trail, $ttr) {
    $trail->parent('tt.index');
    $trail->push('Manage Timetable Record', route('ttr.manage', $ttr));
});

//Payments
Breadcrumbs::for('payments.create', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Create Payment', route('payments.create'));
});

Breadcrumbs::for('payments.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Select Payments Year', route('payments.index'));
});

Breadcrumbs::for('payments.show', function (BreadcrumbTrail $trail, $payment) {
    $trail->parent('payments.index');
    $trail->push('Manage Payments', route('payments.show', $payment));
});

Breadcrumbs::for('payments.edit', function (BreadcrumbTrail $trail, $payment) {
    $trail->parent('payments.index');
    $trail->push('Edit Payment', route('payments.edit', $payment));
});

Breadcrumbs::for('payments.manage', function (BreadcrumbTrail $trail, $class_id) {
    $trail->parent('dashboard');
    $trail->push('Student Payments', route('payments.manage', $class_id));
});

Breadcrumbs::for('payments.invoice', function (BreadcrumbTrail $trail, $id, $year) {
    $class_id = PaymentRepo::getRecordValue($id, 'my_class_id');
    $trail->parent('payments.manage', $class_id);
    $trail->push('Manage Payments', route('payments.invoice', $id, $year));
});

Breadcrumbs::for('payments.status', function (BreadcrumbTrail $trail, $id, $year) {
    $trail->parent('my_children');
    $trail->push('Payments Status', route('payments.status', $id, $year));
});

// Pins
Breadcrumbs::for('pins.create', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Create Pins', route('pins.create'));
});

Breadcrumbs::for('pins.index', function (BreadcrumbTrail $trail) {
    $trail->parent('pins.create');
    $trail->push('Pins', route('pins.index'));
});

Breadcrumbs::for('pins.enter', function (BreadcrumbTrail $trail, $sid) {
    $trail->parent('marks.year_selector', Qs::hash($sid));
    $trail->push('Enter Pin', route('pins.enter', $sid));
});

// Children
Breadcrumbs::for('my_children', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('My Children', route('my_children'));
});

// Time Slots
Breadcrumbs::for('ts.edit', function (BreadcrumbTrail $trail, $ts_id) {
    $time_table = new TimeTableRepo;
    $ttr_id = $time_table->findTimeSlot($ts_id)->ttr_id;
    $trail->parent('ttr.manage', $ttr_id);
    $trail->push('Edit Time Slots', route('ts.edit', $ts_id));
});

// Bin
Breadcrumbs::for('bin', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Recycle Bin', route('bin'));
});

// Statistics
Breadcrumbs::for('statistics.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Statistics', route('statistics.index'));
});

// Analytics
Breadcrumbs::for('analytics.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Analytics', route('analytics.index'));
});

Breadcrumbs::for('analytics.fetch_data', function (BreadcrumbTrail $trail) {
    $trail->parent('analytics.index');
    $trail->push('Data', route('analytics.fetch_data'));
});

// Schedule
Breadcrumbs::for('schedule.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Schedule', route('schedule.index'));
});

Breadcrumbs::for('events.manage', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Manage Events', route('events.manage'));
});

Breadcrumbs::for('events.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('home');
    $trail->push('Edit Event', route('events.edit', $id));
});

// Artisan Commands
Breadcrumbs::for('artisan_commands.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Artisan Commands', route('artisan_commands.index'));
});

// Query Builder
Breadcrumbs::for('query_builder.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Query Builder', route('query_builder.index'));
});

Breadcrumbs::for('query_builder.select', function (BreadcrumbTrail $trail) {
    $trail->parent('query_builder.index');
    $trail->push('Query', route('query_builder.select'));
});

// Notices
Breadcrumbs::for('notices.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Notices', route('notices.index'));
});

Breadcrumbs::for('notices.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('notices.index');
    $trail->push('Edit', route('notices.edit'));
});

// Messages
Breadcrumbs::for('messages', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Messages', route('messages'));
});

Breadcrumbs::for('messages.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Messages', route('messages.index'));
});

Breadcrumbs::for('messages.show', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('messages.index');
    $trail->push('Show', route('messages.show', $id));
});

Breadcrumbs::for('messages.create', function (BreadcrumbTrail $trail) {
    $trail->parent('messages.index');
    $trail->push('Create', route('messages.create'));
});

// LOGS HISTORIES
Breadcrumbs::for('logs.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Manage Logs', route('logs.index'));
});

Breadcrumbs::for('tenancy_logs.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Manage Logs', route('tenancy_logs.index'));
});

// Books
Breadcrumbs::for('books.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Manage Books', route('books.index'));
});

Breadcrumbs::for('book_requests.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Manage Book Requests', route('book_requests.index'));
});

Breadcrumbs::for('books.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('books.index');
    $trail->push('Edit a Book', route('books.edit', $id));
});

Breadcrumbs::for('book_requests.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('book_requests.index');
    $trail->push('Edit Book Request', route('book_requests.edit', $id));
});

// Tenants
Breadcrumbs::for('tenants.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Manage Tenants', route('tenants.index'));
});

Breadcrumbs::for('tenants.edit', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('tenants.index');
    $trail->push('Edit Tenant', route('tenants.edit', $id));
});

// Notifications
Breadcrumbs::for('notifications.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Manage Notifications', route('notifications.index'));
});

// TEST
Breadcrumbs::for('test', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Test', route('test'));
});
