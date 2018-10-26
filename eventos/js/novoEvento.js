function novoEvento(){

    if(!$("#avisoSucesso").hasClass("displayNone")){
      $("#avisoSucesso").addClass("displayNone");
    }

    if(!$("#avisoErro").hasClass("displayNone")){
      $("#avisoErro").addClass("displayNone");
    }

    $("#avisoSucesso > div > p > span").html('');


    var nomeEvento = $("#nomeEvento").val();
    var tipoLetra = $("#tipoLetra").val();
    var crt = $("#crt").val();
    var url = $("#urlSite").val();

    if(nomeEvento){
      $.ajax({
        type: "post",
        url: "ajax/novoEvento.php",
        data: "nomeEvento="+nomeEvento+"&crt="+crt+"&tipoLetra="+tipoLetra,
        dataType: "json",
        success: function(data){
          if(data.sucesso == 1){
            $("#avisoSucesso > div > p > b").html(data.nomeEvento);

            $("#avisoSucesso > div > p > span").html($("#avisoSucesso > div > p > span").html()+"<br>Deseja adicionar participantes agora? <a href='novoParticipante.php?id="+data.id+"'>Clique aqui!</a>");
            if(crt == 1){
              $("#avisoSucesso > div > p > span").html($("#avisoSucesso > div > p > span").html()+"<br>Deseja imprimir o código de Barras CRT do Evento? <a href='..barras/evento.php?id="+data.id+"'>Clique aqui!</a>");
            }


            $("#avisoSucesso").removeClass("displayNone");

            $("#nomeEvento").val('');
            $("#tipoLetra").val('');
          }else{
            $("#avisoErro > div > p").html(data.motivo);
            $("#avisoErro").removeClass("displayNone");
          }
        }
      });
    }else{
      $("#avisoErro > div > p").html("Algum dos campos está nulo. Por favor, verifique e tente novamente.");
      $("#avisoErro").removeClass("displayNone");
    }
}

$("#botaoNovoEvento").click(function(){
  novoEvento();
});

$("#nomeEvento").keypress(function(e) {
  if(e.which == 13) {
    novoEvento();
  }
});
