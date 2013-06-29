<?php
/**
 * 
 *
 * @author Alex Lunardelli
 */

class descontos {
  protected $sUsuarioSessao;

  public function __construct() {}
  
  /* descontos::carregarClasseDesconto
   *
   * Determina qual classe irá ser carregada para calcular o desconto
   * 
   * @param string $sTipo  - Tipo do desconto
   * @return object
   */
  public static function carregarClasseDesconto($sTipo) {

    switch ($sTipo) {
      //T: Sobre o total da compra, 
      case 'T':
        include_once 'class.descontoValorTotal.php';
        $oObj = new descontoValorTotal();
        break;

      //Q: Por quantidade, 
      case 'Q':
        include_once 'class.descontoQuantidade.php';
        $oObj = new descontoQuantidade();
        break;

      //U: Desconto para cada unidade, 
      case 'U':
        include_once 'class.descontoValorUnidade.php';
        $oObj = new descontoValorUnidade();
        break;

      //B: Brinde, 
      case 'B':
        include_once 'class.descontoBrinde.php';
        $oObj = new descontoBrinde();
        break;

      //F: Desconto no frete'
      case 'F':
        include_once 'class.descontoFrete.php';
        $oObj = new descontoFrete();
        break;
  
    }
    return $oObj;
  }

/* descontos::getTiposDescontos
 *
 * Irá retornar informações sobre os descontos que um carrinho possui
 * @param  array $aCarrinho 
 * @return array
 */
  public static function getTiposDescontos($aCarrinho) {
    $aTiposDescontos = array();
    foreach ($aCarrinho as $iIdProd => $aInfo) {
      $aTiposDescontos[] = $aInfo['sTpDesconto'];
    }
    return $aTiposDescontos;
  }
  
  public function setUsuarioSessao($sUsuarioSessao) {
    $this->sUsuarioSessao = $sUsuarioSessao;
  }
}
?>
