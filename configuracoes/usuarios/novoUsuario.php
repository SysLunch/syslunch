<?php
  $caminho = "../../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 5;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "usuario" => 1));
  $factory->getObjeto("login")->permissaoPagina(3,1,"Usuários");

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Novo Usuário - Usuários - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Usuários >> Novo Usuário</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="index.php">Listar Usuários</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers displayNone" id="avisoSucesso">
    <div class="callout success">
      <h5>SUCESSO!</h5>
      <p>Cadastro do usuário <b></b> realizado com sucesso!<span></span></p>
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
          <td>Nome do Usuário</td>
          <td><input type="text" name="nomeUsuario" id="nomeUsuario" required /></td>
        </tr>
        <tr>
          <td>Login do Usuário</td>
          <td><input type="text" name="loginUsuario" id="loginUsuario" required /></td>
        </tr>
        <tr>
          <td>Senha</td>
          <td>
            <select id="selectSenhaUsuario">
              <optgroup label=""></optgroup>
              <option value="1" selected="selected">Gerar senha aleatória</option>
              <option value="2">Definir senha</option>
            </select>
          </td>
        </tr>
        <tr class="senhaUsuario displayNone">
          <td>Nova Senha</td>
          <td><input type="password" name="senhaUsuario" id="senhaUsuario" required /></td>
        </tr>
        <tr class="senhaUsuario displayNone">
          <td>Confirmação da Nova Senha</td>
          <td><input type="password" name="confirmacaoSenhaUsuario" id="confirmacaoSenhaUsuario" required /></td>
        </tr>
        <tr>
          <td>Data de Nascimento</td>
          <td>
            <input type="text" name="nascimentoUsuario" id="nascimentoUsuario" required />
          </td>
        </tr>
        <tr>
          <td>Permissão</td>
          <td>
            <select id="permissaoUsuario">
              <optgroup label=""></optgroup>
              <option value="" selected="selected">Selecione uma das permissões..</option>
              <?php foreach($factory->getObjeto("usuario")->listaPermissoes() as $inf){ ?>
              <option value="<?php echo $inf["idPermissao"] ?>"><?php echo $inf["nomePermissao"] ?></option>
              <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td></td>
          <td>
            <input type="submit" value="Enviar" class="button" id="botaoNovoUsuario" />
            <input type="hidden" name="urlSite" id="urlSite" value="<?php echo $urlPrincipal[1]; ?>" required />
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
    <script src="js/novoUsuario.js"></script>
  </body>
</html>
