
<?php
  /**
   * Descricao
   *
   * @package    Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       06-04-2012
   **/

  class tc_prod_estoque {
  
    public $id;
    public $nu_atual;
    public $nu_minimo;
    public $tx_falta_prod;
    public $cd_visivel_em_falta;
    public $id_prod;
    public    $iCdMsg;
    public    $sMsg;
    public    $aMsg;
    public    $sErro;
    protected $DB_LINK;

    public function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
    }


    public function getNu_atual($nu_atual) {
      return $this->nu_atual;
    }

    public function getNu_minimo($nu_minimo) {
      return $this->nu_minimo;
    }

    public function getTx_falta_prod($tx_falta_prod) {
      return $this->tx_falta_prod;
    }

    public function getCd_visivel_em_falta($cd_visivel_em_falta) {
      return $this->cd_visivel_em_falta;
    }

    public function getId_prod($id_prod) {
      return $this->id_prod;
    }



    public function setNu_atual($nu_atual) {
      $this->nu_atual = $nu_atual;
    }

    public function setNu_minimo($nu_minimo) {
      $this->nu_minimo = $nu_minimo;
    }

    public function setTx_falta_prod($tx_falta_prod) {
      $this->tx_falta_prod = $tx_falta_prod;
    }

    public function setCd_visivel_em_falta($cd_visivel_em_falta) {
      $this->cd_visivel_em_falta = $cd_visivel_em_falta;
    }

    public function setId_prod($id_prod) {
      $this->id_prod = $id_prod;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nu_atual, 
                        nu_minimo, 
                        tx_falta_prod, 
                        cd_visivel_em_falta, 
                        id_prod 
                   FROM tc_prod_estoque
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_prod_estoque = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]                  = $aResultado['id']; 
        $this->NU_ATUAL[]            = $aResultado['nu_atual']; 
        $this->NU_MINIMO[]           = $aResultado['nu_minimo']; 
        $this->TX_FALTA_PROD[]       = $aResultado['tx_falta_prod']; 
        $this->CD_VISIVEL_EM_FALTA[] = $aResultado['cd_visivel_em_falta']; 
        $this->ID_PROD[]             = $aResultado['id_prod']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_prod_estoque(
                             NU_ATUAL, 
                             NU_MINIMO, 
                             TX_FALTA_PROD, 
                             CD_VISIVEL_EM_FALTA, 
                             ID_PROD 
)
      VALUES(
              '".$this->NU_ATUAL[0]."', 
              '".$this->NU_MINIMO[0]."', 
              '".$this->TX_FALTA_PROD[0]."', 
              '".$this->CD_VISIVEL_EM_FALTA[0]."', 
              '".$this->ID_PROD[0]."' 
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

    public function remover($sFiltro) {
      $sQuery = "DELETE FROM tc_prod_estoque ".$sFiltro;

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

    public function editar($sFiltro) {
      $sQuery = "UPDATE tc_prod_estoque
        SET
          nu_atual            = '".$this->NU_ATUAL[0]."', 
          nu_minimo           = '".$this->NU_MINIMO[0]."', 
          tx_falta_prod       = '".$this->TX_FALTA_PROD[0]."', 
          cd_visivel_em_falta = '".$this->CD_VISIVEL_EM_FALTA[0]."', 
          id_prod             = '".$this->ID_PROD[0]."' 
          ".$sFiltro;
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

      $this->ID[0]                  = '';
      $this->NU_ATUAL[0]            = '';
      $this->NU_MINIMO[0]           = '';
      $this->TX_FALTA_PROD[0]       = '';
      $this->CD_VISIVEL_EM_FALTA[0] = '';
      $this->ID_PROD[0]             = '';
      
    }
  }