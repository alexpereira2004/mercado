<?php
/**
 * Manipulação de dados de clientes
 *
 * @author Alex Lunardelli
 */

include_once 'class.tc_clientes.php';
include_once 'class.tc_clientes_enderecos.php';
include_once 'adapter.enderecos_pf.php';
include_once 'adapter.enderecos_pj.php';
include_once 'class.wTools.php';
include_once 'class.tl_geral.php';
include_once 'class.carrinho.php';
class clientes {

  public $aMsg = array();
  private $sTpPessoa;
  private $aOpcoes;

  public function  __construct($sTpPessoa = '') {
    include 'conecta.php';
    $this->DB_LINK = $link;
    $this->oCli = new tc_clientes();
    $this->oUtil = new wTools();
    $this->oLog = new tl_geral();
    $this->sTpPessoa = $sTpPessoa;
    if ($sTpPessoa == 'PJ') {
      $this->oEnd = new enderecos_pj();
    } else {
      $this->oEnd = new enderecos_pf();
    }
    $this->sCdEmpresa = carrinho::getUsuarioSessao();
  }

  public function inicializaAtributos() {
    $this->oEnd->inicializaAtributos();

    if ($this->sTpPessoa == 'PJ') {
      $this->oCli->ID[0]              = (isset ($_POST['CMPclientes-id-PJ'])              ? $_POST['CMPclientes-id-PJ']              : '');
      $this->oCli->NM_CLIENTE[0]      = (isset ($_POST['CMPclientes-cliente-PJ'])      ? $_POST['CMPclientes-cliente-PJ']      : '');
      $this->oCli->NM_SOBRENOME[0]    = (isset ($_POST['CMPclientes-sobrenome-PJ'])    ? $_POST['CMPclientes-sobrenome-PJ']    : '');
      $this->oCli->NU_RG[0]           = (isset ($_POST['CMPclientes-rg-PJ'])           ? $_POST['CMPclientes-rg-PJ']           : '');
      $this->oCli->NU_CPF[0]          = (isset ($_POST['CMPclientes-cpf-PJ'])          ? $_POST['CMPclientes-cpf-PJ']          : '');
      $this->oCli->DT_NASCIMENTO[0]   = (isset ($_POST['CMPclientes-nascimento-PJ'])   ? $_POST['CMPclientes-nascimento-PJ']   : '');
      $this->oCli->TX_TEL[0]          = (isset ($_POST['CMPclientes-tel-PJ'])          ? $_POST['CMPclientes-tel-PJ']          : '');
      $this->oCli->TX_CEL[0]          = (isset ($_POST['CMPclientes-cel-PJ'])          ? $_POST['CMPclientes-cel-PJ']          : '');
      $this->oCli->CD_SEXO[0]         = (isset ($_POST['CMPclientes-sexo-PJ'])         ? $_POST['CMPclientes-sexo-PJ']         : '');
      $this->oCli->TX_SETOR[0]        = (isset ($_POST['CMPclientes-setor-PJ'])        ? $_POST['CMPclientes-setor-PJ']        : '');
      $this->oCli->TX_CARGO[0]        = (isset ($_POST['CMPclientes-cargo-PJ'])        ? $_POST['CMPclientes-cargo-PJ']        : '');
      $this->oCli->NU_CNPJ[0]         = (isset ($_POST['CMPclientes-cnpj-PJ'])         ? $_POST['CMPclientes-cnpj-PJ']         : '');
      $this->oCli->NU_IE[0]           = (isset ($_POST['CMPclientes-ie-PJ'])           ? $_POST['CMPclientes-ie-PJ']           : '');
      $this->oCli->NM_RAZAO_SOCIAL[0] = (isset ($_POST['CMPclientes-razao-social-PJ']) ? $_POST['CMPclientes-razao-social-PJ'] : '');
      $this->oCli->NM_FANTASIA[0]     = (isset ($_POST['CMPclientes-fantasia-PJ'])     ? $_POST['CMPclientes-fantasia-PJ']     : '');
      $this->oCli->TX_SEGMENTO[0]     = (isset ($_POST['CMPclientes-segmento-PJ'])     ? $_POST['CMPclientes-segmento-PJ']     : '');
      $this->oCli->CD_RECEBE_NEWS[0]  = (isset ($_POST['CMPclientes-recebe-news-PJ'])  ? $_POST['CMPclientes-recebe-news-PJ']  : '');
      $this->oCli->TX_EMAIL[0]        = (isset ($_POST['CMPclientes-email-PJ'])        ? $_POST['CMPclientes-email-PJ']        : '');
      $this->oCli->TX_SENHA[0]        = (isset ($_POST['CMPclientes-senha-PJ'])        ? $_POST['CMPclientes-senha-PJ']        : '');
      $this->oCli->DT_CAD[0]          = (isset ($_POST['CMPclientes-cad-PJ'])          ? $_POST['CMPclientes-cad-PJ']          : '');
      $this->oCli->CD_STATUS[0]       = (isset ($_POST['CMPclientes-status-PJ'])       ? $_POST['CMPclientes-status-PJ']       : '');
      $this->oCli->CD_NIVEL[0]        = (isset ($_POST['CMPclientes-nivel-PJ'])        ? $_POST['CMPclientes-nivel-PJ']        : '');
      $this->oCli->TX_TOKEN[0]        = (isset ($_POST['CMPclientes-token-PJ'])        ? $_POST['CMPclientes-token-PJ']        : '');
      
    } else {
      $this->oCli->ID[0]              = (isset ($_POST['CMPclientes-id-PF'])              ? $_POST['CMPclientes-id-PF']              : '');
      $this->oCli->NM_CLIENTE[0]      = (isset ($_POST['CMPclientes-cliente-PF'])      ? $_POST['CMPclientes-cliente-PF']      : '');
      $this->oCli->NM_SOBRENOME[0]    = (isset ($_POST['CMPclientes-sobrenome-PF'])    ? $_POST['CMPclientes-sobrenome-PF']    : '');
      $this->oCli->NU_RG[0]           = (isset ($_POST['CMPclientes-rg-PF'])           ? $_POST['CMPclientes-rg-PF']           : '');
      $this->oCli->NU_CPF[0]          = (isset ($_POST['CMPclientes-cpf-PF'])          ? $_POST['CMPclientes-cpf-PF']          : '');
      $this->oCli->DT_NASCIMENTO[0]   = (isset ($_POST['CMPclientes-nascimento-PF'])   ? $_POST['CMPclientes-nascimento-PF']   : '');
      $this->oCli->TX_TEL[0]          = (isset ($_POST['CMPclientes-tel-PF'])          ? $_POST['CMPclientes-tel-PF']          : '');
      $this->oCli->TX_CEL[0]          = (isset ($_POST['CMPclientes-cel-PF'])          ? $_POST['CMPclientes-cel-PF']          : '');
      $this->oCli->CD_SEXO[0]         = (isset ($_POST['CMPclientes-sexo-PF'])         ? $_POST['CMPclientes-sexo-PF']         : '');
      $this->oCli->TX_SETOR[0]        = (isset ($_POST['CMPclientes-setor-PF'])        ? $_POST['CMPclientes-setor-PF']        : '');
      $this->oCli->TX_CARGO[0]        = (isset ($_POST['CMPclientes-cargo-PF'])        ? $_POST['CMPclientes-cargo-PF']        : '');
      $this->oCli->NU_CNPJ[0]         = (isset ($_POST['CMPclientes-cnpj-PF'])         ? $_POST['CMPclientes-cnpj-PF']         : 0);
      $this->oCli->NU_IE[0]           = (isset ($_POST['CMPclientes-ie-PF'])           ? $_POST['CMPclientes-ie-PF']           : 0);
      $this->oCli->NM_RAZAO_SOCIAL[0] = (isset ($_POST['CMPclientes-razao-social-PF']) ? $_POST['CMPclientes-razao-social-PF'] : '');
      $this->oCli->NM_FANTASIA[0]     = (isset ($_POST['CMPclientes-fantasia-PF'])     ? $_POST['CMPclientes-fantasia-PF']     : '');
      $this->oCli->TX_SEGMENTO[0]     = (isset ($_POST['CMPclientes-segmento-PF'])     ? $_POST['CMPclientes-segmento-PF']     : '');
      $this->oCli->CD_RECEBE_NEWS[0]  = (isset ($_POST['CMPclientes-recebe-news-PF'])  ? $_POST['CMPclientes-recebe-news-PF']  : '');
      $this->oCli->TX_EMAIL[0]        = (isset ($_POST['CMPclientes-email-PF'])        ? $_POST['CMPclientes-email-PF']        : '');
      $this->oCli->TX_SENHA[0]        = (isset ($_POST['CMPclientes-senha-PF'])        ? $_POST['CMPclientes-senha-PF']        : '');
      $this->oCli->DT_CAD[0]          = (isset ($_POST['CMPclientes-cad-PF'])          ? $_POST['CMPclientes-cad-PF']          : '');
      $this->oCli->CD_STATUS[0]       = (isset ($_POST['CMPclientes-status-PF'])       ? $_POST['CMPclientes-status-PF']       : '');
      $this->oCli->CD_NIVEL[0]        = (isset ($_POST['CMPclientes-nivel-PF'])        ? $_POST['CMPclientes-nivel-PF']        : 0);
      $this->oCli->TX_TOKEN[0]        = (isset ($_POST['CMPclientes-token-PF'])        ? $_POST['CMPclientes-token-PF']        : '');
    }
  }
  
