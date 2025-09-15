<?php

namespace Digipemad\Sia\Administration;

use Digipemad\Sia\Academic\Models as ACmodel;
use Digipemad\Sia\Administration\Models as ADmodel;
use Digipemad\Sia\Administration\Policies;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Modules\Account\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        ACmodel\Academic::class => Policies\DatabaseAcademicPolicy::class,
        ADmodel\SchoolCurricula::class => Policies\DatabaseCurriculaPolicy::class,
        ADmodel\SchoolBuilding::class => Policies\FacilityBuildingPolicy::class,
        ADmodel\SchoolBuildingRoom::class => Policies\FacilityRoomPolicy::class,
        ACmodel\AcademicSubject::class => Policies\CurriculaSubjectPolicy::class,
        ACmodel\AcademicSubjectCategory::class => Policies\CurriculaSubjectCategoryPolicy::class,
        ACmodel\AcademicSemester::class => Policies\CurriculaMeetPolicy::class,
        ACmodel\AcademicClassroom::class => Policies\ScholarClassroomPolicy::class,
        ACmodel\Student::class => Policies\ScholarStudentPolicy::class,
        ACmodel\AcademicSemester::class => Policies\ScholarSemesterPolicy::class,
        ACmodel\AcademicMajor::class => Policies\ScholarMajorPolicy::class,
        ACmodel\AcademicSuperior::class => Policies\ScholarStudentPolicy::class,
        ADmodel\SchoolBillReference::class => Policies\BillReferencePolicy::class,
        ADmodel\SchoolBillStudent::class => Policies\BillStudentPolicy::class,
        ADmodel\SchoolBillCycleSemesters::class => Policies\BillBatchPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        Gate::define(
             'administration::access',
             fn(User $user) => count(array_filter(array_map(fn($policy) => (new $policy())->access($user), $this->policies)))
         );
    }
}
