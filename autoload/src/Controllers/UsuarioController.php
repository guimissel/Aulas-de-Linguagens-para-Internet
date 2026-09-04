<?php

class UsuarioController {
  public function __construct()
  {
    echo "UsuarioController";
  }

  public function chamarModel() {
    $model = new Usuario();
  }
}