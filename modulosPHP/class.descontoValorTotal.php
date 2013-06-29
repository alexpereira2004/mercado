<?php

/**
 * Cálculos para desconto por preço total de uma compra
 *
 * @author Alex Lunardelli
 */
include_once 'class.descontos.php';
class descontoValorTotal extends descontos {
  public $oUtil; 
  public $fDesconto = 0;
  public $aParamDesc = array('sAvisoDescontoQnt');

  function __construct() {
  }
  
  public function calcularDescontoListagem(&$oProdutos) {

    if ($oProdutos->TP_DESCONTO[0] != 'T') {
      return false;
    }
    return true;
  }
  
  public function calcularDescontoTotais($aDadosSessao) {
    $this->fDesconto = 0;
    $aTiposDesconto = descontos::getTiposDescontos($_SESSION[$this->sUsuarioSessao]['carrinho']);

    // Terá um "outro desconto"
    if (array_intersect($aTiposDesconto, array('Q', 'U' , 'B'))) {
      echo 'OUTRO DESCONTO!<br />';
      return false;
    }
    
    // Não deve chegar aqui, mas se for verdadeiro "nenhum desconto" será dado
    if (!in_array('T', $aTiposDesconto)) {
      echo 'NENHUM DESCONTO!<br />';
      return false;
    }


      echo 'DESCONTO SOBRE O TOTAL!<br />';
// Debugs - tstAlex
    $oArqDebugs = fopen('C:\Documents and Settings\Alex Lunardelli\Meus documentos\htdocs\debugs\debugs.txt', 'w+');
    ob_start();

    print_r($_SESSION[$this->sUsuarioSessao]['carrinho']);
    print_r($aTiposDesconto);
    $sDebugs = ob_get_clean();
    fwrite($oArqDebugs, $sDebugs);
    fclose($oArqDebugs);
  }
  
  public function criarParametros() {
    $this->aParamDesc['sAvisoDescontoQnt'] = '&nbsp;';
  }

}
?>
