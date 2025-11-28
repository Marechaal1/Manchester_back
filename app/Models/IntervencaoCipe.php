<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntervencaoCipe extends Model
{
    use HasFactory;

    protected $table = 'intervencoes_cipe';

    protected $fillable = [
        'codigo',
        'titulo',
        'definicao',
        'dominio',
        'categoria',
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












