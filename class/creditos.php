<?php

class Creditos{


  function __construct(){

  }

  function novaMovimentacao($tipoMovimentacao, $idFuncionario, $quantidade, $idPedido = NULL, $idCRT = NULL){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      if(!$factory->isSetted("funcionario")){
        $factory->importaClasses(array("funcionario" => 1));
      }

      if(!$factory->isSetted("empresa")){
        $factory->importaClasses(array("empresa" => 1));
      }

      if(!$factory->isSetted("tipoLetra")){
        $factory->importaClasses(array("tipoLetra" => 1));
      }


      /*Tipos de Movimentação
        1 - Adição ou Entrada de Créditos
        2 - Uso ou Saída de Créditos
      */
      if($tipoMovimentacao == 1){
        $sql = "SELECT * FROM movimentacaoCreditos WHERE tipoMovimentacao = 1 && idFuncionario = :p1 && quantidade = :p2 && idPedido = :p3;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $idFuncionario);
        $consulta->bindParam(":p2", $quantidade);
        $consulta->bindParam(":p3", $idPedido);
        $consulta->execute();
        if($consulta->rowCount() == 0){
          $sql = "INSERT INTO movimentacaoCreditos (tipoMovimentacao, idFuncionario, quantidade, dataMovimentacao, idPedido) values (:p0 , :p1, :p2, :p3, :p4);";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p0", $tipoMovimentacao);
          $consulta->bindParam(":p1", $idFuncionario);
          $consulta->bindParam(":p2", $quantidade);
          $consulta->bindParam(":p3", date("Y-m-d H:i:s"));
          $consulta->bindParam(":p4",$idPedido);

          $consulta->execute();


          $consulta = $factory->getObjeto("funcionario")->buscaFuncionario($idFuncionario);

          if($consulta["count"] > 0){
            $creditos = $consulta["consulta"][0]["saldo"] + $quantidade;

            $sql = "UPDATE pessoa SET saldo = :p1 WHERE idPessoa = :p2;";

            $consulta = $conexao->prepare($sql);
            $consulta->bindParam(":p1", $creditos);
            $consulta->bindParam(":p2", $idFuncionario);
            $consulta->execute();
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há funcionário com ID ".$idFuncionario));
          }


          $sql = "SELECT * FROM movimentacaoCreditos WHERE tipoMovimentacao = 1 && idFuncionario = :p1 && quantidade = :p2 && idPedido = :p3;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $idFuncionario);
          $consulta->bindParam(":p2", $quantidade);
          $consulta->bindParam(":p3", $idPedido);
          $consulta->execute();

          if($consulta->rowCount() > 0){
            return json_encode(array("sucesso" => 1));
          }else{
            return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer o cadastro novamente mais tarde."));
          }
        }
      }else{

        $datMin = date("Y-m-d")." 00:00:00";
        $datMax = date("Y-m-d")." 23:59:59";

        //Aqui vai a movimentação de créditos de saída...
        $sql = "SELECT * FROM movimentacaoCreditos WHERE tipoMovimentacao = 2 && idFuncionario = :p1 && (dataMovimentacao >= :p2 && dataMovimentacao <= :p3);";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $idFuncionario);
        $consulta->bindParam(":p2", $datMin);
        $consulta->bindParam(":p3", $datMax);
        $consulta->execute();
        if($consulta->rowCount() == 0){
            $sql = "INSERT INTO movimentacaoCreditos (tipoMovimentacao, idFuncionario, quantidade, dataMovimentacao, idLocal) values ('2', :p1, :p2, :p3, :p4);";
            $consulta = $conexao->prepare($sql);
            $consulta->bindParam(":p1", $idFuncionario);
            $consulta->bindParam(":p2", $quantidade);
            $consulta->bindParam(":p3", date("Y-m-d H:i:s"));
            $consulta->bindParam(":p4", $idCRT);
            $consulta->execute();


            $sql = "SELECT * FROM movimentacaoCreditos WHERE tipoMovimentacao = 2 && idFuncionario = :p1 && (dataMovimentacao >= :p2 && dataMovimentacao <= :p3);";
            $consulta = $conexao->prepare($sql);
            $consulta->bindParam(":p1", $idFuncionario);
            $consulta->bindParam(":p2", $datMin);
            $consulta->bindParam(":p3", $datMax);
            $consulta->execute();

            if($consulta->rowCount() > 0){
              $funcionario = $factory->getObjeto("funcionario")->buscaFuncionario($idFuncionario);
              if($funcionario["count"] > 0){
                $empresa = $factory->getObjeto("empresa")->buscaEmpresa($funcionario["consulta"][0]["idEmpresa"]);
                if($empresa["consulta"][0]["isGratuito"] == 0){
                  $saldo = $funcionario["consulta"][0]["saldo"] - $quantidade;
                  $sql = "UPDATE pessoa SET saldo = :p1 WHERE idPessoa = :p2;";
                  $consulta = $conexao->prepare($sql);
                  $consulta->bindParam(":p1", $saldo);
                  $consulta->bindParam(":p2", $idFuncionario);
                  $consulta->execute();


                  $funcionario = $factory->getObjeto("funcionario")->buscaFuncionario($idFuncionario);
                  $isGratuito = false;
                }else{
                  $isGratuito = true;
                }
                // echo $empresa["consulta"][0]["idLetra"];

                $tipoLetra = $factory->getObjeto("tipoLetra")->buscaLetras($empresa["consulta"][0]["idLetra"]);

                // print_r($tipoLetra);

                if($saldo == $funcionario["consulta"][0]["saldo"] || $isGratuito == true){
                  date_default_timezone_set('America/Sao_Paulo');
                  setlocale(LC_ALL, "pt_BR", "pt_BR.utf-8", "portuguese");
                  $returnArray = array("tipo" => 1, "sucesso" => 1, "texto" => strtoupper($tipoLetra[0]["texto"]), "nomeFuncionario" => $funcionario["consulta"][0]["nomeFuncionario"], "bomAlmoco" => explode(" ", $funcionario["consulta"][0]["nomeFuncionario"])[0]);
                  $returnArray["isGratuito"] = $isGratuito;
                  $returnArray["tipoGrupo"] = $tipoLetra[0]["tipoGrupo"];
                  if(date("m-d",strtotime($funcionario["consulta"][0]["nascimentoFuncionario"])) == date("m-d")){
                    $returnArray["aniversario"] = 1;
                  }else{
                    $returnArray["aniversario"] = 0;
                  }
                  // print_r($funcionario);
                  if($funcionario["consulta"][0]["foto"] != NULL){
                    $returnArray["foto"] = "../empresas/fotos/miniaturas/foto-n.php?imagem=".$funcionario["consulta"][0]["foto"];
                  }else{
                    $returnArray["foto"] = "../empresas/fotos/foto.png";
                  }

                  if($isGratuito == false){
                    $returnArray["saldo"] = $saldo;
                  }

                  return json_encode($returnArray);

                }else{
                  return json_encode(array("tipo" => 0, "sucesso" => 0, "erro" => 1, "motivo" => "Alguma coisa ocorreu de errado. Por favor, tente novamente mais tarde. Erro CRT-FM1"));
                }
              }else{
                return json_encode(array("tipo" => 0, "sucesso" => 0, "erro" => 1, "motivo" => "Alguma coisa ocorreu de errado. Por favor, tente novamente mais tarde. Erro CRT-FM2"));
              }
            }else{
              return json_encode(array("tipo" => 1, "sucesso" => 0, "erro" => 1, "motivo" => "Alguma coisa ocorreu de errado. Por favor, tente novamente mais tarde. Erro CRT-FM3"));
            }
        }else{
          return json_encode(array("tipo" => 2, "sucesso" => 0, "erro" => 1, "motivo" => "Já foi utilizado este cartão no dia de hoje. Caso você não seja o funcionário detentor desse cartão, favor entrar em contato com o setor administrativo. Erro CRT-FM4"));
        }
      }

  }

  function gerarNovoTicket($idPedido, $isFree, $letraTicket){
      global $factory, $ValidadeTicket;

      $dataVencimentoticket = date("Y-m-d",(time()+$ValidadeTicket));

      $conexao = $factory->getObjeto("conexao")->getInstance();

      $tipoProduto = $factory->getObjeto("tipoProduto")->listaTiposProdutosporTipoTransacao(1, $isFree);
      //print_r($tipoProduto);

      foreach($tipoProduto as $inf){
        $sql = "SELECT * FROM itensPedido WHERE idPedido = :p1 && idTipoProduto = :p2";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $idPedido);
        $consulta->bindParam(":p2", $inf["idTipoProduto"]);
        $consulta->execute();
        if($consulta->rowCount() > 0){

          $fetch = $consulta->fetchAll()[0];

          $sql = "SELECT * FROM tickets WHERE idPedido = :p1 && isFree = :p2";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $idPedido);
          $consulta->bindParam(":p2", $isFree);
          $consulta->execute();

          if($fetch["quantidade"] > $consulta->rowCount()){
              $vai = true;
              do{
                $codigoTicket = $letraTicket.rand(0,9).(time()-(rand(0,9)*date("s")));
                $sql = "SELECT * FROM reservaCodigos WHERE codigoReserva = :p1;";
                $consulta = $conexao->prepare($sql);
                $consulta->bindParam(":p1", $codigoTicket);
                $consulta->execute();
                if($consulta->rowCount() == 0){
                  $vai = false;
                }
              }while($vai);

              $sql = "INSERT INTO tickets (idPedido, codigoTicket, dataGeracao, dataVencimento, isFree, situacaoTicket) values (:p1, :p2, :p3, :p4, :p5, 1)";

              $consulta = $conexao->prepare($sql);
              $consulta->bindParam(":p1", $idPedido);
              $consulta->bindParam(":p2", $codigoTicket);
              $consulta->bindParam(":p3", date("Y-m-d H:i:s"));
              $consulta->bindParam(":p4", $dataVencimentoticket);
              $consulta->bindParam(":p5", $inf["isFree"]);
              $consulta->execute();

              $sql = "SELECT * FROM tickets WHERE idPedido = :p1 && codigoTicket = :p2;";
              $consulta = $conexao->prepare($sql);
              $consulta->bindParam(":p1",$idPedido);
              $consulta->bindParam(":p2",$codigoTicket);
              $consulta->execute();

              if($consulta->rowCount() > 0){

                $sql = "INSERT INTO reservaCodigos (codigoReserva, idTicket, dataCadastro) values (:p1, :p2, :p3)";
                $consulta = $conexao->prepare($sql);
                $consulta->bindParam(":p1", $codigoTicket);
                $consulta->bindParam(":p2", $conexao->lastInsertId());
                $consulta->bindParam(":p3", date("Y-m-d H:i:s"));
                $consulta->execute();


                return json_encode(array("sucesso" => 1, "codigo" => $codigoTicket));
              }else{
                return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Ocorreu algo de errado. Por favor, tente fazer o cadastro novamente mais tarde."));
              }

          }
        }
      }

  }

  function buscaTicket($idTicket){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM tickets WHERE idTicket = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $idTicket);
      $consulta->execute();

      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }

  function buscaTicketporCodigo($codigoBarras){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM tickets WHERE codigoTicket = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $codigoBarras);
      $consulta->execute();

      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }

  function listaTickets($idPedido){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM tickets WHERE idPedido = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $idPedido);
      $consulta->execute();
      return $consulta->fetchAll();
  }

  function usarTicket($idCRT, $codigoBarras){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $ticket = self::buscaTicketporCodigo($codigoBarras);
      if($ticket["count"] > 0){

        if(!$factory->isSetted("local")){
          $factory->importaClasses(array("local" => 1));
        }
        $CRT = $factory->getObjeto("local")->buscaLocal($idCRT);
        if($CRT["count"] > 0){
          if($ticket["consulta"][0]["dataVencimento"] >= date("Y-m-d")){
            if($ticket["consulta"][0]["situacaoTicket"] == 1){
              $datetime = date("Y-m-d H:i:s");
              $sql = "UPDATE tickets SET dataUtilizacao = :p1, situacaoTicket = 2, idLocal = :p2 WHERE codigoTicket = :p3;";
              $consulta = $conexao->prepare($sql);
              $consulta->bindParam(":p1", $datetime);
              $consulta->bindParam(":p2", $idCRT);
              $consulta->bindParam(":p3", $codigoBarras);
              $consulta->execute();

              $ticket = self::buscaTicketporCodigo($codigoBarras);
              if($ticket["consulta"][0]["dataUtilizacao"] == $datetime && $ticket["consulta"][0]["situacaoTicket"] == 2){



                $tipoLetra = $factory->getObjeto("tipoLetra")->buscaLetras($ticket["consulta"][0]["idLetra"]);

                if($ticket["consulta"][0]["isFree"] == 1){
                  return json_encode(array("sucesso" => 1, "isTicket" => 1, "texto" => strtoupper($tipoLetra[0]["texto"]), "tipo" => 1, "isGratuito" => 1));
                }else{
                  return json_encode(array("sucesso" => 1, "isTicket" => 1, "texto" => strtoupper($tipoLetra[0]["texto"]), "tipo" => 1, "isGratuito" => 0));
                }
              }else{
                return json_encode(array("sucesso" => 0, "tipo" => 1, "erro" => 1, "motivo" => "Alguma coisa está dando errado. Por favor, tente novamente mais tarde. Erro CRT-T1"));

              }
            }else{
              if($ticket["consulta"][0]["situacaoTicket"] == 0){
                return json_encode(array("sucesso" => 0, "tipo" => 1, "erro" => 1, "motivo" => "O ticket usado está cancelado. Por favor, entre em contato com o setor administrativo. Erro CRT-T2"));
              }else{
                return json_encode(array("sucesso" => 0, "tipo" => 1, "erro" => 1, "motivo" => "Este ticket já foi utilizado. O ticket foi utilizado em <b>".date("d/m/Y",strtotime($ticket["consulta"][0]["dataUtilizacao"]))."</b>. Erro CRT-T3"));
              }
            }
          }else{
              return json_encode(array("sucesso" => 0, "tipo" => 1, "erro" => 1, "motivo" => "O ticket usado está vencido. O mesmo venceu dia <b>".date("d/m/Y",strtotime($ticket["consulta"][0]["dataVencimento"]))."</b>. Erro CRT-T4"));
          }
        }else{
            return json_encode(array("sucesso" => 0, "tipo" => 1, "erro" => 1, "motivo" => "Não há CRT com esse código. Erro CRT-T5"));
        }
      }else{
          return json_encode(array("sucesso" => 0, "tipo" => 1, "erro" => 1, "motivo" => "Não há ticket cadastrado com esse código. Erro CRT-T6"));
      }
  }

  function consultaMovimentacao($idFuncionario, $dataHoje){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    $datMin = date("Y-m-d", strtotime($dataHoje))." 00:00:00";
    $datMax = date("Y-m-d", strtotime($dataHoje))." 23:59:59";

    //Aqui vai a movimentação de créditos de saída...
    $sql = "SELECT * FROM movimentacaoCreditos WHERE tipoMovimentacao = 2 && idFuncionario = :p1 && (dataMovimentacao >= :p2 && dataMovimentacao <= :p3);";
    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1", $idFuncionario);
    $consulta->bindParam(":p2", $datMin);
    $consulta->bindParam(":p3", $datMax);
    $consulta->execute();
    if($consulta->rowCount() > 0){
      return true;
    }else{
      return false;
    }
  }

  function consulta($idCRT, $codigoBarras, $remoteLogin){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    if(!$factory->isSetted("local")){
      $factory->importaClasses(array("local" => 1));
    }

    if(!$factory->isSetted("empresa")){
      $factory->importaClasses(array("empresa" => 1));
    }

    if(!$factory->isSetted("tipoLetra")){
      $factory->importaClasses(array("tipoLetra" => 1));
    }

    if($remoteLogin == 1){

        $consulta = $factory->getObjeto("local")->buscaLocal($idCRT);
        if($consulta["count"] > 0){
          return json_encode(array("tipo" => 0, "sucesso" => 1, "mensagem" => "CRT remotamente setado com sucesso. Já é possível começar a fazer o registro de entrada.", "isCRT" => true, "idCRT" => $consulta["consulta"][0]["idLocal"], "nomeCRT" => $consulta["consulta"][0]["descricaoLocal"]));
        }else{
          return json_encode(array("tipo" => 0, "sucesso" => 0, "erro" => 1, "motivo" => "Não há Local CRT com esse código cadastrado."));
        }

    }else{

      $tipoCodigo = substr($codigoBarras, 0, 1);
      $tipoLetra = $factory->getObjeto("tipoLetra")->buscaLetraporLetra($tipoCodigo);
      // print_r($tipoLetra);
      if(count($tipoLetra) > 0){
        if(($idCRT == 0 || $idCRT == NULL) && $tipoLetra[0]["tipoUso"] != 3){
          return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Local CRT não está setado. Por favor, antes de tudo, passe o código de barras do Local CRT."));
        }else{



          // CRT
          if($tipoLetra[0]["tipoUso"] == 3){
            $consulta = $factory->getObjeto("local")->buscaLocalporCodigo($codigoBarras);
            if($consulta["count"] > 0){
              return json_encode(array("tipo" => 0, "sucesso" => 1, "mensagem" => "CRT setado com sucesso. Já é possível começar a fazer o registro de entrada.", "isCRT" => true, "idCRT" => $consulta["consulta"][0]["idLocal"], "nomeCRT" => $consulta["consulta"][0]["descricaoLocal"]));
            }else{
              return json_encode(array("tipo" => 0, "sucesso" => 0, "erro" => 1, "motivo" => "Não há Local CRT com esse código cadastrado."));
            }


          // Créditos
          }elseif($tipoLetra[0]["tipoUso"] == 1){

            if($codigoBarras == "F12345678901"){
              srand((float) microtime() * 10000000);
              $textos = array("Lorem Ipsum", "Luciano", "Felipe", "Carlos", "Joana", "Paula", "Daniel", "Canal", "Totó", "Bred", "Pinduca");
              return json_encode(array("tipo" => 1, "sucesso" => 1, "texto" => "FUNCIONÁRIO DE TESTE - DESCONSIDERAR", "nomeFuncionario" => "Funcionário de Teste - SysLunch", "bomAlmoco" => $textos[rand(0,count($textos))], "isGratuito" => true, "tipoGrupo" => 1, "aniversario" => 1));
            }else{

              if(!$factory->isSetted("funcionario")){
                $factory->importaClasses(array("funcionario" => 1));
              }
              $consulta = $factory->getObjeto("funcionario")->buscaFuncionarioporCodigo($codigoBarras);
              if($consulta["count"] > 0){
                if($consulta["consulta"][0]["ativoFuncionario"] > 0){
                  $empresa = $factory->getObjeto("empresa")->buscaEmpresa($consulta["consulta"][0]["idEmpresa"]);
                  if($empresa["consulta"][0]["ativoEmpresa"] > 0){
                    if($empresa["consulta"][0]["isGratuito"] == 0){
                      $saldo = json_decode(self::consultarSaldo(NULL, $codigoBarras));
                      $isGratuito = false;
                    }else{
                      $isGratuito = true;
                    }
                    if($saldo->sucesso == 1 || $isGratuito == true){
                      if($saldo->saldo > 0 || $isGratuito == true || self::consultaMovimentacao($consulta["consulta"][0]["idFuncionario"], date("Y-m-d"))){
                        return self::novaMovimentacao(2, $consulta["consulta"][0]["idFuncionario"], 1, NULL, $idCRT);
                      }else{
                        return json_encode(array("tipo" => 1, "isTicket" => 0, "sucesso" => 0, "erro" => 1, "motivo" => "SALDO INSUFICIENTE. Erro CRT-FC1"));
                      }
                    }else{
                      return json_encode(array("tipo" => 1, "sucesso" => 0, "erro" => 1, "motivo" => "Alguma coisa aconteceu de errado, por favor, tente novamente. Erro CRT-FC2"));
                    }
                    //return json_encode(array("sucesso" => 1, "mensagem" => "CRT setado com sucesso. Já é possível começar a fazer o registro de entrada.", "isCRT" => true, "idCRT" => $consulta["consulta"][0]["idLocal"], "nomeCRT" => $consulta["consulta"][0]["descricaoLocal"]));
                  }else{
                    return json_encode(array("tipo" => 0, "sucesso" => 0, "erro" => 1, "motivo" => "A empresa do funcionário desse cartão está desativado. Por favor, entre em contato com o setor financeiro. Erro CRT-FC3"));
                  }
                }else{
                  return json_encode(array("tipo" => 0, "sucesso" => 0, "erro" => 1, "motivo" => "O funcionário desse cartão está desativado. Por favor, entre em contato com o setor financeiro. Erro CRT-FC3"));
                }
              }else{
                return json_encode(array("tipo" => 0, "sucesso" => 0, "erro" => 1, "motivo" => "Não há Funcionário com esse código cadastrado. Erro CRT-FC3"));
              }
            }

          // Ticket
          }elseif($tipoLetra[0]["tipoUso"] == 2){
            return self::usarTicket($idCRT, $codigoBarras);
          }
        }
      }else{
        return json_encode(array("tipo" => 0, "sucesso" => 0, "erro" => 1, "motivo" => "Não há código com esse identificador cadastrado. Por favor, entre em contato com o setor administrativo. Erro CRT-FC4"));
      }
    }
  }

  function consultarSaldo($idFuncionario = NULL, $codigoBarras = NULL){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    if(!$factory->isSetted("funcionario")){
      $factory->importaClasses(array("funcionario" => 1));
    }

    if($idFuncionario != NULL){
      $consulta = $factory->getObjeto("funcionario")->buscaFuncionario($idFuncionario);
      if($consulta["count"] > 0){
        return json_encode(array("sucesso" => 1, "saldo" => $consulta["consulta"][0]["saldo"]));
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há funcionário com esse código cadastrado. Erro CRT-FS1"));
      }
    }elseif($codigoBarras != NULL){
      $consulta = $factory->getObjeto("funcionario")->buscaFuncionarioporCodigo($codigoBarras);
      if($consulta["count"] > 0){
        return json_encode(array("sucesso" => 1, "saldo" => $consulta["consulta"][0]["saldo"]));
      }else{
        return json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Não há funcionário com esse código cadastrado. Erro CRT-FS2"));
      }
    }else{
      return false;
    }
  }

  // Existe uma outra função de hasTickets na classe 'venda', mas essa tem outra finalidade...
  // A finalidade desta função é retornar se ainda existe tickets disponíveis daquele pedido...
  function hasTickets($idPedido){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    $sql = "SELECT count(*) FROM tickets WHERE idPedido = :p1 && situacaoTicket != 2 && situacaoTicket != 0;";
    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1", $idPedido);
    $consulta->execute();
    if($consulta->fetchAll()[0]["count(*)"] > 0){
      return true;
    }else{
      return false;
    }
  }

  /*
    Função para ter status de funcionamento do CRT...
  */
  function ping($idCRT){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    if(!$factory->isSetted("local")){
      $factory->importaClasses(array("local" => 1));
    }

    return $factory->getObjeto("local")->pingLocal($idCRT);
  }
/*
  function listaFuncionarios($idEmpresa){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM funcionario WHERE idEmpresa = :p1 ORDER BY nomeFuncionario;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $idEmpresa);
      $consulta->execute();
      return $consulta->fetchAll();
  }

  function buscaFuncionario($id){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM funcionario WHERE idFuncionario = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $id);
      $consulta->execute();
      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }


  function mudarStatusFuncionario($idFuncionario){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $busca = self::buscaFuncionario($idFuncionario);
      if($busca["count"] == 1){

          $status = ($busca["consulta"][0]["ativoFuncionario"] == 1) ? 0 : 1;

          $sql = "UPDATE funcionario SET ativoFuncionario = :p1 WHERE idFuncionario = :p2;";
          $consulta = $conexao->prepare($sql);
          $consulta->bindParam(":p1", $status);
          $consulta->bindParam(":p2", $idFuncionario);
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
*/
}
