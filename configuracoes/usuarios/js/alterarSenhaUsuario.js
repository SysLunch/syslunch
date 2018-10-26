function editarSenhaUsuario(){

    if(!$("#avisoSucesso").hasClass("displayNone")){
      $("#avisoSucesso").addClass("displayNone");
    }

    if(!$("#avisoErro").hasClass("displayNone")){
      $("#avisoErro").addClass("displayNone");
    }

    $("#avisoSucesso > div > p > span").html('');


    var id = $("#idUsuario").val();
    var selectSenhaUsuario = $("#selectSenhaUsuario").val();
    var senhaUsuario = $("#senhaUsuario").val();
    var confSenhaUsuario = $("#confirmacaoSenhaUsuario").val();
    // alert("senha="+senhaUsuario+"&confirmacaoSenha="+confSenhaUsuario+"&idUsuario="+id+"&novaSenha="+selectSenhaUsuario);

    if((senhaUsuario && confSenhaUsuario) || selectSenhaUsuario == 1){
        $.ajax({
          type: "post",
          url: "ajax/alterarSenhaUsuario.php",
          data: "senha="+senhaUsuario+"&confirmacaoSenha="+confSenhaUsuario+"&idUsuario="+id+"&novaSenha="+selectSenhaUsuario,
          dataType: "json",
          success: function(data){
            if(data.sucesso == 1){
              $("#avisoSucesso > div > p > b").html(data.nomeEmpresa);

              if(selectSenhaUsuario == 1){
                $("#avisoSucesso > div > p > span").html("<br>Uma senha foi gerada para o login do usuário. A senha é <b>"+data.senha+"</b>.");
              }else{
                $("#senhaUsuario").val('');
                $("#confirmacaoSenhaUsuario").val('');
              }

              $("#avisoSucesso").removeClass("displayNone");

              $("#senhaEmpresa").val('');
              $("#confSenhaEmpresa").val('');
            }else{
              $("#avisoErro > div > p").html(data.motivo);
              $("#avisoErro").removeClass("displayNone");
            }
          }
        });
    }else{
      $("#avisoErro > div > p").html("Um dos campos obrigatórios estão nulos. Por favor, verifique e tente novamente.");
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

$("#botaoAlterarSenhaUsuario").click(function(){
  editarSenhaUsuario();
});

$("#senhaUsuario").keypress(function(e) {
  if(e.which == 13) {
    editarSenhaUsuario();
  }
});

$("#confirmacaoSenhaUsuario").keypress(function(e) {
  if(e.which == 13) {
    editarSenhaUsuario();
  }
});
