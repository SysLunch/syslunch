function consultar(semCodigo){

  audioAviso = document.getElementById('audioAviso');
  audioBomAlmoco = document.getElementById('avisoBomAlmoco');
  audioErro = document.getElementById('audioErro');
  audioAniversario = document.getElementById('audioAniversario');

  vartudoNone = 0;
  displayRelogio(0);
  tudoNone();
  showCarregando();
  noColor();

  var idCRT = $("#idCRT").val();
  var codigo = $("#codigoBarras").val();
  if(semCodigo == true){
    var ajax = "ajax/consulta.php?remoteLogin=1&idCRT="+idCRT;
  }else{
    var ajax = "ajax/consulta.php?codigoBarras="+codigo+"&idCRT="+idCRT;
  }

  //alert(ajax);

  $.getJSON(ajax, function(data){

    if(data.tipo == 1){
      // Icones
      if(data.sucesso == 1){
        //alert(1);
        if(data.isTicket == 1){
          $("#status #tudo i").addClass("fi-checkbox");
          $("#status #tudo i").addClass("colorWhite");

          $("#status #tudo h1").html("BOM ALMOÇO!");
          $("#status #tudo h1").addClass("colorWhite");
          $("#fundo").addClass("bgGreen");


          $("#status #tudo h2").html(data.texto);

          // if(data.isGratuito == 1){
          //   $("#status #tudo h2").html("TICKET GRATUITO");
          // }else{
          //   $("#status #tudo h2").html("TICKET");
          // }


          $("#status #tudo i").removeClass("displayNone");
          $("#status #tudo h1").removeClass("displayNone");
          $("#status #tudo h2").removeClass("displayNone");
          $("#status").removeClass("displayNone");

          audioBomAlmoco.play();

          vartudoNone = 1;
          cronometroTudoNone();
        }else{
          $("#status #tudo i").addClass("fi-checkbox");
          $("#status #tudo i").addClass("colorWhite");

          $("#status #tudo h1").html("BOM ALMOÇO!");
          $("#status #tudo h1").addClass("colorWhite");
          $("#fundo").addClass("bgGreen");

          // if(data.isGratuito){
          //   $("#status #tudo h2").html("FUNCIONÁRIO GRATUITO");
          // }else{
          //   $("#status #tudo h2").html("FUNCIONÁRIO");
          //   $("#status #tudo h4").html("SALDO RESTANTE: "+data.saldo);
          //   $("#status #tudo h4").removeClass("displayNone");
          // }


          $("#status #tudo h2").html(data.texto);

          if(data.isGratuito == 0){
            $("#status #tudo h4").html("SALDO RESTANTE: "+data.saldo);
            $("#status #tudo h4").removeClass("displayNone");
          }

          $("#status #tudo h3").html(data.nomeFuncionario);

          if(data.tipoGrupo == 1){
            $("#status #foto").removeClass("displayNone");

            if(!$("#status #foto").hasClass("medium-12")){
              $("#status #foto").toggleClass("medium-12");
            }

            if(!$("#status #foto").hasClass("medium-offset-12")){
              $("#status #foto").toggleClass("medium-offset-12");
            }

            if(!$("#status #icone").hasClass("medium-12")){
              $("#status #icone").toggleClass("medium-12");
            }
            // $("#status #foto").toggleClass("medium-offset-12");
            //
            // $("#status #icone").toggleClass("medium-12");
            // $("#status #icone").toggleClass("medium-offset-12");
          }else{

            if(!$("#status #foto").hasClass("medium-48")){
              $("#status #foto").toggleClass("medium-48");
            }
          }

          $("#foto img").attr("src", data.foto);




          $("#status #tudo i").removeClass("displayNone");
          $("#status #tudo h1").removeClass("displayNone");
          $("#status #tudo h2").removeClass("displayNone");
          $("#status #tudo h3").removeClass("displayNone");
          $("#status").removeClass("displayNone");

          audioBomAlmoco.play();
          if(data.aniversario == 1){
            setTimeout(function(){ audioAniversario.play(); }, 1500);
          }

          vartudoNone = 1;
          cronometroTudoNone();
        }
      }else{
        $("#status #tudo i").addClass("fi-x");
        $("#status #tudo i").addClass("colorWhite");

        $("#status #tudo h1").html("Erro!");
        $("#status #tudo h1").addClass("colorWhite");
        $("#fundo").addClass("bgRed");

        $("#status #tudo h5").html(data.motivo);


        $("#status #tudo i").removeClass("displayNone");
        $("#status #tudo h1").removeClass("displayNone");
        $("#status #tudo h5").removeClass("displayNone");
        $("#status").removeClass("displayNone");

        audioErro.play();

        vartudoNone = 1;
        cronometroTudoNone();
      }

      hideCarregando();
    }else{
      if(data.tipo == 2){
        $("#status #tudo i").addClass("fi-alert");
        $("#status #tudo i").addClass("colorWhite");

        $("#status #tudo h1").html("ALERTA!");
        $("#status #tudo h1").addClass("colorWhite");
        $("#fundo").addClass("bgOrange");

        $("#status #tudo h6").html(data.motivo);

        $("#status #tudo i").removeClass("displayNone");
        $("#status #tudo h1").removeClass("displayNone");
        $("#status #tudo h6").removeClass("displayNone");
        $("#status").removeClass("displayNone");

        audioAviso.play();

        vartudoNone = 1;
        cronometroTudoNone();

        hideCarregando();
      }else{

        if(data.isCRT == true){
          $("#status").removeClass("displayNone");
          $("#idCRT").val(data.idCRT);
          $("#nomeCRT a").html("Local: "+data.idCRT+" - "+data.nomeCRT);

          $("#carregando h3").html("EXECUTANDO TESTES...");

          $("#carregando h6").html("Audio Aviso...");
          $("#carregando h6").removeClass("displayNone");
          audioAviso.play();

          setTimeout(function(){
            $("#carregando h6").html("Audio Erro...");
            audioErro.play();
          }, 1200);

          setTimeout(function(){
          $("#carregando h6").html("Audio Bom Almoço...");
            audioBomAlmoco.play();
            audioAniversario.volume = 0;
            audioAniversario.play();
          }, 2000);

          setTimeout(function(){
            $("#carregando h6").html("Finalizando...");
            $("#carregando h3").html("CARREGANDO...");
          }, 3500);

          setTimeout(function(){
            audioAniversario.volume = 1;
            $("#carregando h6").addClass("displayNone");
            $("#carregando h6").html("");
            $("#avisoSucesso > div > p").html(data.mensagem);
            $("#avisoSucesso").removeClass("displayNone");

            $("#tituloCodigoBarras").html("Por favor, passe o código de barras no leitor");

            moveRelogio();
            cronometroTudoNone(10);
            ping();
            tudoNone();

            hideCarregando();
          }, 4200);

        }else{
          if(data.sucesso == 1){
            $("#avisoSucesso > div > p").html(data.mensagem);
            $("#avisoSucesso").removeClass("displayNone");
          }else{
              $("#avisoErro > div > p").html(data.motivo);
              $("#avisoErro").removeClass("displayNone");

              audioErro.play();

              hideCarregando();
          }
        }
      }
    }
  });
  $("#codigoBarras").val('');
  $("#codigoBarras").focus('');
}
