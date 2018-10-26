redireciona = 1;

function novaVenda(etapa){

  if(!$("#avisoSucesso").hasClass("displayNone")){
    $("#avisoSucesso").addClass("displayNone");
  }

  if(!$("#avisoErro").hasClass("displayNone")){
    $("#avisoErro").addClass("displayNone");
  }

  if(!$("#avisoSucesso2").hasClass("displayNone")){
    $("#avisoSucesso2").addClass("displayNone");
  }

  if(!$("#avisoErro2").hasClass("displayNone")){
    $("#avisoErro2").addClass("displayNone");
  }

  var url = $("#urlSite").val();
  if(etapa == 1){
    idEmpresa = $("#tabelaVenda1 select").val();

    var ajax = "ajax/novaVenda.php?etapa=1&idEmpresa="+idEmpresa;

    //alert(ajax);

    $.getJSON(ajax, function(data){
      if(data.sucesso == 1){
        if(data.nomeEmpresa){
          $("#nomeCliente").val(data.nomeEmpresa);
        }else{
          $("#nomeCliente").val("Cliente não identificado");
        }

        $("#idPedido").val(data.id);


        var ajax = "ajax/carregaTiposProdutos.php?idEmpresa="+idEmpresa;
        $("#tipoAdicionar").load(ajax);


        $('#quantidadeTabela2 input').mask('0000');

        $("#tabelaVenda1").addClass("displayNone");
        $(".tabelaVenda2").removeClass("displayNone");
        $("#tabelaAtiva").val(2);
      }else{
        $("#avisoErro > div > p").html(data.motivo);
        $("#avisoErro").removeClass("displayNone");
      }
    });
  }
  if(etapa == 2){
    idPedido = $("#idPedido").val();

    tipoProduto = $("#tipoAdicionar").val();
    qtd = $("#quantidadeTabela2 input").val();



    var ajax = "ajax/adicionarItemPedido.php?idPedido="+idPedido+"&idTipoProduto="+tipoProduto+"&qtd="+qtd;

    if(!$("#receptorTabela2").hasClass("displayNone")){
      ajax = ajax+"&receptor="+$("#receptorTabela2 select").val();
    }
    //alert(ajax);

    $.getJSON(ajax, function(data){

        if(data.sucesso == 1){
          if($("#numItens").val() == 0){

            if($("#tabelaListaItens").hasClass("displayNone")){
              $("#tabelaListaItens").removeClass("displayNone");
            }

            if($(".tabelaVenda2-1").hasClass("displayNone")){
              $(".tabelaVenda2-1").removeClass("displayNone");
            }

          }


          $("#valorUnitarioTabela2 input").val('');
          $("#tipoAdicionar").val('');
          $("#quantidadeTabela2 input").val('');
          $("#receptorTabela2 select").val('');
          $("#valorTotalTabela2 input").val('');
          if(!$("#receptorTabela2").hasClass("displayNone")){
            $("#receptorTabela2").addClass("displayNone");
              //alert(0);
          }else{
            //alert(1);
          }
          if(data.isNew == 1){
            $("#numItens").val(parseInt($("#numItens").val())+1);
          }
          listaItensPedido();
        }else{
          $("#avisoErro > div > p").html(data.motivo);
          $("#avisoErro").removeClass("displayNone");
        }
          //alert(2);
    });
      //alert(3);
  }
  if(etapa == 3){
    idPedido = $("#idPedido").val();
    var ajax = "ajax/dadosPedido.php?idPedido="+idPedido;
    $.getJSON(ajax,function(data){

      $("#valorTotalPedido h2").html('R$ '+data.valorTotal);
      $("#valorTotalVenda").val(data.valorTotal2);
      $(".tabelaVenda3 select").html(data.meio);

      $(".tabelaVenda2").addClass("displayNone");
      $(".tabelaVenda2-1-1").addClass("displayNone");
      $(".tabela2-1-2").addClass("displayNone");
      $("#displayAviso").removeClass("displayNone");
      $(".tabelaVenda3").removeClass("displayNone");
      $(".opcoesCRT").html("");
      $("#tabelaAtiva").val(3);


    });
  }
  if(etapa == 4){
    idPedido = $("#idPedido").val();
    valorPago = $("#valorPago").val();
    meioPagamento = $(".tabelaVenda3 select").val();

    var ajax = "ajax/finalizaPedido.php?idPedido="+idPedido+"&valorPago="+valorPago+"&meioPagamento="+meioPagamento;
    //alert(ajax);

    $.getJSON(ajax,function(data){
      if(data.sucesso == 1){

        $("#avisoSucesso2").removeClass("displayNone");
        $("#displayAviso").addClass("displayNone");
        $("#tabelaListaItens").addClass("displayNone");
        $(".tabelaVenda3 input").attr("disabled", "disabled");
        $(".tabelaVenda3 select").attr("disabled", "disabled");

        if(data.troco != 0){
          $("#trocoVenda").removeClass("displayNone");
          $("#Troco span").html("R$ "+data.troco);
        }
        $("#avisoSucesso2 a").attr("onClick", "redirecionarRecibo("+idPedido+");");
        if(data.hasTicket == true){
          $("#ticket").removeClass("displayNone");
          $("#ticket").html("Deseja imprimir os tickets? <a onClick='imprimirTickets()'>Clique aqui!</a>");
        }
        redirecionaRecibo(idPedido);

      }else{
        $("#avisoErro2 > div > p").html(data.motivo);
        $("#avisoErro2").removeClass("displayNone");
      }
    });
  }
}

