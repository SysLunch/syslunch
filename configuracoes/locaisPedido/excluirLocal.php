<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "localPedido" => 1));
  $factory->getObjeto("login")->permissaoPagina(3,1,"Configurações");

  $idLocal = $_GET["id"];

  $retorno = $factory->getObjeto("localPedido")->excluirLocal($idLocal);

  if($retorno["sucesso"] == 1){
    header("location: index.php?sucesso=1");
  }else{
    header("location: index.php?e=".$retorno["motivo"]);
  }
