<?php
  include("parametros.php");
  include("class/factory.php");
  $caminho = "./";

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1));

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/app.css" />
    <link rel="stylesheet" href="css/pagina.css" />
  </head>
  <body>

<?php include("php/header.php");

  if(!$factory->getObjeto("login")->isLogado()){
 ?>

    <div class="row">
      <div class="medium-48 columns">
        <div class="tituloPagina">Seja Bem-vindo ao <?php echo $nomeSite; ?>!</div>
        <div class="subtituloPagina">Aqui você poderá controlar a venda e acesso de almoços!</div>
      </div>
      <div class="medium-16 columns">
        Com o SisLunch você consegue fazer a venda, e além disso, pode controlar isso tudo, através de um sistema simples e descomplicado.
      </div>
      <div class="medium-16 columns">
        O SisLunch sempre está inovando e agora tem novas cores e cadastro de participantes de certo evento em uma interface de fácil acesso e uso.
      </div>
      <div class="medium-16 columns">

      </div>
    </div>

<?php

}else{
  include("php/home.php");
}

include("php/footer.php"); ?>


    <script src="js/vendor/jquery.min.js"></script>
    <script src="js/vendor/what-input.min.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/app.js"></script>
    <script src="js/login.js"></script>
  </body>
</html>
