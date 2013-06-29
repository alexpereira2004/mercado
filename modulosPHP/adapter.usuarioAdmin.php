<?php
  include_once 'class.tc_usu_admin.php';
  include_once 'class.tl_geral.php';
  include_once 'class.wTools.php';

  class usuario_admin extends tc_usu_admin{
    public $SCAPE = 'ariana_2012';
    public $sOrigem;
    private $sCdEmpresa;
    public static $oUtil;
    public static $CD_EMPRESA;

    public function  __construct() {
      parent::__construct();
      $this->oLog  = new tl_geral();
      $this->oUtil = new wTools();

      $this->oUtil->buscarParametro('NOME_EMPRESA');
      $this->sCdEmpresa = $this->oUtil->aParametros['NOME_EMPRESA'][0];
    }
    
   /* usuarios::getEmpresa
   *
   * Parâmetro cadastrado utilizado em sessões, permitindo um login para cada
   * projeto/empresa cadastrada
   * 
   * @date 28/12/2012
   * @param
   * @return
   */
    public static function getEmpresa() {
      self::$oUtil       = new wTools();
      self::$oUtil->buscarParametro('NOME_EMPRESA');
      self::$CD_EMPRESA = self::$oUtil->aParametros['NOME_EMPRESA'][0];
      return self::$CD_EMPRESA;
    }

    /*
     * Checa se usuário tem permissão de acesso para as páginas
     */
    public function validar() {
      try {
        if (!isset ($_SESSION[$this->sCdEmpresa]['tmp_atv'])) {
          $sTxLog = 'Tentativa de acesso ao sistema sem sessão';
          throw new Exception;
        }

        // Teste de inatividade de usuário
        $this->oUtil->buscarParametro('EXPIRAR_SESSAO');
        $iParamExpira = $this->oUtil->aParametros['EXPIRAR_SESSAO'][0] * 60;
        $iDifTime = time() - $_SESSION[$this->sCdEmpresa]['tmp_atv'];
        if ($iDifTime > $iParamExpira) {
          $sTxLog = 'Tempo da sessão do usuário expirou';
          throw new Exception;
        }
        //Teste do token
        $sSQL = "SELECT tx_token FROM tc_usu_admin WHERE cd_status = 'A' AND id = ".$_SESSION[$this->sCdEmpresa]['id_usu'];
        $aRet = $this->oUtil->buscarInfoDB($sSQL);


        if ($aRet[0] != $_SESSION[$this->sCdEmpresa]['token']) {
          $sTxLog = 'Token não foi atualizado';
          throw new Exception;
        }

        // Atualiza o tempo para que a sessao possa continuar ativa
        $_SESSION[$this->sCdEmpresa]['tmp_atv'] = time();

        
        //$_SESSION[$this->sCdEmpresa]['permissoes']  = $this->buscarPermissoesLogin($_SESSION[$this->sCdEmpresa]['id_usu']);

        return true;


      } catch (Exception $exc) {

        // Caso haja falha ao fazer login, registra na tabela de log
        $this->oLog->NM_LOG[0]   = 'Falha na validação do usuário';
        $this->oLog->TX_LOG[0]   = $sTxLog;
        $this->oLog->CD_LOG[0]   = 'ACESSO_INVALIDO';
        $this->oLog->CD_ACAO[0]  = 'I';
        $this->oLog->TX_IP[0]    = $_SERVER['REMOTE_ADDR'];
        $this->oLog->TX_TRACE[0] = $_SERVER['REQUEST_URI'];
        $this->oLog->ID_USU[0]   = 0;
        $this->oLog->inserir();

        header("Location: login.php");
        exit;
      }

      return true;
    }

    public function dadosSessaoUsuario($iIdUsu) {
      
      $_SESSION[$this->sCdEmpresa]['tmp_atv']     = time();
      $_SESSION[$this->sCdEmpresa]['token']       = base64_encode('usuario-'.$this->NM_USUARIO[0].'-login-em-'.time());
      $_SESSION[$this->sCdEmpresa]['id_usu']      = $iIdUsu;
      $_SESSION[$this->sCdEmpresa]['nm_usu']      = $this->NM_USUARIO[0];
    }
    
  /* usuarios::atualizarToken
   *
   * Atualiza o campo tx_token na tabela de usuários
   * @date 03/06/2012
   * @param
   * @return
   */
    private function atualizarToken() {

      $sQuery = "UPDATE tc_usu_admin
                    SET tx_token = '".$_SESSION[$this->sCdEmpresa]['token']."'
                  WHERE id = ".$this->ID[0];
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      return true;
    }

    public function registrarLogin() {
      $this->oLog->NM_LOG[0]   = 'Login Admin - '.$this->NM_USUARIO[0];
      $this->oLog->TX_LOG[0]   = '';
      $this->oLog->CD_LOG[0]   = 'REG_LOG_SYS';
      $this->oLog->CD_ACAO[0]  = 'I';
      $this->oLog->TX_IP[0]    = $_SERVER['REMOTE_ADDR'];
      $this->oLog->TX_TRACE[0] = $this->sOrigem;
      $this->oLog->ID_USU[0]   = $this->ID[0];
      $this->oLog->inserir();
      return true;
    }

    public function registrarTentativaDeLogin($sEmail, $sSenha) {
      $aDados = array('Tx_Login' => $sEmail,
                      'Tx_Senha' => md5($this->SCAPE.$sSenha) );
      $sTxLog = $this->oUtil->montarStringDados($aDados);

      $this->oLog->NM_LOG[0]   = 'Tentativa de Login no Painel de Administração';
      $this->oLog->TX_LOG[0]   = $sTxLog;
      $this->oLog->CD_LOG[0]   = 'REG_ERROR_LOG_SYS';
      $this->oLog->CD_ACAO[0]  = 'A';
      $this->oLog->TX_IP[0]    = $_SERVER['REMOTE_ADDR'];
      $this->oLog->TX_TRACE[0] = $this->sOrigem;
      $this->oLog->ID_USU[0]   = 0;

      $this->oLog->inserir();
      return true;
    }

    /*
     * Processo de Login
     * este metodo retorna os dados do usuario
     */
    public function validarLogin($sUsuario, $sSenha, $bCrip = true) {
      $sUsuario = $this->oUtil->anti_sql_injection($sUsuario);
      $sSenha   = $this->oUtil->anti_sql_injection($sSenha);

      if($bCrip) {
        $sFiltro = "WHERE tx_email = '".$sUsuario."' AND  tx_senha = MD5('".$this->SCAPE.$sSenha."')";
      } else {
        $sFiltro = "WHERE tx_email = '".$sUsuario."' AND  tx_senha = ('".$sSenha."')";
      }
      $sFiltro .= " AND cd_status = 'A'";

      $this->listar($sFiltro);
      
			if (!$this->iLinhas) {
        $this->sMsg  = 'Seu email ou senha está incorreto!';
        $this->sErro = mysql_error();
    		$this->sResultado = 'erro';
        return false;
    	}
      
      $this->sMsg  = 'Bem vindo '.$this->NM_USUARIO[0];

      $this->registrarLogin();
      $this->dadosSessaoUsuario($this->ID[0]);
      $this->atualizarToken();

      return true;
    }

    public function deslogar() {
      session_unset();
      header('location:login');
      die();
    }
  }
?>
