
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       22-03-2012
   **/

  include 'conecta.php';

  class tclv_usu_admin {
  
    public $id;
    public $nm_usuario;
    public $tx_email;
    public $tx_senha;
    public $dt_cad;
    public $cd_status;
    public $cd_nivel;
    public $iCdMsg;
    public $sMsg;
    public $sErro;

    public function getNm_usuario($nm_usuario) {
      return $this->nm_usuario;
    }

    public function getTx_email($tx_email) {
      return $this->tx_email;
    }

    public function getTx_senha($tx_senha) {
      return $this->tx_senha;
    }

    public function getDt_cad($dt_cad) {
      return $this->dt_cad;
    }

    public function getCd_status($cd_status) {
      return $this->cd_status;
    }

    public function getCd_nivel($cd_nivel) {
      return $this->cd_nivel;
    }



    public function setNm_usuario($nm_usuario) {
      $this->nm_usuario = $nm_usuario;
    }

    public function setTx_email($tx_email) {
      $this->tx_email = $tx_email;
    }

    public function setTx_senha($tx_senha) {
      $this->tx_senha = $tx_senha;
    }

    public function setDt_cad($dt_cad) {
      $this->dt_cad = $dt_cad;
    }

    public function setCd_status($cd_status) {
      $this->cd_status = $cd_status;
    }

    public function setCd_nivel($cd_nivel) {
      $this->cd_nivel = $cd_nivel;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_usuario, 
                        tx_email, 
                        tx_senha, 
                        date_format(dt_cad, "%d/%m/%Y") AS dt_cad, 
                        cd_status, 
                        cd_nivel 
                   FROM tclv_usu_admin
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTclv_usu_admin = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]         = $aResultado['id']; 
        $this->NM_USUARIO[] = $aResultado['nm_usuario']; 
        $this->TX_EMAIL[]   = $aResultado['tx_email']; 
        $this->TX_SENHA[]   = $aResultado['tx_senha']; 
        $this->DT_CAD[]     = $aResultado['dt_cad']; 
        $this->CD_STATUS[]  = $aResultado['cd_status']; 
        $this->CD_NIVEL[]   = $aResultado['cd_nivel']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tclv_usu_admin(
                             NM_USUARIO, 
                             TX_EMAIL, 
                             TX_SENHA, 
                             DT_CAD, 
                             CD_STATUS, 
                             CD_NIVEL 
)
      VALUES(
              '".$this->NM_USUARIO[0]."', 
              '".$this->TX_EMAIL[0]."', 
              '".$this->TX_SENHA[0]."', 
              '".$this->DT_CAD[0]."', 
              '".$this->CD_STATUS[0]."', 
              '".$this->CD_NIVEL[0]."' 
    )";
      $sResultado = mysql_query($sQuery);

      if (!$sResultado) {
        $this->iCdMsg = 1;
        $this->sMsg  = 'Ocorreu um erro ao salvar o registro.';
        $this->sErro = mysql_error();
    		$this->sResultado = 'erro';
        return false;

    	} else {
        $this->iCdMsg = 0;
        $this->sMsg  = 'O registro foi adicionado com sucesso!';
        $this->sResultado = 'sucesso';
      }
     return true;
    }

    public function remover($iId = '') {
      $sQuery = "DELETE FROM tclv_usu_admin
                       WHERE id = ".$iId;
      $sResultado = mysql_query($sQuery);

      if (!$sResultado) {
        $this->iCdMsg = 1;
        $this->sMsg  = 'Ocorreu um erro ao remover o registro.';
        $this->sErro = mysql_error();
        $this->sResultado = 'erro';
        return false;

      } else {
        $this->iCdMsg = 0;
        $this->sMsg  = 'O registro foi removido com sucesso!';
        $this->sResultado = 'sucesso';
      }
     return true;
  }

    public function editar($iId = '') {
      $sQuery = "UPDATE tclv_usu_admin
        SET
          nm_usuario = '".$this->NM_USUARIO[0]."', 
          tx_email   = '".$this->TX_EMAIL[0]."', 
          tx_senha   = '".$this->TX_SENHA[0]."', 
          dt_cad     = '".$this->DT_CAD[0]."', 
          cd_status  = '".$this->CD_STATUS[0]."', 
          cd_nivel   = '".$this->CD_NIVEL[0]."' 
          WHERE id = ".$iId;
      $sResultado = mysql_query($sQuery);

      if (!$sResultado) {
        $this->iCdMsg = 1;
        $this->sMsg  = 'Ocorreu um erro ao salvar o registro.';
        $this->sErro = mysql_error();
    		$this->sResultado = 'erro';
        return false;

    	} else {
        $this->iCdMsg = 0;
        $this->sMsg  = 'O registro foi editado com sucesso!';
        $this->sResultado = 'sucesso';
      }
     return true;
     
    }

    public function inicializaAtributos() {

      $this->ID[0]         = '';
      $this->NM_USUARIO[0] = '';
      $this->TX_EMAIL[0]   = '';
      $this->TX_SENHA[0]   = '';
      $this->DT_CAD[0]     = '';
      $this->CD_STATUS[0]  = '';
      $this->CD_NIVEL[0]   = '';
      
    }
  }