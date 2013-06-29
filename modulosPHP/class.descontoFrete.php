<?php

/**
 * Cálculos para desconto de frete
 *
 * @author Alex Lunardelli
 */
class descontoFrete {
  function __construct() {
  }
  
  public function calcularDescontoListagem($oProdutos) {

    if ($oProdutos->TP_DESCONTO[0] != 'F') {
      return false;
    }
    

  }
  
  public function calcularDescontoTotais($oCarrinho) {

  }

}
?>
