
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

  class tclv_perfis {
  
    public $id;
    public $nm_perfil;
    public $cd_acc_menu;
    public $iCdMsg;
    public $sMsg;
    public $sErro;

    public function getNm_perfil($nm_perfil) {
      return $this->nm_perfil;
    }

    public function getCd_acc_menu($cd_acc_menu) {
      return $this->cd_acc_menu;
    }



    public function setNm_perfil($nm_perfil) {
      $this->nm_perfil = $nm_perfil;
    }

    public function setCd_acc_menu($cd_acc_menu) {
      $this->cd_acc_menu = $cd_acc_menu;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_perfil, 
                        cd_acc_menu 
                   FROM tclv_perfis
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTclv_perfis = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]          = $aResultado['id']; 
        $this->NM_PERFIL[]   = $aResultado['nm_perfil']; 
        $this->CD_ACC_MENU[] = $aResultado['cd_acc_menu']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tclv_perfis(
                             NM_PERFIL, 
                             CD_ACC_MENU 
)
      VALUES(
              '".$this->NM_PERFIL[0]."', 
              '".$this->CD_ACC_MENU[0]."' 
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
      $sQuery = "DELETE FROM tclv_perfis
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
      $sQuery = "UPDATE tclv_perfis
        SET
          nm_perfil   = '".$this->NM_PERFIL[0]."', 
          cd_acc_menu = '".$this->CD_ACC_MENU[0]."' 
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

      $this->ID[0]          = '';
      $this->NM_PERFIL[0]   = '';
      $this->CD_ACC_MENU[0] = '';
      
    }
  }