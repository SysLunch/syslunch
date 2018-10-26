<optgroup label="Tipo de compra"></optgroup>
<option value="">Selecione...</option>
<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "tipoProduto" => 1, "empresa" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(3,0,"Configurações")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $isEmpresa = $factory->getObjeto("empresa")->buscaEmpresa($_GET["idEmpresa"]);

  $retorno = $factory->getObjeto("tipoProduto")->listaTiposProdutos($isEmpresa["count"]);
    foreach($retorno as $inf){
      ?><option value="<?php echo $inf["idTipoProduto"]; ?>"><?php echo $inf["descricaoProduto"]; ?></option><?php
    }
