<?php

namespace Modules\Portal\Notifications\Vacation\Manage;

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
    public $approvable;
    public $label;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeVacation $vacation, CompanyApprovable $approvable, $label = false)
    {
        $this->vacation = $vacation;
        $this->approvable = $approvable;
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
        $mail = (new MailMessage);

        if ($ccs = $this->approvable->load('modelable.approvables.userable.employee.user')->modelable->approvables->filter(fn ($a) => $a->level < $this->approvable->level)) {
            $mail->bcc($ccs->pluck('userable.employee.user.email_address')->filter());
        }

        return $mail->subject('Maaf ' . $this->vacation->quota->employee->user->name . '! Pengajuan ' . $this->label . ' kamu belum disetujui')
            ->greeting('Maaf! Pengajuan ' . $this->label . ' kamu belum disetujui')
            ->line('Pengajuan ' . $this->label . ' kamu sebanyak ' . $this->vacation->dates->count() . ' hari untuk keperluan ' . ($this->vacation->description ?: 'istirahat') . ' belum disetujui oleh ' . $this->approvable->userable->position->level->label() . ', klik tombol di bawah untuk lihat detailnya.')
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
            'message' => 'Maaf! Pengajuan ' . $this->label . ' kamu sebanyak ' . $this->vacation->dates->count() . ' hari untuk keperluan ' . ($this->vacation->description ?: 'istirahat') . ' belum disetujui oleh ' . $this->approvable->userable->position->level->label() . '.',
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
