// JavaScript Document

function enviarForm(sIdForm) {  
  document.getElementById(sIdForm).submit();
}

function abreFechaDiv(sId){
  oElemento = $('#'+sId);  
  oElemento.fadeToggle(500, "linear");
  return true;
}

function removerPadrao(iId) {
  $("#CMPid").val(iId);
  sNomeRegistro = $("#nome_reg_"+iId).html();
  confirmForm('Você confirma a remoção de: '+sNomeRegistro, 'FRMremover');
}

function confirmForm(sMsg, iIdForm) {
  oForm = document.getElementById(iIdForm);
  bRet = confirm(sMsg);
  if(bRet) {
    oForm.submit();
  } else {
    return false;
  }
  return true;
}
function limparFRM(oObj) {
  oObj = document.getElementById(sId);
  oObj.reset();
}
function validaFormSenhas(){

d = document.formSenhas;

//valida nome
	if (d.nome.value == ""){
	    alert("O campo " + d.nome.name + " deve ser preenchido!");
    	d.nome.focus();
	    return false;
    }

//valida login
	if (d.login.value == ""){
		alert("O campo " + d.login.name + " deve ser preenchido!");
		d.login.focus();
		return false;
	}	

//só vai testar aqui a senha caso esteja sendo adicionada uma nova - formulario add_usuario
	if(d.add_usuario.value == "1"){
	//valida senha
		if (d.senha.value == ""){
			alert("O campo " + d.senha.name + " deve ser preenchido!");
			d.senha.focus();
			return false;
		}
		
	//testa se a senha é igual ao campo confirma senha	
		if (d.senha.value != d.confirma_senha.value){
			alert("O campo senha e confirma senha devem ser preenchidos com o mesmo valor");
			d.senha.focus();
			return false;
		}	
	}
	
//testa se foi digitado um email, caso positivo, testa se ele é válido	
	if(d.email.value != ""){
		return true;
	}else{
		alert ("Digite um email válido");
		d.email.focus();
		return false;
	}	

}

//valida individualmente a senha
function valSenha(){
	d = document.trocaSenha;
	if (d.senha.value == ""){
		alert("O campo " + d.senha.name + " deve ser preenchido!");
		d.senha.focus();
		return false;
	}

	if (d.senha.value != d.confirma_senha.value){
		alert("O campo senha e confirma senha devem ser preenchidos com o mesmo valor");
		d.senha.focus();
		return false;
	}
}

// Troca o estado visivel em uma classe ou ID CSS
function trocaEstVisivel(identificador, modo){
	if (modo == 0){
		document.getElementById(identificador).style.visibility="hidden";	
	}	
	if (modo == 1){
		document.getElementById(identificador).style.visibility="visible";	
		document.trocaImagem.img_capa.focus();  		
	}
}

function teste(){
		alert('oi');
	}

function sobreNoticias(id){
	url = "ajax/ajax_coment_noticias.php?id="+id;
	objID = "sobre";		
	fazRequisicaoAdmin(url,objID);
}	

function sobreArtigos(id){
	url = "ajax/ajax_coment_artigos.php?id="+id;
	objID = "sobre";		
	fazRequisicaoAdmin(url,objID);
}

function menu(local){
	url = local+".php";
	objID = "conteudo";		
	fazRequisicaoAdmin(url,objID);		
}

		
function alternaVisualizacao(sId){
	sElemento = document.getElementById(sId);

	if(sElemento.disabled == false) {
		sElemento.disabled=true;
	} else {
		sElemento.disabled=false;
	}	
}

function alternaDisplay(){
  bObj1 = document.getElementById('CMPgut').checked;
  bObj2 = document.getElementById('CMPbasico').checked;

  if(bObj1 == true) {
    document.getElementById('gut').style.display = 'block';
    document.getElementById('basico').style.display = 'none';
  }

  if(bObj2 == true) {
    document.getElementById('gut').style.display = 'none';
    document.getElementById('basico').style.display = 'block';
  }
}

