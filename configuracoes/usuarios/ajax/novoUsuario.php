<?php
  $caminho = "../../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "usuario" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(3,0,"Usuários")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $nomeUsuario = $_POST["nomeUsuario"];
  $loginUsuario = $_POST["loginUsuario"];
  $senhaUsuario = $_POST["senhaUsuario"];
  $confirmacaoSenhaUsuario = $_POST["confirmacaoSenhaUsuario"];
  $nascimentoUsuario = $_POST["nascimentoUsuario"];
  $permissaoUsuario = $_POST["permissaoUsuario"];

  $retorno = $factory->getObjeto("usuario")->novoUsuario($nomeUsuario, $loginUsuario, $senhaUsuario, $confirmacaoSenhaUsuario, $nascimentoUsuario, $permissaoUsuario);
  echo $retorno;
