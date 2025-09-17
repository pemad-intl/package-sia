<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Bill;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;
use Digipemad\Sia\Administration\Models\SchoolBillCycleSemesters;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Auth;

class BatchController extends Controller
{
    /**
     * index.
     */
    public function index(Request $request)
    {
        $this->authorize('access', SchoolBillCycleSemesters::class);

        $user = auth()->user();

    	$trashed = $request->get('trash', 0);

    	$billsBatch = SchoolBillCycleSemesters::where('name', 'like', '%'.$request->get('search').'%')->when($trashed, function($query, $trashed) {
            return $query->onlyTrashed();
        })->paginate($request->get('limit', 10));

        $billBatchCount = SchoolBillCycleSemesters::whereNull('deleted_at')->count();

        $editBillBatch = null;
        if ($request->filled('edit')) {
            $editBillBatch = SchoolBillCycleSemesters::findOrFail($request->get('edit'));
        }

        $academicSemester = AcademicSemester::with('academic')->whereNull('deleted_at')->get();

        return view('administration::bill.batch.index', compact('user', 'billsBatch','billBatchCount', 'editBillBatch', 'academicSemester'));
    }

    public function store(Request $request)
    {
        $this->authorize('store', SchoolBillCycleSemesters::class);

        $batchs = new SchoolBillCycleSemesters([
            'name' => $request->input('name'),
            'semester_id' => $request->input('semester_id')
        ]);

        if($batchs->save()){
            Auth::user()->log(
                ' Gelombang pembayaran '.$batchs->name.' telah ditambahkan '.
                ' pada semester '.$batchs->semesters->name.
                ' <strong>[ID: ' . $batchs->id . ']</strong>',
                SchoolBillCycleSemesters::class,
                $batchs->id
            );

            return redirect()->route('administration::bill.batchs.index')
            ->with('success', 'Gelombang Pembayaran <strong>'.$batchs->name.'</strong> pada semster '.$batchs->semesters->name.' berhasil disimpan</strong>');
        } 

        return redirect()->back()->with('danger', 'Gelombang Pembayaran <strong>'.$batchs->name.'</strong> semster '.$batchs->semesters->name.' gagal disimpan</strong>');
    }

    public function update(SchoolBillCycleSemesters $batch, Request $request){
        
        $this->authorize('update', SchoolBillCycleSemesters::class);

        if ($batch->trashed()) abort(404);

        if($batch->update([
            'name' => $request->input('name'),
            'semester_id' => $request->input('semester_id')
        ])){
            Auth::user()->log(
                ' Gelombang pembayaran '.$batch->name.' telah diperbarui '.
                ' pada semester '.$batch->semesters->name.
                ' <strong>[ID: ' . $batch->id . ']</strong>',
                SchoolBillCycleSemesters::class,
                $batch->id
            );

            return redirect()
                ->route('administration::bill.batchs.index')
                ->with('success', 'Referensi Pembayaran <strong>'.$batch->name.'</strong> pada semster '.$batch->semesters->name.' berhasil diperbarui');
        }

        return redirect()
                ->route('administration::bill.batchs.index')
                ->with('danger', 'Referensi Pembayaran <strong>'.$batch->name.'</strong> pada semster '.$batch->semesters->name.' gagal diperbarui');
    }

    public function show(SchoolBillReference $room)
    {
        // $this->authorize('show', SchoolBuildingRoom::class);

        // if($room->trashed()) abort(404);


        // return view('administration::facility.rooms.show', compact('room'));
    }

    public function destroy(SchoolBillCycleSemesters $batch)
    {
        $this->authorize('destroy', SchoolBillCycleSemesters::class);
        if($batch->delete()){
            Auth::user()->log(
                ' Gelombang pembayaran '.$batch->name.' telah dihapus '.
                ' pada semester '.$batch->semesters->name.
                ' <strong>[ID: ' . $batch->id . ']</strong>',
                SchoolBillCycleSemesters::class,
                $batch->id
            );

            return redirect()->route('administration::bill.batchs.index')
            ->with('success', 'Gelombang Pembayaran <strong>'.$batch->name.'</strong> pada semster '.$batch->semesters->name.' berhasil dihapus');
        }

        return redirect()->back()->with('danger', 'Gelombang Pembayaran <strong>'.$batch->name.'</strong> pada semster '.$batch->semesters->name.' gagal dihapus');
    }

    public function restore(SchoolBillCycleSemesters $batch)
    {
        $this->authorize('restore', SchoolBillCycleSemesters::class);

        $batch->restore();

         Auth::user()->log(
            ' Gelombang pembayaran '.$batch->name.' telah dipulihkan '.
            ' pada semester '.$batch->semesters->name.
            ' <strong>[ID: ' . $batch->id . ']</strong>',
            SchoolBillCycleSemesters::class,
            $batch->id
        );

        return redirect()->route('administration::bill.batchs.index')
        ->with('success', 'Gelombang pembayaran <strong>'.$batch->name.'</strong> pada semester '.$batch->semesters->name.' berhasil dipulihkan');
    }

    public function kill(SchoolBillCycleSemesters $batch)
    {
        $this->authorize('kill', SchoolBillCycleSemesters::class);

        Auth::user()->log(
            ' Referensi pembayaran '.$batch->name.' telah dihapus permanen '.
            ' pada semester '.$batch->semesters->name.
            ' <strong>[ID: ' . $batch->id . ']</strong>',
            SchoolBillCycleSemesters::class,
            $batch->id
        );

        $tmp = $batch;
        $batch->forceDelete();

        return redirect()->route('administration::bill.batchs.index')
        ->with('success', 'Gelombang pembayaran <strong>'.$tmp->name.'</strong> pada semester '.$tmp->semesters->name.' berhasil dihapus permanen dari sistem');
    }

}
