<?php
  session_start();
  $sPgAtual = 'pedidos-cancelados';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tc_clientes.php';
  include_once '../modulosPHP/adapter.clientes.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();

  $oAdmin = new admin();
  
  if (isset ($_POST)) {
    $aMsg = $oAdmin->msgRetPost($_POST);

    if (isset($_POST['sAcao'])) {
      if ($_POST['sAcao'] == 'remover') {

      }

      if ($_POST['sAcao'] == 'pesquisar') {

      }
    }
  }

  $oPedidos = new carrinho();
  $sFiltro  = implode("','", $CFGaGrupoPedidosCancelados);
  $oPedidos->listar("WHERE cd_sit IN ('".$sFiltro."') ORDER BY id DESC");
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
        $('.remover').click(function(){
          removerViaCheckBox('Deseja realmente excluir os clientes selecionados?', 'clientes.php', 'remover');
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
      <div id="corpo">
        <?php
          $oAdmin->msgRetAlteracoes($aMsg);
          $oAdmin->breadCrumbs();
          //$oAdmin->minheight('600');
        ?>
        <div id="toolBar">
        </div>

        <table class="dataTable" style="z-index: 1">
          <thead>
            <tr class="header">
              <td style="width: 15px">&nbsp;</td>
              <td>Cliente</td>
              <td>Cód. Carrinho</td>
              <td>Cód. Pag Seguro</td>
              <td>Data de cadastro</td>
              <td>Valor</td>
            </tr>
          </thead>
          <tbody>
            <?php
              if ($oPedidos->iLinhas > 0) {
                for ($i = 1; $i < $oPedidos->iLinhas; $i++) {
                  $bLinha = $i%2 ? true : false;
                  ?>
                  <tr class="<?php echo ($bLinha) ? 'corSim' : 'corNao'; ?>">
                    <td class="multiCheck2">
                      <input type="checkbox" class="checkRemover" name="CMPremover_<?php echo $oPedidos->ID[$i]; ?>" value="<?php echo $oPedidos->ID[$i]; ?>" />
                    </td>
                    <td>
                      <a href="clientes_edt.php?n=<?php echo $oPedidos->ID[$i]; ?>">
                        <span id="nome_reg_<?php echo $oPedidos->ID[$i]; ?>">
                          <?php echo $oPedidos->NM_CLIENTE[$i].' '.$oPedidos->NM_SOBRENOME[$i]; ?>
                        </span>
                      </a>
                    </td>
                    <td><?php echo $oPedidos->CD_CARRINHO[$i]; ?></td>
                    <td><?php echo $CFGaCodSitPedido[$oPedidos->CD_SIT[$i]]; ?></td>
                    <td><?php echo $oPedidos->DT_CRIACAO[$i]; ?></td>
                    <td><?php echo $oAdmin->parseValue($oPedidos->VL_TOTAL[$i], 'reais'); ?></td>
                  </tr>
                  <?php
                }
              }
            ?>
          </tbody>
        </table>
        <form method="post" id="FRMremover" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <input type="hidden" name="sAcao" value="remover" />
          <input type="hidden" name="CMPid" id="CMPid"  value="" />
        </form>
      </div>
      <div class="limpa"></div>
      <?php
        $oAdmin->rodape($sPgAtual);
      ?>
    </div>
  </body>
</html>