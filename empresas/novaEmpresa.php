<?php
  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 3;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "tipoLetra" => 1));
  $factory->getObjeto("login")->permissaoPagina(2,1,"Configurações");

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nova Empresa - Empresas - <?php echo $nomeSite; ?></title>
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
      <li><a href="index.php">Listar Empresas</a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers displayNone" id="avisoSucesso">
    <div class="callout success">
      <h5>SUCESSO!</h5>
      <p>Cadastro da empresa <b></b> realizado com sucesso!<span></span></p>
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
            <td><input type="text" name="nomeEmpresa" id="nomeEmpresa" required /></td>
          </tr>
          <tr>
            <td>Login da Empresa</td>
            <td><input type="text" name="loginEmpresa" id="loginEmpresa" required /></td>
          </tr>
          <tr>
            <td>Senha</td>
            <td>
              <select id="selectSenhaEmpresa">
                <optgroup label=""></optgroup>
                <option value="1" selected="selected">Gerar senha aleatória</option>
                <option value="2">Definir senha</option>
              </select>
              <input type="password" name="senhaEmpresa" id="senhaEmpresa" class="displayNone" required />
            </td>
          </tr>
            <td>Tipo de Letra</td>
            <td>
              <?php $letras = $factory->getObjeto("tipoLetra")->listaLetras(1); ?>
              <select id="tipoLetra">
                <optgroup label="Qual letra será usada?"></optgroup>
                <?php foreach($letras as $inf){ ?>
                <option value="<?php echo $inf["idLetra"] ?>"><?php echo $inf["letraReserva"] ?>: <?php echo $inf["descricaoTReserva"] ?> - <?php echo ($inf["isGratuito"] == 1) ? "Gratuito" : "Pago"; ?></option>
                <?php } ?>
              </select>
            </td>
          <tr>
            <td></td>
            <td>
              <input type="submit" value="Enviar" class="button" id="botaoNovaEmpresa" />
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
    <script src="<?php echo $caminho; ?>js/app.js"></script>
    <script src="<?php echo $caminho; ?>js/login.js"></script>
    <script src="js/novaEmpresa.js"></script>
  </body>
</html>
