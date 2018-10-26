<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "empresa" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(2,0,"Empresa")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $idEmpresa = $_POST["idEmpresa"];
  $senhaEmpresa = $_POST["senhaEmpresa"];
  $confSenhaEmpresa = $_POST["confSenhaEmpresa"];
  $novaSenha = $_POST["novaSenha"];

  $retorno = $factory->getObjeto("empresa")->alterarSenhaEmpresa($idEmpresa, $senhaEmpresa, $confSenhaEmpresa, $novaSenha);

  echo $retorno;
