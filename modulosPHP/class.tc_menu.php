
<?php
  /**
   * Descricao
   *
   * @package    Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       06-04-2012
   **/

  class tc_menu {
  
    public $id;
    public $nm_menu;
    public $nm_grupo;
    public $de_hint;
    public $tx_link;
    public $nr_ordem;
    public $sq_pag;
    public $nr_acesso;
    public $id_pagpai;
    public    $iCdMsg;
    public    $sMsg;
    public    $aMsg;
    public    $sErro;
    protected $DB_LINK;

    public function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
    }


    public function getNm_menu($nm_menu) {
      return $this->nm_menu;
    }

    public function getNm_grupo($nm_grupo) {
      return $this->nm_grupo;
    }

    public function getDe_hint($de_hint) {
      return $this->de_hint;
    }

    public function getTx_link($tx_link) {
      return $this->tx_link;
    }

    public function getNr_ordem($nr_ordem) {
      return $this->nr_ordem;
    }

    public function getSq_pag($sq_pag) {
      return $this->sq_pag;
    }

    public function getNr_acesso($nr_acesso) {
      return $this->nr_acesso;
    }

    public function getId_pagpai($id_pagpai) {
      return $this->id_pagpai;
    }



    public function setNm_menu($nm_menu) {
      $this->nm_menu = $nm_menu;
    }

    public function setNm_grupo($nm_grupo) {
      $this->nm_grupo = $nm_grupo;
    }

    public function setDe_hint($de_hint) {
      $this->de_hint = $de_hint;
    }

    public function setTx_link($tx_link) {
      $this->tx_link = $tx_link;
    }

    public function setNr_ordem($nr_ordem) {
      $this->nr_ordem = $nr_ordem;
    }

    public function setSq_pag($sq_pag) {
      $this->sq_pag = $sq_pag;
    }

    public function setNr_acesso($nr_acesso) {
      $this->nr_acesso = $nr_acesso;
    }

    public function setId_pagpai($id_pagpai) {
      $this->id_pagpai = $id_pagpai;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_menu, 
                        nm_grupo, 
                        de_hint, 
                        tx_link, 
                        nr_ordem, 
                        sq_pag, 
                        nr_acesso, 
                        id_pagpai 
                   FROM tc_menu
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_menu = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]        = $aResultado['id']; 
        $this->NM_MENU[]   = $aResultado['nm_menu']; 
        $this->NM_GRUPO[]  = $aResultado['nm_grupo']; 
        $this->DE_HINT[]   = $aResultado['de_hint']; 
        $this->TX_LINK[]   = $aResultado['tx_link']; 
        $this->NR_ORDEM[]  = $aResultado['nr_ordem']; 
        $this->SQ_PAG[]    = $aResultado['sq_pag']; 
        $this->NR_ACESSO[] = $aResultado['nr_acesso']; 
        $this->ID_PAGPAI[] = $aResultado['id_pagpai']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_menu(
                             NM_MENU, 
                             NM_GRUPO, 
                             DE_HINT, 
                             TX_LINK, 
                             NR_ORDEM, 
                             SQ_PAG, 
                             NR_ACESSO, 
                             ID_PAGPAI 
)
      VALUES(
              '".$this->NM_MENU[0]."', 
              '".$this->NM_GRUPO[0]."', 
              '".$this->DE_HINT[0]."', 
              '".$this->TX_LINK[0]."', 
              '".$this->NR_ORDEM[0]."', 
              '".$this->SQ_PAG[0]."', 
              '".$this->NR_ACESSO[0]."', 
              '".$this->ID_PAGPAI[0]."' 
    )";
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        $this->iCdMsg = 1;
        $this->sMsg  = 'Ocorreu um erro ao salvar o registro.';
        $this->sErro = mysql_error();
    		$this->sResultado = 'erro';
        $bSucesso = false;

    	} else {
        $this->iCdMsg = 0;
        $this->sMsg  = 'O registro foi adicionado com sucesso!';
        $this->sResultado = 'sucesso';
        $bSucesso = true;
      }

      // Monta array com mensagem de retorno
      $this->aMsg = array('iCdMsg' => $this->iCdMsg,
                            'sMsg' => $this->sMsg,
                      'sResultado' => $this->sResultado );
      return $bSucesso;
    }

    public function remover($iId = '') {
      $sQuery = "DELETE FROM tc_menu
                       WHERE id = ".$iId;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        $this->iCdMsg = 1;
        $this->sMsg  = 'Ocorreu um erro ao remover o registro.';
        $this->sErro = mysql_error();
        $this->sResultado = 'erro';
        $bSucesso = false;

      } else {
        $this->iCdMsg = 0;
        $this->sMsg  = 'O registro foi removido com sucesso!';
        $this->sResultado = 'sucesso';
        $bSucesso = true;
      }

    // Monta array com mensagem de retorno
    $this->aMsg = array('iCdMsg' => $this->iCdMsg,
                          'sMsg' => $this->sMsg,
                    'sResultado' => $this->sResultado );
    return $bSucesso;
  }

    public function editar($iId = '') {
      $sQuery = "UPDATE tc_menu
        SET
          nm_menu   = '".$this->NM_MENU[0]."', 
          nm_grupo  = '".$this->NM_GRUPO[0]."', 
          de_hint   = '".$this->DE_HINT[0]."', 
          tx_link   = '".$this->TX_LINK[0]."', 
          nr_ordem  = '".$this->NR_ORDEM[0]."', 
          sq_pag    = '".$this->SQ_PAG[0]."', 
          nr_acesso = '".$this->NR_ACESSO[0]."', 
          id_pagpai = '".$this->ID_PAGPAI[0]."' 
          WHERE id = ".$iId;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        $this->iCdMsg = 1;
        $this->sMsg  = 'Ocorreu um erro ao salvar o registro.';
        $this->sErro = mysql_error();
    		$this->sResultado = 'erro';
        $bSucesso = false;

    	} else {
        $this->iCdMsg = 0;
        $this->sMsg  = 'O registro foi editado com sucesso!';
        $this->sResultado = 'sucesso';
        $bSucesso = true;
      }
    // Monta array com mensagem de retorno
    $this->aMsg = array('iCdMsg' => $this->iCdMsg,
                          'sMsg' => $this->sMsg,
                    'sResultado' => $this->sResultado );
    return $bSucesso;
     
    }

    public function inicializaAtributos() {

      $this->ID[0]        = '';
      $this->NM_MENU[0]   = '';
      $this->NM_GRUPO[0]  = '';
      $this->DE_HINT[0]   = '';
      $this->TX_LINK[0]   = '';
      $this->NR_ORDEM[0]  = '';
      $this->SQ_PAG[0]    = '';
      $this->NR_ACESSO[0] = '';
      $this->ID_PAGPAI[0] = '';
      
    }
  }