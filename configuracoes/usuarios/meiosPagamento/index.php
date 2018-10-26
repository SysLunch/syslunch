<?php
  $caminho = "../../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "meioPagamento" => 1));

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Meios de Pagamento - Configurações - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina">SisLunch >> Configurações >> Meios de Pagamento</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="../"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-arrow-left.svg" style="height: 25px !important" /> Voltar</a></li>
      <li><a href="novoMeio.php">Novo Meio de Pagamento</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers">
    <table class="CRTConfigTable">
      <thead>
        <tr class="text-center">
          <th width="30">#</th>
          <th>Meio de Pagamento</th>
          <th>URL</th>
          <th width="200">Opções</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $listaMeios = $factory->getObjeto("meioPagamento")->listaMeios();
        foreach($listaMeios as $inf){
      ?>
        <tr>
          <td><?php echo $inf["idMeio"]; ?></td>
          <td><?php echo $inf["meioPagamento"]; ?></td>
          <td><?php echo $inf["actionPagamento"]; ?></td>
          <td class="opcoesLPedido">
            <a href="editarMeio.php?id=<?php echo $inf["idMeio"]; ?>"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-pencil.svg" /></a>
            <a href="excluirMeio.php?id=<?php echo $inf["idMeio"]; ?>"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-x.svg" /></a>
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
