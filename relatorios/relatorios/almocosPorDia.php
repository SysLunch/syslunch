<?php
  $caminho = "../../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $idEmpresa = $_GET["idEmpresa"];
  $data = $_GET["data"];
  $filtro = ($_GET["filtro"]) ? $_GET["filtro"] : 0;

  if(($data == NULL && $filtro == NULL) || $data == NULL){
    ?><script>window.close();</script><?php
    exit();
  }

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "relatorio" => 1, "funcionario" => 1, "empresa" => 1));
  $factory->getObjeto("login")->permissaoPagina(2,1,"Venda");

  $relatorio = $factory->getObjeto("relatorio")->almocosPorDia($data, $idEmpresa, $filtro);

  $filtros[0] = "Cartão e Ticket";
  $filtros[1] = "Cartão";
  $filtros[2] = "Ticket";

//print_r($relatorio);

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Relatório de Almoços por Dia - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/impressao.css" />
  </head>
  <body>
    <table width="100%">
      <thead class="borderAll">
        <tr id="header">
          <td colspan="6" rowspan="2"><img src="<?php echo $urlPrincipal[1].$logo; ?>" style="height: 90px !important;"></td>
          <td colspan="4"><h1><b>Relatório: Almoços por Dia</b></h1></td>
        </tr>
        <tr>
          <td colspan="1"><h6>Data: <b><?php echo date("d/m/Y",strtotime($data)); ?></b></h6></td>
          <td colspan="3"><h6>Filtro: <b><?php echo $filtros[$filtro]; ?></b></h6></td>
        </tr>
      </thead>
    </table>
    <?php

      if(count($relatorio["creditos"]) > 0 && ($filtro == 0 || $filtro == 1)){
    ?>
    <table width="100%">
      <tbody>
        <tr>
          <td colspan="10" class="text-center borderTop"><h4><b>Cartão</b></h4></td>
        </tr>
      </tbody>
    </table>
    <table width="100%" class="tableListaRelatorio">
      <thead class="borderAll">
        <tr>
          <td colspan="2">#</td>
          <td colspan="2">Hora</td>
          <td colspan="4">Funcionário</td>
          <td colspan="2">Empresa</td>
          <td colspan="2">Tipo Almoço</td>
        </tr>
      </thead>
      <tbody>
    <?php
        $i = 1;
        foreach($relatorio["creditos"] as $inf){
    ?>
        <tr>
          <td colspan="2"><?php echo $i; ?></td>
          <td colspan="2"><?php echo date("H:i:s",strtotime($inf["dataMovimentacao"])); ?></td>
          <td colspan="4"><?php echo $inf["nomeFuncionario"]; ?></td>
          <td colspan="2"><?php if($inf["nomeEmpresa"] != NULL){ echo "<b>".$inf["nomeEmpresa"]."</b>"; }else{ echo "-"; } ?></td>
          <td colspan="2"><?php /*echo "<span class='label'>Almoço</span> ";*/ if($inf["isGratuito"] == 0){ echo "<span class='label alert'>Pago</span>"; }else{ echo "<span class='label success'>Gratuito</span>"; } ?></td>
        </tr>
    <?php
            $i++;
          }
    ?>
      </tbody>
    </table>
    <?php
        }
    ?>
    <?php

      if(count($relatorio["tickets"]) > 0 && ($filtro == 0 || $filtro == 2)){
    ?>

    <table width="100%">
      <tbody>
        <tr>
          <td colspan="10" class="text-center borderTop"><h4><b>Ticket</b></h4></td>
        </tr>
      </tbody>
    </table>
    <table width="100%" class="tableListaRelatorio">
      <thead class="borderAll">
        <tr>
          <td colspan="2">#</td>
          <td colspan="2">Hora</td>
          <td colspan="4">Tipo de Ticket</td>
          <td colspan="2">Código do Ticket</td>
        </tr>
      </thead>
      <tbody>
    <?php
      $i = 1;
      foreach($relatorio["tickets"] as $inf){
    ?>
        <tr>
          <td colspan="2"><?php echo $i; ?></td>
          <td colspan="2"><?php echo date("H:i:s",strtotime($inf["dataUtilizacao"])); ?></td>
          <td colspan="4"><?php if($inf["isFree"] == 1){ echo "<span class='label success'>Gratuito</span>"; }else{ echo "<span class='label alert'>Pago</span>";} ?></td>
          <td colspan="2"><?php echo $inf["codigoTicket"]; ?></td>
        </tr>
    <?php
        $i++;
      }
    ?>
      </tbody>
    </table>
    <?php
      }
    ?>

    <table width="100%">
      <tbody>
        <tr>
          <td colspan="10" class="text-center borderTop"><h4><b>Resumo</b></h4></td>
        </tr>
      </tbody>
    </table>
    <table width="100%">
      <tbody>
        <?php if(($filtro == 0 || $filtro == 1) || $idEmpresa != 0){ ?><tr>
          <td colspan="5"><b>Total de Créditos Utilizados:</b> <?php echo $relatorio["estatisticas"]["totalCreditos"]; ?></td>
          <td colspan="5"><b>Demonstrativo de Créditos:</b> <?php echo $relatorio["estatisticas"]["creditos"]["pagos"]; ?> Almoço(s) Pago(s) e <?php echo $relatorio["estatisticas"]["creditos"]["gratuitos"]; ?> Almoço(s) Gratuito(s)</td>
        </tr><?php } ?>
        <?php if(($filtro == 0 || $filtro == 2) && $idEmpresa == 0){ ?><tr>
          <td colspan="5"><b>Total de Tickets Utilizados:</b> <?php echo $relatorio["estatisticas"]["totalTickets"]; ?></td>
          <td colspan="5"><b>Demonstrativo de Tickets:</b> <?php echo $relatorio["estatisticas"]["tickets"]["pagos"]; ?> Ticket(s) e <?php echo $relatorio["estatisticas"]["tickets"]["gratuitos"]; ?> Ticket(s) Gratuito(s)</td>
        </tr><?php } ?>
        <tr>
          <td colspan="10"><b>Total de Almoços Registrados:</b> <?php echo $relatorio["estatisticas"]["total"]; ?></td>
        </tr>
      </tbody>
    </table>
    <small>Relatório gerado em <?php echo date("d/m/Y"); ?> às <?php echo date("H:i:s"); ?>. <?php if(date("Y-m-d") == $data){ ?>Esse relatório foi gerado no mesmo dia do pesquisado e poderá haver alterações até o final do dia.<?php } ?></small>


    <script src="<?php echo $caminho; ?>js/vendor/jquery.min.js"></script>
    <script src="<?php echo $caminho; ?>js/vendor/what-input.min.js"></script>
    <script src="<?php echo $caminho; ?>js/foundation.min.js"></script>
    <script src="<?php echo $caminho; ?>js/jquery.mask.min.js"></script>
    <script src="<?php echo $caminho; ?>js/jquery.formatCurrency.min.js"></script>
    <script src="<?php echo $caminho; ?>js/app.js"></script>
    <script src="<?php echo $caminho; ?>js/login.js"></script>
    <script src="js/novaVenda.js"></script>
  </body>
</html>
