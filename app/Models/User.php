<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'status'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user role is authorized to view a specific clinical tab.
     */
    public function canAccessTab(string $tab): bool
    {
        $role = trim($this->role ?? '');

        // Super Admin / Medical Director has access to all modules
        if ($role === 'Super Admin / Medical Director' || str_contains(strtolower($role), 'admin') || str_contains(strtolower($role), 'director')) {
            return true;
        }

        $permissions = [
            'Senior Reproductive Endocrinologist' => [
                'overview', 'leads', 'couples', 'cycles', 'transfers', 'failures', 'cryobank'
            ],
            'Chief Embryologist' => [
                'overview', 'cycles', 'laboratory', 'transfers', 'failures', 'cryobank'
            ],
            'Consultant Obstetrician' => [
                'overview', 'couples', 'antenatal', 'deliveries'
            ],
            'Consultant Pediatrician' => [
                'overview', 'deliveries', 'pediatrics'
            ],
            'Senior Clinical Nurse' => [
                'overview', 'cycles', 'antenatal', 'pediatrics'
            ],
            'Clinic Receptionist / CRM' => [
                'overview', 'leads', 'couples'
            ],
        ];

        $allowedTabs = $permissions[$role] ?? ['overview'];

        return in_array($tab, $allowedTabs, true);
    }
}
