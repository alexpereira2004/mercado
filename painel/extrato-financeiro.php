<?php
  session_start();
  $sPgAtual = 'extrato-financeiro';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';
  include_once '../modulosPHP/adapter.pagSeguro.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  
  $aDados = array();
  if (isset($_POST['sAcao'])) {
    $oPag = new pagSeguro();
    if ($_POST['sAcao'] == 'filtrar') {
      $aValidar = array ( 1 => array('Início'     , $_POST['CMPdt_inicio'], 'data', true), 
                          2 => array('Término'     , $_POST['CMPdt_termino'], 'data', true), 
                          2 => array('Término'     , $_POST['CMPdt_termino'], 'data', true), 
          
          );
      
      // Validação de preenchimento
      if ($oAdmin->valida_Preenchimento($aValidar) !== true) {
        $aMsg = $oAdmin->aMsg;
      }
      
      if (!$oPag->pesquisaPorData($_POST['CMPdt_inicio'], $_POST['CMPdt_termino']) ) {
        $aMsg = $oPag->aMsg;
      }
    }

  }

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
        $('.dataTable').dataTable({
          "iDisplayLength": 25
        });
        $( "#CMPdt_inicio" ).datepicker({
          minDate : '-180',
          maxDate : '-1',
          beforeShow: function() {
              setTimeout(function(){
                  $('.ui-datepicker').css('z-index', 99999999999999);
              }, 0);
          }
        });
        $( "#CMPdt_termino" ).datepicker({
          minDate : '-179',
          maxDate : '-1',
          beforeShow: function() {
              setTimeout(function(){
                  $('.ui-datepicker').css('z-index', 99999999999999);
              }, 0);
          }
        });
        
        $("#FRMextrato").submit(function(){
            //validarCamposDatas();
            validarCamposDatas();
          }
        );
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

        <form id="FRMextrato" name="FRMextrato" action="<?php echo $_SERVER['PHP_SELF'].(isset($_GET['n']) ? '?n='.$_GET['n'] : '');?> " method="post">
          <input type="hidden" name="sAcao" value="filtrar" />
          <table class="tab_lista_registros" style="width: 90%">
            <tr>
              <td style="width: 100px" class="infoheader">Início:</td>
              <td class="infovalue">
                <input id="CMPdt_inicio" type="text" name="CMPdt_inicio" value="<?php echo (isset($_POST['CMPdt_inicio']) ? $_POST['CMPdt_inicio'] : ''); ?>"/>
              </td>
            </tr>
            <tr>
              <td class="infoheader">Término:</td>
              <td class="infovalue">
                <input id="CMPdt_termino" type="text" name="CMPdt_termino" value="<?php echo (isset($_POST['CMPdt_termino']) ? $_POST['CMPdt_termino'] : ''); ?>"/>
              </td>
            </tr>
            <tr>
              <td colspan="2">
                
                <input class="bt" type="submit" value="Filtrar" />
              </td>
            </tr>
          </table>
        </form>
        <table class="dataTable" style="z-index:0">
          <thead>
            <tr class="header">
              <td style="width: 15px" title="Data de cadastro">Data Cad.</td>
              <td title="Data última movimentação">Data Mov.</td>
              <td title="Código da transação no Pag Seguro">Cód. Transação</td>
              <td title="Código de referência">Código de referência</td>
              <td title="Método de pagamento">Método de pagamento</td>
              <td title="Situação">Situação</td>
              <td title="Valor líquido da transação">Liq.</td>
              <td title="Valor das taxas cobradas">Taxa</td>
              <td title="Valor extra ou desconto">E/D</td>
              <td title="Valor bruto da transação">Bruto</td>
              <td title="Código de pagamento">Cod. Pag.</td>
            </tr>
          </thead>
          </tr>
          <tbody>
          <?php

          if (isset($oPag->iLinhas)) {

            for ($i = 0; $i < $oPag->iLinhas; $i++) { ?>
              <tr class="<?php echo ($i%2) ? 'corSim' : 'corNao'; ?>">
                <td><?php echo $oAdmin->parseValue($oPag->date[$i], 'bd-dt'); ?></td>
                <td><?php echo $oAdmin->parseValue($oPag->lastEventDate[$i], 'bd-dt'); ?></td>
                <td><?php echo $oPag->code[$i]; ?></td>
                <td><?php echo $oPag->reference[$i]; ?></td>
                <td><?php echo $oPag->type[$i]; ?></td>
                <td><?php echo $oPag->status[$i]; ?></td>
                <td><?php echo $oAdmin->parseValue($oPag->netAmount[$i], 'reais'); ?></td>
                <td><?php echo $oAdmin->parseValue($oPag->feeAmount[$i], 'reais'); ?></td>
                <td><?php echo $oAdmin->parseValue($oPag->extraAmount[$i], 'reais'); ?></td>
                <td><?php echo $oAdmin->parseValue($oPag->grossAmount[$i], 'reais'); ?></td>
                <td><?php echo $oPag->paymentMethod[$i]->getCode(); ?></td>
              </tr><?php
//          $this->date[]          = $transactionSummary->getDate();
//          $this->lastEventDate[] = $transactionSummary->getLastEventDate();
//          $this->code[]          = $transactionSummary->getCode();
//          $this->reference[]     = $transactionSummary->getReference();
//          $this->grossAmount[]   = $transactionSummary->getGrossAmount();
//          $this->type[]          = $transactionSummary->getType()->getTypeFromValue();
//          $this->status[]        = $transactionSummary->getStatus()->getTypeFromValue();
//          $this->netAmount[]     = $transactionSummary->getNetAmount();  
//          $this->feeAmount[]     = $transactionSummary->getFeeAmount();
//          $this->extraAmount[]   = $transactionSummary->getExtraAmount();
//          $this->paymentMethod[] = $transactionSummary->getPaymentMethod();  
            }
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