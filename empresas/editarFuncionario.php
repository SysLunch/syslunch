<?php
  $id = $_GET["id"];
  $barras = ($_GET["barras"] == 1) ? 1 : 0;

  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 3;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "empresa" => 1, "funcionario" => 1));
  $factory->getObjeto("login")->permissaoPagina(3,1,"Empresa");

  $consulta = $factory->getObjeto("funcionario")->buscaFuncionario($id);


  if($consulta["count"] == 0){
    header("location: index.php?e=nTL");
    exit();
  }
  $dadosFuncionario = $consulta["consulta"][0];

  $consultaE = $factory->getObjeto("empresa")->buscaEmpresa($dadosFuncionario["idEmpresa"]);
  $dadosEmpresa = $consultaE["consulta"][0];


  if($barras == 1){
    $msgSucesso = "Cadastro de funcionário atualizado com sucesso! Em 3 segundos essa janela/aba será fechada.";
  }else{
    $msgSucesso = "Cadastro de funcionário atualizado com sucesso!";
  }

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Funcionário - Empresa: <?php echo $dadosEmpresa["nomeEmpresa"]; ?> - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Empresa: <?php echo $dadosEmpresa["nomeEmpresa"]; ?> >> Editar Funcionário</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="indexFuncionarios.php?id=<?php echo $dadosEmpresa["idEmpresa"]; ?>">Listar Funcionários</a></li>
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
            <td colspan="1">Nome do Funcionário</td>
            <td colspan="3"><input type="text" name="nomeFuncionario" id="nomeFuncionario" value="<?php echo $dadosFuncionario["nomeFuncionario"]; ?>" required /></td>
          </tr>
          <tr>
            <td colspan="1">Data de Nascimento</td>
            <td colspan="3"><input type="text" name="nascimentoFuncionario" id="nascimentoFuncionario" value="<?php echo date("d/m/Y",strtotime($dadosFuncionario["nascimentoFuncionario"])); ?>" required /></td>
          </tr>
          <tr>
            <td colspan="1">Código de Barras:</td>
            <td colspan="2">
                <select name="codigoCartao" id="codigoCartao">
                  <optgroup label="Código de Barras deste Funcionário"></optgroup>
                  <?php
                    $barras = $factory->getObjeto("funcionario")->listarCodigosFuncionario($id);
                    foreach($barras as $inf){
                      ?><option value="<?php echo $inf["codigoReserva"]; ?>"<?php if($inf["codigoReserva"] == $dadosFuncionario["codigoCartao"]){ ?> selected="selected"<?php } ?>><?php echo $inf["codigoReserva"]; ?></option><?php
                    }
                  ?>
                </select>
                <input type="text" value="<?php echo $dadosFuncionario["codigoCartao"]; ?>" id="showCodigoBarras" class="displayNone" />
            </td>
            <td colspan="1">
                <a class="button success" href="gerarNovoCodigo.php?id=<?php echo $id; ?>"><i class="fi-refresh"></i> Gerar Novo Código</a><br>
                <label><input type="checkbox" name="mostrarCodigo" id="mostrarCodigo" /> Mostrar Código</label>
            </td>
          </tr>
          <tr>
            <td colspan="1"></td>
            <td colspan="3">
              <input type="submit" value="Enviar" class="button" id="botaoEditarFuncionario" />
              <input type="hidden" name="id" id="idFuncionario" value="<?php echo $id; ?>" />
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
    <script src="js/editarFuncionario.js"></script>
  </body>
</html>
