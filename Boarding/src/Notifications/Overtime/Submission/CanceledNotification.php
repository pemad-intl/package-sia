<?php

namespace Modules\Portal\Notifications\Overtime\Submission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeOvertime;

class CanceledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $overtime;
    public $label;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeOvertime $overtime, $label = false)
    {
        $this->overtime = $overtime;
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
            ->subject('Seseorang membatalkan pengajuan lembur')
            ->greeting('Seseorang membatalkan pengajuan lembur')
            ->line($this->overtime->quota->employee->user->name . ' membatalkan pengajuan lembur, ' . 'klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::overtime.manage.show', ['overtime' => $this->overtime->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->overtime->quota->employee->user->name . ' membatalkan pengajuan lembur',
            'icon' => 'mdi mdi-calendar-minus',
            'color' => 'danger',
            'link' => route('portal::overtime.manage.show', ['overtime' => $this->overtime->id])
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
