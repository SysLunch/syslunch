<?php
  $caminho = "../../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "localPedido" => 1));
  $factory->getObjeto("login")->permissaoPagina(3,1,"Configurações");

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Locais Pedido - Configurações - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina">SisLunch >> Configurações >> Locais Pedido</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="../"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-arrow-left.svg" style="height: 25px !important" /> Voltar</a></li>
      <li><a href="novoLocal.php">Novo Local</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers">
    <table class="CRTConfigTable">
      <thead>
        <tr class="text-center">
          <th width="30">#</th>
          <th>Nome do Local</th>
          <th width="200">Opções</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $listaCRTs = $factory->getObjeto("localPedido")->listaLocais();
        foreach($listaCRTs as $inf){
      ?>
        <tr>
          <td><?php echo $inf["idLPedido"]; ?></td>
          <td><?php echo $inf["nomeLocal"]; ?></td>
          <td class="opcoesLPedido">
            <a href="editarLocal.php?id=<?php echo $inf["idLPedido"]; ?>"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-pencil.svg" /></a>
            <a href="excluirLocal.php?id=<?php echo $inf["idLPedido"]; ?>"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-x.svg" /></a>
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
