<?php

namespace Modules\Portal\Notifications\Leave\Manage;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeLeave;
use Modules\Core\Models\CompanyApprovable;

class RejectedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $leave;
    public $approvable;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeLeave $leave, CompanyApprovable $approvable)
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
        $mail = (new MailMessage);

        if ($ccs = $this->approvable->load('modelable.approvables.userable.employee.user')->modelable->approvables->filter(fn ($a) => $a->level < $this->approvable->level)) {
            $mail->bcc($ccs->pluck('userable.employee.user.email_address')->filter());
        }

        return $mail->subject('Maaf ' . $this->leave->employee->user->name . '! Pengajuan izin kamu belum disetujui')
            ->greeting('Maaf! Pengajuan izin kamu belum disetujui')
            ->line('Pengajuan izin ' . $this->leave->category->name . ' kamu hari untuk keperluan ' . ($this->leave->description ?: 'istirahat') . ' belum disetujui oleh ' . $this->approvable->userable->position->level->label() . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::leave.submission.show', ['leave' => $this->leave->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Maaf! Pengajuan izin ' . $this->leave->category->name . ' kamu hari untuk keperluan ' . ($this->leave->description ?: 'istirahat') . ' belum disetujui oleh ' . $this->approvable->userable->position->level->label() . '.',
            'icon' => 'mdi mdi-calendar-minus',
            'color' => 'danger',
            'link' => route('portal::leave.submission.show', ['leave' => $this->leave->id])
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
