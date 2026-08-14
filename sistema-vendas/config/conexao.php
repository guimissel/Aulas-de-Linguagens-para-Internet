<?php
function getConexao() {
  $host = "localhost";
  $dbname = "web2-vendas";
  $usuario = "root";
  $senha = "";
  $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

  try {
    $pdo = new PDO($dsn, $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    return $pdo;
  } catch (PDOException $e) {
    die("Erro de conexão com o banco.");
  }
}
?>