<?php

namespace Digipemad\Sia\Administration\Http\Requests\Bill\Student;

use Illuminate\Foundation\Http\FormRequest;
use Digipemad\Sia\Academic\Models\StudentSemester;
use Digipemad\Sia\Academic\Models\AcademicClassroom;
use Digipemad\Sia\Administration\Models\SchoolBillReference;
use App\Models\References\GradeLevel;
use Digipemad\Sia\Administration\Models\SchoolBillStudent;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        // return auth()->user()->can('store', User::class);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules()
    {
        $rules = [
            'semester_id' => ['required'],
            'batch_id' => ['required'],
            'package' => ['required']
        ];

        if($this->input('status') == 2){
            $rules['class_id'] = ['required'];
            $rules['classroom_id'] = ['required'];
        }

        return $rules;
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes()
    {
        return [
            'item' => 'komponen tagihan',
            'students' => 'murid',
        ];
    }

    public function transform()
    {
        $batch = $this->input('batch_id');
        $package = $this->input('package');
        $education = $this->input('education');


        $items = SchoolBillReference::where([
            'batch_id' => $batch,
            'payment_category' => $package,
            'type_class' => $education
        ])->get()
        ->groupBy('payment_category')
        ->map(function ($group) {
            return $group->map(fn($item) => $item->id)->all(); // ambil hanya id 
        })
        ->all();

        if(!empty($education)){
            $grades = GradeLevel::where('grade_id', config('school.grade'))->pluck('id');
        } 

        if($this->input('status') == 1){
            $students = StudentSemester::with('classroom')->whereHas('classroom', function($q) use ($grades) {
                $q->whereIn('level_id', $grades);
            })->get();
        } else {
            $students = AcademicClassroom::with('stsems')
                ->where('id', $this->input('classroom_id'))
                ->get()
                ->flatMap->stsems;
        }

        $billings = [];
        
        foreach ($students as $studentSmt) {
            $existing = SchoolBillStudent::where('smt_id', $studentSmt->id)->first();
            
            if($existing) {
                $oldMeta = $existing->meta ?? [];
                $meta = array_replace_recursive($oldMeta, $items);
            } else {
                $meta = $items;
            }
            
            $billings[] = [
                'smt_id'    => $studentSmt->id,
                'meta'  => $meta,
            ];
        }

        return [
            'billings' => $billings,
            'batch_id' => $batch
        ];
    }
}