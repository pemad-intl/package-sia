<?php

namespace Digipemad\Sia\Academic\Http\Controllers\Leave;

use PDF;
use Str;
use Storage;
use Modules\HRMS\Models\EmployeePosition;
use Modules\HRMS\Models\EmployeeLeave;
use Modules\Core\Enums\ApprovableResultEnum;
use Modules\Portal\Http\Controllers\Controller;
use Modules\Docs\Models\Document;

class PrintController extends Controller
{
	/**
	 * Print the document
	 */
	public function index(EmployeeLeave $leave)
	{
		$document = $leave->firstOrCreateDocument(
			$title = 'Surat Permohonan Izin - ' . $leave->employee->user->name . ' - ' . $leave->created_at->getTimestamp(),
			$path = 'employee/leaves/' . Str::random(36) . '.pdf'
		);

		$document->sign($leave->approvables->filter(
			fn ($a) =>
			$a->result == ApprovableResultEnum::APPROVE || $a->cancelable
		)->pluck('userable.employee.user_id')->prepend($leave->employee->user_id));

		$to = $leave->approvables->sortByDesc('level')->first()?->userable ?: null;

		Storage::disk('docs')->put($document->path, PDF::loadView('portal::leave.print.letter', compact('leave', 'document', 'title', 'to'))->output());

		return $document->show();
	}
}
