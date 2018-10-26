<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("creditos" => 1));

  $idCRT = $_GET["idCRT"];
  $codigoBarras = $_GET["codigoBarras"];
  $remoteLogin = ($_GET["remoteLogin"]) ? $_GET["remoteLogin"] : NULL;

  $retorno = $factory->getObjeto("creditos")->consulta($idCRT, $codigoBarras, $remoteLogin);

  echo $retorno;
