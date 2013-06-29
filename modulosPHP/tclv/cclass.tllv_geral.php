
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

  class tllv_geral {
  
    public $id;
    public $nm_log;
    public $tx_log;
    public $cd_log;
    public $cd_acao;
    public $tx_ip;
    public $tx_trace;
    public $id_usu;
    public $dt_cri;
    public $hr_cri;
    public $iCdMsg;
    public $sMsg;
    public $sErro;

    public function getNm_log($nm_log) {
      return $this->nm_log;
    }

    public function getTx_log($tx_log) {
      return $this->tx_log;
    }

    public function getCd_log($cd_log) {
      return $this->cd_log;
    }

    public function getCd_acao($cd_acao) {
      return $this->cd_acao;
    }

    public function getTx_ip($tx_ip) {
      return $this->tx_ip;
    }

    public function getTx_trace($tx_trace) {
      return $this->tx_trace;
    }

    public function getId_usu($id_usu) {
      return $this->id_usu;
    }

    public function getDt_cri($dt_cri) {
      return $this->dt_cri;
    }

    public function getHr_cri($hr_cri) {
      return $this->hr_cri;
    }



    public function setNm_log($nm_log) {
      $this->nm_log = $nm_log;
    }

    public function setTx_log($tx_log) {
      $this->tx_log = $tx_log;
    }

    public function setCd_log($cd_log) {
      $this->cd_log = $cd_log;
    }

    public function setCd_acao($cd_acao) {
      $this->cd_acao = $cd_acao;
    }

    public function setTx_ip($tx_ip) {
      $this->tx_ip = $tx_ip;
    }

    public function setTx_trace($tx_trace) {
      $this->tx_trace = $tx_trace;
    }

    public function setId_usu($id_usu) {
      $this->id_usu = $id_usu;
    }

    public function setDt_cri($dt_cri) {
      $this->dt_cri = $dt_cri;
    }

    public function setHr_cri($hr_cri) {
      $this->hr_cri = $hr_cri;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_log, 
                        tx_log, 
                        cd_log, 
                        cd_acao, 
                        tx_ip, 
                        tx_trace, 
                        id_usu, 
                        date_format(dt_cri, "%d/%m/%Y") AS dt_cri, 
                        date_format(hr_cri, "%H:%i") AS hr_cri 
                   FROM tllv_geral
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTllv_geral = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]       = $aResultado['id']; 
        $this->NM_LOG[]   = $aResultado['nm_log']; 
        $this->TX_LOG[]   = $aResultado['tx_log']; 
        $this->CD_LOG[]   = $aResultado['cd_log']; 
        $this->CD_ACAO[]  = $aResultado['cd_acao']; 
        $this->TX_IP[]    = $aResultado['tx_ip']; 
        $this->TX_TRACE[] = $aResultado['tx_trace']; 
        $this->ID_USU[]   = $aResultado['id_usu']; 
        $this->DT_CRI[]   = $aResultado['dt_cri']; 
        $this->HR_CRI[]   = $aResultado['hr_cri']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tllv_geral(
                             NM_LOG, 
                             TX_LOG, 
                             CD_LOG, 
                             CD_ACAO, 
                             TX_IP, 
                             TX_TRACE, 
                             ID_USU, 
                             DT_CRI, 
                             HR_CRI 
)
      VALUES(
              '".$this->NM_LOG[0]."', 
              '".$this->TX_LOG[0]."', 
              '".$this->CD_LOG[0]."', 
              '".$this->CD_ACAO[0]."', 
              '".$this->TX_IP[0]."', 
              '".$this->TX_TRACE[0]."', 
              '".$this->ID_USU[0]."', 
              '".$this->DT_CRI[0]."', 
              '".$this->HR_CRI[0]."' 
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
      $sQuery = "DELETE FROM tllv_geral
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
      $sQuery = "UPDATE tllv_geral
        SET
          nm_log   = '".$this->NM_LOG[0]."', 
          tx_log   = '".$this->TX_LOG[0]."', 
          cd_log   = '".$this->CD_LOG[0]."', 
          cd_acao  = '".$this->CD_ACAO[0]."', 
          tx_ip    = '".$this->TX_IP[0]."', 
          tx_trace = '".$this->TX_TRACE[0]."', 
          id_usu   = '".$this->ID_USU[0]."', 
          dt_cri   = '".$this->DT_CRI[0]."', 
          hr_cri   = '".$this->HR_CRI[0]."' 
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

      $this->ID[0]       = '';
      $this->NM_LOG[0]   = '';
      $this->TX_LOG[0]   = '';
      $this->CD_LOG[0]   = '';
      $this->CD_ACAO[0]  = '';
      $this->TX_IP[0]    = '';
      $this->TX_TRACE[0] = '';
      $this->ID_USU[0]   = '';
      $this->DT_CRI[0]   = '';
      $this->HR_CRI[0]   = '';
      
    }
  }