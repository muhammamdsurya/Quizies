<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\KodeOtpEmail;
use Filament\Auth\Pages\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Tests\TestCase;

class OtpEmailTest extends TestCase
{
    use RefreshDatabase;

    // Sama seperti setelah App Password Gmail diisi di .env
    private function aktifkanSmtp(): void
    {
        config(['mail.default' => 'smtp', 'mail.mailers.smtp.password' => 'app-password-uji']);
    }

    public function test_tanpa_app_password_login_tidak_meminta_otp(): void
    {
        Notification::fake();
        $user = User::factory()->create(['role' => 'kaprodi']);

        Livewire::test(Login::class)
            ->fillForm(['email' => $user->email, 'password' => 'password'])
            ->call('authenticate')
            ->assertHasNoFormErrors();

        $this->assertAuthenticatedAs($user);
        Notification::assertNothingSent();
    }

    public function test_login_meminta_kode_otp_yang_dikirim_ke_email(): void
    {
        $this->aktifkanSmtp();
        Notification::fake();
        $user = User::factory()->create(['role' => 'mahasiswa']);

        $login = Livewire::test(Login::class)
            ->fillForm(['email' => $user->email, 'password' => 'password'])
            ->call('authenticate');

        // Password benar belum cukup: masih tamu sampai kode OTP dimasukkan
        $this->assertGuest();

        $kode = null;
        Notification::assertSentTo($user, KodeOtpEmail::class, function (KodeOtpEmail $notifikasi) use (&$kode) {
            $kode = $notifikasi->code;

            return $notifikasi->via($notifikasi) === ['mail'];
        });
        $this->assertMatchesRegularExpression('/^\d{6}$/', $kode);

        $login->set('data.multiFactor.email_code.code', $kode === '000000' ? '111111' : '000000')
            ->call('authenticate')
            ->assertHasErrors();
        $this->assertGuest();

        $login->set('data.multiFactor.email_code.code', $kode)
            ->call('authenticate')
            ->assertHasNoErrors();

        $this->assertAuthenticatedAs($user);
    }

    public function test_email_otp_berisi_kode_dan_masa_berlaku(): void
    {
        $user = User::factory()->make(['name' => 'Budi']);
        $html = (string) (new KodeOtpEmail('123456', 10))->toMail($user)->render();

        $this->assertStringContainsString('123456', $html);
        $this->assertStringContainsString('10 menit', $html);
        $this->assertStringContainsString('Halo, Budi!', $html);
    }

    public function test_login_fortify_ditolak_saat_otp_aktif(): void
    {
        $this->aktifkanSmtp();
        $user = User::factory()->create();

        $this->post(route('login.store'), ['email' => $user->email, 'password' => 'password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }
}
