<?php
  $idEmpresa = $_GET["id"];

  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 3;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "funcionario" => 1, "empresa" => 1));
  $factory->getObjeto("login")->permissaoPagina(1,1,"Empresa");

  if($idEmpresa == NULL){
    $ids = explode(",", $_GET["idsFuncionarios"]);
    $consulta = $factory->getObjeto("funcionario")->listaFuncionariosporId($ids);
  }else{
    $consulta = $factory->getObjeto("funcionario")->listaFuncionarios($idEmpresa);

    $consultaE = $factory->getObjeto("empresa")->buscaEmpresa($idEmpresa);
    $dadosEmpresa = $consultaE["consulta"][0];
  }
  if(count($consulta) == 0){
    header("location: ../index.php?e=nTF");
    exit();
  }


  ?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Comprovante de Entrega - <?php echo $dadosEmpresa["nomeEmpresa"]; ?> - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/impressao.css" />
  </head>
  <body>
    <h2 class="text-center"><b>Lista de Entrega de Cartões - <?php echo $dadosEmpresa["nomeEmpresa"]; ?></b></h2>
    <table id="listaEntrega" width="100%">
      <thead>
        <tr>
          <td colspan="5">Nome Funcionário</td>
          <td colspan="9">Assinatura</td>
        </tr>
      </thead>
      <tbody>
      <?php
      foreach($consulta as $inf){
        if($inf["ativoFuncionario"] == 1){
          if(strlen($inf["nomeFuncionario"]) > 30){
            $divisao = explode(" ", $inf["nomeFuncionario"]);
            $cont_divisao = count($divisao);
            $inf["nomeFuncionario"] = $divisao[0]." ".$divisao[$cont_divisao-1];
          }
          if($idEmpresa == NULL){
              $consultaE = $factory->getObjeto("empresa")->buscaEmpresa($idEmpresa);
              $dadosEmpresa = $consultaE["consulta"][0];
          }
      ?>
      <tr>
        <td colspan="5"><?php echo $inf["nomeFuncionario"]; ?></td>
        <td colspan="9"></td>
      </tr>
    <?php
      }
    }
    ?>
      </tbody>
    </table>


    <script src="<?php echo $caminho; ?>js/vendor/jquery.min.js"></script>
    <script src="<?php echo $caminho; ?>js/vendor/what-input.min.js"></script>
    <script src="<?php echo $caminho; ?>js/foundation.min.js"></script>
    <script src="<?php echo $caminho; ?>js/jquery.mask.min.js"></script>
    <script src="<?php echo $caminho; ?>js/jquery.formatCurrency.min.js"></script>
    <script src="<?php echo $caminho; ?>js/app.js"></script>
    <script src="<?php echo $caminho; ?>js/login.js"></script>
    <script src="js/novaVenda.js"></script>
  </body>
</html>
