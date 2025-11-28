<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadoNoc extends Model
{
    use HasFactory;

    protected $table = 'resultados_noc';

    protected $fillable = [
        'codigo',
        'titulo',
        'definicao',
        'dominio',
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












