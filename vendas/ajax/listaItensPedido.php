<?php
  $caminho = "../../";

  include($caminho."parametros.php");
  include($caminho."class/factory.php");

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "item" => 1));

  if(!$factory->getObjeto("login")->permissaoPagina(2,0,"Configurações")){
    echo json_encode(array("sucesso" => 0, "erro" => 1, "motivo" => "Sem permissão.", "redirecionar" => 1));
    exit();
  }

  $retorno = $factory->getObjeto("item")->listaItensPedido($_GET["idPedido"]);
  $total = 0.00;
  foreach($retorno as $inf){
    ?>
    <tr>
      <td><?php echo $inf["tipoProduto"]; ?></td>
      <td><?php echo ($inf["funcionario"] != NULL) ? $inf["funcionario"] : "-"; ?></td>
      <td><?php echo number_format($inf["valorUnitario"], 2, ",", ""); ?></td>
      <td><?php echo $inf["quantidade"]; ?></td>
      <td><?php echo number_format($inf["valorTotal"], 2, ",", ""); ?></td>
      <td class="opcoesCRT"><a onClick="removeItem(<?php echo $_GET["idPedido"]; ?>,<?php echo $inf["idItem"]; ?>)"><img src="<?php echo $urlPrincipal[1]; ?>js/foundation-icon-fonts/svgs/fi-x.svg" /></a></td>
    </tr><?php
    $totalQ += $inf["quantidade"];
    $total += $inf["valorTotal"];
  }
  ?>
    <tr>
      <td></td>
      <td></td>
      <td><b>Total: </b></td>
      <td><?php echo floatval($totalQ); ?></td>
      <td><?php echo number_format($total, 2, ",", ""); ?></td>
      <td></td>
    </tr>
