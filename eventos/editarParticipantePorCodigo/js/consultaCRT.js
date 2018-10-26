function consultar(){

  vartudoNone = 0;
  tudoNone();
  showCarregando();
  noColor();

  var codigo = $("#codigoBarras").val();
  var ajax = "ajax/consulta.php?codigoBarras="+codigo;

  //alert(ajax);

  $.getJSON(ajax, function(data){


      if(data.sucesso == 1){
        $("#avisoSucesso > div > p").html(data.mensagem);
        $("#avisoSucesso").removeClass("displayNone");
        setTimeout( function(){
            window.open('../editarParticipante.php?barras=1&id='+data.id, '_blank');
        }, 2000);
      }else{
          $("#avisoErro > div > p").html(data.motivo);
          $("#avisoErro").removeClass("displayNone");
      }
    hideCarregando();
  });
  $("#codigoBarras").val('');
  $("#codigoBarras").focus('');
}
