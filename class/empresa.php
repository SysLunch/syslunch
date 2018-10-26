<?php

class Empresa{


  function __construct(){

  }

  function editarEmpresa($idEmpresa, $nomeEmpresa, $loginEmpresa, $tipoLetra){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      if(!$factory->isSetted("tipoLetra")){
        $factory->importaClasses(array("tipoLetra" => 1));
      }

      $consulta = self::buscaEmpresa($idEmpresa);
      if($consulta["count"] > 0){

        $sql = "SELECT idGrupo as idEmpresa FROM grupo WHERE (nomeGrupo = :p1 || usuarioGrupo = :p2) && idGrupo != :p3;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $nomeEmpresa);
        $consulta->bindParam(":p2", $loginEmpresa);
        $consulta->bindParam(":p3", $idEmpresa);
        $consulta->execute();
        if($consulta->rowCount() == 0){


          $consultaLogin = self::buscaEmpresa(NULL, 1, $loginEmpresa);

          if($consultaLogin["count"] == 0 || ($consultaLogin["consulta"][0]["idGrupo"] == $consulta["consulta"][0]["idGrupo"])){

            $consultaLetra = $factory->getObjeto("tipoLetra")->buscaLetras($tipoLetra);
            if(count($consultaLetra) > 0){

              $sqlUpdate = "UPDATE grupo SET nomeGrupo = :p1, usuarioGrupo = :p2, idLetra = :p3, isGratuito = :p4, dataEdicao = :p5 WHERE idGrupo = :p6;";
              $consultaUpdate = $conexao->prepare($sqlUpdate);
              $consultaUpdate->bindParam(":p1", $nomeEmpresa);
              $consultaUpdate->bindParam(":p2", $loginEmpresa);
              $consultaUpdate->bindParam(":p3", $tipoLetra);
              $consultaUpdate->bindParam(":p4", $consultaLetra[0]["isGratuito"]);
              $consultaUpdate->bindParam(":p5", date("Y-m-d H:i:s"));
              $consultaUpdate->bindParam(":p6", $idEmpresa);
              $consultaUpdate->execute();


              $sql = "SELECT * FROM grupo WHERE nomeGrupo = :p1 && usuarioGrupo = :p2 && idLetra = :p3 && idGrupo = :p4;";
              $consulta = $conexao->prepare($sql);
              $consulta->bindParam(":p1", $nomeEmpresa);
              $consulta->bindParam(":p2", $loginEmpresa);
              $consulta->bindParam(":p3", $tipoLetra);
              $consulta->bindParam(":p4", $idEmpresa);
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
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Já há uma empresa com esse login cadastrado. Por favor, escolha outro e tente novamente."));
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
          $sqlUpdate = "UPDATE grupo SET senhaGrupo = :p1 WHERE idGrupo = :p2;";
          $consultaUpdate = $conexao->prepare($sqlUpdate);
          $consultaUpdate->bindParam(":p1", hash("sha384",hash("sha256",md5($senhaEmpresa))));
          $consultaUpdate->bindParam(":p2", $idEmpresa);
          $consultaUpdate->execute();


          $sql = "SELECT * FROM grupo WHERE senhaGrupo = :p1 && idGrupo = :p2;";
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

  function novaEmpresa($nomeEmpresa, $loginEmpresa, $senhaEmpresa = null, $tipoLetra){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      if(!$factory->isSetted("tipoLetra")){
        $factory->importaClasses(array("tipoLetra" => 1));
      }

      $sql = "SELECT * FROM grupo WHERE nomeGrupo = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $nomeEmpresa);
      $consulta->execute();

      if($consulta->rowCount() == 0){

        $sql = "SELECT * FROM grupo WHERE usuarioGrupo = :p1;";
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

          $consultaLetra = $factory->getObjeto("tipoLetra")->buscaLetras($tipoLetra);
          if(count($consultaLetra) > 0){



            $sql = "INSERT INTO grupo (nomeGrupo, usuarioGrupo, senhaGrupo, tokenGrupo, dataCadastro, ativoGrupo, isGratuito, idLetra) value (:p1, :p2, :p3, :p4, :p5, '1', :p6, :p7);";
            $consulta = $conexao->prepare($sql);
            $consulta->bindParam(":p1", $nomeEmpresa);
            $consulta->bindParam(":p2", $loginEmpresa);
            $consulta->bindParam(":p3", hash("sha384",hash("sha256",md5($senhaEmpresa))));
            $consulta->bindParam(":p4", $tokenEmpresa);
            $consulta->bindParam(":p5", date("Y-m-d H:i:s"));
            $consulta->bindParam(":p6", $consultaLetra[0]["isGratuito"]);
            $consulta->bindParam(":p7", $tipoLetra);
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
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há essa letra cadastrada. Por favor, escolha outra e tente novamente."));
          }
        }else{
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Já há uma empresa com o mesmo login."));
        }
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Já há uma empresa com o mesmo nome."));
      }
  }

  function listaEmpresas($ativo = 2){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();
      if($ativo == 2){
        $sql = "SELECT idGrupo as idEmpresa, nomeGrupo as nomeEmpresa, usuarioGrupo as usuarioEmpresa, senhaGrupo as senhaEmpresa, tokenGrupo as tokenEmpresa, dataCadastro, dataEdicao, dataDesativacao, ativoGrupo as ativoEmpresa, isGratuito, g.idLetra FROM grupo g INNER JOIN reservaLetras rl ON (rl.idLetra = g.idLetra) WHERE rl.tipoGrupo = 1;";
        $consulta = $conexao->prepare($sql);
      }else{
        $sql = "SELECT idGrupo as idEmpresa, nomeGrupo as nomeEmpresa, usuarioGrupo as usuarioEmpresa, senhaGrupo as senhaEmpresa, tokenGrupo as tokenEmpresa, dataCadastro, dataEdicao, dataDesativacao, ativoGrupo as ativoEmpresa, isGratuito, g.idLetra FROM grupo g INNER JOIN reservaLetras rl ON (rl.idLetra = g.idLetra) WHERE ativoGrupo = :p1 &&  rl.tipoGrupo = 1;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $ativo);
      }
      $consulta->execute();
      return $consulta->fetchAll();
  }

  function buscaEmpresa($id = NULL, $ativo = 2, $login = NULL, $senha = NULL){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();
      if($login != NULL && $senha != NULL){
        $sql = "SELECT idGrupo as idEmpresa, nomeGrupo as nomeEmpresa, usuarioGrupo as usuarioEmpresa, senhaGrupo as senhaEmpresa, tokenGrupo as tokenEmpresa, dataCadastro, dataEdicao, dataDesativacao, ativoGrupo as ativoEmpresa, g.isGratuito, g.idLetra, letraReserva as letra, descricaoTReserva, rl.tipoGrupo FROM grupo g INNER JOIN reservaLetras rl ON (rl.idLetra = g.idLetra) INNER JOIN tipoReserva tr ON(tr.idTipoReserva = rl.idTipoReserva) WHERE loginGrupo = :p1 && senhaGrupo = :p2;";
      }else{
        if($ativo == 2){
          $sql = "SELECT idGrupo as idEmpresa, nomeGrupo as nomeEmpresa, usuarioGrupo as usuarioEmpresa, senhaGrupo as senhaEmpresa, tokenGrupo as tokenEmpresa, dataCadastro, dataEdicao, dataDesativacao, ativoGrupo as ativoEmpresa, g.isGratuito, g.idLetra, letraReserva as letra, descricaoTReserva, rl.tipoGrupo FROM grupo g INNER JOIN reservaLetras rl ON (rl.idLetra = g.idLetra) INNER JOIN tipoReserva tr ON(tr.idTipoReserva = rl.idTipoReserva) WHERE idGrupo = :p1;";
        }else{
          $sql = "SELECT idGrupo as idEmpresa, nomeGrupo as nomeEmpresa, usuarioGrupo as usuarioEmpresa, senhaGrupo as senhaEmpresa, tokenGrupo as tokenEmpresa, dataCadastro, dataEdicao, dataDesativacao, ativoGrupo as ativoEmpresa, g.isGratuito, g.idLetra, letraReserva as letra, descricaoTReserva, rl.tipoGrupo FROM grupo g INNER JOIN reservaLetras rl ON (rl.idLetra = g.idLetra) INNER JOIN tipoReserva tr ON(tr.idTipoReserva = rl.idTipoReserva) WHERE idGrupo = :p1 && ativoGrupo = :p2;";
        }
      }
      $consulta = $conexao->prepare($sql);
      if($login != NULL && $senha != NULL){
        $consulta->bindParam(":p1", $login);
        $consulta->bindParam(":p2", $senha);
      }else{
        $consulta->bindParam(":p1", $id);
        if($ativo != 2){
          $consulta->bindParam(":p2", $ativo);
        }
      }
      $consulta->execute();
      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }


  function mudarStatusEmpresa($idEmpresa){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $busca = self::buscaEmpresa($idEmpresa);
      if($busca["count"] == 1){

          $status = ($busca["consulta"][0]["ativoEmpresa"] == 1) ? 0 : 1;

          $desativacao = ($busca["consulta"][0]["ativoEmpresa"] == 1) ? date("Y-m-d H:i:s") : NULL;

          $sql = "UPDATE grupo SET ativoGrupo = :p1, dataDesativacao = :p2 WHERE idGrupo = :p3;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $status);
          $consulta->bindParam(":p2", $desativacao);
          $consulta->bindParam(":p3", $idEmpresa);
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
  }
}
