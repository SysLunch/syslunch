<?php
  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 6;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "evento" => 1));
  $factory->getObjeto("login")->permissaoPagina(1,1,"Empresa");

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Eventos - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Eventos</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="../"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-arrow-left.svg" style="height: 25px !important" /> Voltar</a></li>
      <li><a href="novoEvento.php">Novo Evento</a></li>
      <li><a href="editarParticipantePorCodigo/">Editar Participante por Código de Barras</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers">
    <table class="CRTConfigTable">
      <thead>
        <tr class="text-center">
          <th width="30">#</th>
          <th>Nome do Evento</th>
          <th width="150">Tipo</th>
          <th width="150">Opções</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $listaEmpresas = $factory->getObjeto("evento")->listaEventos();
        foreach($listaEmpresas as $inf){
      ?>
            <tr<?php if($inf["ativoGrupo"] == 0) { ?> class="eventoDesativado"<?php }?>>
              <td><?php echo $inf["idGrupo"]; ?></td>
              <td><?php echo $inf["nomeGrupo"]; ?></td>
              <td><?php if($inf["ativoGrupo"] == 1) { if($inf["isGratuito"] == 1){ echo "<span class='label success'>Gratuito</span>"; }else{ echo "<span class='label alert'>Pago</span>"; } } ?></td>
              <td class="opcoesLPedido">
                <?php if($inf["ativoGrupo"] == 1) {
                  $icon = "x";
                  $titleStatus = "Desativar Evento";
                ?><a href="indexParticipantes.php?id=<?php echo $inf["idGrupo"]; ?>" title="Participantes do Evento"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-torsos-male-female.svg" /></a>
                <a href="editarEvento.php?id=<?php echo $inf["idGrupo"]; ?>" title="Editar Evento"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-pencil.svg" /></a>
                <?php }else{
                  $icon = "check";
                  $titleStatus = "Ativar Evento";
                } ?>
                <a href="mudarStatusEvento.php?id=<?php echo $inf["idGrupo"]; ?>" title="<?php echo $titleStatus; ?>"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-<?php echo $icon; ?>.svg" /></a>
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
