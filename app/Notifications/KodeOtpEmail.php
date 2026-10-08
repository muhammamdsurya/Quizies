<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

/**
 * Email kode OTP login. Sengaja TIDAK di-queue (berbeda dengan bawaan Filament)
 * agar kode langsung terkirim tanpa perlu menjalankan queue worker.
 */
class KodeOtpEmail extends Notification
{
    public function __construct(
        public string $code,
        public int $codeExpiryMinutes,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode Verifikasi Masuk Kuiz Digital')
            ->greeting("Halo, {$notifiable->name}!")
            ->line('Gunakan kode berikut untuk menyelesaikan proses masuk ke Kuiz Digital:')
            ->line(new HtmlString('<p style="margin: 24px 0; text-align: center; font-size: 32px; font-weight: 700; letter-spacing: 10px; color: #002d57;">'.e($this->code).'</p>'))
            ->line("Kode ini berlaku selama {$this->codeExpiryMinutes} menit dan hanya bisa dipakai satu kali.")
            ->line('Jika Anda tidak sedang mencoba masuk, abaikan email ini dan segera ganti kata sandi Anda.')
            ->salutation(new HtmlString('Salam,<br>Tim Kuiz Digital — Universitas Darma Persada'));
    }
}
