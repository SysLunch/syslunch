<?php
  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 1;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "venda" => 1, "funcionario" => 1, "empresa" => 1, "evento" => 1, "participante" => 1, "tipoProduto" => 1));
  $factory->getObjeto("login")->permissaoPagina(2,1,"Venda");

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nova Venda - Venda - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include($caminho."php/header.php");?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Venda >> Nova Venda</div>
  </div>
  <div class="medium-48 columns">
    <ul class="menu">
      <li><a href="index.php"><img src="<?php echo $caminho; ?>js/foundation-icon-fonts/svgs/fi-arrow-left.svg" style="height: 25px !important" /> Voltar</a></a></li>
    </ul>
  </div>
  <div class="medium-48 columns centers displayNone" id="avisoErro">
    <div class="callout alert">
      <h5>ERRO!</h5>
      <p></p>
    </div>
  </div>
  <div class="medium-48 columns centers">

    <input type="hidden" id="tabelaAtiva" value="1" />

    <table class="CRTConfigTable" id="tabelaVenda1">
      <tbody>
        <tr>
          <td>Cliente</td>
          <td>
            <select>
              <optgroup label="Cliente?"></optgroup>
              <option value="">Selecione...</option>
              <option value="0">Cliente Não Identificado</option>
              <?php
                $consulta = $factory->getObjeto("empresa")->listaEmpresas();
                foreach ($consulta as $inf) {
                  ?><option value="<?php echo $inf["idEmpresa"]; ?>">Empresa: <?php echo $inf["nomeEmpresa"]; ?></option><?php
                  echo "\n";
                }


                $consulta = $factory->getObjeto("evento")->listaEventos();
                foreach ($consulta as $inf) {
                  ?><option value="<?php echo $inf["idGrupo"]; ?>">Evento: <?php echo $inf["nomeGrupo"]; ?></option><?php
                  echo "\n";
                }
              ?>
            </select>
          </td>
        </tr>
        <tr>
          <td></td>
          <td>
            <input type="submit" value="Enviar" class="button" id="botaoTabela1" />
            <input type="hidden" name="urlSite" id="urlSite" value="<?php echo $urlPrincipal[1]; ?>" required />
          </td>
        </tr>
      </tbody>
    </table>


    <table class="CRTConfigTable tabelaVenda2 displayNone">
      <tbody>
        <tr>
          <td>ID do Pedido</td>
          <td>
            <input type="text" id="idPedido" disabled="disabled" />
          </td>
        </tr>
        <tr>
          <td>Cliente</td>
          <td>
            <input type="text" id="nomeCliente" disabled="disabled" />
          </td>
        </tr>
        </tbody>
      </table>

      <h3 class="displayNone tabelaVenda2">Adicionar itens ao pedido:</h3>
      <table class="CRTConfigTable displayNone tabelaVenda2" id="tabelaAdicionar">
        <tbody>
          <tr>
            <td>Tipo</td>
            <td>
              <select id="tipoAdicionar"></select>
            </td>
          </tr>
          <tr id="receptorTabela2" class="etapa2Tabela2 displayNone">
            <td>Receptor</td>
            <td>
              <select>
                <optgroup label="Tipo de compra"></optgroup>
                <option value="">Selecione...</option>
              </select>
            </td>
          </tr>

          <input type="hidden" id="valorUnitarioNumberTabela2" />
          <tr id="valorUnitarioTabela2" class="etapa2Tabela2">
            <td>Valor Unitário(R$)</td>
            <td>
              <input type="text" disabled="disabled" />
            </td>
          </tr>

          <tr id="quantidadeTabela2" class="etapa2Tabela2">
            <td>Quantidade</td>
            <td>
              <input type="text" disabled="disabled" />
            </td>
          </tr>

          <input type="hidden" id="valorTotalNumberTabela2" />
          <tr id="valorTotalTabela2" class="etapa2Tabela2">
            <td>Valor Total(R$)</td>
            <td>
              <input type="text" disabled="disabled" />
            </td>
          </tr>

          <tr id="btnTabela2" class="etapa2Tabela2">
            <td></td>
            <td>
              <input type="button" id="addItemTabela2" value="Adicionar ao Pedido" class="button success" />
            </td>
          </tr>
        </tbody>
      </table>
      <input type="hidden" id="numItens" value="0" />
      <table class="CRTConfigTable displayNone tabelaVenda2-1 tabela2-1-1" id="tabelaListaItens">
        <thead>
          <tr>
            <td>Tipo</td>
            <td>Receptor</td>
            <td>Valor Unitário(R$)</td>
            <td>Qtde</td>
            <td>Valor Total(R$)</td>
            <td></td>
          </tr>
        </thead>
          <tbody></tbody>
      </table>

      <table class="CRTConfigTable displayNone tabelaVenda2-1 tabela2-1-2">
        <tbody>
          <tr>
            <td></td>
            <td>
              <input type="submit" value="Enviar" class="button" id="botaoTabela2" />
            </td>
          </tr>
        </tbody>
      </table>

      <div class="medium-48 columns centers displayNone" id="displayAviso">
        <div class="callout warning">
          <h5>ATENÇÃO!</h5>
          <p>Revise os dados com atenção. Se tiver algum erro, <a onClick="retorna2();">clique aqui</a> e revise,<br>
            pois <b>após o pedido concluído, ele NÃO PODERÁ SER DESFEITO.</b></p>
        </div>
      </div>



      <div class="medium-48 columns centers displayNone" id="avisoSucesso2">
        <div class="callout success">
          <h5>SUCESSO!</h5>
          <p>Pedido realizado com sucesso! Redirecionando para o comprovante em 5 segundos, ou <a>clique aqui</a>.</p>
          <p id="ticket" class="displayNone"></p>
        </div>
      </div>
      <div class="medium-48 columns centers displayNone" id="avisoErro2">
        <div class="callout alert">
          <h5>ERRO!</h5>
          <p></p>
        </div>
      </div>



      <table class="CRTConfigTable displayNone tabelaVenda3">
        <tbody>
          <tr>
            <td><h2>Valor Total</h2></td>
            <td id="valorTotalPedido">
              <h2><span></span></h2>
            </td>
          </tr>
            <tr>
              <td><h2>Forma de Pagamento</h2></td>
              <td>
                <select>
                </select>
              </td>
            </tr>
            <tr id="tabelaVenda3-1-1" class="displayNone">
              <td><h2>Valor Pago</h2></td>
              <td>
                <input type="text" id="valorPago" />
              </td>
            </tr>
            <tr id="trocoVenda" class="displayNone">
              <td><h2>Troco</h2></td>
              <td id="Troco">
                <h2><span></span></h2>
              </td>
            </tr>
            <tr>
              <td></td>
              <td>
                <input type="submit" value="Enviar" class="button" id="botaoFinalizaVenda" />
                <input type="hidden" value="" id="valorTotalVenda" />
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
    <script src="<?php echo $caminho; ?>js/jquery.formatCurrency.min.js"></script>
    <script src="<?php echo $caminho; ?>js/app.js"></script>
    <script src="<?php echo $caminho; ?>js/login.js"></script>
    <script src="js/novaVenda.js"></script>
  </body>
</html>
