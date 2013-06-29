<?php
  session_start();
  $sPgAtual = 'taxas';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include_once '../modulosPHP/adapter.menu.php';
  include_once '../modulosPHP/class.excecoes.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tc_taxas.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  $oMenu = new adapter_tclv_menu();

  /**********************************************************************
    Cabeçalho de tratamento do formulário
  **********************************************************************/
  $oTaxas = new tc_taxas();

  if (isset($_POST['sAcao'])) {

    // Persistencia de dados na tela
    $oTaxas->inicializaAtributos();

    //Anti SQL injection
    foreach ($_POST as $sNome => $mValor) {
      if (!is_array($mValor)) {
        $_POST[$sNome] = $oAdmin->anti_sql_injection($mValor);
      }
    }

    // Manipulação dos dados
    $oManTaxas = new tc_taxas();
    $oManTaxas->inicializaAtributos();

    /*
     Campos com tratamento adicional
    */

    try {

      $oManTaxas->sBackpage = $CFGaPgAtual[$sPgAtual]['backPage'];
      $oManTaxas->ID_USU_CAD[0] = $_SESSION[usuario_admin::getEmpresa()]['id_usu'];
      $oManTaxas->ID_USU_ATU[0] = $_SESSION[usuario_admin::getEmpresa()]['id_usu'];
      $oManTaxas->DT_VIGENCIA_INICIO[0] = $_POST['CMPtaxas-vigencia-inicio'] != '' ? "'".$oAdmin->parseValue($_POST['CMPtaxas-vigencia-inicio'], 'dt-bd')."'" : 'null';
      $oManTaxas->DT_VIGENCIA_FIM[0]    = $_POST['CMPtaxas-vigencia-fim']    != '' ? "'".$oAdmin->parseValue($_POST['CMPtaxas-vigencia-fim'], 'dt-bd')."'" : 'null';
      $oManTaxas->CD_STATUS[0]          = isset($_POST['CMPtaxas-status']) ? 'A' : 'I';

      $fVlTaxa = $_POST['CMPtaxas-tipo'] == 'V' ? $_POST['CMPtaxas-valor-v'] : $_POST['CMPtaxas-valor-p'];
      $oManTaxas->VL_TAXA[0]            = $oAdmin->parseValue($fVlTaxa, 'moeda-db');;
      $_POST['CMPtaxas-valor']          = $oManTaxas->VL_TAXA[0];
      $oTaxas->VL_TAXA[0]               = $oManTaxas->VL_TAXA[0];


      $oManTaxas->salvar();
      $aMsg = $oManTaxas->aMsg;

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
        $oTaxas->listar('WHERE id = '.$iId);

        if ($oTaxas->iLinhas < 1) {
          throw new excecoes(15, $sPgAtual);
        }
      } else {
        $oTaxas->inicializaAtributos();
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
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'].' - '.(!isset($_GET['n']) ? 'Inserir novo registro' : 'Editar registro: '.$oTaxas->NM_TAXA[0]);?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script type="text/javascript" src="../modulosJS/jwysiwyg/jquery.wysiwyg.js"></script>
    <link rel="stylesheet" href="../modulosJS/jwysiwyg/jquery.wysiwyg.css" type="text/css" />
    <script type="text/javascript" src="../modulosJS/maskedinput/jquery.maskedinput-1.3.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function(){
        $( "#CMPtaxas-vigencia-inicio" ).datepicker();
        $( "#CMPtaxas-vigencia-fim" ).datepicker();
        $( "#CMPtaxas-tipo").change(function(){

          var sTipo = $(this).val();
          if (sTipo == 'V') {
            $("#CMPtaxas-valor-v").removeClass('invisivel');
            $("#CMPtaxas-valor-v").addClass('visivel');
            $("#CMPtaxas-valor-p").removeClass('visivel');
            $("#CMPtaxas-valor-p").addClass('invisivel');
            
          } else if (sTipo == 'P') {
            $("#CMPtaxas-valor-v").removeClass('visivel');
            $("#CMPtaxas-valor-v").addClass('invisivel');
            $("#CMPtaxas-valor-p").removeClass('invisivel');
            $("#CMPtaxas-valor-p").addClass('visivel'); 
          }
        });
        
        
        $("#CMPtaxas-valor-p").keyup(function(){
          porcentagem(this);
        });
        $("#CMPtaxas-valor-v").keyup(function(){
          moeda(this);
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
          $oAdmin->minheight('600');
        ?>
        <div id="toolBar">
          <a href="<?php echo $CFGaPgAtual[$sPgAtual]['backPage']; ?>"><img src="../comum/imagens/icones/doc_page_previous.png" alt="Voltar" /></a>
        </div>
        
        <form id="FRMtaxas" name="FRMtaxas" action="<?php echo $_SERVER['PHP_SELF'].(isset($_GET['n']) ? '?n='.$_GET['n'] : '');?> " method="post">
        <input type="hidden" name="sAcao" value="<?php echo (isset($_GET['n']) ? 'editar' : 'inserir'); ?>" />
        <input type="hidden" name="CMPtaxas-id" value="<?php echo $oTaxas->ID[0]; ?>" />
        <table class="tab_lista_registros">
    
          <tr>
            <td class="infoheader" style="width: 30%">Nome:</td>
            <td class="infovalue" style="width: 70%"><input type="text" name="CMPtaxas-nome" value="<?php echo $oTaxas->NM_TAXA[0]; ?>" /></td>
          </tr>
          <tr>
            <td class="infoheader">Tipo:</td>
            <td class="infovalue">
              <?php echo $oAdmin->montaSelect('CMPtaxas-tipo', $CFGaTiposValores, $oTaxas->TP_TAXA[0] == '' ? 'V' : $oTaxas->TP_TAXA[0], true );?>
            </td>
          </tr>
          <tr>
            <td class="infoheader">Status:</td>
            <td class="infovalue">
              <input name="CMPtaxas-status" type="checkbox" value="A" <?php echo $oTaxas->CD_STATUS[0] != 'I' ? 'checked="checked"' : '';?> />
            </td>
          </tr>
          <tr>
            <td class="infoheader">Abrangência:</td>
            <td class="infovalue"><input type="text" name="CMPtaxas-abrangencia" value="<?php echo $oTaxas->CD_ABRANGENCIA[0]; ?>" /></td>
          </tr>
          <tr>
            <td class="infoheader">Taxa:</td>
            <td class="infovalue">
              <input type="text" name="CMPtaxas-valor-p" id="CMPtaxas-valor-p" <?php echo $oTaxas->TP_TAXA[0] == '' || $oTaxas->TP_TAXA[0] == 'V' ? 'class="invisivel"' : '' ;?>  value="<?php echo $oAdmin->parseValue($oTaxas->VL_TAXA[0], 'reais'); ?>" />
              <input type="text" name="CMPtaxas-valor-v" id="CMPtaxas-valor-v" <?php echo $oTaxas->TP_TAXA[0] == 'P' ? 'class="invisivel"' : '';?> value="<?php echo $oAdmin->parseValue($oTaxas->VL_TAXA[0], 'reais'); ?>" />
            </td>
          </tr>
          <tr>
            <td class="infoheader">Início de Vigência:</td>
            <td class="infovalue"><input id="CMPtaxas-vigencia-inicio" type="text" name="CMPtaxas-vigencia-inicio" value="<?php echo $oTaxas->DT_VIGENCIA_INICIO[0]; ?>" /></td>
          </tr>
          <tr>
            <td class="infoheader">Final de Vigência:</td>
            <td class="infovalue"><input id="CMPtaxas-vigencia-fim" type="text" name="CMPtaxas-vigencia-fim" value="<?php echo $oTaxas->DT_VIGENCIA_FIM[0]; ?>" /></td>
          </tr>
          <tr>
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