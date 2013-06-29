
<?php
  /**
   * Descricao
   *
   * @package    Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       06-04-2012
   **/

  class tc_marcas {
  
    public $id;
    public $nm_marca;
    public $de_marca;
    public $tx_meta_title;
    public $tx_meta_description;
    public $tx_keywords;
    public $cd_status;
    public    $iCdMsg;
    public    $sMsg;
    public    $aMsg;
    public    $sErro;
    protected $DB_LINK;

    public function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
    }


    public function getNm_marca($nm_marca) {
      return $this->nm_marca;
    }

    public function getDe_marca($de_marca) {
      return $this->de_marca;
    }

    public function getTx_meta_title($tx_meta_title) {
      return $this->tx_meta_title;
    }

    public function getTx_meta_description($tx_meta_description) {
      return $this->tx_meta_description;
    }

    public function getTx_keywords($tx_keywords) {
      return $this->tx_keywords;
    }

    public function getCd_status($cd_status) {
      return $this->cd_status;
    }



    public function setNm_marca($nm_marca) {
      $this->nm_marca = $nm_marca;
    }

    public function setDe_marca($de_marca) {
      $this->de_marca = $de_marca;
    }

    public function setTx_meta_title($tx_meta_title) {
      $this->tx_meta_title = $tx_meta_title;
    }

    public function setTx_meta_description($tx_meta_description) {
      $this->tx_meta_description = $tx_meta_description;
    }

    public function setTx_keywords($tx_keywords) {
      $this->tx_keywords = $tx_keywords;
    }

    public function setCd_status($cd_status) {
      $this->cd_status = $cd_status;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_marca, 
                        de_marca, 
                        tx_meta_title, 
                        tx_meta_description, 
                        tx_keywords, 
                        cd_status 
                   FROM tc_marcas
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_marcas = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]                  = $aResultado['id']; 
        $this->NM_MARCA[]            = $aResultado['nm_marca']; 
        $this->DE_MARCA[]            = $aResultado['de_marca']; 
        $this->TX_META_TITLE[]       = $aResultado['tx_meta_title']; 
        $this->TX_META_DESCRIPTION[] = $aResultado['tx_meta_description']; 
        $this->TX_KEYWORDS[]         = $aResultado['tx_keywords']; 
        $this->CD_STATUS[]           = $aResultado['cd_status']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_marcas(
                             NM_MARCA, 
                             DE_MARCA, 
                             TX_META_TITLE, 
                             TX_META_DESCRIPTION, 
                             TX_KEYWORDS, 
                             CD_STATUS 
)
      VALUES(
              '".$this->NM_MARCA[0]."', 
              '".$this->DE_MARCA[0]."', 
              '".$this->TX_META_TITLE[0]."', 
              '".$this->TX_META_DESCRIPTION[0]."', 
              '".$this->TX_KEYWORDS[0]."', 
              '".$this->CD_STATUS[0]."' 
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
      $sQuery = "DELETE FROM tc_marcas
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
      $sQuery = "UPDATE tc_marcas
        SET
          nm_marca            = '".$this->NM_MARCA[0]."', 
          de_marca            = '".$this->DE_MARCA[0]."', 
          tx_meta_title       = '".$this->TX_META_TITLE[0]."', 
          tx_meta_description = '".$this->TX_META_DESCRIPTION[0]."', 
          tx_keywords         = '".$this->TX_KEYWORDS[0]."', 
          cd_status           = '".$this->CD_STATUS[0]."' 
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

      $this->ID[0]                  = '';
      $this->NM_MARCA[0]            = '';
      $this->DE_MARCA[0]            = '';
      $this->TX_META_TITLE[0]       = '';
      $this->TX_META_DESCRIPTION[0] = '';
      $this->TX_KEYWORDS[0]         = '';
      $this->CD_STATUS[0]           = '';
      
    }
  }