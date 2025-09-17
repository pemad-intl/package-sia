<?php

namespace Digipemad\Sia\Counseling\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Counseling\Http\Controllers\Controller;

use App\Models\References\GradeLevel;
use Digipemad\Sia\Academic\Models\AcademicClassroom;
use Digipemad\Sia\Academic\Models\AcademicClassroomPresence;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeetPlan;
use Digipemad\Sia\Academic\Models\StudentSemesterAssessment;
use Digipemad\Sia\Counseling\Http\Requests\Presences\PresenceRequest;

class PresenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->authorize('access', AcademicClassroomPresence::class);

        $acsem = $this->acsem->load('classrooms');

        $user = auth()->user();

        $presenceList = AcademicSubjectMeetPlan::$presenceList;

        $presences = AcademicClassroomPresence::where('classroom_id', $request->get('classroom'))
        ->get();

        $currentClassroom = AcademicClassroom::with('stsems.student')->find($request->get('classroom'));

        return view('counseling::presences.index', compact('acsem', 'user', 'presenceList', 'presences', 'currentClassroom'));
    }

    /**
     * Show create resource.
     */
    public function create(Request $request)
    {
        $this->authorize('store', AcademicClassroomPresence::class);

        $acsem = $this->acsem->load('classrooms');
        $user = auth()->user();

        $presenceList = AcademicSubjectMeetPlan::$presenceList;
        $types = StudentSemesterAssessment::$type;

        $currentClassroom = AcademicClassroom::with('stsems')->find($request->get('classroom'));

        return view('counseling::presences.create', compact('acsem', 'user', 'presenceList', 'types', 'currentClassroom'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PresenceRequest $request)
    {
        $this->authorize('store', AcademicClassroomPresence::class);

        $data = $request->validated();

        $user = auth()->user();

        $presence = new AcademicClassroomPresence([
            'classroom_id' => $data['classroom_id'],
            'presenced_at' => date('Y-m-d H:i:s', strtotime($data['presenced_at'])),
            'presence' => AcademicSubjectMeetPlan::transformPresenceFormat($request->input('presence')),
            'presenced_by' => $user->employee->id
        ]);

        $presence->save();

        return redirect($request->get('next', url()->previous()))->with('success', 'Sukses, presensi '.$presence->classroom->full_name.' berhasil dibuat');
    }

    /**
     * Edit the specified resource.
     */
    public function edit(AcademicClassroomPresence $presence, Request $request)
    {
        $this->authorize('update', AcademicClassroomPresence::class);
        return abort(404);
    }

    /**
     * Update the specified resource.
     */
    public function update(AcademicClassroomPresence $presence, Request $request)
    {
        $this->authorize('store', AcademicClassroomPresence::class);
        return abort(404);
    }

    /**
     * Show the specified resource.
     */
    public function show(AcademicClassroomPresence $presence)
    {
        $this->authorize('show', AcademicClassroomPresence::class);
        return abort(404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AcademicClassroomPresence $presence)
    {
        $this->authorize('destroy', AcademicClassroomPresence::class);
        // $this->authorize('remove', $case);

        $tmp = $presence;
        $presence->delete();

        return redirect()->back()->with('success', 'Presensi rombel <strong>'.$tmp->classroom->full_name.'</strong> berhasil dihapus');
    }
}
