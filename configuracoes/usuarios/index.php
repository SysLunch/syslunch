<?php
  $caminho = "../../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 5;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "usuario" => 1));
  $factory->getObjeto("login")->permissaoPagina(3,1,"Usuário");

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Usuarios - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Usuarios</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="../"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-arrow-left.svg" style="height: 25px !important" /> Voltar</a></li>
      <li><a href="novoUsuario.php">Novo Usuário</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers">
    <table class="CRTConfigTable">
      <thead>
        <tr class="text-center">
          <th width="30">#</th>
          <th>Nome do Usuário</th>
          <th width="100">Login</th>
          <th width="150">Opções</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $listaUsuarios = $factory->getObjeto("usuario")->listaUsuarios();
        foreach($listaUsuarios as $inf){
      ?>
            <tr<?php if($inf["ativoUsuario"] == 0) { ?> class="usuarioDesativado"<?php }?>>
              <td><?php echo $inf["idUsuario"]; ?></td>
              <td><?php echo $inf["nomeUsuario"]; ?></td>
              <td><?php echo $inf["loginUsuario"]; ?></td>
              <td class="opcoesLPedido">
                <?php if($inf["ativoUsuario"] == 1) {
                  $icon = "x";
                  $titleStatus = "Desativar Usuário";
                ?><a href="editarUsuario.php?id=<?php echo $inf["idUsuario"]; ?>" title="Editar Usuário"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-pencil.svg" /></a>
                <a href="alterarSenhaUsuario.php?id=<?php echo $inf["idUsuario"]; ?>" title="Alterar Senha do Usuário"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-lock.svg" /></a>
                <?php }else{
                  $icon = "check";
                  $titleStatus = "Ativar Usuário";
                } ?>
                <a href="mudarStatusUsuario.php?id=<?php echo $inf["idUsuario"]; ?>" title="<?php echo $titleStatus; ?>"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-<?php echo $icon; ?>.svg" /></a>
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
