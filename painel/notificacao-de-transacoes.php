<?php
  include_once '../modulosPHP/class.wTools.php';
  include '../modulosPHP/class.tl_geral.php';
  include '../modulosPHP/adapter.pagSeguro.php';
  include '../modulosPHP/class.tc_carrinho.php';
  include '../modulosPHP/config.php';

  $oUtil = new wTools();
  $sTxLog = '';


  $_POST = array( 'notificationCode' => 'D5834A-2702820282D5-5004959F95EF-FF031B',
                  'notificationType' => 'transaction');

  try {
    if (!isset($_POST)) {
      $sMsg = 'Notifica��o: Requisi��o n�o possui dados no POST';
      $sTxLog = '';
      $sCdLog = 'ERRO_NOTIFICACAO_TRANSACAO';
      throw new Exception;
    }
    $aDados = array('notificationCode' => $_POST['notificationCode'],
                    'notificationType' => $_POST['notificationType'] );
    $sTxLog = $oUtil->montarStringDados($aDados);
    $sCdLog = 'NOTIFICACAO_TRANSACAO';
    
    $oPag = new pagSeguro();
    $sTransactionId = $oUtil->anti_sql_injection($_POST['notificationCode']);

    /* Tipo de notifica��o recebida */  
    $sType = $_POST['notificationType'];  

    /* C�digo da notifica��o recebida */  
    $sCode = $_POST['notificationCode'];  

    $oPag->pesquisarNotificacao($sCode, $sType);



    $oCarrinho = new tc_carrinho();
    $sFiltro = "WHERE cd_carrinho = '".$oPag->reference."'";
    $oCarrinho->listar($sFiltro);
    
    if ($oCarrinho->iLinhas != 1) {
      $sMsg = 'Notifica��o: Requisi��o n�o possui dados no POST';
      $sTxLog = "C�digo de carrinho [".$oPag->reference."] n�o foi encontrado.";
      $sCdLog = 'ERRO_NOTIFICACAO_CD_CARRINHO';
      throw new Exception;
    }
    $oCarrinho->CD_SIT[0]           = $CFGaCruzamentoSituacoes[$oPag->oStatus->getValue()];
    $oCarrinho->CD_SIT_PAGSEGURO[0] = $oPag->oStatus->getValue();
    $oCarrinho->CD_PAGAMENTO[0]     = $oPag->paymentMethodCode;
    $oCarrinho->editar($sFiltro);

  } catch (Exception $exc) {

  }

  $oLog  = new tl_geral();
  $oLog->NM_LOG[0]   = (isset($sMsg)) ? $sMsg : 'Recebimento de notifica��o do Pag Seguro';
  $oLog->TX_LOG[0]   = $sTxLog;
  $oLog->CD_LOG[0]   = $sCdLog;
  $oLog->CD_ACAO[0]  = 'I';
  $oLog->TX_IP[0]    = $_SERVER['REMOTE_ADDR'];
  $oLog->TX_TRACE[0] = $_SERVER['REQUEST_URI'];
  $oLog->ID_USU[0]   = 0;
  $oLog->inserir();



  echo '<h3>Notifica��es de transa��es Pag Seguro</h3>';


?>