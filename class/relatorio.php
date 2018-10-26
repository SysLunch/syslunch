<?php

class Relatorio{


  function __construct(){

  }

  function almocosPorDia($data, $idEmpresa, $filtro){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    $dataMin = $data." 00:00:00";
    $dataMax = $data." 23:59:59";

    $retorno["estatisticas"]["totalCreditos"] = 0;
    $retorno["estatisticas"]["totalTickets"] = 0;
    $retorno["estatisticas"]["total"] = 0;
    $retorno["estatisticas"]["creditos"]["pagos"] = 0;
    $retorno["estatisticas"]["creditos"]["gratuitos"] = 0;
    $retorno["estatisticas"]["tickets"]["pagos"] = 0;
    $retorno["estatisticas"]["tickets"]["gratuitos"] = 0;

    if($idEmpresa == 0){

      if($filtro == 0 || $filtro == 1){
        //Creditos
        $sql = "SELECT dataMovimentacao, nomePessoa as nomeFuncionario, nomeGrupo as nomeEmpresa, isGratuito FROM movimentacaoCreditos mc INNER JOIN pessoa p ON (p.idPessoa = mc.idFuncionario) INNER JOIN grupo g ON (g.idGrupo = p.idEmpresa) WHERE dataMovimentacao >= :p1 && dataMovimentacao <= :p2 && tipoMovimentacao = 2 ORDER BY dataMovimentacao;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $dataMin);
        $consulta->bindParam(":p2", $dataMax);
        $consulta->execute();
        $retorno["creditos"] = $consulta->fetchAll();
        //Dados para a Estatística
        $retorno["estatisticas"]["totalCreditos"] = $consulta->rowCount();

        $sql = "SELECT count(*) FROM movimentacaoCreditos mc INNER JOIN pessoa p ON(p.idPessoa = mc.idFuncionario) INNER JOIN grupo g ON(g.idGrupo = p.idEmpresa) WHERE dataMovimentacao >= :p1 && dataMovimentacao <= :p2 && tipoMovimentacao = 2 && isGratuito = 1;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $dataMin);
        $consulta->bindParam(":p2", $dataMax);
        $consulta->execute();

        $retorno["estatisticas"]["creditos"]["gratuitos"] = $consulta->fetch()["count(*)"];
        $retorno["estatisticas"]["creditos"]["pagos"] = $retorno["estatisticas"]["totalCreditos"] - $retorno["estatisticas"]["creditos"]["gratuitos"];
      }

      if($filtro == 0 || $filtro == 2){
        //Tickets
        $sql = "SELECT * FROM tickets t INNER JOIN pedido p ON (p.idPedido = t.idPedido) WHERE t.dataUtilizacao >= :p1 && t.dataUtilizacao <= :p2 ORDER BY t.dataUtilizacao;";
        $consultaTickets = $conexao->prepare($sql);
        $consultaTickets->bindParam(":p1", $dataMin);
        $consultaTickets->bindParam(":p2", $dataMax);
        $consultaTickets->execute();
        $retorno["tickets"] = $consultaTickets->fetchAll();
        $retorno["estatisticas"]["totalTickets"] = $consultaTickets->rowCount();

        $sql = "SELECT count(*) FROM tickets t INNER JOIN pedido p ON (p.idPedido = t.idPedido) WHERE t.dataUtilizacao >= :p1 && t.dataUtilizacao <= :p2 && isFree = 1 ORDER BY t.dataUtilizacao;";
        $consultaTickets = $conexao->prepare($sql);
        $consultaTickets->bindParam(":p1", $dataMin);
        $consultaTickets->bindParam(":p2", $dataMax);
        $consultaTickets->execute();
        $retorno["estatisticas"]["tickets"]["gratuitos"] = $consultaTickets->fetch()["count(*)"];
        $retorno["estatisticas"]["tickets"]["pagos"] = $retorno["estatisticas"]["totalTickets"] - $retorno["estatisticas"]["tickets"]["gratuitos"];
      }

      if($filtro == 0){
        $retorno["estatisticas"]["total"] = $retorno["estatisticas"]["totalCreditos"] + $retorno["estatisticas"]["totalTickets"];
      }elseif($filtro == 1){
        $retorno["estatisticas"]["total"] = $retorno["estatisticas"]["totalCreditos"];
      }else{
        $retorno["estatisticas"]["total"] = $retorno["estatisticas"]["totalTickets"];
      }
    }else{
      $sql = "SELECT dataMovimentacao, nomePessoa as nomeFuncionario, nomeGrupo as nomeEmpresa, isGratuito FROM movimentacaoCreditos mc INNER JOIN pessoa p ON (p.idPessoa = mc.idFuncionario) INNER JOIN grupo g ON (g.idGrupo = p.idEmpresa) WHERE mc.dataMovimentacao >= :p1 && mc.dataMovimentacao <= :p2 && e.idEmpresa = :p3 && mc.tipoMovimentacao = 2 ORDER BY dataMovimentacao;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $dataMin);
      $consulta->bindParam(":p2", $dataMax);
      $consulta->bindParam(":p3", $idEmpresa);
      $consulta->execute();

      $retorno["creditos"] = $consulta->fetchAll();

      //Dados para a Estatística
      $retorno["estatisticas"]["totalCreditos"] = $consulta->rowCount();
      $retorno["estatisticas"]["total"] = $retorno["estatisticas"]["totalCreditos"];

      $sql = "SELECT count(*) FROM movimentacaoCreditos mc INNER JOIN pessoa p ON(p.idPessoa = mc.idFuncionario) INNER JOIN grupo g ON(g.idGrupo = p.idEmpresa) WHERE dataMovimentacao >= :p1 && dataMovimentacao <= :p2 && e.idEmpresa = :p3 && tipoMovimentacao = 2 && isGratuito = 1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $dataMin);
      $consulta->bindParam(":p2", $dataMax);
      $consulta->bindParam(":p3", $idEmpresa);
      $consulta->execute();

      $retorno["estatisticas"]["creditos"]["gratuitos"] = $consulta->fetch()["count(*)"];
      $retorno["estatisticas"]["creditos"]["pagos"] = $retorno["estatisticas"]["totalCreditos"] - $retorno["estatisticas"]["creditos"]["gratuitos"];
    }

    return $retorno;

  }

