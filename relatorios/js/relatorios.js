function tudoNone(){
  if(!$("#avisoErro").hasClass("displayNone")){
    $("#avisoErro").addClass("displayNone");
  }
}

$("#botaoRelatorioAlmocosDia").click(function(){
  tudoNone();
  var data = $("#almocosDia .ano").val()+"-"+$("#almocosDia .mes").val()+"-"+$("#almocosDia .dia").val();
  var empresa = $("#almocosDia #empresas select").val();
  var filtro = $("#almocosDia #filtro select").val();

  var url = "relatorios/almocosPorDia.php?data="+data+"&idEmpresa="+empresa+"&filtro="+filtro;


  window.open(url, '_blank');
});


$("#botaoRelatorioAlmocosPeriodo").click(function(){
  tudoNone();
  var dataInicio = $("#almocosPeriodo #inicial .ano").val()+"-"+$("#almocosPeriodo #inicial .mes").val()+"-"+$("#almocosPeriodo #inicial .dia").val();
  var dataFinal = $("#almocosPeriodo #final .ano").val()+"-"+$("#almocosPeriodo #final .mes").val()+"-"+$("#almocosPeriodo #final .dia").val();
  var dataServidor = $("#dataServidor").val();
  var empresa = $("#almocosPeriodo #empresas select").val();
  var detalhado = $("#almocosPeriodo #relatorioDetalhado").val();
  var filtro = $("#almocosPeriodo #filtro select").val();

  var dInicio = new Date(dataInicio);
  var dFinal = new Date(dataFinal);
  var dServidor = new Date(dataServidor);

  var tInicio = parseInt(dInicio.getTime()/1000);
  var tFinal = parseInt(dFinal.getTime()/1000);
  var tServidor = parseInt(dServidor.getTime()/1000);

  if(dInicio.getTime() < dFinal.getTime()){
    if(tFinal <= tServidor){
      if((tFinal - tInicio) <= 90*24*60*60){
        var url = "relatorios/almocosPorPeriodo.php?dataInicial="+dataInicio+"&dataFinal="+dataFinal+"&idEmpresa="+empresa+"&detalhado="+detalhado+"&filtro="+filtro;
        window.open(url, '_blank');
      }else{
        $("#avisoErro > div > p").html("O período das datas NÃO PODE ser maior que 90 DIAS.");
        $("#avisoErro").removeClass("displayNone");
      }
    }else{
      $("#avisoErro > div > p").html("A data final não pode ser posterior ao dia de hoje.");
      $("#avisoErro").removeClass("displayNone");
    }
  }else{
    if(dInicio.getTime() == dFinal.getTime()){
      $("#avisoErro > div > p").html("O período desse relatório deve ser de NO MÍNIMO 2 DIAS.");
      $("#avisoErro").removeClass("displayNone");
    }else{
      $("#avisoErro > div > p").html("A data inicial DEVE ser anterior a data final.");
      $("#avisoErro").removeClass("displayNone");
    }
  }
});

$(document).ready(function(){
  setTimeout(function(){ location.reload(); }, 600000);
});
