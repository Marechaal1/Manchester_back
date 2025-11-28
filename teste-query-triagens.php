<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Triagem;
use App\Models\AtendimentoMedico;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "🧪 Testando query de triagens...\n\n";

// Testar a query exata do controller
$query = Triagem::with(['paciente', 'usuario']);

// Aplicar os mesmos filtros do controller
$query->where(function($q) {
    $q->where('status', '!=', 'CONCLUIDA')
      ->orWhereNull('status');
});

$query->whereDoesntHave('atendimentos', function($subQuery) {
    $subQuery->where('status', 'FINALIZADO');
});

$triagens = $query->orderBy('data_triagem', 'desc')->get();

echo "📊 Resultado da query:\n";
echo "- Total de triagens retornadas: " . $triagens->count() . "\n\n";

foreach ($triagens as $triagem) {
    echo "- ID: {$triagem->id}, Status: {$triagem->status}, Paciente: {$triagem->paciente_id}\n";
    
    // Verificar atendimentos associados
    $atendimentos = $triagem->atendimentos;
    if ($atendimentos->count() > 0) {
        echo "  Atendimentos:\n";
        foreach ($atendimentos as $atendimento) {
            echo "    - ID: {$atendimento->id}, Status: {$atendimento->status}\n";
        }
    }
    echo "\n";
}

// Testar query alternativa
echo "🔍 Testando query alternativa (sem filtro de atendimentos):\n";
$query2 = Triagem::with(['paciente', 'usuario']);
$query2->where(function($q) {
    $q->where('status', '!=', 'CONCLUIDA')
      ->orWhereNull('status');
});

$triagens2 = $query2->orderBy('data_triagem', 'desc')->get();
echo "- Total de triagens (sem filtro atendimentos): " . $triagens2->count() . "\n\n";

echo "✅ Teste concluído!\n";


