<?php
  session_start();
  $sPgAtual = 'fabricantes';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include_once '../modulosPHP/adapter.menu.php';
  include_once '../modulosPHP/class.excecoes.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tc_prod_fabricantes.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  $oMenu = new adapter_tclv_menu();


  /**********************************************************************
    Cabeçalho de tratamento do formulário
  **********************************************************************/
    $oFabricantes = new tc_prod_fabricantes();

    if (isset($_POST['sAcao'])) {

      // Persistencia de dados na tela
      $oFabricantes->inicializaAtributos();

      //Anti SQL injection
      foreach ($_POST as $sNome => $mValor) {
        if (!is_array($mValor)) {
          $_POST[$sNome] = $oAdmin->anti_sql_injection($mValor);
        }
      }

      // Manipulação dos dados
      $oManFabricantes = new tc_prod_fabricantes();
      $oManFabricantes->inicializaAtributos();

      /*
       Campos com tratamento adicional
      */
      $oManFabricantes->NU_VISUALIZACOES[0]    = $_POST['CMPprod-fabricantes-visualizacoes'] == '' ? 0 : $_POST['CMPprod-fabricantes-visualizacoes'];
      $oManFabricantes->TX_SOUND[0]            = soundex($_POST['CMPprod-fabricantes-fabricante']);
      $oManFabricantes->CD_STATUS[0]           = (isset ($_POST['CMPprod-fabricantes-status']) ? 'A' : 'I');
      $oManFabricantes->TX_LINK[0]             = $oAdmin->montaUrlAmigavel( $_POST['CMPprod-fabricantes-fabricante'], false);

      try {

        $oManFabricantes->sBackpage = $CFGaPgAtual[$sPgAtual]['backPage'];
        $oManFabricantes->salvar();
        $aMsg = $oManFabricantes->aMsg;

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
          $oFabricantes->listar('WHERE id = '.$iId);

          if ($oFabricantes->iLinhas < 1) {
            throw new excecoes(15, $sPgAtual);
          }
        } else {
          $oFabricantes->inicializaAtributos();
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
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'].' - '.(!isset($_GET['n']) ? 'Inserir novo registro' : 'Editar registro: '.$oFabricantes->NM_FABRICANTE[0]);?></title>
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
          $oAdmin->msgRetAlteracoes($aMsg);
          $oAdmin->breadCrumbs();
          $oAdmin->minheight('600');
        ?>
        <div id="toolBar">
          <a href="<?php echo $CFGaPgAtual[$sPgAtual]['backPage']; ?>"><img src="../comum/imagens/icones/doc_page_previous.png" alt="Voltar" /></a>
        </div>
        
        <form id="FRMprod-fabricantes" name="FRMprod-fabricantes" action="<?php echo $_SERVER['PHP_SELF'].(isset($_GET['n']) ? '?n='.$_GET['n'] : '');?> " method="post">
          <input type="hidden" name="sAcao" value="<?php echo (isset($_GET['n']) ? 'editar' : 'inserir'); ?>" />
          <input type="hidden" name="CMPprod-fabricantes-id" value="<?php echo $oFabricantes->ID[0]; ?>" />
          <input type="hidden" name="CMPprod-fabricantes-visualizacoes" value="<?php echo $oFabricantes->NU_VISUALIZACOES[0]; ?>" />
          <fieldset>
            <legend>Cadastro de Fabricante</legend>
            <table class="tab_lista_registros">
              <tr>
                <td class="infoheader">Fabricante*:</td>
                <td class="infovalue"><input type="text" name="CMPprod-fabricantes-fabricante" value="<?php echo $oFabricantes->NM_FABRICANTE[0]; ?>" /></td>
              </tr>
              <tr>
                <td class="infoheader">Ativar Fabricante:</td>
                <td class="infovalue"><input type="checkbox" name="CMPprod-fabricantes-status" value="A" <?php echo $oFabricantes->CD_STATUS[0] == 'A' ? 'checked="checked"' : ''; ?> /></td>
              </tr>
              <tr>
                <td class="infoheader">Descrição*:</td>
                <td class="infovalue">
                  <textarea name="CMPprod-fabricantes-descricao" rows="5" cols="40"><?php echo $oFabricantes->DE_FABRICANTE[0]; ?></textarea>
                </td>
              </tr>
              <tr>
                <td class="infoheader">Meta-title*:</td>
                <td class="infovalue"><input type="text" name="CMPprod-fabricantes-meta-title" value="<?php echo $oFabricantes->TX_META_TITLE[0]; ?>" /></td>
              </tr>
              <tr>
                <td class="infoheader">Meta-description*:</td>
                <td class="infovalue">
                  <textarea name="CMPprod-fabricantes-meta-description" cols="40" rows="5"><?php echo $oFabricantes->TX_META_DESCRIPTION[0]; ?></textarea>
                </td>
              </tr>
              <tr>
                <td class="infoheader">Keywords*:</td>
                <td class="infovalue"><input type="text" name="CMPprod-fabricantes-keywords" value="<?php echo $oFabricantes->TX_KEYWORDS[0]; ?>" /></td>
              </tr>
              <tr>
                <td colspan="2">
                  <input class="bt" type="reset" value="Limpar" />
                  <input class="bt" type="submit" value="Salvar" />
                </td>
              </tr>
            </table>
          </fieldset>
        </form>
      </div>
      <div class="limpa"></div>
      <?php
        $oAdmin->rodape($sPgAtual);
      ?>
    </div>
  </body>
</html>