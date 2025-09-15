<?php

namespace Digipemad\Sia\Portal\Notifications\Counseling\Submission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeCounseling;

class CanceledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $counseling;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeCounseling $counseling)
    {
        $this->counseling = $counseling;
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
            ->subject('Seseorang membatalkan pengajuan konseling')
            ->greeting('Seseorang membatalkan pengajuan konseling')
            ->line($this->counseling->employee->user->name . ' membatalkan pengajuan konseling, klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::counseling.manage.show', ['counseling' => $this->counseling->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->counseling->employee->user->name ?? 'Seseorang ' . ' membatalkan pengajuan konseling!',
            'icon'    => 'mdi mdi-calendar-multiselect',
            'color'   => 'info',
            'link'    => route('portal::counseling.manage.show', ['counseling' => $this->counseling->id])
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