  public function salvar() {
    

    try {

      mysql_query("START TRANSACTION", $this->DB_LINK);


      $_POST['CMPclientes-recebe-news'] = 'S';
      $_POST['CMPclientes-status'] = 'A';
      $_POST['CMPclientes-nivel']  = '0';
      $this->oCli->CD_NIVEL[0] = 0;
      $_POST['CMPclientes-token']  = '';


      $aValidar = $this->buscarDadosValidacao($_POST['CMPtpCadastro']);

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
        if ($this->aMsg['iCdMsg'] != 0) {
          throw new excecoes(25, 'Cadastro de clientes');
        }
        //$this->oUtil->redirFRM($this->sBackpage, $this->aMsg);
        //header('location:'.$this->sBackpage);
        //exit;
      } else {

        // Tipo de ação não é válido
        throw new excecoes(20, $this->oUtil->anti_sql_injection($_POST['CMPpgAtual']));
      }
      mysql_query('COMMIT', $this->DB_LINK);

    } catch (excecoes $e) {

      mysql_query('ROLLBACK', $this->DB_LINK);
      $e->bReturnMsg = false;
      $e->getErrorByCode();
      if (is_array($e->aMsg)) {
        $this->aMsg = $e->aMsg;
      }
      return false;
    }
  }

  public function buscarDadosValidacao($sTpPessoa) {
    if ($sTpPessoa == 'PF') {
      
      
      $this->inicializaAtributos();

      $this->oEnd->NU_CEP[0]         = str_replace('-', '', $_POST['CMPclientes-enderecos-cep-PF']);
      $this->oCli->NU_CPF[0]         = (int) str_replace( array('.',',','-'), '', $_POST['CMPclientes-cpf-PF']);
      $this->oCli->DT_NASCIMENTO[0]  = $this->oUtil->parseValue($_POST['CMPclientes-nascimento-PF'], 'dt-bd');
      $this->oCli->CD_RECEBE_NEWS[0] = isset($_POST['CMPclientes-recebe-news-PF']) ? 'S' : 'N';
      $this->oCli->CD_STATUS[0]      = 'A';
      $this->oCli->CD_NIVEL[0]       = '0';

      $aValidar['PF'] = array ( 1 => array('Nome'          , $_POST['CMPclientes-cliente-PF'], 'varchar', true),
                                2 => array('Sobrenome'     , $_POST['CMPclientes-sobrenome-PF'], 'varchar', true),
                                3 => array('RG'            , $_POST['CMPclientes-rg-PF'], 'int', true),
                                4 => array('Telefone'      , $_POST['CMPclientes-tel-PF'], 'varchar', true),
                                5 => array('Nascimento'    , $_POST['CMPclientes-nascimento-PF'], 'data', true),
                                6 => array('Sexo'          , $_POST['CMPclientes-sexo-PF'], 'char', true),
                                7 => array('CPF'           , $_POST['CMPclientes-cpf-PF'], 'varchar', true),
                                8 => array('Cel'           , $_POST['CMPclientes-cel-PF'], 'varchar', false),

                                20 => array('CEP'                  , $_POST['CMPclientes-enderecos-cep-PF'], 'int', true),
                                21 => array('Endereço Residencial' , $_POST['CMPclientes-enderecos-logradouro-PF'], 'varchar', true),
                                22 => array('Logradouro'           , $_POST['CMPclientes-enderecos-complemento-PF'], 'varchar', false),
                                23 => array('Número'               , $_POST['CMPclientes-enderecos-numero-PF'], 'varchar', true),
                                24 => array('Complemento'          , $_POST['CMPclientes-enderecos-complemento-PF'], 'varchar', false),
                                26 => array('Bairro'               , $_POST['CMPclientes-enderecos-bairro-PF'], 'varchar', true),
                                27 => array('Cidade'               , $_POST['CMPclientes-enderecos-cid-PF'], 'int', true),
                                28 => array('Estado'               , $_POST['CMPclientes-enderecos-uf-PF'], 'int', true),

                                30 => array('Email'        , $_POST['CMPclientes-email-PF'], 'email', true),

                                31 => array('Senha'        , $_POST['CMPclientes-senha-PF'], 'senha-correta', true, $_POST['CMPclientes-senha2-PF']),
                                32 => array('Senha'        , $_POST['CMPclientes-senha-PF'], 'varchar', true),
                                34 => array('Confirmar Senha' , $_POST['CMPclientes-senha2-PF'], 'varchar', true),
                                //19 => array('Cad' , $_POST['CMPclientes-cad'], 'date', true),
                                //50 => array('Status'       , $_POST['CMPclientes-status-PF'], 'char', false),
                                //51 => array('Nivel'        , $_POST['CMPclientes-nivel-PF'], 'int', false),
                                //52 => array('Token'        , $_POST['CMPclientes-token-PF'], 'varchar', false),
                          );
    } elseif ($sTpPessoa == 'PJ') {
      
      $_POST['CMPclientes-cnpj-PJ'] = str_replace(array('.','/','-'), '', $_POST['CMPclientes-cnpj-PJ']);
      
      $this->inicializaAtributos();
      
      $this->oCli->DT_NASCIMENTO[0] = '0000-00-00';
      $this->oCli->NU_CNPJ[0]  = (int) $this->oCli->NU_CNPJ[0];
      $this->oCli->NU_RG[0]    = '0';
      $this->oCli->NU_CPF[0]   = '0';
      $this->oCli->CD_SEXO[0]  = ' ';
      $this->oCli->CD_NIVEL[0] = '0';
      $this->oCli->CD_RECEBE_NEWS[0] = isset($_POST['CMPclientes-recebe-news-PJ']) ? 'S' : 'N';
      $this->oCli->CD_STATUS[0] = 'A';
      //$this->oCli->TX_TOKEN[0] = '';
      $this->oEnd->NU_CEP[0]   = (int)  str_replace('-', '', $_POST['CMPclientes-enderecos-cep-PJ']);      
      

      $aValidar['PJ'] = array ( 1 => array('Nome comprador' , $_POST['CMPclientes-cliente-PJ'], 'varchar', true),
                                2 => array('Sobrenome'      , $_POST['CMPclientes-sobrenome-PJ'], 'varchar', true),
                                9 => array('Setor'          , $_POST['CMPclientes-setor-PJ'], 'varchar', true),
                                10 => array('Cargo'         , $_POST['CMPclientes-cargo-PJ'], 'varchar', true),

                                11 => array('CNPJ'               , $_POST['CMPclientes-cnpj-PJ'], 'float', true),
                                13 => array('Razao Social'       , $_POST['CMPclientes-razao-social-PJ'], 'varchar', true),

                                14 => array('Nome Fantasia'     , $_POST['CMPclientes-fantasia-PJ'], 'varchar', true),
                                15 => array('Ramo/Atividade'    , $_POST['CMPclientes-segmento-PJ'], 'varchar', true),
                                4 => array('Telefone 1'         , $_POST['CMPclientes-tel-PJ'], 'varchar', true),
                                8 => array('Telefone 2'         , $_POST['CMPclientes-cel-PJ'], 'varchar', false),
                                12 => array('Inscrição Estadual' , $_POST['CMPclientes-ie-PJ'], 'varchar', false),
          
                                //16 => array('Recebe-news'  , $_POST['CMPclientes-recebe-news-PJ'], 'char', false),

                                20 => array('CEP'                  , $_POST['CMPclientes-enderecos-cep-PJ'], 'int', true),
                                21 => array('Endereço Residencial' , $_POST['CMPclientes-enderecos-logradouro-PJ'], 'varchar', true),
                                22 => array('Logradouro'           , $_POST['CMPclientes-enderecos-complemento-PJ'], 'varchar', false),
                                23 => array('Número'               , $_POST['CMPclientes-enderecos-numero-PJ'], 'varchar', true),
                                24 => array('Complemento'          , $_POST['CMPclientes-enderecos-complemento-PJ'], 'varchar', false),
                                26 => array('Bairro'               , $_POST['CMPclientes-enderecos-bairro-PJ'], 'varchar', true),
                                27 => array('Cidade'               , $_POST['CMPclientes-enderecos-cid-PJ'], 'int', true),
                                28 => array('Estado'               , $_POST['CMPclientes-enderecos-uf-PJ'], 'int', true),
                                //29 => array('Cliente'              , $_POST['CMPclientes-enderecos-cliente'], 'int', true),

                                30 => array('Email'        , $_POST['CMPclientes-email-PJ'], 'email', true),

                                31 => array('Senha'        , $_POST['CMPclientes-senha-PJ'], 'senha-correta', true, $_POST['CMPclientes-senha2-PJ']),
                                32 => array('Senha'        , $_POST['CMPclientes-senha-PJ'], 'varchar', true),
                                34 => array('Confirmar Senha' , $_POST['CMPclientes-senha2-PJ'], 'varchar', true),
                                //19 => array('Cad' , $_POST['CMPclientes-cad'], 'date', true),
                                //50 => array('Status'       , $_POST['CMPclientes-status-PJ'], 'char', false),
                                //51 => array('Nivel'        , $_POST['CMPclientes-nivel-PJ'], 'int', false),
                                //52 => array('Token'        , $_POST['CMPclientes-token-PJ'], 'varchar', false),
                          );
    }
    return $aValidar[$sTpPessoa];
  }

  public function inserir() {

    try {
      
      $this->oCli->SG_CLIENTE[0] = $this->oUtil->criarSigla($this->oCli->NM_CLIENTE[0].' '.$this->oCli->NM_SOBRENOME[0], 'tc_clientes', 'sg_cliente');
      $this->oCli->inserir();
      if($this->oCli->aMsg['iCdMsg'] != 0) {
        $this->aMsg = $this->oCli->aMsg;
        throw new Exception;
      }
      $aRet = $this->oUtil->pegaInfoDB('tc_clientes', 'max(id)');
      $this->oEnd->ID_CLIENTE[0] = $aRet[0];
      $this->oEnd->inserir();
      
      if ($this->oEnd->aMsg['iCdMsg'] != 0) {
        $this->aMsg = $this->oEnd->aMsg;
        throw new Exception;
      }
      // Monta array com mensagem de retorno
      $this->aMsg = array('iCdMsg' => 0,
                            'sMsg' => 'Cadastro realizado com sucesso, seja bem vindo!',
                      'sResultado' => 'sucesso' );
      return true;
    } catch (Exception $oEx) {
      return false;
    }
    
  }

  public function listar($sWhere = '') {
    $sSQL = "SELECT * 
               FROM v_clientes ".
              $sWhere;

    $sResultado = mysql_query($sSQL, $this->DB_LINK);

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
      $this->oCli->NU_RG[]           = $aResultado['nu_rg']; 
      $this->oCli->NU_CPF[]          = $aResultado['nu_cpf']; 
      $this->oCli->DT_NASCIMENTO[]   = $aResultado['dt_nascimento']; 
      $this->oCli->TX_TEL[]          = $aResultado['tx_tel']; 
      $this->oCli->TX_CEL[]          = $aResultado['tx_cel']; 
      $this->oCli->CD_SEXO[]         = $aResultado['cd_sexo']; 
      $this->oCli->TX_SETOR[]        = $aResultado['tx_setor']; 
      $this->oCli->TX_CARGO[]        = $aResultado['tx_cargo']; 
      $this->oCli->NU_CNPJ[]         = $aResultado['nu_cnpj']; 
      $this->oCli->NU_IE[]           = $aResultado['nu_ie']; 
      $this->oCli->NM_RAZAO_SOCIAL[] = $aResultado['nm_razao_social']; 
      $this->oCli->NM_FANTASIA[]     = $aResultado['nm_fantasia']; 
      $this->oCli->TX_SEGMENTO[]     = $aResultado['tx_segmento']; 
      $this->oCli->CD_RECEBE_NEWS[]  = $aResultado['cd_recebe_news']; 
      $this->oCli->TX_EMAIL[]        = $aResultado['tx_email']; 
      $this->oCli->TX_SENHA[]        = $aResultado['tx_senha']; 
      $this->oCli->DT_CAD[]          = $aResultado['dt_cad']; 
      $this->oCli->CD_STATUS[]       = $aResultado['cd_status']; 
      $this->oCli->CD_NIVEL[]        = $aResultado['cd_nivel']; 
      $this->oCli->TX_TOKEN[]        = $aResultado['tx_token']; 

      $this->oEnd->NM_LOGRADOURO[]  = $aResultado['nm_logradouro']; 
      $this->oEnd->TP_LOGRADOURO[]  = $aResultado['tp_logradouro']; 
      $this->oEnd->TX_NUMERO[]      = $aResultado['tx_numero']; 
      $this->oEnd->TX_COMPLEMENTO[] = $aResultado['tx_complemento']; 
      $this->oEnd->NU_CEP[]         = $aResultado['nu_cep']; 
      $this->oEnd->TX_BAIRRO[]      = $aResultado['tx_bairro']; 
      $this->oEnd->NM_UF[]          = $aResultado['nm_uf']; 
      $this->oEnd->NM_CID[]         = $aResultado['nm_cid']; 


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
  
  public function remover($aId) {

    try {
      mysql_query("START TRANSACTION", $this->DB_LINK);

      $sId = implode(',', $aId);


      if (!$this->oCli->remover('WHERE id IN ('.$sId.')')) {
        $this->aMsg = $this->oCli->aMsg;
        $this->aMsg['sErro'] = $this->oCli->sErro;
        $this->aMsg['sMsg'] = 'Erro ao tentar excluir cliente.';
        throw new Exception;          
      }

      if (!$this->oEnd->remover('WHERE id_cliente IN ('.$sId.')')) {
        $this->aMsg = $this->oEnd->aMsg;
        $this->aMsg['sErro'] = $this->oEnd->sErro;
        $this->aMsg['sMsg'] = 'Erro ao tentar excluir dados referentes aos endereços do cliente.';
        throw new Exception;
      }

      $this->aMsg = $this->oCli->aMsg;
      mysql_query('COMMIT', $this->DB_LINK);

      //mysql_query('ROLLBACK', $this->DB_LINK);
    } catch (Exception $exc) {
      
      mysql_query('ROLLBACK', $this->DB_LINK);

      $sTxLog = 'Ids de clientes a serem removidos: '.$sId."\n".$this->oUtil->anti_sql_injection($this->aMsg['sErro']);
      $this->oLog->NM_LOG[0]   = 'Falha ao tentar excluir cliente';
      $this->oLog->TX_LOG[0]   = $sTxLog;
      $this->oLog->CD_LOG[0]   = 'ERRO_EXC_CLIENTE';
      $this->oLog->CD_ACAO[0]  = 'R';
      $this->oLog->TX_IP[0]    = $_SERVER['REMOTE_ADDR'];
      $this->oLog->TX_TRACE[0] = $_SERVER['REQUEST_URI'];
      $this->oLog->ID_USU[0]   = $_SESSION[usuario_admin::getEmpresa()]['id_usu'];
      $this->oLog->inserir();
    }
  }
  
  
  /* clientes::validar
  *
  * Checa se usuário tem permissão de acesso para as páginas
  * 
  * @date 14/02/2013
  * @param  $bBloquearAcesso - TRUE para lugares onde o usuário deve ser redirecionado
  *                            em caso não estar logado. 
  *                            FALSE para lugares onde mesmo deslogado ele terá acesso
  * @param  $sPaginaRet      - Endereço que o usuário deve ser redirecionado caso não esteja 
  *                            logado
  * @return
  */  
  public function validar($bBloquearAcesso = false, $sPaginaRet = null) {
    try {
      if (!isset ($_SESSION[$this->sCdEmpresa]['login']['tmp_atv'])) {
        $sTxLog = 'Tentativa de acesso ao sistema sem sessão';
        throw new Exception;
      }
      
      if (!isset($_SESSION[$this->sCdEmpresa]['login']['id_usu'])) {
        throw new Exception;
      }

      // Teste de inatividade de usuário
      $this->oUtil->buscarParametro('CLI_EXPIRAR_SESSAO');
      $iParamExpira = $this->oUtil->aParametros['CLI_EXPIRAR_SESSAO'][0] * 60;
      $iDifTime = time() - $_SESSION[$this->sCdEmpresa]['login']['tmp_atv'];
      if ($iDifTime > $iParamExpira) {
        $sTxLog = 'Tempo da sessão do usuário expirou';
        throw new Exception;
      }
      
      
      //Teste do token
      $sSQL = "SELECT tx_token FROM tc_clientes WHERE cd_status = 'A' AND id = ".$_SESSION[$this->sCdEmpresa]['login']['id_usu'];

      $aRet = $this->oUtil->buscarInfoDB($sSQL);
      if ($aRet[0] != $_SESSION[$this->sCdEmpresa]['login']['token']) {
        $sTxLog = 'Token não foi atualizado';
        throw new Exception;
      }

      // Atualiza o tempo para que a sessao possa continuar ativa
      $_SESSION[$this->sCdEmpresa]['tmp_atv'] = time();


      //$_SESSION[$this->sCdEmpresa]['permissoes']  = $this->buscarPermissoesLogin($_SESSION[$this->sCdEmpresa]['id_usu']);

      return true;


    } catch (Exception $exc) {

      unset($_SESSION[$this->sCdEmpresa]['login']);
      // Caso haja falha ao fazer login, registra na tabela de log
      // Este metodo pode ser chamado em lugares onde não é obrigatorio estar logado
      // considerando isto, não deve gravar erro no log
//      $this->oLog->NM_LOG[0]   = 'Falha na validação do usuário';
//      $this->oLog->TX_LOG[0]   = $sTxLog;
//      $this->oLog->CD_LOG[0]   = 'ACESSO_INVALIDO';
//      $this->oLog->CD_ACAO[0]  = 'I';
//      $this->oLog->TX_IP[0]    = $_SERVER['REMOTE_ADDR'];
//      $this->oLog->TX_TRACE[0] = $_SERVER['REQUEST_URI'];
//      $this->oLog->ID_USU[0]   = 0;
//      $this->oLog->inserir();
//
      if ($bBloquearAcesso) {
        echo $sPaginaRet;
        $sPaginaRet = is_null($sPaginaRet) ? $this->oUtil->sUrlBase : $sPaginaRet;
        header("Location: ".$sPaginaRet);
        exit;
      }
      return false;
    }

    return true;
  }
  /* clientes::dadosSessaoUsuario
  *
  * Atualiza os dados da sessão do usuário/cliente
  * 
  * @date 14/02/2013
  * @param
  * @return
  */  
  public function dadosSessaoUsuario($iIdUsu) {
    $_SESSION[$this->sCdEmpresa]['login']['tmp_atv']     = time();
    $_SESSION[$this->sCdEmpresa]['login']['token']       = base64_encode('cliente-'.$this->oCli->NM_CLIENTE[0].'-login-em-'.time());
    $_SESSION[$this->sCdEmpresa]['login']['id_usu']      = $iIdUsu;
    $_SESSION[$this->sCdEmpresa]['login']['nm_usu']      = $this->oCli->NM_CLIENTE[0];

  }
  
 /* clientes::atualizarToken
  *
  * Atualiza o campo tx_token na tabela de clientes
  * @date 14/02/2013
  * @param
  * @return
  */
  private function atualizarToken() {

    $sQuery = "UPDATE tc_clientes
                  SET tx_token = '".$_SESSION[$this->sCdEmpresa]['login']['token']."'
                WHERE id = ".$this->oCli->ID[0];
    $sResultado = mysql_query($sQuery, $this->DB_LINK);

    return true;
  }

 /* clientes::registrarLogin
  *
  * Registra na tabela de log o acesso de um cliente
  * @date 14/02/2013
  * @param
  * @return
  */
  public function registrarLogin() {
    $this->oLog->NM_LOG[0]   = 'Login de Cliente - '.$this->oCli->NM_CLIENTE[0];
    $this->oLog->TX_LOG[0]   = '';
    $this->oLog->CD_LOG[0]   = 'CLI_REG_LOG_SYS';
    $this->oLog->CD_ACAO[0]  = 'I';
    $this->oLog->TX_IP[0]    = $_SERVER['REMOTE_ADDR'];
    $this->oLog->TX_TRACE[0] = $_SERVER['REQUEST_URI'];//$this->sOrigem;
    $this->oLog->ID_USU[0]   = $this->oCli->ID[0];
    $this->oLog->inserir();
    return true;
  }

 /* clientes::registrarTentativaDeLogin
  *
  * Em caso de tentativa de acesso ao sistema sem sucesso, serão salvos os dados
  * na tabela de log por medida de segurança.
  * 
  * @date 14/02/2013
  * @param
  * @return
  */
  public function registrarTentativaDeLogin($sEmail, $sSenha) {
    $aDados = array('Tx_Login' => $sEmail,
                    'Tx_Senha' => md5($this->oCli->SCAPE.$sSenha) );
    $sTxLog = $this->oUtil->montarStringDados($aDados);

    $this->oLog->NM_LOG[0]   = 'Tentativa de Login de Cliente não realizado';
    $this->oLog->TX_LOG[0]   = $sTxLog;
    $this->oLog->CD_LOG[0]   = 'REG_ERROR_LOG_CLI';
    $this->oLog->CD_ACAO[0]  = 'A';
    $this->oLog->TX_IP[0]    = $_SERVER['REMOTE_ADDR'];
    $this->oLog->TX_TRACE[0] = $this->sOrigem;
    $this->oLog->ID_USU[0]   = 0;

    $this->oLog->inserir();
    return true;
  }

 /* clientes::validarLogin
  *
  * Nas páginas onde é obrigatório o login, este método verifica se o login esta
  * ativo e se ele é válido.
  * 
  * @date 14/02/2013
  * @param
  * @return
  */
  public function validarLogin($sUsuario, $sSenha, $bCrip = true) {
    $sUsuario = $this->oUtil->anti_sql_injection($sUsuario);
    $sSenha   = $this->oUtil->anti_sql_injection($sSenha);

    if($bCrip) {
      $sFiltro = "WHERE tx_email = '".$sUsuario."' AND  tx_senha = MD5('".$this->oCli->SCAPE.$sSenha."')";
    } else {
      $sFiltro = "WHERE tx_email = '".$sUsuario."' AND  tx_senha = ('".$sSenha."')";
    }
    $sFiltro .= " AND cd_status = 'A'";

    $this->listar($sFiltro);

    if (!$this->iLinhas) {
      $this->sMsg  = 'Seu email ou senha está incorreto!';
      $this->sErro = mysql_error();
      $this->sResultado = 'erro';
      //return false;
    }

    $this->sMsg  = 'Bem vindo '.$this->oCli->NM_CLIENTE[0];
    
    $this->registrarLogin();
    $this->dadosSessaoUsuario($this->oCli->ID[0]);
    $this->atualizarToken();

    return true;
  }
  
  
 /* clientes::deslogar
  *
  * Encerra a sessão do usuário e o redireciona para uma outra página, conforme
  * parâmetro cadastrado.
  * 
  * @date 14/02/2013
  * @param
  * @return
  */
  public function deslogar() {
    session_unset();
    $aRet = $this->oUtil->buscarParametro(array('CLI_PG_LOGOFF'));
    $sUrlRetorno = $aRet['CLI_PG_LOGOFF'][0];
    header('Location:'.$sUrlRetorno);
    
    die();
  }
  
 /* clientes::validarTempoSessaoCarrinho
  *
  * 
  * @date 15/02/2013
  * @param
  * @return
  */
  public function validarTempoSessaoCarrinho() {

    // Teste de inatividade de usuário
    $this->oUtil->buscarParametro('CARRINHO_SESSAO');
    $iParamExpira = $this->oUtil->aParametros['CARRINHO_SESSAO'][0] * 60;

    $_SESSION[$this->sCdEmpresa]['tmp_atv_carrinho'] = isset($_SESSION[$this->sCdEmpresa]['tmp_atv_carrinho']) ? $_SESSION[$this->sCdEmpresa]['tmp_atv_carrinho'] : 0;
    $iDifTime = time() - $_SESSION[$this->sCdEmpresa]['tmp_atv_carrinho'];

    if ($iDifTime > $iParamExpira) {
      $sTxLog = 'Tempo da sessão do usuário expirou';
      unset($_SESSION[$this->sCdEmpresa]['carrinho']);
      unset ($_SESSION[$this->sCdEmpresa]['tmp_atv_carrinho']);
      return false;
    } else {

      // Atualiza o tempo para que a sessao possa continuar ativa
      $_SESSION[$this->sCdEmpresa]['tmp_atv_carrinho'] = time();      
    }
    return true;
  }
  
  
 /* clientes::tratarFormLogin
  *
  * 
  * @date 16/02/2013
  * @param
  * @return
  */
  public function tratarFormLogin($iOpcaoSelecionada) {
    
    $this->aOpcoes = array( 0 => array('sOrigem' => 'checkout-identificacao',
                                   'sDirecionar' => $this->oUtil->sUrlBase.'/checkout/pagamento/'),

                            1 => array('sOrigem' => 'conta-login',
                                   'sDirecionar' => $this->oUtil->sUrlBase.'/conta/meus-dados/'),
    );
    
    $aCampos = array( 0 => array ('E-mail', $_POST['CMPemail'], 'email', true),
                      1 => array ('Senha' , $_POST['CMPsenha'], 'senha' , true)
                    );
    try {


      // Valida preenchimento dos campos
      if ($this->oUtil->valida_Preenchimento($aCampos) !== true) {
        $this->aMsg = $this->oUtil->aMsg;
        throw new Exception;
      }

      // Faz o teste para ver se os dados são válidos
      if ($this->validarLogin($_POST['CMPemail'], $_POST['CMPsenha'], true)) {
        header('location: '.$this->aOpcoes[$iOpcaoSelecionada]['sDirecionar']);
      } else {
        if (isset($oManCliente->oUsuario->CD_STATUS[0]) && $oManCliente->oUsuario->CD_STATUS[0] == 'I') {
          $this->aMsg = array('iCdMsg' => 2, 'sMsg' => 'Usuário inativo, por favor entre em contato conosco por um de nossos canais de atendimento');
          throw new Exception;
        }
        $this->aMsg = array('iCdMsg' => 1, 'sMsg' => 'Usuário ou senha não é válido');
        throw new Exception;
      }

    } catch (Exception $exc) {
      $this->sOrigem = $this->aOpcoes[$iOpcaoSelecionada]['sOrigem'];
      $this->registrarTentativaDeLogin($_POST['CMPemail'], $_POST['CMPsenha']);
    }
  }  

 /* clientes::tratarFormLoginNaoCadastrado
  *
  * 
  * @date 16/02/2013
  * @param
  * @return
  */
  public function tratarFormLoginNaoCadastrado($iOpcaoSelecionada) {
    $this->aOpcoes = array( 0 => array('sOrigem' => 'checkout-identificacao',
                                   'sDirecionar' => $this->oUtil->sUrlBase.'/checkout/pagamento/'),

                            1 => array('sOrigem' => 'conta-login',
                                   'sDirecionar' => $this->oUtil->sUrlBase.'/conta/meus-dados/'),
    );

    $aCampos = array( 0 => array ('E-mail', $_POST['CMPnovo-email'], 'email' , false),
                      1 => array ('Cep'   , $_POST['CMPcep'] , 'cep', true)
                    );
    try {
      // Valida preenchimento dos campos
      if ($this->oUtil->valida_Preenchimento($aCampos) !== true) {
        $this->aMsg = $this->oUtil->aMsg;
        throw new Exception;
      }
    $_SESSION['tmp']['cadastro']['sEmail']      = $_POST['CMPnovo-email'];
    $_SESSION['tmp']['cadastro']['sCep']        = $_POST['CMPcep'];
    $_SESSION['tmp']['cadastro']['sPagRetorno'] = $this->aOpcoes[$iOpcaoSelecionada]['sDirecionar'];

    header('location: '.$this->oUtil->sUrlBase.'/conta/cadastro/');

    } catch (Exception $exc) {

    } 
  }
  
 /* clientes::setOpcoesFormulario
  *
  * Opções de links para o direcionamento dos formulários de login.
  * Dependendo do local onde o formulário é criado, o usuário deve ser direcionado
  * para um destino diferente, obedecendo o fluxo.
  * 
  * @date 16/02/2013
  * @param
  * @return
  */
  public function setOpcoesFormulario() {
    $this->aOpcoes = array( 0 => array('sOrigem' => 'checkout-identificacao',
                                   'sDirecionar' => $this->oUtil->sUrlBase.'/checkout/pagamento/'),

                            1 => array('sOrigem' => 'conta-login',
                                   'sDirecionar' => $this->oUtil->sUrlBase.'/conta/meus-dados/'),
    );
    return true;
  }
 /* clientes::logoutCliente
  *
  * 
  * @date 03/03/2013
  * @param
  * @return
  */
  public function logoutCliente() {
    unset($_SESSION['INFO']);
  }

 /* clientes::getTipoCliente
  * Identificar se o cliente é PF ou Jurídica
  * 
  * @date 09/03/2013
  * @param
  * @return
  */
  public static function getTipoCliente ($iIdCliente) {
    $oUtil = new wTools();
    $aRet = $oUtil->pegaInfoDB('tc_clientes', 'id', 'WHERE nu_cnpj <> 0 AND id = '.$iIdCliente);
    
    return isset($aRet[0]) ? 'J' : 'F';
  }
  /* clientes::formAtualizarCadastro
  *  Formulário de edição parcial dos dados de clientes
  * 
  * @date 09/03/2013
  * @param
  * @return
  */
  public function formAtualizarCadastro($oCli) {
    $sTpPessoa = self::getTipoCliente($oCli->ID[0]);
    include 'config.php';
    ob_start();
    if ($sTpPessoa == 'F') {
      
    ?>
      <form method="post" action="<?php echo $this->oUtil->sUrlBase; ?>/conta/alterar-cadastro/">
        <input type="hidden" name="sAcao" value="editar" />
        <input type="hidden" name="CMPtpCadastro" value="PF" />
        <input type="hidden" name="CMPpagRetorno" value="<?php echo ''; ?>" />
        <input type="hidden" id="CMPsUrlBase" name="CMPsUrlBase" value="<?php echo $this->oUtil->sUrlBase; ?>" />
        <h1 class="titulo">Dados Pessoais</h1>
        <table style="width: 98%">
          <tr>
            <td class="infoheader">Nome*</td>
            <td class="infoheader">Sobrenome*</td>
            <td class="infoheader">RG*</td>
            <td class="infoheader">Telefone Fixo*</td>
          </tr>
          <tr>
            <td class="infovalue"><input type="text" name="CMPclientes-cliente" value="<?php echo $oCli->NM_CLIENTE[0]; ?>" /></td>
            <td class="infovalue"><input type="text" name="CMPclientes-sobrenome" value="<?php echo $oCli->NM_SOBRENOME[0]; ?>" /></td>
            <td class="infovalue"><input type="text" name="CMPclientes-rg" value="<?php echo $oCli->NU_RG[0]; ?>" /></td>
            <td class="infovalue"><input type="text" name="CMPclientes-tel" value="<?php echo $oCli->TX_TEL[0]; ?>" class="mask_telefone" /></td>
          </tr>
          <tr>
            <td class="infoheader">Data Nascimento*</td>
            <td class="infoheader">Sexo*</td>
            <td class="infoheader">CPF*</td>
            <td class="infoheader">Telefone Celular</td>
          </tr>
          <tr>
            <td class="infovalue"><input type="text" name="CMPclientes-nascimento" value="<?php echo $this->oUtil->parseValue($oCli->DT_NASCIMENTO[0], 'bd-dt'); ?>" class="mask_data" /></td>
            <td class="infovalue">
              <?php 
                $this->oUtil->montaSelect('CMPclientes-sexo', $CFGaSexo, $oCli->CD_SEXO[0]);
              ?>
            </td>
            <td class="infovalue"><input type="text" name="CMPclientes-cpf" value="<?php echo $oCli->NU_CPF[0]; ?>" class="mask_cpf" /></td>
            <td class="infovalue"><input type="text" name="CMPclientes-cel" value="<?php echo $oCli->TX_CEL[0]; ?>" class="mask_telefone" /></td>
          </tr>
        </table>
        <br /><br />
        <table style="width: 98%">
          <tr>
            <td class="infoheader w30">Desejo receber informativos</td>
            <td class="infovalue w70"><input type="checkbox" name="CMPclientes-recebe-news" <?php echo $oCli->CD_RECEBE_NEWS[0] == 'S' ? 'checked="checked"' : ''; ?> value="S"></td>
          </tr>
        </table>
        <br /><br />
        <input type="submit" value="Confirmar Alterações" />
      </form>
  <?php
    } else { ?>
      <form method="post" action="<?php echo $this->oUtil->sUrlBase; ?>/conta/alterar-cadastro/enderecos/">
        <input type="hidden" name="sAcao" value="editar" />
        <input type="hidden" name="CMPtpCadastro" value="PJ" />
        <input type="hidden" name="CMPpagRetorno" value="<?php echo ''; ?>" />
        <h1 class="titulo">Dados Pessoais</h1>
        <table style="width: 98%">
          <tr>
            <td class="infoheader">Nome do comprador*</td>
            <td class="infoheader">Sobrenome*</td>
            <td class="infoheader">Setor*</td>
            <td class="infoheader">Cargo*</td>
          </tr>
          <tr>
            <td class="infovalue"><input type="text" name="CMPclientes-cliente" value="<?php echo $oCli->NM_CLIENTE[0]; ?>" /></td>
            <td class="infovalue"><input type="text" name="CMPclientes-sobrenome" value="<?php echo $oCli->NM_SOBRENOME[0]; ?>" /></td>
            <td class="infovalue"><input type="text" name="CMPclientes-setor" value="<?php echo $oCli->TX_SETOR[0]; ?>" /></td>
            <td class="infovalue"><input type="text" name="CMPclientes-cargo" value="<?php echo $oCli->TX_CARGO[0]; ?>" /></td>
          </tr>
        </table>
        <table style="width: 98%">
          <tr>
            <td class="infoheader w30">CNPJ*</td>
            <td class="infoheader w30">Inscrição Estatual</td>
            <td class="infoheader w40" colspan="2">Razão Social*</td>
          </tr>
          <tr>
            <td class="infovalue"><input type="text" name="CMPclientes-cnpj" value="<?php echo $oCli->NU_CNPJ[0]; ?>" class="mask_cnpj w98"/></td>
            <td class="infovalue"><input type="text" name="CMPclientes-ie" value="<?php echo $oCli->NU_IE[0]; ?>" class="w98" /></td>
            <td class="infovalue"><input type="text" name="CMPclientes-razao-social" value="<?php echo $oCli->NM_RAZAO_SOCIAL[0]; ?>" class="w98" /></td>
          </tr>
        </table>
        <table style="width: 98%">
          <tr>
            <td class="infoheader">Nome Fantasia*</td>
            <td class="infoheader">Ramo/Atividade*</td>
            <td class="infoheader">Telefone 1*</td>
            <td class="infoheader">Telefone 2</td>
          </tr>
          <tr>
            <td class="infovalue"><input type="text" name="CMPclientes-fantasia" value="<?php echo $oCli->NM_FANTASIA[0]; ?>" /></td>
            <td class="infovalue"><input type="text" name="CMPclientes-segmento" value="<?php echo $oCli->TX_SEGMENTO[0]; ?>" /></td>
            <td class="infovalue"><input type="text" name="CMPclientes-tel" value="<?php echo $oCli->TX_TEL[0]; ?>" class="mask_telefone" /></td>
            <td class="infovalue"><input type="text" name="CMPclientes-cel" value="<?php echo $oCli->TX_CEL[0]; ?>" class="mask_telefone"/></td>
          </tr>
        </table>
        <br /><br />
        <table style="width: 98%">
          <tr>
            <td class="infoheader w30">Desejo receber informativos</td>
            <td class="infovalue w70"><input type="checkbox" name="CMPclientes-recebe-news" <?php echo $oCli->CD_RECEBE_NEWS[0] == 'S' ? 'checked="checked"' : ''; ?> value="S"></td>
          </tr>
        </table>
        <br /><br />
        <input type="submit" value="Confirmar Alterações" />
      </form>

  <?php 
    }
    $sRet = ob_get_clean();
    return $sRet;
  }
  /* clientes::tratarFormAtualizarCadastro
  *  Trata os dados do formulário de edição parcial dos dados de clientes
  * 
  * @date 16/03/2013
  * @param
  * @return
  */
  public function tratarFormAtualizarCadastro($iIdCliente) {
    if ($_POST['CMPtpCadastro'] == 'PF') {
      try {
        $oManClientes = new tc_clientes();

        $oManClientes->listar('WHERE id = '.$iIdCliente);

        $_POST['CMPclientes-cpf']         = str_replace( array('.',',','-'), '', $_POST['CMPclientes-cpf']);

        $oManClientes->NM_CLIENTE[0]      = $_POST['CMPclientes-cliente'];;
        $oManClientes->NM_SOBRENOME[0]    = $_POST['CMPclientes-sobrenome'];
        $oManClientes->NU_RG[0]           = $_POST['CMPclientes-rg'];;
        $oManClientes->NU_CPF[0]          = $_POST['CMPclientes-cpf'];
        $oManClientes->DT_NASCIMENTO[0]   = $this->oUtil->parseValue($_POST['CMPclientes-nascimento'], 'dt-bd');
        $oManClientes->TX_TEL[0]          = $_POST['CMPclientes-tel'];;
        $oManClientes->TX_CEL[0]          = $_POST['CMPclientes-cel'];
        $oManClientes->CD_SEXO[0]         = $_POST['CMPclientes-sexo'];
        $oManClientes->CD_RECEBE_NEWS[0]  = isset($_POST['CMPclientes-recebe-news']) ? 'S' : 'N';
        $aValidar = array ( 1 => array('Nome'          , $_POST['CMPclientes-cliente'], 'varchar', true),
                            2 => array('Sobrenome'     , $_POST['CMPclientes-sobrenome'], 'varchar', true),
                            3 => array('RG'            , $_POST['CMPclientes-rg'], 'int', true),
                            4 => array('Telefone'      , $_POST['CMPclientes-tel'], 'varchar', true),
                            5 => array('Nascimento'    , $_POST['CMPclientes-nascimento'], 'data', true),
                            6 => array('Sexo'          , $_POST['CMPclientes-sexo'], 'char', true),
                            7 => array('CPF'           , $_POST['CMPclientes-cpf'], 'varchar', true),
                            8 => array('Cel'           , $_POST['CMPclientes-cel'], 'varchar', false) 
            );
        
        // Mantém os dados digitados pelo usuário em um objeto para ser usado depois
        $this->oManClientes = $oManClientes;

        // Validação de preenchimento
        if ($this->oUtil->valida_Preenchimento($aValidar) !== true) {
          $this->aMsg = $this->oUtil->aMsg;
          throw new excecoes(25, 'Alteração de dados PF');
        }

        //Anti SQL injection
        foreach ($_POST as $sNome => $mValor) {
          if (!is_array($mValor)) {
            $_POST[$sNome] = $this->oUtil->anti_sql_injection($mValor);
          }
        }

        $oManClientes->editar($iIdCliente);
        $this->aMsg = $oManClientes->aMsg;
        
      } catch (excecoes $e) {
        $e->getErrorByCode();
        if (is_array($e->aMsg)) {
          $this->aMsg = $e->aMsg;
        }
      }
    }
    
    if ($_POST['sAcao'] == 'editar' && $_POST['CMPtpCadastro'] == 'PJ') {
      try {
        $oManClientes = new tc_clientes();

        $oManClientes->listar('WHERE id = '.$iIdCliente);

        $_POST['CMPclientes-cnpj']         = str_replace( array('.',',','-','/'), '', $_POST['CMPclientes-cnpj']);

        
        $oManClientes->NM_CLIENTE[0]      = $_POST['CMPclientes-cliente'];;
        $oManClientes->NM_SOBRENOME[0]    = $_POST['CMPclientes-sobrenome'];       
        $oManClientes->TX_SETOR[0]        = $_POST['CMPclientes-setor'];;
        $oManClientes->NU_CPF[0]          = $_POST['CMPclientes-cargo'];

        $oManClientes->NU_CNPJ[0]         = $_POST['CMPclientes-cnpj'];;
        $oManClientes->NU_IE[0]           = $_POST['CMPclientes-ie'];;
        $oManClientes->NM_RAZAO_SOCIAL[0] = $_POST['CMPclientes-razao-social'];

        $oManClientes->NM_FANTASIA[0]     = $_POST['CMPclientes-fantasia'];
        $oManClientes->TX_SEGMENTO[0]     = $_POST['CMPclientes-segmento'];
        $oManClientes->TX_TEL[0]          = $_POST['CMPclientes-tel'];
        $oManClientes->TX_CEL[0]          = $_POST['CMPclientes-cel'];

        $oManClientes->DT_NASCIMENTO[0]   = '0000-00-00';
        $oManClientes->CD_RECEBE_NEWS[0]  = isset($_POST['CMPclientes-recebe-news']) ? 'S' : 'N';

        $aValidar = array (  1 => array('Nome comprador'     , $_POST['CMPclientes-cliente'], 'varchar', true),
                             2 => array('Sobrenome'          , $_POST['CMPclientes-sobrenome'], 'varchar', true),
                             3 => array('Setor'              , $_POST['CMPclientes-setor'], 'varchar', true),
                             4 => array('Cargo'              , $_POST['CMPclientes-cargo'], 'varchar', true),

                             5 => array('CNPJ'               , $_POST['CMPclientes-cnpj'], 'float', true),
                             6 => array('Inscrição Estadual' , $_POST['CMPclientes-ie'], 'varchar', false),
                             7 => array('Razao Social'       , $_POST['CMPclientes-razao-social'], 'varchar', true),

                             8 => array('Nome Fantasia'      , $_POST['CMPclientes-fantasia'], 'varchar', true),
                             9 => array('Ramo/Atividade'     , $_POST['CMPclientes-segmento'], 'varchar', true),
                            10 => array('Telefone 1'         , $_POST['CMPclientes-tel'], 'varchar', true),
                            11 => array('Telefone 2'         , $_POST['CMPclientes-cel'], 'varchar', false),
            );

        // Mantém os dados digitados pelo usuário em um objeto para ser usado depois
        $this->oManClientes = $oManClientes;
      
        // Validação de preenchimento
        if ($this->oUtil->valida_Preenchimento($aValidar) !== true) {
          $this->aMsg = $this->oUtil->aMsg;
          throw new excecoes(25, 'Alteração de dados PJ');
        }

        //Anti SQL injection
        foreach ($_POST as $sNome => $mValor) {
          if (!is_array($mValor)) {
            $_POST[$sNome] = $this->oUtil->anti_sql_injection($mValor);
          }
        }

        $oManClientes->editar($iIdCliente);
        $this->aMsg = $oManClientes->aMsg;
        
      } catch (excecoes $e) {
        $e->getErrorByCode();
        if (is_array($e->aMsg)) {
          $this->aMsg = $e->aMsg;
        }
      }
    }
  }
  /* clientes::formAtualizarEndereco
  *  Formulário de edição dos endereços dos clientes
  * 
  * @date 16/03/2013
  * @param
  * @return
  */
  public function formAtualizarEndereco($oEnderecos) {

    //$sTpPessoa = self::getTipoCliente($oCli->ID[0]);
    include 'config.php';
    ob_start();  
    ?>
      <form method="post" action="<?php echo $this->oUtil->sUrlBase; ?>/conta/alterar-cadastro/enderecos/">
        <input type="hidden" name="sAcao" value="editar-endereco" />
        <input type="hidden" id="CMPsUrlBase" name="CMPsUrlBase" value="<?php echo $this->oUtil->sUrlBase; ?>" />
        <input type="hidden" name="CMPpagRetorno" value="<?php echo ''; ?>" />
        <h1 class="titulo">Dados de Endereço</h1>
        <table>
          <tr>
            <td class="infoheader">Digite seu CEP:</td>
            <td class="infovalue">
              <input type="text" id="CMPcep" name="CMPclientes-enderecos-cep" value="<?php echo $oEnderecos->NU_CEP[0]; ?>" class="mask_cep"/>
              <input type="button" class="bt_salvar buscar_cep" value="Ok" />
            </td>
          </tr>
        </table>
        <table style="width: 98%">
          <tr>
            <td class="infoheader" style="width: 70%">Endereço Residencial*</td>
            <td class="infoheader" style="width: 30%">Número*</td>
          </tr>
          <tr>
            <td class="infovalue">
              <input class="w98" type="text" id="CMPclientes-enderecos-logradouro" name="CMPclientes-enderecos-logradouro" value="<?php echo $oEnderecos->NM_LOGRADOURO[0]; ?>" />
              <input type="hidden" id="CMPclientes-enderecos-tp-logradouro" name="CMPclientes-enderecos-tp-logradouro" value="<?php echo $oEnderecos->TP_LOGRADOURO[0]; ?>" />
            </td>
            <td class="infovalue"><input class="w98" type="text" name="CMPclientes-enderecos-numero" value="<?php echo $oEnderecos->TX_NUMERO[0]; ?>" /></td>
          </tr>
        </table>
        <table style="width: 98%">
          <tr>                 
            <td class="infoheader" style="width: 40%">Complemento</td>
            <td class="infoheader" style="width: 25%">Bairro*</td>
            <td class="infoheader" style="width: 25%">Cidade*</td>
            <td class="infoheader" style="width: 10%">Estado*</td>
          </tr>
          <tr>
            <td class="infovalue"><input class="w98" type="text" id="CMPclientes-enderecos-complemento" name="CMPclientes-enderecos-complemento" value="<?php echo $oEnderecos->TX_COMPLEMENTO[0]; ?>" /></td>
            <td class="infovalue"><input class="w98" type="text" id="CMPclientes-enderecos-bairro" name="CMPclientes-enderecos-bairro" value="<?php echo $oEnderecos->TX_BAIRRO[0]; ?>" /></td>
            <td class="infovalue"><input class="w98" type="text" id="CMPclientes-enderecos-cid" name="CMPclientes-enderecos-cid" value="<?php echo $oEnderecos->NM_CID[0]; ?>" /></td>
            <td class="infovalue">
              <?php $this->oUtil->montaSelectDB('CMPclientes-enderecos-uf', 'tc_estados', 'sg_uf', 'nm_uf', $oEnderecos->NM_UF[0] , true)?>
            </td>
          </tr>
        </table>
        <br /><br />
        <input type="submit" value="Confirmar Alterações" />
      </form>
  <?php
    $sRet = ob_get_clean();
    return $sRet;

  }  
  
  
  /* clientes::tratarformAtualizarEndereco
  *  Trata os dados do formulário de edição dos endereços dos clientes
  * 
  * @date 16/03/2013
  * @param
  * @return
  */
  public function tratarformAtualizarEndereco($iIdCliente) {

    try {
      $oManEnderecos = new tc_clientes_enderecos();

      $oManEnderecos->listar('WHERE id = '.$iIdCliente);

      $oManEnderecos->NM_LOGRADOURO[0]  = $_POST['CMPclientes-enderecos-logradouro'];
      $oManEnderecos->TP_LOGRADOURO[0]  = $_POST['CMPclientes-enderecos-tp-logradouro'];
      $oManEnderecos->TX_NUMERO[0]      = $_POST['CMPclientes-enderecos-numero'];
      $oManEnderecos->TX_COMPLEMENTO[0] = $_POST['CMPclientes-enderecos-complemento'];
      $oManEnderecos->NU_CEP[0]         = str_replace('-', '', $_POST['CMPclientes-enderecos-cep']);
      $oManEnderecos->TX_BAIRRO[0]      = $_POST['CMPclientes-enderecos-bairro'];
      $oManEnderecos->NM_UF[0]          = $_POST['CMPclientes-enderecos-uf'];
      $oManEnderecos->NM_CID[0]         = $_POST['CMPclientes-enderecos-cid'];
      $oManEnderecos->ID_CLIENTE[0]     = $iIdCliente;

      $aValidar = array ( 20 => array('CEP'                  , $_POST['CMPclientes-enderecos-cep'], 'cep', true),
                          21 => array('Endereço Residencial' , $_POST['CMPclientes-enderecos-logradouro'], 'varchar', true),
                          22 => array('Logradouro'           , $_POST['CMPclientes-enderecos-complemento'], 'varchar', false),
                          23 => array('Número'               , $_POST['CMPclientes-enderecos-numero'], 'varchar', true),
                          24 => array('Complemento'          , $_POST['CMPclientes-enderecos-complemento'], 'varchar', false),
                          26 => array('Bairro'               , $_POST['CMPclientes-enderecos-bairro'], 'varchar', true),
                          27 => array('Cidade'               , $_POST['CMPclientes-enderecos-cid'], 'int', true),
                          28 => array('Estado'               , $_POST['CMPclientes-enderecos-uf'], 'int', true), 
          );

      // Mantém os dados digitados pelo usuário em um objeto para ser usado depois
      $this->oManEnderecos = $oManEnderecos;

      // Validação de preenchimento
      if ($this->oUtil->valida_Preenchimento($aValidar) !== true) {
        $this->aMsg = $this->oUtil->aMsg;
        throw new excecoes(25, 'Alteração de dados PF');
      }

      //Anti SQL injection
      foreach ($_POST as $sNome => $mValor) {
        if (!is_array($mValor)) {
          $_POST[$sNome] = $this->oUtil->anti_sql_injection($mValor);
        }
      }

      $oManEnderecos->editar('WHERE id_cliente = '.$iIdCliente);
      $this->aMsg = $oManEnderecos->aMsg;

    } catch (excecoes $e) {
      $e->getErrorByCode();
      if (is_array($e->aMsg)) {
        $this->aMsg = $e->aMsg;
      }
    }

  }
  
  
  
  /* clientes::formAtualizarEmail
  *  Trata os dados do formulário de edição parcial dos dados de clientes
  *  Dados referentes ao email do usuário
  * 
  * @date 16/03/2013
  * @param
  * @return
  */
  public function formAtualizarEmail($oCli) {
    include 'config.php';
    ob_start();  
    ?>
      <form method="post" action="<?php echo $this->oUtil->sUrlBase; ?>/conta/alterar-cadastro/email/">
        <input type="hidden" name="sAcao" value="editar-email" />
        <input type="hidden" id="CMPsUrlBase" name="CMPsUrlBase" value="<?php echo $this->oUtil->sUrlBase; ?>" />
        <input type="hidden" name="CMPpagRetorno" value="<?php echo ''; ?>" />
        <h1 class="titulo">Email</h1>
        <table style="width: 98%">
          <tr>                 
            <td class="infoheader">E-mail*</td>
          </tr>
          <tr>
            <td class="infovalue"><input class="w40" type="text" name="CMPclientes-email" value="<?php echo $oCli->TX_EMAIL[0]; ?>" /></td>
          </tr>
        </table>
        <br /><br />
        <input type="submit" value="Confirmar Alterações" />
      </form>
  <?php
    $sRet = ob_get_clean();
    return $sRet;
  }  
  
  
  /* clientes::tratarformAtualizarEmail
  *  Trata os dados do formulário de edição parcial dos dados de clientes
  * 
  * @date 16/03/2013
  * @param
  * @return
  */
  public function tratarformAtualizarEmail($iIdCliente) {
    try {
      $oManClientes = new tc_clientes();
      $oManClientes->listar('WHERE id = '.$iIdCliente);

      $oManClientes->TX_EMAIL[0]      = $_POST['CMPclientes-email'];;
      $oManClientes->DT_NASCIMENTO[0] = $this->oUtil->parseValue($oManClientes->DT_NASCIMENTO[0], 'dt-db');


      $aValidar = array (  1 => array('E-mail'     , $_POST['CMPclientes-email'], 'email', true),
          );

      // Mantém os dados digitados pelo usuário em um objeto para ser usado depois
      $this->oManClientes = $oManClientes;

      // Validação de preenchimento
      if ($this->oUtil->valida_Preenchimento($aValidar) !== true) {
        $this->aMsg = $this->oUtil->aMsg;
        throw new excecoes(25, 'Alteração de dados de email: Cliente '.$iIdCliente);
      }

      //Anti SQL injection
      foreach ($_POST as $sNome => $mValor) {
        if (!is_array($mValor)) {
          $_POST[$sNome] = $this->oUtil->anti_sql_injection($mValor);
        }
      }

      $oManClientes->editar($iIdCliente);
      $this->aMsg = $oManClientes->aMsg;

    } catch (excecoes $e) {
      $e->getErrorByCode();
      if (is_array($e->aMsg)) {
        $this->aMsg = $e->aMsg;
      }
    }
  }

  /* clientes::formAtualizarSenha
  *  Trata os dados do formulário de edição parcial dos dados de clientes
  *  Dados referentes à senha do usuário
  * 
  * @date 16/03/2013
  * @param
  * @return
  */
  public function formAtualizarSenha($oCli) {
    include 'config.php';
    ob_start();  
    ?>
      <form method="post" action="<?php echo $this->oUtil->sUrlBase; ?>/conta/alterar-cadastro/senha/">
        <input type="hidden" name="sAcao" value="editar-senha" />
        <input type="hidden" id="CMPsUrlBase" name="CMPsUrlBase" value="<?php echo $this->oUtil->sUrlBase; ?>" />
        <input type="hidden" name="CMPpagRetorno" value="<?php echo ''; ?>" />
        <h1 class="titulo">Senha de usuário</h1>
        <table style="width: 98%">
          <tr>
            <td class="infoheader w20">Nova Senha*</td>
            <td class="infovalue"><input class="w50" type="password" autocomplete="off" name="CMPclientes-senha" value="" /></td>
          </tr>
          <tr>
            <td class="infoheader">Confirmar Senha*</td>
            <td class="infovalue"><input class="w50" type="password" autocomplete="off" name="CMPclientes-senha2" value="" /></td>
          </tr>
        </table>
        <br /><br />
        <input type="submit" value="Confirmar Alterações" />
      </form>
  <?php
    $sRet = ob_get_clean();
    return $sRet;
  }  
  
  
  /* clientes::tratarformAtualizarSenha
  *  Trata os dados do formulário de edição parcial dos dados de clientes
  * 
  * @date 16/03/2013
  * @param
  * @return
  */
  public function tratarformAtualizarSenha($iIdCliente) {
    try {
      $oManClientes = new tc_clientes();
      $oManClientes->listar('WHERE id = '.$iIdCliente);

      $oManClientes->TX_SENHA[0]      = $_POST['CMPclientes-senha'];;
      $oManClientes->DT_NASCIMENTO[0] = $this->oUtil->parseValue($oManClientes->DT_NASCIMENTO[0], 'dt-db');


      $aValidar = array ( 32 => array('Nova Senha'      , $_POST['CMPclientes-senha'], 'senha-correta', true, $_POST['CMPclientes-senha2']),
                          34 => array('Confirmar Senha' , $_POST['CMPclientes-senha2'], 'varchar', true),
          );

      // Mantém os dados digitados pelo usuário em um objeto para ser usado depois
      $this->oManClientes = $oManClientes;

      // Validação de preenchimento
      if ($this->oUtil->valida_Preenchimento($aValidar) !== true) {
        $this->aMsg = $this->oUtil->aMsg;
        throw new excecoes(25, 'Alteração de dados de senha de usuário: Cliente '.$iIdCliente);
      }

      //Anti SQL injection
      foreach ($_POST as $sNome => $mValor) {
        if (!is_array($mValor)) {
          $_POST[$sNome] = $this->oUtil->anti_sql_injection($mValor);
        }
      }

      $oManClientes->editar($iIdCliente, true);
      $this->aMsg = $oManClientes->aMsg;

    } catch (excecoes $e) {
      $e->getErrorByCode();
      if (is_array($e->aMsg)) {
        $this->aMsg = $e->aMsg;
      }
    }    
  }
  
  
  

}
?>
