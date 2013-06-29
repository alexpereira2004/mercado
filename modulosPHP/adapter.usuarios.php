<?php
  include_once 'class.wTools.php';
  include_once 'class.tc_usu_admin.php';
  include_once 'class.tr_usuarios_permissoes.php';
  include_once 'class.tr_usuarios_grupo.php';
  /**
   * Description of adapter
   *
   * @author Alex Lunardelli
   */
  class usuarios {
    private $sCdEmpresa;
    public static $oUtil;
    public static $CD_EMPRESA;


    public function __construct() {
      $this->oUtil       = new wTools();
      $this->oUsuario    = new tc_usu_admin();
      $this->oPermissoes = new tr_usuarios_permissoes();
      $this->oGrupos     = new tr_usuarios_grupo();
      $this->SCAPE       = $this->oUsuario->getCifra();
      $this->DB_LINK     = $this->oUsuario->getLink();
      
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


  /* usuarios::login
   *
   * Primeira ação de um usuário ao acessar o sistema através de um login e senha
   * @date 02/06/2012
   * @param
   * @return
   */
    public function login($sUsuario, $sSenha, $bCrip = true) {
      if($bCrip) {
        $sFiltro = "WHERE tx_email = '".$sUsuario."' AND  tx_senha = MD5('".$this->SCAPE.$sSenha."')";
      } else {
        $sFiltro = "WHERE tx_email = '".$sUsuario."' AND  tx_senha = ('".$sSenha."')";
      }
      $this->oUsuario->listar($sFiltro);

      if ($this->oUsuario->iLinhas == 1) {

        //Bloqueio para usuários inativos
        if($this->oUsuario->CD_STATUS[0] == 'I') {
          return false;
        }

        $this->dadosSessaoUsuario($this->oUsuario->ID[0]);

        $this->atualizarToken();
        return true;
      } else {
        return false;
      }
    }
  public function dadosSessaoUsuario($iIdUsu) {
    //$_SESSION['autorizacao'] = true;
    
    $_SESSION[$this->sCdEmpresa]['tmp_atv']     = time();
    $_SESSION[$this->sCdEmpresa]['token']       = base64_encode('usuario-'.$this->oUsuario->NM_USUARIO[0].'-login-em-'.time());
    $_SESSION[$this->sCdEmpresa]['id_usu']      = $iIdUsu;
    $_SESSION[$this->sCdEmpresa]['nm_usu']      = $this->oUsuario->NM_USUARIO[0];
    $_SESSION[$this->sCdEmpresa]['permissoes']  = $this->buscarPermissoesLogin($iIdUsu);

  }
  /* usuarios::validar
   *
   * Usado à qualquer momento que seja necessário certificar que o usuário tem acesso
   * ao sistema
   * @date 02/06/2012
   * @param
   * @return
   */
    public function validar() {
      try {
        if (!isset ($_SESSION[$this->sCdEmpresa]['tmp_atv'])) {
          throw new Exception;
        }

        // Teste de inatividade de usuário
        $this->oUtil->buscarParametro('EXPIRAR_SESSAO');
        $iParamExpira = $this->oUtil->aParametros['EXPIRAR_SESSAO'][0] * 60;
        $iDifTime = time() - $_SESSION[$this->sCdEmpresa]['tmp_atv'];
        if ($iDifTime > $iParamExpira) {
          throw new Exception;
        }

        //Teste do token
        $sSQL = "SELECT tx_token FROM tc_usu_admin WHERE cd_status = 'A' AND id = ".$_SESSION[$this->sCdEmpresa]['id_usu'];
        $aRet = $this->oUtil->buscarInfoDB($sSQL);
        if ($aRet[0] != $_SESSION[$this->sCdEmpresa]['token']) {
          throw new Exception;
        }

        // Atualiza o tempo para que a sessao possa continuar ativa
        $_SESSION[$this->sCdEmpresa]['tmp_atv'] = time();

        // Só para debug RETIRAR DAQUI !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $_SESSION[$this->sCdEmpresa]['permissoes']  = $this->buscarPermissoesLogin($_SESSION[$this->sCdEmpresa]['id_usu']);
       
        return true;


      } catch (Exception $exc) {
        header("Location: login.php");
        exit;
      }

      return true;
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
                  WHERE id = ".$this->oUsuario->ID[0];
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      return true;
    }
    
    public function listarUsuariosGrupos($sFiltro = '') {
      $sQuery = 'SELECT -- tr_usuarios_grupo.id,
                        id_usuario, 
                        id_grupo,

                        tc_usu_admin.nm_usuario, 
                        tc_usu_admin.tx_email, 
                        tc_usu_admin.tx_senha, 
                        date_format(dt_cad, "%d/%m/%Y") AS dt_cad, 
                        tc_usu_admin.cd_status, 
                        tc_usu_admin.cd_nivel,

                        nm_grupo,
                        id_usu_lider,
                        id_usu_criador,
                        cd_sit

                   FROM tr_usuarios_grupo
             INNER JOIN tc_usu_admin      ON tc_usu_admin.id      = tr_usuarios_grupo.id_usuario
             INNER JOIN tc_grupo_usuarios ON tc_grupo_usuarios.id = tr_usuarios_grupo.id_grupo

                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      
      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTr_usuarios_grupo = mysql_num_rows($sResultado);
      $this->iLinhasUsuariosGrupos = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        // $this->ID[]         = $aResultado['id'];
        $this->ID_USUARIO[] = $aResultado['id_usuario']; 
        $this->ID_GRUPO[]   = $aResultado['id_grupo']; 
        
        $this->NM_USUARIO[] = $aResultado['nm_usuario'];
        $this->TX_EMAIL[]   = $aResultado['tx_email'];
        $this->TX_SENHA[]   = $aResultado['tx_senha'];
        $this->DT_CAD[]     = $aResultado['dt_cad'];
        $this->CD_STATUS[]  = $aResultado['cd_status'];
        $this->CD_NIVEL[]   = $aResultado['cd_nivel'];

        $this->NM_GRUPO[]       = $aResultado['nm_grupo'];
        $this->ID_USU_LIDER[]   = $aResultado['id_usu_lider'];
        $this->ID_USU_CRIADOR[] = $aResultado['id_usu_criador'];
        $this->CD_SIT[]         = $aResultado['cd_sit'];
      }      
    }

  /* usuarios::buscarPermissoesLogin
   *
   * Faz busca das permissões de acesso do usuário tanto por cadastros pessoais quanto
   * de grupo. Caso exista permissão gravada em ambas tabelas, da prioridade à configuração
   * pessoal.
   * @date 30/06/2012
   * @param
   * @return $aDados - Array - Permissões
   */
    private function buscarPermissoesLogin($iIdUsu) {
      $sSQL = "SELECT tc_usu_admin.id ,
                      tc_permissoes.cd_codigo,
                      'U' as cd_orig,
                      tr_usuarios_permissoes.id_permissao,
                      tr_usuarios_permissoes.cd_inserir,
                      tr_usuarios_permissoes.cd_remover,
                      tr_usuarios_permissoes.cd_editar,
                      tr_usuarios_permissoes.cd_acessar,
                      tr_usuarios_permissoes.cd_visualizar
                      FROM tc_usu_admin
           INNER JOIN tr_usuarios_permissoes ON tr_usuarios_permissoes.id_usuario = tc_usu_admin.id
           INNER JOIN tc_permissoes ON tc_permissoes.id = tr_usuarios_permissoes.id_permissao
                WHERE tc_usu_admin.id = ".$iIdUsu."

                    UNION ALL

             SELECT tr_usuarios_grupo.id_grupo ,
                    tc_permissoes.cd_codigo,
                    'G' as cd_orig,
                    tr_grupos_permissoes.id_permissao,
                    tr_grupos_permissoes.cd_inserir,
                    tr_grupos_permissoes.cd_remover,
                    tr_grupos_permissoes.cd_editar,
                    tr_grupos_permissoes.cd_acessar,
                    tr_grupos_permissoes.cd_visualizar
               FROM tc_usu_admin
         INNER JOIN tr_usuarios_grupo ON tr_usuarios_grupo.id_usuario = tc_usu_admin.id
         INNER JOIN tr_grupos_permissoes ON tr_grupos_permissoes.id_grupo = tr_usuarios_grupo.id_grupo
         INNER JOIN tc_permissoes ON tc_permissoes.id = tr_grupos_permissoes.id_permissao
              WHERE tc_usu_admin.id = ".$iIdUsu."
                    ORDER BY cd_orig";
    
      $sResultado = mysql_query($sSQL, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      $this->iLinhasPermissoes = mysql_num_rows($sResultado);

      $aDados = array();

      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]            = $aResultado['id'];
        $this->CD_CODIGO[]     = $aResultado['cd_codigo'];
        $this->CD_ORIG[]       = $aResultado['cd_orig'];
        $this->ID_PERMISSAO[]  = $aResultado['id_permissao'];
        $this->CD_INSERIR[]    = $aResultado['cd_inserir'];
        $this->CD_REMOVER[]    = $aResultado['cd_remover'];
        $this->CD_EDITAR[]     = $aResultado['cd_editar'];
        $this->CD_ACESSAR[]    = $aResultado['cd_acessar'];
        $this->CD_VISUALIZAR[] = $aResultado['cd_visualizar'];

        $aDados[$aResultado['cd_codigo']] = array ('I' => $aResultado['cd_inserir'],
                                                     'R' => $aResultado['cd_remover'],
                                                     'E' => $aResultado['cd_editar'],
                                                     'A' => $aResultado['cd_acessar'],
                                                     'V' => $aResultado['cd_visualizar'] );
      }

      return $aDados;

    }

  /* usuarios::verificarPermissaoAcesso
   *
   * Valida as permissões do usuário para ACESSOS
   * @date 30/06/2012
   * @param string $sCodigoLocal - Código que esta na tabela "tc_permissoes.cd_codigo"
   * @param string $sAcao        - ["I"],["R"],["E"],["A"],["V"]
   * @return $aDados - Array - Permissões
   */
    public function verificarPermissaoAcesso($sCodigoLocal, $sAcao) {
      
      if (!isset ($_SESSION[$this->sCdEmpresa]['permissoes'][$sCodigoLocal])) {
        return false;
      }
      if ($_SESSION[$this->sCdEmpresa]['permissoes'][$sCodigoLocal][$sAcao] == 'L') {
        return true;
      }
      if ($_SESSION[$this->sCdEmpresa]['permissoes'][$sCodigoLocal][$sAcao] == 'S') {
        return true;
      }

      return false;
    }
  /* usuarios::verificarPermissaoAcao
   *
   * Valida as permissões do usuário para AÇÔES
   * @date 30/06/2012
   * @param
   * @return $aDados - Array - Permissões
   */
    public function verificarPermissaoAcao() {


    }

  }
?>
