
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       27-03-2013
   **/

  class tc_transportadoras {
  
    public    $id;
    public    $nm_transportadora;
    public    $tx_tel;
    public    $id_endereco;
    public    $tx_obs;
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
        $aValidar = array ( 1 => array('Transportadora' , $_POST['CMPtransportadoras-transportadora'], 'texto'    , true),
                            2 => array('Telefone'       , $_POST['CMPtransportadoras-tel']           , 'telefone' , true),
                            3 => array('Endereco'       , $_POST['CMPtransportadoras-endereco']      , 'digito'   , true),
                            4 => array('Observação'     , $_POST['CMPtransportadoras-obs']           , 'texto'    , false),
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

      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_transportadora, 
                        tx_tel, 
                        id_endereco, 
                        tx_obs 
                   FROM tc_transportadoras
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_transportadoras = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]                = $aResultado['id']; 
        $this->NM_TRANSPORTADORA[] = $aResultado['nm_transportadora']; 
        $this->TX_TEL[]            = $aResultado['tx_tel']; 
        $this->ID_ENDERECO[]       = $aResultado['id_endereco']; 
        $this->TX_OBS[]            = $aResultado['tx_obs']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_transportadoras(
                             NM_TRANSPORTADORA, 
                             TX_TEL, 
                             ID_ENDERECO, 
                             TX_OBS 
)
      VALUES(
              '".$this->NM_TRANSPORTADORA[0]."', 
              '".$this->TX_TEL[0]."', 
              '".$this->ID_ENDERECO[0]."', 
              '".$this->TX_OBS[0]."' 
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
      $sQuery = "DELETE FROM tc_transportadoras ".$sWhere;
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
      $sQuery = "UPDATE tc_transportadoras
        SET
          nm_transportadora = '".$this->NM_TRANSPORTADORA[0]."', 
          tx_tel            = '".$this->TX_TEL[0]."', 
          id_endereco       = '".$this->ID_ENDERECO[0]."', 
          tx_obs            = '".$this->TX_OBS[0]."' 
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

      $this->ID[0]                = (isset ($_POST['CMPtransportadoras-id'])                ? $_POST['CMPtransportadoras-id']                : '');
      $this->NM_TRANSPORTADORA[0] = (isset ($_POST['CMPtransportadoras-transportadora']) ? $_POST['CMPtransportadoras-transportadora'] : '');
      $this->TX_TEL[0]            = (isset ($_POST['CMPtransportadoras-tel'])            ? $_POST['CMPtransportadoras-tel']            : '');
      $this->ID_ENDERECO[0]       = (isset ($_POST['CMPtransportadoras-endereco'])       ? $_POST['CMPtransportadoras-endereco']       : '');
      $this->TX_OBS[0]            = (isset ($_POST['CMPtransportadoras-obs'])            ? $_POST['CMPtransportadoras-obs']            : '');
      
    }
  }