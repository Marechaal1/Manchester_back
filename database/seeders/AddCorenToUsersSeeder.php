<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AddCorenToUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Adicionar COREN para usuários enfermeiros existentes
        $enfermeiros = User::whereIn('tipo_usuario', ['ENFERMEIRO', 'TECNICO_ENFERMAGEM'])
            ->whereNull('coren')
            ->get();

        foreach ($enfermeiros as $enfermeiro) {
            // Gerar um COREN fictício baseado no ID
            $coren = 'COREN-' . str_pad($enfermeiro->id, 6, '0', STR_PAD_LEFT);
            $enfermeiro->update(['coren' => $coren]);
        }

    }
}




