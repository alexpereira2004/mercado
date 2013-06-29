
<?php
  /**
   * Descricao
   *
   * @package    Site Lunacom
   * @author     Alex Lunardelli <alex@lunacom.com.bralex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       26-06-2012
   **/

  class tc_permissoes {
  
    public    $id;
    public    $nm_permissao;
    public    $cd_tipo;
    public    $cd_codigo;
    public    $iCdMsg;
    public    $sMsg;
    public    $aMsg;
    public    $sErro;
    protected $DB_LINK;

    public function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
    }


    public function getNm_permissao($nm_permissao) {
      return $this->nm_permissao;
    }

    public function getCd_tipo($cd_tipo) {
      return $this->cd_tipo;
    }

    public function getCd_codigo($cd_codigo) {
      return $this->cd_codigo;
    }



    public function setNm_permissao($nm_permissao) {
      $this->nm_permissao = $nm_permissao;
    }

    public function setCd_tipo($cd_tipo) {
      $this->cd_tipo = $cd_tipo;
    }

    public function setCd_codigo($cd_codigo) {
      $this->cd_codigo = $cd_codigo;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_permissao, 
                        cd_tipo, 
                        cd_codigo 
                   FROM tc_permissoes
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_permissoes = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]           = $aResultado['id']; 
        $this->NM_PERMISSAO[] = $aResultado['nm_permissao']; 
        $this->CD_TIPO[]      = $aResultado['cd_tipo']; 
        $this->CD_CODIGO[]    = $aResultado['cd_codigo']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_permissoes(
                             NM_PERMISSAO, 
                             CD_TIPO, 
                             CD_CODIGO 
)
      VALUES(
              '".$this->NM_PERMISSAO[0]."', 
              '".$this->CD_TIPO[0]."', 
              '".$this->CD_CODIGO[0]."' 
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
      $sQuery = "DELETE FROM tc_permissoes
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
      $sQuery = "UPDATE tc_permissoes
        SET
          nm_permissao = '".$this->NM_PERMISSAO[0]."', 
          cd_tipo      = '".$this->CD_TIPO[0]."', 
          cd_codigo    = '".$this->CD_CODIGO[0]."' 
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

      $this->ID[0]           = '';
      $this->NM_PERMISSAO[0] = '';
      $this->CD_TIPO[0]      = '';
      $this->CD_CODIGO[0]    = '';
      
    }
  }