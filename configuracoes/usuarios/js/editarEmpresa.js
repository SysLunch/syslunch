function editarEmpresa(){

    if(!$("#avisoSucesso").hasClass("displayNone")){
      $("#avisoSucesso").addClass("displayNone");
    }

    if(!$("#avisoErro").hasClass("displayNone")){
      $("#avisoErro").addClass("displayNone");
    }


    var id = $("#idEmpresa").val();
    var nomeEmpresa = $("#nomeEmpresa").val();
    var loginEmpresa = $("#loginEmpresa").val();
    var isGratuito = $("#isGratuito").val();

    if(nomeEmpresa && loginEmpresa){
      var ajax = "ajax/editarEmpresa.php?idEmpresa="+id+"&nomeEmpresa="+nomeEmpresa+"&isGratuito="+isGratuito+"&loginEmpresa="+loginEmpresa;

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

$("#botaoEditarEmpresa").click(function(){
  editarEmpresa();
});

$("#nomeEmpresa").keypress(function(e) {
  if(e.which == 13) {
    editarEmpresa();
  }
});

$("#loginEmpresa").keypress(function(e) {
  if(e.which == 13) {
    editarEmpresa();
  }
});
