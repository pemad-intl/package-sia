<?php

use \Digipemad\Sia\Teacher\Http\Middleware\IsTeacherMiddleware;

// Route::domain(config('domain.teacher'))->name('teacher::')->middleware('auth')->group(function() {
	Route::middleware(IsTeacherMiddleware::class)->group(function () {
         // Plans
        Route::get('/plans/{plan}', 'PlanController@show')->name('plan');
        Route::put('/plans/{plan}', 'PlanController@update')->name('plan');
        Route::put('/plans/{plan}/presence', 'PlanController@presence')->name('plan.presence');
        Route::put('/plans/{plan}/assessment', 'PlanController@assessment')->name('plan.assessment');
        
    	Route::redirect('/', '/home')->name('index');
    	// Home
    	Route::get('/home', 'HomeController@index')->name('home');
    	// Meet
    	Route::get('/meets/{meet}', 'MeetController@show')->name('meet');
        Route::post('/meets/{meet}', 'MeetController@store')->name('meet');
        Route::post('/meets/{meet}/copy', 'MeetController@copy')->name('meet.copy');
        Route::get('/meets/{meet}/plans', 'MeetController@manage')->name('meet.plans');
        Route::put('/meets/{meet}/plans', 'MeetController@update')->name('meet.plans');
        // Meet
        Route::get('/reports/{meet}', 'ReportController@show')->name('report');
        Route::put('/reports/{meet}', 'ReportController@update')->name('report');

        Route::get('/supervisor/{classroom}', 'SupervisorController@show')->name('supervisor');
        Route::put('/supervisor/{classroom}', 'SupervisorController@update')->name('supervisor');

        Route::get('/recommended/{classroom}', 'ReccomendationController@show')->name('recommended');
        Route::put('/recommended/{classroom}', 'ReccomendationController@update')->name('recommended');

        Route::get('/achievement/{classroom}', 'AchievementController@show')->name('achievement');
        Route::get('/extras/{classroom}', 'ExtrasController@show')->name('extras');

        
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
            // Route::get('/print/{leave}', 'PrintController@index')->name('print');
        });

        Route::resource('evaluation', 'PlanEvalTypeController');
        Route::post('evaluation_copy', 'PlanEvalTypeController@copy')->name('copy_evaluation');

        Route::prefix('achievement/{classroom}/{student}')->group(function () {
            Route::get('/', 'AchievementStudentController@index')->name('achievement.index');
            Route::get('/create', 'AchievementStudentController@create')->name('achievement.create');
            Route::post('/', 'AchievementStudentController@store')->name('achievement.store');
            Route::get('/{achievement}', 'AchievementStudentController@show')->name('achievement.show');
        });

        Route::prefix('extras/{classroom}/{student}')->group(function () {
            Route::get('/', 'StudentExtrasController@index')->name('extras.index');
            Route::get('/create', 'StudentExtrasController@create')->name('extras.create');
            Route::post('/', 'StudentExtrasController@store')->name('extras.store');
            Route::get('/{achievement}', 'StudentExtrasController@show')->name('extras.show');
        });

        // Route::get('/{achievement}/edit', 'AchievementStudentController@edit')->name('achievement.edit');
        Route::put('/{achievement}', 'AchievementStudentController@update')->name('achievement.update');
        Route::delete('/{achievement}', 'AchievementStudentController@destroy')->name('achievement.destroy');

        Route::put('extra/{extra}', 'StudentExtrasController@update')->name('extras.update');
        Route::delete('extra/{extra}', 'StudentExtrasController@destroy')->name('extras.destroy');

       
        // Cases
        Route::get('/cases', 'CaseController@create')->name('case');
        Route::post('/cases', 'CaseController@store')->name('case');
    	// Subject
		Route::prefix('subjects/{subject}')->name('subjects.')->namespace('Subject')->group(function() {
    		// Manage competences
    		Route::resource('competences', 'CompetenceController')->except(['show', 'create', 'edit']);
		});
	});
// });
