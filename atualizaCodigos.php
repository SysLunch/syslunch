<?php
  $id = $_GET["id"];
  $barras = ($_GET["barras"] == 1) ? 1 : 0;

  $caminho = "";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 3;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  // $factory->importaClasses(array("login" => 1, "empresa" => 1, "funcionario" => 1));


  $conexao = $factory->getObjeto("conexao")->getInstance();

  $consulta = $conexao->prepare("SELECT * FROM pessoa;");
  $consulta->execute();
  $retorno = $consulta->fetchAll();
  foreach($retorno as $inf){
      $consulta = $conexao->prepare("UPDATE reservaCodigos SET idFuncionario = :p1 WHERE codigoReserva = :p2");
      $consulta->bindParam(":p1",$inf["idPessoa"]);
      $consulta->bindParam(":p2",$inf["codigoCartao"]);
      $consulta->execute();
      echo $inf["codigoCartao"];
  }
