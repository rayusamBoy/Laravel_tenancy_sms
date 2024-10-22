<?php

use App\Http\Requests\CustomEmailVerificationRequest;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Central Routes
|--------------------------------------------------------------------------
|
| Here you can register the central routes for your application.
|
| Feel free to customize them however you want. Good luck!
|
*/

Auth::routes();

Route::group(['middleware' => ['auth', 'checkForPassUpdate', 'itGuy']], function () {
    /*************** Two factor authentication *****************/
    Route::group(['prefix' => 'auth/2fa'], function () {
        Route::get('account/security', 'Auth\AccountSecurityController@index')->name('account_security.index')->middleware('2fa');
        Route::match(['POST', 'GET'], 'account/recovery', 'Auth\AccountSecurityController@account_recovery')->name('account_security.account_recovery');
        Route::get('show', 'Auth\AccountSecurityController@showGoogle2FAVerification')->name('2fa.show');
        // The middleware in this route will capture the value from the form (the one time password that need to be verified).
        // The middleware will attempt to verify the captured value and return to the form with error message if there was any error.
        // Otherwise; if the value is verified, the 'authenticate' method will be called. See the medhod for more info. about.
        Route::post('authenticate', 'Auth\AccountSecurityController@authenticate')->name('2fa.authenticate')->middleware(['2fa', 'throttle:5,1']);
        Route::post('update/code', 'Auth\AccountSecurityController@update_secret_codes')->name('2fa.update_secret_codes')->middleware('2fa');
        Route::patch('code/null/{user_id?}', 'Auth\AccountSecurityController@null_secret_code')->name('2fa.null_secret_code')->middleware('2fa');
    });

    Route::group(['middleware' => '2fa',], function () {
        Route::group(['namespace' => 'ItGuy'], function () {

            // Probably shared hosting; no shell access, access artisan commands from a browser.
            Route::group(['prefix' => 'artisan_commands'], function () {
                /************* Artisn Commands ************/
                Route::get('index', 'ArtisanCommandsController@index')->name('artisan_commands.index');
                // Cache and Clear artisan commands
                Route::post('optimize', 'ArtisanCommandsController@optimize')->name('artisan_command.optimize');
                Route::post('optimize/clear', 'ArtisanCommandsController@optimize_clear')->name('artisan_command.optimize_clear');
                Route::post('route/cache', 'ArtisanCommandsController@route_cache')->name('artisan_command.route_cache');
                Route::post('route/clear', 'ArtisanCommandsController@route_clear')->name('artisan_command.route_clear');
                Route::post('config/cache', 'ArtisanCommandsController@config_cache')->name('artisan_command.config_cache');
                Route::post('config/clear', 'ArtisanCommandsController@config_clear')->name('artisan_command.config_clear');
                Route::post('event/cache', 'ArtisanCommandsController@event_cache')->name('artisan_command.event_cache');
                Route::post('event/clear', 'ArtisanCommandsController@event_clear')->name('artisan_command.event_clear');
                Route::post('view/cache', 'ArtisanCommandsController@view_cache')->name('artisan_command.view_cache');
                Route::post('view/clear', 'ArtisanCommandsController@view_clear')->name('artisan_command.view_clear');
                Route::post('log_viewer/publish', 'ArtisanCommandsController@log_viewer_publish')->name('artisan_command.log_viewer_publish');
                Route::post('activity_log/clean', 'ArtisanCommandsController@activity_log_clean')->name('logs.activity_log_clean');
                Route::post('storage/link', 'ArtisanCommandsController@storage_link')->name('logs.storage_link');
                Route::post('storage/unlink', 'ArtisanCommandsController@storage_unlink')->name('logs.storage_unlink');
            });

            /*************** Dashboard **************/
            Route::get('/', 'HomeController@dashboard')->name('index');
            Route::get('home', 'HomeController@dashboard')->name('home');
            Route::get('dashboard', 'HomeController@dashboard')->name('dashboard');

            /************* Logs ************/
            Route::group(['prefix' => 'logs'], function () {
                Route::get('index', 'LogController@index')->name('logs.index')->middleware('headSA');
                Route::get('login_history/reset/{user_id}', 'LogController@reset_login_hist')->name('logs.login_history_reset');
                Route::post('delete_activity/{log_id}', 'LogController@delete_activity')->name('logs.activity_log_delete');
            });

            /************* Settings ************/
            Route::get('settings/index', 'SettingController@index')->name('settings_non_tenancy.index')->middleware('password.confirm');
            Route::put('settings/update', 'SettingController@update')->name('settings_non_tenancy.update');
            Route::get('settings/analytics/google/disable', 'SettingController@disable_analytics')->name('settings_non_tenancy.disable_analytics');
            Route::get('settings/analytics/google/enable', 'SettingController@enable_analytics')->name('settings_non_tenancy.enable_analytics');
            Route::get('settings/login_form/preview', 'SettingController@preview_login_form')->name('settings_non_tenancy.preview_login_form');
            Route::get('settings/login_form/preview', 'SettingController@preview_login_form')->name('settings_non_tenancy.preview_login_form');
        });

        /****************** My Account ***************/
        Route::group(['prefix' => 'my_account'], function () {
            Route::get('profile/edit', 'MyAccountController@edit_profile')->name('my_account');
            Route::put('profile/update', 'MyAccountController@update_profile')->name('my_account.update');
            Route::put('password/change', 'MyAccountController@change_pass')->name('my_account.change_pass');
            Route::put('other', 'MyAccountController@other')->name('my_account.other');
        });

        /************************ AJAX ****************************/
        Route::group(['prefix' => 'ajax'], function () {
            Route::get('get_state/{nal_id}', 'AjaxController@get_state')->name('get_state');
            Route::get('get_lga/{state_id}', 'AjaxController@get_lga')->name('get_lga');
        });

        /****************** Email Verification ***************/
        Route::get('/email/verify/{id}/{hash}', function (CustomEmailVerificationRequest $request) {
            $request->fulfill();
            return redirect('/home')->with('pop_success', __('msg.email_verified'));
        })->middleware(['signed'])->name('verification.verify');

        Route::get('email/verification_notification', function (Request $request) {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('flash_success', __('msg.verification_sent'));
        })->middleware(['throttle:6,1'])->name('verification.send');

        Route::group(['namespace' => 'ItGuy'], function () {
            /*************** Users *****************/
            Route::group(['prefix' => 'users'], function () {
                Route::get('password/reset/{id}', 'UserController@reset_pass')->name('users.reset_pass');
                Route::post('user/update/staff/data/edit_state', 'UserController@update_staff_data_edit_state')->name('users.update_staff_data_edit_state');
                Route::post('user/update/user/blocked_state', 'UserController@update_user_blocked_state')->name('users.update_user_blocked_state');
            });

            /*************** Controller's resources **************/
            Route::resource('users', 'UserController');
            Route::resource('tenants', 'TenantController');
        });

        /*************** Notifications **************/
        Route::group(['prefix' => 'notifications'], function () {
            Route::get('mark/as_read/{notification_id}', 'NotificationController@mark_as_read')->name('notifications.mark_as_read');
            Route::put('mark/all_read', 'NotificationController@mark_all_read')->name('notifications.mark_all_read');
            Route::put('update/notifiable', 'NotificationController@update_is_notifiable')->name('notifications.update_is_notifiable');
            Route::match(['post', 'put', 'delete'], 'update/firebase/device_token', 'NotificationController@update_firebase_device_token')->name('notifications.firebase.update_device_token');
        });

        /*************** Controller's resources **************/
        Route::resource('notifications', 'NotificationController');
    });
});

/*************** Test *****************/
Route::get('test', 'TestController@index')->name('test');

/*************** Others *****************/
Route::get('privacy-policy', 'HomeController@privacy_policy')->name('privacy_policy');
Route::get('terms-of-use', 'HomeController@terms_of_use')->name('terms_of_use');
