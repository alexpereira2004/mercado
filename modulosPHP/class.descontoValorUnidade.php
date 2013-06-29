<?php

/**
 * Cálculos para desconto por preço direto no produto
 *
 * @author Alex Lunardelli
 */
include_once 'class.descontos.php';
class descontoValorUnidade extends descontos {
  public $oUtil; 
  public $fDesconto = 0;
  public $aParamDesc = array();

  function __construct() {
  }
  
  public function calcularDescontoListagem($oProdutos) {

    if ($oProdutos->TP_DESCONTO[0] != 'U') {
      return false;
    }
    
    for ($i = 0; $i < $oProdutos->iLinhas; $i++) {
      $fDescUnidCalculado = 0;
      if ($oProdutos->TP_DESCONTO[$i] == 'U') {
        if ($oProdutos->TP_VALOR[$i] == 'P') {
          $fDescUnidCalculado = ($oProdutos->VL_FINAL[$i] * $oProdutos->VL_DESCONTO[$i]) / 100;
        } elseif ($oProdutos->TP_VALOR[$i] == 'V') {
          $fDescUnidCalculado = $oProdutos->VL_DESCONTO[$i];
        }
        $oProdutos->VL_ANTERIOR[$i]     = $oProdutos->VL_FINAL[$i];
        $oProdutos->VL_FINAL[$i]        = $oProdutos->VL_FINAL[$i] - $fDescUnidCalculado;
        $oProdutos->VL_FINAL_REAL[$i]   = $oProdutos->oUtil->parseValue($oProdutos->VL_FINAL[$i], 'reais');
        $oProdutos->VL_DESCONTO[$i]     = $fDescUnidCalculado;
      }
      $oProdutos->fDescUnidCalculado += $fDescUnidCalculado;
    }
    

  }
  
  public function calcularDescontoTotais($oCarrinho) {

  }

}
?>
