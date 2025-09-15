<?php

namespace Digipemad\Sia\Portal\Notifications\Counseling\Submission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeCounseling;

class SubmissionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $counseling;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeCounseling $counseling)
    {
        $this->counseling = $counseling;
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
            ->cc(config('modules.counseling.features.cc'))
            ->subject('Seseorang mengajukan konseling')
            ->greeting('Seseorang mengajukan konseling')
            ->line($this->counseling->employee->user->name . ' mengajukan konseling ' . $this->counseling->category->name . ' dengan deskripsi ' . ($this->counseling->description ?: '-') . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route(config('modules.counseling.features.empl_route'), ['counseling' => $this->counseling->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->counseling->employee->user->name ?? 'Seseorang ' . ' mengajukan konseling, cek sekarang!',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'info',
            'link' => route(config('modules.counseling.features.empl_route'), ['counseling' => $this->counseling->id])
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
