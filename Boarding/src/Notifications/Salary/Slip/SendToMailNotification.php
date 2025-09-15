<?php

namespace Modules\Portal\Notifications\Salary\Slip;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\HRMS\Models\EmployeeSalary;

class SendToMailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $salary;

    /**
     * Create a new notification instance.
     */
    public function __construct(EmployeeSalary $salary)
    {
        $this->salary = $salary;
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
            ->subject('Terima kasih telah menandatangani slip ' . $this->salary->name)
            ->greeting('Terima kasih telah menandatangani slip ' . $this->salary->name)
            ->line('Terima kasih telah menandatangani slip ' . $this->salary->name . ' pada periode ' . $this->salary->end_at->isoFormat('DD MMMM YYYY') . ', klik tombol di bawah untuk melihat slip ')
            ->action('Lihat slip', route('portal::salary.slips.show', ['salary' => $this->salary->id]))
            ->line('Jika Anda membutuhkan informasi lebih lanjut, segera hubungi kami untuk menindak lanjuti.')
            ->line('Terima kasih telah menggunakan layanan kami.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Terima kasih telah menandatangani slip ' . $this->salary->name,
            'icon' => 'mdi mdi-file-plus-outline',
            'color' => 'info',
            'link' => route('portal::salary.slips.show', ['salary' => $this->salary->id])
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
