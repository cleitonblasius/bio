<?php
require_once __DIR__ . '/../vendor/autoload.php';
//require_once __DIR__ . '/functions.php';

use App\Controllers\UtilsController;
use App\Controllers\TerapeutaController;

$utilsController = new UtilsController();
$terapeutaController = new TerapeutaController();

$idTerapeuta = 1; //$_REQUEST['id_terapeuta'] ?? -1;
$terapeuta = $terapeutaController->getById('bio_terapeutas', 'id', $idTerapeuta);

$userGoogleCalendar = $terapeuta['google_agenda'];

?>
<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista de Atendimentos</title>
    <link href="../vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../vendor/fontawesome-free-6.7.2/css/all.min.css" rel="stylesheet">
    <link href="../vendor/adminlte3/css/adminlte.min.css" rel="stylesheet">
    <link href="../assets/css/global.css" rel="stylesheet">
</head>

<?php
function renderMonthBirthdays()
{
    global $utilsController;

    $html = '';
    $nrLine = 1;
    $monthBirths = $utilsController->getMonthBirthdays();
    if (empty($monthBirths)) {
        $html = "<li class='list-group-item no-radius-top d-flex justify-content-between'>
                    <i>Nenhum aniversariante este mês.</i>
                </li>";
    } else {
        foreach ($monthBirths as $person) {
            /* if (empty($person['DIA_ANIVERSARIO'])) {
                continue;
            } */

            $dayBadge = "";
            switch ((int) $person['DIA_ANIVERSARIO']) {
                case 1:
                    $dayBadge = "<div><span class='badge bg-warning text-black'>Ontem!</span></div>";
                    $status = "fez";
                    break;
                case 2:
                    $dayBadge = "<div><span class='badge bg-success'>Hoje!</span></div>";
                    $status = "está fazendo";
                    break;
                case 3:
                    $dayBadge = "<div><span class='badge bg-primary'>Amanhã!</span></div>";
                    $status = "fará";
                    break;
                default:
                    $status = $person['STATUS_ANIVERSARIO'] == 1 ? "fez" : "fará";
                    $dayBadge = "";
                    break;
            }

            $classNm = $nrLine % 2 == 0 ? 'even' : '';
            $html .= "<li class='list-group-item d-flex {$classNm}'>
                        <div style='width: 80px; min-width: 80px;'>
                            <strong>{$person['ANIVERSARIO']}</strong>
                        </div>
                        <div style='width: 100%;'>
                            <strong>{$person['NOME']}</strong> {$status} {$person['IDADE_A_FAZER']} anos.
                        </div>
                        {$dayBadge}
                    </li>";
            $nrLine++;
        }
    }

    echo "<div class='col-6'>
                <div class='card shadow'>
                    <div class='card-header bg-primary text-white d-flex justify-content-between'>
                        <div class='w-100'>
                            <h5 class='mb-0'>Aniversariantes do Mês</h5>
                        </div>    
                        <div class='div-btn' data-bs-toggle='tooltip' data-bs-placement='top' data-bs-title='Dados do terapeuta'>
                            <a onclick='alert(\"reload\")'>
                                <i class='fa-solid fa-rotate-right' ></i>
                            </a>
                        </div>
                    </div>

                    <div class='card-body'>
                        <ul class='list-group'>
                            {$html}
                        </ul>
                    </div>
                </div>
            </div>";
}
?>

<body class="p-2 bg-light">
    <div class="h-100 w-100">
        <div class="row w-100 m-0">
            <!-- Atalhos -->
            <div class="col-sm-12 col-md-4 col-lg-4 h-fit-content">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-success" onclick="goToAddPaciente()">
                        <i class="fa-solid fa-user-plus text-white shortcuts"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pacientes</span>
                        <span class="info-box-text">Criar</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 h-fit-content">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-success">
                        <i class="fa-solid fa-user-doctor text-white shortcuts"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Atendimento</span>
                        <span class="info-box-text">Iniciar</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-4 h-fit-content">
                <div class="info-box shadow">
                    <span class="info-box-icon bg-success">
                        <i class="fa-regular fa-calendar-days text-white shortcuts"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text">Agenda</span>
                        <span class="info-box-text">Consultar</span>
                    </div>
                </div>
            </div>

            <!-- Card Agenda -->
            <?php if (!empty($userGoogleCalendar)) { ?>
                <div class="col-6">
                    <iframe src="https://calendar.google.com/calendar/embed?src=<?= $userGoogleCalendar ?>%40gmail.com&ctz=America%2FSao_Paulo" style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
                </div>
            <?php } ?>

            <!-- Card de Atendimentos -->
            <!-- <div class="col-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Atendimentos da Semana</h5>
                    </div>

                    <div class="card-body">
                        <div class="card-header bg-primary text-white border-radius-top" style="padding: 2px 15px;">Hoje (02/02/2025)</div>
                        <ul class="list-group mb-3">
                            <li class="list-group-item no-radius-top d-flex justify-content-between">
                                <div>
                                    <strong>João Silva</strong> - 14:30
                                </div>
                                <div>
                                    <span class="badge bg-success">Confirmado</span>
                                </div>
                            </li>
                            <li class="list-group-item even d-flex justify-content-between">
                                <div>
                                    <strong>Maria Oliveira</strong> - 10:00
                                </div>
                                <div>
                                    <span class="badge bg-success">Confirmado</span>
                                </div>
                            </li>
                        </ul>
                        <div class="card-header bg-primary text-white border-radius-top" style="padding: 2px 15px;">Hoje</div>
                        <ul class="list-group mb-3">
                            <li class="list-group-item no-radius-top d-flex justify-content-between">
                                <i>Nenhum atendimento agendado.</i>
                                <!-- <button class="btn btn-primary btn-sm">Agendar</button> -- >
                            </li>
                        </ul>

                        <div class="card-header bg-primary text-white border-radius-top" style="padding: 2px 15px;">Amanhã (03/02/2025)</div>
                        <ul class="list-group">
                            <li class="list-group-item no-radius-top">
                                <strong>Carlos Santos</strong> - 16:45
                            </li>
                            <li class="list-group-item even">
                                <strong>Ana Souza</strong> - 09:15
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-primary">Ir para agenda</button>
                    </div>
                </div>
            </div> -->

            <!-- Card de Aniversariantes -->
            <!-- Futuramente: enviar mensagem de felicitações -->
            <?= renderMonthBirthdays() ?>
        </div>
    </div>

    <script src="../vendor/jquery/jquery-3.7.1.min.js"></script>
    <script src="../vendor/bootstrap-5.3.3-dist/js/bootstrap.min.js"></script>
    <script>
        //Redireciona para a pagina de pacientes e abre a criação de novo paciente
        function goToAddPaciente() {
            if (typeof top.addTab == 'function') {
                top.addTab('Pacientes', './pacientes.php');

                setTimeout(() => {
                    if (typeof top.iframe_pacientes.contentWindow.criarPaciente == 'function') {
                        top.iframe_pacientes.contentWindow.criarPaciente();
                    }
                }, 500);
            }
        }
    </script>
</body>

</html>