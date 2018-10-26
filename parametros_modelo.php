<?php
  // Dados para Conexão do Banco de Dados
  $DB_host = "";
  $DB_login = "";
  $DB_senha = "";
  $DB_banco = "";

  //Dados sobre o site
  $nomeSite = "SysLunch";
  $urlPrincipal[0] = "";
  $urlPrincipal[1] = $urlPrincipal[0]."/";

  //Dados de onde é o pedido efetuado pelo sistema
  $localPedidoInterno = "";

  //Timestamp para adicionar para chegar ao dia de vencimento do ticket
  $ValidadeTicket = 60*60*24*365;

  //Ativa ou desativa o Log do Sistema
  $log = true;
  /*ATENÇÃO: Algumas das atividades do sistema não informam quem fez, como por exemplo,
  a criação de um cartão para o funcionário. Essas atividades/funções armazenam no log
  a informação de quem fez o que. Caso desative o log, não há forma de saber quem fez
  algumas das alterações do sistema. */

  //Exibição de Erros
  ini_set('display_errors', '1');
  error_reporting(E_ERROR | E_WARNING | E_PARSE);
  //error_reporting(E_ALL);

  //Local e largura do logo
  $logo = "img/logo.png";
  $largLogo = 150;

  //Versão para CSS e JS
  $versaoParaJSCSS = "1.0.141";

  //Versão para Usuário
  $versaoSislunch["versao"] = "1.1.2";
  $versaoSislunch["release"] = "2016-03-28";
