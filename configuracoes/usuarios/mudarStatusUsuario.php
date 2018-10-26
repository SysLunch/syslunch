<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 5;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "usuario" => 1));
  $factory->getObjeto("login")->permissaoPagina(3,1,"Usuário");

  $idUsuario = $_GET["id"];

  $retorno = $factory->getObjeto("usuario")->mudarStatusUsuario($idUsuario);

  if($retorno["sucesso"] == 1){
    header("location: index.php?sucesso=1");
  }else{
    header("location: index.php?e=".$retorno["motivo"]);
  }
