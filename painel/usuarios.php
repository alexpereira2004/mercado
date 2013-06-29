<?php
  session_start();
  $sPgAtual = 'usuarios';

  include      '../modulosPHP/load.php';
  include      '../modulosPHP/config.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();

  if (isset ($_POST)) {
    $aMsg = $oAdmin->msgRetPost($_POST);

    if (isset($_POST['sAcao'])) {
      if ($_POST['sAcao'] == 'remover') {
        $sFiltro = implode(',', $_POST['CMPaId']);
        $oManUsu = new tc_usu_admin();
        $sWhere = "WHERE id IN (".$sFiltro.")";
        $oManUsu->remover($sWhere);
        $aMsg = $oManUsu->aMsg;
      }
    }
  }

  $sFiltro = '';
  $oUsuario = new usuario_admin();
  $oUsuario->listar($sFiltro);
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
          removerViaCheckBox('Deseja realmente excluir os usuários selecionados?', 'usuarios.php', 'remover');
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
          <a href="usuarios_edt.php?sAcao=novo"><img src="../comum/imagens/icones/add.png" alt="Adicionar" /></a>
          <span style="margin-left: 5px" class="bt_img remover"><img src="../comum/imagens/icones/cross.png" alt="Remover" /></span>
        </div>
        
        
          <table class="dataTable" style="z-index: 1">
            <thead>
              <tr>
                <td style="width: 25px">&nbsp;</td>
                <td>Nome</td>
                <td>Email</td>
              </tr>
            </thead>
            <tbody>
            <?php
              if ($oUsuario->iLinhas > 0) {
                for ($i = 0; $i < $oUsuario->iLinhas; $i++) {
                  $bLinha = $i%2 ? true : false;
                  ?>
                  <tr class="<?php echo ($bLinha) ? 'corSim' : 'corNao'; ?>">
                    <td class="multiCheck2">
                      <input type="checkbox" class="checkRemover" name="CMPremover_<?php echo $oUsuario->ID[$i]; ?>" value="<?php echo $oUsuario->ID[$i]; ?>" />
                    </td>
                    <td>
                      <a href="usuarios_edt.php?n=<?php echo $oUsuario->ID[$i]; ?>">
                        <span id="nome_reg_<?php echo $oUsuario->ID[$i]; ?>">
                          <?php echo $oUsuario->NM_USUARIO[$i]; ?>
                        </span>
                      </a>
                    </td>
                    <td><?php echo $oUsuario->TX_EMAIL[$i]; ?></td>
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