<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.php';

use App\Controllers\AtendimentoController;
use App\Controllers\PacienteController;

$pacientesController = new PacienteController();
$dados = $pacientesController->getAllBasicData();

$atendimentoController = new AtendimentoController();
$listaAtendimentos = $atendimentoController->getAllDataAtendimento();

$hintHistoriaDoenca = "<ul>
                        <li>Qual o problema?</li>
                        <li>Quando começou?</li>
                        <li>Tipo da dor (irradiada ou local)</li>
                        <li>Intensidade da dor</li>
                        <li>O que faz a dor melhorar?</li>
                        <li>O que desencadeia a dor?</li>
                        <li>O que acompanha o sintoma?</li>
                        <li>Período da doença (quando começou)</li>
                        <li>Neste instante como se encontra o problema</li>
                        </ul>";
$hintQueixaPrincipal = "Motivo da consulta (descrever com palavras do paciente)";
$hintEstadoEmocional = "Descreva o estado emocional do paciente ao iniciar o atendimento ";
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
    <link rel="stylesheet" href="../vendor/select2/css/select2.min.css">
    <link href="./css/global.css" rel="stylesheet">
</head>

<body>
    <div class="mt-3 mx-2" style="width: 98%; padding-left: 20px;">
        <div>
            <button class="btn btn-primary btn-sm" onclick="novoAtendimento()">
                <i class="fas fa-plus"></i> Novo atendimento
            </button>
        </div>

        <table id="tabelaAtendimentos" class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Data do último atendimento</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Renderizar as linhas da tabela
                foreach ($listaAtendimentos as $linha) {
                    $atendimento = '<button class="btn btn-outline-primary btn-sm" onclick="listarAtendimentos(' . $linha['ID'] . ', \'' . $linha['NOME'] . '\')">
                            <i class="fa-solid fa-list"></i> Listar atendimentos
                        </button>';
                    //Se estiver finalizado, mostra botão de historico
                    //Status 1 = Em atendimento
                    //Status 2 = Finalizado
                    $status = $linha['STATUS'] ?? 0;
                    if ($status == 2) {
                        $atendimento = '<button class="btn btn-outline-primary btn-sm" onclick="dadosAtendimento(' . $linha['ID'] . ', \'' . $linha['NOME'] . '\')">
                            <i class="fas fa-edit"></i> Histórico do atendimento
                        </button>';
                    }
                    $dadosPaciente = '<button class="btn btn-outline-primary btn-sm" onclick="verDadosPaciente(' . $linha['ID'] . ', \'' . $linha['NOME'] . '\')">
                        <i class="fas fa-user"></i> Dados do paciente
                    </button>';

                    echo "
                    <tr>
                        <td>{$linha['ID']}</td>
                        <td>{$linha['NOME']}</td>
                        <td>{$linha['CRIADO_EM_DMY']}</td>
                        <td style='text-align: center;'>{$atendimento} {$dadosPaciente}</td>
                    </tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalAtendimento" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalAtendimentoLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="padding: 5px;">
                    <h1 class="modal-title fs-5 ms-2" id="modalAtendimentoLabel">Novo atendimento</h1>
                </div>
                <div class="modal-body px-3 mt-2" style="padding: 0px;">
                    <form id="form_inicio_atendimento" action="atendimento_action.php" method="post" class="tab-menu-wrapper needs-validation" novalidate enctype="multipart/form-data" style="margin-bottom: 10px;">
                        <input type="hidden" id="action" name="action" value="4">
                        <input type="hidden" id="id_paciente" name="id_paciente" value="-1">
                        <div id="divSelecionarPaciente" style="padding: 10px;">
                            <div>
                                <span>Primeiro, selecione o paciente:</span>
                            </div>
                            <div>
                                <select id="idPaciente" name="state" style="width: 100%;">
                                    <option value="0">&nbsp;</option>
                                    <?php foreach ($dados as $pacientes) : ?>
                                        <option value="<?= $pacientes['ID'] ?>"><?= $pacientes['ID'] . ' - ' . $pacientes['NOME'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div id="divAtualizarDados" class="hidden" style="max-height: 70vh; overflow: auto; padding: 10px;">
                            <fieldset class="m-0" style="border-radius: 4px;">
                                <legend class="px-2">Gostaria de atualizar alguma informação do paciente?</legend>
                                <div class="form-check form-switch ms-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="usa_marcapasso" name="usa_marcapasso">
                                    <label class="form-check-label" for="usa_marcapasso">Usa marca-passo ou algum dispositivo com baterias?</label>
                                </div>
                                <div id="dadosFemininos">
                                    <div class="form-check form-switch ms-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="gestante" name="gestante">
                                        <label class="form-check-label" for="gestante">Está grávida?</label>
                                    </div>
                                    <div class="form-check form-switch ms-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="risco_gestante" name="risco_gestante">
                                        <label class="form-check-label" for="risco_gestante">Risco de estar grávida?</label>
                                    </div>
                                    <div class="form-check form-switch ms-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="usa_diu" name="usa_diu">
                                        <label class="form-check-label" for="usa_diu">Usa DIU?</label>
                                    </div>
                                    <div class="form-check form-switch ms-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="contraceptivos" name="contraceptivos">
                                        <label class="form-check-label" for="contraceptivos">Usa métodos contraceptivos?</label>
                                    </div>
                                    <div class="form-check form-switch ms-2">
                                        <input class="form-check-input" type="checkbox" role="switch" id="possui_protese" name="possui_protese">
                                        <label class="form-check-label" for="possui_protese">Possui alguma prótese de silicone?</label>
                                    </div>
                                </div>
                            </fieldset>
                            <div class="mb-3">
                                <div class="card group-box m-0 mt-3">
                                    <div class="card-header d-flex justify-content-between">
                                        <div class='w-100'>
                                            Queixa principal
                                        </div>
                                        <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-html="true" data-bs-content="<?= $hintQueixaPrincipal ?>">
                                            <i class="fa-regular fa-circle-question" style="color: darkgreen; font-size: 21px;"></i>
                                        </span>
                                    </div>
                                    <div class="card-body m-0 p-0">
                                        <textarea class="w-100 no-border p-2" id="queixa_principal" rows="2" name="queixa_principal"></textarea>
                                    </div>
                                </div>
                                <div class="card group-box m-0 mt-3">
                                    <div class="card-header d-flex justify-content-between">
                                        <div class='w-100'>
                                            Descrição do problema
                                        </div>
                                        <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-html="true" data-bs-content="<?= $hintHistoriaDoenca ?>">
                                            <i class="fa-regular fa-circle-question" style="color: darkgreen; font-size: 21px;"></i>
                                        </span>
                                    </div>
                                    <div class="card-body m-0 p-0">
                                        <textarea class="w-100 no-border p-2" id="detalhes_doenca" rows="2" name="detalhes_doenca"></textarea>
                                    </div>
                                </div>
                                <div class="card group-box m-0 mt-3">
                                    <div class="card-header d-flex justify-content-between">
                                        <div class='w-100'>
                                            Estado emocional
                                        </div>
                                        <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-html="true" data-bs-content="<?= $hintEstadoEmocional ?>">
                                            <i class="fa-regular fa-circle-question" style="color: darkgreen; font-size: 21px;"></i>
                                        </span>
                                    </div>
                                    <div class="card-body m-0 p-0">
                                        <textarea class="w-100 no-border p-2" id="estado_emocional" rows="2" name="estado_emocional"></textarea>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <fieldset style="border-radius: 4px;">
                                        <legend class="px-2">Exames complementares
                                            <button class="btn btn-primary btn-sm mt-2 ms-2" onclick="adicionarExameAtendimento(event)">
                                                <i class="fas fa-plus"></i> Adicionar Exame
                                            </button>
                                        </legend>
                                        <div class="">
                                        </div>
                                        <div id="examesContainerAtendimento" class="row" style="margin-right: 3px !important; margin-left: 0px !important;">
                                            <!-- Cards inseridos via JS -->
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-success" onclick="salvarDadosInicioAtendimento()">Iniciar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Atendimentos do paciente -->
    <div class="modal fade" id="modalPaciente" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalPacienteLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="padding: 5px;">
                    <h1 class="modal-title fs-5 ms-2">Atendimentos do paciente <span id="modalPacienteLabel"></span></h1>
                </div>
                <div class="modal-body px-3 mt-2" style="padding: 0px;">
                    <table id="tabelaAtendimentosPaciente" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Ordem</th>
                                <th>Data do atendimento</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery-3.7.1.min.js"></script>
    <script src="../vendor/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/DataTables/js/datatables.min.js"></script>
    <script src="../vendor/select2/js/select2.full.min.js"></script>
    <script src="../assets/js/functions.js"></script>
    <script>
        var COUNT_EXAME = 0;
        var tabela;

        $(document).ready(function() {
            $('#tabelaAtendimentos').DataTable({
                responsive: true,
                columnDefs: [{
                    width: '30px',
                    targets: 0
                }],
                language: {
                    url: "../vendor/DataTables/js/pt-BR.json"
                }
            });

            $('#idPaciente').select2({
                dropdownParent: $('#modalAtendimento')
            });

            $('#idPaciente').on('select2:select', function(e) {
                var data = e.params.data;

                if (data.id > 0) {
                    carregarDadosPaciente(data.id);
                } else {
                    $('#divAtualizarDados').hide();
                }

                document.getElementById("id_paciente").value = data.id;
            });

            tabela = $('#tabelaAtendimentosPaciente').DataTable({
                "ajax": {
                    "url": "", // Vai setar depois no evento do select!
                    "dataSrc": ""
                },
                "columns": [{
                        "data": "id_paciente"
                    },
                    {
                        "data": "criado_em",
                        "render": function(data) {
                            // Formata data para DD/MM/YYYY
                            let d = new Date(data);
                            return d.toLocaleDateString('pt-BR');
                        }
                    },
                    {
                        "data": "id",
                        "render": function(data) {
                            return `<button onclick="visualizarAtendimento(${data})" class="btn btn-primary btn-sm">Visualizar</button>`;
                        }
                    }
                ],
                "language": {
                    url: "../vendor/DataTables/js/pt-BR.json"
                }
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });

            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            const popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
                return new bootstrap.Popover(popoverTriggerEl);
            });
        });

        function novoAtendimento() {
            $('#idPaciente').val('0');
            $('#idPaciente').trigger('change');
            $('#divAtualizarDados').hide();

            const modalAtendimento = new bootstrap.Modal(document.getElementById('modalAtendimento'));
            modalAtendimento.show();
        }

        function carregarDadosPaciente(idPaciente) {
            $.ajax({
                url: '../src/api/routes.php',
                type: 'POST',
                data: {
                    rota: 'obter_dados_paciente',
                    id_paciente: idPaciente
                },
                success: function(response) {
                    $('#dadosFemininos').hide();
                    if (response.genero == 2) {
                        $('#dadosFemininos').show();
                        document.getElementById('gestante').checked = response.gestante == 1;
                        document.getElementById('risco_gestante').checked = response.risco_gestante == 1;
                        document.getElementById('usa_diu').checked = response.usa_diu == 1;
                        document.getElementById('contraceptivos').checked = response.contraceptivos == 1;
                        document.getElementById('possui_protese').checked = response.possui_protese == 1;
                    }

                    document.getElementById('usa_marcapasso').checked = response.usa_marcapasso == 1;

                    $('#divAtualizarDados').fadeIn();
                },
                error: function(xhr, status, error) {
                    let response = JSON.parse(xhr.responseText);
                    top.toastr.error(response.message || "Erro ao carregar dados do paciente!");
                }
            });
        }

        function adicionarExameAtendimento(e) {
            e.preventDefault();
            COUNT_EXAME++;

            let html = `<div id="div_novo_exame_comp_${COUNT_EXAME}" class="col-12 mb-3">
                            <div class="card-exame d-flex position-relative">
                                <div id="close_novo_exame_comp_${COUNT_EXAME}" class="position-absolute start-100 translate-middle top-0 rounded-circle delete-card" onclick="excluirCardExame('div_novo_exame_comp_${COUNT_EXAME}', this)" data-bs-toggle="tooltip" data-bs-title="Remover">
                                    <i class="fa-solid fa-xmark"></i>
                                </div>
                                <div style="width: 50%;">
                                    <textarea class="form-control" name="novo_exame_comp[${COUNT_EXAME}]" id="novo_exame_comp_${COUNT_EXAME}" placeholder="Descrição do exame" style="min-height: 70px;"></textarea>
                                </div>
                                <div class="text-center" style="width: 50%;">
                                    <div class="text-center p-2">
                                        <label for="novo_exame_comp_file_${COUNT_EXAME}">
                                            <input
                                                type="file"
                                                style="display: none;"
                                                id="novo_exame_comp_file_${COUNT_EXAME}"
                                                name="novo_exame_comp_file_${COUNT_EXAME}"
                                                onchange="exibirNomeArquivo(this, 'label_file_${COUNT_EXAME}')"
                                                accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/*"
                                            >
                                            <a class="btn btn-primary btn-sm shadow">
                                                <i class="fa-regular fa-file"></i> Selecionar arquivo
                                            </a>
                                        </label>
                                    </div>
                                    <div>
                                        <div class="text-center">
                                            <span id="label_file_${COUNT_EXAME}" class="form-label" style="font-size: 12px;">Nenhum arquivo selecionado</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;

            $('#examesContainerAtendimento').prepend(html);

            //Recria os tooltips do BS5
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        function excluirCardExame(id, elm) {
            // Encontra a instância ativa do Tooltip e a destrói
            const tooltipInstance = bootstrap.Tooltip.getInstance(elm);
            if (tooltipInstance) {
                tooltipInstance.dispose();
            }

            document.getElementById(id).remove();
        }

        function exibirNomeArquivo(input, idLabel) {
            let fileName = "Selecione";

            if (input.files.length > 0) {
                fileName = input.files[0].name;
            }

            document.getElementById(idLabel).innerText = fileName;
        }

        function listarAtendimentos(idPaciente, nomePaciente) {
            // Atualiza tabela quando muda o paciente
            //$('#pacienteSelect').on('change', function() {
            //let idPaciente = $(this).val();
            if (idPaciente) {
                tabela.ajax.url(`../src/api/routes.php?rota=obter_atendimentos_paciente&id_paciente=${idPaciente}`).load();
            } else {
                tabela.clear().draw();
            }
            //});

            $('#modalPacienteLabel').text(nomePaciente);
            const modalPaciente = new bootstrap.Modal(document.getElementById('modalPaciente'));
            modalPaciente.show();
        }

        // Função para o botão
        function visualizarAtendimento(id) {
            alert("Visualizar atendimento ID: " + id);
            // Aqui você pode abrir modal ou redirecionar!
        }

        function exibirModalAtendimentos(id, nomePaciente) {
            $('#modalPacienteLabel').text(nomePaciente);

            /* $('#tabelaAtendimentosPaciente').DataTable({
                responsive: true,
                columnDefs: [{
                    width: '30px',
                    targets: 0
                }],
                language: {
                    url: "../vendor/DataTables/js/pt-BR.json"
                }
            }); */

            const modalPaciente = new bootstrap.Modal(document.getElementById('modalPaciente'));
            modalPaciente.show();
        }

        function salvarDadosInicioAtendimento() {
            let idPaciente = document.getElementById('id_paciente').value;
            if (idPaciente == '') {
                top.toastr.success("Para continuar, selecione um paciente!");
                return false;
            }

            // Obtém o formulário dentro do iframe
            let form = document.getElementById('form_inicio_atendimento');

            // Cria um objeto FormData para capturar todos os campos, incluindo arquivos
            let formData = new FormData(form);

            // Envia os dados via AJAX com FormData
            $.ajax({
                url: './atendimento_action.php',
                type: 'POST',
                data: formData,
                processData: false, // Impede o jQuery de processar os dados (obrigatório para arquivos)
                contentType: false, // Impede o jQuery de definir um contentType incorreto
                success: function(response) {
                    // Exibe mensagem de sucesso
                    top.toastr.success(response.message);

                    // Recarrega o iframe de atendimentos
                    let iframeRaizAtendimentos = parent.document.getElementById('iframe_atendimentos');
                    if (iframeRaizAtendimentos != undefined) {
                        iframeRaizAtendimentos.contentWindow.location.reload();
                    }

                    if (typeof top.addTab == "function") {
                        top.addTab('Atendimento em andamento', './atendimentos.php?id_paciente=' + idPaciente, false);
                    }
                },
                error: function(xhr, status, error) {
                    let response = JSON.parse(xhr.responseText);
                    top.toastr.error(response.message || "Não foi possível iniciar o atendimento! Erro ao gravar dados no banco!");
                }
            });

        }
    </script>
</body>

</html>