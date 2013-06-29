
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       15-01-2013
   **/

  class tc_contatos {
  
    public    $id;
    public    $nm_contato;
    public    $nm_empresa;
    public    $tx_cargo;
    public    $tx_endereco;
    public    $tx_cidade;
    public    $cd_uf;
    public    $tx_email;
    public    $tx_telefone;
    public    $tx_celular;
    public    $de_comonosconheceu;
    public    $tx_assunto;
    public    $de_mensagem;
    public    $dt_envio;
    public    $hr_envio;
    public    $tx_ip;
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
        $aValidar = array ( 1 => array('Contato' , $_POST['CMPcontatos-contato'], 'varchar(30)', true),
                            2 => array('Empresa' , $_POST['CMPcontatos-empresa'], 'varchar(30)', false),
                            3 => array('Cargo' , $_POST['CMPcontatos-cargo'], 'varchar(30)', false),
                            4 => array('Endereco' , $_POST['CMPcontatos-endereco'], 'varchar(100)', false),
                            5 => array('Cidade' , $_POST['CMPcontatos-cidade'], 'varchar(30)', false),
                            6 => array('Uf' , $_POST['CMPcontatos-uf'], 'char(2)', false),
                            7 => array('Email' , $_POST['CMPcontatos-email'], 'varchar(30)', true),
                            8 => array('Telefone' , $_POST['CMPcontatos-telefone'], 'varchar(15)', true),
                            9 => array('Celular' , $_POST['CMPcontatos-celular'], 'varchar(15)', false),
                            10 => array('Comonosconheceu' , $_POST['CMPcontatos-comonosconheceu'], 'text', false),
                            11 => array('Eu gostaria de' , $_POST['CMPcontatos-assunto'], 'varchar(30)', true),
                            12 => array('Mensagem' , $_POST['CMPcontatos-mensagem'], 'text', true),
                            //13 => array('Envio' , $_POST['CMPcontatos-envio'], 'date', true),
                            //14 => array('Envio' , $_POST['CMPcontatos-envio'], 'time', true),
                            15 => array('Ip' , $_POST['CMPcontatos-ip'], 'varchar(30)', true),
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
                        nm_contato, 
                        nm_empresa, 
                        tx_cargo, 
                        tx_endereco, 
                        tx_cidade, 
                        cd_uf, 
                        tx_email, 
                        tx_telefone, 
                        tx_celular, 
                        de_comonosconheceu, 
                        tx_assunto, 
                        de_mensagem, 
                        date_format(dt_envio, "%d/%m/%Y") AS dt_envio, 
                        date_format(hr_envio, "%H:%i") AS hr_envio, 
                        tx_ip 
                   FROM tc_contatos
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_contatos = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]                 = $aResultado['id']; 
        $this->NM_CONTATO[]         = $aResultado['nm_contato']; 
        $this->NM_EMPRESA[]         = $aResultado['nm_empresa']; 
        $this->TX_CARGO[]           = $aResultado['tx_cargo']; 
        $this->TX_ENDERECO[]        = $aResultado['tx_endereco']; 
        $this->TX_CIDADE[]          = $aResultado['tx_cidade']; 
        $this->CD_UF[]              = $aResultado['cd_uf']; 
        $this->TX_EMAIL[]           = $aResultado['tx_email']; 
        $this->TX_TELEFONE[]        = $aResultado['tx_telefone']; 
        $this->TX_CELULAR[]         = $aResultado['tx_celular']; 
        $this->DE_COMONOSCONHECEU[] = $aResultado['de_comonosconheceu']; 
        $this->TX_ASSUNTO[]         = $aResultado['tx_assunto']; 
        $this->DE_MENSAGEM[]        = $aResultado['de_mensagem']; 
        $this->DT_ENVIO[]           = $aResultado['dt_envio']; 
        $this->HR_ENVIO[]           = $aResultado['hr_envio']; 
        $this->TX_IP[]              = $aResultado['tx_ip']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_contatos(
                             NM_CONTATO, 
                             NM_EMPRESA, 
                             TX_CARGO, 
                             TX_ENDERECO, 
                             TX_CIDADE, 
                             CD_UF, 
                             TX_EMAIL, 
                             TX_TELEFONE, 
                             TX_CELULAR, 
                             DE_COMONOSCONHECEU, 
                             TX_ASSUNTO, 
                             DE_MENSAGEM, 
                             DT_ENVIO, 
                             HR_ENVIO, 
                             TX_IP 
)
      VALUES(
              '".$this->NM_CONTATO[0]."', 
              '".$this->NM_EMPRESA[0]."', 
              '".$this->TX_CARGO[0]."', 
              '".$this->TX_ENDERECO[0]."', 
              '".$this->TX_CIDADE[0]."', 
              '".$this->CD_UF[0]."', 
              '".$this->TX_EMAIL[0]."', 
              '".$this->TX_TELEFONE[0]."', 
              '".$this->TX_CELULAR[0]."', 
              '".$this->DE_COMONOSCONHECEU[0]."', 
              '".$this->TX_ASSUNTO[0]."', 
              '".$this->DE_MENSAGEM[0]."', 
              curdate(),
              curtime(), 
              '".$this->TX_IP[0]."' 
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
        $this->sMsg  = 'Obrigado por entrar em contato!';
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
      $sQuery = "DELETE FROM tc_contatos
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
      $sQuery = "UPDATE tc_contatos
        SET
          nm_contato         = '".$this->NM_CONTATO[0]."', 
          nm_empresa         = '".$this->NM_EMPRESA[0]."', 
          tx_cargo           = '".$this->TX_CARGO[0]."', 
          tx_endereco        = '".$this->TX_ENDERECO[0]."', 
          tx_cidade          = '".$this->TX_CIDADE[0]."', 
          cd_uf              = '".$this->CD_UF[0]."', 
          tx_email           = '".$this->TX_EMAIL[0]."', 
          tx_telefone        = '".$this->TX_TELEFONE[0]."', 
          tx_celular         = '".$this->TX_CELULAR[0]."', 
          de_comonosconheceu = '".$this->DE_COMONOSCONHECEU[0]."', 
          tx_assunto         = '".$this->TX_ASSUNTO[0]."', 
          de_mensagem        = '".$this->DE_MENSAGEM[0]."', 
          dt_envio           = '".$this->DT_ENVIO[0]."', 
          hr_envio           = '".$this->HR_ENVIO[0]."', 
          tx_ip              = '".$this->TX_IP[0]."' 
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

      $this->ID[0]                 = (isset ($_POST['CMPcontatos-id'])                 ? $_POST['CMPcontatos-id']                 : '');
      $this->NM_CONTATO[0]         = (isset ($_POST['CMPcontatos-contato'])         ? $_POST['CMPcontatos-contato']         : '');
      $this->NM_EMPRESA[0]         = (isset ($_POST['CMPcontatos-empresa'])         ? $_POST['CMPcontatos-empresa']         : '');
      $this->TX_CARGO[0]           = (isset ($_POST['CMPcontatos-cargo'])           ? $_POST['CMPcontatos-cargo']           : '');
      $this->TX_ENDERECO[0]        = (isset ($_POST['CMPcontatos-endereco'])        ? $_POST['CMPcontatos-endereco']        : '');
      $this->TX_CIDADE[0]          = (isset ($_POST['CMPcontatos-cidade'])          ? $_POST['CMPcontatos-cidade']          : '');
      $this->CD_UF[0]              = (isset ($_POST['CMPcontatos-uf'])              ? $_POST['CMPcontatos-uf']              : '');
      $this->TX_EMAIL[0]           = (isset ($_POST['CMPcontatos-email'])           ? $_POST['CMPcontatos-email']           : '');
      $this->TX_TELEFONE[0]        = (isset ($_POST['CMPcontatos-telefone'])        ? $_POST['CMPcontatos-telefone']        : '');
      $this->TX_CELULAR[0]         = (isset ($_POST['CMPcontatos-celular'])         ? $_POST['CMPcontatos-celular']         : '');
      $this->DE_COMONOSCONHECEU[0] = (isset ($_POST['CMPcontatos-comonosconheceu']) ? $_POST['CMPcontatos-comonosconheceu'] : '');
      $this->TX_ASSUNTO[0]         = (isset ($_POST['CMPcontatos-assunto'])         ? $_POST['CMPcontatos-assunto']         : '');
      $this->DE_MENSAGEM[0]        = (isset ($_POST['CMPcontatos-mensagem'])        ? $_POST['CMPcontatos-mensagem']        : '');
      $this->DT_ENVIO[0]           = (isset ($_POST['CMPcontatos-envio'])           ? $_POST['CMPcontatos-envio']           : '');
      $this->HR_ENVIO[0]           = (isset ($_POST['CMPcontatos-envio'])           ? $_POST['CMPcontatos-envio']           : '');
      $this->TX_IP[0]              = (isset ($_POST['CMPcontatos-ip'])              ? $_POST['CMPcontatos-ip']              : '');
      
    }
  }