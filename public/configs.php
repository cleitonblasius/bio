<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\UtilsModel;

$utils = new UtilsModel();

// Obtém as cores atuais do banco
$config = $utils->getColorConfig();

$primaria = '#bfd7c5';
$secundaria = '#e4f1e7'; //background

// Se o formulário for enviado, atualiza as cores no banco
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $cor_primaria = $_POST['cor_primaria'];
//     $cor_secundaria = $_POST['cor_secundaria'];
//     $cor_fonte = $_POST['cor_fonte'];

//     $sql = "UPDATE bio_config SET cor_primaria = ?, cor_secundaria = ?, cor_fonte = ?";
//     $stmt = $pdo->prepare($sql);
//     $stmt->execute([$cor_primaria, $cor_secundaria, $cor_fonte]);

//     // Redireciona para evitar reenvio do formulário
//     header("Location: configuracao.php");
//     exit;
// }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personalizar Cores</title>
</head>
<body>

<h2>Personalizar Cores da Página</h2>
<form method="post">
    <label>Cor Primária:</label>
    <input type="color" name="cor_primaria" value="<?= $config['cor_primaria'] ?? $primaria; ?>" required><br><br>

    <label>Cor Secundária:</label>
    <input type="color" name="cor_secundaria" value="<?= $config['cor_secundaria'] ?? $secundaria ; ?>" required><br><br>

    <label>Cor da Fonte:</label>
    <input type="color" name="cor_fonte" value="<?= $config['cor_fonte'] ?? '#333333'; ?>" required><br><br>

    Adicionar:
    <ul>
        <li>Cor de fundo da seleçao</li>
        <li>cor de icones</li>
        <li>cor do card header</li>
        <li>cor dos botões de ação</li>
    </ul>

    <button type="submit">Salvar</button>
</form>

</body>
</html>
