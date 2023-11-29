<?php

require_once('./vendor/autoload.php');



$database = new App\DB\DB();


// Testar a conexão
$conn = $database->getConnection();
if (!$conn) {
    echo "Erro ao conectar ao banco de dados.";
} else {
    $resultado = $database->read("SELECT * FROM tb_users");
    if ($resultado === false) {
        echo "Erro ao executar a consulta.";
    } else {
        echo json_encode($resultado);
    }
}

?>