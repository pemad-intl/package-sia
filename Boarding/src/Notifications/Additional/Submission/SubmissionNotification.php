<?php

namespace Digipemad\Sia\Portal\Notifications\Additional\Submission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeOvertimeAddional;
use Modules\HRMS\Models\EmployeePosition;

class SubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $additional;
    public $position;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeOvertimeAddional $additional, ?EmployeePosition $position)
    {
        $this->additional = $additional;
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
            ->subject('Seseorang mengajukan lembur')
            ->greeting('Seseorang mengajukan lembur')
            ->line($this->additional->employee->user->name . ' mengajukan lembur pekerjaan tambahan #' . $this->additional->name . '' . ($this->position ? ' yang telah disetujui oleh ' . $this->position->position->name . ' ' : '') . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::additional.manage.show', ['additional' => $this->additional->id]))
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
            'message' => $this->additional->employee->user->name . ' mengajukan lembur' . ($this->position ? ' yang telah disetujui oleh ' . $this->position->position->name : '') . ', cek sekarang!',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'info',
            'link' => route('portal::additional.manage.show', ['additional' => $this->additional->id])
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
