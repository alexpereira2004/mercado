<?php
/**
 * Manipulação de dados de clientes
 *
 * @author Alex Lunardelli
 */

include_once 'class.tc_clientes.php';
include_once 'class.tc_clientes_enderecos.php';
include_once 'class.wTools.php';
class clientes {

  public $aMsg = array();

  public function  __construct() {
    $this->oCli = new tc_clientes();
    $this->oEnd = new tc_clientes_enderecos();
    $this->oUtil = new wTools();
  }

  public function inicializaAtributos() {
    $this->oCli->inicializaAtributos();
    $this->oEnd->inicializaAtributos();
  }

  public function inserir() {

    try {
      $this->oCli->inserir();
      if($this->oCli->iCdMsg == 0) {
        $aRet = $this->oUtil->pegaInfoDB('tc_clientes', 'max(id)');
        $this->oEnd->ID_CLIENTE[0] = $aRet[0];
        $this->oEnd->inserir();
      }

    } catch (Exception $oEx) {

    }
  }

  public function listar($sWhere = '') {
      $sSQL = "  SELECT tc_clientes.id,
                        tc_clientes.nm_cliente,
                        tc_clientes.nm_sobrenome,
                        tc_clientes.tx_tel_fixo,
                        tc_clientes.tx_tel_cel,
                        tc_clientes.tx_email,
                        tc_clientes.cd_sexo,
                        date_format(tc_clientes.dt_nascimento, \"%d/%m/%Y\") AS dt_nascimento,
                        tc_clientes.nm_razao_social,
                        tc_clientes.nm_fantasia,
                        tc_clientes.nu_cnpj,
                        concat(mid(tc_clientes.nu_cnpj,1,2), '.', mid(tc_clientes.nu_cnpj,3,3), '.', mid(tc_clientes.nu_cnpj,6,3), '/', mid(tc_clientes.nu_cnpj,9,4), '.', mid(tc_clientes.nu_cnpj,13,2)) as nu_cnpj_formatado,
                        tc_clientes.nu_ie,
                        date_format(tc_clientes.dt_fundacao, \"%d/%m/%Y\") AS dt_fundacao,
                        tc_clientes.tx_login,
                        tc_clientes.tx_pass,
                        tc_clientes.cd_recebe_news,

                        -- Tb Endereços
                        tc_clientes_enderecos.nm_logradouro,
                        tc_clientes_enderecos.tp_logradouro,
                        tc_clientes_enderecos.tx_numero,
                        tc_clientes_enderecos.tx_complemento,
                        tc_clientes_enderecos.nu_cep,
                        tc_clientes_enderecos.tx_bairro,
                        tc_clientes_enderecos.id_uf,
                        tc_clientes_enderecos.id_cid,
                        tc_clientes_enderecos.id_cliente

                   FROM tc_clientes
              LEFT JOIN tc_clientes_enderecos ON(tc_clientes_enderecos.id_cliente = tc_clientes.id )
       ".$sWhere;

    $sResultado = mysql_query($sSQL, $this->oCli->DB_LINK);

    if (!$sResultado) {
      die('Erro ao criar a listagem: ' . mysql_error());
      return false;
    }

    //$this->iLinhasTc_clientes = mysql_num_rows($sResultado);
    $this->iLinhas = mysql_num_rows($sResultado);

    while ($aResultado = mysql_fetch_array($sResultado)) {
      $this->oCli->ID[]              = $aResultado['id'];
      $this->oCli->NM_CLIENTE[]      = $aResultado['nm_cliente'];
      $this->oCli->NM_SOBRENOME[]    = $aResultado['nm_sobrenome'];
      $this->oCli->TX_TEL_FIXO[]     = $aResultado['tx_tel_fixo'];
      $this->oCli->TX_TEL_CEL[]      = $aResultado['tx_tel_cel'];
      $this->oCli->TX_EMAIL[]        = $aResultado['tx_email'];
      $this->oCli->CD_SEXO[]         = $aResultado['cd_sexo'];
      $this->oCli->DT_NASCIMENTO[]   = $aResultado['dt_nascimento'];
      $this->oCli->NM_RAZAO_SOCIAL[] = $aResultado['nm_razao_social'];
      $this->oCli->NM_FANTASIA[]     = $aResultado['nm_fantasia'];
      $this->oCli->NU_CNPJ[]         = $aResultado['nu_cnpj_formatado'];
      $this->oCli->NU_IE[]           = $aResultado['nu_ie'];
      $this->oCli->DT_FUNDACAO[]     = $aResultado['dt_fundacao'];
      $this->oCli->TX_LOGIN[]        = $aResultado['tx_login'];
      $this->oCli->TX_PASS[]         = $aResultado['tx_pass'];
      $this->oCli->CD_RECEBE_NEWS[]  = $aResultado['cd_recebe_news'];

      $this->oEnd->NM_LOGRADOURO[]  = $aResultado['nm_logradouro'];
      $this->oEnd->TP_LOGRADOURO[]  = $aResultado['tp_logradouro'];
      $this->oEnd->TX_NUMERO[]      = $aResultado['tx_numero'];
      $this->oEnd->TX_COMPLEMENTO[] = $aResultado['tx_complemento'];
      $this->oEnd->NU_CEP[]         = $aResultado['nu_cep'];
      $this->oEnd->TX_BAIRRO[]      = $aResultado['tx_bairro'];
      $this->oEnd->ID_UF[]          = $aResultado['id_uf'];
      $this->oEnd->ID_CID[]         = $aResultado['id_cid'];
      $this->oEnd->ID_CLIENTE[]     = $aResultado['id_cliente'];
    }
    return true;
  }

  public function editar($iId) {

    try {
      if (!$this->oCli->editar($iId)) {        
        throw new Exception;
      }
      if (!$this->oEnd->editar($iId)) {
        $this->aMsg = $this->oEnd->aMsg;
        throw new Exception;
      }
      
      $this->aMsg = $this->oCli->aMsg;
      $bRes = true;

    } catch (Exception $exc) {
      $bRes = false;
    }

    return $bRes;
  }

}
?>
