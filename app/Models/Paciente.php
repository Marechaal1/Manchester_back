<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    protected $fillable = [
        'nome_completo',
        'cpf',
        'data_nascimento',
        'sexo',
        'telefone',
        'email',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'nome_responsavel',
        'telefone_responsavel',
        'observacoes',
        'ativo'
    ];

    protected $casts = [
        'data_nascimento' => 'date',
        'ativo' => 'boolean'
    ];

    /**
     * Relacionamento com triagens
     */
    public function triagens(): HasMany
    {
        return $this->hasMany(Triagem::class);
    }

    /**
     * Scope para pacientes ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('ativo', true);
    }

    /**
     * Accessor para idade
     */
    public function getIdadeAttribute(): int
    {
        if (!$this->data_nascimento) {
            return 0;
        }

        return $this->data_nascimento->age;
    }
}
