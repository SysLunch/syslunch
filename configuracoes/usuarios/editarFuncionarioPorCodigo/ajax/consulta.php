<?php
  $caminho = "../../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("funcionario" => 1));

  $codigoBarras = $_GET["codigoBarras"];

  $retorno = $factory->getObjeto("funcionario")->buscaFuncionarioporCodigoJS($codigoBarras);

  echo $retorno;