  function almocosPorPeriodo($dataInicial, $dataFinal, $idEmpresa, $filtro, $detalhado){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    $dataMin = $dataInicial." 00:00:00";
    $dataMax = $dataFinal." 23:59:59";

    if($detalhado == 0){
      if($idEmpresa == 0){
        //$sql = "SELECT * FROM movimentacaoCreditos WHERE dataMovimentacao >= :p1 && dataMovimentacao <= :p2 && tipoMovimentacao = 2 ORDER BY dataMovimentacao;";
        $sql = "SELECT dataMovimentacao, nomePessoa as nomeFuncionario, nomeGrupo as nomeEmpresa, isGratuito FROM movimentacaoCreditos mc INNER JOIN pessoa p ON (p.idPessoa = mc.idFuncionario) LEFT JOIN grupo g ON (g.idGrupo = p.idEmpresa) WHERE mc.dataMovimentacao >= :p1 && mc.dataMovimentacao <= :p2 && mc.tipoMovimentacao = 2 ORDER BY dataMovimentacao;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $dataMin);
        $consulta->bindParam(":p2", $dataMax);
        $consulta->execute();

        $sql = "SELECT * FROM tickets t INNER JOIN pedido p ON (p.idPedido = t.idPedido) WHERE t.dataUtilizacao >= :p1 && t.dataUtilizacao <= :p2 ORDER BY t.dataUtilizacao;";
        $consultaTickets = $conexao->prepare($sql);
        $consultaTickets->bindParam(":p1", $dataMin);
        $consultaTickets->bindParam(":p2", $dataMax);
        $consultaTickets->execute();

        //Dados para a Estatística
        $retorno["estatisticas"]["totalCreditos"] = $consulta->rowCount();
        $retorno["estatisticas"]["totalTickets"] = $consultaTickets->rowCount();
        $retorno["estatisticas"]["total"] = $retorno["estatisticas"]["totalCreditos"] + $retorno["estatisticas"]["totalTickets"];

        $retorno["creditos"] = $consulta->fetchAll();
        $retorno["tickets"] = $consultaTickets->fetchAll();


        //$sql = "SELECT * FROM movimentacaoCreditos WHERE dataMovimentacao >= :p1 && dataMovimentacao <= :p2 && tipoMovimentacao = 2 ORDER BY dataMovimentacao;";
        $sql = "SELECT count(*) FROM movimentacaoCreditos mc INNER JOIN pessoa p ON (p.idPessoa = mc.idFuncionario) LEFT JOIN grupo g ON (g.idGrupo = p.idEmpresa) WHERE mc.dataMovimentacao >= :p1 && mc.dataMovimentacao <= :p2 && mc.tipoMovimentacao = 2 && e.isGratuito = 1 ORDER BY dataMovimentacao;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $dataMin);
        $consulta->bindParam(":p2", $dataMax);
        $consulta->execute();

        $sql = "SELECT count(*) FROM tickets t INNER JOIN pedido p ON (p.idPedido = t.idPedido) WHERE t.dataUtilizacao >= :p1 && t.dataUtilizacao <= :p2 && isFree = 1 ORDER BY t.dataUtilizacao;";
        $consultaTickets = $conexao->prepare($sql);
        $consultaTickets->bindParam(":p1", $dataMin);
        $consultaTickets->bindParam(":p2", $dataMax);
        $consultaTickets->execute();

        $retorno["estatisticas"]["creditos"]["gratuitos"] = $consulta->fetch()["count(*)"];
        $retorno["estatisticas"]["creditos"]["pagos"] = $retorno["estatisticas"]["totalCreditos"] - $retorno["estatisticas"]["creditos"]["gratuitos"];
        $retorno["estatisticas"]["tickets"]["gratuitos"] = $consultaTickets->fetch()["count(*)"];
        $retorno["estatisticas"]["tickets"]["pagos"] = $retorno["estatisticas"]["totalTickets"] - $retorno["estatisticas"]["tickets"]["gratuitos"];


      }else{
        $sql = "SELECT dataMovimentacao, nomePessoa as nomeFuncionario, nomeGrupo as nomeEmpresa, isGratuito FROM movimentacaoCreditos mc INNER JOIN pessoa p ON (p.idPessoa = mc.idFuncionario) INNER JOIN grupo g ON (g.idGrupo = p.idEmpresa) WHERE mc.dataMovimentacao >= :p1 && mc.dataMovimentacao <= :p2 && e.idEmpresa = :p3 && mc.tipoMovimentacao = 2 ORDER BY dataMovimentacao;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $dataMin);
        $consulta->bindParam(":p2", $dataMax);
        $consulta->bindParam(":p3", $idEmpresa);
        $consulta->execute();

        $retorno["creditos"] = $consulta->fetchAll();

        //Dados para a Estatística
        $retorno["estatisticas"]["totalCreditos"] = $consulta->rowCount();
        $retorno["estatisticas"]["total"] = $retorno["estatisticas"]["totalCreditos"];

        $sql = "SELECT count(*) FROM movimentacaoCreditos mc INNER JOIN pessoa p ON(p.idPessoa = mc.idFuncionario) INNER JOIN grupo g ON(g.idGrupo = p.idEmpresa) WHERE dataMovimentacao >= :p1 && dataMovimentacao <= :p2 && tipoMovimentacao = 2 && isGratuito = 1;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1", $dataMin);
        $consulta->bindParam(":p2", $dataMax);
        $consulta->execute();

        $retorno["estatisticas"]["creditos"]["gratuitos"] = $consulta->fetch()["count(*)"];
        $retorno["estatisticas"]["creditos"]["pagos"] = $retorno["estatisticas"]["totalCreditos"] - $retorno["estatisticas"]["creditos"]["gratuitos"];

      }

    }
    if($filtro == 0 || $filtro == 1){
      $i = 1;
      //Foreach com todos os créditos movimentados no período... nesse foreach ocorre a separação por dia de cada caso, dando os valores por dia
      foreach($retorno["creditos"] as $inf){
        //Pega o dia da movimentação
        $dM = date("d",strtotime($inf["dataMovimentacao"]));
        $mM = date("m",strtotime($inf["dataMovimentacao"]));
        $yM = date("Y",strtotime($inf["dataMovimentacao"]));

        $retorno["filtro"][$yM][$mM][$dM]["E"]["creditos"]["gratuito"] = 0;
        $retorno["filtro"][$yM][$mM][$dM]["E"]["creditos"]["pago"] = 0;
        $retorno["filtro"][$yM][$mM][$dM]["E"]["creditos"]["totalCreditos"] = 0;
        $retorno["filtro"][$yM][$mM][$dM]["E"]["creditos"]["total"] = 0;

        //Joga dentro do vetor a linha, dentro do vetor correspondente a aquele dia
        $retorno["filtro"][$yM][$mM][$dM]["creditos"][$i]["R"] = $inf;
        //Verifica se o crédito utilizado é de uma Empresa Gratuita
        if($inf["isGratuito"] == 1){
          //Se é de uma Empresa Gratuita, incrementa o valor de gratuitos
          $retorno["filtro"][$yM][$mM][$dM]["E"]["creditos"]["gratuito"]++;
        }else{
          //Se é de uma Empresa Paga(que paga o Almoço), incrementa o valor de pagos
          $retorno["filtro"][$yM][$mM][$dM]["E"]["creditos"]["pago"]++;
        }


        //Incrementa o valor total...
        $retorno["filtro"][$yM][$mM][$dM]["E"]["creditos"]["total"]++;
        $i++;
      }
    }
    if($filtro == 0 || $filtro == 2){
      $i = 1;
      //Foreach com todos os créditos movimentados no período... nesse foreach ocorre a separação por dia de cada caso, dando os valores por dia
      foreach($retorno["tickets"] as $inf){
        //Pega o dia da movimentação
        $dM = date("d",strtotime($inf["dataUtilizacao"]));
        $mM = date("m",strtotime($inf["dataUtilizacao"]));
        $yM = date("Y",strtotime($inf["dataUtilizacao"]));

        $retorno["filtro"][$yM][$mM][$dM]["E"]["tickets"]["gratuito"] = 0;
        $retorno["filtro"][$yM][$mM][$dM]["E"]["tickets"]["pago"] = 0;
        $retorno["filtro"][$dM][$mM][$yM]["E"]["tickets"]["totalTickets"] = 0;
        if($retorno["filtro"][$yM][$mM][$dM]["E"]["creditos"]["total"] == NULL){
          $retorno["filtro"][$yM][$mM][$dM]["E"]["creditos"]["total"] = 0;
        }

        //Joga dentro do vetor a linha, dentro do vetor correspondente a aquele dia
        $retorno["filtro"][$yM][$mM][$dM]["tickets"][$i]["R"] = $inf;
        //Verifica se o crédito utilizado é de uma Empresa Gratuita
        if($inf["isGratuito"] == 1){
          //Se é de uma Empresa Gratuita, incrementa o valor de gratuitos
          $retorno["filtro"][$yM][$mM][$dM]["E"]["tickets"]["gratuito"]++;
        }else{
          //Se é de uma Empresa Paga(que paga o Almoço), incrementa o valor de pagos
          $retorno["filtro"][$yM][$mM][$dM]["E"]["tickets"]["pago"]++;
        }


        //Incrementa o valor total...
        $retorno["filtro"][$yM][$mM][$dM]["E"]["tickets"]["totalTickets"]++;
        $retorno["filtro"][$yM][$mM][$dM]["E"]["tickets"]["total"]++;
        $i++;
      }
    }

    isset($retorno["creditos"]);
    isset($retorno["tickets"]);

    return $retorno["filtro"];
  }

}
