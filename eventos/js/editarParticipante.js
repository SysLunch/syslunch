$(document).ready(function(){
  $('#nascimentoParticipante').mask('00/00/0000');
});
function editarParticipante(){

    if(!$("#avisoSucesso").hasClass("displayNone")){
      $("#avisoSucesso").addClass("displayNone");
    }

    if(!$("#avisoErro").hasClass("displayNone")){
      $("#avisoErro").addClass("displayNone");
    }


    var id = $("#idParticipante").val();
    var nomeParticipante = $("#nomeParticipante").val();
    var nascimentoParticipante = $("#nascimentoParticipante").val();
    var barras = $("#barras").val();

    if(nomeParticipante && nascimentoParticipante){
      var ajax = "ajax/editarParticipante.php?idParticipante="+id+"&nomeParticipante="+nomeParticipante+"&nascimentoParticipante="+nascimentoParticipante;

      $.getJSON(ajax, function(data){
        if(data.sucesso == 1){
          $("#avisoSucesso").removeClass("displayNone");
          if(barras == 1){
            setTimeout(function() {
              window.close();
            },3000);
          }
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

$("#botaoEditarParticipante").click(function(){
  editarParticipante();
});

$("#nomeParticipante").keypress(function(e) {
  if(e.which == 13) {
    editarParticipante();
  }
});

$("#nascimentoParticipante").keypress(function(e) {
  if(e.which == 13) {
    editarParticipante();
  }
});
