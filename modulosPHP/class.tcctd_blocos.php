
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       05-03-2013
   **/

  class tcctd_blocos {
  
    public    $id;
    public    $nm_bloco;
    public    $de_bloco;
    public    $tx_conteudo;
    public    $cd_bloco;
    public    $tp_secao;
    public    $tx_arq_css;
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
        $aValidar = array ( 1 => array('Bloco' , $_POST['CMPblocos-bloco'], 'varchar(100)', true),
                            2 => array('Bloco' , $_POST['CMPblocos-bloco'], 'text', true),
                            3 => array('Conteudo' , $_POST['CMPblocos-conteudo'], 'text', true),
                            4 => array('Bloco' , $_POST['CMPblocos-bloco'], 'varchar(25)', true),
                            5 => array('Secao' , $_POST['CMPblocos-secao'], 'varchar(25)', true),
                            6 => array('Arq-css' , $_POST['CMPblocos-arq-css'], 'varchar(100)', true),
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
                        nm_bloco, 
                        de_bloco, 
                        tx_conteudo, 
                        cd_bloco, 
                        tp_secao, 
                        tx_arq_css 
                   FROM tcctd_blocos
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTcctd_blocos = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]          = $aResultado['id']; 
        $this->NM_BLOCO[]    = $aResultado['nm_bloco']; 
        $this->DE_BLOCO[]    = $aResultado['de_bloco']; 
        $this->TX_CONTEUDO[] = $aResultado['tx_conteudo']; 
        $this->CD_BLOCO[]    = $aResultado['cd_bloco']; 
        $this->TP_SECAO[]    = $aResultado['tp_secao']; 
        $this->TX_ARQ_CSS[]  = $aResultado['tx_arq_css']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tcctd_blocos(
                             NM_BLOCO, 
                             DE_BLOCO, 
                             TX_CONTEUDO, 
                             CD_BLOCO, 
                             TP_SECAO, 
                             TX_ARQ_CSS 
)
      VALUES(
              '".$this->NM_BLOCO[0]."', 
              '".$this->DE_BLOCO[0]."', 
              '".$this->TX_CONTEUDO[0]."', 
              '".$this->CD_BLOCO[0]."', 
              '".$this->TP_SECAO[0]."', 
              '".$this->TX_ARQ_CSS[0]."' 
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
      $sQuery = "DELETE FROM tcctd_blocos
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
      $sQuery = "UPDATE tcctd_blocos
        SET
          nm_bloco    = '".$this->NM_BLOCO[0]."', 
          de_bloco    = '".$this->DE_BLOCO[0]."', 
          tx_conteudo = '".$this->TX_CONTEUDO[0]."', 
          cd_bloco    = '".$this->CD_BLOCO[0]."', 
          tp_secao    = '".$this->TP_SECAO[0]."', 
          tx_arq_css  = '".$this->TX_ARQ_CSS[0]."' 
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

      $this->ID[0]          = (isset ($_POST['CMPblocos-id'])          ? $_POST['CMPblocos-id']          : '');
      $this->NM_BLOCO[0]    = (isset ($_POST['CMPblocos-bloco'])    ? $_POST['CMPblocos-bloco']    : '');
      $this->DE_BLOCO[0]    = (isset ($_POST['CMPblocos-bloco'])    ? $_POST['CMPblocos-bloco']    : '');
      $this->TX_CONTEUDO[0] = (isset ($_POST['CMPblocos-conteudo']) ? $_POST['CMPblocos-conteudo'] : '');
      $this->CD_BLOCO[0]    = (isset ($_POST['CMPblocos-bloco'])    ? $_POST['CMPblocos-bloco']    : '');
      $this->TP_SECAO[0]    = (isset ($_POST['CMPblocos-secao'])    ? $_POST['CMPblocos-secao']    : '');
      $this->TX_ARQ_CSS[0]  = (isset ($_POST['CMPblocos-arq-css'])  ? $_POST['CMPblocos-arq-css']  : '');
      
    }
  }