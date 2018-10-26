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
}

function noColor(){
  if($("#status #tudo i").hasClass("colorGreen")){
    $("#status #tudo i").removeClass("colorGreen");
  }
  if($("#status #tudo i").hasClass("colorRed")){
    $("#status #tudo i").removeClass("colorRed");
  }
  if($("#status #tudo i").hasClass("colorOrange")){
    $("#status #tudo i").removeClass("colorOrange");
  }
  if($("#status #tudo h1").hasClass("colorGreen")){
    $("#status #tudo h1").removeClass("colorGreen");
  }
  if($("#status #tudo h1").hasClass("colorRed")){
    $("#status #tudo h1").removeClass("colorRed");
  }
  if($("#status #tudo h1").hasClass("colorOrange")){
    $("#status #tudo h1").removeClass("colorOrange");
  }
}

vartudoNone = 1;
timeTudoNone = 0;
function cronometroTudoNone(timeTo){
  timeTo = timeTo || 20;
  timeTudoNone = parseInt(Date.now()/1000)+timeTo;
}

function verTudoNone(){
  if(timeTudoNone == parseInt(Date.now()/1000)){
    tudoNone();
    displayRelogio(1);
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
