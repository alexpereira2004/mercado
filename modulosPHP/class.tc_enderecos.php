
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       27-03-2013
   **/

  class tc_enderecos {
  
    public    $id;
    public    $nm_logradouro;
    public    $tp_logradouro;
    public    $tx_numero;
    public    $tx_complemento;
    public    $nu_cep;
    public    $tx_bairro;
    public    $nm_uf;
    public    $nm_cid;
    public    $cd_ref;
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
        $aValidar = array ( 1 => array('Logradouro' , $_POST['CMPenderecos-logradouro'], 'varchar(120)', true),
                            2 => array('Logradouro' , $_POST['CMPenderecos-logradouro'], 'varchar(30)', true),
                            3 => array('Numero' , $_POST['CMPenderecos-numero'], 'varchar(20)', true),
                            4 => array('Complemento' , $_POST['CMPenderecos-complemento'], 'varchar(120)', true),
                            5 => array('Cep' , $_POST['CMPenderecos-cep'], 'int(10)', true),
                            6 => array('Bairro' , $_POST['CMPenderecos-bairro'], 'varchar(50)', true),
                            7 => array('Uf' , $_POST['CMPenderecos-uf'], 'varchar(2)', true),
                            8 => array('Cid' , $_POST['CMPenderecos-cid'], 'varchar(60)', true),
                            9 => array('Ref' , $_POST['CMPenderecos-ref'], 'varchar(8)', true),
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
                        nm_logradouro, 
                        tp_logradouro, 
                        tx_numero, 
                        tx_complemento, 
                        nu_cep, 
                        tx_bairro, 
                        nm_uf, 
                        nm_cid, 
                        cd_ref 
                   FROM tc_enderecos
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_enderecos = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]             = $aResultado['id']; 
        $this->NM_LOGRADOURO[]  = $aResultado['nm_logradouro']; 
        $this->TP_LOGRADOURO[]  = $aResultado['tp_logradouro']; 
        $this->TX_NUMERO[]      = $aResultado['tx_numero']; 
        $this->TX_COMPLEMENTO[] = $aResultado['tx_complemento']; 
        $this->NU_CEP[]         = $aResultado['nu_cep']; 
        $this->TX_BAIRRO[]      = $aResultado['tx_bairro']; 
        $this->NM_UF[]          = $aResultado['nm_uf']; 
        $this->NM_CID[]         = $aResultado['nm_cid']; 
        $this->CD_REF[]         = $aResultado['cd_ref']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_enderecos(
                             NM_LOGRADOURO, 
                             TP_LOGRADOURO, 
                             TX_NUMERO, 
                             TX_COMPLEMENTO, 
                             NU_CEP, 
                             TX_BAIRRO, 
                             NM_UF, 
                             NM_CID, 
                             CD_REF 
)
      VALUES(
              '".$this->NM_LOGRADOURO[0]."', 
              '".$this->TP_LOGRADOURO[0]."', 
              '".$this->TX_NUMERO[0]."', 
              '".$this->TX_COMPLEMENTO[0]."', 
              '".$this->NU_CEP[0]."', 
              '".$this->TX_BAIRRO[0]."', 
              '".$this->NM_UF[0]."', 
              '".$this->NM_CID[0]."', 
              '".$this->CD_REF[0]."' 
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
      $sQuery = "DELETE FROM tc_enderecos
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
      $sQuery = "UPDATE tc_enderecos
        SET
          nm_logradouro  = '".$this->NM_LOGRADOURO[0]."', 
          tp_logradouro  = '".$this->TP_LOGRADOURO[0]."', 
          tx_numero      = '".$this->TX_NUMERO[0]."', 
          tx_complemento = '".$this->TX_COMPLEMENTO[0]."', 
          nu_cep         = '".$this->NU_CEP[0]."', 
          tx_bairro      = '".$this->TX_BAIRRO[0]."', 
          nm_uf          = '".$this->NM_UF[0]."', 
          nm_cid         = '".$this->NM_CID[0]."', 
          cd_ref         = '".$this->CD_REF[0]."' 
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

      $this->ID[0]             = (isset ($_POST['CMPenderecos-id'])             ? $_POST['CMPenderecos-id']             : '');
      $this->NM_LOGRADOURO[0]  = (isset ($_POST['CMPenderecos-logradouro'])  ? $_POST['CMPenderecos-logradouro']  : '');
      $this->TP_LOGRADOURO[0]  = (isset ($_POST['CMPenderecos-logradouro'])  ? $_POST['CMPenderecos-logradouro']  : '');
      $this->TX_NUMERO[0]      = (isset ($_POST['CMPenderecos-numero'])      ? $_POST['CMPenderecos-numero']      : '');
      $this->TX_COMPLEMENTO[0] = (isset ($_POST['CMPenderecos-complemento']) ? $_POST['CMPenderecos-complemento'] : '');
      $this->NU_CEP[0]         = (isset ($_POST['CMPenderecos-cep'])         ? $_POST['CMPenderecos-cep']         : '');
      $this->TX_BAIRRO[0]      = (isset ($_POST['CMPenderecos-bairro'])      ? $_POST['CMPenderecos-bairro']      : '');
      $this->NM_UF[0]          = (isset ($_POST['CMPenderecos-uf'])          ? $_POST['CMPenderecos-uf']          : '');
      $this->NM_CID[0]         = (isset ($_POST['CMPenderecos-cid'])         ? $_POST['CMPenderecos-cid']         : '');
      $this->CD_REF[0]         = (isset ($_POST['CMPenderecos-ref'])         ? $_POST['CMPenderecos-ref']         : '');
      
    }
  }