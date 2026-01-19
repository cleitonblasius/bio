<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.php';

use App\Controllers\TerapeutaController;

$terapeutaController = new TerapeutaController();

$idTerapeuta = 1;//$_REQUEST['id_terapeuta'] ?? -1;

$bioTerapeuta = [];
if ($idTerapeuta > 0) {
    $terapeuta = $terapeutaController->getById('bio_terapeutas', 'id', $idTerapeuta);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados do terapeuta</title>
    <link rel="stylesheet" href="../vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/fontawesome-free-6.7.2/css/all.min.css">
    <link href="../assets/css/global.css" rel="stylesheet">

    <style>
        /* From Uiverse.io by G4b413l */
        .tooltipCustom {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }

        .tooltipCustom .tooltipCustomtext {
            visibility: hidden;
            width: fit-content;
            min-width: 200px;
            background-color: white;
            color: #282828;
            border: 1px solid #282828;
            text-align: center;
            border-radius: 5px;
            padding: 10px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltipCustom:hover .tooltipCustomtext {
            visibility: visible;
            opacity: 1;
        }

        .tooltipCustom .tooltipCustomtext::after {
            content: "";
            position: absolute;
            top: 100%;
            left: 50%;
            border-width: 8px;
            border-style: solid;
            border-color: #28282817 transparent transparent transparent;
            transform: translateX(-50%);
        }
    </style>
</head>

<body>
    <div>
        <form id="form_terapeuta" action="terapeuta_action.php" method="post" class="needs-validation position-relative" novalidate>
            <input type="hidden" id="id_terapeuta" name="id_terapeuta" value="<?= $idTerapeuta ?? -1 ?>">
            <input type="hidden" id="action" name="action" value="<?= empty($idTerapeuta) ? 1 : 2 ?>">

            <button type="button" class="btn btn-success position-absolute z-3" style="bottom: 10px; right: 15px;" onclick="salvarDadosTerapeuta()">Salvar</button>

            <div class="tab-content" id="nav-tabContent">
                <div class="card group-box">
                    <div class="card-header">
                        Dados do terapeuta
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="nome" class="form-label">Nome:</label>
                                <div class="input-group has-validation">
                                    <input type="text" class="form-control" id="nome" name="nome" value="<?= $terapeuta['nome'] ?? '' ?>" required>
                                    <div class="invalid-feedback">
                                        O nome é obrigatório!
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="email" class="form-label">E-mail:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $terapeuta['email'] ?? '' ?>">
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2 mb-3">
                                <label for="cpf" class="form-label">CPF:</label>
                                <div class="input-group has-validation">
                                    <input type="text" class="form-control" id="cpf" name="cpf" value="<?= formatarCPF($terapeuta['cpf'] ?? '') ?>" oninput="formatarCPF(this)" maxlength="14">
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2 mb-3">
                                <label for="data_nascimento" class="form-label">Data de nascimento:</label>
                                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" value="<?= $terapeuta['data_nascimento'] ?? '' ?>">
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2 mb-3">
                                <label for="telefone_principal" class="form-label">Telefone principal:</label>
                                <input type="tel" class="form-control" id="telefone_principal" name="telefone_principal" value="<?= formatarTelefone($terapeuta['telefone_principal'] ?? '') ?>" oninput="formatarTelefone(this)">
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2 mb-3">
                                <label for="telefone_secundario" class="form-label">Telefone secundário:</label>
                                <input type="tel" class="form-control" id="telefone_secundario" name="telefone_secundario" value="<?= formatarTelefone($terapeuta['telefone_secundario'] ?? '') ?>" oninput="formatarTelefone(this)">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="endereco" class="form-label">Endereço:</label>
                                <input type="text" class="form-control" id="endereco" name="endereco" value="<?= $terapeuta['endereco'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="google_agenda" class="form-label">Google Agenda:</label>
                                <button type="button" class="btn btn-link" style="padding: 0px;" id="agendaPopOver">
                                    <i class="fa-regular fa-circle-question"></i>
                                </button>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" id="google_agenda" name="google_agenda" value="<?= $terapeuta['google_agenda'] ?? '' ?>">
                                    <span class="input-group-text">@gmail.com</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div id="password">
                    <div class="card group-box">
                        <div class="card-header">
                            Alterar senha de acesso
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                    <label for="mae" class="form-label">Senha antiga:</label>
                                    <input type="text" class="form-control" id="mae" name="mae" value="">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                    <label for="pai" class="form-label">Nova senha:</label>
                                    <input type="text" class="form-control" id="pai" name="pai" value="">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                    <label for="irmaos" class="form-label">Repita a nova senha:</label>
                                    <input type="text" class="form-control" id="irmaos" name="irmaos" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
            <div style="height: 50px;"></div>
        </form>
    </div>
    <script src="../vendor/jquery/jquery-3.7.1.min.js"></script>
    <script src="../vendor/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/moment/moment-with-locales.js"></script>
    <script src="../assets/js/functions.js"></script>
    <script>
        window.onload = function() {
            // Configuração do popover com HTML interpretado
            var popover = new bootstrap.Popover(document.getElementById('agendaPopOver'), {
                trigger: 'click',
                title: "Como Compartilhar Calendário",
                content: `<ul><li>Acesse o Google Calendar.</li>
                    <li>No lado esquerdo, clique sobre o calendário que deseja compartilhar.</li>
                    <li>Clique nos três pontos ao lado do nome do calendário e selecione Configurações e compartilhamento.</li>
                    <li>Na seção Acesso de permissões, marque a opção Tornar disponível publicamente.</li>
                    <li>Confirme a escolha.</li></ul>`,
                html: true // Habilita a interpretação de HTML no conteúdo
            });

            // Fechar o popover se o usuário clicar fora dele
            document.addEventListener('click', function(event) {
                var popoverElement = document.getElementById('agendaPopOver');
                var popoverInstance = bootstrap.Popover.getInstance(popoverElement);

                // Verifica se o clique foi fora do botão de popover
                if (popoverInstance && !popoverElement.contains(event.target)) {
                    popoverInstance.hide(); // Esconde o popover
                }
            });
        }

        function salvarDadosTerapeuta() {
            //Obtem o formulario
            let form = document.getElementById('form_terapeuta');

            //Valida o preenchimento dos campos requeridos (Nome)
            if (!validaPreenchimentoRequerido(form)) {
                return;
            }

            // Serializa os dados do formulário
            var dados = $(form).serialize();

            // Envia os dados via AJAX
            $.ajax({
                url: './terapeuta_action.php',
                type: 'POST',
                data: dados,
                success: function(response) {
                    //Exibe mensagem de sucesso
                    top.toastr.success(response.message);

                    //Recarrega a tela de dados do Terapeuta
                    let iframeRaizTerapeuta = parent.document.getElementById('iframe_dados-do-terapeuta');
                    if (iframeRaizTerapeuta != undefined) {
                        iframeRaizTerapeuta.contentWindow.location.reload();
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

            campos.forEach(function (campoId) {
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
    </script>
</body>

</html>