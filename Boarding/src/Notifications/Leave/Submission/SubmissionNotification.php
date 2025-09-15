<?php

namespace Modules\Boarding\Notifications\Leave\Submission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Boarding\Models\BoardingStudentsLeave;
use Modules\HRMS\Models\EmployeePosition;

class SubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $leave;
    public $position;

    /**
     * Create a new notification instance.
     */
    public function __construct(BoardingStudentsLeave $leave, ?EmployeePosition $position)
    {
        $this->leave = $leave;
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
        //route('portal::leave.manage.show', ['leave' => $this->leave->id])
        return (new MailMessage)
            ->subject('Seseorang mengajukan izin')
            ->greeting('Seseorang mengajukan izin')
            ->line($this->leave->student->user->name . ' mengajukan izin ' . $this->leave->category->name . '' . ($this->position ? ' yang telah disetujui oleh ' . $this->position->position->name . ' ' : '') . ' dengan keperluan ' . ($this->leave->description ?: 'istirahat') . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', '')
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->leave->student->user->name . ' mengajukan izin' . ($this->position ? ' yang telah disetujui oleh ' . $this->position->position->name . ' ' : '') . ', cek sekarang!',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'info',
            'link' => route('boarding::leave.manage.show', ['leave' => $this->leave->id])
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
