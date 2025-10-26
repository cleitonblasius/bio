<?php
// Permite requisições de qualquer origem (CORS)
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json");

require_once __DIR__ . '/../../vendor/autoload.php';

$pacientesController = new App\Controllers\PacienteController();

$rota = $_REQUEST['rota'] ?? null;

switch ($rota) {
    case "excluir_exame_complementar":
        $exames = $pacientesController->excluirExameComplementar($_REQUEST['id_exame'] ?? 0);
        echo json_encode($exames);
        die();
        break;
    case 'obter_dados_paciente':
        $dadosPaciente = $pacientesController->getDadosPacienteByID($_REQUEST['id_paciente']);
        //$historico = $pacientesController->getHistoricoSaude($_REQUEST['id_paciente']);
        //echo json_encode([ 'PACIENTE' => $dadosPaciente, 'HISTORICO' => $historico, ]);
        echo json_encode($dadosPaciente);
        die();
        break;
    case 'obter_atendimentos_paciente':
        $dadosPaciente = $pacientesController->getAtendimentosPacienteByID($_REQUEST['id_paciente']);
        echo json_encode($dadosPaciente);
        die();
        break;

    default:
        echo json_encode(["error" => "Rota inválida"]);
}
