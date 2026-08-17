<?php
session_start();

require_once __DIR__ . "/helpers/constantes.php";
require_once __DIR__ . "/helpers/validacoes.php";

if (!estaAutenticado()) {
  header("Location: " . URL_BASE . "/usuario/login.php");
  exit;
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produtos</title>
</head>
<body>
  
</body>
</html>