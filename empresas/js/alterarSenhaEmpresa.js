function editarEmpresa(){

    if(!$("#avisoSucesso").hasClass("displayNone")){
      $("#avisoSucesso").addClass("displayNone");
    }

    if(!$("#avisoErro").hasClass("displayNone")){
      $("#avisoErro").addClass("displayNone");
    }

    $("#avisoSucesso > div > p > span").html('');


    var id = $("#idEmpresa").val();
    var selectSenhaEmpresa = $("#selectSenhaEmpresa").val();
    var senhaEmpresa = $("#senhaEmpresa").val();
    var confSenhaEmpresa = $("#confSenhaEmpresa").val();

    if((senhaEmpresa && confSenhaEmpresa) || selectSenhaEmpresa == 1){

        $.ajax({
          type: "post",
          url: "ajax/alterarSenhaEmpresa.php",
          data: "senha="+senhaEmpresa+"&confirmacaoSenha="+confSenhaEmpresa+"&idEmpresa="+id+"&novaSenha="+selectSenhaEmpresa,
          dataType: "json",
          success: function(data){
            if(data.sucesso == 1){
              $("#avisoSucesso > div > p > b").html(data.nomeEmpresa);

              if(selectSenhaEmpresa == 1){
                $("#avisoSucesso > div > p > span").html("<br>Uma senha foi gerada para o login da empresa. A senha é <b>"+data.senha+"</b>.");
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

$("#selectSenhaEmpresa").change(function(){
  if($("#selectSenhaEmpresa").val() == 2){
    if($(".senhaEmpresa").hasClass("displayNone")){
      $(".senhaEmpresa").removeClass("displayNone");
    }
  }else{
    if(!$(".senhaEmpresa").hasClass("displayNone")){
      $(".senhaEmpresa").addClass("displayNone");
    }
  }
});

$("#botaoAlterarSenhaEmpresa").click(function(){
  editarEmpresa();
});

$("#senhaEmpresa").keypress(function(e) {
  if(e.which == 13) {
    editarEmpresa();
  }
});

$("#confSenhaEmpresa").keypress(function(e) {
  if(e.which == 13) {
    editarEmpresa();
  }
});
