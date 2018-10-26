<?php
  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $id = $_GET["id"];

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "venda" => 1, "funcionario" => 1, "empresa" => 1, "meioPagamento" => 1, "tipoProduto" => 1, "item" => 1, "financeiro" => 1));
  $factory->getObjeto("login")->permissaoPagina(2,1,"Venda");

  $venda = $factory->getObjeto("venda")->buscaVenda($id);

  if($venda["count"] == 0){
    header("location: index.php?e=NTV");
    exit();
  }

  if($venda["consulta"][0]["idEmpresa"] == NULL || $venda["consulta"][0]["idEmpresa"] == 0){
    $hasEmpresa = false;
  }else{
    $hasEmpresa = true;
    $empresa = $factory->getObjeto("empresa")->buscaEmpresa($venda["consulta"][0]["idEmpresa"]);
  }

  $meioPagamento = $factory->getObjeto("meioPagamento")->buscaMeio($venda["consulta"][0]["idMeioPagamento"]);
  $financeiro = $factory->getObjeto("financeiro")->buscaMovimentacaoporPedido($id);

  if($financeiro["count"] == 0){
    header("location: index.php?e=NTV-F");
    exit();
  }



?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Comprovante de Venda #<?php echo $id; ?> - <?php echo $nomeSite; ?></title>
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
          <td colspan="3" rowspan="2"><img src="<?php echo $urlPrincipal[1].$logo; ?>" style="height: 90px !important;"></td>
          <td colspan="4"><h1><b>Comprovante de Venda de Almoço</b></h1></td>
        </tr>
        <tr>
          <td colspan="4"><h6>Pedido: <b>#<?php echo $id; ?></b> - <?php if($hasEmpresa){ echo "Empresa: <b>".$empresa["consulta"][0]["nomeEmpresa"]; } ?></b></h6></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td colspan="7" class="text-center borderTop"><h4><b>Itens do Pedido</b></h4></td>
        </tr>
      </tbody>
    </table>

    <table width="100%">
      <thead class="borderAll">
        <tr>
          <td colspan="4">Tipo</td>
          <td colspan="5">Receptor</td>
          <td colspan="1">Valor Unitário(R$)</td>
          <td colspan="1">Quantidade</td>
          <td colspan="1">Valor Total(R$)</td>
        </tr>
      </thead>
      <tbody>
        <?php
          $itens = $factory->getObjeto("item")->listaItensPedido($id);
          if(count($itens) > 0){
            $valorTotal = 0.00;
            $qtd = 0;
            foreach($itens as $inf){
              $valorTotal += $inf["valorTotal"];
              $qtd += $inf["quantidade"];
        ?>
        <tr>
          <td colspan="4"><?php echo $inf["tipoProduto"]; ?></td>
          <td colspan="5"><?php echo ($inf["funcionario"] != NULL) ? $inf["funcionario"] : "-"; ?></td>
          <td colspan="1"><?php echo number_format($inf["valorUnitario"], 2, ",", ""); ?></td>
          <td colspan="1"><?php echo $inf["quantidade"]; ?></td>
          <td colspan="1"><?php echo number_format($inf["valorTotal"], 2, ",", ""); ?></td>
        </tr>
        <?php
            }
            ?>
            <tr>
              <td colspan="4"></td>
              <td colspan="5"></td>
              <td colspan="1"><b>Total:</b></td>
              <td colspan="1"><?php echo $qtd; ?></td>
              <td colspan="1"><?php echo number_format($valorTotal, 2, ",", ""); ?></td>
            </tr>
            <tr>
              <td colspan="4"></td>
              <td colspan="3"></td>
              <td colspan="1"></td>
              <td colspan="2"><b>Forma de Pagamento:</b></td>
              <td colspan="2"><?php echo $meioPagamento["consulta"][0]["meioPagamento"]; ?></td>
            </tr>
            <tr>
              <td colspan="4"></td>
              <td colspan="3"></td>
              <td colspan="1"></td>
              <td colspan="2"><b>Pedido Finalizado em:</b></td>
              <td colspan="2"><?php echo date("d/m/Y H:i:s", strtotime($financeiro["consulta"][0]["dataFinanceiro"])); ?></td>
            </tr>
            <tr>
              <td colspan="4"></td>
              <td colspan="3"></td>
              <td colspan="1"></td>
              <td colspan="2"><b>Pedido Gerado por:</b></td>
              <td colspan="2"><?php echo $venda["consulta"][0]["nomeUsuario"]; ?></td>
            </tr>
            <?php
          }else{

          }

        ?>
      </tbody>
    </table>


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