function retorna2(){

  $(".tabelaVenda2").removeClass("displayNone");
  $(".tabelaVenda2-1-1").removeClass("displayNone");
  $("#displayAviso").addClass("displayNone");
  $(".tabelaVenda3").addClass("displayNone");

  listaItensPedido();
}

function listaItensPedido(){
  idPedido = $("#idPedido").val();
  var listaItens = "ajax/listaItensPedido.php?idPedido="+idPedido;
  $("#tabelaListaItens tbody").load(listaItens);
}

function carregarDadosProduto(idTipoProduto){

  if(!$("#avisoSucesso").hasClass("displayNone")){
    $("#avisoSucesso").addClass("displayNone");
  }

  if(!$("#avisoErro").hasClass("displayNone")){
    $("#avisoErro").addClass("displayNone");
  }

  if(!$("#receptorTabela2").hasClass("displayNone")){
    $("#receptorTabela2").addClass("displayNone");
  }

  $("#valorUnitarioTabela2 input").val('');
  $("#quantidadeTabela2 input").val('');
  $("#valorTotalTabela2 input").val('');

  var url = $("#urlSite").val();

  var idEmpresa = $("#tabelaVenda1 select").val();

  var ajax = "ajax/carregaTipoProduto.php?idTipoProduto="+idTipoProduto;
  $.getJSON(ajax, function(data){
    if(data.sucesso == 1){
      $("#quantidadeTabela2 input").removeAttr('disabled');
      $("#valorUnitarioNumberTabela2").val(data.valorUnitario);
      $("#valorUnitarioTabela2 input").val(data.valorUnitario);

      if(data.isCartao == 1){

        if($("#receptorTabela2").hasClass("displayNone")){
          $("#receptorTabela2").removeClass("displayNone");
        }
        var ajax2 = "ajax/carregaFuncionarios.php?idEmpresa="+idEmpresa;
        $("#receptorTabela2 select").load(ajax2);
      }else{
          $("#receptorTabela2 select").html("");
      }
    }else{
      $("#avisoErro > div > p").html(data.motivo);
      $("#avisoErro").removeClass("displayNone");
    }
  });

}

