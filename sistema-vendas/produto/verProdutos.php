<?php
session_start();

require_once __DIR__ . "/../config/conexao.php";

$pdo = getConexao();

$sql = "SELECT * FROM produto";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$produtos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Produtos</title>
</head>
<body>
  <h1>Produtos</h1>
  <main>
    <?php foreach ($produtos as $produto): ?>
      <a href="./verProduto.php?id=<?= htmlspecialchars($produto["id_produto"]) ?>">
        <span><?= htmlspecialchars($produto["nome"]) ?></span>
        <span><?= $produto["ativo"] === 1 ? "Ativo" : "Desativado" ?></span>
      </a>
    <?php endforeach; ?>
  </main>
</body>
</html>