function ui_alert() {
//  sBox = '<div id="dialog-confirm">Tipo de cadastro</div>';
//  $('#corpo').append(sBox);
//
//  $( "#dialog-confirm" ).dialog({
//    resizable: false,
//    title: 'Tipo de pessoa',
//    modal: true,
//    buttons: {
//      "Cadastro de Pessoa Física": function() {
//        $( this ).dialog( "close" );
//      },
//      "Cadastro de Pessoa Jurídica": function() {
//        $( this ).dialog( "close" );
//      }
//    }
//  });

}

  function buscarCep() {
    
    iCep = $('#CMPcep').val();
    if(iCep.length != 9) {
      alert('Cep inválido!');
      return false;
    }

    $.ajax({
      type: "POST",
      data: "sAcao=buscarDadosEndereco&iCep="+iCep,
      url: "../modulosPHP/tratarAjax.php",
      context: document.body,
      async: false,

      beforeSend: function() {
        $('#ret_endereco_entrega').removeClass('invisivel');
        $('#ret_endereco_entrega').addClass('visivel');
        $("#ret_endereco_entrega").html('<img src="../comum/imagens/icones/loading19.gif" alt="" />');
      },

      success: function(html){
        $("#ret_endereco_entrega").html(html);
      }
    });
  }
  
  function moeda(z){  
    v = z.value; 
    if (v.length > 14) {
      z.value = v.substr(0, 14);
      return;
    }
    v=v.replace(/\D/g,"")  //permite digitar apenas números
    v=v.replace(/[0-9]{12}/,"inválido")   //limita pra máximo 999.999.999,99
    v=v.replace(/(\d{1})(\d{8})$/,"$1.$2")  //coloca ponto antes dos últimos 8 digitos
    v=v.replace(/(\d{1})(\d{5})$/,"$1.$2")  //coloca ponto antes dos últimos 5 digitos
    v=v.replace(/(\d{1})(\d{1,2})$/,"$1,$2")        //coloca virgula antes dos últimos 2 digitos
    z.value = v;
  }

  function porcentagem(z){
    v = z.value; 
    if (v.length > 5) {
      z.value = v.substr(0, 5);
      return;
    }
    v=v.replace(/\D/g,"")  //permite digitar apenas números
    v=v.replace(/[0-9]{12}/,"inválido")   //limita pra máximo 999.999.999,99
    //v=v.replace(/(\d{1})(\d{8})$/,"$1.$2")  //coloca ponto antes dos últimos 8 digitos
    //v=v.replace(/(\d{1})(\d{5})$/,"$1.$2")  //coloca ponto antes dos últimos 5 digitos
    v=v.replace(/(\d{1})(\d{1,2})$/,"$1.$2")        //coloca virgula antes dos últimos 2 digitos
    z.value = v;
  }

  function inteiro(z){  
    v = z.value; 
    if (v.length > 5) {
      z.value = v.substr(0, 5);
      return;
    }
    v=v.replace(/\D/g,"")  //permite digitar apenas números
    v=v.replace(/[0-9]{12}/,"inválido")   //limita pra máximo 999.999.999,99
    z.value = v;
  }
  
      
      
      
    function dump(arr,level) {
      var dumped_text = "";
      if(!level) level = 0;

      //The padding given at the beginning of the line.
      var level_padding = "";
      for(var j=0;j<level+1;j++) level_padding += "    ";

      if(typeof(arr) == 'object') { //Array/Hashes/Objects 
              for(var item in arr) {
                      var value = arr[item];

                      if(typeof(value) == 'object') { //If it is an array,
                              dumped_text += level_padding + "'" + item + "' ...\n";
                              dumped_text += dump(value,level+1);
                      } else {
                              dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
                      }
              }
      } else { //Stings/Chars/Numbers etc.
              dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
      }
      return dumped_text;
    }

    function removerViaCheckBox(sMsgConfirm, sAction, sAcao) {
      
      if ($('.checkRemover').filter(':checked').length == 0) {
        alert('Selecione um registro!');
        return;
      }
      
      if (confirm(sMsgConfirm)) {
        iIdRef = $('#CMPid').val();
        // Form dinamico
        $('body').append('<form name="FRMdinamico" id="FRMdinamico" action="'+sAction+'" method="post">');
        $('#FRMdinamico').append('<input type="hidden" name="sAcao" value="'+sAcao+'" />');
        
        $('#FRMdinamico').append('<input type="hidden" name="CMPid" value="'+iIdRef+'" />');

        $('.checkRemover').each(function(iSeq, oElemento){
          if ($(oElemento).attr('checked')) {
            iId = $(oElemento).val();
            $('#FRMdinamico').append('<input type="hidden" name="CMPaId[]" value="'+iId+'" />');
          }
        });
        $('#FRMdinamico').submit();
      }
    }
    
    function ativaAba(sIdConteinerContAbas, sIdAbaAtivar) {
      var oElemento = $('#'+sIdConteinerContAbas+' > div');
      oElemento.each(function(iIndex){

        if ($(this).attr('id') == sIdAbaAtivar ){
          $('#'+sIdAbaAtivar).removeClass('abas_esconder');
          $('#'+sIdAbaAtivar).addClass('abas_mostrar');
          $('#bt_aba_'+sIdConteinerContAbas+'_'+iIndex).addClass('bt_aba_selecionada');
        } else {
          $(this).addClass('abas_esconder');
          $(this).removeClass('abas_mostrar');
          $('#bt_aba_'+sIdConteinerContAbas+'_'+iIndex).removeClass('bt_aba_selecionada');
        }
      });
    }

    function difData (sData1, sData2){
      date1 = sData1.split("/");
      date2 = sData2.split("/");
      var sDate = new Date(date1[1]+"/"+date1[0]+"/"+date1[2]);
      var eDate = new Date(date2[1]+"/"+date2[0]+"/"+date2[2]);
      var daysApart = Math.abs(Math.round((sDate-eDate)/86400000));
      return daysApart;
    }

    function maiorData(sData1, sData2){
      date1 = sData1.split("/");
      date2 = sData2.split("/");
      var sDate = new Date(date1[1]+"/"+date1[0]+"/"+date1[2]);
      var eDate = new Date(date2[1]+"/"+date2[0]+"/"+date2[2]);
      var daysApart = (Math.round((sDate-eDate)/86400000));

      return daysApart;
    }

  // Função para texto piscar
  // $('.blink').blink();
  $.fn.blink = function(options) {
    var defaults = { delay:500 };
    var options = $.extend(defaults, options);

    return this.each(function() {
      var obj = $(this);
      setInterval(function() {
          if($(obj).css("visibility") == "visible") {
            $(obj).css('visibility','hidden');
          } else {
            $(obj).css('visibility','visible');
          }
      }, options.delay);
    });
  }
    
    