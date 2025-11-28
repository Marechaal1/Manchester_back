<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SistemaParametro extends Model
{
    protected $table = 'sistema_parametros';

    protected $fillable = [
        'categoria_risco',
        'nome_categoria',
        'tempo_reavaliacao_minutos',
        'descricao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
        'tempo_reavaliacao_minutos' => 'integer'
    ];

    /**
     * Buscar parâmetros por categoria de risco
     */
    public static function getByCategoria($categoria)
    {
        return self::where('categoria_risco', $categoria)
                   ->where('ativo', true)
                   ->first();
    }

    /**
     * Buscar todos os parâmetros ativos
     */
    public static function getAtivos()
    {
        return self::where('ativo', true)
                   ->orderBy('tempo_reavaliacao_minutos', 'asc')
                   ->get();
    }

    /**
     * Obter tempo de reavaliação em milissegundos
     */
    public function getTempoReavaliacaoMsAttribute()
    {
        return $this->tempo_reavaliacao_minutos * 60 * 1000;
    }
}
