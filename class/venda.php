<?php

class Venda{


  function __construct(){

  }

  /*function editarLocal($idLocal, $nomeLocal){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM local WHERE idLocal = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $idLocal);
      $consulta->execute();
      if($consulta->rowCount() > 0){

        $sql = "SELECT * FROM local WHERE descricaoLocal = :p1 && idLocal != :p2;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $nomeLocal);
        $consulta->bindParam(":p2", $idLocal);
        $consulta->execute();
        if($consulta->rowCount() == 0){

          $sql = "UPDATE local SET descricaoLocal = :p1 WHERE idLocal = :p2;";
          $consultaUpdate = $conexao->prepare($sql);
          $consultaUpdate->bindParam(":p1", $nomeLocal);
          $consultaUpdate->bindParam(":p2", $idLocal);
          $consultaUpdate->execute();


          $sql = "SELECT * FROM local WHERE descricaoLocal = :p1 && idLocal = :p2;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $nomeLocal);
          $consulta->bindParam(":p2", $idLocal);
          $consulta->execute();
          if($consulta->rowCount() > 0){
            return json_encode(array("sucesso" => 1));
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer o cadastro novamente mais tarde."));
          }
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Há um outro local de mesmo nome cadastrado. Por favor, escolha outro nome."));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há esse local cadastrado.", "local" => 0));
      }
  }*/

  function novaVenda($idEmpresa){
      global $factory, $localPedidoInterno;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      if(!$factory->isSetted("empresa")){
        $factory->importaClasses(array("empresa" => 1));
      }

      if(!$factory->isSetted("localPedido")){
        $factory->importaClasses(array("localPedido" => 1));
      }

      if(!$factory->isSetted("login")){
        $factory->importaClasses(array("login" => 1));
      }

      $empresa = $factory->getObjeto("empresa")->buscaEmpresa($idEmpresa, 1);

      if($empresa["count"] > 0 || $idEmpresa == 0){
        $lPedido = $factory->getObjeto("localPedido")->buscaLocalporNome($localPedidoInterno);


        if($empresa["count"] > 0){
          $sql = "INSERT INTO pedido (idEmpresa, idSituacao, idLPedido, idUsuario) value (:p1, '1', :p2, :p3);";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $idEmpresa);
          $consulta->bindParam(":p2", $lPedido[consulta][0]["idLPedido"]);
          $consulta->bindParam(":p3", $factory->getObjeto("login")->getIdLogin());
          $consulta->execute();
        }else{
          $sql = "INSERT INTO pedido (idSituacao, idLPedido, idUsuario) value ('1', :p1, :p2);";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $lPedido[consulta][0]["idLPedido"]);
          $consulta->bindParam(":p2", $factory->getObjeto("login")->getIdLogin());
          $consulta->execute();
        }
        if($conexao->lastInsertId() > 0){
          return json_encode(array("sucesso" => 1, "id" => $conexao->lastInsertId(), "nomeEmpresa" => $empresa["consulta"][0]["nomeEmpresa"], "idLogin" => $factory->getObjeto("login")->getIdLogin()));
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer o cadastro novamente mais tarde."));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há uma empresa com esse ID cadastrada ou ativa."));
      }
  }

  function listaVendas(){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT p.*, f.dataFinanceiro FROM pedido p LEFT JOIN financeiro f ON (f.idPedido = p.idPedido);";
      $consulta = $conexao->prepare($sql);
      $consulta->execute();
      return $consulta->fetchAll();
      $retorno[0] = '';
      foreach($consulta->fetchAll() as $inf){

      }
  }

  function buscaVenda($id){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT p.*, u.nomeUsuario FROM pedido p INNER JOIN usuario u ON(u.idUsuario = p.idUsuario) WHERE p.idPedido = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $id);
      $consulta->execute();
      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }

  //Função de Rotina diária
  function cancelarPedidosPendentes(){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      if(!$factory->isSetted("situacao")){
        $factory->importaClasses(array("situacao" => 1));
      }

      $situacaoCancelado = $factory->getObjeto("situacao")->buscaSituacaoporNome("Cancelado pelo Sistema");

      $sql = "SELECT p.idPedido as idPedido FROM pedido p INNER JOIN situacao s ON (s.idSituacao = p.idSituacao) WHERE s.isFinal = 0;";
      $consulta = $conexao->prepare($sql);
      $consulta->execute();
      foreach($consulta->fetchAll() as $inf){
        $sql = "UPDATE pedido SET idSituacao = :p1 WHERE idPedido = :p2;";
        $consultaUpdate = $conexao->prepare($sql);
        $consultaUpdate->bindParam(":p1", $situacaoCancelado["consulta"][0]["idSituacao"]);
        $consultaUpdate->bindParam(":p2", $inf["idPedido"]);
        $consultaUpdate->execute();
      }
  }

  function hasTicket($idPedido){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    $sql = "SELECT count(*) FROM tickets WHERE idPedido = :p1;";
    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1", $idPedido);
    $consulta->execute();
    if($consulta->fetchAll()[0]["count(*)"] > 0){
      return true;
    }else{
      return false;
    }
  }


  /*function excluirLocal($idLocal){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $busca = self::buscaLocal($idLocal);
      if($busca["count"] == 1){

          $sql = "DELETE FROM local WHERE idLocal = :p1;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $idLocal);
          $consulta->execute();

          $busca = self::buscaLocal($idLocal);


          if($busca["count"] != 0){
            return array("sucesso" => 1);
          }else{
            return array("sucesso" => 0, "erro" => 1, "motivo" => "Algo aconteceu de errado. Por favor, tente mais tarde.");
          }
      }else{
        return array("sucesso" => 0, "erro" => 1, "motivo" => "O id informado não existe no banco de dados.");
      }

  }*/
}
