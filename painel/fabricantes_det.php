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
  $oFabricantes = new tc_prod_fabricantes();

  try {
    if (isset($_GET['n'])) {
      $_GET['n'] = $oAdmin->anti_sql_injection($_GET['n']);

      // Editar conteúdo
      if(is_numeric($_GET['n'])) {
        $iId = $_GET['n'];
        $sFiltro = 'WHERE id = '.$iId;
        $oFabricantes->listar($sFiltro);
        $sAcao = 'editar';
        if ($oFabricantes->iLinhas != 1) {
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
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'].' - '.$oFabricantes->NM_FABRICANTE[0];?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script type="text/javascript" src="../modulosJS/jwysiwyg/jquery.wysiwyg.js"></script>
    <link rel="stylesheet" href="../modulosJS/jwysiwyg/jquery.wysiwyg.css" type="text/css" />
    <script type="text/javascript">
      $(document).ready(function(){

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
          $oAdmin->minheight('600');
        ?>
        <div id="toolBar">
          <a href="<?php echo $CFGaPgAtual[$sPgAtual]['backPage']; ?>"><img src="../comum/imagens/icones/doc_page_previous.png" alt="Voltar" /></a>
          <a href="fabricantes_edt.php?n=<?php echo $oFabricantes->ID[0]; ?>"><img src="../comum/imagens/icones/hammer.png" alt="Editar" /></a>
        </div>

        <table style="margin-top: 10px;" class="tab_lista_registros">
          <tr class="corSim">
            <td class="headerValue w10">Nome:</td>
            <td class="infoValue"><?php echo $oFabricantes->NM_FABRICANTE[0]; ?></td>
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