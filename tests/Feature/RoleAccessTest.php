<?php
 
namespace Tests\Feature;
 
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
 
class RoleAccessTest extends TestCase
{
    use RefreshDatabase;
 
    public function test_un_invite_est_redirige_vers_la_connexion(): void
    {
        $this->get('/admin')->assertRedirect('/login');
    }
 
    public function test_un_responsable_ne_peut_pas_acceder_a_ladministration(): void
    {
        $user = User::factory()->create(['role' => 'responsable']);
 
        $this->actingAs($user)->get('/admin')->assertForbidden();
    }
 
    public function test_ladmin_ne_peut_pas_acceder_a_lespace_responsable(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
 
        $this->actingAs($admin)->get('/responsable/recherche')->assertForbidden();
    }
}
