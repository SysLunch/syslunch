<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "venda" => 1, "item" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(2,0,"Configurações")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $idPedido = $_GET["idPedido"];
  $idItem = $_GET["idItem"];

  $retorno = $factory->getObjeto("item")->removeItem($idPedido, $idItem);

  echo $retorno;
