<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\Triagem;
use App\Models\AtendimentoMedico;
use App\Models\Paciente;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Testando liberação de paciente...\n\n";

// Verificar se há dados
echo "Verificando dados no banco:\n";
echo "- Triagens: " . Triagem::count() . "\n";
echo "- Atendimentos: " . AtendimentoMedico::count() . "\n";
echo "- Pacientes: " . Paciente::count() . "\n\n";

// Verificar triagens com status CONCLUIDA
echo "Triagens com status CONCLUIDA:\n";
$triagensConcluidas = Triagem::where('status', 'CONCLUIDA')->get();
foreach ($triagensConcluidas as $triagem) {
    echo "- ID: {$triagem->id}, Status: {$triagem->status}, Paciente: {$triagem->paciente_id}\n";
}

echo "\nAtendimentos finalizados:\n";
$atendimentosFinalizados = AtendimentoMedico::where('status', 'FINALIZADO')->get();
foreach ($atendimentosFinalizados as $atendimento) {
    echo "- ID: {$atendimento->id}, Status: {$atendimento->status}, Triagem: {$atendimento->triagem_id}\n";
}

echo "\nTeste concluído!\n";


