<?php
  $caminho = "../../";
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
    <title>Novo Local - Locais CRT - Configurações - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Configurações >> Locais CRT</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="index.php">Listar CRTs</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers displayNone" id="avisoSucesso">
    <div class="callout success">
      <h5>SUCESSO!</h5>
      <p>Cadastro do local <b></b> realizado com sucesso! Deseja criar o código de barras de login?</p>
      <p id="linkAvisoSucesso"></p>
    </div>
  </div>
  <div class="medium-48 columns centers displayNone" id="avisoErro">
    <div class="callout alert">
      <h5>ERRO!</h5>
      <p></p>
    </div>
  </div>
  <div class="medium-48 columns centers">
  <table class="CRTConfigTable">
      <tbody>
          <tr>
            <td>Nome do Local</td>
            <td><input type="text" name="nomeLocal" id="nomeLocal" required /></td>
          </tr>
          <tr>
            <td></td>
            <td>
              <input type="submit" value="Enviar" class="button" id="botaoNovoLocal" />
              <input type="hidden" name="urlSite" id="urlSite" value="<?php echo $urlPrincipal[1]; ?>" required />
            </td>
          </tr>
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
    <script src="js/novoLocal.js"></script>
  </body>
</html>
