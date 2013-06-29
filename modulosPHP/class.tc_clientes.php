
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       17-01-2013
   **/

  class tc_clientes {
  
    public    $id;
    public    $nm_cliente;
    public    $nm_sobrenome;
    public    $nu_rg;
    public    $nu_cpf;
    public    $dt_nascimento;
    public    $tx_tel;
    public    $tx_cel;
    public    $cd_sexo;
    public    $tx_setor;
    public    $tx_cargo;
    public    $nu_cnpj;
    public    $nu_ie;
    public    $nm_razao_social;
    public    $nm_fantasia;
    public    $tx_segmento;
    public    $cd_recebe_news;
    public    $tx_email;
    public    $tx_senha;
    public    $dt_cad;
    public    $cd_status;
    public    $cd_nivel;
    public    $tx_token;
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
      $this->SCAPE = 'graforreia';
    }

    public function salvar() {

      try {
        $aValidar = array ( 1 => array('Nome'      , $_POST['CMPclientes-cliente'], 'varchar', true),
                            2 => array('Sobrenome' , $_POST['CMPclientes-sobrenome'], 'varchar', true),
                            3 => array('RG'        , $_POST['CMPclientes-rg'], 'int', true),
                            4 => array('Telefone'  , $_POST['CMPclientes-tel'], 'varchar', true),
                            5 => array('Nascimento', $_POST['CMPclientes-nascimento'], 'date', true),
                            6 => array('Sexo'      , $_POST['CMPclientes-sexo'], 'char', true),
                            7 => array('CPF'       , $_POST['CMPclientes-cpf'], 'int', true),
                            8 => array('Cel'       , $_POST['CMPclientes-cel'], 'varchar', false),
                            9 => array('Setor' , $_POST['CMPclientes-setor'], 'varchar', true),
                            10 => array('Cargo' , $_POST['CMPclientes-cargo'], 'varchar', true),
                            11 => array('Cnpj' , $_POST['CMPclientes-cnpj'], 'float', true),
                            12 => array('Ie' , $_POST['CMPclientes-ie'], 'varchar', true),
                            13 => array('Razao-social' , $_POST['CMPclientes-razao-social'], 'varchar', true),
                            14 => array('Fantasia' , $_POST['CMPclientes-fantasia'], 'varchar', true),
                            15 => array('Segmento' , $_POST['CMPclientes-segmento'], 'varchar', true),
                            16 => array('Recebe-news' , $_POST['CMPclientes-recebe-news'], 'char', true),
                            17 => array('Email' , $_POST['CMPclientes-email'], 'varchar', true),
                            18 => array('Senha' , $_POST['CMPclientes-senha'], 'varchar', true),
                            //19 => array('Cad' , $_POST['CMPclientes-cad'], 'date', true),
                            20 => array('Status' , $_POST['CMPclientes-status'], 'char', true),
                            21 => array('Nivel' , $_POST['CMPclientes-nivel'], 'int', true),
                            22 => array('Token' , $_POST['CMPclientes-token'], 'varchar', false),
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
                        nm_cliente, 
                        nm_sobrenome, 
                        sg_cliente,
                        nu_rg, 
                        nu_cpf, 
                        CONCAT(MID(tc_clientes.nu_cpf,1,3), ".", MID(tc_clientes.nu_cpf,4,3), ".", MID(tc_clientes.nu_cpf,7,3), "-", MID(tc_clientes.nu_cpf,10,2)) AS nu_cpf_formatado,
                        date_format(dt_nascimento, "%d/%m/%Y") AS dt_nascimento, 
                        tx_tel, 
                        tx_cel, 
                        cd_sexo, 
                        tx_setor, 
                        tx_cargo, 
                        nu_cnpj, 
                        nu_ie, 
                        nm_razao_social, 
                        nm_fantasia, 
                        tx_segmento, 
                        cd_recebe_news, 
                        tx_email, 
                        tx_senha, 
                        date_format(dt_cad, "%d/%m/%Y") AS dt_cad, 
                        cd_status, 
                        cd_nivel, 
                        tx_token 
                   FROM tc_clientes
                   '.$sFiltro;
    $sQuery = "SELECT * 
               FROM v_clientes ".
              $sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_clientes = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]              = $aResultado['id']; 
        $this->NM_CLIENTE[]      = $aResultado['nm_cliente']; 
        $this->NM_SOBRENOME[]    = $aResultado['nm_sobrenome'];
        $this->SG_CLIENTE[]      = $aResultado['sg_cliente'];
        $this->NU_RG[]           = $aResultado['nu_rg']; 
        $this->NU_CPF[]          = $aResultado['nu_cpf']; 
        $this->NU_CPF_FORMATADO[]= $aResultado['nu_cpf_formatado']; 
        $this->DT_NASCIMENTO[]   = $aResultado['dt_nascimento']; 
        $this->TX_TEL[]          = $aResultado['tx_tel']; 
        $this->TX_CEL[]          = $aResultado['tx_cel']; 
        $this->CD_SEXO[]         = $aResultado['cd_sexo']; 
        $this->TX_SETOR[]        = $aResultado['tx_setor']; 
        $this->TX_CARGO[]        = $aResultado['tx_cargo']; 
        $this->NU_CNPJ[]         = $aResultado['nu_cnpj']; 
        $this->NU_IE[]           = $aResultado['nu_ie']; 
        $this->NM_RAZAO_SOCIAL[] = $aResultado['nm_razao_social']; 
        $this->NM_FANTASIA[]     = $aResultado['nm_fantasia']; 
        $this->TX_SEGMENTO[]     = $aResultado['tx_segmento']; 
        $this->CD_RECEBE_NEWS[]  = $aResultado['cd_recebe_news']; 
        $this->TX_EMAIL[]        = $aResultado['tx_email']; 
        $this->TX_SENHA[]        = $aResultado['tx_senha']; 
        $this->DT_CAD[]          = $aResultado['dt_cad']; 
        $this->CD_STATUS[]       = $aResultado['cd_status']; 
        $this->CD_NIVEL[]        = $aResultado['cd_nivel']; 
        $this->TX_TOKEN[]        = $aResultado['tx_token']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_clientes(
                             NM_CLIENTE, 
                             NM_SOBRENOME, 
                             SG_CLIENTE,
                             NU_RG, 
                             NU_CPF, 
                             DT_NASCIMENTO, 
                             TX_TEL, 
                             TX_CEL, 
                             CD_SEXO, 
                             TX_SETOR, 
                             TX_CARGO, 
                             NU_CNPJ, 
                             NU_IE, 
                             NM_RAZAO_SOCIAL, 
                             NM_FANTASIA, 
                             TX_SEGMENTO, 
                             CD_RECEBE_NEWS, 
                             TX_EMAIL, 
                             TX_SENHA, 
                             DT_CAD, 
                             CD_STATUS, 
                             CD_NIVEL, 
                             TX_TOKEN 
)
      VALUES(
              '".$this->NM_CLIENTE[0]."', 
              '".$this->NM_SOBRENOME[0]."', 
              '".$this->SG_CLIENTE[0]."', 
              '".$this->NU_RG[0]."', 
              '".$this->NU_CPF[0]."', 
              '".$this->DT_NASCIMENTO[0]."', 
              '".$this->TX_TEL[0]."', 
              '".$this->TX_CEL[0]."', 
              '".$this->CD_SEXO[0]."', 
              '".$this->TX_SETOR[0]."', 
              '".$this->TX_CARGO[0]."', 
              '".$this->NU_CNPJ[0]."', 
              '".$this->NU_IE[0]."', 
              '".$this->NM_RAZAO_SOCIAL[0]."', 
              '".$this->NM_FANTASIA[0]."', 
              '".$this->TX_SEGMENTO[0]."', 
              '".$this->CD_RECEBE_NEWS[0]."', 
              '".$this->TX_EMAIL[0]."', 
              md5('".$this->SCAPE.$this->TX_SENHA[0]."'), 
              curdate(),
              '".$this->CD_STATUS[0]."', 
              '".$this->CD_NIVEL[0]."', 
              '".$this->TX_TOKEN[0]."' 
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
      $sQuery = "DELETE FROM tc_clientes ".$sWhere;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        $this->iCdMsg = 1;
        $this->sMsg  = 'Ocorreu um erro ao remover o registro.';
        $this->sErro = mysql_error();
        $this->sResultado = 'er34253ro';
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

    public function editar($iId = '', $bAtualizarSenha = false) {
      $sSqlSenha = '';
      if ($bAtualizarSenha) {
        $sSqlSenha = "tx_senha = md5('".$this->SCAPE.$this->TX_SENHA[0]."'),"."\n";
      }
      
      $sQuery = "UPDATE tc_clientes
        SET
          nm_cliente      = '".$this->NM_CLIENTE[0]."', 
          nm_sobrenome    = '".$this->NM_SOBRENOME[0]."', 
          nu_rg           = '".$this->NU_RG[0]."', 
          nu_cpf          = '".$this->NU_CPF[0]."', 
          dt_nascimento   = '".$this->DT_NASCIMENTO[0]."', 
          tx_tel          = '".$this->TX_TEL[0]."', 
          tx_cel          = '".$this->TX_CEL[0]."', 
          cd_sexo         = '".$this->CD_SEXO[0]."', 
          tx_setor        = '".$this->TX_SETOR[0]."', 
          tx_cargo        = '".$this->TX_CARGO[0]."', 
          nu_cnpj         = '".$this->NU_CNPJ[0]."', 
          nu_ie           = '".$this->NU_IE[0]."', 
          nm_razao_social = '".$this->NM_RAZAO_SOCIAL[0]."', 
          nm_fantasia     = '".$this->NM_FANTASIA[0]."', 
          tx_segmento     = '".$this->TX_SEGMENTO[0]."', 
          cd_recebe_news  = '".$this->CD_RECEBE_NEWS[0]."', 
          tx_email        = '".$this->TX_EMAIL[0]."', 
          cd_status       = '".$this->CD_STATUS[0]."', 
          ".$sSqlSenha."
          cd_nivel        = '".$this->CD_NIVEL[0]."' 
          
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

      $this->ID[0]              = (isset ($_POST['CMPclientes-id'])              ? $_POST['CMPclientes-id']              : '');
      $this->NM_CLIENTE[0]      = (isset ($_POST['CMPclientes-cliente'])      ? $_POST['CMPclientes-cliente']      : '');
      $this->NM_SOBRENOME[0]    = (isset ($_POST['CMPclientes-sobrenome'])    ? $_POST['CMPclientes-sobrenome']    : '');
      $this->NU_RG[0]           = (isset ($_POST['CMPclientes-rg'])           ? $_POST['CMPclientes-rg']           : '');
      $this->NU_CPF[0]          = (isset ($_POST['CMPclientes-cpf'])          ? $_POST['CMPclientes-cpf']          : '');
      $this->DT_NASCIMENTO[0]   = (isset ($_POST['CMPclientes-nascimento'])   ? $_POST['CMPclientes-nascimento']   : '');
      $this->TX_TEL[0]          = (isset ($_POST['CMPclientes-tel'])          ? $_POST['CMPclientes-tel']          : '');
      $this->TX_CEL[0]          = (isset ($_POST['CMPclientes-cel'])          ? $_POST['CMPclientes-cel']          : '');
      $this->CD_SEXO[0]         = (isset ($_POST['CMPclientes-sexo'])         ? $_POST['CMPclientes-sexo']         : '');
      $this->TX_SETOR[0]        = (isset ($_POST['CMPclientes-setor'])        ? $_POST['CMPclientes-setor']        : '');
      $this->TX_CARGO[0]        = (isset ($_POST['CMPclientes-cargo'])        ? $_POST['CMPclientes-cargo']        : '');
      $this->NU_CNPJ[0]         = (isset ($_POST['CMPclientes-cnpj'])         ? $_POST['CMPclientes-cnpj']         : '');
      $this->NU_IE[0]           = (isset ($_POST['CMPclientes-ie'])           ? $_POST['CMPclientes-ie']           : '');
      $this->NM_RAZAO_SOCIAL[0] = (isset ($_POST['CMPclientes-razao-social']) ? $_POST['CMPclientes-razao-social'] : '');
      $this->NM_FANTASIA[0]     = (isset ($_POST['CMPclientes-fantasia'])     ? $_POST['CMPclientes-fantasia']     : '');
      $this->TX_SEGMENTO[0]     = (isset ($_POST['CMPclientes-segmento'])     ? $_POST['CMPclientes-segmento']     : '');
      $this->CD_RECEBE_NEWS[0]  = (isset ($_POST['CMPclientes-recebe-news'])  ? $_POST['CMPclientes-recebe-news']  : '');
      $this->TX_EMAIL[0]        = (isset ($_POST['CMPclientes-email'])        ? $_POST['CMPclientes-email']        : '');
      $this->TX_SENHA[0]        = (isset ($_POST['CMPclientes-senha'])        ? $_POST['CMPclientes-senha']        : '');
      $this->DT_CAD[0]          = (isset ($_POST['CMPclientes-cad'])          ? $_POST['CMPclientes-cad']          : '');
      $this->CD_STATUS[0]       = (isset ($_POST['CMPclientes-status'])       ? $_POST['CMPclientes-status']       : '');
      $this->CD_NIVEL[0]        = (isset ($_POST['CMPclientes-nivel'])        ? $_POST['CMPclientes-nivel']        : '');
      $this->TX_TOKEN[0]        = (isset ($_POST['CMPclientes-token'])        ? $_POST['CMPclientes-token']        : '');
      
    }
  }