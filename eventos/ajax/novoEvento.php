<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "evento" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(2,0,"Eventos")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $nomeEvento = $_POST["nomeEvento"];
  $tipoLetra = $_POST["tipoLetra"];
  $crt = $_POST["crt"];

  $retorno = $factory->getObjeto("evento")->novoEvento($nomeEvento, $tipoLetra, $crt);
  echo $retorno;
