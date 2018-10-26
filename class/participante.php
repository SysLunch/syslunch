<?php

class Participante{


  function __construct(){

  }

  function editarParticipante($idParticipante, $nomeParticipante, $nascimentoParticipante){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $consultaParticipante = self::buscaParticipante($idParticipante);
      if($consultaParticipante["count"] > 0){

        $sql = "SELECT * FROM pessoa WHERE nomePessoa = :p1 && nascimentoPessoa = :p2 && idPessoa != :p3 && idEmpresa = :p4;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $nomeParticipante);
        $consulta->bindParam(":p2", $nascimentoParticipante);
        $consulta->bindParam(":p3", $idParticipante);
        $consulta->bindParam(":p4", $consultaParticipante[0]["idGrupo"]);
        $consulta->execute();
        $consultaCount = $consulta->rowCount();
        // echo $nomeParticipante, $nascimentoParticipante, $idParticipante, $consultaParticipante[0]["idGrupo"];
        if($consultaCount == 0){

          $sqlUpdate = "UPDATE pessoa SET nomePessoa = :p1, nascimentoPessoa = :p2, dataEdicao = :p3 WHERE idPessoa = :p4;";
          $consultaUpdate = $conexao->prepare($sqlUpdate);
          $consultaUpdate->bindParam(":p1", $nomeParticipante);
          $consultaUpdate->bindParam(":p2", $nascimentoParticipante);
          $consultaUpdate->bindParam(":p3", date("Y-m-d H:i:s"));
          $consultaUpdate->bindParam(":p4", $idParticipante);
          $consultaUpdate->execute();


          $sql = "SELECT * FROM pessoa WHERE nomePessoa = :p1 && nascimentoPessoa = :p2 && idPessoa = :p3;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $nomeParticipante);
          $consulta->bindParam(":p2", $nascimentoParticipante);
          $consulta->bindParam(":p3", $idParticipante);
          $consulta->execute();

          if($consulta->rowCount() > 0){
            return json_encode(array("sucesso" => 1));
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer a alteração de cadastro novamente mais tarde. ".$consultaCount));
          }
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Há um outro participante de mesmo nome cadastrado. Por favor, escolha outro nome."));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há esse participante cadastrado.", "local" => 0));
      }
  }


  function novoParticipante($nomeParticipante, $nascimentoParticipante, $idEvento){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM pessoa WHERE nomePessoa = :p1 && nascimentoPessoa = :p2 && idEmpresa = :p3;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $nomeParticipante);
      $consulta->bindParam(":p2", $nascimentoParticipante);
      $consulta->bindParam(":p3", $idEmpresa);
      $consulta->execute();

      if($consulta->rowCount() == 0){

        if(!$factory->isSetted("evento")){
          $factory->importaClasses(array("evento" => 1));
        }

        $consulta = $factory->getObjeto("evento")->buscaEvento($idEvento);

        if($consulta["count"] != 0){




          $verCod = true;
          do{
              $cartaoParticipante = $consulta["consulta"][0]["letra"].rand(0,9).(time()-(rand(0,9)*date("s")));
              $sql = "SELECT * FROM reservaCodigos WHERE codigoReserva = :p1;";
              $consulta = $conexao->prepare($sql);
              $consulta->bindParam(":p1", $cartaoParticipante);
              $consulta->execute();
              if($consulta->rowCount() == 0){
                $verCod = false;
              }else{
                sleep(1);
              }
          }while($verCod);


          $sql = "INSERT INTO pessoa (nomePessoa, nascimentoPessoa, ativoPessoa, codigoCartao, idEmpresa, saldo, dataCadastro) value (:p1, :p2, '1', :p3, :p4, '0', :p5);";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $nomeParticipante);
          $consulta->bindParam(":p2", $nascimentoParticipante);
          $consulta->bindParam(":p3", $cartaoParticipante);
          $consulta->bindParam(":p4", $idEvento);
          $consulta->bindParam(":p5", date("Y-m-d H:i:s"));
          $consulta->execute();

          $sql = "SELECT idPessoa FROM pessoa WHERE codigoCartao = :p1;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $cartaoParticipante);
          $consulta->execute();

          $retornoConsulta = $consulta->fetchAll();

          if(count($retornoConsulta) > 0){

              $sql = "INSERT INTO reservaCodigos (codigoReserva, idFuncionario, dataCadastro) values (:p1, :p2, :p3)";
              $consulta = $conexao->prepare($sql);
              $consulta->bindParam(":p1", $cartaoParticipante);
              $consulta->bindParam(":p2", $retornoConsulta[0]["idFuncionario"]);
              $consulta->bindParam(":p3", date("Y-m-d H:i:s"));
              $consulta->execute();

              $sql = "SELECT count(*) FROM reservaCodigos WHERE codigoReserva = :p1 && idFuncionario = :p2;";
              $consulta = $conexao->prepare($sql);
              $consulta->bindParam(":p1", $cartaoParticipante);
              $consulta->bindParam(":p2", $retornoConsulta[0]["idFuncionario"]);
              $consulta->execute();

              if(count($consulta->fetchAll()) > 0){
                return json_encode(array("sucesso" => 1, "id" => $retornoConsulta[0]["idPessoa"], "nomeParticipante" => $nomeParticipante));
              }else{
                return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado no cadastro. Por favor, entre em contato com o administrador e informe esse Erro: FC-01"));
              }
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer o cadastro novamente mais tarde. ERRO: FC-02"));
          }
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "O evento informado não existe. ERRO: FC-03"));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Já há um participante com os mesmos dados. ERRO: FC-04"));
      }
  }

  function listaParticipantes($idEvento){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM pessoa WHERE idEmpresa = :p1 ORDER BY nomePessoa;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $idEvento);
      $consulta->execute();
      return $consulta->fetchAll();
  }

  function listaParticipantesporId($array){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();
      $retorno[0] = "";
      $i = 0;


      foreach($array as $id){
        $sql = "SELECT * FROM pessoa WHERE idPessoa = :p1 ORDER BY nomePessoa;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $id);
        $consulta->execute();
        $retorno[$i] = $consulta->fetchAll()[0];
        $i++;
      }
      return $retorno;
  }

  function buscaParticipante($id){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM pessoa WHERE idPessoa = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $id);
      $consulta->execute();
      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }

  function buscaParticipanteporCodigo($codigoBarras){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM pessoa WHERE codigoCartao = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $codigoBarras);
      $consulta->execute();
      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }

  function buscaParticipanteporCodigoJS($codigoBarras){
    $consulta = self::buscaPessoaporCodigo($codigoBarras);
    if($consulta["count"] > 0){
        return json_encode(array("sucesso" => 1, "erro" => 0, "mensagem" => "Cadastro localizado! Em instantes estaremos redirecionando para edição...", "id" => $consulta["consulta"][0]["idPessoa"]));
    }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há funcionário com esse código cadastrado."));
    }
  }


  function mudarStatusParticipante($idParticipante){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $busca = self::buscaParticipante($idParticipante);
      if($busca["count"] == 1){

          $status = ($busca["consulta"][0]["ativoPessoa"] == 1) ? 0 : 1;
          $desativacao = ($busca["consulta"][0]["ativoPessoa"] == 1) ? date("Y-m-d H:i:s") : NULL;

          $sql = "UPDATE pessoa SET ativoPessoa = :p1, dataDesativacao = :p2 WHERE idPessoa = :p3;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $status);
          $consulta->bindParam(":p2", $desativacao);
          $consulta->bindParam(":p3", $idFuncionario);
          $consulta->execute();

          $busca = self::buscaParticipante($idFuncionario);


          if($busca["consulta"][0]["ativoPesspa"] == $status){
            return array("sucesso" => 1, "idEvento" => $busca["consulta"][0]["idEvento"]);
          }else{
            return array("sucesso" => 0, "erro" => 1, "motivo" => "Algo aconteceu de errado. Por favor, tente mais tarde.", "idEmpresa" => $busca["idEvento"]);
          }
      }else{
        return array("sucesso" => 0, "erro" => 1, "motivo" => "O id informado não existe no banco de dados.", "idEmpresa" => $busca["idEvento"]);
      }

  }
}
