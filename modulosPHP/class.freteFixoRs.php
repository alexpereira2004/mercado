<?php
/**
 * Fretes Fixo calculado para todas cidades do RS
 *
 * @author Alex Lunardelli
 */
include 'interfaces/interface.fretes.php';
include 'class.ws_enderecos.php';
//class freteFixoRs implements iFretes{
class freteFixoRs {

  function __construct() {
  }
  public function calcularFretePorCep($mCep) {
    $mCep = (!is_numeric($mCep)) ? str_replace('-', 0, $mCep) : $mCep;
    $oWsEndereco = new ws_enderecos();
    $oWsEndereco->buscar_cep($mCep);
    return $this->calcularFretePorEndereco($oWsEndereco->sCidade, $oWsEndereco->sSgUf);
  }

  public function calcularFretePorEndereco($sCidade, $sUf) {
    if ($sUf == 'RS') {
      return 35;
    } else {
      return 0;
    }
  }
}
?>
