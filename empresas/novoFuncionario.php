<?php
  $id = $_GET["id"];

  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 3;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "empresa" => 1, "funcionario" => 1));
  $factory->getObjeto("login")->permissaoPagina(2,1,"Empresa");

  $consulta = $factory->getObjeto("empresa")->buscaEmpresa($id);

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Novo Funcionário - Empresa: <?php echo $consulta["consulta"][0]["nomeEmpresa"]?> - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Empresa: <?php echo $consulta["consulta"][0]["nomeEmpresa"]?> >> Novo Funcionário</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="indexFuncionarios.php?id=<?php echo $id; ?>">Listar Funcionários da Empresa: <?php echo $consulta["consulta"][0]["nomeEmpresa"]?></a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers displayNone" id="avisoSucesso">
    <div class="callout success">
      <h5>SUCESSO!</h5>
      <p>Cadastro do funcionário <b></b> realizado com sucesso!<span></span></p>
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
            <td>Nome do Funcionário</td>
            <td><input type="text" name="nomeFuncionario" id="nomeFuncionario" required /></td>
          </tr>
          <tr>
            <td>Data de Nascimento</td>
            <td><input type="text" name="nascimentoFuncionario" id="nascimentoFuncionario" required /></td>
          </tr>
          <tr>
            <td></td>
            <td>
              <input type="submit" value="Enviar" class="button" id="botaoNovoFuncionario" />
              <input type="hidden" name="urlSite" id="urlSite" value="<?php echo $urlPrincipal[1]; ?>" required />
              <input type="hidden" name="idEmpresa" id="idEmpresa" value="<?php echo $id; ?>" required />
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
    <script src="js/novoFuncionario.js"></script>
  </body>
</html>
