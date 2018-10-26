$(document).ready(function(){
  $('#nascimentoParticipante').mask('00/00/0000');
});
function novoParticipante(){

    if(!$("#avisoSucesso").hasClass("displayNone")){
      $("#avisoSucesso").addClass("displayNone");
    }

    if(!$("#avisoErro").hasClass("displayNone")){
      $("#avisoErro").addClass("displayNone");
    }

    $("#avisoSucesso > div > p > span").html('');


    var nomeParticipante = $("#nomeParticipante").val();
    var nascimentoParticipante = $("#nascimentoParticipante").val();
    var url = $("#urlSite").val();
    var idEvento = $("#idEvento").val();

    if(nomeParticipante && nascimentoParticipante){
      $.ajax({
        type: "post",
        url: "ajax/novoParticipante.php",
        data: "nomeParticipante="+nomeParticipante+"&nascimentoParticipante="+nascimentoParticipante+"&idEvento="+idEvento,
        dataType: "json",
        success: function(data){
          if(data.sucesso == 1){
            $("#avisoSucesso > div > p > b").html(data.nomeParticipante);
            $("#avisoSucesso > div > p > span").html($("#avisoSucesso > div > p > span").html()+"Deseja imprimir o cartão agora?<br><a href='../barras/funcionario.php?id="+data.id+"' target='_blank' class='button success'>Gerar Cartão do Participante</a>");


            $("#avisoSucesso").removeClass("displayNone");

            $("#nomeParticipante").val('');
            $("#nascimentoParticipante").val('');
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

$("#botaoNovoParticipante").click(function(){
  novoParticipante();
});

$("#nomeParticipante").keypress(function(e) {
  if(e.which == 13) {
    novoParticipante();
  }
});

$("#nascimentoParticipante").keypress(function(e) {
  if(e.which == 13) {
    novoParticipante();
  }
});
