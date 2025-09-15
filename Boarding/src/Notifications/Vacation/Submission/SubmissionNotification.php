<?php

namespace Modules\Portal\Notifications\Vacation\Submission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeePosition;
use Modules\HRMS\Models\EmployeeVacation;

class SubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $vacation;
    public $label;
    public $position;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeVacation $vacation, $label = false, ?EmployeePosition $position)
    {
        $this->vacation = $vacation;
        $this->label = $label ? 'kompensasi cuti' : 'cuti/libur hari raya';
        $this->position = $position;
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
            ->subject('Seseorang mengajukan ' . $this->label)
            ->greeting('Seseorang mengajukan ' . $this->label)
            ->line($this->vacation->quota->employee->user->name . ' mengajukan ' . $this->label . '' . ($this->position ? ' yang telah disetujui oleh ' . $this->position->position->name . ' ' : '') . ' sebanyak ' . $this->vacation->dates->count() . ' hari untuk keperluan ' . ($this->vacation->description ?: 'istirahat') . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::vacation.manage.show', ['vacation' => $this->vacation->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->vacation->quota->employee->user->name . ' mengajukan ' . $this->label . '' . ($this->position ? ' yang telah disetujui oleh ' . $this->position->position->name . ' ' : '') . ', cek sekarang!',
            'icon' => 'mdi mdi-calendar-minus',
            'color' => 'info',
            'link' => route('portal::vacation.manage.show', ['vacation' => $this->vacation->id])
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
