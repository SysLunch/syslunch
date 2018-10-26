function tudoNone(){
  if(!$("#avisoErro").hasClass("displayNone")){
    $("#avisoErro").addClass("displayNone");
  }

  if(!$("#avisoSucesso").hasClass("displayNone")){
    $("#avisoSucesso").addClass("displayNone");
  }

  if(!$("#status #tudo i").hasClass("displayNone")){
    $("#status #tudo i").addClass("displayNone");
  }

  if(!$("#status").hasClass("displayNone")){
    $("#status").addClass("displayNone");
  }

  if(!$("#status #foto").hasClass("displayNone")){
    $("#status #foto").addClass("displayNone");
  }


  if($("#status #tudo i").hasClass("fi-x")){
    $("#status #tudo i").removeClass("fi-x");
  }
  if($("#status #tudo i").hasClass("fi-checkbox")){
    $("#status #tudo i").removeClass("fi-checkbox");
  }
  if($("#status #tudo i").hasClass("fi-alert")){
    $("#status #tudo i").removeClass("fi-alert");
  }


  if(!$("#status #tudo h1").hasClass("displayNone")){
    $("#status #tudo h1").addClass("displayNone");
  }

  if(!$("#status #tudo h2").hasClass("displayNone")){
    $("#status #tudo h2").addClass("displayNone");
  }

  if(!$("#status #tudo h3").hasClass("displayNone")){
    $("#status #tudo h3").addClass("displayNone");
  }

  if(!$("#status #tudo h4").hasClass("displayNone")){
    $("#status #tudo h4").addClass("displayNone");
  }

  if(!$("#status #tudo h5").hasClass("displayNone")){
    $("#status #tudo h5").addClass("displayNone");
  }

  if(!$("#status #tudo h6").hasClass("displayNone")){
    $("#status #tudo h6").addClass("displayNone");
  }


  if($("#status #icone").hasClass("medium-12")){
    $("#status #icone").removeClass("medium-12");
  }

  if($("#status #icone").hasClass("medium-48")){
    $("#status #icone").removeClass("medium-48");
  }

  if($("#status #icone").hasClass("medium-12")){
    $("#status #icone").removeClass("medium-12");
  }

  if($("#status #icone").hasClass("medium-offset-12")){
    $("#status #icone").removeClass("medium-offset-12");
  }

  if($("#status #foundation").hasClass("medium-12")){
    $("#status #foundation").removeClass("medium-12");
  }

  if($("#status #foundation").hasClass("medium-offset-12")){
    $("#status #foundation").removeClass("medium-offset-12");
  }


  $("#foto img").attr("src", "");
}

function noColor(){
  if($("#status #tudo i").hasClass("colorWhite")){
    $("#status #tudo i").removeClass("colorWhite");
  }
  if($("#status #tudo h1").hasClass("colorWhite")){
    $("#status #tudo h1").removeClass("colorWhite");
  }
  if($("#fundo").hasClass("bgGreen")){
    $("#fundo").removeClass("bgGreen");
  }
  if($("#fundo").hasClass("bgRed")){
    $("#fundo").removeClass("bgRed");
  }
  if($("#fundo").hasClass("bgOrange")){
    $("#fundo").removeClass("bgOrange");
  }
  if($("#fundo").hasClass("bgBlue")){
    $("#fundo").removeClass("bgBlue");
  }
  if($("#fundo").hasClass("bgPurple")){
    $("#fundo").removeClass("bgPurple");
  }
}

vartudoNone = 1;
timeTudoNone = 0;
function cronometroTudoNone(timeTo){
  timeTo = timeTo || 20;
  timeTudoNone = parseInt(Date.now()/1000)+timeTo;
}

function verTudoNone(now){
  if(timeTudoNone == parseInt(Date.now()/1000) || now == 1){
    tudoNone();
    displayRelogio(1);
    noColor();
    vartudoNone = 0;
  }
}

function showCarregando(){

    if($("#carregando").hasClass("displayNone")){
      $("#carregando").removeClass("displayNone");
    }
    if(!$("#tudo").hasClass("displayNone")){
      $("#tudo").addClass("displayNone");
    }
    if(!$("#barras").hasClass("displayNone")){
      $("#barras").addClass("displayNone");
    }

}

function hideCarregando(){

    if(!$("#carregando").hasClass("displayNone")){
      $("#carregando").addClass("displayNone");
    }
    if($("#tudo").hasClass("displayNone")){
      $("#tudo").removeClass("displayNone");
    }
    if($("#barras").hasClass("displayNone")){
      $("#barras").removeClass("displayNone");
    }

}
