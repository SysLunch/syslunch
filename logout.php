<?php
  include("parametros.php");
  include("class/factory.php");
  $caminho = "./";

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1));

  $factory->getObjeto("login")->efetuarLogout();

  header("location: index.php");
