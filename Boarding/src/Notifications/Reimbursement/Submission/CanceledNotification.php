<?php

namespace Modules\Portal\Notifications\Reimbursement\Submission;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeReimbursement;

class CanceledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $reimbursement;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeReimbursement $reimbursement)
    {
        $this->reimbursement = $reimbursement;
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
            ->subject('Seseorang membatalkan pengajuan reimbursement')
            ->greeting('Seseorang membatalkan pengajuan reimbursement')
            ->line($this->reimbursement->employee->user->name . ' membatalkan pengajuan reimbursement senilai ' . number_format($this->reimbursement->amount, 0, ',', '.') . ' rupiah, klik tombol di bawah untuk lihat detailnya.')
            ->action('Periksa sekarang', route('portal::reimbursement.manage.show', ['reimbursement' => $this->reimbursement->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->reimbursement->employee->user->name . ' membatalkan pengajuan reimbursement!',
            'icon' => 'mdi mdi-calendar-multiselect',
            'color' => 'info',
            'link' => route('portal::reimbursement.manage.show', ['reimbursement' => $this->reimbursement->id])
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
