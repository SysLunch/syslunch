<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "item" => 1, "funcionario" => 1, "venda" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(2,0,"Configurações")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $retorno = $factory->getObjeto("item")->carregaItemReceptor($_GET["idPedido"], $_GET["idTipoProduto"], $_GET["receptor"]); 

  echo $retorno;
