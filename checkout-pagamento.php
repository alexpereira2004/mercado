<?php
  session_start();
  $sPgAtual = 'checkout';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  $oSite       = new pimentas();
  $oProdutos   = new produtos();
  $oNuvem      = new nuvem_tags('tags');

  /****************************************************************************
    Transação com PagSeguro
  ****************************************************************************/
  $bBtAvancar = false;
  $bPagamentoOk = false;

  $oLogin  = new clientes();
  $bLogado = $oLogin->validar(true, $oSite->sUrlBase.'/checkout/identificacao/');

  $oCarrinho = new carrinho();

  if (isset($_POST['sAcao'])) {
    if ($_POST['sAcao'] == 'enviar-pagseguro') {
      $oCarrinho->checkoutPagamento($bLogado);      
    }
  }
  

  // Caso esteja logado e com pagamento realizado, o usuário será redirecionado
  // para a página seguinte
  if ($bLogado && $bPagamentoOk) {
    header('location:'.$oSite->sUrlBase.'/checkout/confirmacao/');
    exit;
  }
  

  $oBloco = new tcctd_blocos();
  $oBloco->listar("WHERE cd_bloco IN ('AVISO_PAGSEGURO', 'AVISO_REDIR_PAGSEGURO') ORDER BY id DESC");
  
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
            <td><a href="<?php echo $oSite->sUrlBase; ?>/checkout/itens/">1. Produtos</a></td>
            <td><a href="<?php echo $oSite->sUrlBase; ?>/checkout/identificacao/">2. Identificação</a></td>
            <td class="sel">3. Pagamento</td>
            <td>4. Confirmação</td>
          </tr>
        </table>

            
        <form action="<?php echo $oSite->sUrlBase; ?>/checkout/pagamento/" method="post">
          <input type="hidden" id="CMPsUrlBackPage" name="CMPsUrlBackPage" value="<?php echo $_SESSION[carrinho::getUsuarioSessao()]['navegacao']['sUrlBackPage']; ?>" />
          <input type="hidden" id="CMPsUrlBackPage" name="sAcao" value="enviar-pagseguro" />
          <?php
            
            $sHtml = $oBloco->TX_CONTEUDO[0];
            $sHtml .= '<br /><br /><br />';
            $sHtml .= '<input type="submit" style="margin-left: 50px;" value="Comprar com Pagseguro" />';
            $sHtml .= '<br /><br /><br />';
            $sHtml .= $oBloco->TX_CONTEUDO[1];
            ?>

          <?php
            echo $oCarrinho->aviso($sHtml);
          ?>          
          <div class="limpa"></div>
        </form>
      </div>
      <div class="limpa"></div>
    </div>
    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>    
  </body>
</html>
