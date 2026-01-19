<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.php';

use App\Controllers\PacienteController;

$pacientesController = new PacienteController();
$dados = $pacientesController->getAllBasicData();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha do Cliente</title>
    <link rel="stylesheet" href="../vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/fontawesome-free-6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../vendor/DataTables/css/datatables.min.css">
    <link href="../assets/css/global.css" rel="stylesheet">
</head>

<body>
    <div class="mt-3" style="width: 98%; padding-left: 20px;">
        <div>
            <button class="btn btn-primary btn-sm" onclick="criarPaciente()">
                <i class="fas fa-plus"></i> Novo paciente
            </button>
        </div>
        <table id="tabelaPacientes" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>Idade</th>
                    <th>Telefone</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Renderizar as linhas da tabela
                foreach ($dados as $linha) {
                    $masculino = '<i class="fa-solid fa-mars" style="color: dodgerblue;" title="Masculino"></i>';
                    $feminino = '<i class="fa-solid fa-venus" style="color: magenta;" title="Feminino"></i>';
                    $genero = $linha['GENERO'] == 2 ? $feminino : $masculino;
                    $idade = $linha['IDADE'] ?? '<i style="color: gray;">Não informada</i>';
                    $telefone = !empty($linha['TELEFONE']) ? formatarTelefone($linha['TELEFONE']) : '<i style="color: gray;">Não informado</i>';
                    $gestante = $linha['GESTANTE'] == 1 ? '<i class="fa-solid fa-check"></i>' : '';
                    $editar = '<button class="btn btn-outline-primary btn-sm" onclick="editarPaciente(' . $linha['ID'] . ', \'' . $linha['NOME'] . '\')" data-bs-toggle="tooltip" data-bs-title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>';
                    $excluir = '<button class="btn btn-outline-danger btn-sm" onclick="excluirPaciente(' . $linha['ID'] . ', \'' . $linha['NOME'] . '\')" data-bs-toggle="tooltip" data-bs-title="Excluir">
                        <i class="fas fa-trash-alt"></i>
                    </button>';

                    echo "
                    <tr>
                        <td>{$genero} {$linha['NOME']}</td>
                        <td>{$idade}</td>
                        <td>{$telefone}</td>
                        <td style='text-align: center;'>{$editar} {$excluir}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalPaciente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalPacienteLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header" style="padding: 5px;">
                    <h1 class="modal-title fs-5" id="modalPacienteLabel"><i class="fa-solid fa-hospital-user"></i>Criar Paciente</h1>
                </div>
                <div class="modal-body" style="padding: 0px;">
                    <iframe id="iframe_paciente_data" src="./paciente_data.php?idPaciente=-1" style="width: 100%; height: 99%; border: none;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" onclick="salvarDadosPaciente()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery-3.7.1.min.js"></script>
    <script src="../vendor/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/DataTables/js/datatables.min.js"></script>
    <script src="../assets/js/functions.js"></script>
    <script>
        $(document).ready(function() {
            $('#tabelaPacientes').DataTable({
                language: {
                    url: "../vendor/DataTables/js/pt-BR.json"
                }
            });
            
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        function criarPaciente() {
            $('#modalPacienteLabel').text('Criar Paciente');
            $('#iframe_paciente_data').prop('src', `./paciente_data.php?action=1&id_paciente=-1`);

            const modalPaciente = new bootstrap.Modal(document.getElementById('modalPaciente'));
            modalPaciente.show();
        }

        function editarPaciente(idPaciente, nomePaciente) {
            $('#modalPacienteLabel').text(`Editando Paciente: ${nomePaciente}`);
            $('#iframe_paciente_data').prop('src', `./paciente_data.php?action=2&id_paciente=${idPaciente}`)

            const modalPaciente = new bootstrap.Modal(document.getElementById('modalPaciente'));
            modalPaciente.show();
        }

        function excluirPaciente(id, nomePaciente) {
            if (!confirm(`Deseja realmente excluir o paciente ${nomePaciente}?`)) {
                return;
            }

            $.ajax({
                url: './paciente_action.php',
                type: 'POST',
                data: {
                    action: 3,
                    id_paciente: id
                },
                success: function(response) {
                    //Exibe mensagem de sucesso
                    top.toastr.success(response.message);

                    //Recarrega a frame de pacientes
                    let iframeRaizPacientes = parent.document.getElementById('iframe_pacientes');
                    if (iframeRaizPacientes != undefined) {
                        iframeRaizPacientes.contentWindow.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    let response = JSON.parse(xhr.responseText);
                    top.toastr.error(response.message || "Erro ao excluir registro do banco!");
                }
            });
        }

        function salvarDadosPaciente() {
            // Obtém o formulário dentro do iframe
            let form = document.getElementById('iframe_paciente_data').contentWindow.document.getElementById('form_paciente');

            // Valida o preenchimento dos campos requeridos (Nome e CPF)
            if (!validaPreenchimentoRequerido(form)) {
                return;
            }

            // Valida se os exames complementares foram preenchidos corretamente
            if (!validaPreenchimentoExamesComplementares(form)) {
                top.toastr.error("Favor inserir a descrição do exame complementar ou remover o mesmo.");
                document.getElementById('iframe_paciente_data').contentWindow.document.getElementById('nav-exames-complementares-tab').click();
                return;
            }

            // Cria um objeto FormData para capturar todos os campos, incluindo arquivos
            let formData = new FormData(form);

            // Envia os dados via AJAX com FormData
            $.ajax({
                url: './paciente_action.php',
                type: 'POST',
                data: formData,
                processData: false, // Impede o jQuery de processar os dados (obrigatório para arquivos)
                contentType: false, // Impede o jQuery de definir um contentType incorreto
                success: function(response) {
                    // Exibe mensagem de sucesso
                    top.toastr.success(response.message);

                    // Recarrega o iframe de pacientes
                    let iframeRaizPacientes = parent.document.getElementById('iframe_pacientes');
                    if (iframeRaizPacientes != undefined) {
                        iframeRaizPacientes.contentWindow.location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    let response = JSON.parse(xhr.responseText);
                    top.toastr.error(response.message || "Erro ao gravar dados no banco!");
                }
            });
        }

        //Verifica se os campos requeridos foram preenchidos
        function validaPreenchimentoRequerido(form) {
            //Verifica se os campos especificos são validos
            let campos = ["nome"];
            let isValid = true;

            campos.forEach(function(campoId) {
                let campo = form[campoId];

                // Verifica se o campo é válido
                if (!campo.checkValidity()) {
                    campo.classList.add("is-invalid");
                    isValid = false;
                } else {
                    campo.classList.remove("is-invalid");
                    campo.classList.add("is-valid");
                }
            });

            return isValid;
        }

        //Verifica se os campos de exames complementares foram preenchidos
        function validaPreenchimentoExamesComplementares(form) {
            let isValid = true;
            let textAreas = form.querySelectorAll('textarea[name^="novo_exame_comp"]');

            Object.entries(textAreas).forEach((elm, ind) => {
                if (elm[1].value == '') {
                    isValid = false;
                }
            });

            return isValid;
        }
    </script>
</body>

</html>