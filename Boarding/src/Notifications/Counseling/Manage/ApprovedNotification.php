<?php

namespace Digipemad\Sia\Portal\Notifications\Counseling\Manage;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\Core\Models\CompanyApprovable;
use Modules\HRMS\Models\EmployeeCounseling;

class ApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $counseling;
    public $approvable;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeCounseling $counseling, CompanyApprovable $approvable)
    {
        $this->counseling = $counseling;
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
            ->subject('Selamat! Pengajuan konseling kamu disetujui')
            ->greeting('Selamat! Pengajuan konseling kamu disetujui')
            ->line('Pengajuan konseling ' . $this->counseling->category->name . ' kamu dengan keluhan ' . ($this->counseling->description ?: '-') . ' telah disetujui oleh ' . $this->approvable->userable->position->level->label() . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::counseling.submission.show', ['counseling' => $this->counseling->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Selamat! Pengajuan konseling ' . $this->counseling->category->name . ' kamu dengan keluhan ' . ($this->counseling->description ?: '-') . ' telah disetujui oleh ' . $this->approvable->userable->position->level->label() . '.',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'success',
            'link' => route('portal::counseling.submission.show', ['counseling' => $this->counseling->id])
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
