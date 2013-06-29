<?php

/**
 * Centralização de funções que tratam com sessões
 *
 * @author Alex Lunardelli
 */
include_once 'class.wTools.php';
class pagSeguro {
  
  public $date;

  /* Data da última atualização */  
  public $lastEventDate;

  /* Código da transação */  
  public $code;

  /* Refência */  
  public $reference;

  /* Valor bruto  */  
  public $grossAmount;

  /* Tipo */  
  public $type;

  /* Status */  
  public $status;

  /* Valor líquido  */  
  public $netAmount;

  /* Valor das taxas cobradas  */  
  public $feeAmount;

  /* Valor extra ou desconto */  
  public $extraAmount;

  /* Tipo de meio de pagamento */  
  public $paymentMethod;
  
  public $aMsg = array();


  public function __construct() {
    require_once "PagSeguroLibrary/PagSeguroLibrary.php";
    $this->oUtil       = new wTools();
    
  }

  public function buscarCredenciais() {
    include "config.php";
    $aRet = $this->oUtil->buscarParametro(array ('TOKENPS', 'PG_RET_PS'));
    $credentials = new PagSeguroAccountCredentials(  
      $CFGsEmailPagSeguro, 
      $aRet['TOKENPS'][0]    
    );
    return $credentials;
  }
  
   /* pagSeguro::pesquisarPorCodigo
   *  
   * Consulta ao Pag Seguro por código de transação. Credenciais e outras configurações
   * necessárias são encapsuladas facilitando o uso.
   * 
   * Esta consulta irá retornar somente um resultado. 
   * 
   * Dados são armazenados em atributos do objeto
   * 
   * @date xx/xx/2013
   * @param   string $transaction_id - Id da transação ex: D5834A-2702820282D5-5004959F95EF-FF031B
   * @return  bool
   */
  public function pesquisarPorCodigo($transaction_id) {

    try {
      $credentials = $this->buscarCredenciais();

      $oTransaction = PagSeguroTransactionSearchService::searchByCode(  
          $credentials,
          $transaction_id
      );
            
      $this->date              = $oTransaction->getDate();
      $this->lastEventDate     = $oTransaction->getLastEventDate();
      $this->code              = $oTransaction->getCode();
      $this->reference         = $oTransaction->getReference();
      $this->type              = $oTransaction->getType();
      $this->status            = $oTransaction->getStatus();
      $this->paymentMethod     = $oTransaction->getPaymentMethod();
      $this->paymentMethodCode = $this->paymentMethod->getCode()->getValue();
      $this->grossAmount       = $oTransaction->getGrossAmount();
      $this->discountAmount    = $oTransaction->getDiscountAmount();
      $this->feeAmount         = $oTransaction->getFeeAmount();
      $this->netAmount         = $oTransaction->getNetAmount();
      $this->extraAmount       = $oTransaction->getExtraAmount();
      $this->installmentCount  = $oTransaction->getInstallmentCount();
      $this->items             = $oTransaction->getItems();

      $this->sender            = $oTransaction->getSender();
      $this->shipping          = $oTransaction->getShipping();
    } catch (Exception $e) {
  
      $a = array();
      $aMensagens = array();

      foreach ($e->getErrors($a) as $key => $error) {  
          $aMensagens[] = '['.$error->getCode().'] '.$error->getMessage();
      }
      $sMsg = implode('<br />',$aMensagens);
      
      $this->aMsg = array('iCdMsg' => 2,
                            'sMsg' => $sMsg,
                      'sResultado' => '' );
    }
      
  }

