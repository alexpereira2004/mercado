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

  if (isset ($_POST['sAcao'])) {

    $sAcao = $_POST['sAcao'];

    try {

      $oManHtmlGeral = new tcctd_htmlgeral();

      if ($sAcao == 'novo' || $sAcao == 'editar') {
       
        // Manipular dados
        $oManHtmlGeral->inicializaAtributos();
        $oManHtmlGeral->ID[0]                  = $_POST['CMPid'];
        $oManHtmlGeral->NM_PAGINA[0]              = $oAdmin->anti_sql_injection($_POST['CMPnome']);
        $oManHtmlGeral->TP_SECAO[0]               = $_POST['CMPsecao'];
        $oManHtmlGeral->TX_LINK[0]                = $oAdmin->montaUrlAmigavel($_POST['CMPnome']);

        // Validação dos campos obrigatórios
        $aValidar = array (0 => array('Nome'         , $_POST['CMPnome'], 'text', true),
                           1 => array('Seção'        , $_POST['CMPsecao'], 'text', true),

            );
        if ($oAdmin->valida_Preenchimento($aValidar) !== true) {
          $oHtmlGeral = $oManHtmlGeral;
          $aMsg = $oAdmin->aMsg;
          throw new Exception;
        }
      }
     
      /********** Novo registro **********/
      if ($sAcao == 'novo') {
        if ($oManHtmlGeral->inserir() !== true) {
          $aMsg = $oManHtmlGeral->aMsg;
          throw new Exception;
        }
        // Se inseriu com sucesso, manda para página de retorno
        $mResultado = $oManHtmlGeral->aMsg;
        $mResultado['sMsg'] = 'A nova página foi criada com sucesso, agora você deve edita-la';
        $oHtmlGeral->inicializaAtributos();
        $aRetDB = $oAdmin->pegaInfoDB('tcctd_htmlgeral', 'max(id)');
        $mResultado['retMsg'] = true;

        $oAdmin->redirFRM('novapagina_edt.php?n='.$aRetDB[0], $mResultado);

      /********** Editar dados **********/
      } elseif ($sAcao == 'editar') {
        $oManHtmlGeral->editar($oManHtmlGeral->ID[0]);
        $oHtmlGeral = $oManHtmlGeral;
        $mResultado  = $oManHtmlGeral->aMsg;

      /********** Remove um registro Htmlgeral **********/
      } elseif ($sAcao == 'remover') {
        $oHtmlGeral->inicializaAtributos();
        //$oManHtmlGeral->remover('WHERE id IN ('.implode(',', $_POST['CMPaId']).') ');
        //$aMsg  = $oManHtmlGeral->aMsg;
      }

    } catch (Exception $exc) {
      $oHtmlGeral = $oManHtmlGeral;
    }



  } else {
    $oHtmlGeral->inicializaAtributos();
    $sAcao = 'novo';
  }

  $oHtmlListagem = new tcctd_htmlgeral();
  $oHtmlListagem->listar('ORDER BY id desc');

  $aMsg = $oAdmin->msgRetPost($aMsg);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'].' - Inserir novo registro';?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script type="text/javascript" src="../modulosJS/jwysiwyg/jquery.wysiwyg.js"></script>
    <link rel="stylesheet" href="../modulosJS/jwysiwyg/jquery.wysiwyg.css" type="text/css" />
    <script type="text/javascript">
      $(document).ready(function() {
        $('.dataTable').dataTable({
          "iDisplayLength": 25
        });
        
        $('.remover').click(function(){
          removerViaCheckBox('Deseja realmente excluir a(s) página(s) selecionada(s)?', 'novapagina.php', 'remover');
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
          <a href="<?php echo $CFGaPgAtual[$sPgAtual]['backPage']; ?>"><img src="../comum/imagens/icones/doc_page_previous.png" alt="Voltar" /></a>
          <span style="margin-left: 5px" class="bt_img remover"><img src="../comum/imagens/icones/cross.png" alt="Remover" /></span>          
        </div>

        <form id="FRMnovapagina" name="FRMnovapagina" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" style="margin-top: 10px;">
          <input type="hidden" name="sAcao" value="<?php echo $sAcao == 'novo' || $sAcao == 'editar' ? $sAcao : 'novo'; ?>" />
          <input type="hidden" name="CMPid" value="0" />
          <fieldset>
            <legend>Cadastro de Nova Página:</legend>
            <table>
              <tr>
                <td>Nome:</td>
                <td><input type="text" name="CMPnome" value="<?php echo $oHtmlGeral->NM_PAGINA[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
              </tr>
              <tr>
                <td>Seção:</td>
                <td>
                  <?php
                    $oAdmin->montaSelect('CMPsecao', $CFGaSecoesHtml, $oHtmlGeral->TP_SECAO[0], true, '','', 'Selecione a seção');
                    echo $CFGtxObrigatorio;
                  ?>
                </td>
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
       
        <div class="limpa"></div>
        <br />

        <table class="dataTable" style="z-index: 1;" >
          <thead>
            <tr>
              <td style="width: 15px">&nbsp;</td>
              <td>Nome</td>
              <td>Visualizar</td>
            </tr>
          </thead>
          <tbody>
          <?php
            if ($oHtmlListagem->iLinhas > 0) {
              for ($i = 0; $i < $oHtmlListagem->iLinhas; $i++) {
                $bLinha = $i%2 ? true : false;
                ?>
                <tr class="<?php echo ($bLinha) ? 'corSim' : 'corNao'; ?>">
                  <td class="multiCheck2">
                    <input type="checkbox" class="checkRemover" name="CMPremover_<?php echo $oHtmlListagem->ID[$i]; ?>" value="<?php echo $oHtmlListagem->ID[$i]; ?>" />
                  </td>
                  <td>
                    <a href="novapagina_edt.php?n=<?php echo $oHtmlListagem->ID[$i]; ?>">
                      <span id="nome_reg_<?php echo $oHtmlListagem->ID[$i]; ?>">
                        <?php echo $oHtmlListagem->NM_PAGINA[$i]; ?>
                      </span>
                    </a>
                  </td>
                  <td>
                    <a href="<?php echo $oAdmin->sUrlBase.'/'.$oHtmlListagem->TP_SECAO[$i].'/'.$oHtmlListagem->TX_LINK[$i]; ?>">
                      <?php echo $oAdmin->sUrlBase.'/'.$oHtmlListagem->TP_SECAO[$i].'/'.$oHtmlListagem->TX_LINK[$i]; ?>
                    </a>
                  </td>
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

      </div>
      <div class="limpa"></div>
      <?php
        $oAdmin->rodape($sPgAtual);
      ?>
    </div>
  </body>
</html>