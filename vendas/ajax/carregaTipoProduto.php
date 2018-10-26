<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "tipoProduto" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(3,0,"Configurações")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $idTipoProduto = $_GET["idTipoProduto"];

  $retorno = $factory->getObjeto("tipoProduto")->buscaTipoProduto($idTipoProduto);
  if($retorno["count"] > 0){
    echo json_encode(array("sucesso" => 1, "valorUnitario" => $retorno["consulta"][0]["valorUnitario"], "descricaoProduto" => $retorno["consulta"][0]["descricaoProduto"], "tipoTransacao" => $retorno["consulta"][0]["tipoTransacao"], "isCartao" => $retorno["consulta"][0]["isCartao"], "isFree" => $retorno["consulta"][0]["isFree"]));
  }else{
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há registros de Tipo de Produto com esse código."));
  }
