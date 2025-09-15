<?php

namespace Digipemad\Sia\Portal\Notifications\Holiday\Manage;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeHoliday;
use Modules\Core\Models\CompanyApprovable;

class ApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $holiday;
    public $approvable;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeHoliday $holiday, CompanyApprovable $approvable)
    {
        $this->holiday = $holiday;
        $this->approvable = $approvable;
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
            ->subject('Selamat! Pengajuan libur kamu disetujui')
            ->greeting('Selamat! Pengajuan libur kamu disetujui')
            ->line('Pengajuan libur kamu telah disetujui oleh ' . $this->approvable->userable->position->level->label() . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::holiday.submission.show', ['holiday' => $this->holiday->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Selamat! Pengajuan libur kamu telah disetujui oleh ' . $this->approvable->userable->position->level->label() . '.',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'success',
            'link' => route('portal::holiday.submission.show', ['holiday' => $this->holiday->id])
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
