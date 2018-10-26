<?php
  $id = $_GET["id"];

  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "local" => 1));
  $factory->getObjeto("login")->permissaoPagina(3,1,"CRT");

  $consulta = $factory->getObjeto("local")->buscaLocal($id);


  if($consulta["count"] == 0){
    header("location: ../index.php?e=nTL");
    exit();
  }
  $dadosLocal = $consulta["consulta"][0];


  ?>
  <html>
    <head>
      <meta charset="utf-8" />
      <title><?php echo $dadosLocal["descricaoLocal"]; ?></title>
      <style>
        body{
          font-family: Arial, sans-serif;
          border: 0;
          margin: 0;
          padding: 0;
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
          position:absolute;
          top:0px;
          left:0px;
        }

      </style>
    </head>
    <body>
      <div id="fundo"><img src="../img/fundoCRT_v2.png" width="284" /></div>
      <div id="sobre">
        <img src="barcode.php?text=<?php echo $dadosLocal["hashLocal"]; ?>&amp;size=70"><br>
        <span><b><?php echo $dadosLocal["hashLocal"]; ?></b>
        <hr>
        <span>Local: <b><?php echo $dadosLocal["descricaoLocal"]; ?></span><br>
      </div>
    </body>
  </html>
