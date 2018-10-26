function displayRelogio(modo){
  if(modo == 0){
    if(!$("#relogio").hasClass("displayNone")){
      $("#relogio").addClass("displayNone");
    }
  }else{
    if($("#relogio").hasClass("displayNone")){
      $("#relogio").removeClass("displayNone");
    }
  }
}

function ping(){
    ajax = "ajax/ping.php?idCRT="+ $("#idCRT").val();
    $.getJSON(ajax, function(data){
      if(data.erro){
        console.log("Erro: "+data.erro);
      }else{
        console.log("Sucesso!");
      }
    });
}

function moveRelogio(){
    momentoAtual = new Date();
    dia = momentoAtual.getDate();
    if(dia < 10){
      dia = "0"+dia;
    }
    mes = momentoAtual.getMonth()+1;
    if(mes < 10){
      mes = "0"+mes;
    }
    ano = momentoAtual.getFullYear();
    hora = momentoAtual.getHours();
    if(hora < 10){
      hora = "0"+hora;
    }
    minuto = momentoAtual.getMinutes();
    if(minuto < 10){
      minuto = "0"+minuto;
    }
    segundo = momentoAtual.getSeconds();
    if(segundo < 10){
      segundo = "0"+segundo;
    }

    var data = dia+"/"+mes+"/"+ano
    var horas = hora+":"+minuto+":"+segundo;

    $("#relogio h1").html(data);
    $("#relogio h2").html(horas);

    if(segundo == 0){
      ping();
    }
    setTimeout(function(){moveRelogio(); verTudoNone();},1000);
}
