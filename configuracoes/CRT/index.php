<?php
  $caminho = "../../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 5;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "local" => 1));
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

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Configurações >> Locais CRT</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="../"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-arrow-left.svg" style="height: 25px !important" /> Voltar</a></li>
      <li><a href="novoLocal.php">Novo Local</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers">
  <?php /*  <table id="CRTConfigTable">
      <thead>
        <tr>
          <th width="30">#</th>
          <th>Nome do Local</th>
          <th width="100">Opções</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Fundetec - Refeitório</td>
          <td>Content Goes Here</td>
        </tr>
        <tr>
          <td>2</td>
          <td>Agrotec - Refeitório</td>
          <td>Content Goes Here</td>
        </tr>
      </tbody>
    </table> */ ?>
    <div class="row divsLista">
      <?php
        $listaCRTs = $factory->getObjeto("local")->listaLocais();
        foreach($listaCRTs as $inf){
      ?>
      <div class="medium-22 medium-offset-2 columns">
        <h3><?php echo $inf["descricaoLocal"]; ?></h3>
        <p>ID: <?php echo $inf["idLocal"]; ?></p>
        <p><?php if($inf["ultimaConexao"] != NULL){ if((time() - strtotime($inf["ultimaConexao"])) <= 60){ echo "<span class=\"label success\">Online</span>"; }else{ echo "<span class=\"label warning \">Visto em ".date("d/m/Y H:i:s",strtotime($inf["ultimaConexao"]))."</span>"; } } ?></p>
        <p class="opcoesCRT">
          <a href="editarLocal.php?id=<?php echo $inf["idLocal"]; ?>"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-pencil.svg" /></a>
          <a href="<?php echo $urlPrincipal[1]; ?>barras/crt.php?id=<?php echo $inf["idLocal"]; ?>" target="_blank"><img src="<?php echo $caminho; ?>img/codbarras.svg" /></a>
          <a href="excluirLocal.php?id=<?php echo $inf["idLocal"]; ?>"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-x.svg" /></a>
        </p>

      </div>
      <?php
        }
      ?>
    </div>
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
