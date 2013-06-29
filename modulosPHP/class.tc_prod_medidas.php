
<?php
  /**
   * Descricao
   *
   * @package    Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       06-04-2012
   **/

  class tc_prod_medidas {
  
    public $id;
    public $nu_x;
    public $nu_y;
    public $nu_z;
    public $nu_peso;
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


    public function getNu_x($nu_x) {
      return $this->nu_x;
    }

    public function getNu_y($nu_y) {
      return $this->nu_y;
    }

    public function getNu_z($nu_z) {
      return $this->nu_z;
    }

    public function getNu_peso($nu_peso) {
      return $this->nu_peso;
    }

    public function getId_prod($id_prod) {
      return $this->id_prod;
    }



    public function setNu_x($nu_x) {
      $this->nu_x = $nu_x;
    }

    public function setNu_y($nu_y) {
      $this->nu_y = $nu_y;
    }

    public function setNu_z($nu_z) {
      $this->nu_z = $nu_z;
    }

    public function setNu_peso($nu_peso) {
      $this->nu_peso = $nu_peso;
    }

    public function setId_prod($id_prod) {
      $this->id_prod = $id_prod;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nu_x, 
                        nu_y, 
                        nu_z, 
                        nu_peso, 
                        id_prod 
                   FROM tc_prod_medidas
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_prod_medidas = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]      = $aResultado['id']; 
        $this->NU_X[]    = $aResultado['nu_x']; 
        $this->NU_Y[]    = $aResultado['nu_y']; 
        $this->NU_Z[]    = $aResultado['nu_z']; 
        $this->NU_PESO[] = $aResultado['nu_peso']; 
        $this->ID_PROD[] = $aResultado['id_prod']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_prod_medidas(
                             NU_X, 
                             NU_Y, 
                             NU_Z, 
                             NU_PESO, 
                             ID_PROD 
)
      VALUES(
              '".$this->NU_X[0]."', 
              '".$this->NU_Y[0]."', 
              '".$this->NU_Z[0]."', 
              '".$this->NU_PESO[0]."', 
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
      $sQuery = "DELETE FROM tc_prod_medidas ".$sFiltro;
  
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
      $sQuery = "UPDATE tc_prod_medidas
        SET
          nu_x    = '".$this->NU_X[0]."', 
          nu_y    = '".$this->NU_Y[0]."', 
          nu_z    = '".$this->NU_Z[0]."', 
          nu_peso = '".$this->NU_PESO[0]."', 
          id_prod = '".$this->ID_PROD[0]."' 
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

      $this->ID[0]      = '';
      $this->NU_X[0]    = '';
      $this->NU_Y[0]    = '';
      $this->NU_Z[0]    = '';
      $this->NU_PESO[0] = '';
      $this->ID_PROD[0] = '';
      
    }
  }