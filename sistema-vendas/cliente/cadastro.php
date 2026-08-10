<?php
session_start();

require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../helpers/validacoes.php";

if (estaAutenticado()) {
  header("Location: /web-sistema-vendas/");
  exit;
}

$pdo = getConexao();

$erros = [];
$dadosAntigos = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nome = trim($_POST["nome"] ?? "");
  $email = trim($_POST["email"] ?? "");
  $senha = trim($_POST["senha"] ?? "");

  if (strlen($nome) < 3) $erros["nome"] = "Nome deve ter no mínimo 3 caracteres";
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros["email"] = "Email inválido";
  if (strlen($senha) < 3) $erros["senha"] = "Senha deve ter no mínimo 3 caracteres";

  if (empty($erros)) {
    $senhaHash = password_hash($senha, PASSWORD_BCRYPT);

    try {
      $sql = "INSERT INTO cliente (nome, email, senha) VALUES (:nome, :email, :senha)";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        ":nome" => $nome,
        ":email" => $email,
        ":senha" => $senhaHash
      ]);

      $_SESSION["idCliente"] = $pdo->lastInsertId();

      header("Location: /web-2/sistema-vendas/");
      exit;
    } catch (PDOException $e) {
      die("Erro interno: " . $e->getMessage());
    }
  } else {
    $_SESSION["erros"] = $erros;
    $_SESSION["dadosAntigos"] = ["nome" => $nome, "email" => $email];
    header("Location: " . $_SERVER["REQUEST_URI"]);
    exit;
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
  <title>Cadastro</title>
</head>
<body>
  <form method="post">
    <div>
      <?php if (array_key_exists("nome", $erros)): ?>
        <p><?= htmlspecialchars($erros["nome"]) ?></p>
      <?php endif; ?>
      <label for="nome">Nome</label>
      <input type="text" id="nome" name="nome" value="<?= htmlspecialchars(
        $dadosAntigos['nome'] ?? ""
      ) ?>" autocomplete="off">
    </div>
    <div>
      <?php if (array_key_exists("email", $erros)): ?>
        <p><?= htmlspecialchars($erros["email"]) ?></p>
      <?php endif; ?>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" value="<?= htmlspecialchars(
        $dadosAntigos['email'] ?? ""
      ) ?>" autocomplete="off">
    </div>
    <div>
      <?php if (array_key_exists("senha", $erros)): ?>
        <p><?= htmlspecialchars($erros["senha"]) ?></p>
      <?php endif; ?>
      <label for="senha">Senha</label>
      <input type="password" id="senha" name="senha" autocomplete="off">
    </div>
    <button type="submit">Cadastrar</button>
  </form>
</body>
</html>