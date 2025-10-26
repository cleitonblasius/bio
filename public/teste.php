<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agendamentos da Semana</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .list-group-item:nth-child(even) {
            background-color: #f8f9fa; /* Cor diferente para linhas pares */
        }
    </style>
</head>
<body class="p-5 bg-light">

    <div class="container">
        <div class="row">
            <div class="col-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">📅 Agendamentos da Semana</h5>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <!-- Paciente 1 -->
                            <li class="list-group-item d-flex flex-column">
                                <strong>👤 João Silva</strong>
                                <div class="d-flex justify-content-between">
                                    📅 05/02/2025 - ⏰ 14:30
                                    <span class="badge bg-success">Confirmado</span>
                                </div>
                            </li>

                            <!-- Paciente 2 -->
                            <li class="list-group-item d-flex flex-column">
                                <strong>👤 Maria Oliveira</strong>
                                <div class="d-flex justify-content-between">
                                    📅 06/02/2025 - ⏰ 10:00
                                    <span class="badge bg-danger">Cancelado</span>
                                </div>
                            </li>

                            <!-- Paciente 3 -->
                            <li class="list-group-item d-flex flex-column">
                                <strong>👤 Carlos Santos</strong>
                                <div class="d-flex justify-content-between">
                                    📅 07/02/2025 - ⏰ 16:45
                                    <span class="badge bg-success">Confirmado</span>
                                </div>
                            </li>

                            <!-- Paciente 4 -->
                            <li class="list-group-item d-flex flex-column">
                                <strong>👤 Ana Souza</strong>
                                <div class="d-flex justify-content-between">
                                    📅 08/02/2025 - ⏰ 09:15
                                    <span class="badge bg-warning text-dark">Finalizado</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer text-end">
                        <button class="btn btn-primary">Ver Todos</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
