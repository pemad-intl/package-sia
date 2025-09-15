<?php

namespace Modules\Portal\Notifications\Overtime\Manage;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeOvertime;
use Modules\Core\Models\CompanyApprovable;

class ApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $overtime;
    public $approvable;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeOvertime $overtime, CompanyApprovable $approvable)
    {
        $this->overtime = $overtime;
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
            ->subject('Selamat! Pengajuan lembur kamu disetujui')
            ->greeting('Selamat! Pengajuan lembur kamu disetujui')
            ->line('Pengajuan lembur ' . $this->overtime->name . ' telah disetujui oleh ' . $this->approvable->userable->position->level->label() . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::overtime.submission.show', ['overtime' => $this->overtime->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Selamat! Pengajuan lembur ' . $this->overtime->name . ' telah disetujui oleh ' . $this->approvable->userable->position->level->label() . '.',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'success',
            'link' => route('portal::overtime.submission.show', ['overtime' => $this->overtime->id])
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
