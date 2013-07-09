
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       27-05-2013
   **/

  class tc_descontos {
  
    public    $id;
    public    $nm_desconto;
    public    $de_desconto;
    public    $tp_valor;
    public    $tp_desconto;
    public    $cd_status;
    public    $cd_abrangencia;
    public    $vl_min;
    public    $vl_desconto;
    public    $dt_vigencia_inicio;
    public    $dt_vigencia_fim;
    public    $dt_atu;
    public    $id_usu_atu;
    public    $dt_cad;
    public    $id_usu_cad;
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
      include 'config.php';
      try {
        $aValidar = array ( 1 => array('Nome do desconto'  , $_POST['CMPdescontos-nome'], 'varchar', true),
                            2 => array('Descrição'         , $_POST['CMPdescontos-desc'], 'varchar', true),
            
                            4 => array('Tipo do Desconto'  , $_POST['CMPdescontos-tipo-desc'], 'abrangencia', true, array_keys($CFGaTiposDesconto)),
                            5 => array('Compra mínima'     , $_POST['CMPdescontos-min'], 'float', true),
                            6 => array('Tipo do Valor'     , $_POST['CMPdescontos-tipo'], 'abrangencia', true, array_keys($CFGaTiposValoresDesconto)),
                            7 => array('Valor do Desconto' , $_POST['CMPdescontos-valor'], 'float', true),
            
                            9 => array('Status'            , $_POST['CMPdescontos-status'], 'varchar', true),
                           10 => array('Abrangência'       , $_POST['CMPdescontos-abrangencia'], 'varchar', false),
                           11 => array('Vigencia-inicio'   , $_POST['CMPdescontos-vigencia-inicio'], 'data', false),
                           12 => array('Vigencia-fim'      , $_POST['CMPdescontos-vigencia-fim'], 'data', false),
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
          //$this->oUtil->redirFRM($this->sBackpage, $this->aMsg);
          //header('location:'.$this->sBackpage);
          //exit;
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
                        nm_desconto, 
                        de_desconto, 
                        tp_valor, 
                        tp_desconto, 
                        cd_status, 
                        cd_abrangencia, 
                        vl_min, 
                        vl_desconto, 
                        date_format(dt_vigencia_inicio, "%d/%m/%Y") AS dt_vigencia_inicio, 
                        date_format(dt_vigencia_fim, "%d/%m/%Y") AS dt_vigencia_fim, 
                        date_format(dt_atu, "%d/%m/%Y") AS dt_atu, 
                        id_usu_atu, 
                        date_format(dt_cad, "%d/%m/%Y") AS dt_cad, 
                        id_usu_cad 
                   FROM tc_descontos
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_descontos = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]                 = $aResultado['id']; 
        $this->NM_DESCONTO[]        = $aResultado['nm_desconto']; 
        $this->DE_DESCONTO[]        = $aResultado['de_desconto']; 
        $this->TP_VALOR[]           = $aResultado['tp_valor']; 
        $this->TP_DESCONTO[]        = $aResultado['tp_desconto']; 
        $this->CD_STATUS[]          = $aResultado['cd_status']; 
        $this->CD_ABRANGENCIA[]     = $aResultado['cd_abrangencia']; 
        $this->VL_MIN[]             = $aResultado['vl_min']; 
        $this->VL_DESCONTO[]        = $aResultado['vl_desconto']; 
        $this->DT_VIGENCIA_INICIO[] = $aResultado['dt_vigencia_inicio']; 
        $this->DT_VIGENCIA_FIM[]    = $aResultado['dt_vigencia_fim']; 
        $this->DT_ATU[]             = $aResultado['dt_atu']; 
        $this->ID_USU_ATU[]         = $aResultado['id_usu_atu']; 
        $this->DT_CAD[]             = $aResultado['dt_cad']; 
        $this->ID_USU_CAD[]         = $aResultado['id_usu_cad']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_descontos(
                             NM_DESCONTO, 
                             DE_DESCONTO, 
                             TP_VALOR, 
                             TP_DESCONTO, 
                             CD_STATUS, 
                             CD_ABRANGENCIA, 
                             VL_MIN, 
                             VL_DESCONTO, 
                             DT_VIGENCIA_INICIO, 
                             DT_VIGENCIA_FIM, 
                             DT_ATU, 
                             ID_USU_ATU, 
                             DT_CAD, 
                             ID_USU_CAD 
)
      VALUES(
              '".$this->NM_DESCONTO[0]."', 
              '".$this->DE_DESCONTO[0]."', 
              '".$this->TP_VALOR[0]."', 
              '".$this->TP_DESCONTO[0]."', 
              '".$this->CD_STATUS[0]."', 
              '".$this->CD_ABRANGENCIA[0]."', 
              '".$this->VL_MIN[0]."', 
              '".$this->VL_DESCONTO[0]."', 
              ".$this->DT_VIGENCIA_INICIO[0].", 
              ".$this->DT_VIGENCIA_FIM[0].", 
              null, 
              null, 
              curdate(), 
              '".$this->ID_USU_CAD[0]."' 
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

    public function remover($sFiltro = '') {
      $sQuery = "DELETE FROM tc_descontos ".$sFiltro;
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
      $sQuery = "UPDATE tc_descontos
        SET
          nm_desconto        = '".$this->NM_DESCONTO[0]."', 
          de_desconto        = '".$this->DE_DESCONTO[0]."', 
          tp_valor           = '".$this->TP_VALOR[0]."', 
          tp_desconto        = '".$this->TP_DESCONTO[0]."', 
          cd_status          = '".$this->CD_STATUS[0]."', 
          cd_abrangencia     = '".$this->CD_ABRANGENCIA[0]."', 
          vl_min             = '".$this->VL_MIN[0]."', 
          vl_desconto        = '".$this->VL_DESCONTO[0]."', 
          dt_vigencia_inicio = ".$this->DT_VIGENCIA_INICIO[0].", 
          dt_vigencia_fim    = ".$this->DT_VIGENCIA_FIM[0].", 
          dt_atu             = curdate(),
          id_usu_atu         = '".$this->ID_USU_ATU[0]."'
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

      $this->ID[0]                 = (isset ($_POST['CMPdescontos-id'])              ? $_POST['CMPdescontos-id']              : '');
      $this->NM_DESCONTO[0]        = (isset ($_POST['CMPdescontos-nome'])            ? $_POST['CMPdescontos-nome']            : '');
      $this->DE_DESCONTO[0]        = (isset ($_POST['CMPdescontos-desc'])            ? $_POST['CMPdescontos-desc']            : '');
      $this->TP_VALOR[0]           = (isset ($_POST['CMPdescontos-tipo'])            ? $_POST['CMPdescontos-tipo']            : '');
      $this->TP_DESCONTO[0]        = (isset ($_POST['CMPdescontos-tipo-desc'])       ? $_POST['CMPdescontos-tipo-desc']       : '');
      $this->CD_STATUS[0]          = (isset ($_POST['CMPdescontos-status'])          ? $_POST['CMPdescontos-status']          : '');
      $this->CD_ABRANGENCIA[0]     = (isset ($_POST['CMPdescontos-abrangencia'])     ? $_POST['CMPdescontos-abrangencia']     : '');
      $this->VL_MIN[0]             = (isset ($_POST['CMPdescontos-min'])             ? $_POST['CMPdescontos-min']             : '');
      $this->VL_DESCONTO[0]        = (isset ($_POST['CMPdescontos-desconto'])        ? $_POST['CMPdescontos-desconto']        : '');
      $this->DT_VIGENCIA_INICIO[0] = (isset ($_POST['CMPdescontos-vigencia-inicio']) ? $_POST['CMPdescontos-vigencia-inicio'] : '');
      $this->DT_VIGENCIA_FIM[0]    = (isset ($_POST['CMPdescontos-vigencia-fim'])    ? $_POST['CMPdescontos-vigencia-fim']    : '');
      $this->DT_ATU[0]             = (isset ($_POST['CMPdescontos-atu'])             ? $_POST['CMPdescontos-atu']             : '');
      $this->ID_USU_ATU[0]         = (isset ($_POST['CMPdescontos-usu-atu'])         ? $_POST['CMPdescontos-usu-atu']         : '');
      $this->DT_CAD[0]             = (isset ($_POST['CMPdescontos-cad'])             ? $_POST['CMPdescontos-cad']             : '');
      $this->ID_USU_CAD[0]         = (isset ($_POST['CMPdescontos-usu-cad'])         ? $_POST['CMPdescontos-usu-cad']         : '');
      
    }
  }