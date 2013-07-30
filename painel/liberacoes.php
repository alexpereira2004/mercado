<?php
  session_start();
  $sPgAtual = 'liberacoes';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tc_clientes.php';
  include_once '../modulosPHP/adapter.clientes.php';
  // include_once '../modulosPHP/class.tc_carrinho.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  //$oMenu = new adapter_tclv_menu();
  
  if (isset ($_GET)) {
    $aMsg = $oAdmin->msgRetPost($_POST);

    if (isset($_GET['n'])) {
      $oManPedido = new carrinho();
      $sCdCarrinho = $oAdmin->anti_sql_injection($_GET['n']);
      $iIdCliente  = $_GET['cliente'];
      $sFiltro  = "WHERE cd_carrinho = '$sCdCarrinho'";
      $sFiltro .= " AND id_cliente = '$iIdCliente'";

      $oManPedido->listar($sFiltro);
      
      $oManPedido->atualizarSituacaoCarrinho('EX', $iIdCliente, $sCdCarrinho);
      if ($oManPedido->aMsg['iCdMsg'] == 0) {
        $oManPedido->aMsg['sMsg']  = 'A situação do pedido '.$oManPedido->CD_CARRINHO[1].' foi atualizada para <b>em '.$CFGaCodSitPedido['EX'].'</b><br />';
        $oManPedido->aMsg['sMsg'] .= '<a href="#" class="link-1">Clique aqui para finalizar o pedido</a> e informando as informações de controle.';
      } else {
        $oManPedido->aMsg['sMsg'] = 'Ocorreu um erro ao atualizar a situação do pedido.';
      }
      $aMsg = $oManPedido->aMsg;                         
    }

  }

  $oPedidos = new carrinho();
  $oPedidos->listar("WHERE cd_sit IN ('PC') ORDER BY id DESC");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'];?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script type="text/javascript">
      $(document).ready(function() {
        $('.dataTable').dataTable({
          "iDisplayLength": 25
        });
      });
    </script>

  </head>
  <body>
    <div id="pagina">
      <?php
        $oAdmin->cabecalho();
        $oAdmin->montarMenu($sPgAtual);
      ?>
      <div id="corpo" >
        <?php
          $oAdmin->msgRetAlteracoes($aMsg);
          $oAdmin->breadCrumbs();
          //$oAdmin->minheight('600');
        ?>
        <div id="toolBar"></div>

        <table class="dataTable" style="z-index: 1">
          <thead>
            <tr class="header">
              <td style="width: 15px">&nbsp;</td>
              <td>Nome</td>
              <td>Código</td>
              <td>Situação</td>
              <td>Data da Compra</td>
            </tr>
          </thead>
          <tbody>
            <?php
              if ($oPedidos->iLinhas > 0) {
                for ($i = 1; $i <= $oPedidos->iLinhas; $i++) {
                  $bLinha = $i%2 ? true : false;
                  ?>
                  <tr class="<?php echo ($bLinha) ? 'corSim' : 'corNao'; ?>">
                    <td class="multiCheck2">
                      <a href="liberacoes.php?n=<?php echo $oPedidos->CD_CARRINHO[$i]; ?>&cliente=<?php echo $oPedidos->ID_CLIENTE[$i];?>">Finalizar</a>
                    </td>
                    <td><?php echo $oPedidos->NM_CLIENTE[$i]; ?></td>
                    <td><?php echo $oPedidos->CD_CARRINHO[$i]; ?></td>
                    <td><?php echo $CFGaCodSitPedido[$oPedidos->CD_SIT[$i]]; ?></td>
                    <td><?php echo $oPedidos->DT_CRIACAO[$i]; ?></td>
                  </tr>
                  <?php
                }
              }
            ?>
          </tbody>
        </table>
      </div>
      <div class="limpa"></div>
      <?php
        $oAdmin->rodape($sPgAtual);
      ?>
    </div>
  </body>
</html>