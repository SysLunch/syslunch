<?php
  $id = $_GET["id"];

  $caminho = "../../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 5;


  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "usuario" => 1));
  $factory->getObjeto("login")->permissaoPagina(3,1,"Usuário");

  $consulta = $factory->getObjeto("usuario")->buscaUsuario($id);


  if($consulta["count"] == 0){
    header("location: index.php?e=nTL");
    exit();
  }
  $dadosUsuario = $consulta["consulta"][0];

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Alterar Senha Usuário - Usuário - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Usuário >> Alterar Senha Usuário</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="index.php">Listar Usuários</a></li>
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
            <td>Nome do Usuário</td>
            <td><input type="text" value="<?php echo $dadosUsuario["nomeUsuario"]; ?>" disabled="disabled" /></td>
          </tr>
          <tr>
            <td>Login do Usuário</td>
            <td><input type="text" value="<?php echo $dadosUsuario["loginUsuario"]; ?>" disabled="disabled" /></td>
          </tr>
          <tr>
            <td>Selecione</td>
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
            <td></td>
            <td>
              <input type="submit" value="Enviar" class="button" id="botaoAlterarSenhaUsuario" />
              <input type="hidden" name="id" id="idUsuario" value="<?php echo $id; ?>" />
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
    <script src="js/alterarSenhaUsuario.js"></script>
  </body>
</html>
