<?php

namespace Modules\Portal\Notifications\Loan\Submission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeLoan;
use Modules\HRMS\Models\EmployeePosition;

class SubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $loan;
    public $position;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeLoan $loan, ?EmployeePosition $position)
    {
        $this->loan = $loan;
        $this->position = $position;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Seseorang mengajukan pinjaman')
            ->greeting('Seseorang mengajukan pinjaman')
            ->line($this->loan->employee->user->name . ' mengajukan pinjaman ' . $this->loan->category->name . '' . ($this->position ? ' yang telah disetujui oleh ' . $this->position->position->name . ' ' : '') . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::loan.manage.show', ['loan' => $this->loan->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        // tri mengajukan lembur yang sudah disetujui koord
        return [
            'message' => $this->loan->employee->user->name . ' mengajukan pinjaman' . ($this->position ? ' yang telah disetujui oleh ' . $this->position->position->name : '') . ', cek sekarang!',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'info',
            'link' => route('portal::loan.manage.show', ['loan' => $this->loan->id])
        ];
    }

    /**
     * Determine the notification's delivery delay.
     */
    public function withDelay($notifiable)
    {
        return [
            'mail' => now()->addSeconds(5),
            'database' => now(),
        ];
    }
}
