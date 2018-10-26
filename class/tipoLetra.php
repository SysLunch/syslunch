<?php

class TipoLetra{
  function __construct(){

  }

  // Tipos de Reserva de letra
  // 1 - Cartão
  // 2 - Ticket
  // 3 - CRT
  // 4 - Eventos
  // 5 - Eventos com CRT

  // Há eventos que tem o seu próprio CRT, porém, o uso do CRT do evento não desobriga a necessidade de informarem o CRT do local onde está sendo registrado os tickets...

  // Esse tipo de CRT serve para poder aproveitar um código de barras de inscrição dos participantes do evento no sistema..

  function listaLetras($tipo = 0, $tipoGrupo = NULL){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    if($tipo != 0){
      $sql = "SELECT idLetra, letraReserva, isGratuito, descricaoTReserva, textoTipo, texto FROM reservaLetras rl INNER JOIN tipoReserva tr ON(tr.idTipoReserva = rl.idTipoReserva) WHERE tipoUso = :p1";
      if($tipoGrupo != NULL){
        $sql .= " && tipoGrupo = :p2;";
      }
    }else{
      $sql = "SELECT idLetra, letraReserva, isGratuito, descricaoTReserva, textoTipo, texto FROM reservaLetras rl INNER JOIN tipoReserva tr ON(tr.idTipoReserva = rl.idTipoReserva)";
      if($tipoGrupo != NULL){
        $sql .= " WHERE tipoGrupo = :p2;";
      }
    }
    // echo $sql;
    $consulta = $conexao->prepare($sql);
    if($tipo != 0){
      $consulta->bindParam(":p1", $tipo);
    }
    if($tipoGrupo != NULL){
      $consulta->bindParam(":p2", $tipoGrupo);
    }
    $consulta->execute();

    return $consulta->fetchAll();
  }

  function buscaLetras($idLetra){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    $sql = "SELECT idLetra, letraReserva, isGratuito, descricaoTReserva, tipoUso, textoTipo, texto, tipoGrupo FROM reservaLetras rl INNER JOIN tipoReserva tr ON(tr.idTipoReserva = rl.idTipoReserva) WHERE rl.idLetra = :p1;";

    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1", $idLetra);
    $consulta->execute();

    return $consulta->fetchAll();
  }

  function buscaLetraporLetra($letra){
    global $factory;
    $conexao = $factory->getObjeto("conexao")->getInstance();

    $sql = "SELECT idLetra, letraReserva, isGratuito, descricaoTReserva, tipoUso, textoTipo, texto FROM reservaLetras rl INNER JOIN tipoReserva tr ON(tr.idTipoReserva = rl.idTipoReserva) WHERE rl.letraReserva = :p1;";

    $consulta = $conexao->prepare($sql);
    $consulta->bindParam(":p1", $letra);
    $consulta->execute();

    return $consulta->fetchAll();
  }
}
