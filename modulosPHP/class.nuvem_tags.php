<?php
/**
 * 
 * Versão 1.1
 * @author Alex Lunardelli
 */

include_once 'class.wTools.php';
class nuvem_tags {

  public $aDadosNuvem = array();
  public $iQntPalavras = 0;

  public function __construct($sOpcao) {
    $this->oUtil = new wTools();
    $this->buscarDados($sOpcao);
  }

  private function buscarDados($sOpcao , $iQntItens = '') {
    if ($iQntItens == '') {
      $aRet = $this->oUtil->buscarParametro(array('QNT_ITENS_TAG_01'));
      $iQntItens = $aRet['QNT_ITENS_TAG_01'][0];
    }

    switch ($sOpcao) {
      case 'tags':
        $sSql = "SELECT * 
                   FROM ( SELECT COUNT(nm_tag) AS qnt, 
                                 nm_tag,
                                 tx_link, 
                                 tc_tags.id
                            FROM tc_tags 
                      INNER JOIN tr_prod_tag ON tr_prod_tag.id_tag = tc_tags.id
                        GROUP BY nm_tag
                           LIMIT ".$iQntItens."
                        ) AS DADOS
               ORDER BY nm_tag ASC";

        break;
    }

    
    
    $this->oUtil->buscarInfoDB($sSql);

    $this->aDadosNuvem = $this->oUtil->RETDB;
    
    for ($i = 0; $i < count($this->aDadosNuvem); $i++) {
      $this->iQntPalavras += $this->aDadosNuvem[$i][0];
    }
    
    for ($i = 0; $i < count($this->aDadosNuvem); $i++) {
      $this->aDadosNuvem[$i]['perc'] = round($this->aDadosNuvem[$i][0] / $this->iQntPalavras * 100, 0);
    }

    //shuffle($this->aDadosNuvem);
  }

  private function css() { ?>
    <style type="text/css" media="all">
      #FWKnuvemTag{
        font-size: 9px;
      }
      #FWKnuvemTag span a:hover{
        color: red;
        font-weight: bold;
        
      }
      #FWKnuvemTag span a{
        color: #000;
        font-weight: bold;
      }
    </style>
  <?php
  }


  
  public function montarNuvem() {
    $this->css();

    ?>
    <div id="FWKnuvemTag">
    <?php
    foreach ($this->aDadosNuvem as $aDados) {
      $mTamanho = str_pad(($aDados['perc'] * 10), 2, "0", STR_PAD_LEFT);
      $mTamanho = (1 + ($mTamanho / 100));
      //str_pad($aDados['id'][$i], 5, '0', 0)
      ?>

      <span style="font-size: <?php echo $mTamanho; ?>em; padding: 5px;">
        <a href="<?php echo $this->oUtil->sUrlBase; ?>/tags-detalhe/<?php echo ($aDados[2].'-'.str_pad($aDados[3], 5, '0', 0)); ?>/"><?php echo $aDados[1]; ?></a>
      </span>
      <?php
    }?>
    </div>
  <?php
    
  }
}
?>
