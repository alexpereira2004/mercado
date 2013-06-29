
<?php
  /**
   * Descricao
   *
   * @package    Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       06-04-2012
   **/

  class tc_htmlgeral {
  
    public $id;
    public $nm_pagina;
    public $tx_conteudo;
    public $tx_meta_titu;
    public $de_meta_tag;
    public $tx_tags;
    public $tx_link;
    public $tp_secao;
    public $tx_arq_css;
    public    $iCdMsg;
    public    $sMsg;
    public    $aMsg;
    public    $sErro;
    protected $DB_LINK;

    public function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
    }


    public function getNm_pagina($nm_pagina) {
      return $this->nm_pagina;
    }

    public function getTx_conteudo($tx_conteudo) {
      return $this->tx_conteudo;
    }

    public function getTx_meta_titu($tx_meta_titu) {
      return $this->tx_meta_titu;
    }

    public function getDe_meta_tag($de_meta_tag) {
      return $this->de_meta_tag;
    }

    public function getTx_tags($tx_tags) {
      return $this->tx_tags;
    }

    public function getTx_link($tx_link) {
      return $this->tx_link;
    }

    public function getTp_secao($tp_secao) {
      return $this->tp_secao;
    }

    public function getTx_arq_css($tx_arq_css) {
      return $this->tx_arq_css;
    }



    public function setNm_pagina($nm_pagina) {
      $this->nm_pagina = $nm_pagina;
    }

    public function setTx_conteudo($tx_conteudo) {
      $this->tx_conteudo = $tx_conteudo;
    }

    public function setTx_meta_titu($tx_meta_titu) {
      $this->tx_meta_titu = $tx_meta_titu;
    }

    public function setDe_meta_tag($de_meta_tag) {
      $this->de_meta_tag = $de_meta_tag;
    }

    public function setTx_tags($tx_tags) {
      $this->tx_tags = $tx_tags;
    }

    public function setTx_link($tx_link) {
      $this->tx_link = $tx_link;
    }

    public function setTp_secao($tp_secao) {
      $this->tp_secao = $tp_secao;
    }

    public function setTx_arq_css($tx_arq_css) {
      $this->tx_arq_css = $tx_arq_css;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_pagina, 
                        tx_conteudo, 
                        tx_meta_titu, 
                        de_meta_tag, 
                        tx_tags, 
                        tx_link, 
                        tp_secao, 
                        tx_arq_css 
                   FROM tc_htmlgeral
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_htmlgeral = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]           = $aResultado['id']; 
        $this->NM_PAGINA[]    = $aResultado['nm_pagina']; 
        $this->TX_CONTEUDO[]  = $aResultado['tx_conteudo']; 
        $this->TX_META_TITU[] = $aResultado['tx_meta_titu']; 
        $this->DE_META_TAG[]  = $aResultado['de_meta_tag']; 
        $this->TX_TAGS[]      = $aResultado['tx_tags']; 
        $this->TX_LINK[]      = $aResultado['tx_link']; 
        $this->TP_SECAO[]     = $aResultado['tp_secao']; 
        $this->TX_ARQ_CSS[]   = $aResultado['tx_arq_css']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_htmlgeral(
                             NM_PAGINA, 
                             TX_CONTEUDO, 
                             TX_META_TITU, 
                             DE_META_TAG, 
                             TX_TAGS, 
                             TX_LINK, 
                             TP_SECAO, 
                             TX_ARQ_CSS 
)
      VALUES(
              '".$this->NM_PAGINA[0]."', 
              '".$this->TX_CONTEUDO[0]."', 
              '".$this->TX_META_TITU[0]."', 
              '".$this->DE_META_TAG[0]."', 
              '".$this->TX_TAGS[0]."', 
              '".$this->TX_LINK[0]."', 
              '".$this->TP_SECAO[0]."', 
              '".$this->TX_ARQ_CSS[0]."' 
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

    public function remover($sFiltro) {
      $sQuery = "DELETE FROM tc_htmlgeral ".$sFiltro;

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
      $sQuery = "UPDATE tc_htmlgeral
        SET
          nm_pagina    = '".$this->NM_PAGINA[0]."', 
          tx_conteudo  = '".$this->TX_CONTEUDO[0]."', 
          tx_meta_titu = '".$this->TX_META_TITU[0]."', 
          de_meta_tag  = '".$this->DE_META_TAG[0]."', 
          tx_tags      = '".$this->TX_TAGS[0]."', 
          tx_link      = '".$this->TX_LINK[0]."', 
          tp_secao     = '".$this->TP_SECAO[0]."', 
          tx_arq_css   = '".$this->TX_ARQ_CSS[0]."' 
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

      $this->ID[0]           = '';
      $this->NM_PAGINA[0]    = '';
      $this->TX_CONTEUDO[0]  = '';
      $this->TX_META_TITU[0] = '';
      $this->DE_META_TAG[0]  = '';
      $this->TX_TAGS[0]      = '';
      $this->TX_LINK[0]      = '';
      $this->TP_SECAO[0]     = '';
      $this->TX_ARQ_CSS[0]   = '';
      
    }
  }