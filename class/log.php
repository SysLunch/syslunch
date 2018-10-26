<?php

class Log{
  function __construct(){

  }

  function cadastraLog($idEmpresa = NULL, $idUsuario = NULL, $descricao, $tabela = NULL, $identificacao = NULL){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      if($idEmpresa != NULL){
        if(!$factory->isSetted("empresa")){
          $factory->importarClasses(array("empresa" => 1));
        }
        $empresa = $factory->getObjeto("empresa")->buscaEmpresa($idEmpresa);
      }

      $sql = "INSERT INTO log (";
      $campos[0] = "";
      $i = 0;


      if($idEmpresa != NULL){
        $campos[$i]["campo"] = "idEmpresa";
        $campos[$i]["valor"] = $idEmpresa;
        $campos[$i]["identificador"] = ":p".$i;
        $i++;
      }
      if($idUsuario != NULL){
        $campos[$i]["campo"] = "idUsuario";
        $campos[$i]["valor"] = $idUsuario;
        $campos[$i]["identificador"] = ":p".$i;
        $i++;
      }
      if($descricao != NULL){
        $campos[$i]["campo"] = "descricaoLog";
        $campos[$i]["valor"] = $descricao;
        $campos[$i]["identificador"] = ":p".$i;
        $i++;
      }
      if($tabela != NULL){
        $campos[$i]["campo"] = "tabela";
        $campos[$i]["valor"] = $tabela;
        $campos[$i]["identificador"] = ":p".$i;
        $i++;
      }
      if($identificacao != NULL){
        $campos[$i]["campo"] = "identificacao";
        $campos[$i]["valor"] = $identificacao;
        $campos[$i]["identificador"] = ":p".$i;
        $i++;
      }


      $count = count($campos);
      $l = 1;
      foreach($campos as $inf){
        $sql .= $inf["campo"];
        if($l < $count){
          $sql .= ", ";
        }
        $l++;
      }

      $sql .= ", dataLog) VALUES (";

      $l = 1;
      foreach($campos as $inf){
        $sql .= $inf["identificador"];
        if($l < $count){
          $sql .= ", ";
        }
        $l++;
      }
      $sql .= ", :p".$i.");";
      // echo $sql;
      $consulta = $conexao->prepare($sql);
      foreach($campos as $inf){
        $consulta->bindParam($inf["identificador"], $inf["valor"]);
      }
      $consulta->bindParam(":p".$i, date("Y-m-d H:i:s"));
      $consulta->execute();
      return true;
  }
}
