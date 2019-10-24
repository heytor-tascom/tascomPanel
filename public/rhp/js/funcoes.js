$(document).ready(function () {

    carregaDadosTodos();
    

  });

function carregaDadosTodos(){

    jQuery.ajax({
      type: "POST",
      url: "construtores/urg_n_oracle.php",
      beforeSend: function(){
        $("#load").html('');  
      },
      data: {

      },
      success: function (data) {
        $("#retorno_ora").html(data);
        $("#load").html('');  
      }
    });

  }
/*
  function carregaDadosAvulsas(){

    jQuery.ajax({
      type: "POST",
      url: "construtores/farmacia_uti_avulsas.php",
      beforeSend: function(){
        $("#load").html('');  
      },
      data: {

      },
      success: function (data) {
        $("#retorno_ora").html(data);
        $("#load").html('');  
      }
    });
    
  }

  function carregaDadosParciais(){
    jQuery.ajax({
      type: "POST",
      url: "construtores/farmacia_uti_parciais.php",
      data: {

      },
      success: function (data) {
        $("#retorno_ora").html(data);
        
      }
    });
  }  

    var todos = setInterval(function (){ 
        carregaDadosTodos();
        console.log('Todas as solicitaçoes atualizadas');
     }, 5000 , );

    
     

     parciaisGlobal = setInterval(function(){ 
        carregaDadosParciais();
        console.log('Solicitaçoes Parciais Atualizadas');
     }, 5000);
     clearInterval(parciaisGlobal);

     todosGlobal = setInterval(function(){ 
        carregaDadosTodos();
        console.log('Todas as solicitaçoes atualizadas');
     }, 5000);
     clearInterval(todosGlobal);

     
     
     var avulsas = setInterval(function(){ 
        clearInterval(todosGlobal);
        clearInterval(todos);
        carregaDadosAvulsas();
        console.log('Todas as solicitaçoes avulsas');
        setTimeout(function (){ 
            carregaDadosTodos();
            console.log('Todas as solicitaçoes atualizadas');
         }, 5000 , );
     }, 9000);

     

    // parcial
    $('#opcaoParciais').click(function() {
      $('#opcaoTodos').removeClass('active');
      $('#opcaoParciais').addClass('active');

      clearInterval(todos);
      clearInterval(todosGlobal);
      carregaDadosParciais();
 
      parciaisGlobal = setInterval(function(){ 
        carregaDadosParciais();
        console.log('Solicitaçoes Parciais Atualizadas');
     }, 5000);
    });

    // todos
    $('#opcaoTodos').click(function() {
      $('#opcaoTodos').addClass('active');
      $('#opcaoParciais').removeClass('active');
     
      clearInterval(parciaisGlobal);

      carregaDadosTodos();

      todosGlobal = setInterval(function(){ 
        carregaDadosTodos();
        console.log('Todas as solicitaçoes atualizadas');
     }, 5000);

    });*/

    