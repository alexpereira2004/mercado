<?php
  include_once 'class.wTools.php';

  
  class promocoes {
    
    public $ID; 
    public $NM_PROMOCAO; 
    public $DE_PROMOCAO; 
    public $ID_DESCONTO; 
    public $DT_VIGENCIA_INICIO; 
    public $DT_VIGENCIA_FIM; 
    public $NM_DESCONTO;
    
    function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
      $this->oUtil   = new wTools();
    }

    public function listar($sFiltro = '') {
      $sQuery = 'SELECT tc_promocoes.id,
                        nm_promocao, 
                        de_promocao, 
                        id_desconto,
                        nm_desconto,
                        date_format(dt_vigencia_inicio, "%d/%m/%Y") AS dt_vigencia_inicio, 
                        date_format(dt_vigencia_fim, "%d/%m/%Y") AS dt_vigencia_fim
                   FROM tc_promocoes
              LEFT JOIN tc_descontos ON tc_descontos.id = tc_promocoes.id_desconto
                   
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_promocoes = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]                 = $aResultado['id']; 
        $this->NM_PROMOCAO[]        = $aResultado['nm_promocao']; 
        $this->DE_PROMOCAO[]        = $aResultado['de_promocao']; 
        $this->ID_DESCONTO[]        = $aResultado['id_desconto']; 
        $this->DT_VIGENCIA_INICIO[] = $aResultado['dt_vigencia_inicio']; 
        $this->DT_VIGENCIA_FIM[]    = $aResultado['dt_vigencia_fim']; 
        $this->NM_DESCONTO[]        = $aResultado['nm_desconto']; 
      }  
    }
  }
?>
