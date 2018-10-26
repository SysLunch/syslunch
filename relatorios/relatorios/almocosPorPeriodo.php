<?php
  $caminho = "../../";
  include($caminho."parametros.php");
  include($caminho."class/factory.php");
  $idEmpresa = $_GET["idEmpresa"];
  $dataInicial = $_GET["dataInicial"];
  $dataFinal = $_GET["dataFinal"];

  $filtro = $_GET["filtro"];
  $resumido = $_GET["resumido"];

  if(($dataInicial == NULL && $dataFinal == NULL && $filtro == NULL) || $dataInicial == NULL || $dataFinal == NULL){
    ?><script>window.close();</script><?php
    exit();
  }
  if((strtotime($dataInicial) - strtotime($dataFinal)) > 90*24*60*60){
    ?>
      <html class="no-js" lang="en">
        <head>
          <meta charset="utf-8" />
          <script>
            alert("A diferença entre a data inicial e a final não pode passar de 90 dias!");
            window.close();
          </script>
        </head>
      </html>
    <?php
    exit();
  }



  $factory = new Factory();
  $factory->setCaminho($caminho);
  $factory->importaClasses(array("login" => 1, "relatorio" => 1, "funcionario" => 1, "empresa" => 1));
  $factory->getObjeto("login")->permissaoPagina(2,1,"Relatórios");

  $relatorio = $factory->getObjeto("relatorio")->almocosPorPeriodo($dataInicial, $dataFinal, $idEmpresa, $filtro, $detalhado);
print_r($relatorio);
  $filtros[0] = "Cartão e Ticket";
  $filtros[1] = "Cartão";
  $filtros[2] = "Ticket";

//print_r($relatorio);

