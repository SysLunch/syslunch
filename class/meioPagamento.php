<?php

class MeioPagamento{


  function __construct(){

  }

  function editarMeio($idMeio, $meioPagamento, $url = null){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM meioPagamento WHERE idMeio = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $idMeio);
      $consulta->execute();
      if($consulta->rowCount() > 0){

        $sql = "SELECT * FROM meioPagamento WHERE meioPagamento = :p1 && idMeio != :p2;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $meioPagamento);
        $consulta->bindParam(":p2", $idMeio);
        $consulta->execute();
        if($consulta->rowCount() == 0){

          $sql = "UPDATE meioPagamento SET meioPagamento = :p1, actionPagamento = :p2, dataEdicao = :p3 WHERE idMeio = :p4;";
          $consultaUpdate = $conexao->prepare($sql);
          $consultaUpdate->bindParam(":p1", $meioPagamento);
          $consultaUpdate->bindParam(":p2", $url);
          $consultaUpdate->bindParam(":p3", date("Y-m-d H:i:s"));
          $consultaUpdate->bindParam(":p4", $idMeio);
          $consultaUpdate->execute();


          $sql = "SELECT * FROM meioPagamento WHERE meioPagamento = :p1 && actionPagamento = :p2 && idMeio = :p3;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $meioPagamento);
          $consulta->bindParam(":p2", $url);
          $consulta->bindParam(":p3", $idMeio);
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
  }

  function novoMeio($meioPagamento, $url = null){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM meioPagamento WHERE meioPagamento = :p1 && actionPagamento = :p2;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $meioPagamento);
      $consulta->bindParam(":p2", $url);
      $consulta->execute();
      if($consulta->rowCount() == 0){
        $sql = "INSERT INTO meioPagamento (meioPagamento, actionPagamento, dataCadastro) value (:p1, :p2, :p3);";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $meioPagamento);
        $consulta->bindParam(":p2", $url);
        $consulta->bindParam(":p3", date("Y-m-d H:i:s"));
        $consulta->execute();
        if($conexao->lastInsertId() > 0){
          return json_encode(array("sucesso" => 1, "id" => $conexao->lastInsertId(), "nomeMeio" => $meioPagamento));
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer o cadastro novamente mais tarde."));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Já há um local com o mesmo nome."));
      }
  }

  function listaMeios(){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM meioPagamento;";
      $consulta = $conexao->prepare($sql);
      $consulta->execute();
      return $consulta->fetchAll();
  }

  function buscaMeio($id){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM meioPagamento WHERE idMeio = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $id);
      $consulta->execute();
      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }


  function excluirMeio($idMeio){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $busca = self::buscaMeio($idMeio);
      if($busca["count"] == 1){

          $sql = "DELETE FROM meioPagamento WHERE idMeio = :p1;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $idMeio);
          $consulta->execute();

          $busca = self::buscaMeio($idMeio);


          if($busca["count"] != 0){
            return array("sucesso" => 1);
          }else{
            return array("sucesso" => 0, "erro" => 1, "motivo" => "Algo aconteceu de errado. Por favor, tente mais tarde.");
          }
      }else{
        return array("sucesso" => 0, "erro" => 1, "motivo" => "O id informado não existe no banco de dados.");
      }

  }
}
