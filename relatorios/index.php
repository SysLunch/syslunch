<?php
  $caminho = "../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $PagAt = 4;

  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "empresa" => 1));
  $factory->getObjeto("login")->permissaoPagina(2,1,"Relatórios");

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Relatórios - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="<?php echo $caminho; ?>assets/foundation-icon-fonts/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
  </head>
  <body>

<?php include("../php/header.php"); ?>

<div class="row">
  <div class="medium-48 columns">
    <div class="tituloPagina"><?php echo $nomeSite; ?> >> Relatórios</div>
    <div class="subtituloPagina">Selecione uma das opções abaixo:</div>
  </div>
  <div class="medium-48 columns centers displayNone" id="avisoErro">
    <div class="callout alert">
      <h5>ERRO!</h5>
      <p></p>
    </div>
  </div>
  <div class="medium-48 columns centers">
    <ul class="tabs" data-tabs id="relatoriosTabs">
      <li class="tabs-title is-active"><a href="#almocosDia" aria-selected="true">Quantidade de Almoços por Dia(Detalhado)</a></li>
      <!--<li class="tabs-title"><a href="#almocosPeriodo">Quantidade de Almoços por Período(Resumido)</a></li>-->
      <!--<li class="tabs-title"><a href="#panel2">Tab 2</a></li>-->
    </ul>
    <input type="hidden" id="dataServidor" value="<?php echo date("Y-m-d"); ?>" />
    <div class="tabs-content" data-tabs-content="relatoriosTabs">
      <div class="tabs-panel is-active" id="almocosDia">
        <table width="100%" class="relatorios">
          <tr>
            <td colspan="2">Selecione a Data:</td>
            <td colspan="5">
              <div class="row">
                <div class="medium-12 columns">
                  <select class="dia">
                    <optgroup label="Dia"></optgroup>
                    <option value="">Selecione</option>
                    <?php
                      for($i = 1; $i <= 31; $i++){
                        ?><option value="<?php if($i < 10){ echo 0; } echo $i; if($i == date("d")){ ?>" selected="selected<?php } ?>"><?php if($i < 10){ echo 0; } echo $i; ?></option><?php
                      }
                    ?>
                  </select>
                </div>
                <div class="medium-3 columns barrasData">
                  /
                </div>
                <div class="medium-18 columns">
                  <select class="mes">
                    <optgroup label="Mês"></optgroup>
                    <option value="">Selecione</option>
                    <?php
                      $meses = array(1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
                      $i = 1;
                      foreach($meses as $inf){
                        ?><option value="<?php if($i < 10){ echo 0; } echo $i; if($i == date("m")){ ?>" selected="selected<?php } ?>"><?php echo $inf; ?></option><?php
                        $i++;
                      }
                    ?>
                  </select>
                </div>
                <div class="medium-3 columns barrasData">
                  /
                </div>
                <div class="medium-12 columns">
                  <select class="ano">
                    <optgroup label="Ano"></optgroup>
                    <option value="">Selecione</option>
                    <?php
                      for($i = date("Y"); $i >= 2016; $i--){
                        ?><option value="<?php if($i < 10){ echo 0; } echo $i; if($i == date("Y")){ ?>" selected="selected<?php } ?>"><?php if($i < 10){ echo 0; } echo $i; ?></option><?php
                      }
                    ?>
                  </select>
                </div>
              </div>
            </td>
          </tr>
          <tr id="empresas">
            <td colspan="2">Empresas:</td>
            <td colspan="5">
              <select>
                <optgroup label="Empresa"></optgroup>
                <option value="0">Todas as Empresas</option>
                <?php
                  $empresas = $factory->getObjeto("empresa")->listaEmpresas("1");
                  foreach($empresas as $inf){
                ?>
                <option value="<?php echo $inf["idEmpresa"]; ?>"><?php echo $inf["nomeEmpresa"]; ?></option>
                <?php
                  }
                ?>
              </select>
            </td>
          </tr>
          <tr id="filtro">
            <td colspan="2">Filtro:</td>
            <td colspan="5">
              <select>
                <optgroup label="Filtro"></optgroup>
                <option value="0">Cartão e Ticket</option>
                <option value="1">Apenas Cartão</option>
                <option value="2">Apenas Ticket</option>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="6"></td>
            <td colspan="1"><input type="button" id="botaoRelatorioAlmocosDia" class="button" value="Gerar Relatório" /></td>
          </tr>
        </table>
      </div>

      <div class="tabs-panel" id="almocosPeriodo">
        <table width="100%" class="relatorios">
          <tr id="inicial">
            <td colspan="2">Selecione a Data Inicial:</td>
            <td colspan="5">
              <div class="row">
                <div class="medium-12 columns">
                  <select class="dia">
                    <optgroup label="Dia"></optgroup>
                    <option value="">Selecione</option>
                    <?php
                      for($i = 1; $i <= 31; $i++){
                        ?><option value="<?php if($i < 10){ echo 0; } echo $i; if($i == date("d", time()-(60*60*24))){ ?>" selected="selected<?php } ?>"><?php if($i < 10){ echo 0; } echo $i; ?></option><?php
                      }
                    ?>
                  </select>
                </div>
                <div class="medium-3 columns barrasData">
                  /
                </div>
                <div class="medium-18 columns">
                  <select class="mes">
                    <optgroup label="Mês"></optgroup>
                    <option value="">Selecione</option>
                    <?php
                      $meses = array(1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
                      $i = 1;
                      foreach($meses as $inf){
                        ?><option value="<?php if($i < 10){ echo 0; } echo $i; if($i == date("m", time()-(60*60*24))){ ?>" selected="selected<?php } ?>"><?php echo $inf; ?></option><?php
                        $i++;
                      }
                    ?>
                  </select>
                </div>
                <div class="medium-3 columns barrasData">
                  /
                </div>
                <div class="medium-12 columns">
                  <select class="ano">
                    <optgroup label="Ano"></optgroup>
                    <option value="">Selecione</option>
                    <?php
                      for($i = date("Y"); $i >= 2016; $i--){
                        ?><option value="<?php if($i < 10){ echo 0; } echo $i; if($i == date("Y", time()-(60*60*24))){ ?>" selected="selected<?php } ?>"><?php if($i < 10){ echo 0; } echo $i; ?></option><?php
                      }
                    ?>
                  </select>
                </div>
              </div>
            </td>
          </tr>

          <tr id="final">
            <td colspan="2">Selecione a Data Final:</td>
            <td colspan="5">
              <div class="row">
                <div class="medium-12 columns">
                  <select class="dia">
                    <optgroup label="Dia"></optgroup>
                    <option value="">Selecione</option>
                    <?php
                      for($i = 1; $i <= 31; $i++){
                        ?><option value="<?php if($i < 10){ echo 0; } echo $i; if($i == date("d")){ ?>" selected="selected<?php } ?>"><?php if($i < 10){ echo 0; } echo $i; ?></option><?php
                      }
                    ?>
                  </select>
                </div>
                <div class="medium-3 columns barrasData">
                  /
                </div>
                <div class="medium-18 columns">
                  <select class="mes">
                    <optgroup label="Mês"></optgroup>
                    <option value="">Selecione</option>
                    <?php
                      $meses = array(1 => "Janeiro", 2 => "Fevereiro", 3 => "Março", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
                      $i = 1;
                      foreach($meses as $inf){
                        ?><option value="<?php if($i < 10){ echo 0; } echo $i; if($i == date("m")){ ?>" selected="selected<?php } ?>"><?php echo $inf; ?></option><?php
                        $i++;
                      }
                    ?>
                  </select>
                </div>
                <div class="medium-3 columns barrasData">
                  /
                </div>
                <div class="medium-12 columns">
                  <select class="ano">
                    <optgroup label="Ano"></optgroup>
                    <option value="">Selecione</option>
                    <?php
                      for($i = date("Y"); $i >= 2016; $i--){
                        ?><option value="<?php if($i < 10){ echo 0; } echo $i; if($i == date("Y")){ ?>" selected="selected<?php } ?>"><?php if($i < 10){ echo 0; } echo $i; ?></option><?php
                      }
                    ?>
                  </select>
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="7"><b>ATENÇÃO!</b> O prazo mínimo de consulta deste relatório é de 2 dias e o máximo é de <b>90 dias</b><br>(aproximadamente 3 meses, dependendo de cada caso).</td>
          </tr>
          <tr id="empresas">
            <td colspan="2">Empresas:</td>
            <td colspan="5">
              <select>
                <optgroup label="Empresa"></optgroup>
                <option value="0">Todas as Empresas</option>
                <?php
                  $empresas = $factory->getObjeto("empresa")->listaEmpresas();
                  foreach($empresas as $inf){
                ?>
                <option value="<?php echo $inf["idEmpresa"]; ?>"><?php echo $inf["nomeEmpresa"]; ?></option>
                <?php
                  }
                ?>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="2">Detalhado?</td>
            <td colspan="5">
              <select id="relatorioDetalhado">
                <optgroup label="Relatório Detalhado?"></optgroup>
                <option value="0">Não</option>
                <option value="1">Sim</option>
              </select>

            </td>
          </tr>
          <tr>
            <td colspan="7">Relatórios detalhados podem ser mais demorados para serem gerados.</td>
          </tr>
          <tr id="filtro">
            <td colspan="2">Filtro:</td>
            <td colspan="5">
              <select>
                <optgroup label="Filtro"></optgroup>
                <option value="0">Cartão e Ticket</option>
                <option value="1">Apenas Cartão</option>
                <option value="2">Apenas Ticket</option>
              </select>
            </td>
          </tr>
          <tr>
            <td colspan="6"></td>
            <td colspan="1"><input type="button" id="botaoRelatorioAlmocosPeriodo" class="button" value="Gerar Relatório" /></td>
          </tr>
        </table>
      </div>


      <div class="tabs-panel" id="panel2">

      </div>
    </div>
  </div>
</div>


<?php include("../php/footer.php"); ?>


    <script src="<?php echo $caminho; ?>js/vendor/jquery.min.js"></script>
    <script src="<?php echo $caminho; ?>js/vendor/what-input.min.js"></script>
    <script src="<?php echo $caminho; ?>js/foundation.min.js"></script>
    <script src="<?php echo $caminho; ?>js/app.js"></script>
    <script src="<?php echo $caminho; ?>js/login.js"></script>
    <script src="js/relatorios.js"></script>
  </body>
</html>
