<?php
  session_start();
  $sPgAtual = 'usuario-pedidos';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  include      'modulosPHP/PHPMailer_5.2.1/class.phpmailer.php';
  
  $oSite       = new pimentas();

  $oLogin      = new clientes();
  $oLogin->validar(true, $oSite->sUrlBase.'/conta/login/');
  
  $sFiltroAdc = " AND cd_sit NOT IN ('FI', 'CA')";
  $sPagina = 'em Aberto';

  if (isset($_GET)) {

    switch ($_GET['n']) {
      case 'finalizados/':
      case 'finalizados':
        $sPagina = 'Finalizados';
        $sFiltroAdc = "  AND cd_sit IN ('FI', 'CA') ";
        break;
    }
  }
  
  $iIdCliente = $_SESSION[carrinho::getUsuarioSessao()]['login']['id_usu'];
  $oCarrinho = new carrinho();
  $sFiltro  = " WHERE id_cliente = ".$iIdCliente;
  $sFiltro .= $sFiltroAdc;
  $sFiltro .= ' AND nu_itens > 0 ';
  $sFiltro .= ' ORDER BY id DESC ';
  $oCarrinho->listar($sFiltro);
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
      <div id="conteudo" class="animacao-start">
        <h3 class="titulo-02">Últimos Pedidos <?php echo $sPagina; ?> </h3>


        <?php
          
          for ($i = 1; $i <= $oCarrinho->iLinhas; $i++) {

            $oCarrinho->ID[$i];
            ?>
            <table class="w90">
              <tr id="informacoes-<?php echo $i; ?>">
                <td style="width: 20px; cursor: pointer" onclick="abreFechaDiv('detalhes-<?php echo $i; ?>')" ><img src="<?php echo $oSite->sUrlBase; ?>/comum/imagens/icones/bullet_toggle_plus.png" /></td>
                <td><?php $oSite->box02('Pedido', $oCarrinho->CD_CARRINHO[$i]);?></td>
                <td><?php $oSite->box02('Data', $oCarrinho->DT_CRIACAO[$i]);?></td>
                <td><?php $oSite->box02('Situação', $CFGaCodSitPedido[$oCarrinho->CD_SIT[$i]]);?></td>
              </tr>
            </table>
            <table id="detalhes-<?php echo $i; ?>" class="menu_flex w90">
              <tr>
                <td style="width: 20px">&nbsp;</td>
                <td><?php $oSite->box02('Tipo de entrega', $oCarrinho->CD_TIPO_ENTREGA[$i]);?></td>
                <td><?php $oSite->box02('Detalhes da entrega', $oCarrinho->DE_ENTREGA[$i]);?></td>
              </tr>
              <tr>
                <td style="width: 20px">&nbsp;</td>
                <td colspan="2">
                  <table class="box-02" cellspacing="0" >
                    <tr class="cabecalho f80" style="text-align: right; padding: 10px">
                      <td class="cabecalho w20" style="text-align: left">Produto</td>
                      <td class="w10">Quantidade</td>
                      <td class="w10">Preço Un.</td>
                      <td class="w10">Desconto</td>
                      <td class="w10">Total</td>
                    </tr>
                    <?php 

                      for ($c = 0; $c < $oCarrinho->NU_ITENS[$i]; $c++) { ?>
                        <tr class="conteudo">
                          <td class="infoheader"><?php echo $oCarrinho->ITENS[$i]['nm_produto'][$c]; ?></td>
                          <td class="txt-right"><?php echo $oCarrinho->ITENS[$i]['nu_quantidade'][$c];?></td>
                          <td class="txt-right">R$ <?php echo $oSite->parseValue($oCarrinho->ITENS[$i]['vl_unidade'][$c], 'reais'); ?></td>
                          <td class="txt-right">R$ <?php echo $oSite->parseValue($oCarrinho->ITENS[$i]['vl_desconto'][$c], 'reais'); ?></td>
                          <td class="txt-right">R$ <?php echo $oSite->parseValue($oCarrinho->ITENS[$i]['vl_final'][$c], 'reais'); ?></td>
                        </tr>
                    <?php
                      }
                    ?>
                    <tr>
                      <td colspan="3"></td>
                      <td class="infoheader">Frete: </td>
                      <td>R$ <?php echo $oSite->parseValue($oCarrinho->VL_FRETE[$i], 'reais'); ?></td>
                    </tr>
                    <tr>
                      <td colspan="3"></td>
                      <td class="infoheader">Total: </td>
                      <td>R$ <?php echo $oSite->parseValue($oCarrinho->VL_TOTAL[$i], 'reais'); ?></td>
                    </tr>
                  </table>
                </td>
              </tr>
              <tr>
                <td style="width: 20px">&nbsp;</td>
                <td colspan="2">
                  <?php 
                    ob_start(); 
                    $sEndereco  = $oCarrinho->TP_LOGRADOURO[$i].' ';
                    $sEndereco .= $oCarrinho->NM_LOGRADOURO[$i].', ';
                    $sEndereco .= $oCarrinho->TX_NUMERO[$i];
                    $sEndereco .= $oCarrinho->TX_COMPLEMENTO[$i] != '' ? $oCarrinho->TX_COMPLEMENTO[$i] : '';
                    $sEndereco .= '<br />';
                    $sEndereco .= $oCarrinho->NU_CEP[$i].' ';
                    $sEndereco .= $oCarrinho->TX_BAIRRO[$i].'<br />';
                    $sEndereco .= $oCarrinho->NM_CID[$i].'/'.$oCarrinho->NM_UF[$i];
                    ?>
                    <table>
                      <tr>
                        <td class="infoheader">Endereço de Entrega:</td>
                        <td class="infovalue"><?php echo $sEndereco; ?></td>
                      </tr>
                      <!-- 
                      <tr>
                        <td>Forma de pagamento:</td>
                        <td></td>
                      </tr> -->
                      <tr>
                        <td class="infoheader">Nota Fiscal:</td>
                        <td class="infovalue"><?php echo $oCarrinho->CD_NF[$i] ?></td>
                      </tr>
                    </table>
                  <?php
                    
                    $sDados = ob_get_clean();
                    $oSite->box02('Detalhes do pedido', $sDados);
                  ?>
                </td>
              </tr>
            </table>
            <br />
            
        <?php
          }
          if ($oCarrinho->iLinhas == 0) {
            echo '<div class="box-01">'.$oCarrinho->aviso('Você não possui pedidos '.$sPagina).'</div>';
          }
        ?>
        
      </div>
      <div class="limpa"></div>
        
    </div>
    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>    
  </body>
</html>
