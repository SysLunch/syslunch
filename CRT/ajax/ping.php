<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("creditos" => 1));

  $idCRT = $_GET["idCRT"];

  $retorno = $factory->getObjeto("creditos")->ping($idCRT);

  echo $retorno;
