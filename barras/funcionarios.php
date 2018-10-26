<?php
  $idEmpresa = $_GET["id"];

  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "funcionario" => 1, "empresa" => 1));
  $factory->getObjeto("login")->permissaoPagina(1,1,"Empresa");

  if($idEmpresa == NULL){
    $ids = explode(",", $_GET["idsFuncionarios"]);
    $consulta = $factory->getObjeto("funcionario")->listaFuncionariosporId($ids);
  }else{
    $consulta = $factory->getObjeto("funcionario")->listaFuncionarios($idEmpresa);
  }

  if(count($consulta) == 0){
    header("location: ../index.php?e=nTF");
    exit();
  }


  ?>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?php echo $dadosEmpresa["nomeEmpresa"]; ?></title>
    <style>
      body{
        font-family: Arial, sans-serif;
        border: 0;
        margin: 0;
        padding: 0;
        width: 575px;
      }
      .funcionario{
        position:relative;
        width: 284px;
        height: 355px;
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
        width: 264px;
        height: 155px;
        text-align: center;
        padding: 190px 10px 10px 10px;
        position: absolute;
        top: 0px;
        left: 0px;
      }

    </style>
  </head>
  <body>
    <?php
    foreach($consulta as $inf){
      if($inf["ativoFuncionario"] == 1){
        if(strlen($inf["nomeFuncionario"]) > 30){
          $divisao = explode(" ", $inf["nomeFuncionario"]);
          $cont_divisao = count($divisao);
          $inf["nomeFuncionario"] = $divisao[0]." ".$divisao[$cont_divisao-1];
        }
        $consultaE = $factory->getObjeto("empresa")->buscaEmpresa($consulta[0]["idEmpresa"]);

        $dadosEmpresa = $consultaE["consulta"][0];
    ?>
    <div class="row">
      <div class="funcionario">
        <div id="fundo"><img src="../img/fundoFuncionario_v5.png" width="284" /></div>
        <div id="sobre">
          <img src="barcode.php?text=<?php echo $inf["codigoCartao"]; ?>&amp;size=70"><br>
          <span><b><?php echo $inf["codigoCartao"]; ?></b>
          <hr>
          <span>Nome: <b><?php echo $inf["nomeFuncionario"]; ?></b></span><br>
          <span>Empresa: <b><?php echo $dadosEmpresa["nomeEmpresa"]; ?></b></span><br>
        </div>
      </div>
    </div>
    <?php
      }
    }
    ?>
  </body>
</html>
