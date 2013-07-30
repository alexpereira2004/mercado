<?php
  session_start();
  $sPgAtual = 'clientes';

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
  //$oMenu = new adapter_tclv_menu();
  
  if (isset ($_POST)) {
    $aMsg = $oAdmin->msgRetPost($_POST);

    if (isset($_POST['sAcao'])) {
      if ($_POST['sAcao'] == 'remover') {
        $oManClientes = new clientes();
        $oManClientes->remover($_POST['CMPaId']);
        $aMsg = $oManClientes->aMsg;
      }

      if ($_POST['sAcao'] == 'pesquisar') {
        $sFiltro  = "WHERE 1 = 0";
        $sFiltro .= " OR tc_clientes.nm_cliente LIKE '%".$oAdmin->anti_sql_injection($_POST['CMPpesquisar'])."%'";
        $sFiltro .= " OR tc_clientes.nm_sobrenome LIKE '%".$oAdmin->anti_sql_injection($_POST['CMPpesquisar'])."%'";
        $sFiltro .= " OR tc_clientes.nm_razao_social LIKE '%".$oAdmin->anti_sql_injection($_POST['CMPpesquisar'])."%'";
        $sFiltro .= " OR tc_clientes.nm_fantasia LIKE '%".$oAdmin->anti_sql_injection($_POST['CMPpesquisar'])."%'";
      }
    }

  }

  $oClientes = new clientes();
  $oClientes->listar('ORDER BY id DESC');
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
          <a href="clientes_edt.php?n=novo"><img src="../comum/imagens/icones/add.png" alt="Adicionar" /></a>
          <span style="margin-left: 5px" class="bt_img remover"><img src="../comum/imagens/icones/cross.png" alt="Remover" /></span>
        </div>

        <table class="dataTable" style="z-index: 1">
          <thead>
            <tr class="header">
              <td style="width: 15px">&nbsp;</td>
              <td>Nome</td>
              <td>Situação</td>
              <td>Cidade/UF</td>
              <td>Data de cadastro</td>
            </tr>
          </thead>
          <tbody>
            <?php
              if ($oClientes->iLinhas > 0) {
                for ($i = 0; $i < $oClientes->iLinhas; $i++) {
                  $bLinha = $i%2 ? true : false;
                  ?>
                  <tr class="<?php echo ($bLinha) ? 'corSim' : 'corNao'; ?>">
                    <td class="multiCheck2">
                      <input type="checkbox" class="checkRemover" name="CMPremover_<?php echo $oClientes->oCli->ID[$i]; ?>" value="<?php echo $oClientes->oCli->ID[$i]; ?>" />
                    </td>
                    <td>
                      <a href="clientes_edt.php?n=<?php echo $oClientes->oCli->ID[$i]; ?>">
                        <span id="nome_reg_<?php echo $oClientes->oCli->ID[$i]; ?>">
                          <?php echo $oClientes->oCli->NM_CLIENTE[$i]; ?>
                        </span>
                      </a>
                    </td>
                    <td><?php echo $CFGaSituacao[$oClientes->oCli->CD_STATUS[$i]]; ?></td>
                    <td><?php echo $oClientes->oEnd->NM_CID[$i].'/'.$oClientes->oEnd->NM_UF[$i]; ?></td>
                    <td><?php echo $oClientes->oCli->DT_CAD[$i]; ?></td>
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