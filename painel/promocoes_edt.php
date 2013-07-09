<?php
  session_start();
  $sPgAtual = 'promocoes';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include_once '../modulosPHP/adapter.menu.php';
  include_once '../modulosPHP/class.excecoes.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tc_promocoes.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  $oMenu = new adapter_tclv_menu();

  /**********************************************************************
    Cabeçalho de tratamento do formulário
  **********************************************************************/
  $oPromocoes = new tc_promocoes();

  if (isset($_POST['sAcao'])) {

    // Persistencia de dados na tela
    $oPromocoes->inicializaAtributos();

    //Anti SQL injection
    foreach ($_POST as $sNome => $mValor) {
      if (!is_array($mValor)) {
        $_POST[$sNome] = $oAdmin->anti_sql_injection($mValor);
      }
    }

    // Manipulação dos dados
    $oManPromocoes = new tc_promocoes();
    $oManPromocoes->inicializaAtributos();

    /*
     Campos com tratamento adicional
    */

    try {

      $oManPromocoes->sBackpage = $CFGaPgAtual[$sPgAtual]['backPage'];
      $oManPromocoes->salvar();
      $aMsg = $oManPromocoes->aMsg;

    } catch (excecoes $e) {
      $aMsg = $e->aMsg;
    }

  } else {
    try {
      if (isset ($_REQUEST['n'])) {

        if (!is_numeric($_REQUEST['n'])) {
          throw new excecoes(10, $sPgAtual);
        }

        $iId = $oAdmin->anti_sql_injection($_REQUEST['n']);
        $oPromocoes->listar('WHERE id = '.$iId);

        if ($oPromocoes->iLinhas < 1) {
          throw new excecoes(15, $sPgAtual);
        }
      } else {
        $oPromocoes->inicializaAtributos();
      }

    } catch (excecoes $e) {

      $e->getErrorByCode();
      header('location:'.$CFGaPgAtual[$sPgAtual]['backPage']);
      exit;
    }
  }


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'].' - '.(!isset($_GET['n']) ? 'Inserir novo registro' : 'Editar registro: '.$oPromocoes->NM_PROMOCAO[0]);?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script type="text/javascript" src="../modulosJS/jwysiwyg/jquery.wysiwyg.js"></script>
    <link rel="stylesheet" href="../modulosJS/jwysiwyg/jquery.wysiwyg.css" type="text/css" />
    <script type="text/javascript" src="../modulosJS/maskedinput/jquery.maskedinput-1.3.min.js"></script>
    <script type="text/javascript">

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
          $oAdmin->minheight('600');
        ?>
        <div id="toolBar">
          <a href="<?php echo $CFGaPgAtual[$sPgAtual]['backPage']; ?>"><img src="../comum/imagens/icones/doc_page_previous.png" alt="Voltar" /></a>
        </div>

        <form id="FRMpromocoes" name="FRMpromocoes" action="<?php echo $_SERVER['PHP_SELF'].(isset($_GET['n']) ? '?n='.$_GET['n'] : '');?> " method="post">
          <input type="hidden" name="sAcao" value="<?php echo (isset($_GET['n']) ? 'editar' : 'inserir'); ?>" />
          <input type="hidden" name="CMPpromocoes-id" value="<?php echo $oPromocoes->ID[0]; ?>" />
          <table class="tab_lista_registros">

            <tr>
              <td class="infoheader">Nome:</td>
              <td class="infovalue"><input type="text" name="CMPpromocoes-promocao" value="<?php echo $oPromocoes->NM_PROMOCAO[0]; ?>" /></td>
            </tr>
            <tr>
              <td class="infoheader">Descrição:</td>
              <td class="infovalue">
                <textarea name="CMPpromocoes-desc" cols="40" rows="5"><?php echo $oPromocoes->DE_PROMOCAO[0]; ?></textarea>
              </td>
            </tr>
            <tr>
              <td class="infoheader">Desconto:</td>
              <td class="infovalue">  
                <?php
                  $oAdmin->montaSelectDB('CMPpromocoes-desconto', 'tc_descontos', 'id', 'nm_desconto', $oPromocoes->ID_DESCONTO[0], 'CMPpromocoes-desconto');
                ?>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                <input class="bt" type="reset" value="Limpar" />
                <input class="bt" type="submit" value="Salvar" />
              </td>
            </tr>
          </table>
        </form>
      </div>
      <div class="limpa"></div>
      <?php
        $oAdmin->rodape($sPgAtual);
      ?>
    </div>
  </body>
</html>