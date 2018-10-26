function editarMeio(){

  if(!$("#avisoSucesso").hasClass("displayNone")){
    $("#avisoSucesso").addClass("displayNone");
  }

  if(!$("#avisoErro").hasClass("displayNone")){
    $("#avisoErro").addClass("displayNone");
  }


  var id = $("#idMeio").val();
  var meioPagamento = $("#meioPagamento").val();
  var actionPagamento = $("#actionPagamento").val();

  if(meioPagamento){
    $.ajax({
      type: "post",
      url: "ajax/editarMeio.php",
      data: "meioPagamento="+meioPagamento+"&actionPagamento="+actionPagamento+"&idMeio="+id,
      dataType: "json",
      success: function(data){
        if(data.sucesso == 1){
          $("#avisoSucesso").removeClass("displayNone");
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

$("#botaoEditarMeio").click(function(){
  editarMeio();
});

$("#meioPagamento").keypress(function(e) {
  if(e.which == 13) {
    editarMeio();
  }
});
$("#actionPagamento").keypress(function(e) {
  if(e.which == 13) {
    editarMeio();
  }
});
