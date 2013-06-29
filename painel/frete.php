<?php
  session_start();
  $sPgAtual = 'transportadoras';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';
  include_once '../modulosPHP/adapter.parametros.php';


  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  if (isset ($_POST)) {

    if (isset($_POST['sResultado'])) {
      $aMsg = $oAdmin->msgRetPost($_POST);
    }

    if (isset($_POST['sAcao'])) {
      if ($_POST['sAcao'] == 'salvar') {

        $oManParam    = new adapter_parametros();
        $oManParam->salvarParametros(
                array($_POST['CMPiIdvalor']), 
                array($_POST['CMPselecionado']), 
                array($_POST['CMPsIdParametro'])
                );
        $aMsg = $oManParam->aMsg;
      }
    }
  }



  $oParam    = new adapter_parametros();
  $oParam->listar("WHERE cd_tipo_uso = 'PM' AND cd_parametro = 'OBJ_FRETE' ");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'];?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script type="text/javascript">
      $(document).ready(function() {
//        $('.dataTable').dataTable({
//          "iDisplayLength": 25
//        });
        $('.remover').click(function(){
          removerViaCheckBox('Deseja realmente excluir as transportadoras selecionadas?', 'transportadoras.php', 'remover');
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

        </div>

        <form id="FRMfretes" name="FRMfretes" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" style="margin-top: 10px;">
          <input type="hidden" name="sAcao" value="salvar" />
          <input type="hidden" name="CMPiIdvalor" value="<?php echo $oParam->ID_VALOR[0]; ?>" />
          <input type="hidden" name="CMPsIdParametro" value="<?php echo $oParam->ID_PARAMETRO[0]; ?>" />
          <fieldset>
            <legend>Selecionar Cálculo de Frete:</legend>
            <table class="w90">
              <tr>
                <td style="width: 150px">Opções:</td>
                <td>
                  <?php
                    $sSelecionado = isset($oParam->TX_VALOR[0]) ? $oParam->TX_VALOR[0] : '';
                    $CFGaOpcoesFrete = array ('FIXO-RS' => 'Valor Fixo RS');
                    $oAdmin->montaSelect('CMPselecionado', $CFGaOpcoesFrete, $sSelecionado, 'CMPselecionado');
                    //$oAdmin->montaSelect('CMPselecionado', $CFGaOpcoesFrete, $sSelecionado, $bId, $sClass, $sJsAdicional, $sBranco)
                  ?>
                  
                </td>
              </tr>
              <tr>
                <td><input class="bt" type="submit" value="Salvar" /></td>
              </tr>
            </table>
          </fieldset>
        </form>
        <form method="post" id="FRMremover" action="<?php echo $_SERVER['PHP_SELF']; ?>">
          <input type="hidden" name="sAcao" value="remover" />
          <input type="hidden" name="CMPid" id="CMPid"  value="" />
        </form>
      </div>
      <div class="limpa"></div>
      <?php
        $oAdmin->rodape($sPgAtual);
      ?>
    </div>
  </body>
</html>