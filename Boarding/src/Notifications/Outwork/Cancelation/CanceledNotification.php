<?php

namespace Modules\Portal\Notifications\Outwork\Cancelation;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeOutwork;

class CanceledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $outwork;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeOutwork $outwork)
    {
        $this->outwork = $outwork;
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
            ->subject('Seseorang mengajukan pembatalan kegiatan lainnya')
            ->greeting('Seseorang mengajukan pembatalan kegiatan lainnya')
            ->line($this->outwork->employee->user->name . ' mengajukan pembatalan kegiatan lainnya, klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::outwork.manage.show', ['outwork' => $this->outwork->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->outwork->employee->user->name . ' mengajukan pembatalan kegiatan lainnya!',
            'icon' => 'mdi mdi-calendar-minus',
            'color' => 'warning',
            'link' => route('portal::outwork.manage.show', ['outwork' => $this->vacation->id])
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
