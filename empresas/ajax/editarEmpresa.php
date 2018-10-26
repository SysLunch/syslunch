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

  $idEmpresa = $_GET["idEmpresa"];
  $nomeEmpresa = $_GET["nomeEmpresa"];
  $loginEmpresa = $_GET["loginEmpresa"];
  $tipoLetra = $_GET["tipoLetra"];

  $retorno = $factory->getObjeto("empresa")->editarEmpresa($idEmpresa, $nomeEmpresa, $loginEmpresa, $tipoLetra);

  echo $retorno;
