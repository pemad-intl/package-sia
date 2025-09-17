<?php

namespace Digipemad\Sia\Boarding\Http\Controllers\Facility;

use Auth;
use Illuminate\Http\Request;
use Digipemad\Sia\Boarding\Http\Controllers\Controller;
use Digipemad\Sia\Boarding\Models\BoardingStudents;
use Digipemad\Sia\Academic\Models\Student;
use Digipemad\Sia\Administration\Models\SchoolBuilding;
use Digipemad\Sia\Administration\Models\SchoolBuildingRoom;
use Modules\HRMS\Models\Employee;
use Illuminate\Support\Arr;
use Modules\Core\Enums\PositionTypeEnum;

class StudentController extends Controller
{
	public function index(Request $request)
    {
        $boardingFacilityStdn = BoardingStudents::with('student', 'ground')->whereNull('deleted_at')->paginate(10);
        $students = Student::with('user')->whereNull('deleted_at')->get();
        $buildings = SchoolBuilding::whereNull('deleted_at')->get();
        $room = SchoolBuildingRoom::whereNull('deleted_at')->get();

        $empBoarding = Employee::whereHas('contract.position', function ($query) {
            $query->where('position_id', PositionTypeEnum::PENGASUH);
        })->get();

        return view('boarding::facility.student.index', compact('boardingFacilityStdn', 'students', 'buildings', 'empBoarding'));
    }

    public function store(Request $request){
        $data = Arr::only($request->all(), ['student_id', 'building_id', 'empl_id', 'room_id']);

        $student = BoardingStudents::create($data);

        if ($student) {
            $student->load(['student.user', 'room']);

            Auth::user()->log(
                'Siswa/siswi bernama ' . ($student->student->user->name ?? '-') . ' telah didaftarkan di ruangan <strong>' . ($student->room->name ?? '-') . '</strong>' .
                ' <strong>[ID: ' . $student->id . ']</strong>',
                BoardingStudents::class,
                $student->id
            );

            return redirect($request->input('next', route('boarding::facility.student.index')))
                ->with('success', 'Data berhasil disimpan.');
        } 
        
        return redirect($request->input('next', route('boarding::facility.student.index')))
                ->with('error', 'Data gagal disimpan.');
        
    }

    public function edit(BoardingStudents $student){

        $boardingFacilityStdn = BoardingStudents::with('student', 'ground')
            ->whereNull('deleted_at')
            ->paginate(10);

        $students = Student::with('user')->whereNull('deleted_at')->get();
        $buildings = SchoolBuilding::whereNull('deleted_at')->get();
        $empBoarding = Employee::whereHas('contract.position', function ($query) {
            $query->where('position_id', PositionTypeEnum::PENGASUH);
        })->get();

        return view('boarding::facility.student.index', [
            'boardingFacilityStdn' => $boardingFacilityStdn,
            'students' => $students,
            'buildings' => $buildings,
            'editMode' => true,
            'editItem' => $student,
            'empBoarding' => $empBoarding
        ]);
    }

    public function update(BoardingStudents $student, Request $request)
    {
        $data = Arr::only($request->all(), ['student_id', 'building_id', 'empl_id', 'room_id']);

        $stdn = $student->update($data);

        if($stdn){
            $student->load(['student.user', 'room']);

            Auth::user()->log(
                ' Siswa/siswi bernama '.$student->student->user->name.' telah diperbarui di ruangan <strong> ' . ($student->room->name ?? '-') . '</strong>' .
                ' <strong>[ID: ' . $student->id . ']</strong>',
                BoardingStudents::class,
                $student->id
            );

            return redirect()->route('boarding::facility.student.index')
                ->with('success', 'Data berhasil diperbarui.');
        }


        return redirect()->route('boarding::facility.student.index')
                ->with('error', 'Data gagal diperbarui.');
        
    }

    public function destroy(BoardingStudents $student)
    {
        if($student->delete()){
            $student->load(['student.user', 'room']);

            Auth::user()->log(
                ' Siswa/siswi bernama '.$student->student->user->name.' telah dihapus dari ruangan <strong> ' . ($student->room->name ?? '-') . '</strong>' .
                ' <strong>[ID: ' . $student->id . ']</strong>',
                BoardingStudents::class,
                $student->id
            );

            return redirect()->route('boarding::facility.student.index')
                ->with('success', 'Data berhasil dihapus.');
        }
       
        return redirect()->route('boarding::facility.student.index')
            ->with('error', 'Gagal menghapus data.');
    }
}
