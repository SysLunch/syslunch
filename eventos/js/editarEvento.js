function editarEvento(){

    if(!$("#avisoSucesso").hasClass("displayNone")){
      $("#avisoSucesso").addClass("displayNone");
    }

    if(!$("#avisoErro").hasClass("displayNone")){
      $("#avisoErro").addClass("displayNone");
    }


    var id = $("#idEvento").val();
    var nomeEvento = $("#nomeEvento").val();
    var tipoLetra = $("#tipoLetra").val();

    if(nomeEvento){
      var ajax = "ajax/editarEvento.php?idEvento="+id+"&nomeEvento="+nomeEvento+"&tipoLetra="+tipoLetra;

      $.getJSON(ajax, function(data){
        if(data.sucesso == 1){
          $("#avisoSucesso").removeClass("displayNone");
        }else{
          $("#avisoErro > div > p").html(data.motivo);
          $("#avisoErro").removeClass("displayNone");
        }
      });
    }else{
      $("#avisoErro > div > p").html("Um dos campos obrigatórios estão nulos. Por favor, verifique e tente novamente.");
      $("#avisoErro").removeClass("displayNone");
    }
}

$("#botaoEditarEvento").click(function(){
  editarEvento();
});

$("#nomeEvento").keypress(function(e) {
  if(e.which == 13) {
    editarEvento();
  }
});
