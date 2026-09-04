<?php
session_start();

require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../helpers/constantes.php";

$pdo = getConexao();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = isset($_POST["id-produto"]) ? (int) $_POST["id-produto"] : null;
  $nome = trim($_POST["nome"] ?? "");
  $descricao = trim($_POST["descricao"] ?? "");
  $ativo = isset($_POST["ativo"]);

  $erros = [];

  if (!isset($id)) $erros["id"][] = "ID do produto inválido";

  $sqlSelect = "SELECT * FROM produto WHERE id_produto = :id";
  $stmtSelect = $pdo->prepare($sqlSelect);
  $stmtSelect->execute([":id" => $id]);
  if (empty($stmtSelect->fetch())) $erros["id"][] = "O produto não existe";

  if (empty($nome)) $erros["nome"] = "O nome do produto não pode estar vazio";

  if (empty($erros)) {
    $sql = "UPDATE produto SET nome = :nome, descricao = :descricao, ativo = :ativo WHERE id_produto = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ":nome" => $nome,
      ":descricao" => $descricao,
      ":ativo" => $ativo,
      ":id" => $id
    ]);

    header("Location: " . URL_BASE . "/produto/verProduto.php?id=$id");
    exit;
  } else {
    $_SESSION["erros"] = $erros;
  }
} else {
  if (!empty($erros)) {
    $erros = $_SESSION["erros"];
    unset($_SESSION["erros"]);
  }
  if (!empty($dados)) {
    $dados = $_SESSION["dadosAntigos"];
    unset($_SESSION["dadosAntigos"]);
  }
}
?>