<?php

namespace Modules\Boarding\Notifications\Leave\Manage;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Boarding\Models\BoardingStudentsLeave;
use Modules\Boarding\Models\BoardingCompanyApprovable;

class ApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $leave;
    public $approvable;

    /**
     * Create a new notification instance.
     */
    public function __construct(BoardingStudentsLeave $leave, BoardingCompanyApprovable $approvable)
    {
        $this->leave = $leave;
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
            ->subject('Selamat! Pengajuan izin kamu disetujui')
            ->greeting('Selamat! Pengajuan izin kamu disetujui')
            ->line('Pengajuan izin ' . $this->leave->category->name . ' kamu hari untuk keperluan ' . ($this->leave->description ?: 'istirahat') . ' telah disetujui oleh ' . $this->approvable->userable->position->position_enum_student->label() . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('boarding::leave.submission.show', ['leave' => $this->leave->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Selamat! Pengajuan izin ' . $this->leave->category->name . ' kamu untuk keperluan ' . ($this->leave->description ?: 'istirahat') . ' telah disetujui oleh ' . $this->approvable->userable->position->position_enum_student->label() . '.',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'success',
            'link' => route('boarding::leave.submission.show', ['leave' => $this->leave->id])
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
