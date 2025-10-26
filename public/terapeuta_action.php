<?php

use App\Models\TerapeutaModel;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once(__DIR__ . '/../src/config/Database.php');
require_once(__DIR__ . '/../src/models/BaseModel.php');
require_once(__DIR__ . '/../src/models/TerapeutaModel.php');
require_once('./functions.php');

$model = new TerapeutaModel();

if (empty($_REQUEST['action'])) {
    showResponseAndDie('error', 'Ação não recebida.');
}

$idTerapeuta = !empty($_REQUEST['id_terapeuta']) ? $_REQUEST['id_terapeuta'] : -1;

switch ((int) $_REQUEST['action']) {
    case 1:
        createRecord();
        break;
    case 2:
        editRecord();
        break;
    case 3:
        deleteRecord();
        break;
    default:
        showResponseAndDie('error', "Ação [{$_REQUEST['action']}] não cadastrada.");
        break;
}

//Criar terapeuta
function createRecord()
{
    global $model;

    $nome = $_REQUEST['nome'] ?? null;
    $nextID = -1;

    // Valida os dados obrigatórios
    if (!$nome) {
        showResponseAndDie('error', 'O campo "nome" é obrigatório.');
    }

    try {
        //Insere na tabela principal
        $nextID = $model->insertBioTerapeuta($_REQUEST);

        showResponseAndDie('success', 'Dados salvos com sucesso.', ['id' => $nextID]);
    } catch (PDOException $e) {
        showResponseAndDie('error', 'Erro ao salvar os dados.', [], $e->getMessage());
    }
}

//Editar terapeuta
function editRecord()
{
    global $model, $idTerapeuta;

    if ($idTerapeuta > 0) {
        $model->updateBioTerapeuta($_REQUEST);
        
        showResponseAndDie('success', 'Dados atualizados com sucesso.');
    } else {
        showResponseAndDie('error', 'Identificador do analista não recebido. Impossível editar.');
    }
}

//Excluir terapeuta
/* function deleteRecord()
{
    global $model, $idTerapeuta;

    if ($idTerapeuta > 0) {
        $model->deleteBioTerapeuta($idTerapeuta);

        showResponseAndDie('success', 'Registro excluído com sucesso.');
    } else {
        showResponseAndDie('error', 'Identificador do paciente não recebido. Impossível excluir.');
    }
} */

// function showResponseAndDie(string $status, string $message, array $data = [], string $details = '')
// {
//     echo json_encode([
//         'status' => $status,
//         'message' => $message,
//         'data' => $data,
//         'details' => $details
//     ]);
//     http_response_code($status == 'error' ? 500 : 200);
//     die();
// }
