$(document).ready(function(){
  $('#nascimentoFuncionario').mask('00/00/0000');
});
function editarFuncionario(){

    if(!$("#avisoSucesso").hasClass("displayNone")){
      $("#avisoSucesso").addClass("displayNone");
    }

    if(!$("#avisoErro").hasClass("displayNone")){
      $("#avisoErro").addClass("displayNone");
    }


    var id = $("#idFuncionario").val();
    var nomeFuncionario = $("#nomeFuncionario").val();
    var nascimentoFuncionario = $("#nascimentoFuncionario").val();
    var codigoCartao = $("#codigoCartao").val();
    var barras = $("#barras").val();

    if(nomeFuncionario && nascimentoFuncionario && codigoCartao){
      var ajax = "ajax/editarFuncionario.php?idFuncionario="+id+"&nomeFuncionario="+nomeFuncionario+"&nascimentoFuncionario="+nascimentoFuncionario+"&codigoCartao="+codigoCartao;

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

$("#mostrarCodigo").click(function(){
  $("#showCodigoBarras").toggleClass("displayNone");
});

$("#codigoCartao").change(function(){
  $("#showCodigoBarras").val($("#codigoCartao").val());
});

$("#botaoEditarFuncionario").click(function(){
  editarFuncionario();
});

$("#nomeFuncionario").keypress(function(e) {
  if(e.which == 13) {
    editarFuncionario();
  }
});

$("#nascimentoFuncionario").keypress(function(e) {
  if(e.which == 13) {
    editarFuncionario();
  }
});
