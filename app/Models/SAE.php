<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SAE extends Model
{
    use HasFactory;

    protected $table = 'sae';

    protected $fillable = [
        'paciente_id',
        'triagem_id',
        'usuario_id',
        'dados_clinicos',
        'diagnosticos_enfermagem',
        'intervencoes_enfermagem',
        'evolucao_enfermagem',
        'observacoes_adicionais',
        'coren',
        'data_registro',
    ];

    protected $casts = [
        'dados_clinicos' => 'array',
        'diagnosticos_enfermagem' => 'array',
        'intervencoes_enfermagem' => 'array',
        'data_registro' => 'datetime',
    ];

    /**
     * Relacionamento com paciente
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    /**
     * Relacionamento com triagem
     */
    public function triagem(): BelongsTo
    {
        return $this->belongsTo(Triagem::class);
    }

    /**
     * Relacionamento com usuário (enfermeiro)
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope para buscar SAE por paciente
     */
    public function scopePorPaciente($query, $pacienteId)
    {
        return $query->where('paciente_id', $pacienteId);
    }

    /**
     * Scope para buscar SAE por triagem
     */
    public function scopePorTriagem($query, $triagemId)
    {
        return $query->where('triagem_id', $triagemId);
    }

    /**
     * Scope para ordenar por data mais recente
     */
    public function scopeRecentes($query)
    {
        return $query->orderBy('data_registro', 'desc');
    }
}