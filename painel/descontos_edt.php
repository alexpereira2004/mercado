<?php
  session_start();
  $sPgAtual = 'descontos';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include_once '../modulosPHP/adapter.menu.php';
  include_once '../modulosPHP/class.excecoes.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tc_descontos.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  $oMenu = new adapter_tclv_menu();

  /**********************************************************************
    Cabeçalho de tratamento do formulário
  **********************************************************************/
  $oDescontos = new tc_descontos();

  if (isset($_POST['sAcao'])) {

    // Persistencia de dados na tela
    $oDescontos->inicializaAtributos();

    //Anti SQL injection
    foreach ($_POST as $sNome => $mValor) {
      if (!is_array($mValor)) {
        $_POST[$sNome] = $oAdmin->anti_sql_injection($mValor);
      }
    }

    // Manipulação dos dados
    $oManDescontos = new tc_descontos();
    $oManDescontos->inicializaAtributos();

    /*
     Campos com tratamento adicional
    */

    try {
  
      $oManDescontos->sBackpage = $CFGaPgAtual[$sPgAtual]['backPage'];
      $oManDescontos->ID_USU_CAD[0] = $_SESSION[usuario_admin::getEmpresa()]['id_usu'];
      $oManDescontos->ID_USU_ATU[0] = $_SESSION[usuario_admin::getEmpresa()]['id_usu'];
      $oManDescontos->DT_VIGENCIA_INICIO[0] = $_POST['CMPdescontos-vigencia-inicio'] != '' ? "'".$oAdmin->parseValue($_POST['CMPdescontos-vigencia-inicio'], 'dt-bd')."'" : 'null';
      $oManDescontos->DT_VIGENCIA_FIM[0]    = $_POST['CMPdescontos-vigencia-fim']    != '' ? "'".$oAdmin->parseValue($_POST['CMPdescontos-vigencia-fim'], 'dt-bd')."'" : 'null';

      $_POST['CMPdescontos-status']         = isset($_POST['CMPdescontos-status']) ? 'A' : 'I';
      $oManDescontos->CD_STATUS[0]          = $_POST['CMPdescontos-status'];
      
      // Valor Mínimo para disparar o desconto
      $sSgTipoDesc               = $_POST['CMPdescontos-tipo-desc'];
      $_POST['CMPdescontos-min'] = (!isset($_POST['CMPdescontos-min-'.$sSgTipoDesc])) ? 0 : $_POST['CMPdescontos-min-'.$sSgTipoDesc];
      $oDescontos->VL_MIN[0]     = $_POST['CMPdescontos-min'];
      $_POST['CMPdescontos-min'] = $oAdmin->parseValue($_POST['CMPdescontos-min'], 'moeda-db');
      $oManDescontos->VL_MIN[0]  = $_POST['CMPdescontos-min'];

      // Valor do Desconto
      $sSgTipoValor                  = strtolower($_POST['CMPdescontos-tipo']);
      $_POST['CMPdescontos-valor']   = $_POST['CMPdescontos-valor-'.$sSgTipoValor];
      $oDescontos->VL_DESCONTO[0]    = $_POST['CMPdescontos-valor'];
      $_POST['CMPdescontos-valor']   = $oAdmin->parseValue($_POST['CMPdescontos-valor'], 'moeda-db');
      $oManDescontos->VL_DESCONTO[0] = $_POST['CMPdescontos-valor'];
      
      $oManDescontos->salvar();
      $aMsg = $oManDescontos->aMsg;
      

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
        $oDescontos->listar('WHERE id = '.$iId);

        if ($oDescontos->iLinhas < 1) {
          throw new excecoes(15, $sPgAtual);
        }
      } else {
        $oDescontos->inicializaAtributos();
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
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'].' - '.(!isset($_GET['n']) ? 'Inserir novo registro' : 'Editar registro: '.$oDescontos->NM_DESCONTO[0]);?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script type="text/javascript" src="../modulosJS/jwysiwyg/jquery.wysiwyg.js"></script>
    <link rel="stylesheet" href="../modulosJS/jwysiwyg/jquery.wysiwyg.css" type="text/css" />
    <script type="text/javascript" src="../modulosJS/maskedinput/jquery.maskedinput-1.3.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){

        $(".radio-tipo-valor").attr('disabled', true);
        $("#CMPdescontos-tipo-V").attr('disabled', false);

        $( "#CMPdescontos-vigencia-inicio" ).datepicker();
        $( "#CMPdescontos-vigencia-fim" ).datepicker();

        $( "#CMPdescontos-tipo-desc").change(function(){
          $(".radio-tipo-valor").attr('disabled', true);
          var sTipo = $(this).val();
          if (sTipo == 'T') {
            $("#CMPdescontos-tipo-V").attr('checked', 'checked');
            $("#CMPdescontos-tipo-V").attr('disabled', false);
          } else if (sTipo == 'Q') {
            $("#CMPdescontos-tipo-I").attr('checked', 'checked');
            $("#CMPdescontos-tipo-I").attr('disabled', false);
          } else if (sTipo == 'U') {
            $("#CMPdescontos-tipo-V").attr('checked', 'checked');
            $("#CMPdescontos-tipo-V").attr('disabled', false);
            $("#CMPdescontos-tipo-P").attr('disabled', false);
          } else if (sTipo == 'B') {
            
          }
          atualizarTipoValor();
          atualizarMinimoParaDesconto(sTipo);
        });
        
        $(".radio-tipo-valor").change(function(){
          atualizarTipoValor();
        });
        
        
        // Máscara
        $("#CMPdescontos-valor-p").keyup(function(){
          porcentagem(this);
        });
        $("#CMPdescontos-valor-v").keyup(function(){
          moeda(this);
        });
        $("#CMPdescontos-valor-i").keyup(function(){
          inteiro(this);
        });
        $("#CMPdescontos-min-Q").keyup(function(){
          inteiro(this);
        });
        $("#CMPdescontos-min-T").keyup(function(){
          moeda(this);
        });
        $("#CMPdescontos-min-U").keyup(function(){
          inteiro(this);
        });
      });
      function buscarTipoValor() {
        var sTipo = $('.radio-tipo-valor').filter(':checked').val();
        return sTipo;
      }
      
      function atualizarMinimoParaDesconto(sTipo) {

        if (sTipo == 'T') {
          $("#CMPdescontos-min-T").removeClass('invisivel');
          $("#CMPdescontos-min-Q").removeClass('visivel');
          $("#CMPdescontos-min-U").removeClass('visivel');

          $("#CMPdescontos-min-T").addClass('visivel');
          $("#CMPdescontos-min-Q").addClass('invisivel');
          $("#CMPdescontos-min-U").addClass('invisivel');
          
          $('#LABcompra-min').html('Compra mínima (R$)');

        } else if (sTipo == 'Q') {
          $("#CMPdescontos-min-Q").removeClass('invisivel');
          $("#CMPdescontos-min-T").removeClass('visivel');
          $("#CMPdescontos-min-U").removeClass('visivel');

          $("#CMPdescontos-min-Q").addClass('visivel'); 
          $("#CMPdescontos-min-T").addClass('invisivel');
          $("#CMPdescontos-min-U").addClass('invisivel');
          
          $('#LABcompra-min').html('Quantidade mínima');
          
        } else if (sTipo == 'U') {
          $("#CMPdescontos-min-U").removeClass('invisivel');
          $("#CMPdescontos-min-Q").removeClass('visivel');
          $("#CMPdescontos-min-T").removeClass('visivel');

          $("#CMPdescontos-min-U").addClass('visivel');
          $("#CMPdescontos-min-T").addClass('invisivel');
          $("#CMPdescontos-min-Q").addClass('invisivel');

          $('#LABcompra-min').html('');
        }
        
      }
      function atualizarTipoValor() {
        
        var sTipo = buscarTipoValor();
        $("#CMPdescontos-valor-v").val('');
        $("#CMPdescontos-valor-p").val('');
        $("#CMPdescontos-valor-i").val('');

        if (sTipo == 'V') {
          $("#CMPdescontos-valor-v").removeClass('invisivel');
          $("#CMPdescontos-valor-p").removeClass('visivel');
          $("#CMPdescontos-valor-i").removeClass('visivel');

          $("#CMPdescontos-valor-v").addClass('visivel');
          $("#CMPdescontos-valor-p").addClass('invisivel');
          $("#CMPdescontos-valor-i").addClass('invisivel');

        } else if (sTipo == 'P') {
          $("#CMPdescontos-valor-p").removeClass('invisivel');
          $("#CMPdescontos-valor-v").removeClass('visivel');
          $("#CMPdescontos-valor-i").removeClass('visivel');

          $("#CMPdescontos-valor-p").addClass('visivel'); 
          $("#CMPdescontos-valor-v").addClass('invisivel');
          $("#CMPdescontos-valor-i").addClass('invisivel');
        } else if (sTipo == 'I') {
          $("#CMPdescontos-valor-i").removeClass('invisivel');
          $("#CMPdescontos-valor-p").removeClass('visivel');
          $("#CMPdescontos-valor-v").removeClass('visivel');

          $("#CMPdescontos-valor-i").addClass('visivel');
          $("#CMPdescontos-valor-v").addClass('invisivel');
          $("#CMPdescontos-valor-p").addClass('invisivel');
        }
      }
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


        <form id="FRMdescontos" name="FRMdescontos" action="<?php echo $_SERVER['PHP_SELF'].(isset($_GET['n']) ? '?n='.$_GET['n'] : '');?> " method="post">
          <input type="hidden" name="sAcao" value="<?php echo (isset($_GET['n']) ? 'editar' : 'inserir'); ?>" />
          <input type="hidden" name="CMPdescontos-id" value="<?php echo $oDescontos->ID[0]; ?>" />
          <table class="tab_lista_registros">
            <tr>
              <td class="infoheader" style="width: 180px;">Nome do desconto:</td>
              <td class="infovalue">
                <input type="text" name="CMPdescontos-nome" value="<?php echo $oDescontos->NM_DESCONTO[0]; ?>" />
                <span class="infoheader" style="margin-left: 20px">Ativo:</span>
                <input name="CMPdescontos-status" type="checkbox" value="A" <?php echo $oDescontos->CD_STATUS[0] != 'I' ? 'checked="checked"' : '';?> />
              </td>
            </tr>
            <tr>
              <td class="infoheader">Descrição:</td>
              <td class="infovalue">
                <textarea name="CMPdescontos-desc" cols="40" rows="5"><?php echo $oDescontos->DE_DESCONTO[0]; ?></textarea>
              </td>
            </tr>
            <tr>
              <td colspan="2">
              <fieldset style="width: 50%">
                <legend>Definições do Desconto</legend>           
                <table class="tab_lista_registros">
                  <tr>
                    <td class="infoheader" style="width: 160px;">Tipo do Desconto:</td>
                    <td class="infovalue"><?php echo $oAdmin->montaSelect('CMPdescontos-tipo-desc', $CFGaTiposDesconto, $oDescontos->TP_DESCONTO[0] == '' ? 'T' : $oDescontos->TP_DESCONTO[0], true );?></td>
                  </tr>
                  <tr>
                    <td class="infoheader"><span id="LABcompra-min">Compra mínima:</span></td>
                    <td class="infovalue">
                      <input style="border: dotted 1px yellow"  type="text" name="CMPdescontos-min-Q" id="CMPdescontos-min-Q" <?php echo $oDescontos->TP_DESCONTO[0]  == 'Q' ? '' : 'class="invisivel"'; ?> value="<?php echo $oDescontos->TP_DESCONTO[0]  == 'Q' ? $oDescontos->VL_MIN[0] : ''; ?>" />
                      <input style="border: dotted 1px red"     type="text" name="CMPdescontos-min-T" id="CMPdescontos-min-T" <?php echo ($oDescontos->TP_DESCONTO[0] == 'T' || $oDescontos->TP_DESCONTO[0] == '' ) ? '' : 'class="invisivel"'; ?> value="<?php echo  ($oDescontos->TP_DESCONTO[0] == 'T' || $oDescontos->TP_DESCONTO[0] == '' ) ? $oDescontos->VL_MIN[0] : ''; ?>" />
                      <input style="border: dotted 1px #0086b3" type="text" name="CMPdescontos-min-U" id="CMPdescontos-min-U" <?php echo $oDescontos->TP_DESCONTO[0]  == 'U' ? '' : 'class="invisivel"'; ?> value="<?php echo $oDescontos->TP_DESCONTO[0]  == 'U' ? $oDescontos->VL_MIN[0] : ''; ?>" disabled="disabled" />
                    </td>
                  </tr>
                  <tr>
                    <td class="infoheader">Tipo do Valor:</td>
                    <td class="infovalue">
                      <?php echo $oAdmin->montarRadio('CMPdescontos-tipo', $CFGaTiposValoresDesconto, $oDescontos->TP_VALOR[0] == '' ? 'V' : $oDescontos->TP_VALOR[0], false, true, 'radio-tipo-valor' );?>
                    </td>
                  </tr>
                  <tr>
                    <td class="infoheader">Valor do desconto:</td>
                    <td class="infovalue">
                      <input style="border: dotted 1px yellow"  type="text" name="CMPdescontos-valor-p" id="CMPdescontos-valor-p" <?php echo $oDescontos->TP_VALOR[0]  == 'P' ? '' : 'class="invisivel"'; ?> value="<?php echo $oDescontos->VL_DESCONTO[0]; ?>" />
                      <input style="border: dotted 1px red"     type="text" name="CMPdescontos-valor-v" id="CMPdescontos-valor-v" <?php echo ($oDescontos->TP_VALOR[0] == 'V' || $oDescontos->TP_VALOR[0] == '' ) ? '' : 'class="invisivel"'; ?> value="<?php echo $oDescontos->VL_DESCONTO[0]; ?>" />
                      <input style="border: dotted 1px #0086b3" type="text" name="CMPdescontos-valor-i" id="CMPdescontos-valor-i" <?php echo $oDescontos->TP_VALOR[0]  == 'N' ? '' : 'class="invisivel"'; ?> value="<?php echo $oDescontos->VL_DESCONTO[0]; ?>" />
                    </td>
                  </tr>
                </table>
              </fieldset>
                
              </td>
            </tr>
            <tr>
              <td class="infoheader">Abrangência:</td>
              <td class="infovalue"><input type="text" name="CMPdescontos-abrangencia" value="<?php echo $oDescontos->CD_ABRANGENCIA[0]; ?>" /></td>
            </tr>
            <tr>
              <td class="infoheader">Início de Vigência:</td>
              <td class="infovalue"><input type="text" id="CMPdescontos-vigencia-inicio" name="CMPdescontos-vigencia-inicio" value="<?php echo $oDescontos->DT_VIGENCIA_INICIO[0]; ?>" /></td>
            </tr>
            <tr>
              <td class="infoheader">Fim de Vigência:</td>
              <td class="infovalue"><input type="text" id="CMPdescontos-vigencia-fim" name="CMPdescontos-vigencia-fim" value="<?php echo $oDescontos->DT_VIGENCIA_FIM[0]; ?>" /></td>
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