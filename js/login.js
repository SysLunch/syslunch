function logar(){
  if(!$("#alertaLogin").hasClass("displayNone")){
    $("#alertaLogin").addClass("displayNone");
  }
  var login = $("#loginLogin").val();
  var senha = $("#senhaLogin").val();
  var url = $("#urlSiteP").val();
  if(login && senha){
    $.ajax({
      type: "post",
      url: url+"ajax/login/logar.php",
      data: "login="+login+"&senha="+senha,
      dataType: "json",
      success: function(data){
        if(data.sucesso == 1){
          location.reload();
        }else{
          $("#alertaLogin p").html(data.descricao);
          if($("#alertaLogin").hasClass("displayNone")){
              $("#alertaLogin").removeClass("displayNone");
          }
        }
      }
    });
  }else{
    // Erro: algum campo nulo
  }
}

$("#loginButton").click(function(){
  logar();
});

$("#loginLogin").keypress(function(e) {
  if(e.which == 13) {
    logar();
  }
});
$("#senhaLogin").keypress(function(e) {
  if(e.which == 13) {
    logar();
  }
});

function sair(conf, url){
  if(conf == 1){
    location.href=url+"logout.php";
  }else{
    location.reload();
  }
}
