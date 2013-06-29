
<?php
  /**
   * Descricao
   *
   * @package    Site Lunacom
   * @author     Alex Lunardelli <alex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       21-06-2012
   **/

  class tr_usuarios_grupo {
  
    public    $id;
    public    $id_usuario;
    public    $id_grupo;
    public    $iCdMsg;
    public    $sMsg;
    public    $aMsg;
    public    $sErro;
    protected $DB_LINK;

    public function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
    }


    public function getId_usuario($id_usuario) {
      return $this->id_usuario;
    }

    public function getId_grupo($id_grupo) {
      return $this->id_grupo;
    }



    public function setId_usuario($id_usuario) {
      $this->id_usuario = $id_usuario;
    }

    public function setId_grupo($id_grupo) {
      $this->id_grupo = $id_grupo;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT 
                        id_usuario, 
                        id_grupo 
                   FROM tr_usuarios_grupo
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar dd a listagem: ' . mysql_error().$sQuery);
        return false;
      }

      //$this->iLinhasTr_usuarios_grupo = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        //$this->ID[]         = $aResultado['id'];
        $this->ID_USUARIO[] = $aResultado['id_usuario']; 
        $this->ID_GRUPO[]   = $aResultado['id_grupo']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tr_usuarios_grupo(
                             ID_USUARIO, 
                             ID_GRUPO 
)
      VALUES(
              '".$this->ID_USUARIO[0]."', 
              '".$this->ID_GRUPO[0]."' 
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

    public function remover($sFiltro = '') {
      $sQuery = "DELETE FROM tr_usuarios_grupo ".$sFiltro;
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
      $sQuery = "UPDATE tr_usuarios_grupo
        SET
          id_usuario = '".$this->ID_USUARIO[0]."', 
          id_grupo   = '".$this->ID_GRUPO[0]."' 
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

      $this->ID[0]         = '';
      $this->ID_USUARIO[0] = '';
      $this->ID_GRUPO[0]   = '';
      
    }
  }