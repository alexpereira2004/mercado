
<?php
  /**
   * Descricao
   *
   * @package    Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       17-12-2012
   **/

  class tcseg_parametros {
  
    public    $id;
    public    $nm_parametro;
    public    $cd_parametro;
    public    $tx_explicativo;
    public    $cd_tipo_uso;
    public    $cd_tipo;
    public    $nu_limite_cadastro;
    public    $cd_ativo;
    public    $nu_ordem;
    public    $nu_importancia;
    public    $tx_mascara;
    public    $vl_padrao;
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
        $aValidar = array ( 1 => array('Parametro' , $_POST['CMPparametros-parametro'], 'varchar(55)', true),
                            2 => array('Parametro' , $_POST['CMPparametros-parametro'], 'varchar(20)', true),
                            3 => array('Explicativo' , $_POST['CMPparametros-explicativo'], 'varchar(255)', true),
                            4 => array('Tipo-uso' , $_POST['CMPparametros-tipo-uso'], 'varchar(2)', true),
                            5 => array('Tipo' , $_POST['CMPparametros-tipo'], 'varchar(2)', true),
                            6 => array('Limite-cadastro' , $_POST['CMPparametros-limite-cadastro'], 'int(2)', true),
                            7 => array('Ativo' , $_POST['CMPparametros-ativo'], 'char(2)', true),
                            8 => array('Ordem' , $_POST['CMPparametros-ordem'], 'int(2)', true),
                            9 => array('Importancia' , $_POST['CMPparametros-importancia'], 'int(2)', true),
                            10 => array('Mascara' , $_POST['CMPparametros-mascara'], 'text', true),
                            11 => array('Padrao' , $_POST['CMPparametros-padrao'], 'varchar(255)', true),
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
                        nm_parametro, 
                        cd_parametro, 
                        tx_explicativo, 
                        cd_tipo_uso, 
                        cd_tipo, 
                        nu_limite_cadastro, 
                        cd_ativo, 
                        nu_ordem, 
                        nu_importancia, 
                        tx_mascara, 
                        vl_padrao 
                   FROM tcseg_parametros
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTcseg_parametros = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]                 = $aResultado['id']; 
        $this->NM_PARAMETRO[]       = $aResultado['nm_parametro']; 
        $this->CD_PARAMETRO[]       = $aResultado['cd_parametro']; 
        $this->TX_EXPLICATIVO[]     = $aResultado['tx_explicativo']; 
        $this->CD_TIPO_USO[]        = $aResultado['cd_tipo_uso']; 
        $this->CD_TIPO[]            = $aResultado['cd_tipo']; 
        $this->NU_LIMITE_CADASTRO[] = $aResultado['nu_limite_cadastro']; 
        $this->CD_ATIVO[]           = $aResultado['cd_ativo']; 
        $this->NU_ORDEM[]           = $aResultado['nu_ordem']; 
        $this->NU_IMPORTANCIA[]     = $aResultado['nu_importancia']; 
        $this->TX_MASCARA[]         = $aResultado['tx_mascara']; 
        $this->VL_PADRAO[]          = $aResultado['vl_padrao']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tcseg_parametros(
                             NM_PARAMETRO, 
                             CD_PARAMETRO, 
                             TX_EXPLICATIVO, 
                             CD_TIPO_USO, 
                             CD_TIPO, 
                             NU_LIMITE_CADASTRO, 
                             CD_ATIVO, 
                             NU_ORDEM, 
                             NU_IMPORTANCIA, 
                             TX_MASCARA, 
                             VL_PADRAO 
)
      VALUES(
              '".$this->NM_PARAMETRO[0]."', 
              '".$this->CD_PARAMETRO[0]."', 
              '".$this->TX_EXPLICATIVO[0]."', 
              '".$this->CD_TIPO_USO[0]."', 
              '".$this->CD_TIPO[0]."', 
              '".$this->NU_LIMITE_CADASTRO[0]."', 
              '".$this->CD_ATIVO[0]."', 
              '".$this->NU_ORDEM[0]."', 
              '".$this->NU_IMPORTANCIA[0]."', 
              '".$this->TX_MASCARA[0]."', 
              '".$this->VL_PADRAO[0]."' 
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
      $sQuery = "DELETE FROM tcseg_parametros
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
      $sQuery = "UPDATE tcseg_parametros
        SET
          nm_parametro       = '".$this->NM_PARAMETRO[0]."', 
          cd_parametro       = '".$this->CD_PARAMETRO[0]."', 
          tx_explicativo     = '".$this->TX_EXPLICATIVO[0]."', 
          cd_tipo_uso        = '".$this->CD_TIPO_USO[0]."', 
          cd_tipo            = '".$this->CD_TIPO[0]."', 
          nu_limite_cadastro = '".$this->NU_LIMITE_CADASTRO[0]."', 
          cd_ativo           = '".$this->CD_ATIVO[0]."', 
          nu_ordem           = '".$this->NU_ORDEM[0]."', 
          nu_importancia     = '".$this->NU_IMPORTANCIA[0]."', 
          tx_mascara         = '".$this->TX_MASCARA[0]."', 
          vl_padrao          = '".$this->VL_PADRAO[0]."' 
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

      $this->ID[0]                 = (isset ($_POST['CMPparametros-id'])                 ? $_POST['CMPparametros-id']                 : '');
      $this->NM_PARAMETRO[0]       = (isset ($_POST['CMPparametros-parametro'])       ? $_POST['CMPparametros-parametro']       : '');
      $this->CD_PARAMETRO[0]       = (isset ($_POST['CMPparametros-parametro'])       ? $_POST['CMPparametros-parametro']       : '');
      $this->TX_EXPLICATIVO[0]     = (isset ($_POST['CMPparametros-explicativo'])     ? $_POST['CMPparametros-explicativo']     : '');
      $this->CD_TIPO_USO[0]        = (isset ($_POST['CMPparametros-tipo-uso'])        ? $_POST['CMPparametros-tipo-uso']        : '');
      $this->CD_TIPO[0]            = (isset ($_POST['CMPparametros-tipo'])            ? $_POST['CMPparametros-tipo']            : '');
      $this->NU_LIMITE_CADASTRO[0] = (isset ($_POST['CMPparametros-limite-cadastro']) ? $_POST['CMPparametros-limite-cadastro'] : '');
      $this->CD_ATIVO[0]           = (isset ($_POST['CMPparametros-ativo'])           ? $_POST['CMPparametros-ativo']           : '');
      $this->NU_ORDEM[0]           = (isset ($_POST['CMPparametros-ordem'])           ? $_POST['CMPparametros-ordem']           : '');
      $this->NU_IMPORTANCIA[0]     = (isset ($_POST['CMPparametros-importancia'])     ? $_POST['CMPparametros-importancia']     : '');
      $this->TX_MASCARA[0]         = (isset ($_POST['CMPparametros-mascara'])         ? $_POST['CMPparametros-mascara']         : '');
      $this->VL_PADRAO[0]          = (isset ($_POST['CMPparametros-padrao'])          ? $_POST['CMPparametros-padrao']          : '');
      
    }
  }