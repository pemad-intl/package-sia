<?php

namespace Modules\Portal\Notifications\Reimbursement\Manage;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeReimbursement;
use Modules\Core\Models\CompanyApprovable;

class ApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $reimbursement;
    public $approvable;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeReimbursement $reimbursement, CompanyApprovable $approvable)
    {
        $this->reimbursement = $reimbursement;
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
            ->subject('Selamat! Pengajuan reimbursement kamu disetujui')
            ->greeting('Selamat! Pengajuan reimbursement kamu disetujui')
            ->line('Pengajuan reimbursement ' . $this->reimbursement->category->name . ' senilai ' . number_format($this->reimbursement->amount, 0, ',', '.') . ' rupiah hari dengan deskripsi ' . ($this->reimbursement->description ?: '(tidak ada)') . ' telah disetujui oleh ' . $this->approvable->userable->position->level->label() . ', klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::reimbursement.submission.show', ['reimbursement' => $this->reimbursement->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Selamat! Pengajuan reimbursement ' . $this->reimbursement->category->name . ' senilai ' . number_format($this->reimbursement->amount, 0, ',', '.') . ' rupiah hari dengan deskripsi ' . ($this->reimbursement->description ?: '(tidak ada)') . ' telah disetujui oleh ' . $this->approvable->userable->position->level->label() . '.',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'success',
            'link' => route('portal::reimbursement.submission.show', ['reimbursement' => $this->reimbursement->id])
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
