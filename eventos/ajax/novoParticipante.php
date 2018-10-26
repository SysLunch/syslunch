<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "evento" => 1, "participante" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(2,0,"Evento")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $nomeParticipante = $_POST["nomeParticipante"];
  $idEvento = $_POST["idEvento"];
  $dataNascimento = explode("/", $_POST["nascimentoParticipante"]);
  if(checkdate($dataNascimento[1], $dataNascimento[0], $dataNascimento[2])){
    $nascimentoParticipante = $dataNascimento[2]."-".$dataNascimento[1]."-".$dataNascimento[0];
  }else{
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Data de nascimento inválida."));
    exit();
  }

  $retorno = $factory->getObjeto("participante")->novoParticipante($nomeParticipante, $nascimentoParticipante, $idEvento);
  echo $retorno;
