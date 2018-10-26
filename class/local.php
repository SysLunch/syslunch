<?php

class Local{


  function __construct(){

  }

  function editarLocal($idLocal, $nomeLocal){
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

          $sql = "UPDATE local SET descricaoLocal = :p1, dataEdicao = :p2 WHERE idLocal = :p3;";
          $consultaUpdate = $conexao->prepare($sql);
          $consultaUpdate->bindParam(":p1", $nomeLocal);
          $consultaUpdate->bindParam(":p2", date("Y-m-d H:i:s"));
          $consultaUpdate->bindParam(":p3", $idLocal);
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
  }

  function novoLocal($nomeLocal){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM local WHERE descricaoLocal = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $nomeLocal);
      $consulta->execute();

      if($consulta->rowCount() == 0){

        $verCod = true;
        while($verCod){
          $hash = "C".rand(0,9).(time()-(rand(0,9)*date("s")));
            $sql = "SELECT * FROM local WHERE hashLocal = :p1;";
            $consulta = $conexao->prepare($sql);
            $consulta->bindParam(":p1", $hash);
            $consulta->execute();
            if($consulta->rowCount() == 0){
              $verCod = false;
            }else{
              sleep(1);
            }
        }


        $sql = "INSERT INTO local (descricaoLocal, hashLocal, dataCadastro) value (:p1, :p2, :p3);";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $nomeLocal);
        $consulta->bindParam(":p2", $hash);
        $consulta->bindParam(":p3", date("Y-m-d H:i:s"));
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

      $sql = "SELECT * FROM local;";
      $consulta = $conexao->prepare($sql);
      $consulta->execute();
      return $consulta->fetchAll();
  }

  function buscaLocal($id){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM local WHERE idLocal = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $id);
      $consulta->execute();
      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }

  function buscaLocalporCodigo($codigoBarras){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM local WHERE hashLocal = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $codigoBarras);
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

  }

  function pingLocal($idLocal){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    $sql = "SELECT * FROM local WHERE idLocal = :p1;";
    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1", $idLocal);
    $consulta->execute();
    if($consulta->rowCount() > 0){

        $now = date("Y-m-d H:i:s");
        $sql = "UPDATE local SET ultimaConexao = :p1 WHERE idLocal = :p2;";

        $consultaUpdate = $conexao->prepare($sql);
        $consultaUpdate->bindParam(":p1", $now);
        $consultaUpdate->bindParam(":p2", $idLocal);
        $consultaUpdate->execute();


        $sql = "SELECT * FROM local WHERE ultimaConexao = :p1 && idLocal = :p2;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $now);
        $consulta->bindParam(":p2", $idLocal);
        $consulta->execute();
        if($consulta->rowCount() > 0){
          return json_encode(array("sucesso" => 1));
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "codigo" => "LP-001"));
        }
    }else{
      return json_encode(array("sucesso" => 0, "erro" => 1, "codigo" => "LP-002"));
    }
  }
}
