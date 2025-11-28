<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AtendimentoMedico extends Model
{
    protected $table = 'atendimentos_medicos';

    protected $fillable = [
        'paciente_id',
        'triagem_id',
        'medico_id',
        'historico_medico',
        'exame_fisico',
        'diagnosticos',
        'exames_solicitados',
        'prescricoes',
        'conduta',
        'status',
        'inicio_atendimento',
        'fim_atendimento',
        'inicio_observacao',
        'fim_observacao',
    ];

    protected $casts = [
        'inicio_atendimento' => 'datetime',
        'fim_atendimento' => 'datetime',
        'inicio_observacao' => 'datetime',
        'fim_observacao' => 'datetime',
        'exame_fisico' => 'array',
        'diagnosticos' => 'array',
        'exames_solicitados' => 'array',
        'prescricoes' => 'array',
        'conduta' => 'array',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function triagem(): BelongsTo
    {
        return $this->belongsTo(Triagem::class);
    }

    public function medico(): BelongsTo
    {
        return $this->belongsTo(User::class, 'medico_id');
    }

    /**
     * Verificar se o atendimento está ativo (não finalizado)
     */
    public function isAtivo(): bool
    {
        return $this->status !== 'FINALIZADO';
    }

    /**
     * Verificar se está em observação ativa
     */
    public function isObservacaoAtiva(): bool
    {
        return $this->status === 'OBSERVACAO' && !$this->fim_observacao;
    }

    /**
     * Scope para atendimentos ativos
     */
    public function scopeAtivos($query)
    {
        return $query->where('status', '!=', 'FINALIZADO');
    }

    /**
     * Scope para atendimentos em observação ativa
     */
    public function scopeObservacaoAtiva($query)
    {
        return $query->where('status', 'OBSERVACAO')
                    ->whereNull('fim_observacao');
    }
}


