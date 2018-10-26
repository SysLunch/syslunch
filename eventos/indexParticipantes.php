<?php
  $id = $_GET["id"];

  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 6;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "evento" => 1, "participante" => 1));
  $factory->getObjeto("login")->permissaoPagina(1,1,"Empresa");

  $consulta = $factory->getObjeto("evento")->buscaEvento($id);

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Participantes Evento: <?php echo $consulta["consulta"][0]["nomeGrupo"]; ?> - Eventos - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Evento: <?php echo $consulta["consulta"][0]["nomeGrupo"]; ?> >> Participantes</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="./"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-arrow-left.svg" style="height: 25px !important" /> Voltar</a></li>
      <li><a href="novoParticipante.php?id=<?php echo $id; ?>">Novo Participante</a></li>
      <li><a href="../barras/etiquetas.php?id=<?php echo $id; ?>" target="_blank">Imprimir Códigos de Barras de Todos os Participantes</a></li>
      <li><a href="listaEntrega.php?id=<?php echo $id; ?>" target="_blank">Lista de Entrega</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers">
    <table class="CRTConfigTable">
      <thead>
        <tr class="text-center">
          <th>#</th>
          <th>Nome do Participante</th>
          <?php if($consulta["consulta"][0]["isGratuito"] == 0){ ?><th width="50">Saldo</th><?php } ?>
          <th width="150">Opções</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $i = 1;
        $listaParticipantes = $factory->getObjeto("participante")->listaParticipantes($id);
        foreach($listaParticipantes as $inf){
      ?>
            <tr<?php if($inf["ativoPessoa"] == 0) { ?> class="participanteDesativado"<?php }?>>
              <td><?php echo $i; ?></td>
              <td><?php echo $inf["nomePessoa"]; ?></td>
              <?php if($consulta["consulta"][0]["isGratuito"] == 0){ ?><td><?php echo $inf["saldo"]; ?></td><?php } ?>
              <td class="opcoesLPedido">
                <?php if($inf["ativoPessoa"] == 1) {
                  $icon = "x";
                  $titleStatus = "Desativar Participante";
                ?>
                <a href="<?php echo $urlPrincipal[1]; ?>barras/funcionario.php?id=<?php echo $inf["idPessoa"]; ?>" target="_blank" title="Imprimir Cartão"><img src="<?php echo $caminho; ?>img/codbarras.svg" /></a>
                <?php if($factory->getObjeto("login")->permissaoPagina(3,0,"Eventos")){ ?>
                <a href="editarParticipante.php?id=<?php echo $inf["idPessoa"]; ?>" title="Editar Participante"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-pencil.svg" /></a><?php } ?>
                <?php }else{
                  $icon = "check";
                  $titleStatus = "Ativar Participante";
                } ?>
                <a href="mudarStatusParticipante.php?id=<?php echo $inf["idPessoa"]; ?>" title="<?php echo $titleStatus; ?>"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-<?php echo $icon; ?>.svg" /></a>
              </td>
            </tr>
      <?php
          $i++;
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
