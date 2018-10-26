<?php
  $id = $_GET["id"];

  $caminho = "../../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 6;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "evento" => 1, "participante" => 1));
  $factory->getObjeto("login")->permissaoPagina(2,1,"Eventos");

  $consulta = $factory->getObjeto("evento")->buscaEvento($id);

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Alterar Participante por Código - Eventos - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="<?php echo $caminho; ?>assets/foundation-icon-fonts/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/CRT.css?v<?php echo $versaoParaJSCSS; ?>" />
    <!-- <link href='https://fonts.googleapis.com/css?family=Ubuntu:400,700,500' rel='stylesheet' type='text/css'> -->
    <!-- <link href='https://fonts.googleapis.com/css?family=Cabin:400,700|Abel|Josefin+Sans:400,700|Questrial' rel='stylesheet' type='text/css'> -->
    <link href='https://fonts.googleapis.com/css?family=Cabin:400,700' rel='stylesheet' type='text/css'>
  </head>
  <body>

<?php include($caminho."php/header.php");

 ?>

    <div class="row">

      <div class="medium-48 columns centers displayNone" id="avisoSucesso">
        <div class="callout success">
          <h5>SUCESSO!</h5>
          <p></p>
        </div>
      </div>
      <div class="medium-48 columns centers displayNone" id="avisoErro">
        <div class="callout alert">
          <h5>ERRO!</h5>
          <p></p>
        </div>
      </div>
    </div>

    <div class="row" id="status">
      <div class="medium-48 columns displayNone" id="carregando">
        <i id="carregandoI" class="fi-loop colorBlue"></i><!-- Icone -->
        <h3 class="colorBlue">CARREGANDO...</h3><!-- CARREGANDO -->
        <h1 class="colorBlue">Por Favor, Aguarde...</h1><!-- BOM ALMOÇO, ERRO, AVISO -->
        <hr>
      </div>
    </div>
    <div class="row" id="barras">
      <div class="medium-32 medium-offset-8 columns barras">
        <h4 id="tituloCodigoBarras">Passe o código de barras do Participante:</h4>
        <input type="text" id="codigoBarras" autofocus="autofocus" />
      </div>
    </div>

<?php

include($caminho."php/footer.php"); ?>


    <script src="<?php echo $caminho; ?>js/vendor/jquery.min.js"></script>
    <script src="<?php echo $caminho; ?>js/vendor/what-input.min.js"></script>
    <script src="<?php echo $caminho; ?>js/foundation.min.js"></script>
    <script src="<?php echo $caminho; ?>js/app.js"></script>
    <script src="js/CRT.js?v<?php echo $versaoParaJSCSS; ?>"></script>
    <script src="js/none.js?v<?php echo $versaoParaJSCSS; ?>"></script>
    <script src="js/consultaCRT.js?v<?php echo $versaoParaJSCSS; ?>"></script>
    <script src="js/keypress.js?v<?php echo $versaoParaJSCSS; ?>"></script>
  </body>
</html>
