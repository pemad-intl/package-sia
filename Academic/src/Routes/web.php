<?php

use \Digipemad\Sia\Academic\Http\Middleware\IsStudentMiddleware;

// Route::domain(config('domain.academic'))->name('academic::')->group(function() {
	Route::redirect('/', '/home')->name('index');
	Route::middleware('auth')->group(function () {
		Route::middleware(IsStudentMiddleware::class)->group(function () {
	    	// Home
	    	Route::get('/home', 'HomeController@index')->name('home');
	    	// Report
	    	Route::get('/report', 'ReportController@index')->name('report');
	    	Route::get('/report/print', 'ReportController@print')->name('report.print');


			Route::name('leave.')->prefix('leave')->namespace('Leave')->group(function () {
				Route::get('/', 'SubmissionController@index')->name('submission.index');
				Route::get('/submission', 'SubmissionController@create')->name('submission.create');
				Route::post('/submission', 'SubmissionController@store')->name('submission.store');
				Route::get('/submission/{leave}', 'SubmissionController@show')->name('submission.show');
				Route::delete('/submission/{leave}', 'SubmissionController@destroy')->name('submission.destroy');

				Route::get('/manage', 'ManageController@index')->name('manage.index');
				Route::get('/manage/{leave}', 'ManageController@show')->name('manage.show');
				Route::put('/manage/{approvable}', 'ManageController@update')->name('manage.update');

				Route::get('/print/{leave}', 'PrintController@index')->name('print');
        	});

			//Route::prefix('counseling')->name('counseling.')->group(function () {
        		// Categories
                Route::resource('counselings', 'CounselingController');
				Route::resource('classroom', 'ClassRoomController')->only('index');
				Route::resource('boarding', 'BoardRoomController')->only('index');
				Route::resource('activity', 'ActivityHistoryController')->only('index');

            //});

			Route::view('/notifications', 'academic::notifications')->name('notifications');
			Route::get('/notifications/read-all', 'NotificationController@readAll')->name('notifications.read-all');
			Route::get('/notifications/{id}', 'NotificationController@read')->name('notifications.read');
		});
	});
//});
