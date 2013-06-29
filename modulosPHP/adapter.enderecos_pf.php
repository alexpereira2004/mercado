
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       19-01-2013
   **/

  class enderecos_pf {
  
    public    $id;
    public    $nm_logradouro;
    public    $tp_logradouro;
    public    $tx_numero;
    public    $tx_complemento;
    public    $nu_cep;
    public    $tx_bairro;
    public    $nm_uf;
    public    $nm_cid;
    public    $id_cliente;
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
        $aValidar = array ( 1 => array('Logradouro' , $_POST['CMPclientes-enderecos-logradouro-PF'], 'varchar(120)', true),
                            2 => array('Logradouro' , $_POST['CMPclientes-enderecos-logradouro-PF'], 'varchar(30)', true),
                            3 => array('Numero' , $_POST['CMPclientes-enderecos-numero-PF'], 'varchar(20)', true),
                            4 => array('Complemento' , $_POST['CMPclientes-enderecos-complemento-PF'], 'varchar(120)', true),
                            5 => array('Cep' , $_POST['CMPclientes-enderecos-cep-PF'], 'int', true),
                            6 => array('Bairro' , $_POST['CMPclientes-enderecos-bairro-PF'], 'varchar(50)', true),
                            7 => array('Uf' , $_POST['CMPclientes-enderecos-uf-PF'], 'int(8)', true),
                            8 => array('Cid' , $_POST['CMPclientes-enderecos-cid-PF'], 'int(8)', true),
                            9 => array('Cliente' , $_POST['CMPclientes-enderecos-cliente-PF'], 'int(8)', true),
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
                        id_cliente 
                   FROM tc_clientes_enderecos
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_clientes_enderecos = mysql_num_rows($sResultado);
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
        $this->ID_CLIENTE[]     = $aResultado['id_cliente']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_clientes_enderecos(
                             NM_LOGRADOURO, 
                             TP_LOGRADOURO, 
                             TX_NUMERO, 
                             TX_COMPLEMENTO, 
                             NU_CEP, 
                             TX_BAIRRO, 
                             NM_UF, 
                             NM_CID, 
                             ID_CLIENTE 
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
              '".$this->ID_CLIENTE[0]."' 
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
      $sQuery = "DELETE FROM tc_clientes_enderecos ".$sWhere;
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
      $sQuery = "UPDATE tc_clientes_enderecos
        SET
          nm_logradouro  = '".$this->NM_LOGRADOURO[0]."', 
          tp_logradouro  = '".$this->TP_LOGRADOURO[0]."', 
          tx_numero      = '".$this->TX_NUMERO[0]."', 
          tx_complemento = '".$this->TX_COMPLEMENTO[0]."', 
          nu_cep         = '".$this->NU_CEP[0]."', 
          tx_bairro      = '".$this->TX_BAIRRO[0]."', 
          nm_uf          = '".$this->NM_UF[0]."', 
          nm_cid         = '".$this->NM_CID[0]."', 
          id_cliente     = '".$this->ID_CLIENTE[0]."' 
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

      //$this->ID[0]             = (isset ($_POST['CMPclientes-enderecos-id'])             ? $_POST['CMPclientes-enderecos-id']             : '');
      $this->NM_LOGRADOURO[0]  = (isset ($_POST['CMPclientes-enderecos-logradouro-PF'])     ? $_POST['CMPclientes-enderecos-logradouro-PF']  : '');
      $this->TP_LOGRADOURO[0]  = (isset ($_POST['CMPclientes-enderecos-tp-logradouro-PF'])  ? $_POST['CMPclientes-enderecos-tp-logradouro-PF']  : '');
      $this->TX_NUMERO[0]      = (isset ($_POST['CMPclientes-enderecos-numero-PF'])         ? $_POST['CMPclientes-enderecos-numero-PF']      : '');
      $this->TX_COMPLEMENTO[0] = (isset ($_POST['CMPclientes-enderecos-complemento-PF'])    ? $_POST['CMPclientes-enderecos-complemento-PF'] : '');
      $this->NU_CEP[0]         = (isset ($_POST['CMPclientes-enderecos-cep-PF'])            ? $_POST['CMPclientes-enderecos-cep-PF']         : '');
      $this->TX_BAIRRO[0]      = (isset ($_POST['CMPclientes-enderecos-bairro-PF'])         ? $_POST['CMPclientes-enderecos-bairro-PF']      : '');
      $this->NM_UF[0]          = (isset ($_POST['CMPclientes-enderecos-uf-PF'])             ? $_POST['CMPclientes-enderecos-uf-PF']          : '');
      $this->NM_CID[0]         = (isset ($_POST['CMPclientes-enderecos-cid-PF'])            ? $_POST['CMPclientes-enderecos-cid-PF']         : '');
      $this->ID_CLIENTE[0]     = (isset ($_POST['CMPclientes-enderecos-cliente-PF'])        ? $_POST['CMPclientes-enderecos-cliente-PF']     : '');
      
    }
  }