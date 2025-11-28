<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateEvolucao extends Model
{
    use HasFactory;

    protected $table = 'evolucao_templates';

    protected $fillable = [
        'titulo',
        'conteudo',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function scopeAtivos($query)
    {
        return $query->where('ativo', true)->orderBy('titulo');
    }
}












