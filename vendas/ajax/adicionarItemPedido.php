<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "item" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(2,0,"Configurações")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $idFuncionario = $_GET["receptor"];
  $idTipoProduto = $_GET["idTipoProduto"];
  $idPedido = $_GET["idPedido"];
  $qtd = $_GET["qtd"];


  $retorno = $factory->getObjeto("item")->adicionarItemPedido($idPedido, $idTipoProduto, $qtd, $idFuncionario);

  echo $retorno;
