<?php

namespace Digipemad\Sia\Portal\Notifications\Additional\Submission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeOvertimeAddional;

class CanceledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $additional;
    public $label;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeOvertimeAddional $additional, $label = false)
    {
        $this->additional = $additional;
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
            ->subject('Seseorang membatalkan pengajuan lembur kegiatan tambahan')
            ->greeting('Seseorang membatalkan pengajuan lembur kegiatan tambahan')
            ->line($this->additional->employee->user->name . ' membatalkan pengajuan lembur kegiatan tambahan, ' . 'klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::additional.manage.show', ['additional' => $this->additional->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->additional->employee->user->name . ' membatalkan pengajuan lembur',
            'icon' => 'mdi mdi-calendar-minus',
            'color' => 'danger',
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