  public function pesquisaPorData($sDtInicio, $sDtTermino) {
    $sDtInicio  = $this->oUtil->parseValue($sDtInicio, 'dt-bd');
    $sDtTermino = $this->oUtil->parseValue($sDtTermino, 'dt-bd');
    /* Definindo a data de ínicio da consulta */
    $initialDate = $sDtInicio.'T00:00';

    /* Definindo a data de término da consulta */  
    $finalDate   = $sDtTermino.'T23:59';


    try {
      $credentials = $this->buscarCredenciais();

      /* Definindo o número máximo de resultados por página */  
      $maxPageResults = 1000;  

      /* Definindo o número da página */  
      $pageNumber = 1;  

      $oResult = PagSeguroTransactionSearchService::searchByDate(  
          $credentials,       // credenciais  
          $pageNumber,        // número da página  
          $maxPageResults,    // número máximo de resultados por página  
          $initialDate,       // data de ínicio  
          $finalDate         // data de término  
      );

      $aTransactions = $oResult->getTransactions();  

      $iLinhas = 0;

      $aRet = array();
      if(is_array($aTransactions)) {
        foreach ($aTransactions as $transactionSummary) {
          /* Data da criação */  
          $this->date[]          = $transactionSummary->getDate();
          $this->lastEventDate[] = $transactionSummary->getLastEventDate();
          $this->code[]          = $transactionSummary->getCode();
          $this->reference[]     = $transactionSummary->getReference();
          $this->grossAmount[]   = $transactionSummary->getGrossAmount();
          $this->type[]          = $transactionSummary->getType()->getTypeFromValue();
          $this->status[]        = $transactionSummary->getStatus()->getTypeFromValue();
          $this->netAmount[]     = $transactionSummary->getNetAmount();  
          $this->feeAmount[]     = $transactionSummary->getFeeAmount();
          $this->extraAmount[]   = $transactionSummary->getExtraAmount();
          $this->paymentMethod[] = $transactionSummary->getPaymentMethod();  

          $iLinhas++;
        }
      }
      $this->iLinhas = $iLinhas;
      return true;
      
    } catch (Exception $e) {
  
      $a = array();
      $aMensagens = array();

      foreach ($e->getErrors($a) as $key => $error) {  
          $aMensagens[] = '['.$error->getCode().'] '.$error->getMessage();
      }
      $sMsg = implode('<br />',$aMensagens);
      
      $this->aMsg = array('iCdMsg' => 2,
                            'sMsg' => $sMsg,
                      'sResultado' => '' );
    }

    return false;
  }
  
   /* pagSeguro::pesquisarNotificacao
   *  
   * Busca dados de uma transação assim que o Pag seguro envia código de notificação
   * automatico no pagamento, confirmação ou alteração de status do pagamento.
   * 
   * Esta consulta irá retornar somente um resultado. 
   * 
   * Dados são armazenados em atributos do objeto
   * 
   * @date 13/04/2013
   * @param   string $code - Código da notificação recebida
   * @param   string $type - Tipo da notificação recebida
   * @return  bool
   */
  public function pesquisarNotificacao ($code, $type) {

    $credentials = $this->buscarCredenciais();
     
    try {
      /* Verificando tipo de notificação recebida */
      if ($type === 'transaction') {

        /* Obtendo o objeto PagSeguroTransaction a partir do código de notificação */  
        $oTransaction = PagSeguroNotificationService::checkTransaction(  
            $credentials,  
            $code // código de notificação  
        );

        $this->date              = $oTransaction->getDate();
        $this->lastEventDate     = $oTransaction->getLastEventDate();
        $this->code              = $oTransaction->getCode();
        $this->reference         = $oTransaction->getReference();
        $this->type              = $oTransaction->getType();
        $this->oStatus            = $oTransaction->getStatus();
        $this->paymentMethod     = $oTransaction->getPaymentMethod();
        $this->paymentMethodCode = $this->paymentMethod->getCode()->getValue();
        $this->grossAmount       = $oTransaction->getGrossAmount();
        $this->discountAmount    = $oTransaction->getDiscountAmount();
        $this->feeAmount         = $oTransaction->getFeeAmount();
        $this->netAmount         = $oTransaction->getNetAmount();
        $this->extraAmount       = $oTransaction->getExtraAmount();
        $this->installmentCount  = $oTransaction->getInstallmentCount();
        $this->items             = $oTransaction->getItems();

        $this->sender            = $oTransaction->getSender();
        $this->shipping          = $oTransaction->getShipping();

      }
      
    } catch (Exception $e) {
  
      $a = array();
      $aMensagens = array();

      foreach ($e->getErrors($a) as $key => $error) {  
          $aMensagens[] = '['.$error->getCode().'] '.$error->getMessage();
      }
      $sMsg = implode('<br />',$aMensagens);
      
      $this->aMsg = array('iCdMsg' => 2,
                            'sMsg' => $sMsg,
                      'sResultado' => '' );
      return false;
    }



  }
}
  ?>