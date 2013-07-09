<?php
  session_start();
  $sPgAtual = 'promocoes';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.promocoes.php';
  include_once '../modulosPHP/class.tc_promocoes.php';
  include_once '../modulosPHP/class.tr_prod_promocao.php';


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
        $oManProdRelacionados = new tr_prod_promocao();
        $sWhere = "WHERE id_promocao IN (".$sFiltro.")";
        $oManProdRelacionados->remover($sWhere);
        $aMsg = $oManProdRelacionados->aMsg;
        
        if ($oManProdRelacionados->aMsg['iCdMsg'] == 0) {
          $oManPromo = new tc_promocoes();
          $sWhere = "WHERE id IN (".$sFiltro.")";
          $oManPromo->remover($sWhere);
          $aMsg = $oManPromo->aMsg;          
        }
        
      }
    }
  }

  $oPromo = new promocoes();
  $oPromo->listar();

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
          removerViaCheckBox('Deseja realmente excluir as promoções selecionados?', 'promocoes.php', 'remover');
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
          <a href="promocoes_edt.php"><img src="../comum/imagens/icones/add.png" alt="Adicionar" /></a>
          <span style="margin-left: 5px" class="bt_img remover"><img src="../comum/imagens/icones/cross.png" alt="Remover" /></span>
        </div>
        <table class="dataTable" style="z-index: 1">
          <thead>
            <tr>
              <td style="width: 15px">&nbsp;</td>
              <td style="width: 30%">Nome</td>
              <td style="width: 20%">Desconto Vinculado</td>
              <td style="width: 20%">De</td>
              <td style="width: 20%">Até</td>
            </tr>
          </thead>
          <tbody>
            <input type="hidden" id="iQndReg" value="<?php echo $oPromo->iLinhas; ?>" />
            <?php
              if ($oPromo->iLinhas > 0) {
                for ($i = 0; $i < $oPromo->iLinhas; $i++) {
                  $bLinha = $i%2 ? true : false; ?>
                  <tr class="<?php echo ($bLinha) ? 'corSim' : 'corNao'; ?>">
                    <td class="multiCheck2">
                      <input type="checkbox" class="checkRemover" name="CMPremover_<?php echo $oPromo->ID[$i]; ?>" value="<?php echo $oPromo->ID[$i]; ?>" />
                    </td>
                    <td><a href="promocoes_edt.php?n=<?php echo $oPromo->ID[$i]; ?>" ><?php echo $oPromo->NM_PROMOCAO[$i]; ?></a></td>
                    <td><?php echo $oPromo->NM_DESCONTO[$i]; ?></td>
                    <td><?php echo $oPromo->DT_VIGENCIA_INICIO[$i];?></td>
                    <td><?php echo $oPromo->DT_VIGENCIA_FIM[$i];?></td>
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