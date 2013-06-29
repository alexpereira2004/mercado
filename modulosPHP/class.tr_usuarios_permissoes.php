
<?php
  /**
   * Descricao
   *
   * @package    Site Lunacom
   * @author     Alex Lunardelli <alex@lunacom.com.bralex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       23-06-2012
   **/

  class tr_usuarios_permissoes {
  
    public    $id_permissao;
    public    $id_usuario;
    public    $de_permissao;
    public    $cd_secao;
    public    $cd_inserir;
    public    $cd_remover;
    public    $cd_editar;
    public    $cd_acessar;
    public    $cd_visualizar;
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

    public function getDe_permissao($de_permissao) {
      return $this->de_permissao;
    }

    public function getCd_secao($cd_secao) {
      return $this->cd_secao;
    }

    public function getCd_inserir($cd_inserir) {
      return $this->cd_inserir;
    }

    public function getCd_remover($cd_remover) {
      return $this->cd_remover;
    }

    public function getCd_editar($cd_editar) {
      return $this->cd_editar;
    }

    public function getCd_acessar($cd_acessar) {
      return $this->cd_acessar;
    }

    public function getCd_visualizar($cd_visualizar) {
      return $this->cd_visualizar;
    }



    public function setId_usuario($id_usuario) {
      $this->id_usuario = $id_usuario;
    }

    public function setDe_permissao($de_permissao) {
      $this->de_permissao = $de_permissao;
    }

    public function setCd_secao($cd_secao) {
      $this->cd_secao = $cd_secao;
    }

    public function setCd_inserir($cd_inserir) {
      $this->cd_inserir = $cd_inserir;
    }

    public function setCd_remover($cd_remover) {
      $this->cd_remover = $cd_remover;
    }

    public function setCd_editar($cd_editar) {
      $this->cd_editar = $cd_editar;
    }

    public function setCd_acessar($cd_acessar) {
      $this->cd_acessar = $cd_acessar;
    }

    public function setCd_visualizar($cd_visualizar) {
      $this->cd_visualizar = $cd_visualizar;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id_permissao,
                        id_usuario, 
                        de_permissao, 
                        cd_secao, 
                        cd_inserir, 
                        cd_remover, 
                        cd_editar, 
                        cd_acessar, 
                        cd_visualizar 
                   FROM tr_usuarios_permissoes
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTr_usuarios_permissoes = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID_PERMISSAO[]  = $aResultado['id_permissao']; 
        $this->ID_USUARIO[]    = $aResultado['id_usuario']; 
        $this->DE_PERMISSAO[]  = $aResultado['de_permissao']; 
        $this->CD_SECAO[]      = $aResultado['cd_secao']; 
        $this->CD_INSERIR[]    = $aResultado['cd_inserir']; 
        $this->CD_REMOVER[]    = $aResultado['cd_remover']; 
        $this->CD_EDITAR[]     = $aResultado['cd_editar']; 
        $this->CD_ACESSAR[]    = $aResultado['cd_acessar']; 
        $this->CD_VISUALIZAR[] = $aResultado['cd_visualizar']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tr_usuarios_permissoes(
                             ID_USUARIO, 
                             ID_PERMISSAO,
                             CD_INSERIR, 
                             CD_REMOVER, 
                             CD_EDITAR, 
                             CD_ACESSAR, 
                             CD_VISUALIZAR 
)
      VALUES(
              '".$this->ID_USUARIO[0]."',
              '".$this->ID_PERMISSAO[0]."',
              '".$this->CD_INSERIR[0]."', 
              '".$this->CD_REMOVER[0]."', 
              '".$this->CD_EDITAR[0]."', 
              '".$this->CD_ACESSAR[0]."', 
              '".$this->CD_VISUALIZAR[0]."' )";
      
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

    public function remover($sWhere = '') {
      $sQuery = "DELETE FROM tr_usuarios_permissoes ".$sWhere;
      
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
      $sQuery = "UPDATE tr_usuarios_permissoes
        SET
          id_usuario    = '".$this->ID_USUARIO[0]."', 
          de_permissao  = '".$this->DE_PERMISSAO[0]."', 
          cd_secao      = '".$this->CD_SECAO[0]."', 
          cd_inserir    = '".$this->CD_INSERIR[0]."', 
          cd_remover    = '".$this->CD_REMOVER[0]."', 
          cd_editar     = '".$this->CD_EDITAR[0]."', 
          cd_acessar    = '".$this->CD_ACESSAR[0]."', 
          cd_visualizar = '".$this->CD_VISUALIZAR[0]."' 
          WHERE id_permissao = ".$iId;
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

      $this->ID_PERMISSAO[0]  = '';
      $this->ID_USUARIO[0]    = '';
      $this->DE_PERMISSAO[0]  = '';
      $this->CD_SECAO[0]      = '';
      $this->CD_INSERIR[0]    = '';
      $this->CD_REMOVER[0]    = '';
      $this->CD_EDITAR[0]     = '';
      $this->CD_ACESSAR[0]    = '';
      $this->CD_VISUALIZAR[0] = '';
      
    }
  }