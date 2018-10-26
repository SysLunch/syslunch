<?php

class Login{
  private static $idLogin;
  private static $login;
  private static $senha;
  private static $token;
  private static $erro;

  private static $nome;
  private static $permissao;


  function zeraInfos($erro = 1){
    isset(self::$idLogin);
    isset(self::$login);
    isset(self::$senha);
    isset(self::$token);
    isset($_SESSION["SisLunch_login"]);
    isset($_SESSION["SisLunch_token"]);
    //session_destroy();
    if($erro == 1){ isset(self::$erro); }

    return true;
  }

  function consultaLogin($opcao = 1, $tipoU = 1){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();
    if($tipoU == 1){
      if($opcao == 1){
        if(self::$login != NULL && self::$senha != NULL){
          $sql = "SELECT u.*, pu.nomePermissao, pu.nivelPermissao FROM usuario u INNER JOIN permissoesUsuario pu ON (pu.idPermissao = u.permissaoUsuario) WHERE u.loginUsuario = :p1 && u.senhaUsuario = :p2 && u.ativoUsuario = 1;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", self::$login);
          $consulta->bindParam(":p2", self::$senha);
          $consulta->execute();
          if($consulta->rowCount() > 0){
            $dadosUser = $consulta->fetch();
            self::$idLogin = $dadosUser["idUsuario"];
            self::$permissao = $dadosUser["nivelPermissao"];
            $_SESSION["SisLunch_login"] = $dadosUser["loginUsuario"];
            $_SESSION["SisLunch_token"] = $dadosUser["tokenUsuario"];
            return true;
          }else{
            self::$erro = "O login e/ou senha estão inválidos. Por favor, verifique-os para continuarmos.";
            self::zeraInfos(0);
          }
        }else{
          self::$erro = "O login ou senha estão nulos. Por favor, informe-os para continuarmos.";
          self::zeraInfos(0);
        }
      }else{
        if(self::$login != NULL && self::$token != NULL){
          $sql = "SELECT * FROM usuario WHERE loginUsuario = :p1 && tokenUsuario = :p2 && ativoUsuario = 1;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", self::$login);
          $consulta->bindParam(":p2", self::$token);
          $consulta->execute();
          if($consulta->rowCount() > 0){
            $dadosUser = $consulta->fetch();
            self::$idLogin = $dadosUser["idUsuario"];
            self::$nome = $dadosUser["nomeUsuario"];
            self::$permissao = $dadosUser["permissaoUsuario"];
            return true;
          }else{
            self::$erro = "O login e/ou token estão inválidos. Por favor, verifique-os para continuarmos.";
            self::zeraInfos(0);
          }
        }else{
          self::$erro = "Alguma coisa aconteceu de errado. Vamos lhe redirecionar para a página de login.";
          self::zeraInfos(0);
        }
      }
    }else{
      if($opcao == 1){
        if(self::$login != NULL && self::$senha != NULL){
          $buscaEmpresa = $factory->getObjeto("empresa")->buscaEmpresa(NULL, NULL, self::$login, self::$senha);
          if($buscaEmpresa["count"] > 0){
            $dadosEmpresa = $buscaEmpresa["consulta"];
            self::$idLogin = $dadosEmpresa["idGrupo"];
            $_SESSION["SisLunch_login"] = $dadosEmpresa["loginGrupo"];
            $_SESSION["SisLunch_token"] = $dadosEmpresa["tokenGrupo"];
            return true;
          }else{
            self::$erro = "O login e/ou senha estão inválidos. Por favor, verifique-os para continuarmos.";
            self::zeraInfos(0);
          }
        }else{
          self::$erro = "O login ou senha estão nulos. Por favor, informe-os para continuarmos.";
          self::zeraInfos(0);
        }
      }else{
        if(self::$login != NULL && self::$token != NULL){
          $sql = "SELECT * FROM usuario WHERE loginEmpresa = :p1 && tokenEmpresa = :p2;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", self::$login);
          $consulta->bindParam(":p2", self::$token);
          $consulta->execute();
          if($consulta->rowCount() > 0){
            $dadosEmpresa = $consulta->fetch();
            self::$idLogin = $dadosEmpresa["idEmpresa"];
            self::$nome = $dadosEmpresa["nomeEmpresa"];
            return true;
          }else{
            self::$erro = "O login e/ou token estão inválidos. Por favor, verifique-os para continuarmos.";
            self::zeraInfos(0);
          }
        }else{
          self::$erro = "Alguma coisa aconteceu de errado. Vamos lhe redirecionar para a página de login.";
          self::zeraInfos(0);
        }
      }
    }
  }

  function setLogin($Login){
    self::$login = $Login;
  }
  function setSenha($Senha){
    self::$senha = hash("sha384",hash("sha256",md5($Senha)));
  }

  function getIdLogin(){
    return self::$idLogin;
  }

  function getDadosUser(){
      return array("id" => self::$idLogin, "nome" => self::$nome);
  }

  function getPermissao(){
      return self::$permissao;
  }

  function isLogado(){
    if(self::$idLogin != NULL){
      return true;
    }else{
      return false;
    }
  }

  function efetuarLogin($tipo = 1){
    $consultaLogin = self::consultaLogin(1,$tipo);
    if($consultaLogin == true){
      return array("sucesso" => 1);
    }else{
      return array("sucesso" => 0, "erro" => 1, "descricao" => self::$erro);
    }
  }

  function efetuarLogout(){
    self::zeraInfos(1);
    session_destroy();
    return true;
  }


  function verificaLogin($tipo = 1){
    $consultaLogin = self::consultaLogin(2,$tipo);
    //echo self::$login;
    if($consultaLogin == true){
      return true;
    }else{
      return false;
    }
  }

  function permissaoPagina($permissao, $redirecionar = 1, $pagina){
    global $urlPrincipal;
    if(self::$permissao >= $permissao){
      if($redirecionar == 0){
        return true;
      }
    }else{
      if($redirecionar == 1){
        header("location: ".$urlPrincipal[0]."/?erro=permissao&pagina=$pagina");
      }else{
        return false;
      }
    }
  }

  function __construct(){
    session_start();

    self::$login = $_SESSION["SisLunch_login"];
    self::$token = $_SESSION["SisLunch_token"];
  }
}
