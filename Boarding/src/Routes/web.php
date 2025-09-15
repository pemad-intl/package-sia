<?php

use Illuminate\Support\Facades\Route;
use Digipemad\Sia\Boarding\Http\Middleware\IsBoardingMiddleware;
use Digipemad\Sia\Administration\Models\SchoolBuildingRoom;

Route::middleware(['auth', IsBoardingMiddleware::class])->group(function () {

        //    Route::redirect('/', '/dashboard')->name('index');
        // Dashboard
        Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
        Route::name('facility.')->prefix('facility')->namespace('Facility')->group(function () {
            Route::resource('student', 'StudentController');
        });

        Route::get('/rooms/{building_id}', function ($building_id) {
            $rooms = SchoolBuildingRoom::where('building_id', $building_id)
                ->whereNull('deleted_at')
                ->get(['id', 'name']);

            return response()->json($rooms);
        })->name('building-rooms');

        Route::name('event.')->prefix('event')->namespace('Event')->group(function () {
            Route::resource('event-reference', 'EventReferenceController');
            Route::resource('event-student', 'EventStudentController');
        });

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
    //     Route::middleware(OpenedAcsemsMiddleware::class)->group(function() {
    //         // Scholar
    //         Route::name('scholar.')->prefix('scholar')->namespace('Scholar')->group(function() {
    //             // Classrooms
    //             Route::put('/classrooms/{classroom}/students', 'ClassroomController@students')->name('classrooms.students');
    //             Route::put('/classrooms/{classroom}/restore', 'ClassroomController@restore')->name('classrooms.restore');
    //             Route::delete('/classrooms/{classroom}/kill', 'ClassroomController@kill')->name('classrooms.kill');
    //             Route::resource('classrooms', 'ClassroomController');
    //                 // Majors
    //                 Route::put('/majors/{major}/restore', 'MajorController@restore')->name('majors.restore');
    //                 Route::delete('/majors/{major}/kill', 'MajorController@kill')->name('majors.kill');
    //                 Route::resource('majors', 'MajorController');
    //                 // Superiors
    //                 Route::put('/superiors/{superior}/restore', 'SuperiorController@restore')->name('superiors.restore');
    //                 Route::delete('/superiors/{superior}/kill', 'SuperiorController@kill')->name('superiors.kill');
    //                 Route::resource('superiors', 'SuperiorController');
    //             // Students
    //                 // Student Achievements
    //                 Route::resource('students.achievements', 'Student\AchievementController');
    //             // Reource
    //             Route::get('/students/export', 'StudentController@export')->name('students.export');
    //             Route::post('/students/import', 'StudentController@import')->name('students.import');
    //             Route::put('/students/{student}/restore', 'StudentController@restore')->name('students.restore');
    //             Route::delete('/students/{student}/kill', 'StudentController@kill')->name('students.kill');
    //             Route::resource('students', 'StudentController');
    //             // Semesters
    //             Route::get('/semesters/export', 'SemesterController@export')->name('semesters.export');
    //             Route::post('/semesters/import', 'SemesterController@import')->name('semesters.import');
    //             Route::get('/semesters', 'SemesterController@index')->name('semesters.index');
    //             Route::get('/semesters/registrations', 'SemesterController@registrations')->name('semesters.registrations');
    //             Route::get('/semesters/promotions', 'SemesterController@promotions')->name('semesters.promotions');
    //             Route::post('/semesters/promotions', 'SemesterController@promote')->name('semesters.promote');
    //         });
    //         // Employee
    //         Route::name('employees.')->prefix('employees')->namespace('Employee')->group(function() {
    //             // Teacher
    //             Route::resource('teachers', 'TeacherController');
    //             Route::put('/teachers/{teacher}/restore', 'TeacherController@restore')->name('teachers.restore');
    //             Route::delete('/teachers/{teacher}/kill', 'TeacherController@kill')->name('teachers.kill');
    //         });
    //         // Curriculum
    //         Route::name('curriculas.')->prefix('curriculas')->namespace('Curricula')->group(function() {
    //             // Subject
    //             Route::put('/subjects/{subject}/restore', 'SubjectController@restore')->name('subjects.restore');
    //             Route::delete('/subjects/{subject}/kill', 'SubjectController@kill')->name('subjects.kill');
    //             Route::resource('subjects', 'SubjectController');
    //                 // Subject categories
    //                 Route::resource('subject-categories', 'SubjectCategoryController');
    //             // Meet
    //             Route::put('/meets/{meet}/restore', 'MeetController@restore')->name('meets.restore');
    //             Route::delete('/meets/{meet}/kill', 'MeetController@kill')->name('meets.kill');
    //             Route::resource('meets', 'MeetController');
    //         });
    //         // Facility
    //         Route::name('facility.')->prefix('facility')->namespace('Facility')->group(function() {
    //             // Buildings
    //             Route::put('/buildings/{building}/restore', 'BuildingController@restore')->name('buildings.restore');
    //             Route::delete('/buildings/{building}/kill', 'BuildingController@kill')->name('buildings.kill');
    //             Route::resource('buildings', 'BuildingController');

    //             // Building rooms
    //             Route::put('/rooms/{room}/restore', 'RoomController@restore')->name('rooms.restore');
    //             Route::delete('/rooms/{room}/kill', 'RoomController@kill')->name('rooms.kill');
    //             Route::resource('rooms', 'RoomController');

    //             // Building room assets
    //             Route::put('/rooms/{room}/restore', 'RoomController@restore')->name('rooms.restore');
    //             Route::delete('/rooms/{room}/kill', 'RoomController@kill')->name('rooms.kill');
    //             Route::resource('assets', 'AssetController');

    //             Route::resource('categories', 'AssetCategoriesController');
    //         });
    //     });
    //     // Database
    //     Route::name('database.')->prefix('database')->namespace('Database')->group(function() {
    //         // Manage
    //         Route::name('manage.')->prefix('manage')->namespace('Manage')->group(function() {
    //             // Users
    //             Route::put('/users/{user}/restore', 'UserController@restore')->name('users.restore');
    //             Route::put('/users/{user}/repass', 'UserController@repass')->name('users.repass');
    //             Route::put('/users/{user}/profile', 'User\ProfileController@update')->name('users.update.profile');
    //             Route::put('/users/{user}/email', 'User\EmailController@update')->name('users.update.email');
    //             Route::put('/users/{user}/phone', 'User\PhoneController@update')->name('users.update.phone');
    //             Route::put('/users/{user}/roles', 'User\RoleController@update')->name('users.update.roles');
    //             Route::delete('/users/{user}/kill', 'UserController@kill')->name('users.kill');
    //             Route::resource('users', 'UserController')->except(['create', 'edit']);
    //             // Roles
    //             Route::resource('roles', 'RoleController');
    //         });
    //         // Academics
    //         Route::put('/academics/{academic}/restore', 'AcademicController@restore')->name('academics.restore');
    //         Route::delete('/academics/{academic}/kill', 'AcademicController@kill')->name('academics.kill');
    //         Route::resource('academics', 'AcademicController');
    //             // Academic semesters
    //             Route::put('/academics/{academic}/semesters/{semester}/toggle', 'AcademicSemesterController@toggle')->name('academics.semesters.toggle');
    //             Route::put('/academics/{academic}/semesters/{semester}/restore', 'AcademicSemesterController@restore')->name('academics.semesters.restore');
    //             Route::delete('/academics/{academic}/semesters/{semester}/kill', 'AcademicSemesterController@kill')->name('academics.semesters.kill');
    //             Route::resource('academics.semesters', 'AcademicSemesterController');
    //         // Curriculas
    //         Route::put('/curriculas/{curricula}/restore', 'CurriculaController@restore')->name('curriculas.restore');
    //         Route::delete('/curriculas/{curricula}/kill', 'CurriculaController@kill')->name('curriculas.kill');
    //         Route::resource('curriculas', 'CurriculaController');
    //     });
    // });
    // // Empty academic semesters
    // Route::view('/empty-acsems', 'administration::empty-acsems')->name('empty-acsems');
    });
