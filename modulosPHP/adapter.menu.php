<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of adapter
 *
 * @author Alex Lunardelli
 */
include_once 'class.tc_menu.php';

class adapter_tclv_menu extends tc_menu{
  public function  __construct() {

  }

  public function montarMenu() {
    $sFiltro = 'ORDER BY sq_pag';
    $this->listar($sFiltro);
    $aDadosMenu = array();
    
    for ($i = 0; $i < $this->iLinhas; $i++) {

      
      $iPai = $this->ID_PAGPAI[$i];
      $aDadosMenu[$this->SQ_PAG[$i]][$this->ID[$i]] = $this->NM_MENU[$i];
      $aDadosMenu['pai'][$this->ID[$i]] = $this->ID_PAGPAI[$i];
      $iPaiAnt = 0;

    }

    for ($i = 1; $i < count($aDadosMenu[1]) + 1; $i++) {
      $aPrimeiroNivel[$i] = array($aDadosMenu[1][$i]);
    }
    //$aRet = $aDadosMenu[1];

    for ($i = 2 ; $i <= count($aDadosMenu) - 1; $i++) {
      foreach ($aDadosMenu[$i] as $key => $aValores) {
        $iIndicePai = $aDadosMenu['pai'][$key];
//        echo 'NIVEL --> '.$i.' ** '.$key.' '.$aValores.' ';
//        echo 'TESTE --> $iIndicePai *** <b>'.$iIndicePai.'</b><br />';
        $aDemaisNiveis[$i][$iIndicePai][$key] = $aValores;
        //$aRet[$iIndicePai][] = $aValores;

      }
    }


    for ($i = 1; $i < count($aPrimeiroNivel) + 1; $i++) {
      $aRet[$i] = array($aPrimeiroNivel[$i]);

      if (isset($aDemaisNiveis['2'][$i])) {
        $aRet[$i] = array_merge($aRet[$i] , array($aDemaisNiveis['2'][$i]));
//        $iIndice = key($aDemaisNiveis['2'][$i]);
//        echo 'ndi - '.$iIndice.'<br />';

        // Para Cada sub menu, procura seus sub sub
          for ($y = 0; $y < count($aDemaisNiveis['2'][$i]); $y++) {
            echo '<h3>'.$i.'-'.$y.' o que eh isso ?</h3>';
            
            //for ($z = 0; $z < count($aDemaisNiveis['3']); $z++) {
            foreach ($aDemaisNiveis['3'] as $key2 => $value) {
              echo 'ZZ - '.$key2.'<br>';
              
              //$aRet[$i] = array_merge($aRet[$i] , array($aDemaisNiveis['3'][$i]));
            
            }
          }
        /*
        echo '$iIndicePai - '.$iIndicePai.'<br>';
         * 
         */
      }

    }

    
    

  }
}
?>
