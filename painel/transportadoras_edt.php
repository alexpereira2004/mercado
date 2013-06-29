<?php
  session_start();
  $sPgAtual = 'transportadoras';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include_once '../modulosPHP/adapter.menu.php';
  include_once '../modulosPHP/class.excecoes.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tc_transportadoras.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  $oMenu = new adapter_tclv_menu();


  /**********************************************************************
    Cabeçalho de tratamento do formulário
  **********************************************************************/


  $oTransportadoras = new tc_transportadoras();

  if (isset($_POST['sAcao'])) {

    // Persistencia de dados na tela
    $oTransportadoras->inicializaAtributos();

    //Anti SQL injection
    foreach ($_POST as $sNome => $mValor) {
      if (!is_array($mValor)) {
        $_POST[$sNome] = $oAdmin->anti_sql_injection($mValor);
      }
    }

    // Manipulação dos dados
    $oManTransportadoras = new tc_transportadoras();
    $oManTransportadoras->inicializaAtributos();

    /*
     Campos com tratamento adicional
    */

    try {

      $oManTransportadoras->sBackpage = $CFGaPgAtual[$sPgAtual]['backPage'];
      $oManTransportadoras->salvar();
      $aMsg = $oManTransportadoras->aMsg;

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
        $oTransportadoras->listar('WHERE id = '.$iId);

        if ($oTransportadoras->iLinhas < 1) {
          throw new excecoes(15, $sPgAtual);
        }
      } else {
        $oTransportadoras->inicializaAtributos();
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
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'].' - '.(!isset($_GET['n']) ? 'Inserir novo registro' : 'Editar registro: '.$oTransportadoras->NM_TRANSPORTADORA[0]);?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script type="text/javascript" src="../modulosJS/maskedinput/jquery.maskedinput-1.3.min.js"></script>
    <script type="text/javascript" src="../modulosJS/jwysiwyg/jquery.wysiwyg.js"></script>
    <link rel="stylesheet" href="../modulosJS/jwysiwyg/jquery.wysiwyg.css" type="text/css" />
    <script type="text/javascript">
      $(document).ready(function(){
        $(".mask_telefone").mask("(99)9999-9999");
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
          $oAdmin->minheight('600');
        ?>
        <div id="toolBar">
          <a href="<?php echo $CFGaPgAtual[$sPgAtual]['backPage']; ?>"><img src="../comum/imagens/icones/doc_page_previous.png" alt="Voltar" /></a>
        </div>
        
        <form id="FRMtransportadoras" name="FRMtransportadoras" action="<?php echo $_SERVER['PHP_SELF'].(isset($_GET['n']) ? '?n='.$_GET['n'] : '');?> " method="post">
        <input type="hidden" name="sAcao" value="<?php echo (isset($_GET['n']) ? 'editar' : 'inserir'); ?>" />
        <input type="hidden" name="CMPtransportadoras-id" value="<?php echo $oTransportadoras->ID[0]; ?>" />
        <input type="hidden" name="CMPtransportadoras-endereco" value="0" />

          <table class="tab_lista_registros w98">
            <tr>
              <td class="infoheader w30">Transportadora:</td>
              <td class="infovalue w70"><input type="text" name="CMPtransportadoras-transportadora" value="<?php echo $oTransportadoras->NM_TRANSPORTADORA[0]; ?>" /></td>
            </tr>
            <tr>
              <td class="infoheader">Tel:</td>
              <td class="infovalue"><input type="text" name="CMPtransportadoras-tel" class="mask_telefone"  value="<?php echo $oTransportadoras->TX_TEL[0]; ?>" /></td>
            </tr>
<!--            
            <tr>
              <td class="infoheader">Endereco:</td>
              <td class="infovalue"><input type="text" name="CMPtransportadoras-endereco" value="<?php echo $oTransportadoras->ID_ENDERECO[0]; ?>" /></td>
            </tr>
-->
            <tr>
              <td class="infoheader">Obs:</td>
              <td class="infovalue">
                <textarea name="CMPtransportadoras-obs" rows="4" cols="20"><?php echo $oTransportadoras->TX_OBS[0]?></textarea>
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