<?php

class Financeiro{


  function __construct(){

  }

  function novaMovimentacao($idPedido, $valorTotal){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    $sql = "SELECT * FROM financeiro WHERE idPedido = :p1";
    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1", $idPedido);
    $consulta->execute();

    if($consulta->rowCount() == 0){
      $sql = "INSERT INTO financeiro (valorTotal, dataFinanceiro, idUsuario, idPedido) values (:p1, :p2, :p3, :p4)";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $valorTotal);
      $consulta->bindParam(":p2", date("Y-m-d H:i:s"));
      $consulta->bindParam(":p3", $factory->getObjeto("login")->getIdLogin());
      $consulta->bindParam(":p4", $idPedido);
      $consulta->execute();

      $sql = "SELECT * FROM financeiro WHERE idPedido = :p1";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $idPedido);
      $consulta->execute();

      if($consulta->rowCount() == 0){
        return json_encode(array("sucesso" => 1));
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer o cadastro novamente mais tarde."));
      }


    }

    return true;
  }


  function buscaMovimentacaoporPedido($idPedido){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM financeiro WHERE idPedido = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $idPedido);
      $consulta->execute();
      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }

  /*function editarEmpresa($idEmpresa, $nomeEmpresa, $loginEmpresa){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $consulta = self::buscaEmpresa($idEmpresa);
      if($consulta["count"] > 0){

        $sql = "SELECT * FROM empresa WHERE (nomeEmpresa = :p1 || usuarioEmpresa = :p2) && idEmpresa != :p3;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $nomeEmpresa);
        $consulta->bindParam(":p2", $loginEmpresa);
        $consulta->bindParam(":p3", $idEmpresa);
        $consulta->execute();
        if($consulta->rowCount() == 0){

          $sqlUpdate = "UPDATE empresa SET nomeEmpresa = :p1, usuarioEmpresa = :p2 WHERE idEmpresa = :p3;";
          $consultaUpdate = $conexao->prepare($sqlUpdate);
          $consultaUpdate->bindParam(":p1", $nomeEmpresa);
          $consultaUpdate->bindParam(":p2", $loginEmpresa);
          $consultaUpdate->bindParam(":p3", $idEmpresa);
          $consultaUpdate->execute();


          $sql = "SELECT * FROM empresa WHERE nomeEmpresa = :p1 && usuarioEmpresa = :p2 && idEmpresa = :p3;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $nomeEmpresa);
          $consulta->bindParam(":p2", $loginEmpresa);
          $consulta->bindParam(":p3", $idEmpresa);
          $consulta->execute();

          if($consulta->rowCount() > 0){
            return json_encode(array("sucesso" => 1));
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer a alteração de cadastro novamente mais tarde."));
          }
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Há um outro local de mesmo nome cadastrado. Por favor, escolha outro nome."));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há esse local cadastrado.", "local" => 0));
      }
  }

  function alterarSenhaEmpresa($idEmpresa, $senhaEmpresa, $confSenhaEmpresa, $novaSenha){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $consulta = self::buscaEmpresa($idEmpresa);
      if($consulta["count"] > 0){

        if($novaSenha == 1){
          $senhaEmpresa = self::geraSenha(10);
          $senhaGerada = 1;
        }else{
          $senhaGerada = 0;
        }
        if($senhaEmpresa == $confSenhaEmpresa || $novaSenha == 1){
          $sqlUpdate = "UPDATE empresa SET senhaEmpresa = :p1 WHERE idEmpresa = :p2;";
          $consultaUpdate = $conexao->prepare($sqlUpdate);
          $consultaUpdate->bindParam(":p1", hash("sha384",hash("sha256",md5($senhaEmpresa))));
          $consultaUpdate->bindParam(":p2", $idEmpresa);
          $consultaUpdate->execute();


          $sql = "SELECT * FROM empresa WHERE senhaEmpresa = :p1 && idEmpresa = :p2;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", hash("sha384",hash("sha256",md5($senhaEmpresa))));
          $consulta->bindParam(":p2", $idEmpresa);
          $consulta->execute();

          if($consulta->rowCount() > 0){
            if($senhaGerada == 1){
              return json_encode(array("sucesso" => 1, "senha" => $senhaEmpresa));
            }else{
              return json_encode(array("sucesso" => 1));
            }
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer a alteração de cadastro novamente mais tarde."));
          }
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "As senhas não batem. Por favor, verifique e tente novamente."));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há essa empresa cadastrada.", "empresa" => 0));
      }
  }

  function novaEmpresa($nomeEmpresa, $loginEmpresa, $senhaEmpresa = null){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM empresa WHERE nomeEmpresa = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $nomeEmpresa);
      $consulta->execute();

      if($consulta->rowCount() == 0){

        $sql = "SELECT * FROM empresa WHERE usuarioEmpresa = :p1;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $loginEmpresa);
        $consulta->execute();


        if($consulta->rowCount() == 0){

          if($senhaEmpresa == NULL){
            $senhaEmpresa = self::geraSenha(10);
            $senhaGerada = 1;
          }else{
            $senhaGerada = 0;
          }

          $tokenEmpresa = hash("sha256", sha1($nomeEmpresa.rand(0,99).$loginEmpresa.rand(1000,99999)));


          $sql = "INSERT INTO empresa (nomeEmpresa, usuarioEmpresa, senhaEmpresa, tokenEmpresa, dataCadastro, ativoEmpresa) value (:p1, :p2, :p3, :p4, :p5, '1');";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $nomeEmpresa);
          $consulta->bindParam(":p2", $loginEmpresa);
          $consulta->bindParam(":p3", hash("sha384",hash("sha256",md5($senhaEmpresa))));
          $consulta->bindParam(":p4", $tokenEmpresa);
          $consulta->bindParam(":p5", date("Y-m-d H:i:s"));
          $consulta->execute();
          if($conexao->lastInsertId() > 0){
            if($senhaGerada == 1){
              //echo $senhaGerada;
              return json_encode(array("sucesso" => 1, "id" => $conexao->lastInsertId(), "nomeEmpresa" => $nomeEmpresa, "senha" => $senhaEmpresa));
            }else{
              return json_encode(array("sucesso" => 1, "id" => $conexao->lastInsertId(), "nomeEmpresa" => $nomeEmpresa));
            }
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer o cadastro novamente mais tarde."));
          }
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Já há uma empresa com o mesmo login."));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Já há uma empresa com o mesmo nome."));
      }
  }

  function listaEmpresas(){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM empresa;";
      $consulta = $conexao->prepare($sql);
      $consulta->execute();
      return $consulta->fetchAll();
  }


  function mudarStatusEmpresa($idEmpresa){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $busca = self::buscaEmpresa($idEmpresa);
      if($busca["count"] == 1){

          $status = ($busca["consulta"][0]["ativoEmpresa"] == 1) ? 0 : 1;

          $sql = "UPDATE empresa SET ativoEmpresa = :p1 WHERE idEmpresa = :p2;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $status);
          $consulta->bindParam(":p2", $idEmpresa);
          $consulta->execute();

          $busca = self::buscaEmpresa($idEmpresa);


          if($busca["consulta"][0]["statusEmpresa"] == $status){
            return array("sucesso" => 1);
          }else{
            return array("sucesso" => 0, "erro" => 1, "motivo" => "Algo aconteceu de errado. Por favor, tente mais tarde.");
          }
      }else{
        return array("sucesso" => 0, "erro" => 1, "motivo" => "O id informado não existe no banco de dados.");
      }

  }

  function geraSenha($tamanho = 10){
    $letras = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","s","t","u","v","x","y","w","z");
    $numeros = array(5,6,4,7,3,8,2,9,1,0);
    $simbolos = array("!","@","%","*","+","-","#");


    for ($i=0; $i < $tamanho; $i++) {
      switch(rand(1,3)){
        case 1:

          $l = $letras[rand(0,(count($letras)-1))];

          if(rand(0,1) == 0){
            $l = strtoupper($l);
          }else{
            $l = strtolower($l);
          }
          break;
        case 2:
          $l = $numeros[rand(0,(count($numeros)-1))];
          break;
        case 3:
          $l = $simbolos[rand(0,(count($simbolos)-1))];
          break;
      }
      $senha .= $l;
    }

    return $senha;
  }*/
}
