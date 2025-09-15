<?php

namespace Modules\Portal\Notifications\Vacation\Cancelation;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeVacation;
use Modules\Core\Models\CompanyApprovable;

class RejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $vacation;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeVacation $vacation)
    {
        $this->vacation = $vacation;
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
            ->subject('Maaf! Pengajuan pembatalan tanggal cuti/libur hari raya kamu belum disetujui')
            ->greeting('Maaf! Pengajuan pembatalan tanggal cuti/libur hari raya kamu belum disetujui')
            ->line('Pengajuan pembatalan tanggal cuti/libur hari raya kamu sebanyak ' . $this->vacation->dates->count() . ' hari untuk keperluan ' . ($this->vacation->description ?: 'istirahat') . ' belum disetujui, klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::vacation.submission.show', ['vacation' => $this->vacation->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Maaf! Pengajuan pembatalan tanggal cuti/libur hari raya kamu sebanyak ' . $this->vacation->dates->count() . ' hari untuk keperluan ' . ($this->vacation->description ?: 'istirahat') . ' belum disetujui.',
            'icon' => 'mdi mdi-calendar-minus',
            'color' => 'danger',
            'link' => route('portal::vacation.submission.show', ['vacation' => $this->vacation->id])
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
