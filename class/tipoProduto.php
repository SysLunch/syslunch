<?php

class TipoProduto{
  function __construct(){

  }

  function listaTiposProdutos($isEmpresa = 1){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();
      if($isEmpresa == 0){
        $sql = "SELECT * FROM tipoProduto WHERE toEmpresa = :p1;";
        $consulta = $conexao->prepare($sql);
        $consulta->bindParam(":p1",$isEmpresa);
      }else{
        $sql = "SELECT * FROM tipoProduto;";
        $consulta = $conexao->prepare($sql);
      }
      $consulta->execute();

      return $consulta->fetchAll();
  }
  function listaTiposProdutosporTipoTransacao($tipoTransacao = 1, $isFree = 0){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      if($isEmpresa == 0){
        $sql = "SELECT * FROM tipoProduto WHERE isFree = :p1 && tipoTransacao = :p2;";
      }else{
        $sql = "SELECT * FROM tipoProduto WHERE isFree = :p1;";
      }
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $isFree);
      if($isEmpresa == 0){
        $consulta->bindParam(":p2", $tipoTransacao);
      }
      $consulta->execute();

      return $consulta->fetchAll();
  }

  function buscaTipoProduto($idTipoProduto){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM tipoProduto WHERE idTipoProduto = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $idTipoProduto);
      $consulta->execute();

      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }


  function buscaTipoProdutoporNome($descricaoProduto){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM tipoProduto WHERE descricaoProduto = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $descricaoProduto);
      $consulta->execute();

      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }


  function buscaTipoProdutoporTipoTransacao($tipoTransacao){
      global $factory;
      $conexao = $factory->getObjeto("conexao")->getInstance();

      $sql = "SELECT * FROM tipoProduto WHERE tipoTransacao = :p1;";
      $consulta = $conexao->prepare($sql);
      $consulta->bindParam(":p1", $tipoTransacao);
      $consulta->execute();

      if($consulta->rowCount() > 0){
        return array("count" => 1, "consulta" => $consulta->fetchAll());
      }else{
        return array("count" => 0);
      }
  }
}
