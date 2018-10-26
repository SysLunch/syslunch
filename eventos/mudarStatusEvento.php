<?php
  $caminho = "../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 6;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "empresa" => 1));
  $factory->getObjeto("login")->permissaoPagina(2,1,"Configurações");

  $idEmpresa = $_GET["id"];

  $retorno = $factory->getObjeto("empresa")->mudarStatusEmpresa($idEmpresa);

  if($retorno["sucesso"] == 1){
    header("location: index.php?sucesso=1");
  }else{
    header("location: index.php?e=".$retorno["motivo"]);
  }
