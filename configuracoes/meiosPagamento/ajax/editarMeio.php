<?php
  $caminho = "../../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "meioPagamento" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(3,0,"Configurações")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $idMeio = $_POST["idMeio"];
  $meioPagamento = $_POST["meioPagamento"];
  $actionPagamento = $_POST["actionPagamento"];

  $retorno = $factory->getObjeto("meioPagamento")->editarMeio($idMeio, $meioPagamento, $actionPagamento);

  echo $retorno;
