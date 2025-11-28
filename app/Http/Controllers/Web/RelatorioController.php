<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Triagem;
use App\Models\AtendimentoMedico;
use App\Models\Paciente;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class RelatorioController extends Controller
{
    /**
     * Exibe a página de seleção de relatórios
     */
    public function index()
    {
        // Verificar se o usuário tem permissão para extrair relatórios
        $user = auth()->user();
        if (!$user->permite_extrair_relatorios && $user->tipo_usuario !== 'ADMINISTRADOR') {
            abort(403, 'Você não tem permissão para acessar os relatórios.');
        }

        $pacientes = Paciente::orderBy('nome_completo')->get();
        $enfermeiros = User::where('tipo_usuario', 'ENFERMEIRO')
            ->where('ativo', true)
            ->orderBy('nome_completo')
            ->get();
        $medicos = User::where('tipo_usuario', 'MEDICO')
            ->where('ativo', true)
            ->orderBy('nome_completo')
            ->get();
        return view('relatorios.index', compact('pacientes', 'enfermeiros', 'medicos'));
    }

    /**
     * Gera relatório de triagens em Excel
     */
    public function triagens(Request $request)
    {
        // Verificar se o usuário tem permissão para extrair relatórios
        $user = auth()->user();
        if (!$user->permite_extrair_relatorios && $user->tipo_usuario !== 'ADMINISTRADOR') {
            abort(403, 'Você não tem permissão para extrair relatórios.');
        }

        try {
            $validated = $request->validate([
                'tipo_filtro' => 'required|in:periodo,paciente,enfermeiro',
                'data_inicio' => 'required_if:tipo_filtro,periodo|nullable|date',
                'data_fim' => 'required_if:tipo_filtro,periodo|nullable|date|after_or_equal:data_inicio',
                'paciente_id' => 'required_if:tipo_filtro,paciente|nullable|exists:pacientes,id',
                'enfermeiro_id' => 'required_if:tipo_filtro,enfermeiro|nullable|exists:users,id',
            ]);

            $query = Triagem::with(['paciente', 'usuario']);

            if ($validated['tipo_filtro'] === 'periodo') {
                $query->whereBetween('data_triagem', [
                    $validated['data_inicio'] . ' 00:00:00',
                    $validated['data_fim'] . ' 23:59:59'
                ]);
                $titulo = "Relatório de Triagens - {$validated['data_inicio']} a {$validated['data_fim']}";
            } elseif ($validated['tipo_filtro'] === 'paciente') {
                $query->where('paciente_id', $validated['paciente_id']);
                $paciente = Paciente::find($validated['paciente_id']);
                $titulo = "Relatório de Triagens - {$paciente->nome_completo}";
            } else {
                $query->where('usuario_id', $validated['enfermeiro_id']);
                $enfermeiro = User::find($validated['enfermeiro_id']);
                $titulo = "Relatório de Triagens - Enfermeiro: " . ($enfermeiro->nome_completo ?? $enfermeiro->name ?? 'N/A');
            }

            $triagens = $query->orderBy('data_triagem', 'desc')->get();

            return $this->gerarExcelTriagens($triagens, $titulo);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('relatorios.index')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('relatorios.index')
                ->with('error', 'Erro ao gerar relatório: ' . $e->getMessage());
        }
    }

    /**
     * Gera relatório de atendimentos médicos em Excel
     */
    public function atendimentos(Request $request)
    {
        // Verificar se o usuário tem permissão para extrair relatórios
        $user = auth()->user();
        if (!$user->permite_extrair_relatorios && $user->tipo_usuario !== 'ADMINISTRADOR') {
            abort(403, 'Você não tem permissão para extrair relatórios.');
        }

        try {
            $validated = $request->validate([
                'tipo_filtro' => 'required|in:periodo,paciente,medico',
                'data_inicio' => 'required_if:tipo_filtro,periodo|nullable|date',
                'data_fim' => 'required_if:tipo_filtro,periodo|nullable|date|after_or_equal:data_inicio',
                'paciente_id' => 'required_if:tipo_filtro,paciente|nullable|exists:pacientes,id',
                'medico_id' => 'required_if:tipo_filtro,medico|nullable|exists:users,id',
            ]);

            $query = AtendimentoMedico::with(['paciente', 'medico', 'triagem']);

            if ($validated['tipo_filtro'] === 'periodo') {
                $query->whereBetween('created_at', [
                    $validated['data_inicio'] . ' 00:00:00',
                    $validated['data_fim'] . ' 23:59:59'
                ]);
                $titulo = "Relatório de Atendimentos Médicos - {$validated['data_inicio']} a {$validated['data_fim']}";
            } elseif ($validated['tipo_filtro'] === 'paciente') {
                $query->where('paciente_id', $validated['paciente_id']);
                $paciente = Paciente::find($validated['paciente_id']);
                $titulo = "Relatório de Atendimentos Médicos - {$paciente->nome_completo}";
            } else {
                $query->where('medico_id', $validated['medico_id']);
                $medico = User::find($validated['medico_id']);
                $titulo = "Relatório de Atendimentos Médicos - Médico: " . ($medico->nome_completo ?? $medico->name ?? 'N/A');
            }

            $atendimentos = $query->orderBy('created_at', 'desc')->get();

            return $this->gerarExcelAtendimentos($atendimentos, $titulo);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('relatorios.index')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('relatorios.index')
                ->with('error', 'Erro ao gerar relatório: ' . $e->getMessage());
        }
    }

    /**
     * Gera arquivo Excel para triagens
     */
    private function gerarExcelTriagens($triagens, $titulo)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Triagens');

        // Cabeçalho
        $sheet->setCellValue('A1', $titulo);
        $sheet->mergeCells('A1:J1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Cabeçalhos da tabela
        $headers = [
            'ID', 'Data/Hora', 'Paciente', 'CPF', 'Classificação', 
            'Status', 'Tempo Espera (min)', 'Enfermeiro', 'Protocolo', 'Observações'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '3', $header);
            $sheet->getStyle($col . '3')->getFont()->setBold(true);
            $sheet->getStyle($col . '3')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('4472C4');
            $sheet->getStyle($col . '3')->getFont()->getColor()->setRGB('FFFFFF');
            $sheet->getStyle($col . '3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col++;
        }

        // Dados
        $row = 4;
        foreach ($triagens as $triagem) {
            $descricaoRisco = [
                'VERMELHO' => 'Emergência',
                'LARANJA' => 'Muito Urgente',
                'AMARELO' => 'Urgente',
                'VERDE' => 'Pouco Urgente',
                'AZUL' => 'Não Urgente',
            ];

            $sheet->setCellValue('A' . $row, $triagem->id);
            $sheet->setCellValue('B' . $row, $triagem->data_triagem ? $triagem->data_triagem->format('d/m/Y H:i') : '-');
            $sheet->setCellValue('C' . $row, $triagem->paciente->nome_completo ?? '-');
            $sheet->setCellValue('D' . $row, $triagem->paciente->cpf ?? '-');
            $sheet->setCellValue('E' . $row, $descricaoRisco[$triagem->classificacao_risco] ?? $triagem->classificacao_risco ?? '-');
            $sheet->setCellValue('F' . $row, $triagem->status ?? '-');
            $sheet->setCellValue('G' . $row, $triagem->tempo_espera_minutos ?? '-');
            $sheet->setCellValue('H' . $row, $triagem->usuario->nome_completo ?? $triagem->usuario->name ?? '-');
            $sheet->setCellValue('I' . $row, $triagem->protocolo ?? '-');
            $sheet->setCellValue('J' . $row, $triagem->observacoes ?? '-');
            $row++;
        }

        // Ajustar largura das colunas
        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Bordas
        $sheet->getStyle('A3:J' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $filename = 'relatorio_triagens_' . date('Y-m-d_His') . '.xlsx';
        
        // Criar arquivo temporário
        $tempFile = tempnam(sys_get_temp_dir(), 'relatorio_triagens_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);
        
        // Retornar download
        return Response::download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Gera arquivo Excel para atendimentos médicos
     */
    private function gerarExcelAtendimentos($atendimentos, $titulo)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Atendimentos Médicos');

        // Cabeçalho
        $sheet->setCellValue('A1', $titulo);
        $sheet->mergeCells('A1:H1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Cabeçalhos da tabela
        $headers = [
            'ID', 'Data/Hora', 'Paciente', 'CPF', 'Médico', 
            'Status', 'Início Atendimento', 'Fim Atendimento'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '3', $header);
            $sheet->getStyle($col . '3')->getFont()->setBold(true);
            $sheet->getStyle($col . '3')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setRGB('4472C4');
            $sheet->getStyle($col . '3')->getFont()->getColor()->setRGB('FFFFFF');
            $sheet->getStyle($col . '3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $col++;
        }

        // Dados
        $row = 4;
        foreach ($atendimentos as $atendimento) {
            $sheet->setCellValue('A' . $row, $atendimento->id);
            $sheet->setCellValue('B' . $row, $atendimento->created_at ? $atendimento->created_at->format('d/m/Y H:i') : '-');
            $sheet->setCellValue('C' . $row, $atendimento->paciente->nome_completo ?? '-');
            $sheet->setCellValue('D' . $row, $atendimento->paciente->cpf ?? '-');
            $sheet->setCellValue('E' . $row, $atendimento->medico->nome_completo ?? $atendimento->medico->name ?? '-');
            $sheet->setCellValue('F' . $row, $atendimento->status ?? '-');
            $sheet->setCellValue('G' . $row, $atendimento->inicio_atendimento ? $atendimento->inicio_atendimento->format('d/m/Y H:i') : '-');
            $sheet->setCellValue('H' . $row, $atendimento->fim_atendimento ? $atendimento->fim_atendimento->format('d/m/Y H:i') : '-');
            $row++;
        }

        // Ajustar largura das colunas
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Bordas
        $sheet->getStyle('A3:H' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000'],
                ],
            ],
        ]);

        $filename = 'relatorio_atendimentos_' . date('Y-m-d_His') . '.xlsx';
        
        // Criar arquivo temporário
        $tempFile = tempnam(sys_get_temp_dir(), 'relatorio_atendimentos_') . '.xlsx';
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);
        
        // Retornar download
        return Response::download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }
}

