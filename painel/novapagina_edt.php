<?php
  session_start();
  $sPgAtual = 'novapagina';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tcctd_htmlgeral.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  $oMenu = new adapter_tclv_menu();
  $oHtmlGeral = new tcctd_htmlgeral();
  $mResultado = '';

  if (isset ($_POST['sAcao'])) {

    $oManHtmlGeral = new tcctd_htmlgeral();

    if($_POST['sAcao'] == 'remover') {
      $oManHtmlGeral->remover($_POST['CMPid']);
      if ($oManHtmlGeral->iCdMsg == 0) {
        $aCampos = $oManHtmlGeral->aMsg;
        $aCampos['retMsg'] = true;
        $oAdmin->redirFRM($CFGaPgAtual[$sPgAtual]['backPage'], $aCampos);
      }
    }

    try {

      // Validação dos campos obrigatórios


      $aValidar = array ( 0 => array('Url amigável'      , $_POST['CMPlink']      , 'text', true),
                          1 => array('Título da janela'  , $_POST['CMPtituJanela'], 'text', true),
                          2 => array('Descrição'         , $_POST['CMPdescricao'] , 'text', true),
                          3 => array('Keywords'          , $_POST['CMPtags']      , 'text', true),
                          3 => array('Conteúdo da página', $_POST['CMPconteudo']  , 'text', true),
          );

      // Manipular dados
      $oManHtmlGeral->ID[0]           = $_POST['CMPid'];
      $oManHtmlGeral->NM_PAGINA[0]    = $oAdmin->anti_sql_injection($_POST['CMPnome']);
      $oManHtmlGeral->TX_CONTEUDO[0]  = $_POST['CMPconteudo'];
      $oManHtmlGeral->TX_META_TITU[0] = $_POST['CMPtituJanela'];
      $oManHtmlGeral->DE_META_TAG[0]  = $_POST['CMPdescricao'];
      $oManHtmlGeral->TX_TAGS[0]      = $_POST['CMPtags'];
      $oManHtmlGeral->TX_LINK[0]      = $_POST['CMPlink'];
      $oManHtmlGeral->TP_SECAO[0]     = $_POST['CMPsecao'];
      $oManHtmlGeral->TX_ARQ_CSS[0]   = $_POST['CMParqCss'];

      $sAcao = $_POST['sAcao'];

      if ($oAdmin->valida_Preenchimento($aValidar) !== true) {
        $aMsg = $oAdmin->aMsg;
        throw new Exception;
      }


      /********** Novo registro **********/
      if ($sAcao == 'novo') {
        if ($oManHtmlGeral->inserir() !== true) {
          $mResultado = $oManHtmlGeral->aMsg;
          throw new Exception;
        }
        // Se inseriu com sucesso, manda para página de retorno
        $aCampos = $oManHtmlGeral->aMsg;
        $aCampos['retMsg'] = true;
        $oAdmin->redirFRM($CFGaPgAtual[$sPgAtual]['backPage'], $aCampos);

      /********** Editar dados **********/
      } elseif ($sAcao == 'editar') {
        $oManHtmlGeral->ID[0] = $_POST['CMPid'];
        $oManHtmlGeral->editar($oManHtmlGeral->ID[0]);
        $oHtmlGeral = $oManHtmlGeral;
        $mResultado = $oManHtmlGeral->aMsg;
      }

    } catch (Exception $exc) {

      $oHtmlGeral = $oManHtmlGeral;
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
          $oHtmlGeral->listar($sFiltro);
          $sAcao = 'editar';
          if ($oHtmlGeral->iLinhas != 1) {
            throw new Exception;
          }
        } else {

          // Adicionar conteúdo
          if ($_GET['n'] == 'novo') {
            $oHtmlGeral->inicializaAtributos();
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

  if ($mResultado == '') {
    $mResultado = $oAdmin->msgRetPost($_POST);
  }
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'].' - '.($sAcao == 'inserir' ? 'Inserir novo registro' : 'Editar '.$oHtmlGeral->NM_PAGINA[0]);?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script type="text/javascript" src="../modulosJS/jwysiwyg/jquery.wysiwyg.js"></script>
    <link rel="stylesheet" href="../modulosJS/jwysiwyg/jquery.wysiwyg.css" type="text/css" />
    <script type="text/javascript">
      $(document).ready(function(){
        $('#CMPconteudo').wysiwyg({ });

        $('#BTsalvarParametros').click(function(){
          enviarForm('FRMnovapagina');
        });
        $('#BTexcluirPagina').click(function(){
          sDecisao = confirm("Você realmente deseja apagar esse registro?");
          if (sDecisao){
            $('#sAcao').val('remover');
            $('#FRMnovapagina').submit();
          }
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
          $oAdmin->msgRetAlteracoes($mResultado);
          $oAdmin->breadCrumbs();
          $oAdmin->minheight('600');
        ?>
        <div id="toolBar">
          <a href="<?php echo $CFGaPgAtual[$sPgAtual]['backPage']; ?>"><img src="../comum/imagens/icones/doc_page_previous.png" alt="Voltar" /></a>
          <a href="#" id="BTsalvarParametros"><img src="../comum/imagens/icones/disk.png" alt="icon" title="Salvar as alterações" /></a>
          <a href="#" id="BTexcluirPagina">   <img src="../comum/imagens/icones/delete.png" alt="Excluir" title="Excluir esta página" /></a><br />
        </div>

        <form id="FRMnovapagina" name="FRMnovapagina" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" style="margin-top: 10px;">
          <input type="hidden" name="sAcao" id="sAcao" value="<?php echo $sAcao; ?>" />
          <input type="hidden" name="CMPid" value="<?php echo $oHtmlGeral->ID[0]; ?>" />
          <input type="hidden" name="CMPsecao" value="<?php echo $oHtmlGeral->TP_SECAO[0]; ?>" />
          <input type="hidden" name="CMPnome" value="<?php echo $oHtmlGeral->NM_PAGINA[0]; ?>" />
          <fieldset>
            <legend>Cadastro de Páginas:</legend>

            <table style="width: 98%; padding: 20px;">
              <tr>
                <td colspan="2"><h3><?php echo $oHtmlGeral->NM_PAGINA[0]; ?></h3></td>
              </tr>
              <tr>
                <td>Subseção:</td>
                <td>
                  <?php
                    echo $oHtmlGeral->TP_SECAO[0];
                  ?>
                </td>
              </tr>
              <tr>
                <td class="w02">Url amigável:</td>
                <td><input class="w06" type="text" name="CMPlink" value="<?php echo $oHtmlGeral->TX_LINK[0];?>" /><?php echo $CFGtxObrigatorio; ?></td>
              </tr>
              <tr>
                <td class="w02">Link para página:</td>
                <td>
                  <a href="<?php echo $oAdmin->sUrlBase.'/'.$oHtmlGeral->TP_SECAO[0].'/'.$oHtmlGeral->TX_LINK[0]; ?>">
                    <?php echo $oAdmin->sUrlBase.'/'.$oHtmlGeral->TP_SECAO[0].'/'.$oHtmlGeral->TX_LINK[0]; ?>
                  </a>
                </td>
              </tr>
              <tr>
                <td class="w02">Título da janela:</td>
                <td><input class="w06" type="text" name="CMPtituJanela" value="<?php echo $oHtmlGeral->TX_META_TITU[0];?>" /><?php echo $CFGtxObrigatorio; ?></td>
              </tr>
            </table>

            <table>
              <tr>
                <td colspan="2">Descrição da página: <strong>(meta tag description)</strong><?php echo $CFGtxObrigatorio; ?></td>
              </tr>
              <tr>
                <td>
                  <textarea name="CMPdescricao" cols="80" rows="2" class="w095"><?php echo $oHtmlGeral->DE_META_TAG[0]; ?></textarea>
                </td>
              </tr>

              <tr>
                <td colspan="2">Keywords: <strong>(separar tags por vírgula)</strong><?php echo $CFGtxObrigatorio; ?></td>
              </tr>
              <tr>
                <td>
                  <textarea name="CMPtags" cols="80" rows="2" class="w095"><?php echo $oHtmlGeral->TX_TAGS[0]; ?></textarea>
                </td>
              </tr>
              <tr>
                <td colspan="2">Conteúdo da página:<?php echo $CFGtxObrigatorio; ?></td>
              </tr>
              <tr>
                <td>
                  <textarea id="CMPconteudo" name="CMPconteudo" cols="80" rows="5" class="w095"><?php echo $oHtmlGeral->TX_CONTEUDO[0]; ?></textarea>
                <?php
//                  $oFCKeditor = new FCKeditor('CMPconteudo') ;
//                  $oFCKeditor->BasePath = 'fckeditor/' ;
//                  $oFCKeditor->Width = 800 ;
//                  $oFCKeditor->Height = 600 ;
//                  $oFCKeditor->Value = $oHtmlGeral->TX_CONTEUDO[0];
//                  $oFCKeditor->Create() ;
                ?>
                </td>
              </tr>
              <tr>
                <td>Arquivos de Estilo:<input class="w06" type="text" name="CMParqCss" value="<?php echo $oHtmlGeral->TX_ARQ_CSS[0];?>" /></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
              </tr>
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