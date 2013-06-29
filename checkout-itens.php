<?php
  session_start();
  $sPgAtual = 'checkout';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  $oSite       = new pimentas();
  $oNuvem      = new nuvem_tags('tags');
  $oCarrinho   = new carrinho();
  if (isset($_POST['sAcao'])) {
    $oCarrinho->criar(array($_POST['CMPiIdProd']));
  }
  
  $bPossuiItens = $oCarrinho->contarItensRestantes() > 0 ? true : false;
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
        $('.removerItemCarrinhoSessao').click(function(){
          var sId    = $(this).attr('id');
          var aDados = sId.split('-');
          removerItemCarrinhoSessao(aDados[2]);
        });
        $('.alterarQuantidadeItensCarrinho').click(function(){
          var sId    = $(this).attr('id');
          var aDados = sId.split('-');
          var sTpAcao = aDados[0];
          var iIdProd = aDados[2];
          alterarQuantidadeItensCarrinho(iIdProd, sTpAcao);
        });
        

        
        $('.blink').blink();
        
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
            <td class="sel">1. Produtos</td>
            <td>2. Identificação</td>
            <td>3. Pagamento</td>
            <td>4. Confirmação</td>
          </tr>
        </table>
          
        <form id="FRMcarrinho" action="<?php echo $oSite->sUrlBase; ?>/checkout/identificacao/" method="post">
          <input type="hidden" id="CMPsUrlBackPage" name="CMPsUrlBackPage" value="<?php echo $_SESSION[$oCarrinho->sUsuarioSessao]['navegacao']['sUrlBackPage']; ?>" />
          <input type="hidden" id="CMPsUrlBase" name="CMPsUrlBase" value="<?php echo $oSite->sUrlBase; ?>" />
          <input type="button" class="bt_salvar continuar-comprando" value="Continuar comprando" style="float: left; margin-bottom: 10px;"/>
          <?php
            if ($bPossuiItens) { ?>
              <input type="submit" value="Avançar" style="float: right; margin-bottom: 10px;"/> <?php
            }
          ?>
          <div class="limpa"></div>
          <?php
            $oCarrinho->apresentarProdutosCarrinho(); 
          ?>
          <input type="button" class="bt_salvar continuar-comprando" value="Continuar comprando" style="float: left; margin-top: 10px;"/>
          <?php
            if ($bPossuiItens) { ?>
              <input type="submit" value="Avançar" style="float: right; margin-top: 10px;"/> <?php
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
