
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       27-03-2013
   **/

  class tc_coletas {
  
    public    $id;
    public    $dt_coleta;
    public    $id_transportadora;
    public    $tx_obs;
    public    $cd_canhoto;
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
        $aValidar = array ( 1 => array('Coleta' , $_POST['CMPcoletas-coleta'], 'date', true),
                            2 => array('Transportadora' , $_POST['CMPcoletas-transportadora'], 'int(10)', true),
                            3 => array('Obs' , $_POST['CMPcoletas-obs'], 'text', true),
                            4 => array('Canhoto' , $_POST['CMPcoletas-canhoto'], 'varchar(20)', true),
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
                        date_format(dt_coleta, "%d/%m/%Y") AS dt_coleta, 
                        id_transportadora, 
                        tx_obs, 
                        cd_canhoto 
                   FROM tc_coletas
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_coletas = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]                = $aResultado['id']; 
        $this->DT_COLETA[]         = $aResultado['dt_coleta']; 
        $this->ID_TRANSPORTADORA[] = $aResultado['id_transportadora']; 
        $this->TX_OBS[]            = $aResultado['tx_obs']; 
        $this->CD_CANHOTO[]        = $aResultado['cd_canhoto']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_coletas(
                             DT_COLETA, 
                             ID_TRANSPORTADORA, 
                             TX_OBS, 
                             CD_CANHOTO 
)
      VALUES(
              '".$this->DT_COLETA[0]."', 
              '".$this->ID_TRANSPORTADORA[0]."', 
              '".$this->TX_OBS[0]."', 
              '".$this->CD_CANHOTO[0]."' 
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
      $sQuery = "DELETE FROM tc_coletas
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
      $sQuery = "UPDATE tc_coletas
        SET
          dt_coleta         = '".$this->DT_COLETA[0]."', 
          id_transportadora = '".$this->ID_TRANSPORTADORA[0]."', 
          tx_obs            = '".$this->TX_OBS[0]."', 
          cd_canhoto        = '".$this->CD_CANHOTO[0]."' 
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

      $this->ID[0]                = (isset ($_POST['CMPcoletas-id'])                ? $_POST['CMPcoletas-id']                : '');
      $this->DT_COLETA[0]         = (isset ($_POST['CMPcoletas-coleta'])         ? $_POST['CMPcoletas-coleta']         : '');
      $this->ID_TRANSPORTADORA[0] = (isset ($_POST['CMPcoletas-transportadora']) ? $_POST['CMPcoletas-transportadora'] : '');
      $this->TX_OBS[0]            = (isset ($_POST['CMPcoletas-obs'])            ? $_POST['CMPcoletas-obs']            : '');
      $this->CD_CANHOTO[0]        = (isset ($_POST['CMPcoletas-canhoto'])        ? $_POST['CMPcoletas-canhoto']        : '');
      
    }
  }