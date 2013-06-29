<?php

/**
 * Cálculos para desconto por quantidade de produtos
 *
 * @author Alex Lunardelli
 */
include_once 'class.descontos.php';
class descontoQuantidade extends descontos {
  public $oUtil; 
  public $iQntItensPromo = 0;
  public $fDesconto = 0;
  public $aParamDesc = array('sAvisoDescontoQnt');

  function __construct() {
    $this->oUtil = new wTools();
  }
  
  public function calcularDescontoListagem($oProdutos) {

    if ($oProdutos->TP_DESCONTO[0] != 'Q') {
      return false;
    }
    

  }
  
  public function calcularDescontoTotais($aDadosSessao) {

    // Verifica os parâmetros da promoção que o produto esta enquadrado
    $sSql = "SELECT nm_desconto, 
                    de_desconto,
                    vl_min,
                    vl_desconto
               FROM tc_descontos 
         INNER JOIN tr_prod_desconto ON tr_prod_desconto.id_desconto = tc_descontos.id
              WHERE tr_prod_desconto.id_prod = ".$aDadosSessao['id']."
                AND tp_desconto = 'Q'
                AND cd_status = 'A'
                AND (  	 dt_vigencia_inicio < CURDATE() 
                      OR dt_vigencia_inicio IS NULL )
                AND (    dt_vigencia_fim > CURDATE()
                      OR dt_vigencia_fim IS NULL)";

    $aRet = $this->oUtil->buscarInfoDB($sSql);

    $this->iQntItensPromo = floor($aDadosSessao['iQnt'] / $aRet[2] );

    $this->criarParametros();
    
    $this->fDesconto = $this->iQntItensPromo * $aDadosSessao['fVlUnid'];

    return true;
  }
  
  public function criarParametros() {
    
    if ($this->iQntItensPromo > 0) {
      $this->aParamDesc['sAvisoDescontoQnt'] = utf8_encode('Grátis '.$this->iQntItensPromo.' '.($this->iQntItensPromo == 1 ? 'item' : 'itens'));
    } else {
      $this->aParamDesc['sAvisoDescontoQnt'] = '&nbsp;';
    }
  }

}
?>
