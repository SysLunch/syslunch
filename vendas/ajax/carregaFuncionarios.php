<optgroup label="Funcionários"></optgroup>
<option value="0">Selecione...</option>
<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "empresa" => 1, "funcionario" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(2,0,"Configurações")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $retorno = $factory->getObjeto("funcionario")->listaFuncionarios($_GET["idEmpresa"]);
  foreach($retorno as $inf){
    ?><option value="<?php echo $inf["idFuncionario"]; ?>"><?php echo $inf["nomeFuncionario"]; ?></option><?php
  }
