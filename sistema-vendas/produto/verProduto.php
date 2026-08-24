<?php
session_start();

require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../helpers/constantes.php";
require_once __DIR__ . "/../helpers/validacoes.php";

if (!estaAutenticado()) {
  header("Location: " . URL_BASE . "/usuario/login.php");
  exit;
}

if (!isAdmin()) {
  header("Location: " . URL_BASE . "/views-erros/404.php");
}

$pdo = getConexao();

$idProduto = $_GET["id"] ?? null;

if (!isset($idProduto)) {
  header("Location: " . URL_BASE . "/views-erros/404.php");
  exit;
}

try {
  $sql = "SELECT * FROM produto WHERE id_produto = :id_produto";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([":id_produto" => $idProduto]);
  $produto = $stmt->fetch();

  if (empty($produto)) {
    header("Location: " . URL_BASE . "/views-erros/404.php");
    exit;
  }
} catch (PDOException $e) {
  die("Erro interno: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($produto["nome"]) ?></title>
</head>
<body>
  <h1><?= htmlspecialchars($produto["nome"]) ?></h1>
  <h2>Descrição</h2>
  <p><?= isset($produto["descricao"]) ? htmlspecialchars($produto["descricao"]) : "O produto não tem descrição" ?></p>
  <button id="editar">Editar</button>
  <a href="./excluirProduto.php?id=<?= htmlspecialchars($produto["id_produto"]) ?>">Excluir</a>
  <form action="./atualizarProduto.php" method="post" id="form-editar" style="display: none;">
    <input type="hidden" name="id-produto" value="<?= htmlspecialchars($produto["id_produto"]) ?>">
    <input type="text" name="nome" placeholder="Nome" value="<?= htmlspecialchars($produto["nome"]) ?>">
    <textarea name="descricao" placeholder="Descrição"><?= htmlspecialchars($produto["descricao"]) ?></textarea>

    <label>Ativo</label>
    <input type="checkbox" name="ativo" <?= $produto["ativo"] ? "checked" : "" ?>>

    <button type="submit">Atualizar</button>
  </form>

<script>
  const botaoEditar = document.getElementById("editar");
  const formEditar = document.getElementById("form-editar")
  botaoEditar.addEventListener("click", () => {
    formEditar.style.display === "none" ? formEditar.style.display = "block" : formEditar.style.display = "none";
  })
</script>
</body>
</html>