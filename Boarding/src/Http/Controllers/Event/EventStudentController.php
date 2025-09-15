<?php

namespace Digipemad\Sia\Boarding\Http\Controllers\Event;

use Auth;
use Digipemad\Sia\Academic\Models\AcademicClassroom;
use Illuminate\Http\Request;
use Digipemad\Sia\Boarding\Http\Controllers\Controller;
use Digipemad\Sia\Boarding\Models\BoardingStudentsEvent;
use Digipemad\Sia\Academic\Models\Student;
use Modules\HRMS\Models\Employee;
use Modules\Core\Enums\PositionTypeEnum;
use Digipemad\Sia\Administration\Models\SchoolBuilding;
use Digipemad\Sia\Boarding\Models\BoardingReferenceEvent;
use Illuminate\Support\Arr;

class EventStudentController extends Controller
{
    public function index(Request $request)
    {
        $boardingEventStdn = BoardingStudentsEvent::with(['event', 'teacher', 'supervisor'])
        ->whereHas('teacher', function ($query) {
            $query->where('grade_id', userGrades()); 
        })
        ->whereNull('deleted_at')
        ->paginate(10);
        
        $students = Student::with('user')
        ->where('grade_id', userGrades())
        ->whereNull('deleted_at')->get();

        $events = BoardingReferenceEvent::where('grade_id', userGrades())->whereNull('deleted_at')->get();
        $acdmcClass = AcademicClassroom::whereNull('deleted_at')->get();
        
        $employeeTeacher = Employee::with('user')
        ->where('grade_id', userGrades())
        ->whereHas('contract.position.position', function ($q) {
            $q->where('type', PositionTypeEnum::GURU->value);
        })->get();

        $employeeSupervisor = Employee::with('user')
        ->where('grade_id', userGrades())
        ->whereHas('contract.position.position', function ($q) {
            $q->where('type', PositionTypeEnum::PENGURUS->value);
        })->get();


        return view('boarding::event.student.index', compact('boardingEventStdn', 'students', 'events', 'employeeTeacher', 'employeeSupervisor', 'acdmcClass'));
    }

    public function store(Request $request)
    {
        $data = Arr::only($request->all(), ['event_id', 'modelable_type', 'modelable_id', 'teacher_id', 'supervisor_id']);

        $typeEvent = $request->input('participant_type');

        if ($typeEvent == 1) {
            $studentableType = \Modules\Academic\Models\Student::class;
            $studentableId = $request->input('student_id');
        } elseif ($typeEvent == 2) {
            $studentableType = \Modules\Academic\Models\AcademicClassroom::class; 
            $studentableId = $request->input('academic_id'); 
        } else {
            return redirect()->back()->with('error', 'Type event tidak valid.');
        }

        $data['modelable_type'] = $studentableType;
        $data['modelable_id'] = $studentableId;

        $boardStudent = BoardingStudentsEvent::create($data);

        if ($boardStudent) {
            // $boardStudent->load('studentable', 'event');

            Auth::user()->log(
                ' Kegiatan bernama ' . $boardStudent->event->name . ' telah ditambahkan pada ' . 
                ($typeEvent == 1 
                    ? 'siswa/siswi bernama ' . $boardStudent->modelable->user->name
                    : 'rombel bernama ' . $boardStudent->modelable->name) .
                ' <strong>[ID: ' . $boardStudent->id . ']</strong>',
                BoardingStudentsEvent::class,
                $boardStudent->id
            );

            return redirect($request->input('next', route('boarding::facility.student.index')))
                ->with('success', 'Data berhasil disimpan.');
        }

        return redirect($request->input('next', route('boarding::facility.student.index')))
            ->with('error', 'Data gagal disimpan.');
    }


    public function edit(BoardingStudentsEvent $event_student)
    {

        $boardingEventStdn = BoardingStudentsEvent::with('student', 'event', 'teacher', 'supervisor')
            ->whereNull('deleted_at')
            ->paginate(10);

        $acdmcClass = AcademicClassroom::whereNull('deleted_at')->get();


        $employeeTeacher = Employee::with('user')->whereHas('contract.position.position', function ($q) {
            $q->where('type', PositionTypeEnum::GURU->value);
        })->get();

        $employeeSupervisor = Employee::with('user')->whereHas('contract.position.position', function ($q) {
            $q->where('type', PositionTypeEnum::PENGURUS->value);
        })->get();

        $students = Student::with('user')->whereNull('deleted_at')->get();
        $events = BoardingReferenceEvent::whereNull('deleted_at')->get();

        return view('boarding::event.student.index', [
            'boardingEventStdn' => $boardingEventStdn,
            'students' => $students,
            'events' => $events,
            'editMode' => true,
            'editItem' => $event_student,
            'employeeTeacher' => $employeeTeacher,
            'employeeSupervisor' => $employeeSupervisor,
            'acdmcClass' => $acdmcClass
        ]);
    }

    public function update(BoardingStudentsEvent $event_student, Request $request)
    {
        $data = Arr::only($request->all(), ['event_id', 'modelable_type', 'modelable_id', 'teacher_id', 'supervisor_id']);

        $typeEvent = $request->input('participant_type');

        if ($typeEvent == 1) {
            $studentableType = \Modules\Academic\Models\Student::class;
            $studentableId = $request->input('student_id');
        } elseif ($typeEvent == 2) {
            $studentableType = \Modules\Academic\Models\AcademicClassroom::class; 
            $studentableId = $request->input('academic_id'); 
        } else {
            return redirect()->back()->with('error', 'Type event tidak valid.');
        }

        $data['modelable_type'] = $studentableType;
        $data['modelable_id'] = $studentableId;

        $boardStudent = $event_student->update($data);
        if($boardStudent){
         //   $event_student->load('studentable.user', 'event');

            Auth::user()->log(
                ' Kegiatan bernama ' . $event_student->event->name . ' telah diperbarui pada ' . 
                ($typeEvent == 1 
                    ? 'siswa/siswi bernama ' . $event_student->modelable->user->name
                    : 'rombel bernama ' . $event_student->modelable->name) .
                ' <strong>[ID: ' . $event_student->id . ']</strong>',
                BoardingStudentsEvent::class,
                $event_student->id
            );

            return redirect()->route('boarding::event.event-student.index')
                ->with('success', 'Data berhasil diperbarui.');
        } 
        
        
        return redirect()->route('boarding::event.event-student.index')
            ->with('error', 'Data gagal diperbarui.');
        
    }

    public function destroy(BoardingStudentsEvent $event_student)
    {
        $studentEv = $event_student->delete();

        if($studentEv){
            $event_student->load('student.user','event');   

            Auth::user()->log(
                ' Kegiatan bernama ' . $event_student->event->name . ' telah diperbarui pada ' . 
                ($event_student->event_id == 1 
                    ? 'siswa/siswi bernama ' . $event_student->modelable->user->name
                    : 'rombel bernama ' . $event_student->modelable->name) .
                ' <strong>[ID: ' . $event_student->id . ']</strong>',
                BoardingStudentsEvent::class,
                $event_student->id
            );

            return redirect()->route('boarding::event.event-student.index')
                ->with('success', 'Data berhasil dihapus.');
        } 
        
        return redirect()->route('boarding::event.event-student.index')
                ->with('error', 'Gagal menghapus data.');
        
    }
}
