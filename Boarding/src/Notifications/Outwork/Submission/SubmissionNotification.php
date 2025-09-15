<?php

namespace Modules\Portal\Notifications\Outwork\Submission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeOutwork;
use Modules\HRMS\Models\EmployeePosition;

class SubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $outwork;
    public $position;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeOutwork $outwork, ?EmployeePosition $position)
    {
        $this->outwork = $outwork;
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
            ->subject('Seseorang mengajukan insentif kegiatan lainnya')
            ->greeting('Seseorang mengajukan insentif kegiatan lainnya')
            ->line($this->outwork->employee->user->name . ' mengajukan insentif kegiatan lainnya ' . $this->outwork->name . '' . ($this->position ? ' yang telah disetujui oleh ' . $this->position->position->name . ' ' : '') . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::outwork.manage.show', ['outwork' => $this->outwork->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->outwork->employee->user->name . ' mengajukan insentif kegiatan lainnya' . ($this->position ? ' yang telah disetujui oleh ' . $this->position->position->name . ' ' : '') . ', cek sekarang!',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'info',
            'link' => route('portal::outwork.manage.show', ['outwork' => $this->outwork->id])
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
