<?php
$pares = include 'pares.php'; // importa todos os pares biomagnéticos
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Rastreio Biomagnético</title>
    <link rel="stylesheet" href="../../vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../vendor/fontawesome-free-6.7.2/css/all.min.css">
    <style>
        body {
            background-color: #f8faf9;
            padding: 1rem;
        }

        .card-row {
            position: relative;
            padding: 0.5rem;
            border-radius: 0.5rem;
            background: #fff;
            margin-bottom: 0.5rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
            /* sombra mais forte */
            display: flex;
            align-items: flex-start;
            gap: 10px;
            flex-wrap: wrap;
        }

        .card-row label {
            font-weight: 600;
            font-size: 0.8rem;
            margin-bottom: 0.1rem;
            display: block;
            color: #166534;
        }

        .card-row .form-control,
        .card-row textarea {
            font-size: 0.85rem;
        }

        .card-row textarea {
            resize: vertical;
            min-height: 38px;
        }

        .delete-row {
            position: absolute;
            top: -6px;
            right: -9px;
            background: #dc2626;
            border: none;
            border-radius: 50%;
            color: #fff;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.7rem;
            cursor: pointer;
        }

        #addRow {
            margin-top: 0.6rem;
        }

        .w-full-25 {
            width: 100% !important;
            max-width: 20%;
        }

        .width50 {
            width: 50px !important;
            min-width: 50px;
            /* flex-grow: 0; */
        }

        .width200 {
            max-width: 200px;
            /* flex-grow: 0; */
        }

        .label-relogio {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .btn-relogio {
            background: none;
            border: none;
            color: #166534;
            cursor: pointer;
            font-size: 0.9rem;
            padding: 0;
            margin-left: 4px;
            position: absolute;
        }

        .btn-relogio:hover {
            color: #0a3d1b;
        }

        .relative {
            position: relative;
        }
    </style>
</head>

<body>

    <div id="rastreioContainer"></div>

    <button class="btn btn-success btn-sm" id="addRow"><i class="fas fa-plus"></i> Adicionar</button>

    <script src="../../vendor/jquery/jquery-3.7.1.min.js"></script>
    <script src="../../vendor/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const PARES_BIOMAGNETICOS = <?php echo json_encode($pares); ?>;

        function buscarDados(codigo) {
            return PARES_BIOMAGNETICOS[codigo] || null;
        }

        function salvarDados(idAtendimento) {
            let rastreios = {};

            $(".card-row").each(function() {
                const codigo = $(this).find(".codigo").val().trim();
                const sintomas = $(this).find(".sintomas").val().trim();

                // só adiciona se tiver código preenchido
                if (codigo !== "") {
                    rastreios[codigo] = sintomas;
                }
            });

            const dados = {
                id_atendimento: idAtendimento,
                rastreios: rastreios
            };

            $.ajax({
                url: 'rastreio_action.php',
                method: 'POST',
                data: {
                    dados: JSON.stringify(dados)
                },
                success: function(response) {
                    top.toastr.success(error, 'Rastreio salvo com sucesso.');
                },
                error: function(xhr, status, error) {
                    console.error('Erro ao salvar rastreio:', error);
                    top.toastr.error('Houve um erro ao salvar as informações de rastreio.');
                }
            });
        }

        $(function() {
            function adicionarLinha() {
                let card = $(`
                    <div class="card-row">
                        <div class="width50" style="width: 50px;">
                            <label>Código</label>
                            <input type="text" class="form-control form-control-sm codigo" placeholder="">
                        </div>
                        <div class="width50 relative">
                            <label class="label-relogio">Início
                                <button type="button" class="btn-relogio" title="Hora de aplicação dos imãs" onclick="setStartAndEndTime(this)">
                                    <i class="fas fa-clock"></i>
                                </button>
                            </label>
                            <input type="text" class="form-control form-control-sm hora-rastreio" disabled>
                        </div>
                        <div class="width50">
                            <label>Fim</label>
                            <input type="text" class="form-control form-control-sm hora-final" disabled>
                        </div>
                        <div class="width200">
                            <label>Classificação</label>
                            <textarea class="form-control form-control-sm classificacao" rows="2" disabled></textarea>
                        </div>
                        <div class="width200">
                            <label>Par</label>
                            <textarea class="form-control form-control-sm par" rows="2" disabled></textarea>
                        </div>
                        <div class="w-full-25">
                            <label>Patógeno</label>
                            <textarea class="form-control form-control-sm patogeno" rows="2" disabled></textarea>
                        </div>
                        <div class="w-full-25">
                            <label>Diagnóstico</label>
                            <textarea class="form-control form-control-sm diagnostico" rows="2" disabled></textarea>
                        </div>
                        <div class="campo sintomas" style="flex-grow:1; min-width:200px;">
                            <label>Sintomas</label>
                            <textarea class="form-control form-control-sm sintomas" rows="2" placeholder="Sintomas"></textarea>
                        </div>
                        <button class="delete-row" data-bs-toggle="tooltip" title="Excluir"><i class="fas fa-trash"></i></button>
                    </div>
                    `);

                $("#rastreioContainer").append(card);

                // Tooltip
                var tooltipTriggerList = [].slice.call(card.find('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });

                adjustParentIframeHeight();
            }

            // transforma código em maiúsculo
            $(document).on("input", ".codigo", function() {
                this.value = this.value.toUpperCase();
            });

            function processarCodigo(input) {
                let codigo = $(input).val().trim();
                if (!codigo) return;

                let card = $(input).closest(".card-row");
                let dados = buscarDados(codigo);

                if (dados) {
                    card.find(".classificacao").val(dados.classificacao);
                    card.find(".par").val(dados.par);
                    card.find(".patogeno").val(dados.patogeno);
                    card.find(".diagnostico").val(dados.diagnostico);
                } else {
                    top.toastr.warning(`Código ${codigo} não encontrado!`);
                    return;
                }

                setStartAndEndTime(card.find(".hora-rastreio"));

                if (card.is(":last-child")) adicionarLinha();
            }

            $(document).on("keypress", ".codigo", function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    processarCodigo(this);
                }
            });

            $(document).on("blur", ".codigo", function() {
                processarCodigo(this);
            });

            $("#addRow").click(function() {
                adicionarLinha();
            });

            $(document).on("click", ".delete-row", function() {
                if (confirm("Tem certeza que deseja remover esta linha?")) {
                    let tooltip = bootstrap.Tooltip.getInstance(this);
                    if (tooltip) tooltip.dispose();
                    $(this).closest(".card-row").remove();
                    adjustParentIframeHeight();
                }
            });

            adicionarLinha(); // adiciona primeira linha automaticamente
        });

        //Seta a hora de inicio e fim para o tempo de aplicação do imã
        //input - deve ser o elemento do input de hora de inicio
        function setStartAndEndTime(input) {
            if (!input) return;

            let card = $(input).closest(".card-row");
            let agora = new Date();
            let hora = agora.toLocaleTimeString([], {
                hour: "2-digit",
                minute: "2-digit"
            });
            card.find(".hora-rastreio").val(hora);
            agora.setMinutes(agora.getMinutes() + 15);
            card.find(".hora-final").val(agora.toLocaleTimeString([], {
                hour: "2-digit",
                minute: "2-digit"
            }));
        }

        // Ajusta tamanho da iframe se houver
        function adjustParentIframeHeight() {
            let containerRastreio = parent.$('#container-rastreio');
            if (containerRastreio.length == 1) {
                containerRastreio.height(document.body.clientHeight);
            }
        }
    </script>

</body>

</html>