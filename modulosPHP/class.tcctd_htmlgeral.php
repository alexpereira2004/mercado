
<?php
  /**
   * Descricao
   *
   * @package    Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       31-12-2012
   **/

  class tcctd_htmlgeral {
  
    public    $id;
    public    $nm_pagina;
    public    $tx_conteudo;
    public    $tx_meta_titu;
    public    $de_meta_tag;
    public    $tx_tags;
    public    $tx_link;
    public    $tp_secao;
    public    $tx_arq_css;
    public    $iCdMsg;
    public    $sMsg;
    public    $aMsg;
    public    $sErro;
    public    $sBackpage;
    protected $DB_LINK;
    protected $oUtil;

    public function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
      $this->oUtil   = new wTools();
    }

    public function salvar() {

      try {
        $aValidar = array ( 1 => array('Pagina' , $_POST['CMPhtmlgeral-pagina'], 'varchar(100)', true),
                            2 => array('Conteudo' , $_POST['CMPhtmlgeral-conteudo'], 'text', true),
                            3 => array('Meta-titu' , $_POST['CMPhtmlgeral-meta-titu'], 'varchar(100)', true),
                            4 => array('Meta-tag' , $_POST['CMPhtmlgeral-meta-tag'], 'text', true),
                            5 => array('Tags' , $_POST['CMPhtmlgeral-tags'], 'text', true),
                            6 => array('Link' , $_POST['CMPhtmlgeral-link'], 'varchar(200)', true),
                            7 => array('Secao' , $_POST['CMPhtmlgeral-secao'], 'varchar(25)', true),
                            8 => array('Arq-css' , $_POST['CMPhtmlgeral-arq-css'], 'varchar(100)', true),
                            );

        // Validação de preenchimento
        if ($this->oUtil->valida_Preenchimento($aValidar) !== true) {
          $this->aMsg = $this->oUtil->aMsg;
          throw new excecoes(25);
        }

        // Editar conteúdo
        if ($_POST['sAcao'] == 'editar') {
          $this->editar($this->ID[0]);

        } elseif ($_POST['sAcao'] == 'inserir') {
          $this->inserir();
          $this->oUtil->redirFRM($this->sBackpage, $this->aMsg);
          header('location:'.$this->sBackpage);
          exit;
        } else {

          // Tipo de ação não é válido
          throw new excecoes(20, $this->oUtil->anti_sql_injection($_POST['CMPpgAtual']));
        }

      } catch (excecoes $e) {
        $e->bReturnMsg = false;
        $e->getErrorByCode();
        if (is_array($e->aMsg)) {
          $this->aMsg = $e->aMsg;
        }
        return false;
      }
      return true;
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
                   FROM tcctd_htmlgeral
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTcctd_htmlgeral = mysql_num_rows($sResultado);
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
      $sQuery = "INSERT INTO tcctd_htmlgeral(
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
      $sQuery = "DELETE FROM tcctd_htmlgeral ".$sFiltro;

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
      $sQuery = "UPDATE tcctd_htmlgeral
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

      $this->ID[0]           = (isset ($_POST['CMPhtmlgeral-id'])           ? $_POST['CMPhtmlgeral-id']           : '');
      $this->NM_PAGINA[0]    = (isset ($_POST['CMPhtmlgeral-pagina'])    ? $_POST['CMPhtmlgeral-pagina']    : '');
      $this->TX_CONTEUDO[0]  = (isset ($_POST['CMPhtmlgeral-conteudo'])  ? $_POST['CMPhtmlgeral-conteudo']  : '');
      $this->TX_META_TITU[0] = (isset ($_POST['CMPhtmlgeral-meta-titu']) ? $_POST['CMPhtmlgeral-meta-titu'] : '');
      $this->DE_META_TAG[0]  = (isset ($_POST['CMPhtmlgeral-meta-tag'])  ? $_POST['CMPhtmlgeral-meta-tag']  : '');
      $this->TX_TAGS[0]      = (isset ($_POST['CMPhtmlgeral-tags'])      ? $_POST['CMPhtmlgeral-tags']      : '');
      $this->TX_LINK[0]      = (isset ($_POST['CMPhtmlgeral-link'])      ? $_POST['CMPhtmlgeral-link']      : '');
      $this->TP_SECAO[0]     = (isset ($_POST['CMPhtmlgeral-secao'])     ? $_POST['CMPhtmlgeral-secao']     : '');
      $this->TX_ARQ_CSS[0]   = (isset ($_POST['CMPhtmlgeral-arq-css'])   ? $_POST['CMPhtmlgeral-arq-css']   : '');
      
    }
  }