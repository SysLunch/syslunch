<?php
  include("../../parametros.php");
  include("../../class/factory.php");
  $caminho = "../../";

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1));

  $login = $_POST["login"];
  $senha = $_POST["senha"];
  //echo $login, $senha;

  $factory->getObjeto("login")->setLogin($login);
  $factory->getObjeto("login")->setSenha($senha);
  $logar = $factory->getObjeto("login")->efetuarLogin();
  echo json_encode($logar);
