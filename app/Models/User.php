<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nome_completo',
        'sobrenome',
        'cpf',
        'data_nascimento',
        'sexo',
        'estado_civil',
        'telefone',
        'celular',
        'cep',
        'endereco',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'crm',
        'coren',
        'tipo_usuario',
        'ativo',
        'ultimo_acesso',
        'permite_gerenciar_usuarios',
        'permite_gerenciar_pacientes',
        'permite_liberar_observacao',
        'permite_extrair_relatorios'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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
            'data_nascimento' => 'date',
            'ativo' => 'boolean',
            'ultimo_acesso' => 'datetime',
            'permite_gerenciar_usuarios' => 'boolean',
            'permite_gerenciar_pacientes' => 'boolean',
            'permite_liberar_observacao' => 'boolean',
            'permite_extrair_relatorios' => 'boolean'
        ];
    }

    /**
     * Relacionamento com triagens
     */
    public function triagens()
    {
        return $this->hasMany(Triagem::class);
    }

    /**
     * Relacionamento muitos para muitos com perfis
     */
    public function perfis()
    {
        return $this->belongsToMany(Perfil::class, 'usuario_perfil', 'usuario_id', 'perfil_id')
                    ->withPivot(['data_atribuicao', 'data_remocao', 'ativo'])
                    ->withTimestamps();
    }

    /**
     * Scope para usuários ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

}
