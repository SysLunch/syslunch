function novaEmpresa(){

    if(!$("#avisoSucesso").hasClass("displayNone")){
      $("#avisoSucesso").addClass("displayNone");
    }

    if(!$("#avisoErro").hasClass("displayNone")){
      $("#avisoErro").addClass("displayNone");
    }

    $("#avisoSucesso > div > p > span").html('');


    var nomeEmpresa = $("#nomeEmpresa").val();
    var loginEmpresa = $("#loginEmpresa").val();
    var senhaEmpresa = $("#senhaEmpresa").val();
    var tipoLetra = $("#tipoLetra").val();
    var selectSenhaEmpresa = $("#selectSenhaEmpresa").val();
    var url = $("#urlSite").val();

    if(selectSenhaEmpresa == 1){
      senhaEmpresa = '';
    }

    if(nomeEmpresa && loginEmpresa && (senhaEmpresa || selectSenhaEmpresa == 1)){
      $.ajax({
        type: "post",
        url: "ajax/novaEmpresa.php",
        data: "nomeEmpresa="+nomeEmpresa+"&loginEmpresa="+loginEmpresa+"&senhaEmpresa="+senhaEmpresa+"&tipoLetra="+tipoLetra,
        dataType: "json",
        success: function(data){
          if(data.sucesso == 1){
            $("#avisoSucesso > div > p > b").html(data.nomeEmpresa);

            if(selectSenhaEmpresa == 1){
              $("#avisoSucesso > div > p > span").html("<br>Uma senha foi gerada para o login da empresa. A senha é <b>"+data.senha+"</b>.");
            }

            $("#avisoSucesso > div > p > span").html($("#avisoSucesso > div > p > span").html()+"<br>Deseja adicionar funcionários agora? <a href='novoFuncionario.php?id="+data.id+"'>Clique aqui!</a>");


            $("#avisoSucesso").removeClass("displayNone");

            $("#nomeEmpresa").val('');
            $("#loginEmpresa").val('');
            $("#senhaEmpresa").val('');
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

$("#selectSenhaEmpresa").change(function(){
  if($("#selectSenhaEmpresa").val() == 2){
    if($("#senhaEmpresa").hasClass("displayNone")){
      $("#senhaEmpresa").removeClass("displayNone");
    }
  }else{
    if(!$("#senhaEmpresa").hasClass("displayNone")){
      $("#senhaEmpresa").addClass("displayNone");
    }
  }
});

$("#botaoNovaEmpresa").click(function(){
  novaEmpresa();
});

$("#nomeEmpresa").keypress(function(e) {
  if(e.which == 13) {
    novaEmpresa();
  }
});

$("#loginEmpresa").keypress(function(e) {
  if(e.which == 13) {
    novaEmpresa();
  }
});

$("#senhaEmpresa").keypress(function(e) {
  if(e.which == 13) {
    novaEmpresa();
  }
});
