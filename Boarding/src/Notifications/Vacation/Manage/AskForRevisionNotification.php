<?php

namespace Modules\Portal\Notifications\Vacation\Manage;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeVacation;
use Modules\Core\Models\CompanyApprovable;

class AskForRevisionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $vacation;
    public $approvable;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeVacation $vacation, CompanyApprovable $approvable)
    {
        $this->vacation = $vacation;
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

        return $mail->subject('Hai ' . $this->vacation->quota->employee->user->name . '! Silakan revisi pengajuan cuti/libur hari raya kamu')
            ->greeting('Silakan revisi pengajuan cuti/libur hari raya kamu')
            ->line('Pengajuan cuti/libur hari raya kamu sebanyak ' . $this->vacation->dates->count() . ' hari untuk keperluan ' . ($this->vacation->description ?: 'istirahat') . ' telah mendapat pesan untuk revisi dari ' . $this->approvable->userable->position->level->label() . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::vacation.submission.edit', ['vacation' => $this->vacation->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Pengajuan cuti/libur hari raya kamu sebanyak ' . $this->vacation->dates->count() . ' hari untuk keperluan ' . ($this->vacation->description ?: 'istirahat') . ' telah mendapat pesan untuk revisi dari ' . $this->approvable->userable->position->level->label() . '.',
            'icon' => 'mdi mdi-calendar-minus',
            'color' => 'warning',
            'link' => route('portal::vacation.submission.edit', ['vacation' => $this->vacation->id])
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
