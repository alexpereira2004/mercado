
<?php
  /**
   * Descricao
   *
   * @package    Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       06-04-2012
   **/

  class tc_imagens {
  
    public $id;
    public $tx_link;
    public $nm_imagem;
    public $cd_tipo;
    public $cd_status;
    public $cd_extensao;
    public $de_breve;

    public    $iCdMsg;
    public    $sMsg;
    public    $aMsg;
    public    $sErro;
    protected $DB_LINK;

    public function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
    }

      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        tx_link, 
                        nm_imagem, 
                        cd_tipo, 
                        cd_status, 
                        cd_extensao,
                        de_breve
                   FROM tc_imagens
                   '.$sFiltro;

      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_imagens = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]          = $aResultado['id']; 
        $this->TX_LINK[]     = $aResultado['tx_link']; 
        $this->NM_IMAGEM[]   = $aResultado['nm_imagem']; 
        $this->CD_TIPO[]     = $aResultado['cd_tipo']; 
        $this->CD_STATUS[]   = $aResultado['cd_status']; 
        $this->CD_EXTENSAO[] = $aResultado['cd_extensao']; 
        $this->DE_BREVE[]    = $aResultado['de_breve']; 
      }
    }

    public function inserir() {

      $sQuery = "INSERT INTO tc_imagens(
                             TX_LINK, 
                             NM_IMAGEM, 
                             CD_TIPO, 
                             CD_STATUS, 
                             CD_EXTENSAO,
                             DE_BREVE
)
      VALUES(
              '".$this->TX_LINK[0]."', 
              '".$this->NM_IMAGEM[0]."', 
              '".$this->CD_TIPO[0]."', 
              '".$this->CD_STATUS[0]."', 
              '".$this->CD_EXTENSAO[0]."',
              '".$this->DE_BREVE[0]."' 
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

    public function remover($sFiltro = '') {
      $sQuery = "DELETE FROM tc_imagens
                       ".$sFiltro;
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
      $sQuery = "UPDATE tc_imagens
        SET
          tx_link     = '".$this->TX_LINK[0]."', 
          nm_imagem   = '".$this->NM_IMAGEM[0]."', 
          cd_tipo     = '".$this->CD_TIPO[0]."', 
          cd_status   = '".$this->CD_STATUS[0]."', 
          de_breve    = '".$this->DE_BREVE[0]."', 
          cd_extensao = '".$this->CD_EXTENSAO[0]."' 
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

      $this->ID[0]          = '';
      $this->TX_LINK[0]     = '';
      $this->NM_IMAGEM[0]   = '';
      $this->CD_TIPO[0]     = '';
      $this->CD_STATUS[0]   = '';
      $this->CD_EXTENSAO[0] = '';
      $this->DE_BREVE[0]    = '';
      
    }
  }