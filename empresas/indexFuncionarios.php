<?php
  $id = $_GET["id"];

  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 3;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "empresa" => 1, "funcionario" => 1));
  $factory->getObjeto("login")->permissaoPagina(1,1,"Empresa");

  $consulta = $factory->getObjeto("empresa")->buscaEmpresa($id);

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
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Empresa: <?php echo $consulta["consulta"][0]["nomeEmpresa"]?> >> Funcionários</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="./"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-arrow-left.svg" style="height: 25px !important" /> Voltar</a></li>
      <li><a href="novoFuncionario.php?id=<?php echo $id; ?>">Novo Funcionário</a></li>
      <li><a href="../barras/funcionarios.php?id=<?php echo $id; ?>" target="_blank">Imprimir Códigos de Barras de Todos os Funcionários</a></li>
      <li><a href="listaEntrega.php?id=<?php echo $id; ?>" target="_blank">Lista de Entrega</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers">
    <table class="CRTConfigTable">
      <thead>
        <tr class="text-center">
          <th>#</th>
          <th>Nome do Funcionário</th>
          <?php if($consulta["consulta"][0]["isGratuito"] == 0){ ?><th width="50">Saldo</th><?php } ?>
          <th width="150">Opções</th>
        </tr>
      </thead>
      <tbody>
      <?php
        $i = 1;
        $listaFuncionarios = $factory->getObjeto("funcionario")->listaFuncionarios($id);
        foreach($listaFuncionarios as $inf){
      ?>
            <tr<?php if($inf["ativoFuncionario"] == 0) { ?> class="funcionarioDesativado"<?php }?>>
              <td><?php echo $i; ?></td>
              <td><?php echo $inf["nomeFuncionario"]; ?></td>
              <?php if($consulta["consulta"][0]["isGratuito"] == 0){ ?><td><?php echo $inf["saldo"]; ?></td><?php } ?>
              <td class="opcoesLPedido">
                <?php if($inf["ativoFuncionario"] == 1) {
                  $icon = "x";
                  $titleStatus = "Desativar Funcionário";
                ?>
                <a href="<?php echo $urlPrincipal[1]; ?>barras/funcionario.php?id=<?php echo $inf["idFuncionario"]; ?>" target="_blank" title="Imprimir Cartão"><img src="<?php echo $caminho; ?>img/codbarras.svg" /></a>
                <?php if($factory->getObjeto("login")->permissaoPagina(3,0,"Empresa")){ ?>
                <a href="editarFuncionario.php?id=<?php echo $inf["idFuncionario"]; ?>" title="Editar Funcionário"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-pencil.svg" /></a><?php } ?>
                <?php }else{
                  $icon = "check";
                  $titleStatus = "Ativar Funcionário";
                } ?>
                <a href="mudarStatusFuncionario.php?id=<?php echo $inf["idFuncionario"]; ?>" title="<?php echo $titleStatus; ?>"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-<?php echo $icon; ?>.svg" /></a>
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
