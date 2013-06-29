<?php
  session_start();

  $sPgAtual = 'checkout';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  $oSite       = new pimentas();
  $oProdutos   = new produtos();
  $oNuvem      = new nuvem_tags('tags');


  $oLogin = new clientes();
  $bLogado = $oLogin->validar();
  $aMsgCliCadastrado = $aMsg;
  $aMsgCliNovo       = $aMsg;
  
  if (isset($_POST['sAcao'])) {

    $oManCliente = new clientes();

    // Testa a tentativa de acesso ao sistema
    if ($_POST['sAcao'] == 'acessar') {
      $oManCliente->tratarFormLogin(0);
      $aMsgCliCadastrado = isset($oManCliente->aMsg['iCdMsg']) ? $oManCliente->aMsg : $aMsg;
    }
    
    if ($_POST['sAcao'] == 'novo-cadastro') {
      $oManCliente->tratarFormLoginNaoCadastrado(0);
      $aMsgCliNovo = isset($oManCliente->aMsg['iCdMsg']) ? $oManCliente->aMsg : $aMsg;
    }
  }

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
    <script type="text/javascript" src="<?php echo $oSite->sUrlBase;?>/modulosJS/maskedinput/jquery.maskedinput-1.3.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        
        $('.continuar-comprando').click(function(){
          window.location = $('#CMPsUrlBackPage').val();
        });
        $('.avancar').click(function(){
          $('#target-identificacao').submit();
        });
        $(".mask_cep").mask("99999-999");
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
            <td class="sel">2. Identificação</td>
            <td>3. Pagamento</td>
            <td>4. Confirmação</td>
          </tr>
        </table>
          
        <form id="target-identificacao" action="<?php echo $oSite->sUrlBase; ?>/checkout/pagamento/" method="post">
          <input type="hidden" id="CMPsUrlBackPage" name="CMPsUrlBackPage" value="<?php echo $_SESSION[carrinho::getUsuarioSessao()]['navegacao']['sUrlBackPage']; ?>" /> 
          <input type="button" class="bt_salvar continuar-comprando" value="Continuar comprando" style="float: left; margin-bottom: 10px;"/>
          <?php
            if ($bLogado) { ?>
              <input type="submit" value="Avançar" style="float: right; margin-bottom: 10px;"/>
              <?php
            } ?>
        </form>
        <div class="limpa"></div>
        <?php
          $sAction = $oSite->sUrlBase.'/checkout/identificacao/';

          // Se já estiver logado, simplesmente mostrar os dados do usuário
          if ($bLogado) {
            $oLogin->listar('WHERE id = '.$_SESSION[carrinho::getUsuarioSessao()]['login']['id_usu']);

            $oSite->box01('Dados do comprador', $oSite->apresentarDadosCliente($oLogin));
            echo '<br />';
            $oSite->box01('Endereço de entrega', $oSite->apresentarDadosEndereçoCliente($oLogin));

            // Se ainda não estiver logado, então deverá mostrar o formulário de login
          } else { ?>
            <div style="width: 49%; float: left;">
              <?php 
                $sAction = $oSite->sUrlBase.'/checkout/identificacao#target-identificacao';
                $oSite->box01('Já sou cadastrado', $oSite->montarFormLogin($sAction, $aMsgCliCadastrado));
              ?>
            </div>
            <div style="width: 49%; float: right;">
              <?php 
                //$sAction = $oSite->sUrlBase.'/conta/cadastro/';
                $sAction = $oSite->sUrlBase.'/checkout/identificacao#target-identificacao';
                $oSite->box01('Cadastre-se!', $oSite->montarFormLoginNaoCadastrado($sAction, $aMsgCliNovo));
              ?>
            </div>
            <div class="limpa"></div>
        <?php
          } ?>
          <input type="button" class="bt_salvar continuar-comprando" value="Continuar comprando" style="float: left; margin-top: 10px;"/>
            
        <?php  
          if ($bLogado) { ?>
          <input type="submit" class="avancar" value="Avançar" style="float: right; margin-top: 10px;"/>
            <?php
          }
        ?>
          <div class="limpa"></div>
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