?><!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Relatório de Almoços por Período - <?php echo $nomeSite; ?></title>
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/foundation.css" />
    <link href="http://cdnjs.cloudflare.com/ajax/libs/foundicons/3.0.0/foundation-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/app.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/pagina.css" />
    <link rel="stylesheet" href="<?php echo $caminho; ?>css/impressao.css" />
  </head>
  <body>
    <table width="100%">
      <thead class="borderAll">
        <tr id="header">
          <td colspan="6" rowspan="2"><img src="<?php echo $urlPrincipal[1].$logo; ?>" style="height: 90px !important;"></td>
          <td colspan="4"><h1><b>Relatório: Almoços por Período</b></h1></td>
        </tr>
        <tr>
          <td colspan="1"><h6>Período: <b><?php echo date("d/m/Y",strtotime($dataInicial)); ?></b> ~ <b><?php echo date("d/m/Y",strtotime($dataFinal)); ?></b></h6></td>
          <td colspan="3"><h6>Filtro: <b><?php echo $filtros[$filtro]; ?></b></h6></td>
        </tr>
      </thead>
    </table>
    <?php
      $total = 0;
      $totalTickets = 0;
        $ticketsGratuitos = 0;
        $ticketsPagos = 0;
      $totalCartao = 0;
        $cartaoPago = 0;
        $cartaoGratuito = 0;

      $countDay = array();
      $count = 0;


      $cC = 1;
      //Estatísticas do Cartão
      foreach($relatorio["creditos"] as $inf){
        $dM = date("d",strtotime($inf["dataMovimentacao"]));
        $mM = date("m",strtotime($inf["dataMovimentacao"]));
        $yM = date("Y",strtotime($inf["dataMovimentacao"]));

        if($countDay[$yM][$mM][$dM]["creditos"]["C"] == NULL){
          $countDay[$yM][$mM][$dM]["creditos"]["C"] = 1;
        }else{
          $countDay[$yM][$mM][$dM]["creditos"]["C"]++;
        }

        if($empresa["consulta"][0]["isGratuito"] == 0){
          if($countDay[$yM][$mM][$dM]["creditos"]["T"]["pago"] == NULL){
            $countDay[$yM][$mM][$dM]["creditos"]["T"]["pago"] = 1;
          }else{
            $countDay[$yM][$mM][$dM]["creditos"]["T"]["pago"]++;
          }

          $cartaoPago++;
        }else{

          if($countDay[$yM][$mM][$dM]["creditos"]["T"]["gratuito"] == NULL){
            $countDay[$yM][$mM][$dM]["creditos"]["T"]["gratuito"] = 1;
          }else{
            $countDay[$yM][$mM][$dM]["creditos"]["T"]["gratuito"]++;
          }

          $cartaoGratuito++;
        }

        $countDay[$yM][$mM][$dM]["creditos"]["R"][$cC] = $inf;

        $count++;
        $total++;
        $totalCartao++;
        $cC++;
      }

      $cT = 1;
      //Estatísticas do Ticket
      foreach($relatorio["tickets"] as $inf){
        $dM = date("d",strtotime($inf["dataUtilizacao"]));
        $mM = date("m",strtotime($inf["dataUtilizacao"]));
        $yM = date("Y",strtotime($inf["dataUtilizacao"]));

        $countDay[$yM][$mM][$dM]["date"] = $yM."-".$mM."-".$dM;

        if($countDay[$yM][$mM][$dM]["tickets"]["C"] == NULL){
          $countDay[$yM][$mM][$dM]["tickets"]["C"] = 1;
        }else{
          $countDay[$yM][$mM][$dM]["tickets"]["C"]++;
        }

        if($empresa["consulta"][0]["isFree"] == 0){
          if($countDay[$yM][$mM][$dM]["tickets"]["T"]["pago"] == NULL){
            $countDay[$yM][$mM][$dM]["tickets"]["T"]["pago"] = 1;
          }else{
            $countDay[$yM][$mM][$dM]["tickets"]["T"]["pago"]++;
          }

          $ticketsPagos++;
        }else{

          if($countDay[$yM][$mM][$dM]["tickets"]["T"]["gratuito"] == NULL){
            $countDay[$yM][$mM][$dM]["tickets"]["T"]["gratuito"] = 1;
          }else{
            $countDay[$yM][$mM][$dM]["tickets"]["T"]["gratuito"]++;
          }

          $ticketsGratuitos++;
        }

        $countDay[$yM][$mM][$dM]["tickets"]["R"][$cT] = $inf;



        $count++;
        $total++;
        $totalTickets++;
        $cT++;
      }


      ?>
      <?php
      //Cartão
      //print_r($countDay);
      foreach($countDay as $inf[0]){
        //Ano
        foreach($inf[0] as $inf[1]){
          //Mês
          foreach($inf[1] as $inf[2]){
            //Dia
            $cD = 1;
            ?>
            <table width="100%">
              <tbody>
                <tr>
                  <td colspan="10" class="text-center borderTop"><h4>Dia: <b><?php echo date("d/m/Y", strtotime($inf[2]["date"])); ?></b></h4></td>
                </tr>
              </tbody>
            </table>
            <?php

            if(count($inf[2]["creditos"]["C"]) > 0){
              foreach($inf[2]["creditos"]["R"] as $inf[3]){
                if($cD == 1){
                  ?>
                  <table width="100%">
                    <tbody>
                      <tr>
                        <td colspan="10" class="text-center borderTop"><h4><b>Cartão</b></h4></td>
                      </tr>
                    </tbody>
                  </table>
                  <table width="100%" class="tableListaRelatorio">
                    <thead class="borderAll">
                      <tr>
                        <td colspan="2">#</td>
                        <td colspan="2">Hora</td>
                        <td colspan="4">Funcionário</td>
                        <td colspan="2">Empresa</td>
                        <td colspan="2">Tipo Almoço</td>
                      </tr>
                    </thead>
                    <tbody>
                  <?php

                }


                  $funcionario = $factory->getObjeto("funcionario")->buscaFuncionario($inf[3]["R"]["idFuncionario"]);
                  $empresa = $factory->getObjeto("empresa")->buscaEmpresa($funcionario["consulta"][0]["idEmpresa"]);
                ?>
                <tr>
                  <td colspan="2"><?php echo $i; ?></td>
                  <td colspan="2"><?php echo date("H:i:s",strtotime($inf[3]["R"]["dataMovimentacao"])); ?></td>
                  <td colspan="4"><?php echo $funcionario["consulta"][0]["nomeFuncionario"]; ?></td>
                  <td colspan="2"><?php if($empresa["count"] > 0){ echo "<b>".$empresa["consulta"][0]["nomeEmpresa"]."</b>"; }else{ echo "-"; } ?></td>
                  <td colspan="2"><?php if($empresa["consulta"][0]["isGratuito"] == 0){ echo "Almoço Pago"; }else{ echo "Almoço Gratuito"; } ?></td>
                </tr>
                <?php

                if($cD == $inf[3]["C"]){
                  ?>

                    </tbody>
                  </table>


                  <table width="100%">
                    <tbody>
                      <tr>
                        <td colspan="10" class="text-center borderTop"><h4><b>Resumo Dia</b></h4></td>
                      </tr>
                    </tbody>
                  </table>
                  <table width="100%">
                    <tbody>
                      <tr>
                        <td colspan="5"><b>Total de Créditos Utilizados:</b> <?php echo $inf[3]["C"]; ?></td>
                        <td colspan="5"><b>Demonstrativo de Créditos:</b> <?php echo $inf[3]["T"]["pago"]; ?> Almoço(s) Pago(s) e <?php echo$inf[3]["T"]["gratuito"]; ?> Almoço(s) Gratuito(s)</td>
                      </tr>
                    </tbody>
                  </table>
                  <?php
                }

                $cD++;
              }
            }
          }
        }

      ?>
      <?php
          $i = 1;
          /*foreach($relatorio["creditos"] as $inf){
            $funcionario = $factory->getObjeto("funcionario")->buscaFuncionario($inf["idFuncionario"]);
            $empresa = $factory->getObjeto("empresa")->buscaEmpresa($funcionario["consulta"][0]["idEmpresa"]);
      ?>
          <tr>
            <td colspan="2"><?php echo $i; ?></td>
            <td colspan="2"><?php echo date("H:i:s",strtotime($inf["dataMovimentacao"])); ?></td>
            <td colspan="4"><?php echo $funcionario["consulta"][0]["nomeFuncionario"]; ?></td>
            <td colspan="2"><?php if($empresa["count"] > 0){ echo "<b>".$empresa["consulta"][0]["nomeEmpresa"]."</b>"; }else{ echo "-"; } ?></td>
            <td colspan="2"><?php if($empresa["consulta"][0]["isGratuito"] == 0){ echo "Almoço Pago"; $cartaoPago++; }else{ echo "Almoço Gratuito"; $cartaoGratuito++; } ?></td>
          </tr>
      <?php
            $i++;
          }*/
        }
        ?>
    <?php

      if(count($relatorio["tickets"]) > 0){
    ?>

    <table width="100%">
      <tbody>
        <tr>
          <td colspan="10" class="text-center borderTop"><h4><b>Ticket</b></h4></td>
        </tr>
      </tbody>
    </table>
    <table width="100%" class="tableListaRelatorio">
      <thead class="borderAll">
        <tr>
          <td colspan="2">#</td>
          <td colspan="2">Hora</td>
          <td colspan="4">Tipo de Ticket</td>
          <td colspan="2">Código do Ticket</td>
        </tr>
      </thead>
      <tbody>
    <?php
      $i = 1;
      foreach($relatorio["tickets"] as $inf){
    ?>
        <tr>
          <td colspan="2"><?php echo $i; ?></td>
          <td colspan="2"><?php echo date("H:i:s",strtotime($inf["dataUtilizacao"])); ?></td>
          <td colspan="4"><?php if($inf["isFree"] == 1){ echo "Ticket Gratuito"; $ticketsGratuitos++; }else{ echo "Ticket"; $ticketsPagos++; } ?></td>
          <td colspan="2"><?php echo $inf["codigoTicket"]; ?></td>
        </tr>
    <?php
          $i++;
      }
    ?>
      </tbody>
    </table>
    <table width="100%">
      <tbody>
        <tr>
          <td colspan="1" style="width:40%"><b>Total de Tickets Utilizados:</b></td>
          <td colspan="1"><?php echo $totalTickets; ?></td>
        </tr>
      </tbody>
    </table>
    <?php
      }else{

      }
    ?>

    <table width="100%">
      <tbody>
        <tr>
          <td colspan="10" class="text-center borderTop"><h4><b>Resumo Total</b></h4></td>
        </tr>
      </tbody>
    </table>
    <table width="100%">
      <tbody>
        <tr>
          <td colspan="5"><b>Total de Créditos Utilizados:</b> <?php echo $totalCartao; ?></td>
          <td colspan="5"><b>Demonstrativo de Créditos:</b> <?php echo $cartaoPago; ?> Almoço(s) Pago(s) e <?php echo $cartaoGratuito; ?> Almoço(s) Gratuito(s)</td>
        </tr>
        <tr>
          <td colspan="5"><b>Total de Tickets Utilizados:</b> <?php echo $totalTickets; ?></td>
          <td colspan="5"><b>Demonstrativo de Tickets:</b> <?php echo $ticketsPagos; ?> Ticket(s) e <?php echo $ticketsGratuitos; ?> Ticket(s) Gratuito(s)</td>
        </tr>
        <tr>
          <td colspan="10"><b>Total de Almoços Registrados:</b> <?php echo $total; ?></td>
        </tr>
      </tbody>
    </table>
    <small>Relatório gerado em <?php echo date("d/m/Y"); ?> às <?php echo date("H:i:s"); ?>. <?php if(date("Y-m-d") == $data){ ?>Esse relatório foi gerado no mesmo dia do pesquisado e poderá haver alterações até o final do dia.<?php } ?></small>


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
