<?php

/**
 * Cálculos para desconto com brindes
 *
 * @author Alex Lunardelli
 */
class descontoBrinde {
  function __construct() {
  }
  
  public function calcularDescontoListagem($oProdutos) {

    if ($oProdutos->TP_DESCONTO[0] != 'B') {
      return false;
    }
    

  }
  
  public function calcularDescontoTotais($oCarrinho) {

  }

}
?>
