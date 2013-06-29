<?php
  session_start();
  $sPgAtual = 'fabricantes';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tc_prod_fabricantes.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  $oMenu = new adapter_tclv_menu();

  if (isset ($_POST)) {

    if (isset($_POST['sResultado'])) {
      $aMsg = $oAdmin->msgRetPost($_POST);
    }

    if (isset($_POST['sAcao'])) {
      if ($_POST['sAcao'] == 'remover') {
        $oManFabricantes = new tc_prod_fabricantes();
        $sFiltro = implode(',', $_POST['CMPaId']);
        $sWhere = "WHERE id IN (".$sFiltro.")";
        $oManFabricantes->remover($sWhere);
        $aMsg = $oManFabricantes->aMsg;
      }
    }
  }



  $oFabricantes = new tc_prod_fabricantes();
  $oFabricantes->listar();
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
          removerViaCheckBox('Deseja realmente excluir os fabricantes selecionados?', 'fabricantes.php', 'remover');
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
          <a href="fabricantes_edt.php"><img src="../comum/imagens/icones/add.png" alt="Adicionar" /></a>
          <span style="margin-left: 5px" class="bt_img remover"><img src="../comum/imagens/icones/cross.png" alt="Remover" /></span>
        </div>

        <table class="dataTable" style="z-index: 1">
          <thead>
            <tr>
              <td style="width: 15px">&nbsp;</td>
              <td style="width: 50%">Nome</td>
              <td style="width: 40%">Status</td>
            </tr>
          </thead>
          <tbody>
            <?php
              if ($oFabricantes->iLinhas > 0) {
                for ($i = 0; $i < $oFabricantes->iLinhas; $i++) {
                  $bLinha = $i%2 ? true : false;
                  ?>
                  <tr class="<?php echo ($bLinha) ? 'corSim' : 'corNao'; ?>">
                    <td class="multiCheck2">
                      <input type="checkbox" class="checkRemover" name="CMPremover_<?php echo $oFabricantes->ID[$i]; ?>" value="<?php echo $oFabricantes->ID[$i]; ?>" />
                    </td>
                    <td><a href="fabricantes_edt.php?n=<?php echo $oFabricantes->ID[$i]; ?>" ><?php echo $oFabricantes->NM_FABRICANTE[$i]; ?></a></td>
                    <td><?php echo $CFGaSituacao[$oFabricantes->CD_STATUS[$i]]; ?></td>
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