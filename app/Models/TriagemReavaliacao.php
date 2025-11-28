<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TriagemReavaliacao extends Model
{
    protected $table = 'triagem_reavaliacoes';

    protected $fillable = [
        'triagem_id',
        'usuario_id',
        'dados_clinicos',
        'observacoes',
    ];

    protected $casts = [
        'dados_clinicos' => 'array',
        'observacoes' => 'array',
    ];

    /**
     * Relacionamento com triagem
     */
    public function triagem(): BelongsTo
    {
        return $this->belongsTo(Triagem::class);
    }

    /**
     * Relacionamento com usuário (enfermeiro que fez a reavaliação)
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}


















