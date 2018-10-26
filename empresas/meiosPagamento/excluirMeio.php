<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "meioPagamento" => 1));

  $idMeio = $_GET["id"];

  $retorno = $factory->getObjeto("meioPagamento")->excluirMeio($idMeio);

  if($retorno["sucesso"] == 1){
    header("location: index.php?sucesso=1");
  }else{
    header("location: index.php?e=".$retorno["motivo"]);
  }
