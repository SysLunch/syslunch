<?php
  $idEvento = $_GET["id"];

  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "evento" => 1, "participante" => 1));
  $factory->getObjeto("login")->permissaoPagina(1,1,"Evento");

  if($idEvento == NULL){
    $ids = explode(",", $_GET["idsParticipantes"]);
    $consulta = $factory->getObjeto("participante")->listaParticipantesporId($ids);
  }else{
    $consulta = $factory->getObjeto("participante")->listaParticipantes($idEvento);
  }

  if(count($consulta) == 0){
    header("location: ../index.php?e=nTF");
    exit();
  }


  ?>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?php echo $dadosEvento["nomeEmpresa"]; ?></title>
    <style>
      body{
        font-family: Arial, sans-serif;
        border: 0;
        margin: 0;
        padding: 0;
        width: 700px;
      }
      .funcionario{
        position:relative;
        width: 150px;
        height: 115px;
        float: left;
        margin-left: 3px;
        margin-bottom: 3px;
      }
      #fundo{
         position:relative;
         top:0px;
         left:0px;
         width: 284px;
         height: 355px;
      }
      #sobre{
        width: 130px;
        height: 95px;
        text-align: center;
        padding: 10px;
        position: absolute;
        top: 0px;
        left: 0px;
      }

      .small{
        font-size: 10px;
      }

    </style>
  </head>
  <body>
    <?php
    foreach($consulta as $inf){
      if($inf["ativoPessoa"] == 1){
        if(strlen($inf["nomePessoa"]) > 23){
          $divisao = explode(" ", $inf["nomePessoa"]);
          $cont_divisao = count($divisao);
          $inf["nomePessoa"] = $divisao[0]." ".$divisao[$cont_divisao-1];
        }
        $consultaE = $factory->getObjeto("evento")->buscaEvento($consulta[0]["idEvento"]);

        $dadosEvento = $consultaE["consulta"][0];
    ?>
    <div class="row">
      <div class="funcionario">
        <div id="sobre">
          <img src="barcode.php?text=<?php echo $inf["codigoCartao"]; ?>&amp;size=60" width="100%"><br>
          <span class="small"><b><?php echo $inf["codigoCartao"]; ?></b></span><br>
          <span class="small"><b><?php echo $inf["nomePessoa"]; ?></b></span>
          <hr>
        </div>
      </div>
    </div>
    <?php
      }
    }
    ?>
  </body>
</html>
