<?php
  $id = $_GET["id"];
  $barras = ($_GET["barras"] == 1) ? 1 : 0;

  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 3;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "evento" => 1, "participante" => 1));
  $factory->getObjeto("login")->permissaoPagina(3,1,"Eventos");

  $consulta = $factory->getObjeto("participante")->buscaParticipante($id);


  if($consulta["count"] == 0){
    header("location: index.php?e=nTL");
    exit();
  }
  $dadosParticipante = $consulta["consulta"][0];

  $consultaE = $factory->getObjeto("evento")->buscaEvento($dadosParticipante["idEmpresa"]);
  $dadosEvento = $consultaE["consulta"][0];


  if($barras == 1){
    $msgSucesso = "Cadastro de participante atualizado com sucesso! Em 3 segundos essa janela/aba será fechada.";
  }else{
    $msgSucesso = "Cadastro de participante atualizado com sucesso!";
  }

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Participante - Evento: <?php echo $dadosEmpresa["nomeEvento"]; ?> - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Empresa: <?php echo $dadosEmpresa["nomeEvento"]; ?> >> Editar Participante</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="indexParticipantes.php?id=<?php echo $dadosEvento["idGrupo"]; ?>">Listar Participantes</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers displayNone" id="avisoSucesso">
    <div class="callout success">
      <h5>SUCESSO!</h5>
      <p><?php echo $msgSucesso ;?></p>
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
            <td>Nome do Participante</td>
            <td><input type="text" name="nomeParticipante" id="nomeParticipante" value="<?php echo $dadosParticipante["nomePessoa"]; ?>" required /></td>
          </tr>
          <tr>
            <td>Data de Nascimento</td>
            <td><input type="text" name="nascimentoParticipante" id="nascimentoParticipante" value="<?php echo date("d/m/Y",strtotime($dadosParticipante["nascimentoPessoa"])); ?>" required /></td>
          </tr>
          <tr>
            <td></td>
            <td>
              <input type="submit" value="Enviar" class="button" id="botaoEditarParticipante" />
              <input type="hidden" name="id" id="idParticipante" value="<?php echo $id; ?>" />
              <input type="hidden" name="barras" id="barras" value="<?php echo $barras; ?>" />
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
    <script src="<?php echo $caminho; ?>js/jquery.mask.min.js"></script>
    <script src="<?php echo $caminho; ?>js/app.js"></script>
    <script src="<?php echo $caminho; ?>js/login.js"></script>
    <script src="js/editarParticipante.js"></script>
  </body>
</html>
