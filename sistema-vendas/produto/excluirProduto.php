<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../helpers/constantes.php";

$pdo = getConexao();

$idProduto = $_GET["id"];

$sql = "DELETE FROM produto WHERE id_produto = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([":id" => $idProduto]);

header("Location: " . URL_BASE . "/produto/verProdutos.php");
exit;