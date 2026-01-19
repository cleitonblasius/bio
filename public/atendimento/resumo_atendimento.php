<?php
$idAtendimento = $_REQUEST['id_atendimento'] ?? -1;

/* if ($idAtendimento < 0) {
    include 'erro.php';
    exit;
} */

// Simulação de dados (backend)
$paciente = "Cleiton Blasius";
$id_atendimento = 1234;
$tools = ['npn', 'dem', 'rastreio']; // Ferramentas utilizadas
$observacoes = "Paciente respondeu bem ao tratamento. Reavaliar após 7 dias.";

$ferramentasNomes = [
    'npn' => 'NPN',
    'dem' => 'DEM',
    'rastreio' => 'Rastreio'
];

$ferramentasIcones = [
    'npn' => 'fa-brain',
    'dem' => 'fa-masks-theater',
    'rastreio' => 'fa-location-crosshairs'
];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Resumo do Atendimento</title>
    <link rel="stylesheet" href="../../vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../vendor/fontawesome-free-6.7.2/css/all.min.css">
    <style>
        body {
            background-color: #f5f9f6;
            font-family: 'Segoe UI', sans-serif;
        }

        .card-paciente {
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border: none;
            background: linear-gradient(135deg, #2e7d32, #388e3c);
            color: white;
            padding: 1.5rem;
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
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .ferramenta-body {
            background: #fafafa;
            color: #333;
            padding: 1rem;
            min-height: 150px;
        }

        .observacoes-text {
            white-space: pre-wrap;
            font-style: italic;
        }
    </style>
</head>

<body>
    <div class="container-fluid py-4">
        <!-- Card do paciente -->
        <div class="card-paciente d-flex justify-content-between align-items-center">
            <div>
                <h2><?= htmlspecialchars($paciente) ?></h2>
                <small>ID Atendimento: <?= htmlspecialchars($id_atendimento) ?></small>
            </div>
            <div>
                <i class="fa-solid fa-leaf fa-2xl opacity-50"></i>
            </div>
        </div>

        <!-- Ferramentas -->
        <div class="ferramentas-container">
            <?php foreach ($tools as $tool): ?>
                <div class="ferramenta-card">
                    <div class="ferramenta-header">
                        <h5><i class="fas <?= $ferramentasIcones[$tool] ?>"></i> <?= $ferramentasNomes[$tool] ?></h5>
                        <span class="text-white-50">Visualização</span>
                    </div>
                    <div class="ferramenta-body">
                        <?php
                        // Aqui você pode substituir pelo conteúdo real da ferramenta,
                        // como uma consulta ao banco ou um include de resumo.
                        echo "<p>Resumo da ferramenta <strong>{$ferramentasNomes[$tool]}</strong> do atendimento.</p>";
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>

            <!-- Observações -->
            <div class="ferramenta-card">
                <div class="ferramenta-header">
                    <h5><i class="fa-solid fa-pen-to-square"></i> Observações do Analista</h5>
                </div>
                <div class="ferramenta-body">
                    <p class="observacoes-text mb-0"><?= nl2br(htmlspecialchars($observacoes)) ?></p>
                </div>
            </div>
        </div>
    </div>

    <script src="../../vendor/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
