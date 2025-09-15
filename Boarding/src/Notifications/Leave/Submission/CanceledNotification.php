<?php

namespace Modules\Portal\Notifications\Leave\Submission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeLeave;

class CanceledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $leave;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeLeave $leave)
    {
        $this->leave = $leave;
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
            ->subject('Seseorang membatalkan pengajuan izin')
            ->greeting('Seseorang membatalkan pengajuan izin')
            ->line($this->leave->employee->user->name . ' membatalkan pengajuan izin, klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::leave.manage.show', ['leave' => $this->leave->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->leave->employee->user->name . ' membatalkan pengajuan izin!',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'info',
            'link' => route('portal::leave.manage.show', ['leave' => $this->leave->id])
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
