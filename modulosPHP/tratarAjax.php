<?php
  session_start();
  header("Content-Type: text/html; charset=ISO-8859-1",true);
  include 'config.php';
  include 'conecta.php';
  include_once 'class.wTools.php';
  include_once 'class.pimentas.php';
  include_once 'class.carrinho.php';



  $oSite = new pimentas();

  if(isset($_POST['sAcao'])) {
    

    switch ($_POST['sAcao']) {


      case 'calcularPrecoProduto':
    
        $fTotal = 0;

        $fPrecoCusto = $_POST['fPrecoCusto'];
        $fTaxas      = $_POST['fTaxas'];
        $fPrecoAdc   = $_POST['fPrecoAdc'];
        $iMargem     = $_POST['iMargem'];
        
        $fPrecoCusto = str_replace('.', '', $fPrecoCusto);
        $fPrecoCusto = str_replace(',', '.', $fPrecoCusto);

        $fTaxas      = str_replace('.', '', $fTaxas);
        $fTaxas      = str_replace(',', '.', $fTaxas);
        
        $fPrecoAdc   = str_replace('.', '', $fPrecoAdc);
        $fPrecoAdc   = str_replace(',', '.', $fPrecoAdc);

        $fTotal += $fPrecoCusto + $fTaxas + $fPrecoAdc;
        
        if (is_numeric($iMargem)) {
          $fTotal += $fTotal * $iMargem / 100;
        }

        $fTeste = $oSite->parseValue($fTotal, 'moeda-bd');
        


        $fTotal = $oSite->parseValue($fTotal, 'reais');
        $aRet = json_encode(array('fTotal' => $fTotal));

        echo ($aRet);

        break;
      
      case 'buscarCidades':
        sleep(1);
        $oSite->montaSelectDB('CMPcidade', 'tc_cidades', 'id', 'nm_cidade', '', true, '', '', 'Selecionar uma cidade', 'WHERE id_uf = '.$_POST['iIdUf']);
      break;
    
    
      /************************************************************************
        Carregar dados dinamicamente para a tela
      *************************************************************************/
      case 'carregarDadosLazyLoad':
        include_once 'adapter.produtos.php';
        sleep(2);
        $oProdutos = new produtos();
        
        if ((!is_numeric($_POST['iId'])) )
          exit;
        
?>
    <script type="text/javascript">
      $(document).ready(function() {
        //alert('JS adicionado');

        //$('#produto .dados .imagens, .botao-comprar , #vitrine img, .item-hover, .botao1, .titulo,  input[type="submit"], .bt_salvar, .bt').corner('5px');
        
        $('.itens').hover(
          function(){
            $(this).addClass('item-hover');
          },
          function(){
            $(this).removeClass('item-hover');
          }
        );

      });
    </script>
<?php
        
        $oProdutos->buscarItensVitrine('', $_POST['sTpFiltro']);
        $oProdutos->montarVitrine4x4();

        break;

      /************************************************************************
        Cálculo de frete realizado para informar usuário
      *************************************************************************/
      case 'calcularFrete':
        include_once 'class.frete.php';
        $oFrete = frete::carregarClasseFrete();
        $fValorFrete = $oFrete->calcularFretePorCep($_POST['iCep1'].$_POST['iCep2']);

        ?>
        <table>
          <tr>
            <td>R$</td>
            <td><?php echo $oSite->parseValue($fValorFrete, 'reais'); ?></td>
          </tr>
        </table><?php

        break;

      /************************************************************************
        Usado para cadastro de endereço de usuários
      *************************************************************************/
      case 'buscarDadosEndereco':
        
        include 'class.ws_enderecos.php';
        sleep(1);
        $oWScep = new ws_enderecos();
        $bSucesso = $oWScep->buscar_cep($_POST['iCep']);
        

        $aRet = array ( 'bSucesso' => $bSucesso,
                        'sCdTpCep' => $oWScep->sCdTpCep,
                         'sCidade' => $oWScep->sCidade,
                           'sSgUf' => $oWScep->sSgUf,
                 'sTipoLogradouro' => $oWScep->sTipoLogradouro,
                     'sLogradouro' => utf8_encode($oWScep->sLogradouro),
                         'sBairro' => $oWScep->sBairro
            );

        $aRet = json_encode($aRet);

        echo ($aRet);

        break;

      /************************************************************************
        Atualiza a sessão adicionando ou removendo item do carrinho e atualizando
        os dados na tela.
      *************************************************************************/
      case 'alterarQuantidadeItensCarrinho':


        $oCarrinho = new carrinho();

        $oCarrinho->alterarQuantidadeItensCarrinho($_POST['iIdProd'], $_POST['sTpAcao']);

        $oCarrinho->calcularTotais();


        
//        if (isset($oCarrinho->aParamDesc)) {
//          $teste = json_encode($oCarrinho->aParamDesc);
//          // Debugs - tstAlex
//          $oArqDebugs = fopen('C:\Documents and Settings\Alex Lunardelli\Meus documentos\htdocs\debugs\debugs.txt', 'w+');
//          ob_start();
//          print_r($teste);
//          print_r($oCarrinho->aParamDesc);
//          $sDebugs = ob_get_clean();
//          fwrite($oArqDebugs, $sDebugs);
//          fclose($oArqDebugs);
//          
//        }
        
        $aRet = array(
            'fVlTotal' => $oCarrinho->oUtil->parseValue($_SESSION[$oCarrinho->sUsuarioSessao]['carrinho'][$_POST['iIdProd']]['fVlTotal'], 'reais'),
             'fVlUnid' => $_SESSION[$oCarrinho->sUsuarioSessao]['carrinho'][$_POST['iIdProd']]['fVlUnid'],
                'iQnt' => $_SESSION[$oCarrinho->sUsuarioSessao]['carrinho'][$_POST['iIdProd']]['iQnt'],

              'fTotal' => $oCarrinho->oUtil->parseValue($oCarrinho->aTotais['total'], 'reais'),
         'fVlProdutos' => $oCarrinho->oUtil->parseValue($oCarrinho->aTotais['produtos'], 'reais'),
        'fVlDescontos' => $oCarrinho->oUtil->parseValue($oCarrinho->aTotais['descontos'], 'reais'),
            'fVlFrete' => $oCarrinho->oUtil->parseValue($oCarrinho->aTotais['frete'], 'reais'),
   'sAvisoDescontoQnt' => $oCarrinho->aParamDesc['sAvisoDescontoQnt'][$_POST['iIdProd']]
            
        );
  


        $aRet = json_encode($aRet);


        echo ($aRet);

        break;

      /************************************************************************
        Remove item do carrinho. 
        Exclui item da sessão e em caso de sucesso, remove via jQuery o item da 
        tela.
      *************************************************************************/
      case 'removerItemCarrinhoSessao':
        $oCarrinho = new carrinho();
        $oCarrinho->removerProdCarrinho($_POST['iIdProd']);
        $oCarrinho->calcularTotais();

        $aRet = array('bCarrinhoVazio' => false,
                              'fTotal' => $oCarrinho->aTotais['total'],
                         'fVlProdutos' => $oCarrinho->aTotais['produtos'],
                        'fVlDescontos' => $oCarrinho->aTotais['descontos'],
                            'fVlFrete' => $oCarrinho->aTotais['frete'],
                   'iQntProdRestantes' => $oCarrinho->iQntProdRestantes
                      );

        if ($oCarrinho->iQntProdRestantes == 0) {
          $aRet['sHtmlMsgSemCarrinho'] = $oCarrinho->aviso('Seu carrinho esta vazio');
          $aRet['bCarrinhoVazio']      = true;
        }

        $aRet = json_encode($aRet);
        echo ($aRet);

        break;

    }


  }
?>
