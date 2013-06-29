<?php
/**
 * 
 *
 * @author Alex Lunardelli
 */

class frete {
  public function __construct() {}
  
  public static function carregarClasseFrete($sTipo = 'FIXO-RS') {
    switch ($sTipo) {
      case 'FIXO-RS':
        include_once 'class.freteFixoRs.php';
        $oObj = new freteFixoRs();
        break;
    }
    return $oObj;
  }
}
?>
