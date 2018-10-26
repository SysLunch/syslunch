<?php
  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("creditos" => 1));

  $idCRT = ($_GET["idCRT"]) ? $_GET["idCRT"] : NULL;

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>CRT - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="../css/foundation.css" />
    <link href="../assets/foundation-icon-fonts/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/app.css" />
    <link rel="stylesheet" href="../css/pagina.css" />
    <link rel="stylesheet" href="../css/CRT.css?v<?php echo $versaoParaJSCSS; ?>" />
    <!-- <script type="text/javascript" src="../bower_components/crypto-js/hmac-sha256.js"></script> -->
    <!-- <script src='https://code.responsivevoice.org/responsivevoice.js'></script> -->
    <!-- <script src="http://crypto-js.googlecode.com/svn/tags/3.1/build/rollups/hmac-sha256.js"></script> -->
    <!-- <link href='https://fonts.googleapis.com/css?family=Ubuntu:400,700,500' rel='stylesheet' type='text/css'> -->
    <!-- <link href='https://fonts.googleapis.com/css?family=Cabin:400,700|Abel|Josefin+Sans:400,700|Questrial' rel='stylesheet' type='text/css'> -->
    <link href='https://fonts.googleapis.com/css?family=Cabin:400,700' rel='stylesheet' type='text/css'>
  </head>
  <body>

<?php include("../php/headerCRT.php");

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

    <div class="row expanded" id="status">
      <div id="fundo" class="bgBlue">
        <div class="medium-48 float-left columns" id="tudo">
          <div class="row expanded">
            <div class="columns displayNone" id="foto">
              <img src="<?php echo $urlPrincipal[1]; ?>empresas/fotos/foto.png" height="400">
            </div>
            <div class="columns float-left" id="icone">
              <i class=""></i><!-- Icone -->
              <h1 class=""></h1><!-- BOM ALMOÇO, ERRO, AVISO -->
            </div>
          </div>
          <hr>
          <h3 class="">INDICAR O CRT</h3><!-- Nome Funcionário -->
          <h2 class=""></h2><!-- TIPO DE ALMOÇO: TICKET, FUNCIONÁRIO -->
          <h4 class=""></h4><!-- Saldo Funcionário -->
          <h5 class=""></h5><!-- Mensagem de Erro -->
          <h6 class=""></h6><!-- Mensagem de Aviso -->
        </div>
        <div class="medium-48 columns displayNone row expanded" id="carregando">
          <div class="medium-16 columns"><i id="carregandoI" class="fi-loop colorWhite"></i><!-- Icone --></div>
          <div class="medium-32 columns">
            <h3 class="colorWhite">CARREGANDO...</h3><!-- CARREGANDO -->
            <h1 class="colorWhite">Por Favor, Aguarde...</h1><!-- BOM ALMOÇO, ERRO, AVISO -->
            <h6 class="displayNone"></h6><!-- Mensagem de Aviso -->
          </div>
        </div>
      </div>
    </div>

    <div class="row" id="relogio">
      <div class="medium-48 columns">
        <h1></h1><!-- Relógio -->
        <h2></h2><!-- Relógio Horas -->
      </div>
    </div>

    <div class="row" id="barras">
      <div class="medium-32 medium-offset-8 columns barras">
        <h4 id="tituloCodigoBarras">Antes de Tudo, Passe o Código CRT no leitor:</h4>
        <input type="text" id="codigoBarras" autofocus="autofocus" />
        <input type="hidden" id="idCRT" />
      </div>
    </div>
    <audio id="audioAviso">
      <source src="OGG/aviso.ogg" type="audio/ogg">
      <!-- <source src="ajax/audio.php?texto=Aviso" type="audio/mpeg"> -->
      Seu navegador não suporta a tag audio.
    </audio>
    <audio id="avisoBomAlmoco">
      <source src="OGG/bomalmoco.ogg" type="audio/ogg">
      <!-- <source src="ajax/audio.php?texto=Bom Almoço!" type="audio/mpeg"> -->
      Seu navegador não suporta a tag audio.
    </audio>
    <audio id="audioErro">
      <source src="OGG/erro.ogg" type="audio/ogg">
      <!-- <source src="ajax/audio.php?texto=Erro" type="audio/mpeg"> -->
      Seu navegador não suporta a tag audio.
    </audio>
    <audio id="audioAniversario">
      <source src="OGG/felizaniversario.ogg" type="audio/ogg">
      <!-- <source src="ajax/audio.php?texto=Erro" type="audio/mpeg"> -->
      Seu navegador não suporta a tag audio.
    </audio>
    <!-- <audio id="audioErro">
      <!-- <source src="OGG/erro.ogg" type="audio/ogg"> -->
      <!-- <source src="ajax/audio.php?texto=Erro" type="audio/mpeg">
      Seu navegador não suporta a tag audio.
    </audio> -->
    <div id="audioPessoa"></div>
    <div id="audioBom"></div>

<?php

include("../php/footer.php"); ?>


    <script src="../js/vendor/jquery.min.js"></script>
    <script src="../js/vendor/what-input.min.js"></script>
    <script src="../js/foundation.min.js"></script>
    <script src="../js/app.js"></script>
    <script src="js/CRT.js?v<?php echo $versaoParaJSCSS; ?>"></script>
    <script src="js/none.js?v<?php echo $versaoParaJSCSS; ?>"></script>
    <script src="js/consultaCRTCatraca.js?v<?php echo $versaoParaJSCSS; ?>"></script>
    <script src="js/keypress.js?v<?php echo $versaoParaJSCSS; ?>"></script>
    <?php
      if($idCRT){
        ?><script type="text/javascript">
      $(document).ready(function(){
        $("#idCRT").val(<?php echo $idCRT; ?>);
        consultar(true);
      });
        </script>
        <?php
      }
    ?>
  </body>
</html>
