function novoMeio(){

  if(!$("#avisoSucesso").hasClass("displayNone")){
    $("#avisoSucesso").addClass("displayNone");
  }

  if(!$("#avisoErro").hasClass("displayNone")){
    $("#avisoErro").addClass("displayNone");
  }


  var meioPagamento = $("#meioPagamento").val();
  var actionPagamento = $("#actionPagamento").val();
  var url = $("#urlSite").val();

  if(meioPagamento){
    $.ajax({
      type: "post",
      url: "ajax/novoMeio.php",
      data: "meioPagamento="+meioPagamento+"&actionPagamento="+actionPagamento,
      dataType: "json",
      success: function(data){
        if(data.sucesso == 1){
          $("#avisoSucesso > div > p > b").html(data.meioPagamento);
          $("#avisoSucesso").removeClass("displayNone");
          $("#meioPagamento").val('');
          $("#actionPagamento").val('');
        }else{
          $("#avisoErro > div > p").html(data.motivo);
          $("#avisoErro").removeClass("displayNone");
        }
      }
    });
  }else{
    $("#avisoErro > div > p").html("Campo 'Nome do Meio de Pagamento' está nulo.");
    $("#avisoErro").removeClass("displayNone");
  }
}

$("#botaoNovoMeio").click(function(){
  novoMeio();
});

$("#meioPagamento").keypress(function(e) {
  if(e.which == 13) {
    novoMeio();
  }
});
$("#actionPagamento").keypress(function(e) {
  if(e.which == 13) {
    novoMeio();
  }
});
