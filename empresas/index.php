<?php
  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 3;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "empresa" => 1));
  $factory->getObjeto("login")->permissaoPagina(1,1,"Empresa");

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Empresas - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Empresas</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="../"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-arrow-left.svg" style="height: 25px !important" /> Voltar</a></li>
      <li><a href="novaEmpresa.php">Nova Empresa</a></li>
      <li><a href="editarFuncionarioPorCodigo/">Editar Funcionário por Código de Barras</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers">
    <table class="CRTConfigTable">
      <thead>
        <tr class="text-center">
          <th width="30">#</th>
          <th>Nome da Empresa</th>
          <th width="100">Tipo</th>
          <th width="100">Login</th>
          <th width="150">Opções</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $listaEmpresas = $factory->getObjeto("empresa")->listaEmpresas();
        foreach($listaEmpresas as $inf){
      ?>
            <tr<?php if($inf["ativoEmpresa"] == 0) { ?> class="empresaDesativada"<?php }?>>
              <td><?php echo $inf["idEmpresa"]; ?></td>
              <td><?php echo $inf["nomeEmpresa"]; ?></td>
              <td><?php if($inf["ativoEmpresa"] == 1) { if($inf["isGratuito"] == 1){ echo "<span class='label success'>Gratuita</span>"; }else{ echo "<span class='label alert'>Paga</span>"; } } ?></td>
              <td><?php echo $inf["usuarioEmpresa"]; ?></td>
              <td class="opcoesLPedido">
                <?php if($inf["ativoEmpresa"] == 1) {
                  $icon = "x";
                  $titleStatus = "Desativar Empresa";
                ?><a href="indexFuncionarios.php?id=<?php echo $inf["idEmpresa"]; ?>" title="Funcionários da Empresa"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-torsos-male-female.svg" /></a>
                <a href="alterarSenhaEmpresa.php?id=<?php echo $inf["idEmpresa"]; ?>" title="Alterar Senha da Empresa"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-lock.svg" /></a>
                <a href="editarEmpresa.php?id=<?php echo $inf["idEmpresa"]; ?>" title="Editar Empresa"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-pencil.svg" /></a>
                <?php }else{
                  $icon = "check";
                  $titleStatus = "Ativar Empresa";
                } ?>
                <a href="mudarStatusEmpresa.php?id=<?php echo $inf["idEmpresa"]; ?>" title="<?php echo $titleStatus; ?>"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-<?php echo $icon; ?>.svg" /></a>
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
