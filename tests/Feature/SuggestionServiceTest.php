<?php
 
namespace Tests\Feature;
 
use App\Models\Salle;
use App\Services\SuggestionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
 
class SuggestionServiceTest extends TestCase
{
    use RefreshDatabase;
 
    public function test_recommande_la_plus_petite_salle_suffisante(): void
    {
        Salle::create(['batiment' => 'A', 'numero' => '1', 'capacite' => 100]);
        $petite = Salle::create(['batiment' => 'A', 'numero' => '2', 'capacite' => 50]);
        Salle::create(['batiment' => 'A', 'numero' => '3', 'capacite' => 20]);
 
        $res = app(SuggestionService::class)->rechercher([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '10:00',
            'heure_fin' => '12:00',
            'effectif' => 45,
        ]);
 
        $this->assertTrue($res['recommandee']->is($petite));
        $this->assertCount(2, $res['salles']);
    }
 
    public function test_aucune_salle_si_effectif_trop_grand(): void
    {
        Salle::create(['batiment' => 'A', 'numero' => '1', 'capacite' => 30]);
 
        $res = app(SuggestionService::class)->rechercher([
            'date' => now()->addDay()->toDateString(),
            'heure_debut' => '10:00',
            'heure_fin' => '12:00',
            'effectif' => 200,
        ]);
 
        $this->assertNull($res['recommandee']);
        $this->assertTrue($res['salles']->isEmpty());
    }
}
