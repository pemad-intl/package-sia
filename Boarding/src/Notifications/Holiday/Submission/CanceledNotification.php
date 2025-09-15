<?php

namespace Digipemad\Sia\Portal\Notifications\Holiday\Submission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeHoliday;

class CanceledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $holiday;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeHoliday $holiday)
    {
        $this->holiday = $holiday;
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
            ->subject('Seseorang membatalkan pengajuan hari libur')
            ->greeting('Seseorang membatalkan pengajuan hari libur')
            ->line($this->holiday->employee->user->name . ' membatalkan pengajuan hari libur, klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::holiday.manage.show', ['holiday' => $this->holiday->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->holiday->employee->user->name . ' membatalkan pengajuan hari libur!',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'info',
            'link' => route('portal::holiday.manage.show', ['holiday' => $this->holiday->id])
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
