<?php 

$host = "sql308.infinityfree.com";
$port = "3306";
$dbname = "if0_41206004_world_colors";
$user = "if0_41206004";
$password = "4O69PuUxJe";

$conexao = new mysqli($host, $user, $password, $dbname, $port);
$conexao->set_charset("utf8mb4");

if ($conexao->connect_error) {
  error_log("Erro na conecxão á base de dados.\n" . $conexao->connect_error);
  die("Erro na ligação");
}

?>
