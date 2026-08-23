<?php
session_start();

require_once __DIR__ . "/../config/conexao.php";

$pdo = getConexao();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $id = (int) $_POST["id-produto"] ?? null;
  $nome = trim($_POST["nome"] ?? "");
  $preco = trim($_POST["preco"] ?? "");
  $ativo = $_POST["ativo"] ?? false;

  $erros = [];

  if (!isset($id)) $erros["id"][] = "ID do produto inválido";

  $sqlSelect = "SELECT * FROM produto WHERE id_produto = :id";
  $stmtSelect = $pdo->prepare($sql);
  $stmtSelect->execute([":id" => $id]);
  if (empty($stmtSelect->fetch())) $erros["id"][] = "O produto não existe";

  if (empty($nome)) $erros["nome"] = "O nome do produto não pode estar vazio";
  if (empty($preco)) $erros["preco"][] = "Preço vazio";
  
  $preco = str_replace(",", ".", $preco);

  if (filter_var($preco, FILTER_VALIDATE_FLOAT, ["options" => ["min_range" => 0.01]]) === false) {
    $erros["preco"][] = "Preço deve ser maior que 0.01";
  }

  if (empty($erros)) {
    $sql = "UPDATE produto SET nome = :nome, preco = :preco, ativo = :ativo WHERE id_produto = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ":nome" => $nome,
      ":preco" => $preco,
      ":ativo" => $ativo,
      ":id" => $id
    ]);
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