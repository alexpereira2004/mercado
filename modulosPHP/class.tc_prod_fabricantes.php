
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       05-01-2013
   **/

  class tc_prod_fabricantes {
  
    public    $id;
    public    $nm_fabricante;
    public    $tx_sound;
    public    $nu_visualizacoes;
    public    $cd_status;
    public    $de_fabricante;
    public    $tx_meta_title;
    public    $tx_meta_description;
    public    $tx_keywords;
    public    $tx_link;
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
        $aValidar = array ( 1 => array('Fabricante'    , $_POST['CMPprod-fabricantes-fabricante'], 'varchar', true),
                            5 => array('Fabricante'    , $_POST['CMPprod-fabricantes-fabricante'], 'text', true),
                            6 => array('Meta-title'    , $_POST['CMPprod-fabricantes-meta-title'], 'varchar', true),
                            7 => array('Meta-description' , $_POST['CMPprod-fabricantes-meta-description'], 'text', true),
                            8 => array('Keywords'      , $_POST['CMPprod-fabricantes-keywords'], 'varchar', true),
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

          if ($this->inserir()) {
            $this->oUtil->redirFRM($this->sBackpage, $this->aMsg);
            header('location:'.$this->sBackpage);
            exit;
          }
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

      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_fabricante, 
                        tx_sound, 
                        nu_visualizacoes, 
                        cd_status, 
                        de_fabricante, 
                        tx_meta_title, 
                        tx_meta_description, 
                        tx_keywords, 
                        tx_link 
                   FROM tc_prod_fabricantes
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_prod_fabricantes = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]                  = $aResultado['id']; 
        $this->NM_FABRICANTE[]       = $aResultado['nm_fabricante']; 
        $this->TX_SOUND[]            = $aResultado['tx_sound']; 
        $this->NU_VISUALIZACOES[]    = $aResultado['nu_visualizacoes']; 
        $this->CD_STATUS[]           = $aResultado['cd_status']; 
        $this->DE_FABRICANTE[]       = $aResultado['de_fabricante']; 
        $this->TX_META_TITLE[]       = $aResultado['tx_meta_title']; 
        $this->TX_META_DESCRIPTION[] = $aResultado['tx_meta_description']; 
        $this->TX_KEYWORDS[]         = $aResultado['tx_keywords']; 
        $this->TX_LINK[]             = $aResultado['tx_link']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_prod_fabricantes(
                             NM_FABRICANTE, 
                             TX_SOUND, 
                             NU_VISUALIZACOES, 
                             CD_STATUS, 
                             DE_FABRICANTE, 
                             TX_META_TITLE, 
                             TX_META_DESCRIPTION, 
                             TX_KEYWORDS, 
                             TX_LINK 
)
      VALUES(
              '".$this->NM_FABRICANTE[0]."', 
              '".$this->TX_SOUND[0]."', 
              '".$this->NU_VISUALIZACOES[0]."', 
              '".$this->CD_STATUS[0]."', 
              '".$this->DE_FABRICANTE[0]."', 
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
      $sQuery = "DELETE FROM tc_prod_fabricantes ".$sWhere;
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
      $sQuery = "UPDATE tc_prod_fabricantes
        SET
          nm_fabricante       = '".$this->NM_FABRICANTE[0]."', 
          tx_sound            = '".$this->TX_SOUND[0]."', 
          nu_visualizacoes    = '".$this->NU_VISUALIZACOES[0]."', 
          cd_status           = '".$this->CD_STATUS[0]."', 
          de_fabricante       = '".$this->DE_FABRICANTE[0]."', 
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

      $this->ID[0]                  = (isset ($_POST['CMPprod-fabricantes-id'])               ? $_POST['CMPprod-fabricantes-id']               : '');
      $this->NM_FABRICANTE[0]       = (isset ($_POST['CMPprod-fabricantes-fabricante'])       ? $_POST['CMPprod-fabricantes-fabricante']       : '');
      $this->TX_SOUND[0]            = (isset ($_POST['CMPprod-fabricantes-sound'])            ? $_POST['CMPprod-fabricantes-sound']            : '');
      $this->NU_VISUALIZACOES[0]    = (isset ($_POST['CMPprod-fabricantes-visualizacoes'])    ? $_POST['CMPprod-fabricantes-visualizacoes']    : 0);
      $this->CD_STATUS[0]           = (isset ($_POST['CMPprod-fabricantes-status'])           ? $_POST['CMPprod-fabricantes-status']           : '');
      $this->DE_FABRICANTE[0]       = (isset ($_POST['CMPprod-fabricantes-descricao'])        ? $_POST['CMPprod-fabricantes-descricao']        : '');
      $this->TX_META_TITLE[0]       = (isset ($_POST['CMPprod-fabricantes-meta-title'])       ? $_POST['CMPprod-fabricantes-meta-title']       : '');
      $this->TX_META_DESCRIPTION[0] = (isset ($_POST['CMPprod-fabricantes-meta-description']) ? $_POST['CMPprod-fabricantes-meta-description'] : '');
      $this->TX_KEYWORDS[0]         = (isset ($_POST['CMPprod-fabricantes-keywords'])         ? $_POST['CMPprod-fabricantes-keywords']         : '');
      $this->TX_LINK[0]             = (isset ($_POST['CMPprod-fabricantes-link'])             ? $_POST['CMPprod-fabricantes-link']             : '');
      
    }
  }