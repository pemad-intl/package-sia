<?php

namespace Digipemad\Sia\Boarding\Http\Controllers\Event;

use Auth;
use Illuminate\Http\Request;
use Digipemad\Sia\Boarding\Http\Controllers\Controller;
use Digipemad\Sia\Boarding\Models\BoardingReferenceEvent;
use Digipemad\Sia\Academic\Models\Student;
use Digipemad\Sia\Administration\Models\SchoolBuilding;
use Illuminate\Support\Arr;

class EventReferenceController extends Controller
{
    public function index(Request $request)
    {
        $boardingEvent = BoardingReferenceEvent::where('grade_id', userGrades())->whereNull('deleted_at')->paginate(10);
        
        return view('boarding::event.index', compact('boardingEvent'));
    }

    public function store(Request $request)
    {
        $data = array_merge(
            Arr::only($request->all(), [
                'name', 
                'type', 
                'start_date', 
                'end_date', 
                'in', 
                'out', 
                'type_participant'
            ]),
            [
                'grade_id' => userGrades()
            ]
        );

        $boardEvent = BoardingReferenceEvent::create($data);
        
        if($boardEvent){

            Auth::user()->log(
                ' Kegiatan bernama '.$boardEvent->name. '<strong>'.' telah ditambahkan '.'</strong>' .
                ' <strong>[ID: ' . $boardEvent->id . ']</strong>',
                BoardingReferenceEvent::class,
                $boardEvent->id
            );

            return redirect($request->input('next', route('boarding::event.event-reference.index')))
            ->with('success', 'Data berhasil disimpan.'); 
        }

        return redirect($request->input('next', route('boarding::event.event-reference.index')))
                ->with('error', 'Data gagal disimpan.');
        
    }

    public function edit(BoardingReferenceEvent $event_reference)
    {
        $boardingEvent = BoardingReferenceEvent::whereNull('deleted_at')
            ->paginate(10);

        return view('boarding::event.index', [
            'boardingEvent' => $boardingEvent,
            'editMode' => true,
            'editItem' => $event_reference
        ]);
    }

    public function update(BoardingReferenceEvent $event_reference, Request $request)
    {
        $data = array_merge(
            Arr::only($request->all(), [
                'name',
                'type',
                'start_date',
                'end_date',
                'in',
                'out',
                'type_participant',
            ]),
            [
                'grade_id' => userGrades()
            ]
        );

        
        $event = $event_reference->update($data);

        if($event){
            Auth::user()->log(
                ' Kegiatan bernama '.$event_reference->name. '<strong>'.' telah diperbarui '.'</strong>' .
                ' <strong>[ID: ' . $event_reference->id . ']</strong>',
                BoardingReferenceEvent::class,
                $event_reference->id
            );

            return redirect()->route('boarding::event.event-reference.index')
                ->with('success', 'Data berhasil diperbarui.');
        } 
        
        return redirect()->route('boarding::event.event-reference.index')
                ->with('error', 'Data gagal diperbarui.');
        
    }

    public function destroy(BoardingReferenceEvent $event_reference)
    {
        $event = $event_reference->delete();

        if($event){
            
            Auth::user()->log(
                ' Kegiatan bernama '.$event_reference->name. '<strong>'.' telah dihapus '.'</strong>' .
                ' <strong>[ID: ' . $event_reference->id . ']</strong>',
                BoardingReferenceEvent::class,
                $event_reference->id
            );

            return redirect()->route('boarding::event.event-reference.index')
                ->with('success', 'Data berhasil dihapus.');
        }

        return redirect()->route('boarding::event.event-reference.index')
            ->with('error', 'Gagal menghapus data.');
        
    }
}
