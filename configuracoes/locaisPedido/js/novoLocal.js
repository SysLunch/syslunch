function novoLocal(){

    if(!$("#avisoSucesso").hasClass("displayNone")){
      $("#avisoSucesso").addClass("displayNone");
    }

    if(!$("#avisoErro").hasClass("displayNone")){
      $("#avisoErro").addClass("displayNone");
    }


    var nomeLocal = $("#nomeLocal").val();
    var url = $("#urlSite").val();

    var ajax = "ajax/novoLocal.php?nomeLocal="+nomeLocal;
    $.getJSON(ajax, function(data){
      if(data.sucesso == 1){
        $("#avisoSucesso > div > p > b").html(data.nomeLocal);
        $("#avisoSucesso").removeClass("displayNone");
        $("#nomeLocal").val('');
      }else{
        $("#avisoErro > div > p").html(data.motivo);
        $("#avisoErro").removeClass("displayNone");
      }
    });
}

$("#botaoNovoLocal").click(function(){
  novoLocal();
});

$("#nomeLocal").keypress(function(e) {
  if(e.which == 13) {
    novoLocal();
  }
});
