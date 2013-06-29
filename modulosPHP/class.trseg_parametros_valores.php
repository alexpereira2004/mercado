
<?php
  /**
   * Descricao
   *
   * @package    Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       17-12-2012
   **/

  class trseg_parametros_valores {
  
    public    $id;
    public    $id_parametro;
    public    $tx_valor;
    public    $tx_func;
    public    $dt_log;
    public    $hr_log;
    public    $id_usu;
    public    $cd_ativo;
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
        $aValidar = array ( 1 => array('Parametro' , $_POST['CMPparametros-valores-parametro'], 'int(8)', true),
                            2 => array('Valor' , $_POST['CMPparametros-valores-valor'], 'varchar(255)', true),
                            3 => array('Func' , $_POST['CMPparametros-valores-func'], 'text', true),
                            4 => array('Log' , $_POST['CMPparametros-valores-log'], 'date', true),
                            5 => array('Log' , $_POST['CMPparametros-valores-log'], 'time', true),
                            6 => array('Usu' , $_POST['CMPparametros-valores-usu'], 'int(8)', true),
                            7 => array('Ativo' , $_POST['CMPparametros-valores-ativo'], 'varchar(2)', true),
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
                        id_parametro, 
                        tx_valor, 
                        tx_func, 
                        date_format(dt_log, "%d/%m/%Y") AS dt_log, 
                        date_format(hr_log, "%H:%i") AS hr_log, 
                        id_usu, 
                        cd_ativo 
                   FROM trseg_parametros_valores
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTrseg_parametros_valores = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]           = $aResultado['id']; 
        $this->ID_PARAMETRO[] = $aResultado['id_parametro']; 
        $this->TX_VALOR[]     = $aResultado['tx_valor']; 
        $this->TX_FUNC[]      = $aResultado['tx_func']; 
        $this->DT_LOG[]       = $aResultado['dt_log']; 
        $this->HR_LOG[]       = $aResultado['hr_log']; 
        $this->ID_USU[]       = $aResultado['id_usu']; 
        $this->CD_ATIVO[]     = $aResultado['cd_ativo']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO trseg_parametros_valores(
                             ID_PARAMETRO, 
                             TX_VALOR, 
                             TX_FUNC, 
                             DT_LOG, 
                             HR_LOG, 
                             ID_USU, 
                             CD_ATIVO 
)
      VALUES(
              '".$this->ID_PARAMETRO[0]."', 
              '".$this->TX_VALOR[0]."', 
              '".$this->TX_FUNC[0]."', 
              curdate(),
              curtime(),
              '".$this->ID_USU[0]."', 
              '".$this->CD_ATIVO[0]."' 
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
      $sQuery = "DELETE FROM trseg_parametros_valores
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
      $sQuery = "UPDATE trseg_parametros_valores
        SET
          id_parametro = '".$this->ID_PARAMETRO[0]."', 
          tx_valor     = '".$this->TX_VALOR[0]."', 
          tx_func      = '".$this->TX_FUNC[0]."', 
          dt_log       = curdate(),
          hr_log       = curtime(), 
          id_usu       = '".$this->ID_USU[0]."', 
          cd_ativo     = '".$this->CD_ATIVO[0]."' 
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

      $this->ID[0]           = (isset ($_POST['CMPparametros-valores-id'])           ? $_POST['CMPparametros-valores-id']           : '');
      $this->ID_PARAMETRO[0] = (isset ($_POST['CMPparametros-valores-parametro']) ? $_POST['CMPparametros-valores-parametro'] : '');
      $this->TX_VALOR[0]     = (isset ($_POST['CMPparametros-valores-valor'])     ? $_POST['CMPparametros-valores-valor']     : '');
      $this->TX_FUNC[0]      = (isset ($_POST['CMPparametros-valores-func'])      ? $_POST['CMPparametros-valores-func']      : '');
      $this->DT_LOG[0]       = (isset ($_POST['CMPparametros-valores-log'])       ? $_POST['CMPparametros-valores-log']       : '');
      $this->HR_LOG[0]       = (isset ($_POST['CMPparametros-valores-log'])       ? $_POST['CMPparametros-valores-log']       : '');
      $this->ID_USU[0]       = (isset ($_POST['CMPparametros-valores-usu'])       ? $_POST['CMPparametros-valores-usu']       : '');
      $this->CD_ATIVO[0]     = (isset ($_POST['CMPparametros-valores-ativo'])     ? $_POST['CMPparametros-valores-ativo']     : '');
      
    }
  }