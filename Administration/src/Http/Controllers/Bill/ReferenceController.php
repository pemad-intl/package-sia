<?php

namespace Digipemad\Sia\Administration\Http\Controllers\Bill;

use Illuminate\Http\Request;
use Digipemad\Sia\Administration\Http\Controllers\Controller;
use Digipemad\Sia\Administration\Models\SchoolBillCycleSemesters;
use Digipemad\Sia\Administration\Models\SchoolBillReference;
use Digipemad\Sia\Academic\Models\AcademicSemester;
use Modules\Sia\Core\Enums\BillCategoryEnum;
use Auth;

class ReferenceController extends Controller
{
    /**
     * index.
     */
    public function index(Request $request)
    {
        $this->authorize('access', SchoolBillReference::class);

        $user = auth()->user();

    	$trashed = $request->get('trash', 0);

    	$bills = SchoolBillReference::query()
            ->when($request->batch_id, fn($q) => $q->where('batch_id', $request->batch_id))
            ->when($request->class_id, fn($q) => $q->where('type_class', $request->class_id))
            ->where('name', 'like', '%' . $request->get('search') . '%')
            ->where('type_class', userGrades())
            ->when($trashed, fn($q) => $q->onlyTrashed())
            ->paginate($request->get('limit', 10));

        $billCount = SchoolBillReference::query()
            ->when($request->batch_id, fn($q) => $q->where('batch_id', $request->batch_id))
            ->when($request->class_id, fn($q) => $q->where('type_class', $request->class_id))
            ->where('type_class', userGrades())
            ->whereNull('deleted_at')->count();

        $billCategories = BillCategoryEnum::cases();

        $editBill = null;
        if ($request->filled('edit')) {
            $editBill = SchoolBillReference::findOrFail($request->get('edit'));
        }

        $academicSmt = AcademicSemester::whereNull('deleted_at')->get();
        $academicBatch = SchoolBillCycleSemesters::with('semesters')->whereNull('deleted_at')->get();

        return view('administration::bill.reference.index', compact('user', 'bills','billCount', 'billCategories', 'editBill', 'academicSmt', 'academicBatch'));
    }

    public function store(Request $request)
    {
        $this->authorize('store', SchoolBillReference::class);

        $reference = new SchoolBillReference([
            'batch_id' => $request->input('batch_id'),
            'kd' => $request->input('kd'),
            'name' => $request->input('name'),
            'payment_category' => $request->input('payment_category'),
            'payment_cycle' => $request->input('payment_cycle'),
            'price' => $request->input('price'), 
            'type' => $request->input('type')
        ]);

        if($reference->save()){
            Auth::user()->log(
                ' Referensi pembayaran '.$reference->name.' telah dihapus '.
                ' <strong>[ID: ' . $reference->id . ']</strong>',
                SchoolBillReference::class,
                $reference->id
            );

            return redirect()->back()->with('success', 'Referensi Pembayaran <strong>'.$reference->name.'</strong> berhasil disimpan</strong>');
        } 

        return redirect()->back()->with('danger', 'Referensi Pembayaran <strong>'.$reference->name.'</strong> gagal disimpan</strong>');
    }

    public function update(SchoolBillReference $reference, Request $request){
        
        $this->authorize('update', SchoolBillReference::class);

        if ($reference->trashed()) abort(404);

        if($reference->update([
            'batch_id' => $request->input('batch_id'),
            'kd' => $request->input('kd'),
            'name' => $request->input('name'),
            'payment_category' => $request->input('payment_category'),
            'payment_cycle' => $request->input('payment_cycle'),
            'price' => $request->input('price'), 
            'type' => $request->input('type')
        ])){
            Auth::user()->log(
                ' Referensi pembayaran '.$reference->name.' telah diperbarui '.
                ' <strong>[ID: ' . $reference->id . ']</strong>',
                SchoolBillReference::class,
                $reference->id
            );

            return redirect()
                ->route('administration::bill.references.index')
                ->with('success', 'Referensi Pembayaran <strong>' . $reference->name . '</strong> berhasil diperbarui');
        }

        return redirect()
                ->route('administration::bill.references.index')
                ->with('danger', 'Referensi Pembayaran <strong>' . $reference->name . '</strong> gagal diperbarui');
    }

    public function show(SchoolBillReference $room)
    {
        // $this->authorize('show', SchoolBuildingRoom::class);

        // if($room->trashed()) abort(404);


        // return view('administration::facility.rooms.show', compact('room'));
    }

    public function destroy(SchoolBillReference $reference)
    {
        $this->authorize('destroy', SchoolBillReference::class);
        if($reference->delete()){
            Auth::user()->log(
                ' Referensi pembayaran '.$reference->name.' telah dihapus '.
                ' <strong>[ID: ' . $reference->id . ']</strong>',
                SchoolBillReference::class,
                $reference->id
            );

            return redirect()->back()->with('success', 'Referensi Pembayaran <strong>'.$reference->name.'</strong> berhasil dihapus');
        }

        return redirect()->back()->with('danger', 'Referensi Pembayaran <strong>'.$reference->name.'</strong> gagal dihapus');
    }

    public function restore(SchoolBillReference $reference)
    {
        $this->authorize('restore', SchoolBillReference::class);

        $reference->restore();

         Auth::user()->log(
            ' Referensi pembayaran '.$reference->name.' telah dipulihkan '.
            ' <strong>[ID: ' . $reference->id . ']</strong>',
            SchoolBillReference::class,
            $reference->id
        );

        return redirect()->back()->with('success', 'Referensi pembayaran <strong>'.$reference->name.'</strong> berhasil dipulihkan');
    }

    public function kill(SchoolBillReference $reference)
    {
        $this->authorize('kill', SchoolBillReference::class);

        Auth::user()->log(
            ' Referensi pembayaran '.$reference->name.' telah dihapus permanen '.
            ' <strong>[ID: ' . $reference->id . ']</strong>',
            SchoolBillReference::class,
            $reference->id
        );

        $tmp = $room;
        $room->forceDelete();

        return redirect()->back()->with('success', 'Referensi pembayaran <strong>'.$tmp->name.'</strong> berhasil dihapus permanen dari sistem');
    }

}
