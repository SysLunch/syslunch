<?php
  $idPedido = $_GET["id"];

  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "funcionario" => 1, "empresa" => 1, "creditos" => 1));
  $factory->getObjeto("login")->permissaoPagina(2,1,"Pedido");

  $consulta = $factory->getObjeto("creditos")->listaTickets($idPedido);


  if(count($consulta) == 0){
    header("location: ../index.php?e=nTF");
    exit();
  }
  ?>
<html>
  <head>
    <meta charset="utf-8" />
    <title>Tickets do Pedido #<?php echo $idPedido; ?></title>
    <style>
      body{
        font-family: Arial, sans-serif;
        border: 0;
        margin: 0;
        padding: 0;
        width: 575px;
      }
      .ticket{
        position:relative;
        width: 284px;
        height: 178px;
        float: left;
        margin-left: 3px;
        margin-bottom: 3px;
      }
      #fundo{
         position:relative;
         top:0px;
         left:0px;
         width: 284px;
         height: 178px;
      }
      #sobre{
        width: 264px;
        height: 0px;
        text-align: center;
        padding: 35px 0px 10px 0px;
        position: absolute;
        top: 0px;
        left: 0px;
      }

    </style>
  </head>
  <body>
    <?php
    foreach($consulta as $inf){
      if($inf["situacaoTicket"] == 1){
    ?>
    <div class="row">
      <div class="ticket">
        <div id="fundo"><img src="../img/fundoTicket<?php if($inf["isFree"] == 1) { ?>Free_v1<?php }else{ ?>_v1<?php } ?>.png" width="284" /></div>
        <div id="sobre">
          <img src="barcode.php?text=<?php echo $inf["codigoTicket"]; ?>&amp;size=70"><br>
          <span><b><?php echo $inf["codigoTicket"]; ?></b><br>
          <span><small>Válido até <b><?php echo date("d/m/Y",strtotime($inf["dataVencimento"])); ?></b></small></span><br>
        </div>
      </div>
    </div>
    <?php
      }
    }
    ?>
  </body>
</html>
