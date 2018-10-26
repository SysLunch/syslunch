<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "empresa" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(2,0,"Empresas")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $nomeEmpresa = $_POST["nomeEmpresa"];
  $loginEmpresa = $_POST["loginEmpresa"];
  $senhaEmpresa = $_POST["senhaEmpresa"];
  $tipoLetra = $_POST["tipoLetra"];

  $retorno = $factory->getObjeto("empresa")->novaEmpresa($nomeEmpresa, $loginEmpresa, $senhaEmpresa, $tipoLetra);
  echo $retorno;
