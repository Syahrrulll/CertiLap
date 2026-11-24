<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\RiwayatDiagnosa;
use App\Services\CertaintyFactorService;

class SistemPakarTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_landing_page_is_accessible_to_guest(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('CertiLap');
        $response->assertSee('Certainty Factor');
        $response->assertSee('Kerusakan pada RAM');
    }

    public function test_user_can_login(): void
    {
        $user = User::where('username', 'user')->first();

        $response = $this->post(route('login.post'), [
            'username' => 'user',
            'password' => 'user123',
        ]);

        $response->assertRedirect(route('user.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_admin_can_login(): void
    {
        $admin = User::where('username', 'admin')->first();

        $response = $this->post(route('login.post'), [
            'username' => 'admin',
            'password' => 'admin123',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($admin);
    }

    public function test_non_admin_cannot_access_admin_dashboard(): void
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_certainty_factor_service_combine_formula(): void
    {
        // 1. Keduanya positif: CF1 + CF2 * (1 - CF1)
        // CF1 = 0.8, CF2 = 0.6 -> 0.8 + 0.6 * (1 - 0.8) = 0.92
        $resPositive = CertaintyFactorService::combine(0.8, 0.6);
        $this->assertEqualsWithDelta(0.92, $resPositive, 0.0001);

        // 2. Keduanya negatif: CF1 + CF2 * (1 + CF1)
        // CF1 = -0.4, CF2 = -0.5 -> -0.4 + (-0.5) * (1 + (-0.4)) = -0.7
        $resNegative = CertaintyFactorService::combine(-0.4, -0.5);
        $this->assertEqualsWithDelta(-0.7, $resNegative, 0.0001);

        // 3. Berlawanan tanda: (CF1 + CF2) / (1 - min(|CF1|, |CF2|))
        // CF1 = 0.8, CF2 = -0.4 -> 0.4 / 0.6 = 0.6667
        $resOpposite = CertaintyFactorService::combine(0.8, -0.4);
        $this->assertEqualsWithDelta(0.6667, $resOpposite, 0.001);
    }

    public function test_user_can_diagnose_laptop_damage(): void
    {
        $user = User::where('role', 'user')->first();

        // G01 (Blue screen dump RAM) memiliki CF_pakar = 1.0 pada K01 (Kerusakan pada RAM)
        // User memilih 1.0 (Sangat Yakin) -> CF_gejala = 1.0 * 1.0 = 1.0 -> 100%
        $response = $this->actingAs($user)->post(route('user.diagnosa.proses'), [
            'gejala' => [
                1 => 1.0,
            ],
        ]);

        $riwayat = RiwayatDiagnosa::where('user_id', $user->id)->latest('id_riwayat')->first();
        $this->assertNotNull($riwayat);
        $response->assertRedirect(route('user.diagnosa.hasil', $riwayat->id_riwayat));

        $this->assertEquals(1, $riwayat->id_kerusakan_utama);
        $this->assertEquals(100.0, (float)$riwayat->persentase);

        // Akses halaman hasil
        $hasilResponse = $this->actingAs($user)->get(route('user.diagnosa.hasil', $riwayat->id_riwayat));
        $hasilResponse->assertStatus(200);
        $hasilResponse->assertSee('Kerusakan pada RAM');
        $hasilResponse->assertSee('100%');
    }

    public function test_user_can_access_gamified_diagnosa_page(): void
    {
        $user = User::where('role', 'user')->first();

        $response = $this->actingAs($user)->get(route('user.diagnosa.index'));
        $response->assertStatus(200);
        $response->assertSee('Kuisioner Diagnosa Interaktif');
        $response->assertSee('Peta Soal');
        $response->assertSee('quizGameView');
        $response->assertSee('CERTILAP_QUIZ_DATA');
    }
}
