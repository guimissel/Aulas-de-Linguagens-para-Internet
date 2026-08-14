<?php
session_start();

require_once __DIR__ . '/../config/conexao.php';
require_once __DIR__ . "/../helpers/validacoes.php";

if (estaAutenticado()) {
  header("Location: /web-2/sistema-vendas/");
  exit;
}

$erros = [];
$dadosAntigos = [];

$pdo = getConexao();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = trim($_POST["email"] ?? "");
  $senha = trim($_POST["senha"] ?? "");

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros["email"] = "Email inválido";
  if (strlen($senha) < 3) $erros["senha"] = "Senha deve ter no mínimo 3 caracteres";

  if (empty($erros)) {
    try {
      $sql = "SELECT id_usuario, email, senha, id_papel FROM usuario WHERE email = :email";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([":email" => $email]);
      $usuario = $stmt->fetch();
  
      if (empty($usuario)) {
        $erros["naoEncontrado"] = "Email ou senha incorretos";
      } elseif (!password_verify($senha, $usuario["senha"])) {
        $erros["naoEncontrado"] = "Email ou senha incorretos";
      } else {
        $_SESSION["idUsuario"] = $usuario["id_usuario"];
        $_SESSION["papel"] = $usuario["id_papel"];
        
        header("Location: /web-2/sistema-vendas/");
        exit;
      }

      $_SESSION["erros"] = $erros;
      $_SESSION["dadosAntigos"] = ["email" => $email];
      header("Location: " . $_SERVER["REQUEST_URI"]);
      exit;
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
  <title>Login</title>
</head>
<body>
  <form method="post">
    <div>
      <?php if (array_key_exists("email", $erros)): ?>
        <p><?= htmlspecialchars($erros["email"]) ?></p>
      <?php endif; ?>
      <label for="email">Email</label>
      <input type="email" id="email" name="email" autocomplete="off" value="<?= htmlspecialchars($dadosAntigos['email'] ?? "") ?>">
    </div>
    <div>
      <?php if (array_key_exists("senha", $erros)): ?>
        <p><?= htmlspecialchars($erros["senha"]) ?></p>
        <?php endif; ?>
        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" autocomplete="off">
        <?php if (array_key_exists("naoEncontrado", $erros)): ?>
          <p><?= htmlspecialchars($erros["naoEncontrado"]) ?></p>
        <?php endif; ?>
    </div>
    <button type="submit">Entrar</button>
  </form>
</body>
</html>