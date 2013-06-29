
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       11-05-2013
   **/

  class tc_taxas {
  
    public    $id;
    public    $nm_taxa;
    public    $tp_taxa;
    public    $cd_status;
    public    $cd_abrangencia;
    public    $vl_taxa;
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

      try {
        $aValidar = array ( 1 => array('Nome' , $_POST['CMPtaxas-nome'], 'texto', true),
                            2 => array('Tipo' , $_POST['CMPtaxas-tipo'], 'abrangencia', false, array('V','P')),
                            //3 => array('Status' , $_POST['CMPtaxas-status'], 'texto', true),
                            4 => array('Abrangência' , $_POST['CMPtaxas-abrangencia'], 'texto', false),
                            5 => array('Taxa' , $_POST['CMPtaxas-valor'], 'float', true),
                            6 => array('Início de Vigência' , $_POST['CMPtaxas-vigencia-inicio'], 'data', false),
                            7 => array('Final de Vigência' , $_POST['CMPtaxas-vigencia-fim'], 'data', false),
                            //8 => array('Atu' , $_POST['CMPtaxas-atu'], 'date', false),
                            //9 => array('Usu-atu' , $_POST['CMPtaxas-usu-atu'], 'int', false),
                            //10 => array('Cad' , $_POST['CMPtaxas-cad'], 'date', false),
                            //11 => array('Usu-cad' , $_POST['CMPtaxas-usu-cad'], 'int', false),
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
//          $this->oUtil->redirFRM($this->sBackpage, $this->aMsg);
//          header('location:'.$this->sBackpage);
//          exit;
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
                        nm_taxa, 
                        tp_taxa, 
                        cd_status, 
                        cd_abrangencia, 
                        vl_taxa, 
                        date_format(dt_vigencia_inicio, "%d/%m/%Y") AS dt_vigencia_inicio, 
                        date_format(dt_vigencia_fim, "%d/%m/%Y") AS dt_vigencia_fim, 
                        date_format(dt_atu, "%d/%m/%Y") AS dt_atu, 
                        id_usu_atu, 
                        date_format(dt_cad, "%d/%m/%Y") AS dt_cad, 
                        id_usu_cad 
                   FROM tc_taxas
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_taxas = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]                 = $aResultado['id']; 
        $this->NM_TAXA[]            = $aResultado['nm_taxa']; 
        $this->TP_TAXA[]            = $aResultado['tp_taxa']; 
        $this->CD_STATUS[]          = $aResultado['cd_status']; 
        $this->CD_ABRANGENCIA[]     = $aResultado['cd_abrangencia']; 
        $this->VL_TAXA[]            = $aResultado['vl_taxa']; 
        $this->DT_VIGENCIA_INICIO[] = $aResultado['dt_vigencia_inicio']; 
        $this->DT_VIGENCIA_FIM[]    = $aResultado['dt_vigencia_fim']; 
        $this->DT_ATU[]             = $aResultado['dt_atu']; 
        $this->ID_USU_ATU[]         = $aResultado['id_usu_atu']; 
        $this->DT_CAD[]             = $aResultado['dt_cad']; 
        $this->ID_USU_CAD[]         = $aResultado['id_usu_cad']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_taxas(
                             NM_TAXA, 
                             TP_TAXA, 
                             CD_STATUS, 
                             CD_ABRANGENCIA, 
                             VL_TAXA, 
                             DT_VIGENCIA_INICIO, 
                             DT_VIGENCIA_FIM, 
                             DT_ATU, 
                             ID_USU_ATU, 
                             DT_CAD, 
                             ID_USU_CAD 
)
      VALUES(
              '".$this->NM_TAXA[0]."', 
              '".$this->TP_TAXA[0]."', 
              '".$this->CD_STATUS[0]."', 
              '".$this->CD_ABRANGENCIA[0]."', 
              '".$this->VL_TAXA[0]."', 
              ".$this->DT_VIGENCIA_INICIO[0].", 
              ".$this->DT_VIGENCIA_FIM[0].", 
              null, 
              null, 
              curdate(), 
              '".$this->ID_USU_CAD[0]."' )";
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
      $sQuery = "DELETE FROM tc_taxas ".$sWhere;
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
      $sQuery = "UPDATE tc_taxas
        SET
          nm_taxa            = '".$this->NM_TAXA[0]."', 
          tp_taxa            = '".$this->TP_TAXA[0]."', 
          cd_status          = '".$this->CD_STATUS[0]."', 
          cd_abrangencia     = '".$this->CD_ABRANGENCIA[0]."', 
          vl_taxa            = '".$this->VL_TAXA[0]."', 
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
      $this->ID[0]                 = (isset ($_POST['CMPtaxas-id'])              ? $_POST['CMPtaxas-id']                 : '');
      $this->NM_TAXA[0]            = (isset ($_POST['CMPtaxas-nome'])            ? $_POST['CMPtaxas-nome']            : '');
      $this->TP_TAXA[0]            = (isset ($_POST['CMPtaxas-tipo'])            ? $_POST['CMPtaxas-tipo']            : '');
      $this->CD_STATUS[0]          = (isset ($_POST['CMPtaxas-status'])          ? $_POST['CMPtaxas-status']          : 'I');
      $this->CD_ABRANGENCIA[0]     = (isset ($_POST['CMPtaxas-abrangencia'])     ? $_POST['CMPtaxas-abrangencia']     : '');
      $this->VL_TAXA[0]            = (isset ($_POST['CMPtaxas-valor'])           ? $_POST['CMPtaxas-valor']            : '');
      $this->DT_VIGENCIA_INICIO[0] = (isset ($_POST['CMPtaxas-vigencia-inicio']) ? $_POST['CMPtaxas-vigencia-inicio'] : '');
      $this->DT_VIGENCIA_FIM[0]    = (isset ($_POST['CMPtaxas-vigencia-fim'])    ? $_POST['CMPtaxas-vigencia-fim']    : '');
      $this->DT_ATU[0]             = (isset ($_POST['CMPtaxas-atu'])             ? $_POST['CMPtaxas-atu']             : '');
      $this->ID_USU_ATU[0]         = (isset ($_POST['CMPtaxas-usu-atu'])         ? $_POST['CMPtaxas-usu-atu']         : '');
      $this->DT_CAD[0]             = (isset ($_POST['CMPtaxas-cad'])             ? $_POST['CMPtaxas-cad']             : '');
      $this->ID_USU_CAD[0]         = (isset ($_POST['CMPtaxas-usu-cad'])         ? $_POST['CMPtaxas-usu-cad']         : '');
    }
  }