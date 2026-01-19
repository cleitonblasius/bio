<?php

use App\Models\AtendimentoModel;

require_once(__DIR__ . '/../../src/config/Database.php');
require_once(__DIR__ . '/../../src/models/BaseModel.php');
require_once(__DIR__ . '/../../src/models/AtendimentoModel.php');

$model = new AtendimentoModel();
return $model->getParesBiomagneticos();

/* return [
    "R1" => [
        "classificacao" => "Reservatório universal",
        "par" => "Cápsula renal D/E - Rim Ips",
        "patogeno" => "Reservatório HIV",
        "diagnostico" => "Checar rins, disfunção renal."
    ],
    "R2" => [
        "classificacao" => "Reservatório exemplo",
        "par" => "Exemplo D/E - Toba",
        "patogeno" => "Pato Geno",
        "diagnostico" => "Sei lá, não sou magnetista."
    ],
    "R3" => [
        "classificacao" => "Reservatório intermediário",
        "par" => "Pulmão D/E - Fígado",
        "patogeno" => "Bactéria simbiótica",
        "diagnostico" => "Checar sistema respiratório."
    ]
    // ... até R300
];
 */