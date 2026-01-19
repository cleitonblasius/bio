<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../functions.php';

use App\Controllers\AtendimentoController;

$atendimentoController = new AtendimentoController();

$tools = []; // ['npn', 'rastreio', 'dem'];
$viewMode = (bool) $_REQUEST['view'] ?? false;
$idPaciente = $_REQUEST['id_paciente'] ?? -1;
$idAtendimento = (int) $_REQUEST['id_atendimento'] ?: -1;

if (empty($idAtendimento)) {
    die('ID do atendimento não recebido. Não é possível continuar.');
}

if ($idAtendimento > 0) {
    //Se o atendimento já existe, carrega os dados
    $dadosAtendimentos = $atendimentoController->getDadosAtendimento($idAtendimento);
    $idPaciente = $dadosAtendimentos['ID_PACIENTE'];
    $idRastreio = $dadosAtendimentos['ID_RASTREIO'];
    $idNpn = $dadosAtendimentos['ID_NPN'];
    $idDem = $dadosAtendimentos['ID_DEM'];
    $nomePaciente = $dadosAtendimentos['NOME'];
    $criadoEm = $dadosAtendimentos['CRIADO_EM_DMY'];
}

// Lista de ferramentas disponíveis
$ferramentasDisponiveis = ['npn', 'dem', 'rastreio'];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Atendimento Biomagnetismo</title>
    <link rel="stylesheet" href="../../vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../vendor/fontawesome-free-6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../../vendor/toastr/css/toastr.min.css">
    <style>
        body {
            position: relative;
            background-color: #f5f9f6;
            font-family: 'Segoe UI', sans-serif;
        }

        .card-paciente {
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: none;
            background: linear-gradient(135deg, #2e7d32, #388e3c);
            color: white;
            position: relative;
            padding: 10px;
        }

        .card-paciente h2 {
            font-weight: 600;
            margin: 0;
        }

        .actions {
            position: absolute;
            top: 15px;
            right: 15px;
            display: flex;
            gap: 0.5rem;
        }

        .actions button {
            border: none;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.6rem 0.8rem;
            border-radius: 10px;
            transition: all 0.2s;
        }

        .actions button:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: scale(1.05);
        }

        .ferramentas-container {
            margin-top: 1.5rem;
        }

        .ferramenta-card {
            border-radius: 5px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
            border: none;
            background: #1b5e20;
            color: white;
            overflow: hidden;
            margin-bottom: 1rem;
            position: relative;
        }

        .ferramenta-header {
            background: #2e7d32;
            padding: 0.6rem 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ferramenta-header h5 {
            margin: 0;
            font-size: 1rem;
        }

        .ferramenta-controls button {
            margin-left: 0.3rem;
        }

        .ferramenta-body {
            height: 300px;
            background: #fafafa;
        }

        .maximized {
            position: fixed !important;
            top: 0;
            left: 0;
            width: 100% !important;
            height: 100% !important;
            z-index: 9999;
            border-radius: 0 !important;
        }

        .maximized .ferramenta-body {
            height: calc(100vh - 50px) !important;
        }

        .minimized .ferramenta-body {
            display: none;
        }

        .resize-vertical {
            border: 1px solid #ccc;
            padding: 8px;
            min-height: 300px;
            overflow: auto;
            resize: vertical;
            background-color: #fff;
            /* border-radius: 6px; */
        }

        .save-button {
            position: sticky;
            left: 50%;
            bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <!-- Card do paciente -->
        <div class="card-paciente">
            <div>
                <h3><?= $nomePaciente ?></h3>
                <small>ID Atendimento: <?= $idAtendimento ?></small>
            </div>
            <div class="actions">
                <?php
                $icons = ['npn' => 'fa-brain', 'dem' => 'fa-masks-theater', 'rastreio' => 'fa-location-crosshairs'];
                $titles = ['npn' => 'Adicionar NPN', 'dem' => 'Adicionar DEM', 'rastreio' => 'Adicionar Rastreio'];
                ?>
                <?php foreach ($ferramentasDisponiveis as $tool) : ?>
                    <button class="add-ferramenta" data-tool="<?= $tool ?>" title="<?= $titles[$tool] ?>" data-bs-toggle="tooltip">
                        <i class="fas <?= $icons[$tool] ?>"></i>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Ferramentas adicionadas -->
        <div class="ferramentas-container" id="ferramentas">
            <?php
            // Carrega ferramentas já registradas
            if (!empty($tools)) {
                $added = [];
                foreach ($tools as $tool) {
                    if (!in_array($tool, $added) && file_exists("{$tool}.php")) {
                        $added[] = $tool;
                        require "{$tool}.php";
                    }
                }
            }
            ?>
        </div>

        <!-- Observações -->
        <div class="ferramenta-card">
            <div class="ferramenta-header">
                <span><i class="fa-solid fa-pen-to-square"></i> Observações:</span>
                <div class="ferramenta-controls">
                    <button class="btn btn-sm btn-light minimize-card" title="Minimizar" data-bs-toggle="tooltip">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button class="btn btn-sm btn-light maximize-card" title="Maximizar" data-bs-toggle="tooltip">
                        <i class="fas fa-expand"></i>
                    </button>
                </div>
            </div>
            <div class="ferramenta-body">
                <textarea class="form-control form-control-sm sintomas h-100" name="descricao" id="descricao"></textarea>
            </div>
        </div>
    </div>

    <!-- Botão de salvar flutuante no final da pagina -->
    <button type="button" class="btn btn-success save-button" onclick="salvarAtendimento()">Salvar</button>

    <script src="../../vendor/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../vendor/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../vendor/toastr/js/toastr.min.js"></script>
    <script>
        const FERRAMENTAS = <?php echo json_encode($ferramentasDisponiveis); ?>;
        const ID_ATENDIMENTO = <?= $idAtendimento ?>

        $(function() {
            toastr.options = {
                "closeButton": true, // Exibe o botão "X" para fechar
                "debug": false, // Desativa o modo debug
                "newestOnTop": true, // Exibe novas notificações no topo
                "progressBar": true, // Exibe uma barra de progresso
                "positionClass": "toast-top-right", // Posição padrão (superior direita)
                "preventDuplicates": true, // Evita mensagens duplicadas
                "onclick": null, // Nenhuma ação ao clicar
                "showDuration": "300", // Duração da animação de entrada (ms)
                "hideDuration": "1000", // Duração da animação de saída (ms)
                "timeOut": "5000", // Tempo antes de desaparecer (ms)
                "extendedTimeOut": "1000", // Tempo extra se o mouse estiver sobre o toast
                "showEasing": "swing", // Efeito da animação de entrada
                "hideEasing": "linear", // Efeito da animação de saída
                "showMethod": "fadeIn", // Método de exibição
                "hideMethod": "fadeOut" // Método de ocultação
            };

            $('[data-bs-toggle="tooltip"]').tooltip();

            const addedTools = new Set();

            $(".add-ferramenta").on("click", function() {
                const tool = $(this).data("tool");
                if (addedTools.has(tool)) {
                    alert("Esta ferramenta já foi adicionada!");
                    return;
                }
                addedTools.add(tool);

                const tituloMap = {
                    npn: "NPN",
                    dem: "DEM",
                    rastreio: "Rastreio"
                };
                const titulo = tituloMap[tool] || tool.toUpperCase();
                const iframeUrl = `${tool}.php?atendimento=${ID_ATENDIMENTO}`;

                const card = `
                    <div class="ferramenta-card">
                        <div class="ferramenta-header">
                            <span><i class="fa-solid fa-check-circle"></i> Ferramenta: ${titulo}</span>
                            <div class="ferramenta-controls">
                                <button class="btn btn-sm btn-light minimize-card" title="Minimizar" data-bs-toggle="tooltip">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button class="btn btn-sm btn-light maximize-card" title="Maximizar" data-bs-toggle="tooltip">
                                    <i class="fas fa-expand"></i>
                                </button>
                                <button class="btn btn-sm btn-danger remove-card" title="Excluir" data-bs-toggle="tooltip">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div id="container-${tool}" class="ferramenta-body">
                            <iframe src="${iframeUrl}" width="100%" height="100%" frameborder="0"></iframe>
                        </div>
                    </div>
                `;

                $("#ferramentas").append(card);
                $('[data-bs-toggle="tooltip"]').tooltip();
            });

            $(document).on("click", ".minimize-card", function() {
                let card = $(this).closest(".ferramenta-card");
                card.toggleClass("minimized");
                $(this).find("i").toggleClass("fa-minus fa-plus");
            });

            $(document).on("click", ".maximize-card", function() {
                let card = $(this).closest(".ferramenta-card");
                card.toggleClass("maximized");
                $(this).find("i").toggleClass("fa-expand fa-compress");
            });

            $(document).on("click", ".remove-card", function() {
                if (confirm("Tem certeza que deseja remover esta ferramenta?")) {
                    let tooltip = bootstrap.Tooltip.getInstance(this);
                    if (tooltip) tooltip.dispose();
                    const toolName = $(this).closest(".ferramenta-card").find(".ferramenta-header span").text().split(":")[1].trim();
                    addedTools.delete(toolName.toLowerCase());
                    $(this).closest(".ferramenta-card").remove();
                }
            });
        });

        function salvarAtendimento() {
            FERRAMENTAS.forEach(tool => {
                const iframe = document.querySelector(`#container-${tool} iframe`);
                if (iframe && iframe.contentWindow && typeof iframe.contentWindow.salvarDados === 'function') {
                    iframe.contentWindow.salvarDados(ID_ATENDIMENTO);
                }
            });
        }
    </script>
</body>

</html>