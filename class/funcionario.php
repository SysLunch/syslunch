<?php

class Funcionario{


  function __construct(){

  }

  function editarFuncionario($idFuncionario, $nomeFuncionario, $nascimentoFuncionario, $codigoCartao){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $consulta = self::buscaFuncionario($idFuncionario);
      if($consulta["count"] > 0){

        $sql = "SELECT * FROM pessoa WHERE nomePessoa = :p1 && nascimentoPessoa = :p2 && codigoCartao = :p3 && idPessoa != :p4;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $nomeFuncionario);
        $consulta->bindParam(":p2", $nascimentoFuncionario);
        $consulta->bindParam(":p3", $codigoCartao);
        $consulta->bindParam(":p4", $idFuncionario);
        $consulta->execute();
        $consultaCount = $consulta->rowCount();
        if($consultaCount == 0){

          $sqlUpdate = "UPDATE pessoa SET nomePessoa = :p1, nascimentoPessoa = :p2, codigoCartao = :p3,  dataEdicao = :p4 WHERE idPessoa = :p5;";
          $consultaUpdate = $conexao->prepare($sqlUpdate);
          $consultaUpdate->bindParam(":p1", $nomeFuncionario);
          $consultaUpdate->bindParam(":p2", $nascimentoFuncionario);
          $consultaUpdate->bindParam(":p3", $codigoCartao);
          $consultaUpdate->bindParam(":p4", date("Y-m-d H:i:s"));
          $consultaUpdate->bindParam(":p5", $idFuncionario);
          $consultaUpdate->execute();


          $sql = "SELECT * FROM pessoa WHERE nomePessoa = :p1 && nascimentoPessoa = :p2 && codigoCartao = :p3 && idPessoa = :p4;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $nomeFuncionario);
          $consulta->bindParam(":p2", $nascimentoFuncionario);
          $consulta->bindParam(":p3", $codigoCartao);
          $consulta->bindParam(":p4", $idFuncionario);
          $consulta->execute();

          if($consulta->rowCount() > 0){
            return json_encode(array("sucesso" => 1));
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer a alteração de cadastro novamente mais tarde. ".$consultaCount));
          }
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Há um outro funcionário de mesmo nome cadastrado. Por favor, escolha outro nome."));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há esse funcionário cadastrado.", "local" => 0));
      }
  }


  function novoFuncionario($nomeFuncionario, $nascimentoFuncionario, $idEmpresa){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM pessoa WHERE nomePessoa = :p1 && nascimentoPessoa = :p2 && idEmpresa = :p3;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $nomeFuncionario);
      $consulta->bindParam(":p2", $nascimentoFuncionario);
      $consulta->bindParam(":p3", $idEmpresa);
      $consulta->execute();

      if($consulta->rowCount() == 0){

        if(!$factory->isSetted("empresa")){
          $factory->importaClasses(array("empresa" => 1));
        }

        $consulta = $factory->getObjeto("empresa")->buscaEmpresa($idEmpresa);

        if($consulta["count"] != 0){




          $verCod = true;
          do{
              $cartaoFuncionario = $consulta["consulta"][0]["letra"].rand(0,9).(time()-(rand(0,9)*date("s")));
              $sql = "SELECT * FROM reservaCodigos WHERE codigoReserva = :p1;";
              $consulta = $conexao->prepare($sql);
              $consulta->bindParam(":p1", $cartaoFuncionario);
              $consulta->execute();
              if($consulta->rowCount() == 0){
                $verCod = false;
              }else{
                sleep(1);
              }
          }while($verCod);


          $sql = "INSERT INTO pessoa (nomePessoa, nascimentoPessoa, ativoPessoa, codigoCartao, idEmpresa, saldo, dataCadastro) value (:p1, :p2, '1', :p3, :p4, '0', :p5);";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $nomeFuncionario);
          $consulta->bindParam(":p2", $nascimentoFuncionario);
          $consulta->bindParam(":p3", $cartaoFuncionario);
          $consulta->bindParam(":p4", $idEmpresa);
          $consulta->bindParam(":p5", date("Y-m-d H:i:s"));
          $consulta->execute();

          $sql = "SELECT idPessoa FROM pessoa WHERE codigoCartao = :p1;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $cartaoFuncionario);
          $consulta->execute();

          $retornoConsulta = $consulta->fetchAll();

          if(count($retornoConsulta) > 0){

            // print_r($retornoConsulta);

              $sql = "INSERT INTO reservaCodigos (codigoReserva, idFuncionario, dataCadastro) values (:p1, :p2, :p3)";
              $consulta = $conexao->prepare($sql);
              $consulta->bindParam(":p1", $cartaoFuncionario);
              $consulta->bindParam(":p2", $retornoConsulta[0]["idPessoa"]);
              $consulta->bindParam(":p3", date("Y-m-d H:i:s"));
              $consulta->execute();

              $sql = "SELECT count(*) FROM reservaCodigos WHERE codigoReserva = :p1 && idFuncionario = :p2;";
              $consulta = $conexao->prepare($sql);
              $consulta->bindParam(":p1", $cartaoFuncionario);
              $consulta->bindParam(":p2", $retornoConsulta[0]["idPessoa"]);
              $consulta->execute();

              // $cont = $consulta->fetchAll()[0]["count(*)"];

              echo $cont;
              if($cont > 0){
                return json_encode(array("sucesso" => 1, "id" => $retornoConsulta[0]["idPessoa"], "nomeFuncionario" => $nomeFuncionario));
              }else{
                return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado no cadastro. Por favor, entre em contato com o administrador e informe esse Erro: FC-01"));
              }
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer o cadastro novamente mais tarde. ERRO: FC-02"));
          }
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "A empresa informada não existe. ERRO: FC-03"));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Já há um funcionário com os mesmos dados. ERRO: FC-04"));
      }
  }

  function listaFuncionarios($idEmpresa){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT idPessoa as idFuncionario, idEmpresa, nomePessoa as nomeFuncionario, nascimentoPessoa as nascimentoFuncionario, ativoPessoa as ativoFuncionario, codigoCartao, saldo, dataCadastro, dataEdicao, dataDesativacao FROM pessoa WHERE idEmpresa = :p1 ORDER BY nomePessoa;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $idEmpresa);
      $consulta->execute();
      return $consulta->fetchAll();
  }

  function listaFuncionariosporId($array){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();
      $retorno[0] = "";
      $i = 0;


      foreach($array as $id){
        $sql = "SELECT idPessoa as idFuncionario, idEmpresa, nomePessoa as nomeFuncionario, nascimentoPessoa as nascimentoFuncionario, ativoPessoa as ativoFuncionario, codigoCartao, saldo, dataCadastro, dataEdicao, dataDesativacao FROM pessoa WHERE idPessoa = :p1 ORDER BY nomePessoa;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $id);
        $consulta->execute();
        $retorno[$i] = $consulta->fetchAll()[0];
        $i++;
      }
      return $retorno;
  }

  function buscaFuncionario($id){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT idPessoa as idFuncionario, idEmpresa, nomePessoa as nomeFuncionario, nascimentoPessoa as nascimentoFuncionario, ativoPessoa as ativoFuncionario, codigoCartao, saldo, foto, dataCadastro, dataEdicao, dataDesativacao FROM pessoa WHERE idPessoa = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $id);
      $consulta->execute();
      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }

  function buscaFuncionarioporCodigo($codigoBarras){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT idPessoa as idFuncionario, idEmpresa, nomePessoa as nomeFuncionario, nascimentoPessoa as nascimentoFuncionario, ativoPessoa as ativoFuncionario, codigoCartao, saldo, foto, dataCadastro, dataEdicao, dataDesativacao FROM pessoa WHERE codigoCartao = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $codigoBarras);
      $consulta->execute();
      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }

  function buscaFuncionarioporCodigoJS($codigoBarras){
    $consulta = self::buscaFuncionarioporCodigo($codigoBarras);
    if($consulta["count"] > 0){
        return json_encode(array("sucesso" => 1, "erro" => 0, "mensagem" => "Cadastro localizado! Em instantes estaremos redirecionando para edição...", "id" => $consulta["consulta"][0]["idFuncionario"]));
    }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há funcionário com esse código cadastrado."));
    }
  }


  function mudarStatusFuncionario($idFuncionario){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $busca = self::buscaFuncionario($idFuncionario);
      if($busca["count"] == 1){

          $status = ($busca["consulta"][0]["ativoFuncionario"] == 1) ? 0 : 1;
          $desativacao = ($busca["consulta"][0]["ativoFuncionario"] == 1) ? date("Y-m-d H:i:s") : NULL;

          $sql = "UPDATE pessoa SET ativoPessoa = :p1, dataDesativacao = :p2 WHERE idPessoa = :p3;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $status);
          $consulta->bindParam(":p2", $desativacao);
          $consulta->bindParam(":p3", $idFuncionario);
          $consulta->execute();

          $busca = self::buscaFuncionario($idFuncionario);


          if($busca["consulta"][0]["ativoFuncionario"] == $status){
            return array("sucesso" => 1, "idEmpresa" => $busca["consulta"][0]["idEmpresa"]);
          }else{
            return array("sucesso" => 0, "erro" => 1, "motivo" => "Algo aconteceu de errado. Por favor, tente mais tarde.", "idEmpresa" => $busca["idEmpresa"]);
          }
      }else{
        return array("sucesso" => 0, "erro" => 1, "motivo" => "O id informado não existe no banco de dados.", "idEmpresa" => $busca["idEmpresa"]);
      }

  }

  function listarCodigosFuncionario($idFuncionario){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM reservaCodigos WHERE idFuncionario = :p1;";

      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $idFuncionario);
      $consulta->execute();

      return $consulta->fetchAll();

  }

  function gerarNovoCodigoFuncionario($idFuncionario){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    $funcionario = self::buscaFuncionario($idFuncionario);


    if(!$factory->isSetted("empresa")){
      $factory->importaClasses(array("empresa" => 1));
    }

    $consulta = $factory->getObjeto("empresa")->buscaEmpresa($funcionario["consulta"][0]["idEmpresa"]);

    $verCod = true;
    do{
        $cartaoFuncionario = $consulta["consulta"][0]["letra"].rand(0,9).(time()-(rand(0,9)*date("s")));
        $sql = "SELECT * FROM reservaCodigos WHERE codigoReserva = :p1;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $cartaoFuncionario);
        $consulta->execute();
        if($consulta->rowCount() == 0){
          $verCod = false;
        }else{
          sleep(1);
        }
    }while($verCod);

    $sql = "INSERT INTO reservaCodigos (codigoReserva, idFuncionario, dataCadastro) values (:p1, :p2, :p3)";
    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1", $cartaoFuncionario);
    $consulta->bindParam(":p2", $idFuncionario);
    $consulta->bindParam(":p3", date("Y-m-d H:i:s"));
    $consulta->execute();

    $sql = "SELECT count(*) FROM reservaCodigos WHERE codigoReserva = :p1 && idFuncionario = :p2;";
    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1", $cartaoFuncionario);
    $consulta->bindParam(":p2", $idFuncionario);
    $consulta->execute();

    $cont = $consulta->fetchAll()[0]["count(*)"];

    // echo $cont;
    if($cont > 0){
      return array("sucesso" => 1);
    }else{
      return array("sucesso" => 0, "erro" => 1);
    }
  }
}
