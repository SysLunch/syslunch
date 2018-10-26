<?php
  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 5;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1));
  $factory->getObjeto("login")->permissaoPagina(3,1,"Configurações");

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Configurações - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include("../php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Configurações</div>
    <div class="subtituloPagina">Selecione uma das opções abaixo:</div>
  </div>
  <div class="medium-24 columns centers">
    <a href="CRT/" class="button bigHome">Locais CRT</a>
  </div>
  <?php /* <div class="medium-16 columns centers">
    <a href="locaisPedido/" class="button bigHome">Locais Pedido</a>
  </div> */ ?>
  <div class="medium-24 columns centers">
    <a href="usuarios/" class="button bigHome">Usuários</a>
  </div>
  <div class="medium-48 columns centers">
    <a href="#" class="button bigHome disabled">Outras Configs.</a>
  </div>
  <?php /*<div class="medium-24 columns centers">
    <a href="meiosPagamento/" class="button bigHome">Meios de Pagamento</a>
  </div> */ ?>
</div>


<?php include("../php/footer.php"); ?>


    <script src="<?php echo $caminho; ?>js/vendor/jquery.min.js"></script>
    <script src="<?php echo $caminho; ?>js/vendor/what-input.min.js"></script>
    <script src="<?php echo $caminho; ?>js/foundation.min.js"></script>
    <script src="<?php echo $caminho; ?>js/app.js"></script>
    <script src="<?php echo $caminho; ?>js/login.js"></script>
  </body>
</html>
