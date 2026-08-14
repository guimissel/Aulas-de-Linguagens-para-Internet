<?php
function estaAutenticado(): bool {
  return isset($_SESSION["idCliente"]);
}
?>