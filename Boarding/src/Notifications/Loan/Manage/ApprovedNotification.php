<?php

namespace Modules\Portal\Notifications\Loan\Manage;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeLoan;
use Modules\Core\Models\CompanyApprovable;

class ApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $loan;
    public $approvable;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeLoan $loan, CompanyApprovable $approvable)
    {
        $this->loan = $loan;
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
            ->subject('Selamat! Pengajuan pinjaman kamu disetujui')
            ->greeting('Selamat! Pengajuan pinjaman kamu disetujui')
            ->line('Pengajuan pinjaman ' . $this->loan->category->name . ' sejumlah Rp. ' . number_format($this->loan->amount_total, 0) . ' telah disetujui oleh ' . $this->approvable->userable->position->level->label() . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::loan.submission.show', ['loan' => $this->loan->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Selamat, pengajuan pinjaman ' . $this->loan->category->name . ' sejumlah Rp. ' . number_format($this->loan->amount_total, 0) . ' telah disetujui oleh ' . $this->approvable->userable->position->level->label() . '.',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'success',
            'link' => route('portal::loan.submission.show', ['loan' => $this->loan->id])
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