function atualizarPreco(){
  if(!$("#avisoSucesso").hasClass("displayNone")){
    $("#avisoSucesso").addClass("displayNone");
  }

  if(!$("#avisoErro").hasClass("displayNone")){
    $("#avisoErro").addClass("displayNone");
  }
  valorUnitario = $("#valorUnitarioNumberTabela2").val();
  if(!isNaN($("#quantidadeTabela2 input").val())){
    qtde = Math.floor($("#quantidadeTabela2 input").val()/1);

    total = qtde*valorUnitario;
    $("#quantidadeTabela2 input").val(qtde);
    $("#valorTotalNumberTabela2").val(total);
    $("#valorTotalTabela2 input").val(total);
  }else{
      $("#avisoErro > div > p").html("Digite um número inteiro em quantidade...");
      $("#quantidadeTabela2 input").val('');
      $("#valorTotalTabela2 input").val('');
      $("#avisoErro").removeClass("displayNone");
  }
}

function carregaQuantidade(){
  var idPedido = $("#idPedido").val();
  var idTipoProduto = $("#tipoAdicionar").val();
  var receptor = $("#receptorTabela2 select").val();
  var ajax = "ajax/dadosPedidoReceptor.php?idPedido="+idPedido+"&&idTipoProduto="+idTipoProduto;
  if($("#receptorTabela2 select").val()){
    ajax = ajax+"&receptor="+receptor;
  }

  //alert(ajax);

  $.getJSON(ajax, function(data){
    if(data.qtde || data.qtde != 0){
      $("#quantidadeTabela2 input").val(data.qtde);
      atualizarPreco();
    }
  });
}

function removeItem(idPedido,idItem){
  var ajax = "ajax/removeItem.php?idItem="+idItem+"&idPedido="+idPedido;
  //alert(ajax);

  $.getJSON(ajax, function(data){
    if(data.sucesso == 1){
      listaItensPedido();

      $("#numItens").val(parseInt($("#numItens").val())-1);
      if($("#numItens").val() <= 0){
        $(".tabelaVenda2-1").addClass("displayNone");
      }
    }else{
      $("#avisoErro > div > p").html(data.motivo);
      $("#avisoErro").removeClass("displayNone");
    }
  });
}

function redirecionaRecibo(idPedido){
  for(var i=6; i > 0; i--){
    setTimeout(function(){
      if(i == 0 && redireciona == 1){
        redirecionarRecibo(idPedido);
      }
    }, 1000);
  }
}

function redirecionarRecibo(idPedido){
  window.open('comprovanteVenda.php?id='+idPedido, '_blank');
  redireciona = 0;
  return true;
}

function imprimirTickets(){
  var idPedido = $("#idPedido").val();

  window.open('../barras/ticket.php?id='+idPedido, '_blank');
}

$("#botaoTabela1").click(function(){
  novaVenda(1);
  $('#valorTotalTabela2 input').formatCurrency({decimalSymbol:',', region: 'pt-BR' });
});

$("#botaoTabela2").click(function(){
  novaVenda(3);
});

$("#botaoFinalizaVenda").click(function(){
  novaVenda(4);
});

$("#tipoAdicionar").change(function(){
  carregarDadosProduto($("#tipoAdicionar").val());
  carregaQuantidade();
});

$("#quantidadeTabela2 input").keyup(function(){
  atualizarPreco();
});

$("#addItemTabela2").click(function(){
  novaVenda(2);
});

$("#receptorTabela2 select").change(function(){
  carregaQuantidade();
});

$(".tabelaVenda3 select").change(function(){

  $('#tabelaVenda3-1-1 input').mask("000000,00", {reverse: true});

  if($(".tabelaVenda3 select").val() > 0){
    if($("#tabelaVenda3-1-1").hasClass("displayNone")){
      $("#tabelaVenda3-1-1").removeClass("displayNone");
    }
  }else{
    if(!$("#tabelaVenda3-1-1").hasClass("displayNone")){
      $("#tabelaVenda3-1-1").addClass("displayNone");
    }
  }
});
$('#tabelaVenda3-1-1 input').keyup(function(){

});
