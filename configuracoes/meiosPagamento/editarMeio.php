<?php
  $id = $_GET["id"];

  $caminho = "../../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "meioPagamento" => 1));

  $consulta = $factory->getObjeto("meioPagamento")->buscaMeio($id);


  if($consulta["count"] == 0){
    header("location: index.php?e=nTL");
    exit();
  }
  $dadosLocal = $consulta["consulta"][0];

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Meio - Meio de Pagamento - Configurações - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Configurações >> Meio de Pagamento >> Editar Meio de Pagamento</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="index.php">Listar Meio de Pagamento</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers displayNone" id="avisoSucesso">
    <div class="callout success">
      <h5>SUCESSO!</h5>
      <p>Cadastro do meio de pagamento atualizado com sucesso!</p>
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
            <td>Nome do Meio de Pagamento</td>
            <td><input type="text" name="meioPagamento" id="meioPagamento" value="<?php echo $dadosLocal["meioPagamento"]; ?>" required /></td>
          </tr>
          <tr>
            <td>URL(Action) do Meio de Pagamento</td>
            <td><input type="text" name="actionPagamento" id="actionPagamento" value="<?php echo $dadosLocal["actionPagamento"]; ?>" required /></td>
          </tr>
          <tr>
            <td></td>
            <td>
              <input type="submit" value="Enviar" class="button" id="botaoEditarMeio" />
              <input type="hidden" name="id" id="idMeio" value="<?php echo $id; ?>" />
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
    <script src="js/editarMeio.js"></script>
  </body>
</html>
