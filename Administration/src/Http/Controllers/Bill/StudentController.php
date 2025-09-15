<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Bill;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;
use Digipemad\Sia\Administration\Models\SchoolBillStudent;
use Digipemad\Sia\Administration\Models\SchoolBillReference;
use Digipemad\Sia\Academic\Models\Student;
use Digipemad\Sia\Academic\Models\StudentSemester;
use Digipemad\Sia\Academic\Models\AcademicClassroom;
use Digipemad\Sia\Administration\Http\Requests\Bill\Student\StoreRequest;
use Auth;
use App\Models\References\GradeLevel;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * index.
     */
    public function index(Request $request)
    {
        $this->authorize('access', SchoolBillStudent::class);

        $acsem = $this->acsem;

        $user = auth()->user();

    	$trashed = $request->get('trash', 0);

    	$students = SchoolBillStudent::when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->paginate($request->get('limit', 10));

        $studentCount = SchoolBillStudent::whereNull('deleted_at')->count();

        $grades = [];

        if(!empty($request->education)){
            $grades = GradeLevel::where('grade_id', $request->education)->with('grade')->get()->pluck('id');
        } 

        $semesterStudent = StudentSemester::with('classroom')->whereHas('classroom', function($q) use ($grades) {
            $q->whereIn('level_id', $grades);
        })->get();

        return view('administration::bill.student.index', compact('user','students','studentCount', 'grades', 'semesterStudent'));
    }

    public function create(Request $request){
        $this->authorize('access', SchoolBillStudent::class);
        $acsem = $this->acsem;

        $referenceComponent = SchoolBillReference::whereNull('deleted_at')->get();

        $students = Student::whereHas('semesters', function ($semester) {
            return $semester->where(['semester_id' => $this->acsem->id]);
        })->get();

        $classRoomStudent = AcademicClassroom::whereNull('deleted_at')->get();

        return view('administration::bill.student.create', compact('referenceComponent', 'students', 'classRoomStudent'));
    }

    public function store(StoreRequest $request)
    {
        $this->authorize('store', SchoolBillStudent::class);

        try {
            DB::beginTransaction();
            
            foreach ($request->transform()['billings'] as $cmpStudentBill) {
                    $studentsPayComponent = SchoolBillStudent::updateOrCreate(
                    [
                        'smt_id'   => $cmpStudentBill['smt_id'], 
                        'batch_id' => $request->transform()['batch_id']
                    ],
                    [
                        'meta'     => $cmpStudentBill['meta']
                    ]
                );

                $student = StudentSemester::with('student')->find($cmpStudentBill['smt_id'])->student;
                if (! $studentsPayComponent->save()) {
                    throw new \Exception('Gagal menyimpan komponen tagihan siswa '.$student->user->name);
                }

                Auth::user()->log(
                    'Komponen tagihan siswa bernama ' . $student->user->name . ' telah disimpan ' .
                    '<strong>[ID: ' . $studentsPayComponent->id . ']</strong>',
                    SchoolBillStudent::class,
                    $studentsPayComponent->id
                );
            }

            DB::commit();

            return redirect()->back()->with('success', 'Semua komponen tagihan siswa berhasil disimpan');
        } catch (\Throwable $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->with('danger', 'Terjadi kesalahan saat menyimpan data tagihan siswa');
        }
    }

    public function edit(SchoolBillStudent $student){
        $this->authorize('update', SchoolBillStudent::class);
        $acsem = $this->acsem;
        $references = $student->references();
      
        return view('administration::bill.student.edit', compact('student', 'references'));
    }

    public function update(SchoolBuildingRoom $room, Request $request){
        $this->authorize('update', SchoolBuildingRoom::class);

        if ($room->trashed()) abort(404);

        if($room->update([
            'kd' => $request->input('kd'),
            'name' => $request->input('name'),
            'capacity' => $request->input('capacity')
        ])){
            Auth::user()->log(
                ' Ruangan bernama '.$room->name.' telah diperbarui '.
                ' <strong>[ID: ' . $room->id . ']</strong>',
                SchoolBuildingRoom::class,
                $room->id
            );

            return redirect()
                ->route('administration::facility.rooms.index')
                ->with('success', 'Ruangan <strong>' . $room->name . '</strong> berhasil diperbarui');
        }

        return redirect()
                ->route('administration::facility.rooms.index')
                ->with('danger', 'Ruangan <strong>' . $room->name . '</strong> gagal diperbarui');
    }

    public function show(SchoolBuildingRoom $room)
    {
        $this->authorize('show', SchoolBuildingRoom::class);

        if($room->trashed()) abort(404);


        return view('administration::facility.rooms.show', compact('room'));
    }

    public function destroy(SchoolBuildingRoom $room)
    {
        $this->authorize('destroy', SchoolBuildingRoom::class);
        if($room->delete()){
            Auth::user()->log(
                ' Ruangan bernama '.$room->name.' telah dihapus '.
                ' <strong>[ID: ' . $room->id . ']</strong>',
                SchoolBuildingRoom::class,
                $room->id
            );

            return redirect()->back()->with('success', 'Ruang <strong>'.$room->name.'</strong> berhasil dihapus');
        }

        return redirect()->back()->with('danger', 'Ruang <strong>'.$room->name.'</strong> gagal dihapus');
    }

    public function restore(SchoolBuildingRoom $room)
    {
        $this->authorize('restore', SchoolBuildingRoom::class);

        $room->restore();

        return redirect()->back()->with('success', 'Ruang <strong>'.$room->name.'</strong> berhasil dipulihkan');
    }

    public function kill(SchoolBuildingRoom $room)
    {
        $this->authorize('kill', SchoolBuildingRoom::class);

        $tmp = $room;
        $room->forceDelete();

        return redirect()->back()->with('success', 'Ruang <strong>'.$tmp->name.'</strong> berhasil dihapus permanen dari sistem');
    }

}
