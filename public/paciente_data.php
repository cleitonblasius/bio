<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/functions.php';

use App\Controllers\PacienteController;

$pacientesController = new PacienteController();

$idPaciente = $_REQUEST['id_paciente'] ?? -1;

$bioPacientes = $bioEstruturaFamiliar = $bioDoencasFamilia = $bioHistoricoSaude = $bioHistoriaFisiologica = $bioHistoriaSocial = $bioHistoriaDoenca = [];
$qtdFilhos = 0;
$usaDIU = false;
$isGestante = false;
$usaMarcapasso = false;
$riscoGestante = false;
$possuiProtese = false;
$isGeneroFeminino = true;
$usaContraceptivos = false;
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

if ($idPaciente > 0) {
    $bioPacientes = $pacientesController->getById('bio_pacientes', 'id', $idPaciente);
    $bioEstruturaFamiliar = $pacientesController->getById('bio_estrutura_familiar', 'id_paciente', $idPaciente);
    $bioDoencasFamilia = $pacientesController->getById('bio_doencas_familia', 'id_paciente', $idPaciente);
    $bioHistoricoSaude = $pacientesController->getById('bio_historico_saude', 'id_paciente', $idPaciente);
    $bioHistoriaFisiologica = $pacientesController->getById('bio_historia_fisiologica', 'id_paciente', $idPaciente);
    $bioHistoriaSocial = $pacientesController->getById('bio_historia_social', 'id_paciente', $idPaciente);
    $bioHistoriaDoenca = $pacientesController->getById('bio_historia_doenca', 'id_paciente', $idPaciente);

    $isGeneroFeminino = !empty($bioPacientes['genero']) && $bioPacientes['genero'] == '2';
    $qtdFilhos = !empty($bioPacientes['filhos']) ? $bioPacientes['filhos'] : 0;
    $isGestante = !empty($bioPacientes['gestante']) && $bioPacientes['gestante'] == '1';
    $usaMarcapasso = !empty($bioPacientes['marcapasso']) && $bioPacientes['marcapasso'] == '1';
    $riscoGestante = !empty($bioPacientes['risco_gestante']) && $bioPacientes['risco_gestante'] == '1';
    $usaContraceptivos = !empty($bioPacientes['contraceptivos']) && $bioPacientes['contraceptivos'] == '1';
    $usaDIU = !empty($bioPacientes['usa_diu']) && $bioPacientes['usa_diu'] == '1';
    $possuiProtese = !empty($bioPacientes['possui_protese']) && $bioPacientes['possui_protese'] == '1';

    //Exames complementares
    $examesComplementares = $pacientesController->getExamesComplementares($idPaciente);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha do Cliente</title>
    <link rel="stylesheet" href="../vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/fontawesome-free-6.7.2/css/all.min.css">
    <link href="./css/global.css" rel="stylesheet">
</head>

<body>
    <div class="w-100">
        <nav style="padding-top: 10px;">
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link tab-border-top active" id="nav-dados-pessoais-tab" data-bs-toggle="tab" data-bs-target="#nav-dados-pessoais" type="button" role="tab" aria-controls="nav-dados-pessoais" aria-selected="true">Dados Pessoais</button>
                <button class="nav-link tab-border-top" id="nav-familiar-tab" data-bs-toggle="tab" data-bs-target="#nav-familiar" type="button" role="tab" aria-controls="nav-familiar" aria-selected="false">Dados Familiares</button>
                <button class="nav-link tab-border-top" id="nav-historico-saude-tab" data-bs-toggle="tab" data-bs-target="#nav-historico-saude" type="button" role="tab" aria-controls="nav-historico-saude" aria-selected="false">Histórico de Saúde</button>
                <button class="nav-link tab-border-top" id="nav-historia-fisiologica-tab" data-bs-toggle="tab" data-bs-target="#nav-historia-fisiologica" type="button" role="tab" aria-controls="nav-historia-fisiologica" aria-selected="false">História Fisiológica</button>
                <button class="nav-link tab-border-top" id="nav-historia-social-tab" data-bs-toggle="tab" data-bs-target="#nav-historia-social" type="button" role="tab" aria-controls="nav-historia-social" aria-selected="false">História Social</button>
                <button class="nav-link tab-border-top" id="nav-historia-doenca-atual-tab" data-bs-toggle="tab" data-bs-target="#nav-historia-doenca-atual" type="button" role="tab" aria-controls="nav-historia-doenca-atual" aria-selected="false">História da Doença Atual (HDA)</button>
                <button class="nav-link tab-border-top" id="nav-exames-complementares-tab" data-bs-toggle="tab" data-bs-target="#nav-exames-complementares" type="button" role="tab" aria-controls="nav-exames-complementares" aria-selected="false">Exames Complementares</button>
                <button class="nav-link tab-border-top" id="nav-observacoes-tab" data-bs-toggle="tab" data-bs-target="#nav-observacoes" type="button" role="tab" aria-controls="nav-observacoes" aria-selected="false">Observações do Terapeuta</button>
                <button class="nav-link tab-border-top" id="nav-consentimento-tab" data-bs-toggle="tab" data-bs-target="#nav-consentimento" type="button" role="tab" aria-controls="nav-consentimento" aria-selected="false">Termo de consentimento</button>
            </div>
        </nav>
        <form id="form_paciente" action="paciente_action.php" method="post" class="tab-menu-wrapper needs-validation" novalidate enctype="multipart/form-data">
            <input type="hidden" id="id_paciente" name="id_paciente" value="<?= $_REQUEST['id_paciente'] ?? -1 ?>">
            <input type="hidden" id="action" name="action" value="<?= $_REQUEST['action'] ?? -1 ?>">

            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-dados-pessoais" role="tabpanel" aria-labelledby="nav-dados-pessoais-tab" tabindex="0">
                    <div class="group-box p-4">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="nome" class="form-label">Nome:</label>
                                <div class="input-group has-validation">
                                    <input type="text" class="form-control" id="nome" name="nome" value="<?= $bioPacientes['nome'] ?? '' ?>" required>
                                    <div class="invalid-feedback">
                                        O nome é obrigatório!
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-2 mb-3">
                                <label for="cpf" class="form-label">CPF:</label>
                                <div class="input-group has-validation">
                                    <input type="text" class="form-control" id="cpf" name="cpf" value="<?= formatarCPF($bioPacientes['cpf'] ?? '') ?>" oninput="formatarCPF(this)" maxlength="14" required>
                                    <div class="invalid-feedback">
                                        O CPF é obrigatório!
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-2 mb-3">
                                <label for="rg" class="form-label">RG:</label>
                                <input type="text" class="form-control" id="rg" name="rg" value="<?= $bioPacientes['rg'] ?? '' ?>">
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2 mb-3">
                                <label for="data_nascimento" class="form-label">Data de nascimento:</label>
                                <input type="date" class="form-control" id="data_nascimento" name="data_nascimento" onchange="calcularIdade(this, 'idade')" onblur="calcularIdade(this, 'idade')" value="<?= $bioPacientes['data_nascimento'] ?? '' ?>">
                            </div>
                            <div class="col-sm-6 col-md-2 col-lg-2 mb-3">
                                <label for="idade" class="form-label">Idade:</label>
                                <input type="number" class="form-control" id="idade" name="idade" disabled>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="endereco" class="form-label">Endereço:</label>
                                <input type="text" class="form-control" id="endereco" name="endereco" value="<?= $bioPacientes['endereco'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="naturalidade" class="form-label">Naturalidade:</label>
                                <input type="text" class="form-control" id="naturalidade" name="naturalidade" value="<?= $bioPacientes['naturalidade'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="email" class="form-label">E-mail:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $bioPacientes['email'] ?? '' ?>">
                            </div>
                            <div class="col-sm-6 col-md-3 col-lg-2 mb-3">
                                <label for="telefone_pessoal" class="form-label">Telefone principal:</label>
                                <input type="tel" class="form-control" id="telefone_pessoal" name="telefone_pessoal" value="<?= formatarTelefone($bioPacientes['telefone_pessoal'] ?? '') ?>" oninput="formatarTelefone(this)" maxlength="15">
                            </div>
                            <div class="col-sm-6 col-md-3 col-lg-2 mb-3">
                                <label for="telefone_recados" class="form-label">Telefone secundário:</label>
                                <input type="tel" class="form-control" id="telefone_recados" name="telefone_recados" value="<?= formatarTelefone($bioPacientes['telefone_recados'] ?? '') ?>" oninput="formatarTelefone(this)" maxlength="15">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="profissao" class="form-label">Profissão/Carga horária:</label>
                                <input type="text" class="form-control" id="profissao" name="profissao" value="<?= $bioPacientes['profissao'] ?? '' ?>">
                            </div>
                            <div class="col-sm-6 col-md-3 col-lg-2 mb-3">
                                <label for="religiao" class="form-label">Religião:</label>
                                <input type="text" class="form-control" id="religiao" name="religiao" value="<?= $bioPacientes['religiao'] ?? '' ?>">
                            </div>
                            <div class="col-sm-6 col-md-3 col-lg-2 mb-3">
                                <span>Gênero:</span>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="genero" id="generoF" <?= $isGeneroFeminino ? 'checked' : '' ?> value="2" onclick="exibirCamposGeneroFeminino(true)">
                                    <label class="form-check-label" for="generoF">
                                        Feminino
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="genero" id="generoM" <?= $isGeneroFeminino ? '' : 'checked' ?> value="1" onclick="exibirCamposGeneroFeminino(false)">
                                    <label class="form-check-label" for="generoM">
                                        Masculino
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-2 mb-3">
                                <label for="cor" class="form-label">Cor:</label>
                                <input type="text" class="form-control" id="cor" name="cor" value="<?= $bioPacientes['cor'] ?? '' ?>">
                            </div>
                            <div class="col-sm-6 col-md-6 col-lg-2 mb-3">
                                <label for="estado_civil" class="form-label">Estado Civil:</label>
                                <input type="text" class="form-control" id="estado_civil" name="estado_civil" value="<?= $bioPacientes['estado_civil'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="escolaridade" class="form-label">Escolaridade:</label>
                                <input type="text" class="form-control" id="escolaridade" name="escolaridade" value="<?= $bioPacientes['escolaridade'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="medicamentos" class="form-label">Medicamentos Atuais:</label>
                                <input type="text" class="form-control" id="medicamentos" name="medicamentos" value="<?= $bioPacientes['medicamentos'] ?? '' ?>">
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-2 mb-3">
                                <div class="form-check form-switch ms-2 mt-3">
                                    <input class="form-check-input" type="checkbox" role="switch" id="marcapasso" name="marcapasso" <?= $usaMarcapasso ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="marcapasso">Utiliza marcapasso?</label>
                                </div>
                                <div class="form-check form-switch ms-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="filhosSN" name="filhosSN" <?= $qtdFilhos > 0 ? 'checked' : '' ?> onclick="mudaEstadoFilhos(this)">
                                    <label class="form-check-label" for="filhosSN">Tem filhos?</label>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4 col-lg-2 mb-3">
                                <label for="filhos" class="form-label">Quantos filhos?</label>
                                <input type="number" class="form-control" id="filhos" name="filhos" value="<?= $bioPacientes['filhos'] ?? '' ?>" <?= $qtdFilhos > 0 ? '' : 'disabled' ?>>
                            </div>
                            <div id="grupoFeminino1" class="col-sm-6 col-md-4 col-lg-4 mb-3">
                                <div class="form-check form-switch ms-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="gestante" name="gestante" <?= $isGestante ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="gestante">Está grávida?</label>
                                </div>
                                <div class="form-check form-switch ms-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="risco_gestante" name="risco_gestante" <?= $riscoGestante ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="risco_gestante">Risco de estar grávida?</label>
                                </div>
                                <div class="form-check form-switch ms-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="usa_diu" name="usa_diu" <?= $usaDIU ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="usa_diu">Usa DIU?</label>
                                </div>
                            </div>
                            <div id="grupoFeminino2" class="col-sm-6 col-md-4 col-lg-4 mb-3">
                                <div class="form-check form-switch ms-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="contraceptivos" name="contraceptivos" <?= $usaContraceptivos ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="contraceptivos">Usa métodos contraceptivos?</label>
                                </div>
                                <div class="form-check form-switch ms-2">
                                    <input class="form-check-input" type="checkbox" role="switch" id="possui_protese" name="possui_protese" <?= $possuiProtese ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="possui_protese">Possui alguma prótese de silicone?</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-familiar" role="tabpanel" aria-labelledby="nav-familiar-tab" tabindex="0">
                    <div class="card group-box">
                        <div class="card-header">
                            Estrutura Familiar
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                    <label for="tipo_moradia" class="form-label">Tipo de moradia</label>
                                    <input type="text" class="form-control" id="tipo_moradia" name="tipo_moradia" value="<?= $bioEstruturaFamiliar['tipo_moradia'] ?? '' ?>">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                    <label for="habitantes" class="form-label">Quem mora na casa</label>
                                    <input type="text" class="form-control" id="habitantes" name="habitantes" value="<?= $bioEstruturaFamiliar['habitantes'] ?? '' ?>">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                    <label for="animais" class="form-label">Animais de estimação</label>
                                    <input type="text" class="form-control" id="animais" name="animais" value="<?= $bioEstruturaFamiliar['animais'] ?? '' ?>">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                    <label for="parentes" class="form-label">Parentes que moram perto</label>
                                    <input type="text" class="form-control" id="parentes" name="parentes" value="<?= $bioEstruturaFamiliar['parentes'] ?? '' ?>">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                    <label for="comunidade" class="form-label">Comunidade (rede de apoio)</label>
                                    <input type="text" class="form-control" id="comunidade" name="comunidade" value="<?= $bioEstruturaFamiliar['comunidade'] ?? '' ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card group-box">
                        <div class="card-header">
                            Histórico Familiar (Doenças)
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                    <label for="mae" class="form-label">Mãe</label>
                                    <input type="text" class="form-control" id="mae" name="mae" value="<?= $bioDoencasFamilia['mae'] ?? '' ?>">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                    <label for="pai" class="form-label">Pai</label>
                                    <input type="text" class="form-control" id="pai" name="pai" value="<?= $bioDoencasFamilia['pai'] ?? '' ?>">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                    <label for="irmaos" class="form-label">Irmãos</label>
                                    <input type="text" class="form-control" id="irmaos" name="irmaos" value="<?= $bioDoencasFamilia['irmaos'] ?? '' ?>">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                    <label for="avos_maternos" class="form-label">Avó materna/avô materno</label>
                                    <input type="text" class="form-control" id="avos_maternos" name="avos_maternos" value="<?= $bioDoencasFamilia['avos_maternos'] ?? '' ?>">
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                    <label for="avos_paternos" class="form-label">Avó paterna/avô paterno</label>
                                    <input type="text" class="form-control" id="avos_paternos" name="avos_paternos" value="<?= $bioDoencasFamilia['avos_paternos'] ?? '' ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-historico-saude" role="tabpanel" aria-labelledby="nav-historico-saude-tab" tabindex="0">
                <div class="card group-box">
                    <div class="card-header">
                        Histórico de saúde (História Patológica Pregressa (HPP)
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="doencas_infancia" class="form-label">Doenças de infância</label>
                                <textarea class="form-control" id="doencas_infancia" name="doencas_infancia"><?= $bioHistoricoSaude['doencas_infancia'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="vacinacao" class="form-label">Vacinação</label>
                                <textarea class="form-control" id="vacinacao" name="vacinacao"><?= $bioHistoricoSaude['vacinacao'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="transfusao" class="form-label">Transfusão sanguínea</label>
                                <textarea class="form-control" id="transfusao" name="transfusao"><?= $bioHistoricoSaude['transfusao'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="doacao_sangue" class="form-label">Doação de sangue</label>
                                <textarea class="form-control" id="doacao_sangue" name="doacao_sangue"><?= $bioHistoricoSaude['doacao_sangue'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="alergias" class="form-label">Alergias</label>
                                <textarea class="form-control" id="alergias" name="alergias"><?= $bioHistoricoSaude['alergias'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="fraturas" class="form-label">Fraturas</label>
                                <textarea class="form-control" id="fraturas" name="fraturas"><?= $bioHistoricoSaude['fraturas'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="cirurgias" class="form-label">Cirurgias</label>
                                <textarea class="form-control" id="cirurgias" name="cirurgias"><?= $bioHistoricoSaude['cirurgias'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="tatuagens" class="form-label">Tatuagens</label>
                                <textarea class="form-control" id="tatuagens" name="tatuagens"><?= $bioHistoricoSaude['tatuagens'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="piercings" class="form-label">Piercings</label>
                                <textarea class="form-control" id="piercings" name="piercings"><?= $bioHistoricoSaude['piercings'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="fenomenos_tumorais" class="form-label">Fenômenos tumorais</label>
                                <textarea class="form-control" id="fenomenos_tumorais" name="fenomenos_tumorais" placeholder="Cistos, câncer, pólipos, tumor benigno..."><?= $bioHistoricoSaude['fenomenos_tumorais'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="problemas_memoria" class="form-label">Problemas de memória</label>
                                <textarea class="form-control" id="problemas_memoria" name="problemas_memoria"><?= $bioHistoricoSaude['problemas_memoria'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="problemas_dormir" class="form-label">Problemas para dormir</label>
                                <textarea class="form-control" id="problemas_dormir" name="problemas_dormir"><?= $bioHistoricoSaude['problemas_dormir'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="problemas_visao" class="form-label">Problemas com a visão</label>
                                <textarea class="form-control" id="problemas_visao" name="problemas_visao"><?= $bioHistoricoSaude['problemas_visao'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="problemas_audicao" class="form-label">Problemas com audição</label>
                                <textarea class="form-control" id="problemas_audicao" name="problemas_audicao"><?= $bioHistoricoSaude['problemas_audicao'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="problemas_digestivos" class="form-label">Problemas Digestivos</label>
                                <textarea class="form-control" id="problemas_digestivos" name="problemas_digestivos" placeholder="Bucais, náuseas, emagrecimento, obesidade, diarreia, constipação"><?= $bioHistoricoSaude['problemas_digestivos'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="problemas_renais" class="form-label">Problemas renais e urinários</label>
                                <textarea class="form-control" id="problemas_renais" name="problemas_renais" placeholder="Ardência ao urinar, volume, cor da urina..."><?= $bioHistoricoSaude['problemas_renais'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="problemas_respiratorios" class="form-label">Problemas respiratórios</label>
                                <textarea class="form-control" id="problemas_respiratorios" name="problemas_respiratorios" placeholder="Asma, bronquite, renite, pneumonia, falta de ar, tosse..."><?= $bioHistoricoSaude['problemas_respiratorios'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="problemas_cardiacos" class="form-label">Problemas Cardíacos</label>
                                <textarea class="form-control" id="problemas_cardiacos" name="problemas_cardiacos" placeholder="Marcapasso, pressão arterial alterada, infarto..."><?= $bioHistoricoSaude['problemas_cardiacos'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="problemas_metabolicos" class="form-label">Problemas metabólicos</label>
                                <textarea class="form-control" id="problemas_metabolicos" name="problemas_metabolicos" placeholder="Diabetes, colesterol, obesidade..."><?= $bioHistoricoSaude['problemas_metabolicos'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="problemas_psicoemocionais" class="form-label">Problemas psicoemocionais</label>
                                <textarea class="form-control" id="problemas_psicoemocionais" name="problemas_psicoemocionais" placeholder="Depressão, ansiedade, tristeza, ideação suicida..."><?= $bioHistoricoSaude['problemas_psicoemocionais'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="doencas_sexuais" class="form-label">Doenças sexualmente transmissíveis</label>
                                <textarea class="form-control" id="doencas_sexuais" name="doencas_sexuais"><?= $bioHistoricoSaude['doencas_sexuais'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="problemas_hepaticos" class="form-label">Problemas hepáticos</label>
                                <textarea class="form-control" id="problemas_hepaticos" name="problemas_hepaticos" placeholder="Hepatite..."><?= $bioHistoricoSaude['problemas_hepaticos'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="problemas_reprodutor" class="form-label">Problemas no sistema reprodutor</label>
                                <textarea class="form-control" id="problemas_reprodutor" name="problemas_reprodutor" placeholder="Libido, potência sexual, endometriose..."><?= $bioHistoricoSaude['problemas_reprodutor'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="problemas_musculares" class="form-label">Problemas muscoloesqueléticos</label>
                                <textarea class="form-control" id="problemas_musculares" name="problemas_musculares" placeholder="Força, dor muscular e articular..."><?= $bioHistoricoSaude['problemas_musculares'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="problemas_pele" class="form-label">Problemas de pele</label>
                                <textarea class="form-control" id="problemas_pele" name="problemas_pele" placeholder="Psoríase, dermatite..."><?= $bioHistoricoSaude['problemas_pele'] ?? '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-historia-fisiologica" role="tabpanel" aria-labelledby="nav-historia-fisiologica-tab" tabindex="0">
                <div class="card group-box">
                    <div class="card-header">
                        História Fisiológica
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="nascimento" class="form-label">Tipo de parto/ onde nasceu</label>
                                <textarea class="form-control" id="nascimento" name="nascimento"><?= $bioHistoriaFisiologica['nascimento'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="desenvolvimento" class="form-label">Atraso no desenvolvimento</label>
                                <textarea class="form-control" id="desenvolvimento" name="desenvolvimento"><?= $bioHistoriaFisiologica['desenvolvimento'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="menstruacao" class="form-label">Primeira menstruação/ como é</label>
                                <textarea class="form-control" id="menstruacao" name="menstruacao"><?= $bioHistoriaFisiologica['menstruacao'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="primeira_relacao" class="form-label">Primeira relação sexual</label>
                                <textarea class="form-control" id="primeira_relacao" name="primeira_relacao"><?= $bioHistoriaFisiologica['primeira_relacao'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="menopausa" class="form-label">Menopausa</label>
                                <textarea class="form-control" id="menopausa" name="menopausa"><?= $bioHistoriaFisiologica['menopausa'] ?? '' ?></textarea>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="gestacoes" class="form-label">Número de Gestações</label>
                                <input type="number" class="form-control" id="gestacoes" name="gestacoes" value="<?= $bioHistoriaFisiologica['gestacoes'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="qtd_filhos" class="form-label">Quantos filhos</label>
                                <input type="number" class="form-control" id="qtd_filhos" name="qtd_filhos" value="<?= $bioHistoriaFisiologica['qtd_filhos'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="qtd_abortos" class="form-label">Teve aborto?</label>
                                <input type="number" class="form-control" id="qtd_abortos" name="qtd_abortos" value="<?= $bioHistoriaFisiologica['qtd_abortos'] ?? '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-historia-social" role="tabpanel" aria-labelledby="nav-historia-social-tab" tabindex="0">
                <div class="card group-box">
                    <div class="card-header">
                        História Social
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="bebidas_alcoolicas" class="form-label">Bebidas alcoólicas</label>
                                <input type="text" class="form-control" id="bebidas_alcoolicas" name="bebidas_alcoolicas" value="<?= $bioHistoriaSocial['bebidas_alcoolicas'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="tabagismo_drogas" class="form-label">Tabagismo/ drogas</label>
                                <input type="text" class="form-control" id="tabagismo_drogas" name="tabagismo_drogas" value="<?= $bioHistoriaSocial['tabagismo_drogas'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="ingestao_agua" class="form-label">Ingestão de água (quantidade)</label>
                                <input type="text" class="form-control" id="ingestao_agua" name="ingestao_agua" value="<?= $bioHistoriaSocial['ingestao_agua'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="habitos_sono" class="form-label">Hábitos de Sono</label>
                                <input type="text" class="form-control" id="habitos_sono" name="habitos_sono" placeholder="Horário em que vai dormir e acorda" value="<?= $bioHistoriaSocial['habitos_sono'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="habitos_lazer" class="form-label">Hábitos de Lazer</label>
                                <input type="text" class="form-control" id="habitos_lazer" name="habitos_lazer" value="<?= $bioHistoriaSocial['habitos_lazer'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="viagens" class="form-label">Viagens</label>
                                <input type="text" class="form-control" id="viagens" name="viagens" value="<?= $bioHistoriaSocial['viagens'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="ambiente_trabalho" class="form-label">Ambiente de trabalho</label>
                                <input type="text" class="form-control" id="ambiente_trabalho" name="ambiente_trabalho" value="<?= $bioHistoriaSocial['ambiente_trabalho'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="atividade_fisica" class="form-label">Atividade física</label>
                                <input type="text" class="form-control" id="atividade_fisica" name="atividade_fisica" value="<?= $bioHistoriaSocial['atividade_fisica'] ?? '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card group-box">
                    <div class="card-header">
                        Hábitos alimentares
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="cafe_manha" class="form-label">Café da manhã</label>
                                <input type="text" class="form-control" id="cafe_manha" name="cafe_manha" value="<?= $bioHistoriaSocial['cafe_manha'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="lanche_manha" class="form-label">Lanche</label>
                                <input type="text" class="form-control" id="lanche_manha" name="lanche_manha" value="<?= $bioHistoriaSocial['lanche_manha'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="almoco" class="form-label">Almoço</label>
                                <input type="text" class="form-control" id="almoco" name="almoco" value="<?= $bioHistoriaSocial['almoco'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="lanche_tarde" class="form-label">Lanche da tarde</label>
                                <input type="text" class="form-control" id="lanche_tarde" name="lanche_tarde" value="<?= $bioHistoriaSocial['lanche_tarde'] ?? '' ?>">
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                <label for="janta" class="form-label">Janta</label>
                                <input type="text" class="form-control" id="janta" name="janta" value="<?= $bioHistoriaSocial['janta'] ?? '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-historia-doenca-atual" role="tabpanel" aria-labelledby="nav-historia-doenca-atual-tab" tabindex="0">
                <div class="row m-2">
                    <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                        <label for="problema" class="form-label">Qual o problema?</label>
                        <textarea class="form-control" id="problema" name="problema"><?= $bioHistoriaDoenca['problema'] ?? '' ?></textarea>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                        <label for="tipo_dor" class="form-label">Tipo da dor: (irradiada ou local)</label>
                        <input type="text" class="form-control" id="tipo_dor" name="tipo_dor" value="<?= $bioHistoriaDoenca['tipo_dor'] ?? '' ?>">
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                        <label for="intensidade_dor" class="form-label">Intensidade da dor:</label>
                        <input type="text" class="form-control" id="intensidade_dor" name="intensidade_dor" value="<?= $bioHistoriaDoenca['intensidade_dor'] ?? '' ?>">
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                        <label for="melhorar" class="form-label">O que faz a dor melhorar?</label>
                        <textarea class="form-control" id="melhorar" name="melhorar"><?= $bioHistoriaDoenca['melhorar'] ?? '' ?></textarea>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                        <label for="desencadeia" class="form-label">O que desencadeia?</label>
                        <textarea class="form-control" id="desencadeia" name="desencadeia"><?= $bioHistoriaDoenca['desencadeia'] ?? '' ?></textarea>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                        <label for="acompanha_sintoma" class="form-label">O que acompanha o sintoma?</label>
                        <textarea class="form-control" id="acompanha_sintoma" name="acompanha_sintoma"><?= $bioHistoriaDoenca['acompanha_sintoma'] ?? '' ?></textarea>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                        <label for="inicio_doenca" class="form-label">Período da doença: (Quando começou)</label>
                        <textarea class="form-control" id="inicio_doenca" name="inicio_doenca"><?= $bioHistoriaDoenca['inicio_doenca'] ?? '' ?></textarea>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-4 mb-3">
                        <label for="situacao_atual" class="form-label">Neste instante, como se encontra?</label>
                        <textarea class="form-control" id="situacao_atual" name="situacao_atual"><?= $bioHistoriaDoenca['situacao_atual'] ?? '' ?></textarea>
                    </div>
                </div>

            </div>
            <!-- EXAMES COMPLEMENTARES -->
            <?php if ($idPaciente > 0) { ?>
                <div class="tab-pane fade" id="nav-exames-complementares" role="tabpanel" aria-labelledby="nav-exames-complementares-tab" tabindex="0">
                    <div class="m-1">
                        <div>
                            <button class="btn btn-primary btn-sm mt-2 ms-2" onclick="adicionarExame(event)">
                                <i class="fas fa-plus"></i> Adicionar Exame
                            </button>
                        </div>
                        <div>
                            <div id="examesContainer" class="row" style="margin-right: 3px !important; margin-left: 0px !important;">
                                <!-- Cards de exame -->
                                <?php foreach ($examesComplementares as $key => $dadosExame): ?>
                                    <div id="div_exame_comp_<?= $dadosExame['ID'] ?>" class="col-sm-12 col-md-6 col-lg-4 mb-3">
                                        <div class="card-exame d-flex position-relative">
                                            <div class="position-absolute start-100 translate-middle top-0 rounded-circle delete-card" onclick="excluirCardSalvoExame(<?= $dadosExame['ID'] ?>, this)" data-bs-toggle="tooltip" data-bs-title="Excluir">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </div>
                                            <div style="width: 60%;">
                                                <textarea
                                                    class="form-control"
                                                    name="edit_exame_comp<?= $dadosExame['ID'] ?>"
                                                    placeholder="Descrição do exame"
                                                    style="min-height: 70px;"><?= $dadosExame['DESCRICAO'] ?></textarea>
                                            </div>
                                            <div class="text-center mt-2" style="width: 40%;">
                                                <div class="text-center px-3">
                                                    <a class="download-link" href="<?= $dadosExame['ARQUIVO'] ?? "" ?>" target="_blank">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <i class="<?= getFileIconClass(explode('.', $dadosExame['ARQUIVO'])[1] ?? "") ?>" style="font-size: 35px;"></i>
                                                            Visualizar Arquivo
                                                        </div>
                                                    </a>
                                                </div>
                                                <span class="form-label" style="font-size: 12px;">Cadastrado em <?= $dadosExame['CRIADO_EM'] ?></span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div id="nenhumExameAdd">
                                <?php if (empty($examesComplementares)) {
                                    echo printNoDataFoundMessage(
                                        'Nenhum exame cadastrado até o momento.',
                                        '<div class="mt-2">Adicione exames através do botão</div>
                                        <button class="btn btn-primary btn-sm mt-2 ms-2" onclick="adicionarExame(event)">
                                            <i class="fas fa-plus"></i> Adicionar Exame
                                        </button>'
                                    );
                                } ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="tab-pane fade" id="nav-observacoes" role="tabpanel" aria-labelledby="nav-observacoes-tab" tabindex="0">
                <div class="row m-4">
                    <textarea class="form-control" id="obs_terapeuta" name="obs_terapeuta" rows="10"><?= $bioPacientes['obs_terapeuta'] ?? '' ?></textarea>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-consentimento" role="tabpanel" aria-labelledby="nav-consentimento-tab" tabindex="0">
                <div class="row m-4">
                    termo de consetimento anexado
                </div>
            </div>
        </form>
    </div>

    <script src="../vendor/jquery/jquery-3.7.1.min.js"></script>
    <script src="../vendor/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/moment/moment-with-locales.js"></script>
    <script src="../assets/js/functions.js"></script>
    <script>
        var COUNT_NOVO_EXAME = 0;

        window.onload = function() {
            if ('<?= $bioPacientes['data_nascimento'] ?? '' ?>' != '') {
                calcularIdade($('#data_nascimento'), 'idade');
            }

            if (<?= !$isGeneroFeminino ? 'true' : 'false' ?>) {
                $('#grupoFeminino1').hide();
                $('#grupoFeminino2').hide();
            }
        }

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

        // Habilita/Desabilita o campo de filhos
        function mudaEstadoFilhos(elm) {
            if (elm.checked && elm.checked) {
                $('#filhos').prop('disabled', false);
            } else {
                $('#filhos').prop('disabled', true);
            }
        }

        function exibirNomeArquivo(input, idLabel) {
            let fileName = "Selecione";

            if (input.files.length > 0) {
                fileName = input.files[0].name;
            }

            document.getElementById(idLabel).innerText = fileName;
        }

        function adicionarExame(e) {
            e.preventDefault();
            COUNT_NOVO_EXAME++;

            let html = `<div id="div_novo_exame_comp_${COUNT_NOVO_EXAME}" class="col-sm-12 col-md-6 col-lg-4 mb-3">
                            <div class="card-exame d-flex position-relative">
                                <div id="close_novo_exame_comp_${COUNT_NOVO_EXAME}" class="position-absolute start-100 translate-middle top-0 rounded-circle delete-card" onclick="excluirCardExame('div_novo_exame_comp_${COUNT_NOVO_EXAME}', this)" data-bs-toggle="tooltip" data-bs-title="Remover">
                                    <i class="fa-solid fa-xmark"></i>
                                </div>
                                <div style="width: 50%;">
                                    <textarea class="form-control" name="novo_exame_comp[${COUNT_NOVO_EXAME}]" id="novo_exame_comp_${COUNT_NOVO_EXAME}" placeholder="Descrição do exame" style="min-height: 70px;"></textarea>
                                </div>
                                <div class="text-center" style="width: 50%;">
                                    <div class="text-center p-2">
                                        <label for="novo_exame_comp_file_${COUNT_NOVO_EXAME}">
                                            <input
                                                type="file"
                                                style="display: none;"
                                                id="novo_exame_comp_file_${COUNT_NOVO_EXAME}"
                                                name="novo_exame_comp_file_${COUNT_NOVO_EXAME}"
                                                onchange="exibirNomeArquivo(this, 'label_file_${COUNT_NOVO_EXAME}')"
                                                accept="application/pdf, application/msword, application/vnd.openxmlformats-officedocument.wordprocessingml.document, image/*"
                                            >
                                            <a class="btn btn-primary btn-sm shadow">
                                                <i class="fa-regular fa-file"></i> Selecionar arquivo
                                            </a>
                                        </label>
                                    </div>
                                    <div>
                                        <div class="text-center">
                                            <span id="label_file_${COUNT_NOVO_EXAME}" class="form-label" style="font-size: 12px;">Nenhum arquivo selecionado</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;

            $('#nenhumExameAdd').fadeOut();
            $('#examesContainer').prepend(html);

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

            //Se não tiver outro exame, exibe a mensagem de "nenhum exame"
            if ($('div[id*="div_novo_exame_comp_"]').length == 0) {
                $('#nenhumExameAdd').fadeIn();
            }
        }

        function excluirCardSalvoExame(id, elm) {
            if (!confirm(`Deseja realmente excluir o exame?\nEsta ação não poderá ser desfeita!`)) {
                return;
            }

            $.ajax({
                url: '../src/api/routes.php',
                type: 'POST',
                data: {
                    rota: 'excluir_exame_complementar',
                    id_exame: id
                },
                success: function(response) {
                    if (response > 0) {
                        excluirCardExame(`div_exame_comp_${id}`, elm);

                        //Exibe mensagem de sucesso
                        top.toastr.success("Exame excluído com sucesso!");
                    }
                },
                error: function(xhr, status, error) {
                    let response = JSON.parse(xhr.responseText);
                    top.toastr.error(response.message || "Erro ao excluir registro do banco!");
                }
            });
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

        function exibirCamposGeneroFeminino(show) {
            if (show) {
                $('#grupoFeminino1').fadeIn();
                $('#grupoFeminino2').fadeIn();
            } else {
                $('#grupoFeminino1').fadeOut();
                $('#grupoFeminino2').fadeOut();
            }
        }
    </script>
</body>

</html>