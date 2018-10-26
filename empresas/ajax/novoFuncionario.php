<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "empresa" => 1, "funcionario" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(2,0,"Empresas")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $nomeFuncionario = $_POST["nomeFuncionario"];
  $idEmpresa = $_POST["idEmpresa"];
  $dataNascimento = explode("/", $_POST["nascimentoFuncionario"]);
  if(checkdate($dataNascimento[1], $dataNascimento[0], $dataNascimento[2])){
    $nascimentoFuncionario = $dataNascimento[2]."-".$dataNascimento[1]."-".$dataNascimento[0];
  }else{
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Data de nascimento inválida."));
    exit();
  }

  $retorno = $factory->getObjeto("funcionario")->novoFuncionario($nomeFuncionario, $nascimentoFuncionario, $idEmpresa);
  echo $retorno;
