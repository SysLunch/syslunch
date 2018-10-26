<?php

class Item{
  function __construct(){

  }

  function adicionarItemPedido($idPedido, $idTipoProduto, $qtd, $idFuncionario = null){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      if(!$factory->isSetted("venda")){
        $factory->importaClasses(array("venda" => 1));
      }
      if(!$factory->isSetted("funcionario")){
        $factory->importaClasses(array("funcionario" => 1));
      }
      if(!$factory->isSetted("tipoProduto")){
        $factory->importaClasses(array("tipoProduto" => 1));
      }

      $tipoProduto = $factory->getObjeto("tipoProduto")->buscaTipoProduto($idTipoProduto);
      if($tipoProduto["count"] == 0){
        return json_encode(array("sucesso" => "0", "erro" => 1, "motivo" => "Tipo de produto não encontrado."));
      }


      if($tipoProduto["consulta"][0]["isCartao"] == 1){
        if($idFuncionario == NULL || $idFuncionario == 0){
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "O tipo de produto selecionado obriga selecionar um funcionário receptor."));
        }
      }

      $valorTotal = $tipoProduto["consulta"][0]["valorUnitario"]*$qtd;

      if($idFuncionario != NULL){
        $funcionario = $factory->getObjeto("funcionario")->buscaFuncionario($idFuncionario);
        if($funcionario["count"] == 0){
          return json_encode(array("sucesso" => "0", "erro" => 1, "motivo" => "Funcionário não encontrado."));
        }
        $ja = self::carregaItemReceptor($idPedido, $idTipoProduto, $idFuncionario, true);
        if($ja){
          $sql = "UPDATE itensPedido SET quantidade = :p3, valorUnitario = :p4, valorTotal = :p5 WHERE idPedido = :p1 && idTipoProduto = :p2 && idFuncionario = :p6;";
          $isNew = false;
        }else{
          $sql = "INSERT INTO itensPedido (idPedido, idTipoProduto, quantidade, valorUnitario, valorTotal, idFuncionario) value (:p1, :p2, :p3, :p4, :p5, :p6);";
          $isNew = true;
        }
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1",$idPedido);
        $consulta->bindParam(":p2",$idTipoProduto);
        $consulta->bindParam(":p3",$qtd);
        $consulta->bindParam(":p4",$tipoProduto["consulta"][0]["valorUnitario"]);
        $consulta->bindParam(":p5",$valorTotal);
        $consulta->bindParam(":p6",$idFuncionario);
      }else{
        $ja = self::carregaItemReceptor($idPedido, $idTipoProduto, null, true);
        if($ja){
          $sql = "UPDATE itensPedido SET quantidade = :p3, valorUnitario = :p4, valorTotal = :p5 WHERE idPedido = :p1 && idTipoProduto = :p2;";
          $isNew = false;
        }else{
          $sql = "INSERT INTO itensPedido (idPedido, idTipoProduto, quantidade, valorUnitario, valorTotal) value (:p1, :p2, :p3, :p4, :p5);";
          $isNew = true;
        }
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1",$idPedido);
        $consulta->bindParam(":p2",$idTipoProduto);
        $consulta->bindParam(":p3",$qtd);
        $consulta->bindParam(":p4",$tipoProduto["consulta"][0]["valorUnitario"]);
        $consulta->bindParam(":p5",$valorTotal);
      }
      $consulta->execute();

      self::calculaTotal($idPedido);

      return json_encode(array("sucesso" => 1, "isNew" => $isNew));


  }

  function listaItensPedido($idPedido){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      if(!$factory->isSetted("venda")){
        $factory->importaClasses(array("venda" => 1));
      }
      if(!$factory->isSetted("funcionario")){
        $factory->importaClasses(array("funcionario" => 1));
      }
      if(!$factory->isSetted("tipoProduto")){
        $factory->importaClasses(array("tipoProduto" => 1));
      }

      $sql = "SELECT * FROM itensPedido WHERE idPedido = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $idPedido);
      $consulta->execute();

      $retorno[0] = '';
      $i = 0;
      foreach($consulta->fetchAll() as $inf){

        $array["quantidade"] = $inf["quantidade"];
        $array["valorUnitario"] = $inf["valorUnitario"];
        $array["valorTotal"] = $inf["valorTotal"];
        $array["idItem"] = $inf["idItem"];

        $tipoProduto = $factory->getObjeto("tipoProduto")->buscaTipoProduto($inf["idTipoProduto"]);
        //print_r($tipoProduto);
        if($tipoProduto["count"] > 0){
          $array["tipoProduto"] = $tipoProduto["consulta"][0]["descricaoProduto"];
          $array["idTipoProduto"] = $inf["idTipoProduto"];
          $array["tipoTransacao"] = $tipoProduto["consulta"][0]["tipoTransacao"];
          $array["isFree"] = $tipoProduto["consulta"][0]["isFree"];
        }

        if($inf["idFuncionario"] != NULL){
          $funcionario = $factory->getObjeto("funcionario")->buscaFuncionario($inf["idFuncionario"]);
          if($funcionario["count"] > 0){
            $array["idFuncionario"] = $inf["idFuncionario"];
            $array["funcionario"] = $funcionario["consulta"][0]["nomeFuncionario"];
          }
        }else{
          $array["funcionario"] = '';
        }

        $retorno[$i] = $array;
        $i++;
      }
      //print_r($retorno);
      return $retorno;
  }

  function carregaItemReceptor($idPedido, $idTipoProduto, $idFuncionario = null, $bool = false){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    if(!$factory->isSetted("venda")){
      $factory->importaClasses(array("venda" => 1));
    }
    if(!$factory->isSetted("funcionario")){
      $factory->importaClasses(array("funcionario" => 1));
    }
    if(!$factory->isSetted("tipoProduto")){
      $factory->importaClasses(array("tipoProduto" => 1));
    }

    if($idFuncionario != NULL){
      $sql = "SELECT * FROM itensPedido WHERE idPedido = :p1 && idTipoProduto = :p2 && idFuncionario = :p3;";
    }else{
      $sql = "SELECT * FROM itensPedido WHERE idPedido = :p1 && idTipoProduto = :p2;";
    }
    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1",$idPedido);
    $consulta->bindParam(":p2",$idTipoProduto);
    if($idFuncionario != NULL){
      $consulta->bindParam(":p3",$idFuncionario);
    }
    $consulta->execute();
    if($bool == false){
      $retorno["qtde"] = $consulta->fetchAll()[0]["quantidade"];
      return json_encode($retorno);
    }else{
      if($consulta->rowCount() > 0){
        return true;
      }else {
        return false;
      }
    }
  }

  function calculaTotal($idPedido){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    if(!$factory->isSetted("venda")){
      $factory->importaClasses(array("venda" => 1));
    }
    if(!$factory->isSetted("funcionario")){
      $factory->importaClasses(array("funcionario" => 1));
    }
    if(!$factory->isSetted("tipoProduto")){
      $factory->importaClasses(array("tipoProduto" => 1));
    }

    $sql = "SELECT * FROM itensPedido WHERE idPedido = :p1;";
    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1", $idPedido);
    $consulta->execute();

    $valTo = 0.00;
    foreach($consulta->fetchAll() as $inf){
      $valTo += $inf["valorTotal"];
    }

    $sql = "UPDATE pedido SET valorTotal = :p1 WHERE idPedido = :p2";
    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1", $valTo);
    $consulta->bindParam(":p2", $idPedido);
    $consulta->execute();
    return true;
  }

  function removeItem($idPedido, $idItem){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    if(!$factory->isSetted("venda")){
      $factory->importaClasses(array("venda" => 1));
    }
    if(!$factory->isSetted("funcionario")){
      $factory->importaClasses(array("funcionario" => 1));
    }
    if(!$factory->isSetted("tipoProduto")){
      $factory->importaClasses(array("tipoProduto" => 1));
    }

      $sql = "DELETE FROM itensPedido WHERE idItem = :p1;";
    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1",$idItem);
    $consulta->execute();

    self::calculaTotal($idPedido);

    return json_encode(array("sucesso" => 1));
  }

  function listaDadosPedido($idPedido){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    if(!$factory->isSetted("venda")){
      $factory->importaClasses(array("venda" => 1));
    }
    if(!$factory->isSetted("funcionario")){
      $factory->importaClasses(array("funcionario" => 1));
    }
    if(!$factory->isSetted("tipoProduto")){
      $factory->importaClasses(array("tipoProduto" => 1));
    }
    if(!$factory->isSetted("meioPagamento")){
      $factory->importaClasses(array("meioPagamento" => 1));
    }

    $sql = "SELECT * FROM pedido WHERE idPedido = :p1";
    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1", $idPedido);
    $consulta->execute();

    $fetch = $consulta->fetchAll()[0];

    $fetch["valorTotal2"] = $fetch["valorTotal"];
    $fetch["valorTotal"] = number_format($fetch["valorTotal"], 2, ",", "");


    $meio = $factory->getObjeto("meioPagamento")->listaMeios();
    $meios = "<optgroup label='Formas de Pagamento'></optgroup><option value=''>Selecione...</option>";
    foreach($meio as $inf){
      $meios .= "<option value='".$inf["idMeio"]."'>".$inf["meioPagamento"]."</option>";
    }

    $fetch["meio"] = $meios;

    return json_encode($fetch);
  }

  function finalizaPedido($idPedido, $valorPago, $meioPagamento){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    if(!$factory->isSetted("venda")){
      $factory->importaClasses(array("venda" => 1));
    }
    if(!$factory->isSetted("funcionario")){
      $factory->importaClasses(array("funcionario" => 1));
    }
    if(!$factory->isSetted("tipoProduto")){
      $factory->importaClasses(array("tipoProduto" => 1));
    }
    if(!$factory->isSetted("meioPagamento")){
      $factory->importaClasses(array("meioPagamento" => 1));
    }
    if(!$factory->isSetted("situacao")){
      $factory->importaClasses(array("situacao" => 1));
    }
    if(!$factory->isSetted("creditos")){
      $factory->importaClasses(array("creditos" => 1));
    }
    if(!$factory->isSetted("financeiro")){
      $factory->importaClasses(array("financeiro" => 1));
    }
    if(!$factory->isSetted("empresa")){
      $factory->importaClasses(array("empresa" => 1));
    }
    if(!$factory->isSetted("tipoLetra")){
      $factory->importaClasses(array("tipoLetra" => 1));
    }


    $sql = "SELECT * FROM pedido WHERE idPedido = :p1;";
    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1", $idPedido);
    $consulta->execute();


    //POG - Converter o tal do ponto flutuante BR para o US
    $valorPago = explode(",", $valorPago);
    $valorPago = $valorPago[0].".".$valorPago[1];
    $valorPago = floatval($valorPago);

    $fetch = $consulta->fetchAll()[0];

    if($valorPago >= $fetch["valorTotal"]){
      $troco = $valorPago - $fetch["valorTotal"];
      $situacao = $factory->getObjeto("situacao")->buscaSituacaoporNome("Pagamento Concluído");

      //print_r($situacao);

      $sql = "UPDATE pedido SET idMeioPagamento = :p1, idSituacao = :p2 WHERE idPedido = :p3";

      $consulta = $conexao->prepare($sql);

      $consulta->bindParam(":p1", $meioPagamento);
      $consulta->bindParam(":p2", $situacao['consulta'][0]["idSituacao"]);
      $consulta->bindParam(":p3", $idPedido);

      $consulta->execute();

      $itensPedido = self::listaItensPedido($idPedido);

      $empresa = $factory->getObjeto("empresa")->buscaEmpresa($fetch["idEmpresa"]);

      $letras = $factory->getObjeto("tipoLetra")->listaLetras(2, $empresa["consulta"][0]["tipoGrupo"]);
      // return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => '2, '.print_r($empresa)));

      foreach($letras as $inf){
        $j = $inf["isGratuito"];
        $letra[$j] = $inf["letraReserva"];
      }

      $hasTicket = false;
      foreach($itensPedido as $inf){
        if($inf["tipoTransacao"] == 1){
          for($i = 1; $i <= $inf["quantidade"]; $i++){

            $factory->getObjeto("creditos")->gerarNovoTicket($idPedido, $inf["isFree"], $letra[$inf["isFree"]]);
            $hasTicket = true;
          }
        }else{
          $factory->getObjeto("creditos")->novaMovimentacao(1, $inf["idFuncionario"], $inf["quantidade"], $idPedido);
        }
      }

      $factory->getObjeto("financeiro")->novaMovimentacao($idPedido, $fetch["valorTotal"]);

      $situacao = $factory->getObjeto("situacao")->buscaSituacaoporNome("Finalizado");

      //print_r($situacao);

      $sql = "UPDATE pedido SET idSituacao = :p1 WHERE idPedido = :p2";

      $consulta = $conexao->prepare($sql);

      $consulta->bindParam(":p1", $situacao['consulta'][0]["idSituacao"]);
      $consulta->bindParam(":p2", $idPedido);

      $consulta->execute();




      $sql = "SELECT * FROM pedido WHERE idPedido = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $idPedido);
      $consulta->execute();

      if($consulta->fetchAll()[0]["idSituacao"] == $situacao['consulta'][0]["idSituacao"]){
        $array["sucesso"] = 1;
        if($troco > 0){
          $array["troco"] = number_format($troco, 2, ",", "");
        }else{
          $array["troco"] = 0;
        }
        $array["hasTicket"] = $hasTicket;
        return json_encode($array);
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer o cadastro novamente mais tarde."));
      }

    }else{
      return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "O Valor pago é menor que o valor total do pedido."));
    }
  }
}
