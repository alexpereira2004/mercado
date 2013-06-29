
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

  class tclv_tags {
  
    public $id;
    public $nm_tag;
    public $tx_sound;
    public $nu_visualizacoes;
    public $cd_tipo;
    public $iCdMsg;
    public $sMsg;
    public $sErro;

    public function getNm_tag($nm_tag) {
      return $this->nm_tag;
    }

    public function getTx_sound($tx_sound) {
      return $this->tx_sound;
    }

    public function getNu_visualizacoes($nu_visualizacoes) {
      return $this->nu_visualizacoes;
    }

    public function getCd_tipo($cd_tipo) {
      return $this->cd_tipo;
    }



    public function setNm_tag($nm_tag) {
      $this->nm_tag = $nm_tag;
    }

    public function setTx_sound($tx_sound) {
      $this->tx_sound = $tx_sound;
    }

    public function setNu_visualizacoes($nu_visualizacoes) {
      $this->nu_visualizacoes = $nu_visualizacoes;
    }

    public function setCd_tipo($cd_tipo) {
      $this->cd_tipo = $cd_tipo;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_tag, 
                        tx_sound, 
                        nu_visualizacoes, 
                        cd_tipo 
                   FROM tclv_tags
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTclv_tags = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]               = $aResultado['id']; 
        $this->NM_TAG[]           = $aResultado['nm_tag']; 
        $this->TX_SOUND[]         = $aResultado['tx_sound']; 
        $this->NU_VISUALIZACOES[] = $aResultado['nu_visualizacoes']; 
        $this->CD_TIPO[]          = $aResultado['cd_tipo']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tclv_tags(
                             NM_TAG, 
                             TX_SOUND, 
                             NU_VISUALIZACOES, 
                             CD_TIPO 
)
      VALUES(
              '".$this->NM_TAG[0]."', 
              '".$this->TX_SOUND[0]."', 
              '".$this->NU_VISUALIZACOES[0]."', 
              '".$this->CD_TIPO[0]."' 
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
      $sQuery = "DELETE FROM tclv_tags
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
      $sQuery = "UPDATE tclv_tags
        SET
          nm_tag           = '".$this->NM_TAG[0]."', 
          tx_sound         = '".$this->TX_SOUND[0]."', 
          nu_visualizacoes = '".$this->NU_VISUALIZACOES[0]."', 
          cd_tipo          = '".$this->CD_TIPO[0]."' 
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

      $this->ID[0]               = '';
      $this->NM_TAG[0]           = '';
      $this->TX_SOUND[0]         = '';
      $this->NU_VISUALIZACOES[0] = '';
      $this->CD_TIPO[0]          = '';
      
    }
  }