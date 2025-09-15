<?php

namespace Modules\Portal\Notifications\Holiday\Submission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeHoliday;
use Modules\HRMS\Models\EmployeePosition;

class SubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $holiday;
    public $position;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeHoliday $holiday, ?EmployeePosition $position)
    {
        $this->holiday = $holiday;
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
            ->subject('Seseorang mengajukan hari libur')
            ->greeting('Seseorang mengajukan hari libur')
            ->line($this->holiday->employee->user->name . ' mengajukan tanggal libur ' . ($this->position ? ' yang telah disetujui oleh ' . $this->position->position->name . ' ' : '') . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::holiday.manage.show', ['leave' => $this->holiday->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->holiday->employee->user->name . ' mengajukan hari libur' . ($this->position ? ' yang telah disetujui oleh ' . $this->position->position->name . ' ' : '') . ', cek sekarang!',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'info',
            'link' => route('portal::holiday.manage.show', ['leave' => $this->holiday->id])
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
