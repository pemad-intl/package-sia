<?php

use \Digipemad\Sia\Counseling\Http\Middleware\IsCounselorMiddleware;

// Route::domain(config('domain.counseling'))->name('counseling::')->middleware('auth')->group(function() {
	Route::middleware(IsCounselorMiddleware::class)->group(function () {
        // Index
    	Route::redirect('/', '/home')->name('index');
    	Route::get('/home', 'HomeController@index')->name('home');
        // Presences
        Route::resource('presences', 'PresenceController');
        // Cases
        Route::resource('cases', 'CaseController');
        // Counseling
        Route::resource('counselings', 'CounselingController');
    	// Manage

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
          
    	Route::namespace('Manage')->prefix('manage')->name('manage.')->group(function () {
            // Cases
            Route::prefix('cases')->name('cases.')->group(function () {
        		// Categories
                Route::put('/categories/{category}/restore', 'CaseCategoryController@restore')->name('categories.restore');
                Route::delete('/categories/{category}/kill', 'CaseCategoryController@kill')->name('categories.kill');
        		Route::resource('categories', 'CaseCategoryController');
        		// Description
        		Route::resource('descriptions', 'CaseDescriptionController');
            });
            // Counseling
            Route::prefix('counseling')->name('counseling.')->group(function () {
        		// Categories
                Route::put('/categories/{category}/restore', 'CounselingCategoryController@restore')->name('categories.restore');
                Route::delete('/categories/{category}/kill', 'CounselingCategoryController@kill')->name('categories.kill');
        		Route::resource('categories', 'CounselingCategoryController');
            });
    	});
    });
// });
