<?php
  $id = $_GET["id"];

  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 3;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "empresa" => 1));
  $factory->getObjeto("login")->permissaoPagina(2,1,"Empresa");

  $consulta = $factory->getObjeto("empresa")->buscaEmpresa($id);


  if($consulta["count"] == 0){
    header("location: index.php?e=nTL");
    exit();
  }
  $dadosEmpresa = $consulta["consulta"][0];

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar Empresa - Empresas - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Empresas >> Alterar Senha Empresa</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="index.php">Listar Empresas</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers displayNone" id="avisoSucesso">
    <div class="callout success">
      <h5>SUCESSO!</h5>
      <p>Senha alterada com sucesso!<span></span></p>
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
            <td>Nome da Empresa</td>
            <td><input type="text" value="<?php echo $dadosEmpresa["nomeEmpresa"]; ?>" disabled="disabled" /></td>
          </tr>
          <tr>
            <td>Nome da Empresa</td>
            <td><input type="text" value="<?php echo $dadosEmpresa["usuarioEmpresa"]; ?>" disabled="disabled" /></td>
          </tr>
          <tr>
            <td>Selecione</td>
            <td>
                <select id="selectSenhaEmpresa">
                  <optgroup label=""></optgroup>
                  <option value="1" selected="selected">Gerar senha aleatória</option>
                  <option value="2">Definir senha</option>
                </select>
            </td>
          </tr>
          <tr class="senhaEmpresa displayNone">
            <td>Nova Senha</td>
            <td><input type="password" name="senhaEmpresa" id="senhaEmpresa" required /></td>
          </tr>
          <tr class="senhaEmpresa displayNone">
            <td>Confirmação da Nova Senha</td>
            <td><input type="password" name="confSenhaEmpresa" id="confSenhaEmpresa" required /></td>
          </tr>
          <tr>
            <td></td>
            <td>
              <input type="submit" value="Enviar" class="button" id="botaoAlterarSenhaEmpresa" />
              <input type="hidden" name="id" id="idEmpresa" value="<?php echo $id; ?>" />
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
    <script src="<?php echo $caminho; ?>js/app.js"></script>
    <script src="<?php echo $caminho; ?>js/login.js"></script>
    <script src="js/alterarSenhaEmpresa.js"></script>
  </body>
</html>
