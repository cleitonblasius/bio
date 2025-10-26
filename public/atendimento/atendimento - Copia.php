<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.php';

use App\Controllers\AtendimentoController;
use App\Controllers\PacienteController;

$pacientesController = new PacienteController();
$dados = $pacientesController->getAllBasicData();

$atendimentoController = new AtendimentoController();
$listaAtendimentos = $atendimentoController->getAllDataAtendimento();
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
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<!-- 
    <div class="mt-3 mx-2" style="width: 98%; padding-left: 20px;">
        Adicionar ferramentas do atendimento
    </div> -->

<body class="bg-gray-50 p-6 font-sans">
    <div class="max-w-5xl mx-auto bg-white shadow-xl rounded-xl p-6">
        <div class="flex justify-between items-center border-b pb-4 mb-6">
            <h1 class="text-xl font-bold text-blue-700">Paciente: Cleiton Blasius</h1>
            <button onclick="addLinha()" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Adicionar Par</button>
        </div>

        <div id="linhas-container" class="space-y-4">
            <!-- Linhas serão adicionadas dinamicamente aqui -->
        </div>
    </div>

    <script>
        let linhaId = 0;

        function addLinha() {
            const id = linhaId++;
            const container = document.getElementById('linhas-container');
            const linha = document.createElement('div');
            linha.className = "grid grid-cols-5 gap-4 items-center";
            linha.innerHTML = `
                <input type="text" placeholder="Código" onblur="buscarDados(this, ${id})"
                       class="border p-2 rounded col-span-1" id="codigo-${id}">
                <input type="text" placeholder="Par" class="border p-2 rounded col-span-1" id="par-${id}" readonly>
                <input type="text" placeholder="Tipo do Par" class="border p-2 rounded col-span-1" id="tipo-${id}" readonly>
                <input type="text" placeholder="Ponto de Impactação" class="border p-2 rounded col-span-1" id="ponto-${id}" readonly>
                <input type="text" placeholder="Sintomas" class="border p-2 rounded col-span-1" id="sintomas-${id}" readonly>
            `;
            container.appendChild(linha);
        }

        async function buscarDados(input, id) {
            const codigo = input.value.trim();
            if (!codigo) return;

            const res = await fetch(`get_par.php?codigo=${encodeURIComponent(codigo)}`);
            const data = await res.json();

            if (data && data.success) {
                document.getElementById(`par-${id}`).value = data.par;
                document.getElementById(`tipo-${id}`).value = data.tipo_par;
                document.getElementById(`ponto-${id}`).value = data.ponto_impactacao;
                document.getElementById(`sintomas-${id}`).value = data.sintomas;
            } else {
                alert('Código não encontrado.');
            }
        }
    </script>

    <script src="../vendor/jquery/jquery-3.7.1.min.js"></script>
    <script src="../vendor/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/DataTables/js/datatables.min.js"></script>
    <script src="../vendor/select2/js/select2.full.min.js"></script>
    <script src="../assets/js/functions.js"></script>
    <script>
    </script>
</body>

</html>