<?php
  session_start();
  $sPgAtual = 'descontos';

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
  
  $oLog = new tl_geral();
  $oLog->listar("WHERE cd_log = 'CLI_REG_LOG_SYS' ORDER BY id DESC");

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
      <div id="corpo">
        <?php
          $oAdmin->msgRetAlteracoes($aMsg);
          $oAdmin->breadCrumbs();
          //$oAdmin->minheight('600');
        ?>
        <div id="toolBar">
        </div> <?php
          if ($oLog->iLinhas > 0) { ?>

          <table class="dataTable" style="z-index: 1">
            <thead>
              <tr class="header">
                <td style="width: 15px">&nbsp;</td>
                <td>Descrição</td>
                <td>Data</td>
                <td>Hora</td>
                <td>IP</td>
              </tr>
            </thead>
            <tbody>
              <?php
                if ($oLog->iLinhas > 0) {
                  for ($i = 1; $i < $oLog->iLinhas; $i++) {
                    $bLinha = $i%2 ? true : false;
                    ?>
                    <tr class="<?php echo ($bLinha) ? 'corSim' : 'corNao'; ?>">
                      <td><?php echo $oLog->ID[$i]; ?></td>
                      <td><?php echo $oLog->NM_LOG[$i]; ?></td>
                      <td><?php echo $oLog->DT_CRI[$i]; ?></td>
                      <td><?php echo $oLog->HR_CRI[$i]; ?></td>
                      <td><?php echo $oLog->TX_IP[$i] ?></td>
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
          </table> <?php
        } else { ?>
         <div class="corSim" style="font-size: 12px; margin-top: 5px; padding: 5px;font-weight: bold">Nenhum registro</div> <?php 
        } ?>
      </div>
      <div class="limpa"></div>
      <?php
        $oAdmin->rodape($sPgAtual);
      ?>
    </div>
  </body>
</html>