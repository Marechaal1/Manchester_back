<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Triagem extends Model
{
    protected $table = 'triagens';
    
    protected $fillable = [
        'paciente_id',
        'usuario_id',
        'protocolo',
        'data_triagem',
        'classificacao_risco',
        'tempo_espera_minutos',
        'dados_clinicos',
        'diagnosticos_enfermagem',
        'intervencoes_enfermagem',
        'avaliacao_seguranca',
        'observacoes',
        'status',
        'data_conclusao',
        'requer_reavaliacao',
        'data_reavaliacao',
        'ultima_reavaliacao',
        'reavaliacoes_count'
    ];

    protected $casts = [
        'data_triagem' => 'datetime',
        'data_conclusao' => 'datetime',
        'data_reavaliacao' => 'datetime',
        'ultima_reavaliacao' => 'datetime',
        'dados_clinicos' => 'array',
        'diagnosticos_enfermagem' => 'array',
        'intervencoes_enfermagem' => 'array',
        'avaliacao_seguranca' => 'array',
        'requer_reavaliacao' => 'boolean'
    ];

    /**
     * Relacionamento com paciente
     */
    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    /**
     * Relacionamento com usuário (enfermeiro que fez a triagem)
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com atendimento médico associado a esta triagem
     */
    public function atendimentoMedico(): HasOne
    {
        return $this->hasOne(AtendimentoMedico::class, 'triagem_id');
    }

    /**
     * Relacionamento com todos os atendimentos médicos do paciente
     */
    public function atendimentos()
    {
        return $this->hasMany(AtendimentoMedico::class, 'triagem_id');
    }

    /**
     * Relacionamento com reavaliações desta triagem
     */
    public function reavaliacoes()
    {
        return $this->hasMany(\App\Models\TriagemReavaliacao::class, 'triagem_id');
    }

    /**
     * Scope para triagens em andamento
     */
    public function scopeEmAndamento($query)
    {
        return $query->where('status', 'EM_ANDAMENTO');
    }

    /**
     * Scope para triagens concluídas
     */
    public function scopeConcluidas($query)
    {
        return $query->where('status', 'CONCLUIDA');
    }

    /**
     * Scope para triagens por classificação de risco
     */
    public function scopePorClassificacao($query, string $classificacao)
    {
        return $query->where('classificacao_risco', $classificacao);
    }
}
