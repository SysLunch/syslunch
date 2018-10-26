<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "evento" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(2,0,"Evento")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $idEvento = $_GET["idEvento"];
  $nomeEvento = $_GET["nomeEvento"];
  $tipoLetra = $_GET["tipoLetra"];

  $retorno = $factory->getObjeto("evento")->editarEvento($idEvento, $nomeEvento, $tipoLetra);

  echo $retorno;
