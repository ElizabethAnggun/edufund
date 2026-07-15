<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\StudentProfile;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);
        $this->seed(RoleSeeder::class);
    }

    public function test_guest_auth_pages_render_successfully(): void
    {
        $this->get(route('login'))->assertOk()->assertSee('Login');
        $this->get(route('register'))->assertOk()->assertSee('Register');
        $this->get(route('password.request'))->assertOk()->assertSee('Forgot Password');
        $this->get(route('password.reset', ['token' => 'test-token']))->assertOk()->assertSee('Reset Password');
    }

    public function test_root_redirects_guest_to_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_user_can_register_and_is_redirected_to_login(): void
    {
        $response = $this->post(route('register.post'), [
            'name' => 'Test Donor',
            'email' => 'newdonor@edufund.test',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => UserRole::DONOR->value,
        ]);

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'newdonor@edufund.test',
        ]);

        $user = User::where('email', 'newdonor@edufund.test')->first();
        $this->assertTrue($user->hasRole(UserRole::DONOR->value));
    }

    public function test_admin_can_login_and_is_redirected_to_admin_dashboard(): void
    {
        $user = $this->createUserWithRole(UserRole::ADMIN);

        $response = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_student_can_login_and_is_redirected_to_student_dashboard(): void
    {
        $user = $this->createUserWithRole(UserRole::STUDENT);

        $response = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('student.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_school_can_login_and_is_redirected_to_school_dashboard(): void
    {
        $user = $this->createUserWithRole(UserRole::SCHOOL);

        $response = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('school.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_donor_can_login_and_is_redirected_to_donor_dashboard(): void
    {
        $user = $this->createUserWithRole(UserRole::DONOR);

        $response = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect(route('donor.dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    public function test_authenticated_user_can_logout(): void
    {
        $user = $this->createUserWithRole(UserRole::ADMIN);

        $this->actingAs($user)
            ->post(route('logout'))
            ->assertRedirect(route('login'));

        $this->assertGuest();
    }

    public function test_role_dashboards_require_authentication(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->get(route('student.dashboard'))->assertRedirect(route('login'));
        $this->get(route('school.dashboard'))->assertRedirect(route('login'));
        $this->get(route('donor.dashboard'))->assertRedirect(route('login'));
    }

    public function test_role_middleware_blocks_cross_role_access(): void
    {
        $student = $this->createUserWithRole(UserRole::STUDENT);

        $this->actingAs($student)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }

    public function test_role_dashboards_render_for_authenticated_users(): void
    {
        $this->actingAs($this->createUserWithRole(UserRole::ADMIN))
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Admin Dashboard');

        $this->actingAs($this->createUserWithRole(UserRole::STUDENT))
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Student Dashboard');

        $this->actingAs($this->createUserWithRole(UserRole::SCHOOL))
            ->get(route('school.dashboard'))
            ->assertOk()
            ->assertSee('School Dashboard');

        $this->actingAs($this->createUserWithRole(UserRole::DONOR))
            ->get(route('donor.dashboard'))
            ->assertOk()
            ->assertSee('Donor Dashboard');
    }

    public function test_student_dashboard_renders_when_student_profile_is_missing(): void
    {
        $student = $this->createUserWithRole(UserRole::STUDENT);

        $this->actingAs($student)
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Student Dashboard')
            ->assertSee('Student Profile Not Complete');
    }

    public function test_student_dashboard_renders_when_student_profile_exists(): void
    {
        $student = $this->createUserWithRole(UserRole::STUDENT);

        StudentProfile::create([
            'user_id' => $student->id,
            'school_id' => null,
            'nisn' => 'NISN-0001',
            'nim' => 'NIM-0001',
            'education_level' => 's1',
            'major' => 'Computer Science',
            'semester' => 4,
            'gpa' => 3.75,
            'date_of_birth' => '2004-01-01',
            'phone' => '08123456789',
            'address' => 'Jl. Testing No. 1',
            'bio' => 'Student dashboard test profile.',
            'student_id_card' => 'card-001.png',
        ]);

        $this->actingAs($student)
            ->get(route('student.dashboard'))
            ->assertOk()
            ->assertSee('Student Dashboard')
            ->assertSee('Welcome back');
    }

    public function test_login_fails_for_user_without_role(): void
    {
        $user = User::factory()->create([
            'email' => 'norole@edufund.test',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post(route('login.post'), [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    private function createUserWithRole(UserRole $role): User
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $user->assignRole($role->value);

        return $user;
    }
}
