<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "funcionario" => 1, "empresa" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(3,0,"Empresa")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $idFuncionario = $_GET["idFuncionario"];
  $nomeFuncionario = $_GET["nomeFuncionario"];
  $dataNascimento = explode("/", $_GET["nascimentoFuncionario"]);
  if(checkdate($dataNascimento[1], $dataNascimento[0], $dataNascimento[2])){
    $nascimentoFuncionario = $dataNascimento[2]."-".$dataNascimento[1]."-".$dataNascimento[0];
  }else{
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Data de nascimento inválida."));
    exit();
  }

  $retorno = $factory->getObjeto("funcionario")->editarFuncionario($idFuncionario, $nomeFuncionario, $nascimentoFuncionario);

  echo $retorno;
