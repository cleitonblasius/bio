<?php

use App\Models\AtendimentoModel;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once(__DIR__ . '/../../src/config/Database.php');
require_once(__DIR__ . '/../../src/models/BaseModel.php');
require_once(__DIR__ . '/../../src/models/AtendimentoModel.php');
require_once(__DIR__ . '/../functions.php');

$model = new AtendimentoModel();

$dados = json_decode($_REQUEST['dados'], true);
$idAtendimento = $dados['id_atendimento'] ?? -1;
$rastreios = $dados['rastreios'] ?? [];

if (empty($idAtendimento) || empty($rastreios)) {
    return ['status' => 'error', 'message' => 'Identificador do atendimento ou dados de rastreio não recebidos.'];
}

/* tabela BIO_ATENDIMENTO_RASTREIO
id -- Identificador único do registro
id_atendimento  -- Chave estrangeira para bio_atendimentos(id)
codigo_par -- Chave estrangeira para bio_pares_rastreio(codigo_par)
sintomas -- Sintomas informados no rastreio */

//Pega os dados já salvos
$dadosSalvos = $model->getRatreiosAtendimento();

//Caso o par já exista, atualiza os sintomas
if (!empty($dadosSalvos)) {
    foreach ($dadosSalvos as $codigoPar) {
        //remover os valores updatados do array recebido por request, para fazer insert depois do que sobrar
        unset($rastreios[$codigoPar]);

        //Se o sintoma foi alterado, atualiza
        $sintomas = trim($rastreios[$codigoPar]['sintomas']) ?? "";
        if (empty($sintomas)) {
            continue;
        }

        //pegar os dados do array recebido por request
        $model->updateBioAtendimentoRastreio($idAtendimento, $codigoPar, $sintomas);
    }
}

//Insere um novo registro para os pares que sobraram no array de pares
foreach ($rastreios as $codigoPar => $sintomas) {
    $model->insertBioAtendimentoRastreio($idAtendimento, $codigoPar, $sintomas);
}

return ['status' => 'success', 'message' => $message];