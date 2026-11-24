<?php
 
namespace Tests\Feature;
 
use App\Exceptions\AnnulationRefuseeException;
use App\Exceptions\SalleIndisponibleException;
use App\Models\Reservation;
use App\Models\Salle;
use App\Models\User;
use App\Services\ReservationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
 
class ReservationServiceTest extends TestCase
{
    use RefreshDatabase;
 
    private function salle(int $capacite = 40): Salle
    {
        return Salle::create([
            'batiment' => 'A', 'numero' => '101', 'capacite' => $capacite,
        ]);
    }
 
    private function reserver(User $user, Salle $salle,
                              string $debut, string $fin): Reservation
    {
        return app(ReservationService::class)->reserver($user, [
            'salle_id' => $salle->id,
            'date' => now()->addDays(3)->toDateString(),
            'heure_debut' => $debut,
            'heure_fin' => $fin,
            'effectif' => 30,
        ]);
    }
 
    public function test_reservation_confirmee_sur_salle_libre(): void
    {
        $r = $this->reserver(User::factory()->create(), $this->salle(),
                             '10:00', '12:00');
 
        $this->assertSame(Reservation::CONFIRMEE, $r->statut);
    }
 
    public function test_conflit_sur_creneau_qui_chevauche(): void
    {
        $salle = $this->salle();
        $this->reserver(User::factory()->create(), $salle, '10:00', '12:00');
 
        $this->expectException(SalleIndisponibleException::class);
        $this->reserver(User::factory()->create(), $salle, '11:00', '13:00');
    }
 
    public function test_creneaux_adjacents_acceptes(): void
    {
        $salle = $this->salle();
        $this->reserver(User::factory()->create(), $salle, '10:00', '12:00');
        $r = $this->reserver(User::factory()->create(), $salle, '12:00', '14:00');
 
        $this->assertSame(Reservation::CONFIRMEE, $r->statut);
    }
 
    public function test_capacite_insuffisante_refusee(): void
    {
        $this->expectException(SalleIndisponibleException::class);
        $this->reserver(User::factory()->create(), $this->salle(20),
                        '10:00', '12:00');
    }
 
    public function test_annulation_refusee_hors_delai(): void
    {
        config(['salleo.delai_annulation_heures' => 10000]);
        $r = $this->reserver(User::factory()->create(), $this->salle(),
                             '10:00', '12:00');
 
        $this->expectException(AnnulationRefuseeException::class);
        app(ReservationService::class)->annuler($r);
    }
 
    public function test_annulation_dans_le_delai_libere_la_salle(): void
    {
        config(['salleo.delai_annulation_heures' => 0]);
        $salle = $this->salle();
        $r = $this->reserver(User::factory()->create(), $salle, '10:00', '12:00');
 
        app(ReservationService::class)->annuler($r);
 
        $this->assertSame(Reservation::ANNULEE, $r->fresh()->statut);
        $this->assertTrue($salle->estDisponible(
            now()->addDays(3)->toDateString(), '10:00:00', '12:00:00'
        ));
    }
}
