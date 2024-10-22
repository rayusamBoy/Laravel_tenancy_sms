<?php

declare(strict_types=1);

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Requests\CustomEmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    // If you change this middleware and decide to use different tenancy initialization middleware ie., InitializeTenancyBySubdomain::class.
    // You need to set its $onFail static property in 'app\Providers\TenancyServiceProvider.php' file under the 'boot' method for custom
    // behavior that should happen when a tenant couldn't be identified.
    InitializeTenancyByDomain::class,

    PreventAccessFromCentralDomains::class,
])->group(function () {

    Auth::routes();

    Route::group(['middleware' => ['auth', 'checkForPassUpdate']], function () {
        /*************** Two factor authentication *****************/
        Route::group(['prefix' => 'auth/2fa'], function () {
            Route::get('account/security', 'Auth\AccountSecurityController@index')->name('account_security.index')->middleware('2fa');
            Route::match(['POST', 'GET'], 'account/recovery', 'Auth\AccountSecurityController@account_recovery')->name('account_security.account_recovery');
            Route::get('show', 'Auth\AccountSecurityController@showGoogle2FAVerification')->name('2fa.show');
            // The middleware in this route will capture the value from the form (the one time password that need to be verified).
            // The middleware will attempt to verify the captured value and return to the form with error message if there was any error.
            // Otherwise; if the value is verified, the 'authenticate' method will be called. See the medhod for more info.
            Route::post('authenticate', 'Auth\AccountSecurityController@authenticate')->name('2fa.authenticate')->middleware(['2fa', 'throttle:5,1']);
            Route::post('update/code', 'Auth\AccountSecurityController@update_secret_codes')->name('2fa.update_secret_codes')->middleware('2fa');
            Route::patch('code/null/{user_id?}', 'Auth\AccountSecurityController@null_secret_code')->name('2fa.null_secret_code')->middleware('2fa');
            Route::delete('logout/browser/sessions/others', 'Auth\AccountSecurityController@logout_other_browser_sessions')->name('2fa.logout_other_browser_sessions');
        });

        Route::group(['middleware' => '2fa'], function () {
            /*************** Dashboard **************/
            Route::get('/', 'HomeController@dashboard')->name('index');
            Route::get('home', 'HomeController@dashboard')->name('home');
            Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');

            /*************** Calendar Events **************/
            Route::group(['prefix' => 'schedule'], function () {
                Route::get('index', 'ScheduleController@index')->name('schedule.index');
                Route::group(['prefix' => 'events', 'middleware' => 'teamSA'], function () {
                    Route::post('create', 'ScheduleController@create_event')->name('events.create');
                    Route::get('edit/{event_id}', 'ScheduleController@edit_event')->name('events.edit');
                    Route::get('delete', 'ScheduleController@delete_event')->name('events.delete');
                    Route::post('update/{event_id}', 'ScheduleController@update_event')->name('events.update');
                });
            });

            /****************** My Account ***************/
            Route::group(['prefix' => 'my_account'], function () {
                Route::get('profile/edit', 'MyAccountController@edit_profile')->name('my_account');
                Route::put('profile/update', 'MyAccountController@update_profile')->name('my_account.update');
                Route::put('password/change', 'MyAccountController@change_pass')->name('my_account.change_pass');
                Route::put('other', 'MyAccountController@other')->name('my_account.other');
            });

            /****************** Email Verification ***************/
            Route::get('/email/verify/{id}/{hash}', function (CustomEmailVerificationRequest $request) {
                $request->fulfill();
                return redirect('/home')->with('pop_success', __('msg.email_verified'));
            })->middleware(['signed'])->name('verification.verify');

            Route::get('email/verification/notification', function (Request $request) {
                $request->user()->sendEmailVerificationNotification();
                return back()->with('flash_success', __('msg.verification_sent'));
            })->middleware(['throttle:6,1'])->name('verification.send');

            /*************** Support Team *****************/
            Route::group(['namespace' => 'SupportTeam'], function () {
                /*************** Statistics **************/
                Route::group(['prefix' => 'statistics'], function () {
                    Route::get('index/{exam_id?}', 'StatisticsController@index')->name('statistics.index');
                });

                /*************** Students *****************/
                Route::group(['prefix' => 'students'], function () {

                    Route::get('password/reset/{st_id}', 'StudentRecordController@reset_pass')->name('st.reset_pass');
                    Route::get('graduated', 'StudentRecordController@graduated')->name('students.graduated');
                    Route::put('not_graduated/{s_id}/{prom_id}', 'StudentRecordController@not_graduated')->name('st.not_graduated');
                    Route::get('list/{class_id}', 'StudentRecordController@list_by_class')->name('students.list')->middleware('teamSATCL');

                    /* ID Cards */
                    Route::get('id/cards', 'StudentRecordController@id_cards')->name('students.id_cards');
                    Route::post('id/cards/manage/{class_id?}/{issued_date?}/{expire_date?}/{theme?}', 'StudentRecordController@id_cards_manage')->name('students.id_cards_manage');
                    Route::get('id/cards/print/class/{class_id?}/{issued?}/{expire?}/{theme?}/{phone?}/{class_from?}/{class_to?}/{motto?}/{brightness?}/{website_link?}', 'StudentRecordController@print_class_id_cards')->name('students.print_class_id_cards');
                    Route::post('id/cards/print/selected', 'StudentRecordController@print_selected_id_cards')->name('students.print_selected_id_cards');

                    /* Promotions */
                    Route::post('promote/selector', 'PromotionController@selector')->name('students.promote_selector');
                    Route::get('promotion/manage', 'PromotionController@manage')->name('students.promotion_manage');
                    Route::delete('promotion/reset/sigle/{pid}', 'PromotionController@reset')->name('students.promotion_reset');
                    Route::delete('promotion/reset/all', 'PromotionController@reset_all')->name('students.promotion_reset_all');
                    Route::get('promotion/{status?}/{fc?}/{fs?}/{tc?}/{ts?}', 'PromotionController@promotion')->name('students.promotion');
                    Route::post('promote/{status}/{fc}/{fs}/{tc}/{ts}', 'PromotionController@promote')->name('students.promote');
                    Route::post('promotion/remarks/{id}', 'PromotionController@remarks')->name('students.promotion_remarks');

                    /* Students Block and Unblock */
                    Route::post('block/class', 'StudentRecordController@block_all_class')->name('students.block_all_class')->middleware('teamSA');
                    Route::post('unblock/class', 'StudentRecordController@unblock_all_class')->name('students.unblock_all_class')->middleware('teamSA');
                    Route::post('block/graduated', 'StudentRecordController@block_all_graduated')->name('students.block_all_graduated')->middleware('teamSA');
                    Route::post('unblock/graduated', 'StudentRecordController@unblock_all_graduated')->name('students.unblock_all_graduated')->middleware('teamSA');
                });

                /*************** Users *****************/
                Route::group(['prefix' => 'users'], function () {
                    Route::get('password/reset/{id}', 'UserController@reset_pass')->name('users.reset_pass');
                    Route::post('user/update/staff/data/edit_state', 'UserController@update_staff_data_edit_state')->name('users.update_staff_data_edit_state');
                    Route::post('user/update/user/blocked_state', 'UserController@update_user_blocked_state')->name('users.update_user_blocked_state');
                });

                /*************** Query Builder *****************/
                Route::group(['prefix' => 'query_bulder'], function () {
                    Route::get('index', 'QueryBuilderController@index')->name('query_builder.index');
                    Route::get('select/query', 'QueryBuilderController@select')->name('query_builder.select');
                    Route::get('staff/data/print', 'QueryBuilderController@print_staff_data')->name('query_builder.print_staff_data');
                });

                /*************** TimeTables *****************/
                Route::group(['prefix' => 'timetables'], function () {
                    Route::get('index', 'TimeTableController@index')->name('tt.index');

                    Route::group(['middleware' => 'teamSA'], function () {
                        Route::post('store', 'TimeTableController@store')->name('tt.store');
                        Route::put('update/{tt}', 'TimeTableController@update')->name('tt.update');
                        Route::delete('delete/{tt}', 'TimeTableController@delete')->name('tt.delete');
                    });

                    /*************** TimeTable Records *****************/
                    Route::group(['prefix' => 'records'], function () {

                        Route::group(['middleware' => 'teamSA'], function () {
                            Route::get('manage/{ttr}', 'TimeTableController@manage')->name('ttr.manage');
                            Route::post('store', 'TimeTableController@store_record')->name('ttr.store');
                            Route::get('edit/{ttr}', 'TimeTableController@edit_record')->name('ttr.edit');
                            Route::put('update/{ttr}', 'TimeTableController@update_record')->name('ttr.update');
                        });

                        Route::get('show/{ttr}', 'TimeTableController@show_record')->name('ttr.show');
                        Route::get('print/{ttr}', 'TimeTableController@print_record')->name('ttr.print');
                        Route::delete('destroy/{ttr}', 'TimeTableController@delete_record')->name('ttr.destroy');
                    });

                    /*************** Time Slots *****************/
                    Route::group(['prefix' => 'time_slots', 'middleware' => 'teamSA'], function () {
                        Route::post('store', 'TimeTableController@store_time_slot')->name('ts.store');
                        Route::post('use/{ttr}', 'TimeTableController@use_time_slot')->name('ts.use');
                        Route::get('edit/{ts}', 'TimeTableController@edit_time_slot')->name('ts.edit');
                        Route::delete('delete/{ts}', 'TimeTableController@delete_time_slot')->name('ts.destroy');
                        Route::put('update/{ts}', 'TimeTableController@update_time_slot')->name('ts.update');
                    });
                });

                /*************** Notices *****************/
                Route::group(['prefix' => 'notices'], function () {
                    Route::post('set_viewed', 'NoticeController@set_as_viewed')->name('notices.set_viewed');
                    Route::group(['middleware' => 'teamAdministrative'], function () {
                        Route::get('index', 'NoticeController@index')->name('notices.index');
                        Route::post('store', 'NoticeController@store')->name('notices.store');
                        Route::delete('destroy/{notice}', 'NoticeController@destroy')->name('notices.destroy');
                        Route::get('edit/{notice_id?}', 'NoticeController@edit')->name('notices.edit');
                        Route::post('update/{notice}', 'NoticeController@update_record')->name('notices.update');
                    });
                });

                /*************** Payments *****************/
                Route::group(['prefix' => 'payments'], function () {
                    Route::get('manage/{class_id?}', 'PaymentController@manage')->name('payments.manage');
                    Route::get('invoice/{id}/{year?}', 'PaymentController@invoice')->name('payments.invoice');
                    Route::get('status/{id}/{year?}', 'PaymentController@status')->name('payments.status');
                    Route::get('receipts/{id}/{notification_id?}', 'PaymentController@receipts')->name('payments.receipts');
                    Route::get('receipts/pdf/{id}', 'PaymentController@pdf_receipts')->name('payments.pdf_receipts');
                    Route::post('select/year', 'PaymentController@select_year')->name('payments.select_year');
                    Route::post('select/class', 'PaymentController@select_class')->name('payments.select_class');
                    Route::delete('record/reset/{id}', 'PaymentController@reset_record')->name('payments.reset_record');
                    Route::post('pay/now/{id}', 'PaymentController@pay_now')->name('payments.pay_now');
                });

                /*************** Pins *****************/
                Route::group(['prefix' => 'pins'], function () {
                    Route::get('index', 'PinController@index')->name('pins.index');
                    Route::get('create', 'PinController@create')->name('pins.create');
                    Route::post('store', 'PinController@store')->name('pins.store');
                    Route::get('enter/{id}', 'PinController@enter_pin')->name('pins.enter');
                    Route::post('verify/{id}', 'PinController@verify')->name('pins.verify');
                    Route::delete('destroy', 'PinController@destroy')->name('pins.destroy');
                });

                /*************** Marks *****************/
                Route::group(['prefix' => 'marks'], function () {

                    // FOR teamSA
                    Route::group(['middleware' => 'teamSAT'], function () {
                        Route::get('batch', 'MarkController@batch')->name('marks.batch');
                        Route::put('batch/update', 'MarkController@batch_update')->name('marks.batch_update');
                        Route::get('batch/template', 'MarkController@batch_template')->name('marks.batch_template');
                        Route::post('batch/delete', 'MarkController@batch_delete')->name('marks.batch_delete');
                        Route::put('batch/upload', 'MarkController@batch_upload')->name('marks.batch_upload');
                        Route::get('tabulation/{exam?}/{class?}/{sec_id?}', 'MarkController@tabulation')->name('marks.tabulation');
                        Route::post('tabulation/select', 'MarkController@tabulation_select')->name('marks.tabulation_select');
                        Route::get('tabulation/print/{exam}/{class}/{sec_id}', 'MarkController@print_tabulation')->name('marks.print_tabulation');
                    });

                    // FOR teamSAT
                    Route::group(['middleware' => 'teamSAT'], function () {
                        Route::get('index', 'MarkController@index')->name('marks.index');
                        Route::get('convert', 'MarkController@convert')->name('marks.convert');
                        Route::get('manage/{exam}/{class}/{subject}/{section?}', 'MarkController@manage')->name('marks.manage');
                        Route::put('update/{exam}/{class}/{subject}/{section?}', 'MarkController@update')->name('marks.update');
                        Route::put('comment/update/{exr_id}', 'MarkController@comment_update')->name('marks.comment_update');
                        Route::put('skills/update/{skill}/{exr_id}', 'MarkController@skills_update')->name('marks.skills_update');
                        Route::post('selector', 'MarkController@selector')->name('marks.selector');
                        Route::get('bulk/{class?}/{section?}', 'MarkController@bulk')->name('marks.bulk');
                        Route::post('bulk/select', 'MarkController@bulk_select')->name('marks.bulk_select');
                    });

                    Route::get('select_year/{id}', 'MarkController@year_selector')->name('marks.year_selector');
                    Route::post('select_year/{id}', 'MarkController@year_selected')->name('marks.year_select');
                    Route::get('show/{id}/{year}', 'MarkController@show')->name('marks.show');
                    Route::get('print/{id}/{exam_id}/{year}', 'MarkController@print_view')->name('marks.print');
                });

                /*************** Continous Assessments *****************/
                Route::group(['prefix' => 'assessments'], function () {

                    // FOR teamSA
                    Route::group(['middleware' => 'teamSA'], function () {
                        Route::get('batch/fix', 'AssessmentController@batch_fix')->name('assessments.batch_fix');
                        Route::put('batch/update', 'AssessmentController@batch_update')->name('assessments.batch_update');
                    });

                    // FOR teamSAT
                    Route::group(['middleware' => 'teamSAT'], function () {
                        Route::get('index', 'AssessmentController@index')->name('assessments.index');
                        Route::get('list', 'AssessmentController@list')->name('assessments.list');
                        Route::get('progressive/{exam?}/{class?}/{sec_id?}', 'AssessmentController@progressive')->name('assessments.progressive');
                        Route::post('progressive/select', 'AssessmentController@progressive_select')->name('assessments.progressive_select');
                        Route::get('progressive/print/{exam}/{class}/{sec_id}', 'AssessmentController@print_progressive')->name('assessments.print_progressive');
                        Route::get('manage/{exam}/{class}/{subject}/{section?}', 'AssessmentController@manage')->name('assessments.manage');
                        Route::put('update/{exam}/{class}/{subject}/{section?}', 'AssessmentController@update')->name('assessments.update');
                        Route::post('selector', 'AssessmentController@selector')->name('assessments.selector');
                        Route::get('bulk/{class?}/{section?}', 'AssessmentController@bulk')->name('assessments.bulk');
                        Route::post('bulk', 'AssessmentController@bulk_select')->name('assessments.bulk_select');
                        Route::get('print/{exam}/{class}/{subject}/{year}/{sec_id?}', 'AssessmentController@print_assessments')->name('assessments.print_assessments');
                        Route::get('print/cover/{st_id}', 'AssessmentController@print_cover')->name('assessments.print_cover');
                        Route::put('comment/update/{as_id}', 'AssessmentController@comment_update')->name('assessments.comment_update');
                        Route::put('skills/update/{skill}/{as_id}', 'AssessmentController@skills_update')->name('assessments.skills_update');
                    });

                    Route::get('show/{id}/{year}', 'AssessmentController@show')->name('assessments.show');
                    Route::get('selector/year/{id}', 'AssessmentController@year_selector')->name('assessments.year_selector');
                    Route::post('select/year/{id}', 'AssessmentController@year_selected')->name('assessments.year_select');
                    Route::get('detailed/print/{id}/{exam_id}/{year}', 'AssessmentController@print_detailed')->name('assessments.print_detailed');
                    Route::get('minimal/print/{id}/{exam_id}/{year}', 'AssessmentController@print_minimal')->name('assessments.print_minimal');
                });

                // Exam Edit State - true/false
                Route::group(['prefix' => 'exams', 'middleware' => 'teamSA'], function () {
                    Route::post('update/edit_state', 'ExamController@update_edit_state')->name('exams.update_edit_state');
                    Route::post('update/lock_state', 'ExamController@update_lock_state')->name('exams.update_lock_state');
                    Route::get('publish/{exam}', 'ExamController@publish')->name('exams.publish');
                    Route::post('announce', 'ExamController@announce')->name('exams.announce');
                });

                /*************** Messages **************/
                Route::group(['prefix' => 'messages'], function () {
                    Route::get('participant/remove/{thread_id}/{participant_id}', 'MessageController@remove_participant')->name('messages.remove_participant');
                    Route::get('participant/activate/all/thread/{thread_id}', 'MessageController@activate_all_participants')->name('thread.activate_all_participants');
                    Route::delete('delete/{msg_id}', 'MessageController@user_delete')->name('messages.user_delete');
                    Route::post('update/participant/last_read/{thread_id}', 'MessageController@update_participant_last_read')->name('messages.update_participant_last_read');
                    Route::post('previous/fetch/{thread_id}/{current_first_msg_id_in_view}', 'MessageController@fetch_previous')->name('messages.fetch_previous');
                });

                /*************** Subject Record **************/
                Route::group(['prefix' => 'subject'], function () {
                    Route::get('record/edit/{rec_id}', 'SubjectController@edit_record')->name('subjects.edit_record');
                    Route::delete('record/delete/{sub_id}/{rec_id}', 'SubjectController@delete_record')->name('subjects.delete_record');
                    Route::put('record/update/{rec_id}', 'SubjectController@update_record')->name('subjects.update_record');
                });

                /*************** Controller's resources **************/
                Route::resource('students', 'StudentRecordController');
                Route::resource('books', 'BookController');
                Route::resource('book_requests', 'BookRequestController');
                Route::resource('users', 'UserController');
                Route::resource('classes', 'MyClassController');
                Route::resource('sections', 'SectionController');
                Route::resource('subjects', 'SubjectController');
                Route::resource('grades', 'GradeController');
                Route::resource('exams', 'ExamController');
                Route::resource('dorms', 'DormController');
                Route::resource('payments', 'PaymentController');
                Route::resource('divisions', 'DivisionController');
                Route::resource('messages', 'MessageController');
            });

            /*************** Controller's resources **************/
            Route::resource('notifications', 'NotificationController');

            /************************ AJAX ****************************/
            Route::group(['prefix' => 'ajax'], function () {
                Route::get('get_state/{nal_id}', 'AjaxController@get_state')->name('get_state');
                Route::get('get_lga/{state_id}', 'AjaxController@get_lga')->name('get_lga');
                Route::get('get_class_sections/{class_id}', 'AjaxController@get_class_sections')->name('get_class_sections');
                Route::get('get_class_students/{class_id}', 'AjaxController@get_class_students')->name('get_class_students');
                Route::get('get_class_type_subjects/{class_type_id}', 'AjaxController@get_class_type_subjects')->name('get_class_type_subjects');
                Route::get('get_teacher_class_sections/{class_id}', 'AjaxController@get_teacher_class_sections')->name('get_teacher_class_sections');
                Route::get('get_subject_section_teacher/{subject_id}/{section_id}', 'AjaxController@get_subject_section_teacher')->name('get_subject_section_teacher');
                Route::get('get_class_subjects/{class_id}', 'AjaxController@get_class_subjects')->name('get_class_subjects');
                Route::get('get_table_columns/{table_name}', 'AjaxController@get_table_columns')->name('get_table_columns');
            });

            /************************ SUPER ADMIN ****************************/
            Route::group(['namespace' => 'SuperAdmin', 'middleware' => 'superAdmin', 'prefix' => 'super_admin'], function () {
                /************* Settings ************/
                Route::get('settings', 'SettingController@index')->name('settings.index')->middleware('password.confirm');
                Route::put('settings/update', 'SettingController@update')->name('settings.update');
                Route::get('settings/analytics/google/disable', 'SettingController@disable_analytics')->name('settings.disable_analytics');
                Route::get('settings/analytics/google/enable', 'SettingController@enable_analytics')->name('settings.enable_analytics');
                Route::get('settings/login_form/preview', 'SettingController@preview_login_form')->name('settings.preview_login_form');
                // Bin
                Route::delete('model/{model_name}/soft_deleted/empty', 'SoftDeleteController@empty_soft_deleted_model')->name('model.empty_soft_deleted');
                Route::get('bin', 'SoftDeleteController@bin')->name('bin')->middleware('password.confirm');
                // Payments
                Route::post('payment/restore/{pay_id}', 'SoftDeleteController@pay_restore')->name('payments.restore');
                Route::post('paymenst/force_delete/{pay_id}', 'SoftDeleteController@pay_force_delete')->name('payments.force_delete');
                // Users
                Route::post('user/restore/{user_id}', 'SoftDeleteController@user_restore')->name('user.restore');
                Route::post('user/force_delete/{user_id}', 'SoftDeleteController@user_force_delete')->name('user.force_delete');
                // My Classes
                Route::post('my_class/restore/{class_id}', 'SoftDeleteController@my_class_restore')->name('my_class.restore');
                Route::post('my_class/force_delete/{class_id}', 'SoftDeleteController@my_class_force_delete')->name('my_class.force_delete');
                // Exams
                Route::post('exam/restore/{exam_id}', 'SoftDeleteController@exam_restore')->name('exam.restore');
                Route::post('exam/force_delete/{exam_id}', 'SoftDeleteController@exam_force_delete')->name('exam.force_delete');
                // Message Thread
                Route::post('thread/restore/{exam_id}', 'SoftDeleteController@thread_restore')->name('thread.restore');
                Route::post('thread/force_delete/{exam_id}', 'SoftDeleteController@thread_force_delete')->name('thread.force_delete');

                /************* Logs ************/
                Route::group(['prefix' => 'logs'], function () {
                    Route::get('index', 'LogController@index')->name('tenancy_logs.index')->middleware('headSA');
                    Route::get('login_history/reset/{user_id}', 'LogController@reset_login_hist')->name('login_histories.reset');
                    Route::post('delete_activity/{log_id}', 'LogController@delete_activity')->name('activity_log.delete');
                    Route::post('activity_log/clean', 'LogController@activity_log_clean')->name('activity_log.clean');
                });
            });

            /************************ PARENT ****************************/
            Route::group(['namespace' => 'MyParent', 'middleware' => 'myParent',], function () {
                Route::get('my_children', 'MyController@children')->name('my_children');
            });

            /*************** Analytics **************/
            Route::group(['prefix' => 'analytics', 'middleware' => 'teamSA', 'namespace' => 'SuperAdmin'], function () {
                Route::get('index', 'AnalyticController@index')->name('analytics.index');
                Route::post('googe/setup', 'AnalyticController@google_setup')->name('analytics.google_setup');
                Route::get('data/google/fetch', 'AnalyticController@fetch_data')->name('analytics.fetch_data');
            });

            /*************** Notifications **************/
            Route::group(['prefix' => 'notifications'], function () {
                Route::get('mark/as_read/{notification_id}', 'NotificationController@mark_as_read')->name('notifications.mark_as_read');
                Route::put('mark/all_read', 'NotificationController@mark_all_read')->name('notifications.mark_all_read');
                Route::put('update/notifiable', 'NotificationController@update_is_notifiable')->name('notifications.update_is_notifiable');
                Route::match(['post', 'put', 'delete'], 'update/firebase/device_token', 'NotificationController@update_firebase_device_token')->name('notifications.firebase.update_device_token');
            });
        });
    });

    /*************** Test *****************/
    Route::get('test', 'TestController@index')->name('test');

    /*************** Others *****************/
    Route::get('privacy-policy', 'HomeController@privacy_policy')->name('privacy_policy');
    Route::get('terms-of-use', 'HomeController@terms_of_use')->name('terms_of_use');
});
