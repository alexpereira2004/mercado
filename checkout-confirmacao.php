<?php
  session_start();
  $sPgAtual = 'checkout';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  $oSite       = new pimentas();
  $oProdutos   = new produtos();
  $oNuvem      = new nuvem_tags('tags');
  $oCarrinho   = new carrinho();
  
  $oLogin = new clientes();
  $bLogado = $oLogin->validar();
  $bFinalizarCompra = false;

  //if (isset($_SESSION[carrinho::getUsuarioSessao()]['carrinho']) && $bLogado) {
  if ($bLogado) {
    $oCarrinho->iIdCliente = $_SESSION[carrinho::getUsuarioSessao()]['login']['id_usu'];
    $oLogin->listar('WHERE id = '.$oCarrinho->iIdCliente);
  }

  $sCdCarrinho      = isset($_GET['code'])           ? $_GET['code'] : null;
  $sIdTransaction   = isset($_GET['transaction_id']) ? $_GET['transaction_id'] : null;
  $bFinalizarCompra = $oCarrinho->confirmacao($sCdCarrinho, $sIdTransaction);

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo']; ?></title>

    <?php
      $oSite->incluirCss($sPgAtual);
      $oSite->incluirJs($sPgAtual);
      $oSite->incluirMetaTags($sPgAtual);
    ?>
    <script type="text/javascript">
      $(document).ready(function() {
        $('.continuar-comprando').click(function(){
          window.location = $('#CMPsUrlBackPage').val();
        });
      });
    </script>
  </head>
  <body>
    <?php 
      echo $oSite->cabecalho();
    ?>
    
    <div id="pagina">
      <?php 
        echo $oSite->listagem($sPgAtual);
      ?>
      <div id="conteudo">
        <h1 class="titulo-02">Checkout</h1>
        <table id="checkout-passo">
          <tr>
            <td>1. Produtos</td>
            <td>2. Identificação</td>
            <td>3. Pagamento</td>
            <td class="sel">4. Confirmação</td>
          </tr>
        </table>
          
        <form action="<?php echo $oSite->sUrlBase; ?>/checkout/confirmacao/" method="post">
          <input type="hidden" id="CMPsUrlBackPage" name="CMPsUrlBackPage" value="<?php echo $_SESSION['anonimo']['navegacao']['sUrlBackPage']; ?>" />
          <?php
            if ($bFinalizarCompra && $bLogado) { ?>
              <!-- <input type="submit" value="Finalizar compra" style="float: right; margin-bottom: 10px;"/> --> <?php
            }
          ?>
          <div class="limpa"></div>
          <?php
            if (!$bLogado) {
              $sTxt  = 'Você não esta logado!';
              $sTxt .= '<br /><a href="'.$oSite->sUrlBase.'/conta/meus-dados/">Clique aqui</a> para verificar a situação da sua compra.';
              echo $oCarrinho->aviso($sTxt);              
            } else if (!$bFinalizarCompra) {
              $sTxt  = $oCarrinho->oLog->NM_LOG[0];
              echo $oCarrinho->aviso($sTxt);
            } else {
              $oExtrato = new pagSeguro();
              $oExtrato->pesquisarPorCodigo($sIdTransaction);
              ob_start(); 
              ?>
          
              <div class="msg-ok">Recebemos seu pedido! Assim que o pagamento for confirmado seu pedido será liberado</div><br />
              <table class="box-01" cellspacing="0">
                <tr class="cabecalho" style="text-align: right; padding: 10px">
                  <td class="cabecalho w50" style="text-align: left">Produto</td>
                  <td class="w10" style="text-align: center;">Quantidade</td>
                  <td class="w15" style="text-align: right;">Preço Un.</td>
                  <td class="w15" style="text-align: right;">Total</td>
                </tr><?php
                foreach ($oExtrato->items as $key => $oPagSeguroItem ) { ?>
                <tr>
                  <td><?php echo $oPagSeguroItem->getDescription();?></td>
                  <td style="text-align: center;"><?php echo $oPagSeguroItem->getQuantity();?></td>
                  <td style="text-align: right;"><?php echo $oSite->parseValue($oPagSeguroItem->getAmount(), 'reais');?></td>
                  <td style="text-align: right;"><?php echo $oSite->parseValue($oPagSeguroItem->getAmount() * $oPagSeguroItem->getQuantity(), 'reais');?></td>
                </tr>
                <?php
              } ?>
              </table><?php
              
              $sHtmlItens = ob_get_clean();
              echo '<br />';
              echo $sHtmlItens;
              
              $fVlrParcelado = $oExtrato->grossAmount / $oExtrato->installmentCount;
              ob_start(); ?>
              <table class="w90">
                <tr>
                  <td class="infoheader w30">Situação:</td>
                  <td class="infovalue w70"><?php echo $CFGaStatusPagSeguro[$oExtrato->status->getValue()];?></td>
                </tr>
                <tr>
                  <td class="infoheader">Método de pagamento:</td>
                  <td class="infovalue"><?php echo $CFGaCodigoMetodoPagamento[$oExtrato->paymentMethodCode];?></td>
                </tr>
                <tr>
                  <td class="infoheader">Data do último evento da transação:</td>
                  <td class="infovalue"><?php echo $oSite->parseValue($oExtrato->date, 'bd-dt');?></td>
                </tr>
                <tr>
                  <td class="infoheader"><?php echo $CFGaCardinalF[$oExtrato->installmentCount];?> parcela<?php echo $oSite->plural($oExtrato->installmentCount)?>:</td>
                  <td class="infovalue">R$ <?php echo $oSite->parseValue($fVlrParcelado, 'reais');?></td>
                </tr>
              </table>
              <?php
              $sHtmlDadosPagamento = ob_get_clean();  

              echo '<br />';
              $oSite->box02('Forma de pagamento', $sHtmlDadosPagamento);

              echo '<br />';
              $oSite->box02('Dados do comprador', $oSite->apresentarDadosCliente($oLogin));

              echo '<br />';
              $oSite->box02('Endereço de entrega', $oSite->apresentarDadosEndereçoCliente($oLogin));              
            }

          if ($bFinalizarCompra && $bLogado) { ?>
          <!-- <input type="submit" value="Finalizar compra" style="float: right; margin-top: 10px;"/> --> <?php
          }
          ?>
          <div class="limpa"></div>
        </form>
        <?php
          $oNuvem->montarNuvem();
        ?>
        
      </div>
      <div class="limpa"></div>
    </div>
    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>    
  </body>
</html>
