<?php
  session_start();
  $sPgAtual = 'tags';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tc_tags.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  $oMenu = new adapter_tclv_menu();
  $oTags = new tc_tags();

  if (isset ($_POST['sAcao'])) {

    try {

      // Manipular dados
      $oManTags = new tc_tags();
      $oManTags->inicializaAtributos();
      $oManTags->ID[0]                  = $_POST['CMPid'];
      $oManTags->NM_TAG[0]              = $oAdmin->anti_sql_injection($_POST['CMPnome']);
      $oManTags->DE_TAG[0]              = $_POST['CMPdescricao'];
      $oManTags->TX_META_TITLE[0]       = $oAdmin->anti_sql_injection($_POST['CMPmetaTitle']);
      $oManTags->TX_META_DESCRIPTION[0] = $oAdmin->anti_sql_injection($_POST['CMPmetaDescription']);
      $oManTags->TX_KEYWORDS[0]         = $oAdmin->anti_sql_injection($_POST['CMPmetaKeywords']);
      $oManTags->TX_LINK[0]             = $oAdmin->montaUrlAmigavel($oManTags->NM_TAG[0], false);
      $oManTags->TX_SOUND[0]            = soundex($oManTags->NM_TAG[0]);
      $oManTags->NU_VISUALIZACOES[0]    = $_POST['CMPvisualizacoes'] == '' ? 0 : $_POST['CMPvisualizacoes'];

      // Validação dos campos obrigatórios
      $aValidar = array (0 => array('Nome'             , $_POST['CMPnome']),
                         1 => array('Descrição'        , $_POST['CMPdescricao']),
                         2 => array('Meta Title'       , $_POST['CMPmetaTitle']),
                         3 => array('Meta Description' , $_POST['CMPmetaDescription']),
                         4 => array('Palavras chaves'   , $_POST['CMPmetaKeywords']),

          );
      if ($oAdmin->valida_Preenchimento($aValidar) !== true) {
        $oTags = $oManTags;
        $aMsg = $oAdmin->aMsg;
        throw new Exception;
      }


      $sAcao = $_POST['sAcao'];

      /********** Novo registro **********/
      if ($sAcao == 'novo') {
        if ($oManTags->inserir() !== true) {
          $aMsg = $oManTags->aMsg;
          throw new Exception;
        }
        // Se inseriu com sucesso, manda para página de retorno
        $aCampos = $oManTags->aMsg;
        $aCampos['retMsg'] = true;
        $oAdmin->redirFRM($CFGaPgAtual[$sPgAtual]['backPage'], $aCampos);

      /********** Editar dados **********/
      } elseif ($sAcao == 'editar') {
        $oManTags->editar($oManTags->ID[0]);
        $oTags = $oManTags;
        $mResultado  = $oManTags->aMsg;
      }

    } catch (Exception $exc) {
      $oTags = $oManTags;
      $mResultado = $aMsg;
    }



  } else {

    try {
      if (isset($_GET['n'])) {
        $_GET['n'] = $oAdmin->anti_sql_injection($_GET['n']);

        // Editar conteúdo
        if(is_numeric($_GET['n'])) {
          $iId = $_GET['n'];
          $sFiltro = 'WHERE id = '.$iId;
          $oTags->listar($sFiltro);
          $sAcao = 'editar';
          if ($oTags->iLinhas != 1) {
            throw new Exception;
          }
        } else {

          // Adicionar conteúdo
          if ($_GET['n'] == 'novo') {
            $oTags->inicializaAtributos();
            $sAcao = 'novo';
          } else {
            throw new Exception;
          }
        }
      }
    } catch (Exception $exc) {
      $oAdmin->redirFRM($CFGaPgAtual[$sPgAtual]['backPage']);
      exit;
    }

  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'].' - '.($sAcao == 'inserir' ? 'Inserir novo registro' : 'Editar '.$oTags->NM_TAG[0]);?></title>
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
        </div>
        <form id="FRMtags" name="FRMtags" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" style="margin-top: 10px;">
          <input type="hidden" name="sAcao" value="<?php echo $sAcao; ?>" />
          <input type="hidden" name="CMPid" value="<?php echo $oTags->ID[0]; ?>" />
          <input type="hidden" name="CMPvisualizacoes" value="<?php echo $oTags->NU_VISUALIZACOES[0]; ?>" />
          <fieldset>
            <legend>Cadastro de Tags:</legend>
            <table>
              <tr>
                <td>Nome:</td>
                <td><input type="text" name="CMPnome" value="<?php echo $oTags->NM_TAG[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
                <td></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="4">Descrição:<?php echo $CFGtxObrigatorio; ?></td>
              </tr>
              <tr>
                <td colspan="4"><textarea name="CMPdescricao" cols="40" rows="5"><?php echo $oTags->DE_TAG[0]; ?></textarea></td>
              </tr>
              <tr><td colspan="4">Meta Title:(título para o Google)<?php echo $CFGtxObrigatorio; ?></td></tr>
              <tr><td colspan="4"><textarea name="CMPmetaTitle" cols="40" rows="5"><?php echo $oTags->TX_META_TITLE[0]; ?></textarea></td></tr>
              <tr><td colspan="4">Meta Description:(descrição para o Google)<?php echo $CFGtxObrigatorio; ?></td></tr>
              <tr><td colspan="4"><textarea name="CMPmetaDescription" cols="40" rows="5"><?php echo $oTags->TX_META_DESCRIPTION[0]; ?></textarea></td></tr>
              <tr><td colspan="4">Palavras chaves:(separar por vírgula)<?php echo $CFGtxObrigatorio; ?></td></tr>
              <tr><td colspan="4"><textarea name="CMPmetaKeywords" cols="40" rows="5"><?php echo $oTags->TX_KEYWORDS[0]; ?></textarea></td></tr>
            </table>
          </fieldset>
          <table style="margin-top: 20px">
            <tr>
              <td>&nbsp;</td>
              <td>
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