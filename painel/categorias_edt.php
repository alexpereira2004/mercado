<?php
  session_start();
  $sPgAtual = 'categorias';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tc_prod_categorias.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  $oMenu = new adapter_tclv_menu();
  $oCategorias = new tc_prod_categorias();

  if (isset ($_POST['sAcao'])) {

    try {

      // Manipular dados
      $oManCategorias = new tc_prod_categorias();
      $oManCategorias->inicializaAtributos();
      $oManCategorias->ID[0]                  = $_POST['CMPid'];
      $oManCategorias->NM_CATEGORIA[0]        = $oAdmin->anti_sql_injection($_POST['CMPnome']);
      $oManCategorias->CD_STATUS[0]           = isset($_POST['CMPstatus']) ? 'A' : 'I';
      $oManCategorias->DE_CATEGORIA[0]        = $_POST['CMPdescricao'];
      $oManCategorias->TX_META_TITLE[0]       = $oAdmin->anti_sql_injection($_POST['CMPmetaTitle']);
      $oManCategorias->TX_META_DESCRIPTION[0] = $oAdmin->anti_sql_injection($_POST['CMPmetaDescription']);
      $oManCategorias->TX_KEYWORDS[0]         = $oAdmin->anti_sql_injection($_POST['CMPmetaKeywords']);
      $oManCategorias->TX_LINK[0]             = $oAdmin->montaUrlAmigavel($oManCategorias->NM_CATEGORIA[0], false);
      $oManCategorias->TX_SOUND[0]            = soundex($oManCategorias->NM_CATEGORIA[0]);
      $oManCategorias->NU_VISUALIZACOES[0]    = $_POST['CMPvisualizacoes'] == '' ? 0 : $_POST['CMPvisualizacoes'];
      
      // Validação dos campos obrigatórios
      $aValidar = array (0 => array('Nome'             , $_POST['CMPnome']),
                         1 => array('Descrição'        , $_POST['CMPdescricao']),
                         2 => array('Meta Title'       , $_POST['CMPmetaTitle']),
                         3 => array('Meta Description' , $_POST['CMPmetaDescription']),
                         4 => array('Palavras chaves'   , $_POST['CMPmetaKeywords']),

          );
      if ($oAdmin->valida_Preenchimento($aValidar) !== true) {
        $oCategorias = $oManCategorias;
        $aMsg = $oAdmin->aMsg;
        throw new Exception;
      }

      $sAcao = $_POST['sAcao'];

      /********** Novo registro **********/
      if ($sAcao == 'novo') {
        if ($oManCategorias->inserir() !== true) {
          $aMsg = $oManCategorias->aMsg;
          throw new Exception;
        }
        // Se inseriu com sucesso, manda para página de retorno
        $aCampos = $oManCategorias->aMsg;
        $aCampos['retMsg'] = true;
        $oAdmin->redirFRM($CFGaPgAtual[$sPgAtual]['backPage'], $aCampos);

      /********** Editar dados **********/
      } elseif ($sAcao == 'editar') {       
        $oManCategorias->editar($oManCategorias->ID[0]);
        $oCategorias = $oManCategorias;
        $mResultado  = $oManCategorias->aMsg;
      }

    } catch (Exception $exc) {
      $oCategorias = $oManCategorias;
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
          $oCategorias->listar($sFiltro);
          $sAcao = 'editar';
          if ($oCategorias->iLinhas != 1) {
            throw new Exception;
          }
        } else {

          // Adicionar conteúdo
          if ($_GET['n'] == 'novo') {
            $oCategorias->inicializaAtributos();
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
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'].' - '.($sAcao == 'inserir' ? 'Inserir novo registro' : 'Editar '.$oCategorias->NM_CATEGORIA[0]);?></title>
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
        <form id="FRMcategorias" name="FRMcategorias" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" style="margin-top: 10px;">
          <input type="hidden" name="sAcao" value="<?php echo $sAcao; ?>" />
          <input type="hidden" name="CMPid" value="<?php echo $oCategorias->ID[0]; ?>" />
          <input type="hidden" name="CMPvisualizacoes" value="<?php echo $oCategorias->NU_VISUALIZACOES[0]; ?>" />
          <fieldset>
            <legend>Cadastro de Categoria:</legend>
            <table>
              <tr>
                <td class="infoheader">Nome:</td>
                <td><input type="text" name="CMPnome" value="<?php echo $oCategorias->NM_CATEGORIA[0]; ?>" /></td>
                <td class="infoheader">Ativar categoria:</td>
                <td><input type="checkbox" name="CMPstatus" value="1" <?php echo $oCategorias->CD_STATUS[0] == 'A' ? 'checked="checked"' : ''; ?> /></td>
              </tr>
              <tr>
                <td colspan="4" class="infoheader">Descrição:</td>
              </tr>
              <tr>
                <td colspan="4"><textarea name="CMPdescricao" cols="40" rows="5"><?php echo $oCategorias->DE_CATEGORIA[0]; ?></textarea></td>
              </tr>
              <tr><td colspan="4" class="infoheader">Meta Title:(título para o Google)</td></tr>
              <tr><td colspan="4"><input type="text" name="CMPmetaTitle" value="<?php echo $oCategorias->TX_META_TITLE[0]; ?>" /></td></tr>
              <tr><td colspan="4" class="infoheader">Meta Description:(descrição para o Google)</td></tr>
              <tr><td colspan="4"><textarea name="CMPmetaDescription" cols="40" rows="5"><?php echo $oCategorias->TX_META_DESCRIPTION[0]; ?></textarea></td></tr>
              <tr><td colspan="4" class="infoheader">Palavras chaves:(separar por vírgula)</td></tr>
              <tr><td colspan="4"><textarea name="CMPmetaKeywords" cols="40" rows="5"><?php echo $oCategorias->TX_KEYWORDS[0]; ?></textarea></td></tr>
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