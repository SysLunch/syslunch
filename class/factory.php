<?php

class Factory{

  private static $caminho;
  private static $objetos;

  function __construct(){

  }
  function importaClasses($arrayClasses){

    if($arrayClasses["login"] == 1){
      include(self::$caminho."class/login.php");
      self::$objetos["login"] = new Login();
      self::$objetos["login"]->verificaLogin();

      include(self::$caminho."class/log.php");
      self::$objetos["log"] = new Log();
    }
    if($arrayClasses["local"] == 1){
      include(self::$caminho."class/local.php");
      self::$objetos["local"] = new Local();
    }
    if($arrayClasses["localPedido"] == 1){
      include(self::$caminho."class/localPedido.php");
      self::$objetos["localPedido"] = new LocalPedido();
    }
    if($arrayClasses["meioPagamento"] == 1){
      include(self::$caminho."class/meioPagamento.php");
      self::$objetos["meioPagamento"] = new MeioPagamento();
    }
    if($arrayClasses["empresa"] == 1){
      include(self::$caminho."class/empresa.php");
      self::$objetos["empresa"] = new Empresa();
    }
    if($arrayClasses["funcionario"] == 1){
      include(self::$caminho."class/funcionario.php");
      self::$objetos["funcionario"] = new Funcionario();
    }
    if($arrayClasses["venda"] == 1){
      include(self::$caminho."class/venda.php");
      self::$objetos["venda"] = new Venda();
    }
    if($arrayClasses["tipoProduto"] == 1){
      include(self::$caminho."class/tipoProduto.php");
      self::$objetos["tipoProduto"] = new TipoProduto();
    }
    if($arrayClasses["item"] == 1){
      include(self::$caminho."class/item.php");
      self::$objetos["item"] = new Item();
    }
    if($arrayClasses["situacao"] == 1){
      include(self::$caminho."class/situacao.php");
      self::$objetos["situacao"] = new Situacao();
    }
    if($arrayClasses["creditos"] == 1){
      include(self::$caminho."class/creditos.php");
      self::$objetos["creditos"] = new Creditos();
    }
    if($arrayClasses["financeiro"] == 1){
      include(self::$caminho."class/financeiro.php");
      self::$objetos["financeiro"] = new Financeiro();
    }
    if($arrayClasses["relatorio"] == 1){
      include(self::$caminho."class/relatorio.php");
      self::$objetos["relatorio"] = new Relatorio();
    }
    if($arrayClasses["usuario"] == 1){
      include(self::$caminho."class/usuario.php");
      self::$objetos["usuario"] = new Usuario();
    }
    if($arrayClasses["log"] == 1){
      include(self::$caminho."class/log.php");
      self::$objetos["log"] = new Log();
    }
    if($arrayClasses["evento"] == 1){
      include(self::$caminho."class/evento.php");
      self::$objetos["evento"] = new Evento();
    }
    if($arrayClasses["tipoLetra"] == 1){
      include(self::$caminho."class/tipoLetra.php");
      self::$objetos["tipoLetra"] = new TipoLetra();
    }
    if($arrayClasses["participante"] == 1){
      include(self::$caminho."class/participante.php");
      self::$objetos["participante"] = new Participante();
    }
  }
  function setCaminho($Caminho){
    self::$caminho = $Caminho;

    global $DB_host, $DB_login, $DB_senha, $DB_banco;
    //Importando a classe de conexão
    include(self::$caminho."class/conexao.php");
    self::$objetos["conexao"] = new Conexao();
    self::$objetos["conexao"]->setHost($DB_host);
    self::$objetos["conexao"]->setLogin($DB_login);
    self::$objetos["conexao"]->setSenha($DB_senha);
    self::$objetos["conexao"]->setDB($DB_banco);

  }
  function getObjeto($obj){
    return self::$objetos[$obj];
  }

  function isSetted($classe){
    if(self::$objetos[$classe] != NULL){
      return true;
    }else{
      return false;
    }
  }
}
