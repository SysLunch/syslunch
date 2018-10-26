$(document).ready(function(){
  $('#nascimentoFuncionario').mask('00/00/0000');
});
function novoFuncionario(){

    if(!$("#avisoSucesso").hasClass("displayNone")){
      $("#avisoSucesso").addClass("displayNone");
    }

    if(!$("#avisoErro").hasClass("displayNone")){
      $("#avisoErro").addClass("displayNone");
    }

    $("#avisoSucesso > div > p > span").html('');


    var nomeFuncionario = $("#nomeFuncionario").val();
    var nascimentoFuncionario = $("#nascimentoFuncionario").val();
    var url = $("#urlSite").val();
    var idEmpresa = $("#idEmpresa").val();

    if(nomeFuncionario && nascimentoFuncionario){
      $.ajax({
        type: "post",
        url: "ajax/novoFuncionario.php",
        data: "nomeFuncionario="+nomeFuncionario+"&nascimentoFuncionario="+nascimentoFuncionario+"&idEmpresa="+idEmpresa,
        dataType: "json",
        success: function(data){
          if(data.sucesso == 1){
            $("#avisoSucesso > div > p > b").html(data.nomeFuncionario);
            $("#avisoSucesso > div > p > span").html($("#avisoSucesso > div > p > span").html()+"Deseja imprimir o cartão agora?<br><a href='../barras/funcionario.php?id="+data.id+"' target='_blank' class='button success'>Gerar Cartão do Funcionário</a>");


            $("#avisoSucesso").removeClass("displayNone");

            $("#nomeFuncionario").val('');
            $("#nascimentoFuncionario").val('');
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

$("#botaoNovoFuncionario").click(function(){
  novoFuncionario();
});

$("#nomeFuncionario").keypress(function(e) {
  if(e.which == 13) {
    novoFuncionario();
  }
});

$("#nascimentoFuncionario").keypress(function(e) {
  if(e.which == 13) {
    novoFuncionario();
  }
});
