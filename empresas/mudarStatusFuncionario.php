<?php
  $caminho = "../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 3;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "funcionario" => 1, "empresa" => 1));
  $factory->getObjeto("login")->permissaoPagina(2,1,"Empresa");

  $idFuncionario = $_GET["id"];

  $retorno = $factory->getObjeto("funcionario")->mudarStatusFuncionario($idFuncionario);

  if($retorno["sucesso"] == 1){
    header("location: indexFuncionarios.php?sucesso=1&id=".$retorno["idEmpresa"]);
  }else{
    header("location: indexFuncionarios.php?e=".$retorno["motivo"]."&id=".$retorno["idEmpresa"]);
  }
