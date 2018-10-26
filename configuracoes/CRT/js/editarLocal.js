function editarLocal(){

    if(!$("#avisoSucesso").hasClass("displayNone")){
      $("#avisoSucesso").addClass("displayNone");
    }

    if(!$("#avisoErro").hasClass("displayNone")){
      $("#avisoErro").addClass("displayNone");
    }


    var id = $("#idLocal").val();
    var nomeLocal = $("#nomeLocal").val();

    var ajax = "ajax/editarLocal.php?idLocal="+id+"&nomeLocal="+nomeLocal;

    $.getJSON(ajax, function(data){
      if(data.sucesso == 1){
        $("#avisoSucesso").removeClass("displayNone");
      }else{
        $("#avisoErro > div > p").html(data.motivo);
        $("#avisoErro").removeClass("displayNone");
      }
    });
}

$("#botaoEditarLocal").click(function(){
  editarLocal();
});

$("#nomeLocal").keypress(function(e) {
  if(e.which == 13) {
    editarLocal();
  }
});
