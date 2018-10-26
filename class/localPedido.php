<?php

class LocalPedido{


  function __construct(){

  }

  function editarLocal($idLocal, $nomeLocal){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM localPedido WHERE idLPedido = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $idLocal);
      $consulta->execute();
      if($consulta->rowCount() > 0){

        $sql = "SELECT * FROM localPedido WHERE nomeLocal = :p1 && idLPedido != :p2;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $nomeLocal);
        $consulta->bindParam(":p2", $idLocal);
        $consulta->execute();
        if($consulta->rowCount() == 0){

          $sql = "UPDATE localPedido SET nomeLocal = :p1, dataEdicao = :p2 WHERE idLPedido = :p3;";
          $consultaUpdate = $conexao->prepare($sql);
          $consultaUpdate->bindParam(":p1", $nomeLocal);
          $consultaUpdate->bindParam(":p2", date("Y-m-d H:i:s"));
          $consultaUpdate->bindParam(":p3", $idLocal);
          $consultaUpdate->execute();


          $sql = "SELECT * FROM localPedido WHERE nomeLocal = :p1 && idLPedido = :p2;";
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
  }

  function novoLocal($nomeLocal){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM localPedido WHERE nomeLocal = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $nomeLocal);
      $consulta->execute();
      if($consulta->rowCount() == 0){
        $sql = "INSERT INTO localPedido (nomeLocal, dataCadastro) value (:p1, :p2);";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $nomeLocal);
        $consulta->bindParam(":p2", date("Y-m-d H:i:s"));
        $consulta->execute();
        if($conexao->lastInsertId() > 0){
          return json_encode(array("sucesso" => 1, "id" => $conexao->lastInsertId(), "nomeLocal" => $nomeLocal));
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer o cadastro novamente mais tarde."));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Já há um local com o mesmo nome."));
      }
  }

  function listaLocais(){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM localPedido;";
      $consulta = $conexao->prepare($sql);
      $consulta->execute();
      return $consulta->fetchAll();
  }

  function buscaLocal($id){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM localPedido WHERE idLPedido = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $id);
      $consulta->execute();
      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }
  function buscaLocalporNome($descricaoLocal){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM localPedido WHERE nomeLocal = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $descricaoLocal);
      $consulta->execute();
      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }


  function excluirLocal($idLocal){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $busca = self::buscaLocal($idLocal);
      if($busca["count"] == 1){

          $sql = "DELETE FROM localPedido WHERE idLPedido = :p1;";
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

  }
}
