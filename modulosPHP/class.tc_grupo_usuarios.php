
<?php
  /**
   * Descricao
   *
   * @package    Site Lunacom
   * @author     Alex Lunardelli <alex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       21-06-2012
   **/

  class tc_grupo_usuarios {
  
    public    $id;
    public    $nm_grupo;
    public    $id_usu_lider;
    public    $id_usu_criador;
    public    $cd_sit;
    public    $iCdMsg;
    public    $sMsg;
    public    $aMsg;
    public    $sErro;
    protected $DB_LINK;

    public function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
    }


    public function getNm_grupo($nm_grupo) {
      return $this->nm_grupo;
    }

    public function getId_usu_lider($id_usu_lider) {
      return $this->id_usu_lider;
    }

    public function getId_usu_criador($id_usu_criador) {
      return $this->id_usu_criador;
    }

    public function getCd_sit($cd_sit) {
      return $this->cd_sit;
    }



    public function setNm_grupo($nm_grupo) {
      $this->nm_grupo = $nm_grupo;
    }

    public function setId_usu_lider($id_usu_lider) {
      $this->id_usu_lider = $id_usu_lider;
    }

    public function setId_usu_criador($id_usu_criador) {
      $this->id_usu_criador = $id_usu_criador;
    }

    public function setCd_sit($cd_sit) {
      $this->cd_sit = $cd_sit;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_grupo, 
                        id_usu_lider, 
                        id_usu_criador, 
                        cd_sit 
                   FROM tc_grupo_usuarios
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_grupo_usuarios = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]             = $aResultado['id']; 
        $this->NM_GRUPO[]       = $aResultado['nm_grupo']; 
        $this->ID_USU_LIDER[]   = $aResultado['id_usu_lider']; 
        $this->ID_USU_CRIADOR[] = $aResultado['id_usu_criador']; 
        $this->CD_SIT[]         = $aResultado['cd_sit']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_grupo_usuarios(
                             NM_GRUPO, 
                             ID_USU_LIDER, 
                             ID_USU_CRIADOR, 
                             CD_SIT 
)
      VALUES(
              '".$this->NM_GRUPO[0]."', 
              '".$this->ID_USU_LIDER[0]."', 
              '".$this->ID_USU_CRIADOR[0]."', 
              '".$this->CD_SIT[0]."' 
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
      $sQuery = "DELETE FROM tc_grupo_usuarios
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
      $sQuery = "UPDATE tc_grupo_usuarios
        SET
          nm_grupo       = '".$this->NM_GRUPO[0]."', 
          id_usu_lider   = '".$this->ID_USU_LIDER[0]."'
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

      $this->ID[0]             = '';
      $this->NM_GRUPO[0]       = '';
      $this->ID_USU_LIDER[0]   = '';
      $this->ID_USU_CRIADOR[0] = '';
      $this->CD_SIT[0]         = '';
      
    }
  }