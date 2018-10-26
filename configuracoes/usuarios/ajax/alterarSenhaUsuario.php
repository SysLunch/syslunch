<?php
  $caminho = "../../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "usuario" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(3,0,"Usuário")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $idUsuario = $_POST["idUsuario"];
  $senhaUsuario = $_POST["senha"];
  $confSenhaUsuario = $_POST["confirmacaoSenha"];
  $novaSenha = $_POST["novaSenha"];

  $retorno = $factory->getObjeto("usuario")->alterarSenhaUsuario($idUsuario, $senhaUsuario, $confSenhaUsuario, $novaSenha);

  echo $retorno;
