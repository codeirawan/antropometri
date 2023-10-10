<?php

Auth::routes(['verify' => true, 'register' => false]);

Route::middleware(['auth', 'verified'])->group(
    function () {
        Route::redirect('/dashboard', url('/'));
        Route::redirect('/password/confirm', url('/'));
        Route::get('/', 'DashboardController@index')->name('dashboard');

        Route::namespace('Weighing')->group(function () {
            Route::middleware('permission:view-weighing')->group(function () {
                Route::resource('weighing', 'WeighingController');
                Route::post('/weighing/data', 'WeighingController@data')->name('weighing.data');

            });
        });

        Route::namespace('Report')->group(function () {
            Route::middleware('permission:view-report')->group(function () {
                Route::resource('report', 'ReportController');
                Route::post('/report/data', 'ReportController@data')->name('report.data');

            });
        });

        Route::namespace('User')->group(
            function () {
                Route::prefix('/password')->as('password.')->group(
                    function () {
                        Route::get('/edit', 'ChangePasswordController@edit')->name('edit');
                        Route::post('/', 'ChangePasswordController@update')->name('store');
                    }
                );
                Route::middleware('permission:view-role')->group(
                    function () {
                        Route::resource('role', 'RoleController')->except(['destroy']);
                        Route::post('/role/data', 'RoleController@data')->name('role.data');
                    }
                );
                Route::middleware('permission:view-user')->group(
                    function () {
                        Route::resource('user', 'UserController');
                        Route::post('/user/data', 'UserController@data')->name('user.data');
                        Route::post('/user/bulk', 'UserController@bulk')->name('user.bulk');
                    }
                );
            }
        );

        Route::prefix('/master')->as('master.')->namespace('Master')->group(
            function () {
                Route::middleware('permission:view-master')->group(
                    function () {
                        Route::resource('project', 'ProjectController')->except(['show']);
                        Route::post('/project/data', 'ProjectController@data')->name('project.data');

                        Route::resource('skill', 'SkillController')->except(['show']);
                        Route::post('/skill/data', 'SkillController@data')->name('skill.data');

                        Route::resource('leave-type', 'LeaveTypeController')->except(['show']);
                        Route::post('/leave-type/data', 'LeaveTypeController@data')->name('leave-type.data');

                        Route::resource('shift', 'ShiftController')->except(['show']);
                        Route::post('/shift/data', 'ShiftController@data')->name('shift.data');

                        Route::resource('national-holiday', 'NationalHolidayController')->except(['show']);
                        Route::post('/national-holiday/data', 'NationalHolidayController@data')->name('national-holiday.data');

                        Route::resource('activity', 'ActivityController')->except(['show']);
                        Route::post('/activity/data', 'ActivityController@data')->name('activity.data');
                    }
                );

            }
        );
    }
);