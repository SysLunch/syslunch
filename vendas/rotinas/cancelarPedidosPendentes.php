<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "venda" => 1));

  $retorno = $factory->getObjeto("venda")->cancelarPedidosPendentes();

  echo $retorno;
