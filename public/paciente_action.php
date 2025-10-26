<?php

use App\Models\PacienteModel;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once(__DIR__ . '/../src/config/Database.php');
require_once(__DIR__ . '/../src/models/BaseModel.php');
require_once(__DIR__ . '/../src/models/PacienteModel.php');
require_once('./functions.php');

$model = new PacienteModel();

if (empty($_REQUEST['action'])) {
    showResponseAndDie('error', 'Ação não recebida.');
}

$idPaciente = !empty($_REQUEST['id_paciente']) ? (int) $_REQUEST['id_paciente'] : -1;

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

//Criar paciente
function createRecord()
{
    global $model;

    $nome = $_REQUEST['nome'] ?? null;
    $nextID = -1;

    // Valida os dados obrigatórios
    if (!$nome) {
        showResponseAndDie('error', 'O mome do paciente é obrigatório!');
    }

    try {
        //Insere na tabela principal
        $nextID = $model->insertBioPacientes($_REQUEST);

        //Se o registro foi criado, insere nas demais tabelas
        if ($nextID > 0) {
            $model->insertBioHistoricoSaude($_REQUEST, $nextID);
            $model->insertBioHistoriaSocial($_REQUEST, $nextID);
            $model->insertBioHistoriaFisiologica($_REQUEST, $nextID);
            $model->insertBioHistoriaDoenca($_REQUEST, $nextID);
            $model->insertBioEstruturaFamiliar($_REQUEST, $nextID);
            $model->insertBioDoencasFamilia($_REQUEST, $nextID);
            salvarAnexoExame($nextID);
        }

        showResponseAndDie('success', 'Dados salvos com sucesso.', ['id' => $nextID]);
    } catch (PDOException $e) {
        showResponseAndDie('error', 'Erro ao salvar os dados.', [], $e->getMessage());
    }
}

//Editar paciente
function editRecord()
{
    global $model, $idPaciente;

    if ($idPaciente > 0) {
        $model->updateBioPacientes($_REQUEST);
        $model->updateBioHistoricoSaude($_REQUEST);
        $model->updateBioHistoriaSocial($_REQUEST);
        $model->updateBioHistoriaFisiologica($_REQUEST);
        $model->updateBioHistoriaDoenca($_REQUEST);
        $model->updateBioEstruturaFamiliar($_REQUEST);
        $model->updateBioDoencasFamilia($_REQUEST);
        salvarAnexoExame($idPaciente);

        showResponseAndDie('success', 'Dados atualizados com sucesso.');
    } else {
        showResponseAndDie('error', 'Identificador do paciente não recebido. Impossível editar.');
    }
}

//Excluir paciente
function deleteRecord()
{
    global $model, $idPaciente;

    if ($idPaciente > 0) {
        $model->deleteBioPacientes($idPaciente);
        $model->deleteBioHistoricoSaude($idPaciente);
        $model->deleteBioHistoriaSocial($idPaciente);
        $model->deleteBioHistoriaFisiologica($idPaciente);
        $model->deleteBioHistoriaDoenca($idPaciente);
        $model->deleteBioEstruturaFamiliar($idPaciente);
        $model->deleteBioDoencasFamilia($idPaciente);

        showResponseAndDie('success', 'Registro excluído com sucesso.');
    } else {
        showResponseAndDie('error', 'Identificador do paciente não recebido. Impossível excluir.');
    }
}

function salvarAnexoExame(int $idPaciente)
{
    global $model, $idPaciente;

    if (!empty($_REQUEST['novo_exame_comp'])) {
        // Pasta onde as imagens serão salvas
        $diretorio_upload = "uploads/";
        $exames = $_REQUEST['novo_exame_comp'];

        foreach ($exames as $key => $descricao) {
            $arquivo = $_FILES['novo_exame_comp_file_' . $key];

            //Ignora se não tiver texto nem arquivo
            if ($descricao == "" && $arquivo['name'] == "") {
                continue;
            }

            // Criar pasta se não existir
            if (!is_dir($diretorio_upload)) {
                mkdir($diretorio_upload, 0777, true);
            }

            // Verifica se o arquivo foi enviado
            if (!empty($arquivo) && $arquivo['error'] == 0) {
                $nome_arquivo = basename($arquivo['name']);
                $caminho_arquivo = $diretorio_upload . uniqid() . "_" . $nome_arquivo;

                // Move o arquivo para a pasta de uploads
                if (move_uploaded_file($arquivo['tmp_name'], $caminho_arquivo)) {
                    // Insere no banco de dados
                    $model->inserirNovoAnexoExame($idPaciente, $descricao, $caminho_arquivo);
                }
            }
        }
    }
}
