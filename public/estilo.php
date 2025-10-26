<?php
header("Content-type: text/css");

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\UtilsModel;

$config = new UtilsModel();
?>

/* CSS Gerado Dinamicamente */
body {
    background-color: <?php echo $config['cor_primaria']; ?>;
    color: <?php echo $config['cor_fonte']; ?>;
}

button {
    background-color: <?php echo $config['cor_secundaria']; ?>;
    color: white;
    padding: 10px;
    border: none;
    cursor: pointer;
}

button:hover {
    opacity: 0.8;
}
