<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Mapeamento de casts do Model
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class, // Converte a string do banco no Enum UserRole
        ];
    }

    // Método auxiliar para facilitar checagens rápidas
    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    /**
     * Relacionamento: Turmas vinculadas ao usuário
     */
    public function classrooms(): BelongsToMany
    {
        return $this->belongsToMany(Classroom::class);
    }

    /**
     * Relacionamento: Disciplinas vinculadas ao usuário
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }

    /**
     * Retorna o rótulo da função do usuário em português.
     */
    public function getRoleLabelAttribute(): string
    {
        // Se a role for um Enum que já possui o método label()
        if (is_object($this->role) && method_exists($this->role, 'label')) {
            return $this->role->label();
        }

        $value = is_object($this->role) && isset($this->role->value) ? $this->role->value : $this->role;

        return match (strtolower((string)$value)) {
            'admin'     => 'Administrador',
            'teacher'   => 'Professor',
            'preceptor' => 'Preceptor',
            default     => ucfirst($value ?? 'Acesso Geral'),
        };
    }
}
