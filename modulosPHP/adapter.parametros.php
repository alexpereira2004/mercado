<?php
  include 'class.tcseg_parametros.php';
  include 'class.trseg_parametros_valores.php';
  include_once 'class.wTools.php';
/**
 * Description of adapter
 *
 * @author Alex Lunardelli
 */
class adapter_parametros {

  // Tabela de campos dos parâmetros
  public    $id;
  public    $nm_parametro;
  public    $cd_parametro;
  public    $tx_explicativo;
  public    $cd_tipo;
  public    $nu_limite_cadastro;
  public    $cd_ativo;
  public    $nu_ordem;
  public    $nu_importancia;
  public    $tx_mascara;
  public    $vl_padrao;

  // Tabela de Valores dos paâmetros
  public    $id_parametro;
  public    $tx_valor;
  public    $tx_func;
  public    $dt_log;
  public    $hr_log;
  public    $id_usu;

  public    $iCdMsg;
  public    $sMsg;
  public    $aMsg;
  public    $sErro;

  public $oParametros;
  public $oValores;
  private $DB_LINK;
  
  public function __construct() {
    include 'conecta.php';
    $this->DB_LINK = $link;
    $this->oParametros = new tcseg_parametros();
    $this->oValores    = new trseg_parametros_valores();
    $this->oUtil       = new wTools();
  }

  public function trocarStatus($iId) {
    $aRet = $this->oUtil->pegaInfoDB('trseg_parametros_valores', 'cd_ativo', 'WHERE id = '.$iId);
    $sNovoStatus = $aRet[0] == 'A' ? 'I' : 'A';

    $sQuery = "UPDATE trseg_parametros_valores
                  SET cd_ativo = '".$sNovoStatus."',
                      id_usu       = '".($_SESSION[usuario_admin::$CD_EMPRESA]['id_usu'])."'
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

  public function removerValorParametro($iId) {
    $this->oValores->remover($iId);
  }

  public function salvarParametros($aIds, $aValores, $aIdParametros) {

    //$this->oValores    = new trseg_parametros_valores();

    for ($i = 0; $i < count($aIds); $i++) {

      $this->oValores->ID_PARAMETRO[0] = $aIdParametros[$i];
      $this->oValores->TX_VALOR[0]     = $aValores[$i];
      $this->oValores->TX_FUNC[0]      = '';
      $this->oValores->ID_USU[0]       = (isset($_SESSION[usuario_admin::$CD_EMPRESA]['id_usu']) ? $_SESSION[usuario_admin::$CD_EMPRESA]['id_usu'] : '0');
      $this->oValores->CD_ATIVO[0]     = 'A';

      if ($aIds[$i] != '') {
        $this->oValores->editar($aIds[$i]);
      } else {
        $this->oValores->inserir();
      }
      $this->aMsg = $this->oValores->aMsg;

    }
  }

  public function listar($sFiltro = '') {
    $aRet = $this->oUtil->pegaInfoDB('tc_usu_admin', 'cd_nivel','WHERE id = '.$_SESSION[usuario_admin::$CD_EMPRESA]['id_usu']);
    if ( $aRet[0] == 1) {
      $sFiltroTipoUso = "'PM', 'CF'";
    } else {
      $sFiltroTipoUso = "'PM'";
    }
    $sQuery = 'SELECT
                      -- tcseg_parametros
                      tcseg_parametros.id,
                      tcseg_parametros.nm_parametro,
                      tcseg_parametros.cd_parametro,
                      tcseg_parametros.tx_explicativo,
                      tcseg_parametros.cd_tipo,
                      tcseg_parametros.nu_limite_cadastro,
                      -- tcseg_parametros.cd_ativo,
                      tcseg_parametros.nu_ordem,
                      tcseg_parametros.nu_importancia,
                      tcseg_parametros.tx_mascara,
                      tcseg_parametros.vl_padrao,
                      
                      -- trseg_parametros_valores
                      trseg_parametros_valores.id    AS id_valor,
                      trseg_parametros_valores.id_parametro,
                      trseg_parametros_valores.tx_valor,
                      trseg_parametros_valores.tx_func,
                      date_format(trseg_parametros_valores.dt_log, "%d/%m/%Y") AS dt_log,
                      date_format(trseg_parametros_valores.hr_log, "%H:%i")    AS hr_log,
                      trseg_parametros_valores.id_usu,
                      trseg_parametros_valores.cd_ativo

                 FROM tcseg_parametros
            LEFT JOIN trseg_parametros_valores ON trseg_parametros_valores.id_parametro = tcseg_parametros.id
                '.$sFiltro.'
             ORDER BY nu_ordem ASC';
      $sResultado = mysql_query($sQuery, $this->DB_LINK);
      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhastcseg_parametros = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {

        $this->ID[]                 = $aResultado['id'];
        $this->NM_PARAMETRO[]       = $aResultado['nm_parametro'];
        $this->CD_PARAMETRO[]       = $aResultado['cd_parametro'];
        $this->TX_EXPLICATIVO[]     = $aResultado['tx_explicativo'];
        $this->CD_TIPO[]            = $aResultado['cd_tipo'];
        $this->NU_LIMITE_CADASTRO[] = $aResultado['nu_limite_cadastro'];
        // $this->CD_ATIVO[]           = $aResultado['cd_ativo'];
        $this->NU_ORDEM[]           = $aResultado['nu_ordem'];
        $this->NU_IMPORTANCIA[]     = $aResultado['nu_importancia'];
        $this->TX_MASCARA[]         = $aResultado['tx_mascara'];
        $this->VL_PADRAO[]          = $aResultado['vl_padrao'];
        $this->iTotais[$aResultado['cd_parametro']][] = $aResultado['cd_parametro'];


        $this->ID_VALOR[]     = $aResultado['id_valor'];// ? $aResultado['id_valor'] : '_';
        $this->ID_PARAMETRO[] = $aResultado['id_parametro'];
        $this->TX_VALOR[]     = $aResultado['tx_valor'];
        $this->TX_FUNC[]      = $aResultado['tx_func'];
        $this->DT_LOG[]       = $aResultado['dt_log'];
        $this->HR_LOG[]       = $aResultado['hr_log'];
        $this->ID_USU[]       = $aResultado['id_usu'];
        $this->CD_ATIVO[]     = $aResultado['cd_ativo'];
    }
  }


  /* adapter_parametros::criarNovoCampoParametro
   *
   * Quando os paâmetros ainda tem possibilidade de receber novos valores, os campos
   * deverão ser criados dentro da tabela de relacionamento, após eles poderão ter
   * seus valores também editados.
   *
   * @date 31/05/2012
   * @param
   * @return
   */
  public function criarNovoCampoParametro() {
    if (!is_numeric($this->ID_PARAMETRO[0])) {
      $this->ID_PARAMETRO[0] = trim(ereg_replace("[^0-9]", " ", $this->ID_PARAMETRO[0]));
    }
    $sQuery = "INSERT INTO trseg_parametros_valores(
                           ID_PARAMETRO,
                           TX_FUNC,
                           DT_LOG,
                           HR_LOG,
                           ID_USU )
    VALUES( '".$this->ID_PARAMETRO[0]."',
            '".$this->TX_FUNC[0]."',
            curdate(),
            curtime(),
            '".$this->ID_USU[0]."' )";
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

}
?>
