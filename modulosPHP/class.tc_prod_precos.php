
<?php
  /**
   * Descricao
   *
   * @package    Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       06-04-2012
   **/

  class tc_prod_precos {
  
    public $id;
    public $vl_adicionais;
    public $vl_taxas;
    public $vl_custo;
    public $pc_margem;
    public $vl_final;
    public $id_prod;
    public $cd_visivel;
    public    $iCdMsg;
    public    $sMsg;
    public    $aMsg;
    public    $sErro;
    protected $DB_LINK;

    public function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
    }


    public function getVl_adicionais($vl_adicionais) {
      return $this->vl_adicionais;
    }

    public function getVl_taxas($vl_taxas) {
      return $this->vl_taxas;
    }

    public function getVl_custo($vl_custo) {
      return $this->vl_custo;
    }

    public function getPc_margem($pc_margem) {
      return $this->pc_margem;
    }

    public function getVl_final($vl_final) {
      return $this->vl_final;
    }

    public function getId_prod($id_prod) {
      return $this->id_prod;
    }

    public function getCd_visivel($cd_visivel) {
      return $this->cd_visivel;
    }



    public function setVl_adicionais($vl_adicionais) {
      $this->vl_adicionais = $vl_adicionais;
    }

    public function setVl_taxas($vl_taxas) {
      $this->vl_taxas = $vl_taxas;
    }

    public function setVl_custo($vl_custo) {
      $this->vl_custo = $vl_custo;
    }

    public function setPc_margem($pc_margem) {
      $this->pc_margem = $pc_margem;
    }

    public function setVl_final($vl_final) {
      $this->vl_final = $vl_final;
    }

    public function setId_prod($id_prod) {
      $this->id_prod = $id_prod;
    }

    public function setCd_visivel($cd_visivel) {
      $this->cd_visivel = $cd_visivel;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        vl_adicionais, 
                        vl_taxas, 
                        vl_custo, 
                        pc_margem, 
                        vl_final, 
                        id_prod, 
                        cd_visivel 
                   FROM tc_prod_precos
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_prod_precos = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]            = $aResultado['id']; 
        $this->VL_ADICIONAIS[] = $aResultado['vl_adicionais']; 
        $this->VL_TAXAS[]      = $aResultado['vl_taxas']; 
        $this->VL_CUSTO[]      = $aResultado['vl_custo']; 
        $this->PC_MARGEM[]     = $aResultado['pc_margem']; 
        $this->VL_FINAL[]      = $aResultado['vl_final']; 
        $this->ID_PROD[]       = $aResultado['id_prod']; 
        $this->CD_VISIVEL[]    = $aResultado['cd_visivel']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_prod_precos(
                             VL_ADICIONAIS, 
                             VL_TAXAS, 
                             VL_CUSTO, 
                             PC_MARGEM, 
                             VL_FINAL, 
                             ID_PROD, 
                             CD_VISIVEL 
)
      VALUES(
              '".$this->VL_ADICIONAIS[0]."', 
              '".$this->VL_TAXAS[0]."', 
              '".$this->VL_CUSTO[0]."', 
              '".$this->PC_MARGEM[0]."', 
              '".$this->VL_FINAL[0]."', 
              '".$this->ID_PROD[0]."', 
              '".$this->CD_VISIVEL[0]."' )";
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
      $sQuery = "DELETE FROM tc_prod_precos ".$sFiltro;

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

    public function editar($sFiltro = '') {
      $sQuery = "UPDATE tc_prod_precos
        SET
          vl_adicionais = '".$this->VL_ADICIONAIS[0]."', 
          vl_taxas      = '".$this->VL_TAXAS[0]."', 
          vl_custo      = '".$this->VL_CUSTO[0]."', 
          pc_margem     = '".$this->PC_MARGEM[0]."', 
          vl_final      = '".$this->VL_FINAL[0]."', 
          id_prod       = '".$this->ID_PROD[0]."', 
          cd_visivel    = '".$this->CD_VISIVEL[0]."' 
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

      $this->ID[0]            = '';
      $this->VL_ADICIONAIS[0] = '';
      $this->VL_TAXAS[0]      = '';
      $this->VL_CUSTO[0]      = '';
      $this->PC_MARGEM[0]     = '';
      $this->VL_FINAL[0]      = '';
      $this->ID_PROD[0]       = '';
      $this->CD_VISIVEL[0]    = '';
      
    }
  }