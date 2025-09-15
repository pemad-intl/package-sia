<?php

namespace Modules\Portal\Notifications\Vacation\Submission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeVacation;

class CanceledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $vacation;
    public $label;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeVacation $vacation, $label = false)
    {
        $this->vacation = $vacation;
        $this->label = $label ? 'kompensasi cuti' : 'cuti/libur hari raya';
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
            ->subject('Seseorang membatalkan pengajuan ' . $this->label)
            ->greeting('Seseorang membatalkan pengajuan ' . $this->label)
            ->line($this->vacation->quota->employee->user->name . ' membatalkan pengajuan ' . $this->label . 'klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::vacation.manage.show', ['vacation' => $this->vacation->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->vacation->quota->employee->user->name . ' membatalkan pengajuan ' . $this->label,
            'icon' => 'mdi mdi-calendar-minus',
            'color' => 'danger',
            'link' => route('portal::vacation.manage.show', ['vacation' => $this->vacation->id])
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
