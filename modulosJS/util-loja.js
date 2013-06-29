  
  
  function calcularFrete() {
    var sUrlBase= $('#CMPsUrlBase').val();
    var iCep1   = $('#CMPcalcularFrete-01').val();
    var iCep2   = $('#CMPcalcularFrete-02').val();
    var iIdProd = $('#CMPiIdProd').val();
    var iQnt    = 1;


    if(iCep1.length != 5) {
      return false;
    }
    if(iCep2.length != 3) {
      return false;
    }

    $.ajax({
      type: "POST",
      data: "sAcao=calcularFrete&iCep1="+iCep1+"&iCep2="+iCep2+"&iIdProd="+iIdProd+"&iQnt="+iQnt,
      url: sUrlBase+"/modulosPHP/tratarAjax.php",
      //dataType: 'json',
      context: document.body,
      async: false,

      success: function(html){
        $('#retCalculoFrete').html(html);
      }
    });
  }
  function buscarCepCliente(sCep, sTipoCad) {
    var sUrlBase= $('#CMPsUrlBase').val();

    if(sCep.length != 9) {
      alert('Cep inválido!');
      return false;
    }
    
    //Tipo de cadastro e sigla para os campos
    if (sTipoCad != '') {
      sTipoCad = '-'+sTipoCad;
    }

    $.ajax({
      type: "POST",
      data: "sAcao=buscarDadosEndereco&iCep="+sCep,
      url: sUrlBase+"/modulosPHP/tratarAjax.php",
      dataType: 'json',
      context: document.body,
      async: false,

      beforeSend: function() {
        //$('#ret_endereco_entrega').removeClass('invisivel');
        //$('#ret_endereco_entrega').addClass('visivel');
        //$("#ret_endereco_entrega").html('<img src="../comum/imagens/icones/loading19.gif" alt="" />');
      },

      success: function(dados){
        if (dados.bSucesso) {
          $("#CMPclientes-enderecos-logradouro"+sTipoCad ).val(dados.sLogradouro);
          $("#CMPclientes-enderecos-tp-logradouro"+sTipoCad ).val(dados.sTipoLogradouro);
          //$("#CMPclientes-enderecos-logradouro"+sTipoCad ).attr('readonly', true);

          $("#CMPclientes-enderecos-bairro"+sTipoCad ).val(dados.sBairro);
          //$("#CMPclientes-enderecos-bairro"+sTipoCad ).attr('readonly', true);

          $("#CMPclientes-enderecos-cid"+sTipoCad ).val(dados.sCidade);
          //$("#CMPclientes-enderecos-cid"+sTipoCad ).attr('readonly', true);

          $("#CMPclientes-enderecos-uf"+sTipoCad ).val(dados.sSgUf);
          //$("#CMPclientes-enderecos-uf"+sTipoCad ).attr('readonly', true);
        } else {
//          $("#CMPclientes-enderecos-logradouro"+sTipoCad ).attr('readonly', false);
//          $("#CMPclientes-enderecos-bairro"+sTipoCad ).attr('readonly', false);
//          $("#CMPclientes-enderecos-cid"+sTipoCad ).attr('readonly', false);

          alert('Cep não encontrado na base de dados dos correios');
        }
      }
    });
    return true;
  }
  
  
  
  function calcularPrecoProduto() {
    var fPrecoCusto = $('#CMPprecoCusto').val();
    var fTaxas      = $('#CMPtaxas').val();
    var fPrecoAdc   = $('#CMPprecoAdicional').val();
    var iMargem     = $('#CMPmargem').val();


    $.ajax({
      type: "POST",
      data: "sAcao=calcularPrecoProduto&fPrecoCusto="+fPrecoCusto+"&fTaxas="+fTaxas+"&fPrecoAdc="+fPrecoAdc+"&iMargem="+iMargem,
      url: "../modulosPHP/tratarAjax.php",
      dataType: 'json',
      context: document.body,
      async: false,

      success: function(data){
        $('#CMPvalorFinal').val(data.fTotal);
      }

    });

  }
  
  function removerItemCarrinhoSessao(iIdProd) {
    var sUrlBase= $('#CMPsUrlBase').val();
    $.ajax({
      type: "POST",
      data: "sAcao=removerItemCarrinhoSessao&iIdProd="+iIdProd,
      url: sUrlBase+"/modulosPHP/tratarAjax.php",
      dataType: 'json',
      context: document.body,
      async: false,
      error: function() {
        //alert('erro');
      },
      success: function(data){
        if (data.bCarrinhoVazio) {
          $('#lista-itens-carrinho').html(data.sHtmlMsgSemCarrinho);
        } else {
          $('#conteiner-produto-'+iIdProd).remove();
        }

         //Total de todos os itens
        $('.vl_produtos').html('R$ '+data.fVlProdutos);
        $('.vl_descontos').html('R$ '+data.fVlDescontos);
        $('.vl_frete').html('R$ '+data.fVlFrete);
        $('.vl_total').html('R$ '+data.fTotal);

        if (data.iQntProdRestantes > 0) {
          $('.qnt-prod-pestantes').html(data.iQntProdRestantes+ ' itens');
        } else {
          $('.qnt-prod-pestantes').html('Vazio');
        }
      }
    });
  }

  function alterarQuantidadeItensCarrinho(iIdProd, sTpAcao) {

    var sUrlBase= $('#CMPsUrlBase').val();
    $.ajax({
      type: "POST",
      data: "sAcao=alterarQuantidadeItensCarrinho&iIdProd="+iIdProd+"&sTpAcao="+sTpAcao,
      url: sUrlBase+"/modulosPHP/tratarAjax.php",
      dataType: 'json',
      context: document.body,
      async: false,
      
      error: function() {
        //alert('erro '+sUrlBase);
      },

      success: function(data){

        $('#CMPiQnt-'+iIdProd).val(data.iQnt);
        $('#HTMLfVlTotal-'+iIdProd).html('R$ '+data.fVlTotal);
        
        if (data.sAvisoDescontoQnt != undefined) {
            $('#sAvisoDescontoQnt-'+iIdProd).html(data.sAvisoDescontoQnt);
        }
//          tags = $.parseJSON(data.sAvisoDescontoQnt);
//          alert(tags[0][220]['sAvisoDescontoQnt']);
        
         //Total de todos os itens
        $('.vl_produtos').html('R$ '+data.fVlProdutos);
        $('.vl_descontos').html('R$ '+data.fVlDescontos);
        $('.vl_frete').html('R$ '+data.fVlFrete);
        $('.vl_total').html('R$ '+data.fTotal);
      }
    });

  }