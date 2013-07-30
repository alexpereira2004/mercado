<?php
  session_start();
  $sPgAtual = 'taxas';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tc_taxas.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();

  if (isset ($_POST)) {

    if (isset($_POST['sAcao'])) {
      if ($_POST['sAcao'] == 'remover') {
        $oManTaxas = new tc_Taxas();
        $sFiltro = implode(',', $_POST['CMPaId']);
        $sWhere = "WHERE id IN (".$sFiltro.")";
        $oManTaxas->remover($sWhere);
        $aMsg = $oManTaxas->aMsg;
      }
    }
  }

  $oAdmin = new admin();
  $oTaxas = new tc_taxas();
  $oTaxas->listar();
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
          removerViaCheckBox('Deseja realmente excluir as taxas selecionadas?', 'taxas.php', 'remover');
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
          <a href="taxas_edt.php"><img src="../comum/imagens/icones/add.png" alt="Adicionar" /></a>
          <span style="margin-left: 5px" class="bt_img remover"><img src="../comum/imagens/icones/cross.png" alt="Remover" /></span>
        </div>
        <table class="dataTable" style="z-index: 1">
          <thead>
            <tr class="header">
              <td style="width: 15px">&nbsp;</td>
              <td>Nome</td>
              <td>Valor</td>
              <td>Status</td>
            </tr>
          </thead>
          <tbody>
            <?php
              if ($oTaxas->iLinhas > 0) {
                for ($i = 0; $i < $oTaxas->iLinhas; $i++) {
                  $bLinha = $i%2 ? true : false;
                  $sComp01 = $oTaxas->TP_TAXA[$i] == 'V' ? 'R$ ' : '';
                  $sComp02 = $oTaxas->TP_TAXA[$i] == 'P' ? ' %' : '';
                  ?>
                  <tr class="<?php echo ($bLinha) ? 'corSim' : 'corNao'; ?>">
                    <td class="multiCheck2">
                      <input type="checkbox" class="checkRemover" name="CMPremover_<?php echo $oTaxas->ID[$i]; ?>" value="<?php echo $oTaxas->ID[$i]; ?>" />
                    </td>
                    <td><a href="taxas_edt.php?n=<?php echo $oTaxas->ID[$i]; ?>" ><?php echo $oTaxas->NM_TAXA[$i]; ?></a></td>
                    <td><?php echo $sComp01.$oTaxas->VL_TAXA[$i].$sComp02; ?></td>
                    <td><?php echo $CFGaSituacao[$oTaxas->CD_STATUS[$i]]; ?></td>
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