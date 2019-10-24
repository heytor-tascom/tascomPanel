$(document).ready(function () {

    carregaDadosTodos();
    
  });

function carregaDadosTodos(){
  $("#retorno_ora").html('<tr><td colspan="13"><button class="btn btn-primary form-control text-center" type="button" disabled>'
  +'<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>'
  +' Carregando...'
  +'</button></td></tr>'); 

  var query = location.search.slice(1);
  var partes = query.split('&');
  var data = {};
  partes.forEach(function (parte) {
      var chaveValor = parte.split('=');
      var chave = chaveValor[0];
      var valor = chaveValor[1];
      data[chave] = valor;
  });

    jQuery.ajax({
      type: "POST",
      url: "construtores/oncologia_oracle.php",
      beforeSend: function(){
        $("#load").html('');  
      },
      data: {
        setores : data.setores
      },
      success: function (data) {
        $("#retorno_ora").html(data);
        $("#load").html('');  
        
      }
    });

  };


  

