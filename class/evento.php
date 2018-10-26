<?php

class Evento{


  function __construct(){

  }

  function editarEvento($idEvento, $nomeEvento, $tipoLetra){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      if(!$factory->isSetted("tipoLetra")){
        $factory->importaClasses(array("tipoLetra" => 1));
      }

      $consulta = self::buscaEvento($idEvento);
      if($consulta["count"] > 0){
        $consultaLetra = $factory->getObjeto("tipoLetra")->buscaLetras($tipoLetra);
        if(count($consultaLetra) > 0){

          $sqlUpdate = "UPDATE grupo SET nomeGrupo = :p1, idLetra = :p2, isGratuito = :p3, dataEdicao = :p4 WHERE idGrupo = :p5;";
          $consultaUpdate = $conexao->prepare($sqlUpdate);
          $consultaUpdate->bindParam(":p1", $nomeEvento);
          $consultaUpdate->bindParam(":p2", $tipoLetra);
          $consultaUpdate->bindParam(":p3", $consultaLetra[0]["isGratuito"]);
          $consultaUpdate->bindParam(":p4", date("Y-m-d H:i:s"));
          $consultaUpdate->bindParam(":p5", $idEvento);
          $consultaUpdate->execute();


          $sql = "SELECT * FROM grupo WHERE nomeGrupo = :p1 && idLetra = :p2 && idGrupo = :p3;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $nomeEvento);
          $consulta->bindParam(":p2", $tipoLetra);
          $consulta->bindParam(":p3", $idEvento);
          $consulta->execute();

          if($consulta->rowCount() > 0){
            return json_encode(array("sucesso" => 1));
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer a alteração de cadastro novamente mais tarde."));
          }
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há essa letra cadastrada. Por favor, escolha outra e tente novamente."));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há esse local cadastrado.", "local" => 0));
      }
  }

  function novoEvento($nomeEvento, $tipoLetra){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      if(!$factory->isSetted("tipoLetra")){
        $factory->importaClasses(array("tipoLetra" => 1));
      }

      $tokenEvento = hash("sha256", sha1($nomeEvento.rand(0,99).rand(1000,99999)));

      $consultaLetra = $factory->getObjeto("tipoLetra")->buscaLetras($tipoLetra);
      $consultaLetraCRT = $factory->getObjeto("tipoLetra")->buscaLetras(5);
      if(count($consultaLetra) > 0){



        do{
            $cartaoEvento = $consultaLetraCRT["consulta"][0]["letra"].rand(0,9).(time()-(rand(0,9)*date("s")));
            $sql = "SELECT * FROM reservaCodigos WHERE codigoReserva = :p1;";
            $consulta = $conexao->prepare($sql);
            $consulta->bindParam(":p1", $cartaoEvento);
            $consulta->execute();
            if($consulta->rowCount() == 0){
              $verCod = false;
            }else{
              sleep(1);
            }
        }while($verCod);


        $sql = "INSERT INTO grupo (nomeGrupo, tokenGrupo, dataCadastro, ativoGrupo, isGratuito, idLetra, codigoBarras, crt) value (:p1, :p2, :p3, '1', :p4, :p5, :p6, :p7);";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $nomeEvento);
        $consulta->bindParam(":p2", $tokenEvento);
        $consulta->bindParam(":p3", date("Y-m-d H:i:s"));
        $consulta->bindParam(":p4", $consultaLetra[0]["isGratuito"]);
        $consulta->bindParam(":p5", $tipoLetra);
        $consulta->bindParam(":p6", $cartaoEvento);
        $consulta->bindParam(":p7", $crt);
        $consulta->execute();
        if($conexao->lastInsertId() > 0){

          $sql = "INSERT INTO reservaCodigos (codigoReserva, dataCadastro, idGrupo) VALUES (:p1, :p2, :p3)";
          $consultaReserva = $conexao->prepare($sql);
          $consultaReserva->bindParam(":p1", $cartaoEvento);
          $consultaReserva->bindParam(":p2", date("Y-m-d H:i:s"));
          $consultaReserva->bindParam(":p3", $conexao->lastInsertId());
          $consultaReserva->execute();



          $sql = "SELECT * FROM reservaCodigos  WHERE codigoReserva = :p1;";
          $consultaReserva = $conexao->prepare($sql);
          $consultaReserva->bindParam(":p1", $cartaoEvento);
          $consultaReserva->execute();

          if($consultaReserva->rowCount() > 0){
            if($crt == 1){
              return json_encode(array("sucesso" => 1, "id" => $conexao->lastInsertId(), "nomeEvento" => $nomeEvento, "crt" => $crt));
            }else{
              return json_encode(array("sucesso" => 1, "id" => $conexao->lastInsertId(), "nomeEvento" => $nomeEvento));
            }
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer o cadastro novamente mais tarde. Erro EC-01"));
          }
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer o cadastro novamente mais tarde. Erro EC-02"));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há essa letra cadastrada. Por favor, escolha outra e tente novamente. Erro EC-03"));
      }
  }

  function listaEventos($ativo = 2){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();
      if($ativo == 2){
        $sql = "SELECT g.* FROM grupo g INNER JOIN reservaLetras rl ON (rl.idLetra = g.idLetra) WHERE rl.tipoGrupo = 2;";
        $consulta = $conexao->prepare($sql);
      }else{
        $sql = "SELECT g.* FROM grupo g INNER JOIN reservaLetras rl ON (rl.idLetra = g.idLetra) WHERE ativoGrupo = :p1 &&  rl.tipoGrupo = 2;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $ativo);
      }
      $consulta->execute();
      return $consulta->fetchAll();
  }

  function buscaEvento($id = NULL, $ativo = 2){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();
      if($ativo == 2){
        $sql = "SELECT g.*, letraReserva as letra, descricaoTReserva FROM grupo g INNER JOIN reservaLetras rl ON (rl.idLetra = g.idLetra) INNER JOIN tipoReserva tr ON(tr.idTipoReserva = rl.idTipoReserva) WHERE idGrupo = :p1;";
      }else{
        $sql = "SELECT g.*, letraReserva as letra, descricaoTReserva FROM grupo g INNER JOIN reservaLetras rl ON (rl.idLetra = g.idLetra) INNER JOIN tipoReserva tr ON(tr.idTipoReserva = rl.idTipoReserva) WHERE idGrupo = :p1 && ativoGrupo = :p2;";
      }
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $id);
      if($ativo != 2){
        $consulta->bindParam(":p2", $ativo);
      }
      $consulta->execute();
      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }


  function mudarStatusEvento($idEvento){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $busca = self::buscaEvento($idEvento);
      if($busca["count"] == 1){

          $status = ($busca["consulta"][0]["ativoGrupo"] == 1) ? 0 : 1;

          $desativacao = ($busca["consulta"][0]["ativoGrupo"] == 1) ? date("Y-m-d H:i:s") : NULL;

          $sql = "UPDATE grupo SET ativoGrupo = :p1, dataDesativacao = :p2 WHERE idGrupo = :p3;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $status);
          $consulta->bindParam(":p2", $desativacao);
          $consulta->bindParam(":p3", $idEvento);
          $consulta->execute();

          $busca = self::buscaEmpresa($idEvento);


          if($busca["consulta"][0]["statusEmpresa"] == $status){
            return array("sucesso" => 1);
          }else{
            return array("sucesso" => 0, "erro" => 1, "motivo" => "Algo aconteceu de errado. Por favor, tente mais tarde.");
          }
      }else{
        return array("sucesso" => 0, "erro" => 1, "motivo" => "O id informado não existe no banco de dados.");
      }

  }
}
