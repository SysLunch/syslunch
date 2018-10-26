$(document).ready(function(){
  $('#nascimentoUsuario').mask('00/00/0000');
});
function novoUsuario(){

    if(!$("#avisoSucesso").hasClass("displayNone")){
      $("#avisoSucesso").addClass("displayNone");
    }

    if(!$("#avisoErro").hasClass("displayNone")){
      $("#avisoErro").addClass("displayNone");
    }

    $("#avisoSucesso > div > p > span").html('');


    var nomeUsuario = $("#nomeUsuario").val();
    var loginUsuario = $("#loginUsuario").val();
    var senhaUsuario = $("#senhaUsuario").val();
    var confirmacaoSenhaUsuario = $("#confirmacaoSenhaUsuario").val();
    var nascimentoUsuario = $("#nascimentoUsuario").val();
    var permissaoUsuario = $("#permissaoUsuario").val();
    var selectSenhaUsuario = $("#selectSenhaUsuario").val();

    var url = $("#urlSite").val();

    if(selectSenhaUsuario == 1){
      senhaUsuario = '';
      confirmacaoSenhaUsuario = '';
    }

    if(nomeUsuario && loginUsuario && nascimentoUsuario && permissaoUsuario && ((senhaUsuario && confirmacaoSenhaUsuario) || selectSenhaUsuario == 1)){
      $.ajax({
        type: "post",
        url: "ajax/novoUsuario.php",
        data: "nomeUsuario="+nomeUsuario+"&loginUsuario="+loginUsuario+"&senhaUsuario="+senhaUsuario+"&confirmacaoSenhaUsuario="+confirmacaoSenhaUsuario+"&nascimentoUsuario="+nascimentoUsuario+"&permissaoUsuario="+permissaoUsuario,
        dataType: "json",
        success: function(data){
          if(data.sucesso == 1){
            $("#avisoSucesso > div > p > b").html(data.nomeUsuario);

            if(selectSenhaUsuario == 1){
              $("#avisoSucesso > div > p > span").html("<br>Uma senha foi gerada para o login do usuário. A senha é <b>"+data.senhaUsuario+"</b>.");
            }


            $("#avisoSucesso").removeClass("displayNone");

            $("#nomeUsuario").val('');
            $("#loginUsuario").val('');
            $("#senhaUsuario").val('');
            $("#confirmacaoSenhaUsuario").val('');
            $("#selectSenhaUsuario").val(1);
            $("#nascimentoUsuario").val('');
            $("#permissaoUsuario").val('');
            $("#selectSenhaUsuario").change();

          }else{
            $("#avisoErro > div > p").html(data.motivo);
            $("#avisoErro").removeClass("displayNone");
          }
        }
      });
    }else{
      $("#avisoErro > div > p").html("Algum dos campos está nulo. Por favor, verifique e tente novamente."+nomeUsuario+" "+loginUsuario+" "+nascimentoUsuario+" "+permissaoUsuario+" "+senhaUsuario+" "+confirmacaoSenhaUsuario+" "+selectSenhaUsuario);
      $("#avisoErro").removeClass("displayNone");
    }
}

$("#selectSenhaUsuario").change(function(){
  if($("#selectSenhaUsuario").val() == 2){
    if($(".senhaUsuario").hasClass("displayNone")){
      $(".senhaUsuario").removeClass("displayNone");
    }
  }else{
    if(!$(".senhaUsuario").hasClass("displayNone")){
      $(".senhaUsuario").addClass("displayNone");
    }
  }
});

$("#botaoNovoUsuario").click(function(){
  novoUsuario();
});

$("#nomeUsuario").keypress(function(e) {
  if(e.which == 13) {
    novoUsuario();
  }
});

$("#loginUsuario").keypress(function(e) {
  if(e.which == 13) {
    novoUsuario();
  }
});

$("#senhaUsuario").keypress(function(e) {
  if(e.which == 13) {
    novoUsuario();
  }
});

$("#confirmacaoSenhaUsuario").keypress(function(e) {
  if(e.which == 13) {
    novoUsuario();
  }
});

$("#nascimentoUsuario").keypress(function(e) {
  if(e.which == 13) {
    novoUsuario();
  }
});
