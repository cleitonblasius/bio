<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Rastreio Biomagnético</title>
    <link rel="stylesheet" href="../vendor/bootstrap-5.3.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../vendor/fontawesome-free-6.7.2/css/all.min.css">
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
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
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
    </style>
</head>

<body>

    <div id="rastreioContainer"></div>

    <button class="btn btn-success btn-sm" id="addRow"><i class="fas fa-plus"></i> Adicionar</button>

    <script src="../vendor/jquery/jquery-3.7.1.min.js"></script>
    <script src="../vendor/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(function() {

            function buscarDados(codigo) {
                let dados = {
                    "R9": {
                        classificacao: "Reservatório universal",
                        par: "Cápsula renal D/E - Rim Ips",
                        patogeno: "Reservatório HIV",
                        diagnostico: "Checar rins, disfunção renal."
                    },
                    "R1": {
                        classificacao: "Reservatório exemplo",
                        par: "Exemplo D/E - toba",
                        patogeno: "Pato Geno",
                        diagnostico: "Sei lá, não sou magnetista."
                    }
                };
                return dados[codigo] || null;
            }

            function adicionarLinha() {
                let card = $(`
                <div class="card-row">
                    <div class="row gx-2">
                        <div class="col-8">
                            <div class="row gx-2 mb-1">
                                <div class="col-2">
                                    <label>Código</label>
                                    <input type="text" class="form-control form-control-sm codigo" placeholder="">
                                </div>
                                <div class="col-5">
                                    <label>Classificação</label>
                                    <input type="text" class="form-control form-control-sm classificacao" disabled>
                                </div>
                                <div class="col-5">
                                    <label>Par</label>
                                    <input type="text" class="form-control form-control-sm par" disabled>
                                </div>
                            </div>
                            <div class="row gx-2 mb-1">
                                <div class="col-5">
                                    <label>Patógeno</label>
                                    <input type="text" class="form-control form-control-sm patogeno" disabled>
                                </div>
                                <div class="col-5">
                                    <label>Diagnóstico</label>
                                    <input type="text" class="form-control form-control-sm diagnostico" disabled>
                                </div>
                                <div class="col-1">
                                    <label>Início</label>
                                    <input type="text" class="form-control form-control-sm hora-rastreio" disabled>
                                </div>
                                <div class="col-1">
                                    <label>Fim</label>
                                    <input type="text" class="form-control form-control-sm hora-final" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-4">
                            <label>Sintomas</label>
                            <textarea class="form-control form-control-sm sintomas" rows="4" placeholder="Sintomas"></textarea>
                        </div>
                    </div>
                    <button class="delete-row" data-bs-toggle="tooltip" title="Excluir"><i class="fas fa-trash"></i></button>
                </div>
                `);
                $("#rastreioContainer").append(card);

                // inicializa tooltip do botão excluir
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
                }

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

        //Ajusta o tamanho da iframe que contém essa pagina de acordo com o crescimento do conteudo
        function adjustParentIframeHeight() {
            let containerRastreio = parent.$('#container-rastreio');
            if (containerRastreio.length == 1) {
                containerRastreio.height(document.body.clientHeight);
            }
        }
    </script>

</body>

</html>