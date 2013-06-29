<?php
  session_start();
  $sPgAtual = 'usuarios';

  include      '../modulosPHP/load.php';
  include      '../modulosPHP/config.php';
  include_once '../modulosPHP/adapter.clientes.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();

  $oAdmin = new admin();
  $oUsuarios = new usuario_admin();

  try {
    if (isset($_GET['n'])) {
      $_GET['n'] = $oAdmin->anti_sql_injection($_GET['n']);

      // Editar conteúdo
      if(is_numeric($_GET['n'])) {
        $iId = $_GET['n'];
        $sFiltro = 'WHERE id = '.$iId;
        $oUsuarios->listar($sFiltro);
        $sAcao = 'editar';
        if ($oUsuarios->iLinhas != 1) {
          throw new Exception;
        }
      }
    }
  } catch (Exception $exc) {
    $oAdmin->redirFRM($CFGaPgAtual[$sPgAtual]['backPage']);
    exit;
  }
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
          $oAdmin->msgRetAlteracoes($mResultado);
          $oAdmin->breadCrumbs();
          //$oAdmin->minheight('600');
        ?>
        <div id="toolBar">
          <a href="<?php echo $CFGaPgAtual[$sPgAtual]['backPage']; ?>"><img src="../comum/imagens/icones/doc_page_previous.png" alt="Voltar" /></a>
          <a href="usuarios_edt.php?sAcao=editar&n=<?php echo $oUsuarios->ID[0]; ?>"><img src="../comum/imagens/icones/hammer.png" alt="Editar" /></a>
        </div>

        <table class="w98 tab_lista_registros">
          <tr class="corSim">
            <td class="infoheader" style="width: 120px">Nome</td>
            <td><?php echo $oUsuarios->NM_USUARIO[0]; ?></td>
          </tr>
          <tr class="corNao">
            <td class="infoheader">Email</td>
            <td class="infoValue"><?php echo $oUsuarios->TX_EMAIL[0]; ?></td>
          </tr>
          <tr class="corSim ">
            <td class="infoheader">Nível</td>
            <td>
              <?php
                echo $CFGaNiveisUsuarios[$oUsuarios->CD_NIVEL[0]];
                ?>
            </td>
          </tr>
        </table>
      </div>
      <div class="limpa"></div>
      <?php
        $oAdmin->rodape($sPgAtual);
      ?>
    </div>
  </body>
</html>