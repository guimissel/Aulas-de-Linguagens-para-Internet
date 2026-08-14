<?php
session_start();

require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../helpers/validacoes.php";

if (!estaAutenticado()) {
  header("Location: /web-2/sistema-vendas/usuario/login.php");
  exit;
}

if (!isAdmin()) {
  header("Location: /web-2/sistema-vendas/views-erros/404.php");
  exit;
}

$pdo = getConexao();
$erros = [];
$dadosAntigos = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nome = trim($_POST["nome"] ?? "");
  $descricao = trim($_POST["descricao"] ?? "");

  if (empty($nome)) $erros["nome"] = "O nome não pode ser vazio";
  
  if (empty($erros)) {
    try {
      $sql = "INSERT INTO produto (nome, descricao) VALUES (:nome, :descricao)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ":nome" => $nome,
        ":descricao" => empty($descricao) ? null : $descricao
      ]);
    } catch (PDOException $e) {
      die("Erro interno: " . $e->getMessage());
    }
  }
} else {
  if (!empty($_SESSION["erros"])) {
    $erros = $_SESSION["erros"];
    unset($_SESSION["erros"]);
  }
  if (!empty($_SESSION["dadosAntigos"])) {
    $dadosAntigos = $_SESSION["dadosAntigos"];
    unset($_SESSION["dadosAntigos"]);
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cadastrar Produto</title>
</head>
<body>
  <h1>Cadastrar Produto</h1>

  <form method="post">
    <div>
      <label for="nome">Nome</label>
      <input type="text" name="nome" id="nome" autocomplete="off">
    </div>
    <div>
      <label for="descricao">Descrição</label>
      <textarea name="descricao" id="descricao" autocomplete="off"></textarea>
    </div>
    
    <button type="submit">Cadastrar</button>
  </form>
</body>
</html>