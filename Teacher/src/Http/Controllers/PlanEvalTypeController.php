<?php

namespace Digipemad\Sia\Teacher\Http\Controllers;

use Illuminate\Http\Request;
use Digipemad\Sia\Teacher\Http\Controllers\Controller;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeetEval;
use Digipemad\Sia\Academic\Models\AcademicSubjectMeet;

use Auth;
use Digipemad\Sia\Academic\Models\Student;

class PlanEvalTypeController extends Controller
{

    public function index(Request $request)	
    {
     //   $this->authorize('access', AcademicSemester::class);

        $acsem = $this->acsem;

		$user = auth()->user();
        $meet = $request->meet;
        $subjectEvalMeet = AcademicSubjectMeetEval::whereNull('deleted_at')->get();
        
        // $meetr = AcademicSubjectMeet::where('id', $request->meet)->first();
        // $sbjMeet = AcademicSubjectMeet::where('id', '!=', $request->meet)->where('subject_id', $meetr->subject_id)->get();

        $editData = '';
        if(isset($request->evaluation)){
            $editData = AcademicSubjectMeetEval::find($request->evaluation);
        }

		return view('teacher::evaluation.index', compact('meet', 'subjectEvalMeet', 'editData'));
	}

    public function store(Request $request){
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $meet = AcademicSubjectMeet::find($request->meet)->first();
        $insert = AcademicSubjectMeetEval::create([
            'name' => $request->name,
            'smt_id' => $meet->semester_id
        ]);

        if ($insert) {
            Auth::user()->log(
				' Jenis penilaian '.
                ' telah ditambahkan oleh '.$user->employee->user->name.
				' <strong>[ID: ' . $insert->id . ']</strong>',
				AcademicSubjectMeetEval::class,
				$insert->id
			);

            return redirect()->back()->with('success', 'Jenis Penilaian berhasil ditambahkan.');
        }

        return redirect()->back()->with('error', 'Jenis Penilaian gagal ditambahkan.');
    }

    public function update(AcademicSubjectMeetEval $evaluation, Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Update nama penilaian
        $updated = $evaluation->update([
            'name' => $request->name,
        ]);

        if ($updated) {
            Auth::user()->log(
                ' Jenis penilaian '.
                ' telah diperbarui oleh '.$user->employee->user->name.
                ' <strong>[ID: ' . $evaluation->id . ']</strong>',
                AcademicSubjectMeetEval::class,
                $evaluation->id
            );

            return redirect()->back()->with('success', 'Jenis Penilaian berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Jenis Penilaian gagal diperbarui.');
    }

    // public function copy(Request $request)
    // {
    //     $targetMeetId = $request->meet;

    //     if (empty($request->copy_rombel)) {
    //         return redirect()->back()->with('error', 'Pilih rombel dahulu.');
    //     }

    //     $evalData = AcademicSubjectMeetEval::where('meet_id', $request->copy_rombel)->get();

    //     if ($evalData->isEmpty()) {
    //         return redirect()->back()->with('error', 'Data yang akan dicopy tidak ditemukan.');
    //     }

    //     AcademicSubjectMeetEval::where('meet_id', $targetMeetId)->delete();

    //     foreach ($evalData as $eval) {
    //         $newEval = $eval->replicate();
    //         $newEval->created_at = now();
    //         $newEval->updated_at = now();
    //         $newEval->save();
    //     }

    //     return redirect()->back()->with('success', 'Data berhasil dicopy.');
    // }



   public function destroy(AcademicSubjectMeetEval $evaluation)
    {
        $user = auth()->user();
        $deleted = $evaluation->delete();

        if ($deleted) {
            Auth::user()->log(
				' Jenis penilaian '.
                ' telah dihapus oleh '.$user->employee->user->name.
				' <strong>[ID: ' . $evaluation->id . ']</strong>',
				AcademicSubjectMeetEval::class,
				$evaluation->id
			);

            return redirect()->back()->with('success', 'Prestasi berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Prestasi gagal dihapus.');
    }

}
