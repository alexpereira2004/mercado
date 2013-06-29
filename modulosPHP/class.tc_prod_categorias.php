
<?php
  /**
   * Descricao
   *
   * @package    Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       06-04-2012
   **/

  class tc_prod_categorias {
  
    public $id;
    public $nm_categoria;
    public $tx_sound;
    public $nu_visualizacoes;
    public $cd_status;
    public $de_categoria;
    public $tx_meta_title;
    public $tx_meta_description;
    public $tx_keywords;
    public $tx_link;
    public    $iCdMsg;
    public    $sMsg;
    public    $aMsg;
    public    $sErro;
    protected $DB_LINK;

    public function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
    }


    public function getNm_categoria($nm_categoria) {
      return $this->nm_categoria;
    }

    public function getTx_sound($tx_sound) {
      return $this->tx_sound;
    }

    public function getNu_visualizacoes($nu_visualizacoes) {
      return $this->nu_visualizacoes;
    }

    public function getCd_status($cd_status) {
      return $this->cd_status;
    }

    public function getDe_categoria($de_categoria) {
      return $this->de_categoria;
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

    public function getTx_link($tx_link) {
      return $this->tx_link;
    }



    public function setNm_categoria($nm_categoria) {
      $this->nm_categoria = $nm_categoria;
    }

    public function setTx_sound($tx_sound) {
      $this->tx_sound = $tx_sound;
    }

    public function setNu_visualizacoes($nu_visualizacoes) {
      $this->nu_visualizacoes = $nu_visualizacoes;
    }

    public function setCd_status($cd_status) {
      $this->cd_status = $cd_status;
    }

    public function setDe_categoria($de_categoria) {
      $this->de_categoria = $de_categoria;
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

    public function setTx_link($tx_link) {
      $this->tx_link = $tx_link;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_categoria, 
                        tx_sound, 
                        nu_visualizacoes, 
                        cd_status, 
                        de_categoria, 
                        tx_meta_title, 
                        tx_meta_description, 
                        tx_keywords, 
                        tx_link 
                   FROM tc_prod_categorias
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_prod_categorias = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]                  = $aResultado['id']; 
        $this->NM_CATEGORIA[]        = $aResultado['nm_categoria']; 
        $this->TX_SOUND[]            = $aResultado['tx_sound']; 
        $this->NU_VISUALIZACOES[]    = $aResultado['nu_visualizacoes']; 
        $this->CD_STATUS[]           = $aResultado['cd_status']; 
        $this->DE_CATEGORIA[]        = $aResultado['de_categoria']; 
        $this->TX_META_TITLE[]       = $aResultado['tx_meta_title']; 
        $this->TX_META_DESCRIPTION[] = $aResultado['tx_meta_description']; 
        $this->TX_KEYWORDS[]         = $aResultado['tx_keywords']; 
        $this->TX_LINK[]             = $aResultado['tx_link']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_prod_categorias(
                             NM_CATEGORIA, 
                             TX_SOUND, 
                             NU_VISUALIZACOES, 
                             CD_STATUS, 
                             DE_CATEGORIA, 
                             TX_META_TITLE, 
                             TX_META_DESCRIPTION, 
                             TX_KEYWORDS, 
                             TX_LINK 
)
      VALUES(
              '".$this->NM_CATEGORIA[0]."', 
              '".$this->TX_SOUND[0]."', 
              '".$this->NU_VISUALIZACOES[0]."', 
              '".$this->CD_STATUS[0]."', 
              '".$this->DE_CATEGORIA[0]."', 
              '".$this->TX_META_TITLE[0]."', 
              '".$this->TX_META_DESCRIPTION[0]."', 
              '".$this->TX_KEYWORDS[0]."', 
              '".$this->TX_LINK[0]."' 
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

    public function remover($sWhere) {
      $sQuery = "DELETE FROM tc_prod_categorias ".$sWhere;
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
      $sQuery = "UPDATE tc_prod_categorias
        SET
          nm_categoria        = '".$this->NM_CATEGORIA[0]."', 
          tx_sound            = '".$this->TX_SOUND[0]."', 
          nu_visualizacoes    = '".$this->NU_VISUALIZACOES[0]."', 
          cd_status           = '".$this->CD_STATUS[0]."', 
          de_categoria        = '".$this->DE_CATEGORIA[0]."', 
          tx_meta_title       = '".$this->TX_META_TITLE[0]."', 
          tx_meta_description = '".$this->TX_META_DESCRIPTION[0]."', 
          tx_keywords         = '".$this->TX_KEYWORDS[0]."', 
          tx_link             = '".$this->TX_LINK[0]."' 
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
      $this->NM_CATEGORIA[0]        = '';
      $this->TX_SOUND[0]            = '';
      $this->NU_VISUALIZACOES[0]    = '';
      $this->CD_STATUS[0]           = '';
      $this->DE_CATEGORIA[0]        = '';
      $this->TX_META_TITLE[0]       = '';
      $this->TX_META_DESCRIPTION[0] = '';
      $this->TX_KEYWORDS[0]         = '';
      $this->TX_LINK[0]             = '';
      
    }
  }