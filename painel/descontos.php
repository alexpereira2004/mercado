<?php
  session_start();
  $sPgAtual = 'descontos';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tc_descontos.php';
  include_once '../modulosPHP/class.tr_prod_desconto.php';


  $oLogin = new usuario_admin();
  $oLogin->validar();
  $oAdmin = new admin();
  
  if (isset ($_POST)) {

    if (isset($_POST['sResultado'])) {
      $aMsg = $oAdmin->msgRetPost($_POST);
    }

    if (isset($_POST['sAcao'])) {
      if ($_POST['sAcao'] == 'remover') {
        $sFiltro = implode(',', $_POST['CMPaId']);
        $oManProdRelacionados = new tr_prod_desconto();
        $sWhere = "WHERE id_desconto IN (".$sFiltro.")";
        $oManProdRelacionados->remover($sWhere);
        $aMsg = $oManProdRelacionados->aMsg;
        
        if ($oManProdRelacionados->aMsg['iCdMsg'] == 0) {
          $oManDescontos = new tc_descontos();
          $sWhere = "WHERE id IN (".$sFiltro.")";
          $oManDescontos->remover($sWhere);
          $aMsg = $oManDescontos->aMsg;          
        }
        
      }
    }
  }

  $oDescontos = new tc_descontos();
  $oDescontos->listar();

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
          removerViaCheckBox('Deseja realmente excluir os descontos selecionados?', 'descontos.php', 'remover');
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
          <a href="descontos_edt.php"><img src="../comum/imagens/icones/add.png" alt="Adicionar" /></a>
          <span style="margin-left: 5px" class="bt_img remover"><img src="../comum/imagens/icones/cross.png" alt="Remover" /></span>
        </div>
        <table class="dataTable" style="z-index: 1">
          <thead>
            <tr>
              <td style="width: 15px">&nbsp;</td>
              <td style="width: 30%">Nome</td>
              <td style="width: 20%">Configuração</td>
              <td style="width: 30%">Valor</td>
              <td style="width: 10%">De</td>
              <td style="width: 10%">Até</td>
              <td style="width: 10%">Status</td>
            </tr>
          </thead>
          <tbody>
            <input type="hidden" id="iQndReg" value="<?php echo $oDescontos->iLinhas; ?>" />
            <?php
              if ($oDescontos->iLinhas > 0) {
                for ($i = 0; $i < $oDescontos->iLinhas; $i++) {
                  $bLinha = $i%2 ? true : false;
                  $sComp01 = $oDescontos->TP_VALOR[$i] == 'V' ? 'R$ ' : '';
                  $sComp02 = $oDescontos->TP_VALOR[$i] == 'P' ? ' %' : '';
                  ?>
                  <tr class="<?php echo ($bLinha) ? 'corSim' : 'corNao'; ?>">
                    <td class="multiCheck2">
                      <input type="checkbox" class="checkRemover" name="CMPremover_<?php echo $oDescontos->ID[$i]; ?>" value="<?php echo $oDescontos->ID[$i]; ?>" />
                    </td>
                    <td><a href="descontos_edt.php?n=<?php echo $oDescontos->ID[$i]; ?>" ><?php echo $oDescontos->NM_DESCONTO[$i]; ?></a></td>
                    <td><?php echo $CFGaTiposDesconto[$oDescontos->TP_DESCONTO[$i]] ?></td>
                    <td><?php echo $sComp01.$oDescontos->VL_DESCONTO[$i].$sComp02; ?></td>
                    <td><?php echo $oDescontos->DT_VIGENCIA_INICIO[$i];?></td>
                    <td><?php echo $oDescontos->DT_VIGENCIA_FIM[$i];?></td>
                    <td><?php echo $CFGaSituacao[$oDescontos->CD_STATUS[$i]]; ?></td>
                  </tr>
                  <?php
                }
              } else { ?>
                <tr>
                  <td colspan="3" class="infoValue">Nenhum registro</td>
                </tr>
              <?php
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