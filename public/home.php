<!DOCTYPE html>
<html lang="pt-br">

<!-- twilio - id
S4512V6KKAJPFABGWHXLTVW3 -->

<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.php';

use App\Controllers\TerapeutaController;

$terapeutaController = new TerapeutaController();

$idTerapeuta = 1; //$_REQUEST['id_terapeuta'] ?? -1;
$terapeuta = $terapeutaController->getById('bio_terapeutas', 'id', $idTerapeuta);
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="./favicon.ico">
    <title>Biomagnetismo</title>
    <link href="../vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/adminlte3/css/adminlte.min.css" rel="stylesheet">
    <link href="../vendor/fontawesome-free-6.7.2/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../vendor/toastr/css/toastr.min.css">
    <link href="../assets/css/global.css" rel="stylesheet">
</head>

<body>
    <div id="sidebar" style="position: relative;">
        <div class="logo">
            <img src="./image/logo.png" alt="Bru.terapeuta" style="max-height: 100px;">
        </div>
        <nav class="nav flex-column">
            <a class="nav-link main-menu" onclick="addTab('Início', './inicio.php')">Início</a>
            <a class="nav-link main-menu" onclick="addTab('Pacientes', './pacientes.php')">Pacientes</a>
            <a class="nav-link main-menu" onclick="addTab('Atendimentos', './lista_atendimentos.php')">Atendimentos</a>
            <a class="nav-link main-menu" onclick="addTab('Configurações', './configs.php')">Configurações</a>
        </nav>
        <nav class="nav flex-column">
        </nav>
        <div class="ml-4 d-flex" style="align-items: center; position: absolute; bottom: 20px;">
            <div class="me-2 rounded-icon" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Dados do terapeuta">
                <a onclick="addTab('Dados do terapeuta', './terapeuta.php')">
                    <i class="fa-solid fa-user-pen" style="color: darkgreen;"></i>
                </a>
            </div>
            <div class="nome-terapeuta"><?= abreviarNome($terapeuta['nome']) ?></div>
        </div>
    </div>
    <div id="content">
        <ul class="nav nav-tabs" id="tabMenu" style="gap: 3px;">
        </ul>
        <div class="tab-content mx-2" id="tabContent" style="height: calc(100% - 48px);">
            <div class="tab-pane" id="tab_pacientes" role="tabpanel">
                <!-- Tabs inseridas dinamicamente -->
            </div>
        </div>
    </div>

    <script src="../vendor/jquery/jquery-3.7.1.min.js"></script>
    <script src="../vendor/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/toastr/js/toastr.min.js"></script>
    <script>
        $(document).ready(function() {
            toastr.options = {
                "closeButton": true, // Botão para fechar
                "debug": false,
                //"newestOnTop": true, // Notificações mais recentes no topo
                "progressBar": true, // Barra de progresso
                "positionClass": "toast-top-right", // Posição (top-right, top-left, bottom-right, etc.)
                "preventDuplicates": true, // Evita notificações duplicadas
                //"onclick": null,
                "showDuration": "300", // Duração da animação de entrada
                "hideDuration": "1000", // Duração da animação de saída
                "timeOut": "3000", // Tempo de exibição (em ms)
                "extendedTimeOut": "1000", // Tempo extra ao passar o mouse
                "showEasing": "swing", // Tipo de animação de entrada
                "hideEasing": "linear", // Tipo de animação de saída
                "showMethod": "fadeIn", // Método de entrada
                "hideMethod": "fadeOut" // Método de saída
            };

            //Tooltip BS5
            addTooltips();

            addTab('Início', './inicio.php', false);
        });

        function addTab(title, content, closable = true) {
            var titleRep = title.replace(/\s+/g, '-');
            var tabId = titleRep + '-tab';
            var paneId = titleRep + '-pane';

            // Check if the tab is already open
            if ($(`#${tabId}`).length === 0) {
                // Add new tab header
                closeBtn = '';
                if (closable) {
                    closeBtn = `<span class='ms-2 text-secondary close-tab' style='cursor:pointer;'><i class="fa-solid fa-xmark btn-close-tab"></i></span>`;
                }

                $("#tabMenu").append(
                    `<li class="nav-item">
                        <button class="nav-link tab-border-top" id="${tabId}" data-bs-toggle="tab" data-bs-target="#${paneId}" type="button" role="tab">
                            ${title} ${closeBtn}
                        </button>
                    </li>`
                );

                // Add new tab content
                if (content.startsWith('#')) {
                    // Existing HTML element
                    var elementContent = $(content).html() || `<p>Conteúdo não encontrado para ${title}</p>`;
                    $("#tabContent").append(
                        `<div class="tab-pane" id="${paneId}" role="tabpanel">
                            ${elementContent}
                        </div>`
                    );
                } else {
                    // URL in iframe
                    $("#tabContent").append(
                        `<div class="tab-pane" id="${paneId}" role="tabpanel">
                            <iframe id="iframe_${titleRep.toLowerCase()}" src="${content}" style="width:100%; height:100%; border:none;"></iframe>
                        </div>`
                    );
                }
            }

            // Activate the new tab
            $(`#${tabId}`).tab("show");
        }

        // Close tab functionality
        $(document).on("click", ".close-tab", function() {
            var tabId = $(this).closest("button").attr("id");
            var paneId = $(this).closest("button").data("bs-target");

            // Remove the tab and its content
            $(`#${tabId}`).parent().remove();
            $(paneId).remove();

            // Activate the first tab if any exist
            if ($("#tabMenu .nav-link").length > 0) {
                $("#tabMenu .nav-link:first").tab("show");
            }
        });

        function addTooltips() {
            let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            let tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                this.addEventListener('hide.bs.tooltip', function() {
                    new bootstrap.Tooltip(tooltipTriggerEl)
                })
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        }

        //Calcula a idade apos preencher a data de nascimento
        function calcularIdade(elm) {
            if ($(elm).val().length < 10) {
                return;
            }
            var birthDate = new Date($(elm).val());
            var today = new Date();
            var age = today.getFullYear() - birthDate.getFullYear();
            var month = today.getMonth() - birthDate.getMonth();
            if (month < 0 || (month === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            $("#idade").val(age);
        }
    </script>
</body>

</html>