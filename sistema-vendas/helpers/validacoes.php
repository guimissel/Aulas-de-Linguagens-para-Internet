<?php
function estaAutenticado(): bool {
  return isset($_SESSION["idUsuario"]);
}

function isAdmin(): bool {
  return $_SESSION["papel"] === 1;
}

function isCliente(): bool {
  return $_SESSION["papel"] === 2;
}
?>