<?php

class Usuario{


  function __construct(){

  }

  function editarUsuario($idUsuario, $nomeUsuario, $loginUsuario, $nascimentoUsuario, $permissaoUsuario){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $consulta = self::buscaUsuario($idUsuario);
      if($consulta["count"] > 0){

        $sql = "SELECT * FROM usuario WHERE loginUsuario = :p1 && idUsuario != :p2;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $loginUsuario);
        $consulta->bindParam(":p2", $idUsuario);
        $consulta->execute();
        $consultaCount = $consulta->rowCount();
        if($consultaCount == 0){

          $sqlUpdate = "UPDATE usuario SET nomeUsuario = :p1, nascimentoUsuario = :p2, nascimentoUsuario = :p3, permissaoUsuario = :p4, edicaoUsuario = :p5 WHERE idFuncionario = :p6;";
          $consultaUpdate = $conexao->prepare($sqlUpdate);
          $consultaUpdate->bindParam(":p1", $nomeUsuario);
          $consultaUpdate->bindParam(":p2", $loginUsuario);
          $consultaUpdate->bindParam(":p3", $nascimentoUsuario);
          $consultaUpdate->bindParam(":p4", $permissaoUsuario);
          $consultaUpdate->bindParam(":p5", date("Y-m-d H:i:s"));
          $consultaUpdate->bindParam(":p6", $idUsuario);
          $consultaUpdate->execute();


          $sql = "SELECT * FROM usuario WHERE loginUsuario = :p1 && idUsuario = :p2;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $loginUsuario);
          $consulta->bindParam(":p2", $idUsuario);
          $consulta->execute();

          if($consulta->rowCount() > 0){
            return json_encode(array("sucesso" => 1));
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer a alteração de cadastro novamente mais tarde. Erro: UEC-01"));
          }
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Há um outro usuário de mesmo login cadastrado. Por favor, escolha outro login. Erro: UEC-02"));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há esse usuário cadastrado. Erro: UEC-03"));
      }
  }

  function alterarSenhaUsuario($idUsuario, $senhaUsuario = NULL, $confirmacaoSenhaUsuario = NULL, $novaSenha){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $consulta = self::buscaUsuario($idUsuario);
      if($consulta["count"] > 0){

        if($novaSenha == 1){
          $senhaUsuario = self::geraSenha(10);
          $confirmacaoSenhaUsuario = $senhaUsuario;
          $senhaGerada = 1;
        }else{
          $senhaGerada = 0;
        }
        if($senhaUsuario == $confirmacaoSenhaUsuario || $novaSenha == 1){
          $sqlUpdate = "UPDATE usuario SET senhaUsuario = :p1 WHERE idUsuario = :p2;";
          $consultaUpdate = $conexao->prepare($sqlUpdate);
          $consultaUpdate->bindParam(":p1", hash("sha384",hash("sha256",md5($senhaUsuario))));
          $consultaUpdate->bindParam(":p2", $idUsuario);
          $consultaUpdate->execute();


          $sql = "SELECT * FROM usuario WHERE senhaUsuario = :p1 && idUsuario = :p2;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", hash("sha384",hash("sha256",md5($senhaUsuario))));
          $consulta->bindParam(":p2", $idUsuario);
          $consulta->execute();

          if($consulta->rowCount() > 0){
            $dadosUsuario = $consulta->fetchAll();
            $factory->getObjeto("log")->cadastraLog(NULL, $factory->getObjeto("login")->getIdLogin(), "O Usuário logado alterou a senha do usuário '".$dadosUsuario[0]["nomeUsuario"]."'.", "usuario", $idUsuario);
            if($senhaGerada == 1){
              return json_encode(array("sucesso" => 1, "senha" => $senhaUsuario));
            }else{
              // return json_encode(array("sucesso" => 1, "senha" => $senhaUsuario));
              return json_encode(array("sucesso" => 1));
            }
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer a alteração de cadastro novamente mais tarde."));
          }
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "As senhas não conferem. Por favor, verifique e tente novamente."));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há esse usuário cadastrada.", "usuario" => 0));
      }
  }

  function novoUsuario($nomeUsuario, $loginUsuario, $senhaUsuario = null, $confirmacaoSenhaUsuario = NULL, $nascimentoUsuario, $permissaoUsuario){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();


      if($nomeUsuario != NULL && $loginUsuario != NULL && $nascimentoUsuario != NULL && ($permissaoUsuario != NULL && $permissaoUsuario != 0)){

        $sql = "SELECT * FROM usuario WHERE loginUsuario = :p1;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $loginUsuario);
        $consulta->execute();

        if($consulta->rowCount() == 0){

          // Gera a senha se necessário
          if($senhaUsuario == NULL){
            $senhaUsuario = self::geraSenha(10);
            $confirmacaoSenhaUsuario = $senhaUsuario;
            $senhaGerada = 1;
          }else{
            $senhaGerada = 0;
          }

          if($senhaUsuario == $confirmacaoSenhaUsuario){

            $verCod = true;
            do{
                $tokenUsuario = md5(sha1(hash("sha256",$senhaUsuario.time().rand(0,date("s")))));
                $sql = "SELECT * FROM usuario WHERE tokenUsuario = :p1;";
                $consulta = $conexao->prepare($sql);
                $consulta->bindParam(":p1", $tokenUsuario);
                $consulta->execute();
                if($consulta->rowCount() == 0){
                  $verCod = false;
                }else{
                  sleep(1);
                }
            }while($verCod);


            $sql = "INSERT INTO usuario (nomeUsuario, loginUsuario, senhaUsuario, tokenUsuario, nascimentoUsuario, cadastroUsuario, ativoUsuario, permissaoUsuario) value (:p1, :p2, :p3, :p4, :p5, :p6, '1', :p7);";
            $consulta = $conexao->prepare($sql);
            $consulta->bindParam(":p1", $nomeUsuario);
            $consulta->bindParam(":p2", $loginUsuario);
            $consulta->bindParam(":p3", hash("sha384",hash("sha256",md5($senhaUsuario))));
            $consulta->bindParam(":p4", $tokenUsuario);
            $consulta->bindParam(":p5", $nascimentoUsuario);
            $consulta->bindParam(":p6", date("Y-m-d H:i:s"));
            $consulta->bindParam(":p7", $permissaoUsuario);
            $consulta->execute();

            $sql = "SELECT idUsuario FROM usuario WHERE loginUsuario = :p1;";
            $consulta = $conexao->prepare($sql);
            $consulta->bindParam(":p1", $loginUsuario);
            $consulta->execute();

            $retornoConsulta = $consulta->fetchAll();

            if(count($retornoConsulta) > 0){
              if($senhaGerada == 1){
                return json_encode(array("sucesso" => 1, "id" => $retornoConsulta[0]["idUsuario"], "nomeUsuario" => $nomeUsuario, "senhaUsuario" => $senhaUsuario));
              }else{
                return json_encode(array("sucesso" => 1, "id" => $retornoConsulta[0]["idUsuario"], "nomeUsuario" => $nomeUsuario));
              }
            }else{
              return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer o cadastro novamente mais tarde. ERRO: UC-01"));
            }
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "As senhas não conferem. ERRO: UC-02"));
          }
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Já há um usuário com os mesmos dados. ERRO: UC-03"));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Há campos obrigatórios que estão nulos. Por favor, preencha-os e depois tente novamente. ERRO: UC-04"));
      }
  }

  function listaUsuarios(){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM usuario ORDER BY idUsuario;";
      $consulta = $conexao->prepare($sql);
      $consulta->execute();
      return $consulta->fetchAll();
  }

  function listaPermissoes(){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      if(!$factory->isSetted("login")){
        $factory->importaClasses(array("login" => 1));
      }

      if($factory->getObjeto("login")->getPermissao() == 4){
        $sql = "SELECT * FROM permissoesUsuario ORDER BY nivelPermissao;";
      }else{
        $sql = "SELECT * FROM permissoesUsuario WHERE restritoSuper = 0 ORDER BY nivelPermissao;";
      }
      $consulta = $conexao->prepare($sql);
      $consulta->execute();
      return $consulta->fetchAll();
  }

  function listaUsuariosporId($array){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();
      $retorno[0] = "";
      $i = 0;


      foreach($array as $id){
        $sql = "SELECT * FROM usuario WHERE idUsuario = :p1;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $id);
        $consulta->execute();
        $retorno[$i] = $consulta->fetchAll()[0];
        $i++;
      }
      return $retorno;
  }

  function buscaUsuario($id){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM usuario WHERE idUsuario = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $id);
      $consulta->execute();
      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }


  function mudarStatusUsuario($idUsuario){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $busca = self::buscaUsuario($idUsuario);
      if($busca["count"] == 1){

          $status = ($busca["consulta"][0]["ativoUsuario"] == 1) ? 0 : 1;
          $desativacao = ($busca["consulta"][0]["ativoUsuario"] == 1) ? date("Y-m-d H:i:s") : NULL;

          $sql = "UPDATE usuario SET ativoUsuario = :p1, desativacaoUsuario = :p2 WHERE idUsuario = :p3;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $status);
          $consulta->bindParam(":p2", $desativacao);
          $consulta->bindParam(":p3", $idUsuario);
          $consulta->execute();

          $busca = self::buscaUsuario($idUsuario);


          if($busca["consulta"][0]["ativoUsuario"] == $status){
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
  }
}
