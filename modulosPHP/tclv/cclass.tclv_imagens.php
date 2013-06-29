
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

  class tclv_imagens {
  
    public $id;
    public $tx_link;
    public $nm_imagem;
    public $cd_tipo;
    public $cd_status;
    public $cd_extensao;
    public $iCdMsg;
    public $sMsg;
    public $sErro;

    public function getTx_link($tx_link) {
      return $this->tx_link;
    }

    public function getNm_imagem($nm_imagem) {
      return $this->nm_imagem;
    }

    public function getCd_tipo($cd_tipo) {
      return $this->cd_tipo;
    }

    public function getCd_status($cd_status) {
      return $this->cd_status;
    }

    public function getCd_extensao($cd_extensao) {
      return $this->cd_extensao;
    }



    public function setTx_link($tx_link) {
      $this->tx_link = $tx_link;
    }

    public function setNm_imagem($nm_imagem) {
      $this->nm_imagem = $nm_imagem;
    }

    public function setCd_tipo($cd_tipo) {
      $this->cd_tipo = $cd_tipo;
    }

    public function setCd_status($cd_status) {
      $this->cd_status = $cd_status;
    }

    public function setCd_extensao($cd_extensao) {
      $this->cd_extensao = $cd_extensao;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        tx_link, 
                        nm_imagem, 
                        cd_tipo, 
                        cd_status, 
                        cd_extensao 
                   FROM tclv_imagens
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTclv_imagens = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]          = $aResultado['id']; 
        $this->TX_LINK[]     = $aResultado['tx_link']; 
        $this->NM_IMAGEM[]   = $aResultado['nm_imagem']; 
        $this->CD_TIPO[]     = $aResultado['cd_tipo']; 
        $this->CD_STATUS[]   = $aResultado['cd_status']; 
        $this->CD_EXTENSAO[] = $aResultado['cd_extensao']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tclv_imagens(
                             TX_LINK, 
                             NM_IMAGEM, 
                             CD_TIPO, 
                             CD_STATUS, 
                             CD_EXTENSAO 
)
      VALUES(
              '".$this->TX_LINK[0]."', 
              '".$this->NM_IMAGEM[0]."', 
              '".$this->CD_TIPO[0]."', 
              '".$this->CD_STATUS[0]."', 
              '".$this->CD_EXTENSAO[0]."' 
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
      $sQuery = "DELETE FROM tclv_imagens
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
      $sQuery = "UPDATE tclv_imagens
        SET
          tx_link     = '".$this->TX_LINK[0]."', 
          nm_imagem   = '".$this->NM_IMAGEM[0]."', 
          cd_tipo     = '".$this->CD_TIPO[0]."', 
          cd_status   = '".$this->CD_STATUS[0]."', 
          cd_extensao = '".$this->CD_EXTENSAO[0]."' 
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
      $this->TX_LINK[0]     = '';
      $this->NM_IMAGEM[0]   = '';
      $this->CD_TIPO[0]     = '';
      $this->CD_STATUS[0]   = '';
      $this->CD_EXTENSAO[0] = '';
      
    }
  }