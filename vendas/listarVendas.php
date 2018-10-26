<?php
  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 1;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "venda" => 1, "situacao" => 1, "empresa" => 1, "creditos" => 1));
  $factory->getObjeto("login")->permissaoPagina(2,1,"Vendas");

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Listar Vendas - Vendas - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Vendas >> Listar Vendas</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="index.php"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-arrow-left.svg" style="height: 25px !important" /> Voltar</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers">
    <table class="CRTConfigTable">
      <thead>
        <tr class="text-center">
          <th width="30">#</th>
          <th>Cliente</th>
          <th>Data</th>
          <th>Valor Total</th>
          <th>Situação</th>
          <th width="150">Opções</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $listaVendas = $factory->getObjeto("venda")->listaVendas();
        foreach($listaVendas as $inf){
      ?>
            <tr<?php if($inf["idSituacao"] == $factory->getObjeto("situacao")->buscaSituacaoporNome("Cancelado")["consulta"][0]["idSituacao"]) { ?> class="vendaCancelada"<?php }?>>
              <td><?php echo $inf["idPedido"]; ?></td>
              <td><?php if($inf["idEmpresa"] != NULL){ echo $factory->getObjeto("empresa")->buscaEmpresa($inf["idEmpresa"])["consulta"][0]["nomeEmpresa"]; }else{ echo "Cliente Não Identificado"; } ?></td>
              <td><?php echo ($inf["dataFinanceiro"] != NULL) ? date("d/m/Y",strtotime($inf["dataFinanceiro"])) : "-"; ?></td>
              <td>R$ <?php echo number_format($inf["valorTotal"], 2, ",", ""); ?></td>
              <td><?php echo $factory->getObjeto("situacao")->buscaSituacaoporIdparaLabel($inf["idSituacao"])["consulta"]; ?></td>
              <td class="opcoesLPedido">
                <?php if($inf["idSituacao"] == $factory->getObjeto("situacao")->buscaSituacaoporNome("Finalizado")["consulta"][0]["idSituacao"]) {
                  ?><a href="comprovanteVenda.php?id=<?php echo $inf["idPedido"]; ?>" target="_blank" title="Comprovante do Pedido Finalizado"><i class="fi-print iconesVendas"></i></a>
                <?php }
                      if($factory->getObjeto("venda")->hasTicket($inf["idPedido"])){
                  ?><a href="<?php echo $urlPrincipal[1]; ?>barras/ticket.php?id=<?php echo $inf["idPedido"]; ?>" target="_blank" title="Imprimir Tickets Restantes"><i class="fi-page iconesVendas<?php if(!$factory->getObjeto("creditos")->hasTickets($inf["idPedido"])){ ?> ticketsUsados<?php } ?>"></i></a>
                <?php } ?>
              </td>
            </tr>
      <?php
        }
      ?>
      </tbody>
    </table>
  </div>
</div>


<?php include($caminho."php/footer.php"); ?>


    <script src="<?php echo $caminho; ?>js/vendor/jquery.min.js"></script>
    <script src="<?php echo $caminho; ?>js/vendor/what-input.min.js"></script>
    <script src="<?php echo $caminho; ?>js/foundation.min.js"></script>
    <script src="<?php echo $caminho; ?>js/app.js"></script>
    <script src="<?php echo $caminho; ?>js/login.js"></script>
  </body>
</html>
