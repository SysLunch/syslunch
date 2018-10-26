<?php
  $id = $_GET["id"];

  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "funcionario" => 1, "empresa" => 1));
  $factory->getObjeto("login")->permissaoPagina(1,1,"Empresa");

  $consulta = $factory->getObjeto("funcionario")->buscaFuncionario($id);


  if($consulta["count"] == 0){
    header("location: ../index.php?e=nTF");
    exit();
  }
  $dadosFuncionario = $consulta["consulta"][0];
  $consultaE = $factory->getObjeto("empresa")->buscaEmpresa($dadosFuncionario["idEmpresa"]);

  if($consultaE["count"] == 0){
    header("location: ../index.php?e=nTFE");
    exit();
  }

  $dadosEmpresa = $consultaE["consulta"][0];


  if(strlen($dadosFuncionario["nomeFuncionario"]) > 30){
    $divisao = explode(" ", $dadosFuncionario["nomeFuncionario"]);
    $cont_divisao = count($divisao);
    $dadosFuncionario["nomeFuncionario"] = $divisao[0]." ".$divisao[$cont_divisao-1];
  }


  ?>
<html>
  <head>
    <meta charset="utf-8" />
    <title><?php echo $dadosFuncionario["nomeFuncionario"]; ?> - <?php echo $dadosEmpresa["nomeEmpresa"]; ?></title>
    <style>
      body{
        font-family: Arial, sans-serif;
        border: 0;
        margin: 0;
        padding: 0;
      }
      .funcionario{
         position:relative;
        width: 284px;
        height: 355px;
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
    <div class="funcionario">
      <div id="fundo"><img src="../img/fundoFuncionario_v5.png" width="284" /></div>
      <div id="sobre">
        <img src="barcode.php?text=<?php echo $dadosFuncionario["codigoCartao"]; ?>&amp;size=70"><br>
        <span><b><?php echo $dadosFuncionario["codigoCartao"]; ?></b>
        <hr>
        <span>Nome: <b><?php echo $dadosFuncionario["nomeFuncionario"]; ?></b></span><br>
        <span>Empresa: <b><?php echo $dadosEmpresa["nomeEmpresa"]; ?></b></span><br>
      </div>
    </div>
  </body>
</html>
