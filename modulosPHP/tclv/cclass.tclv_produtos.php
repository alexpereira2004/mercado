
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

  class tclv_produtos {
  
    public $id;
    public $nm_produto;
    public $de_produto;
    public $cd_status;
    public $nu_cliques;
    public $nm_prod_rel;
    public $nm_pronuncia;
    public $id_tipo;
    public $iCdMsg;
    public $sMsg;
    public $sErro;

    public function getNm_produto($nm_produto) {
      return $this->nm_produto;
    }

    public function getDe_produto($de_produto) {
      return $this->de_produto;
    }

    public function getCd_status($cd_status) {
      return $this->cd_status;
    }

    public function getNu_cliques($nu_cliques) {
      return $this->nu_cliques;
    }

    public function getNm_prod_rel($nm_prod_rel) {
      return $this->nm_prod_rel;
    }

    public function getNm_pronuncia($nm_pronuncia) {
      return $this->nm_pronuncia;
    }

    public function getId_tipo($id_tipo) {
      return $this->id_tipo;
    }



    public function setNm_produto($nm_produto) {
      $this->nm_produto = $nm_produto;
    }

    public function setDe_produto($de_produto) {
      $this->de_produto = $de_produto;
    }

    public function setCd_status($cd_status) {
      $this->cd_status = $cd_status;
    }

    public function setNu_cliques($nu_cliques) {
      $this->nu_cliques = $nu_cliques;
    }

    public function setNm_prod_rel($nm_prod_rel) {
      $this->nm_prod_rel = $nm_prod_rel;
    }

    public function setNm_pronuncia($nm_pronuncia) {
      $this->nm_pronuncia = $nm_pronuncia;
    }

    public function setId_tipo($id_tipo) {
      $this->id_tipo = $id_tipo;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_produto, 
                        de_produto, 
                        cd_status, 
                        nu_cliques, 
                        nm_prod_rel, 
                        nm_pronuncia, 
                        id_tipo 
                   FROM tclv_produtos
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTclv_produtos = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]           = $aResultado['id']; 
        $this->NM_PRODUTO[]   = $aResultado['nm_produto']; 
        $this->DE_PRODUTO[]   = $aResultado['de_produto']; 
        $this->CD_STATUS[]    = $aResultado['cd_status']; 
        $this->NU_CLIQUES[]   = $aResultado['nu_cliques']; 
        $this->NM_PROD_REL[]  = $aResultado['nm_prod_rel']; 
        $this->NM_PRONUNCIA[] = $aResultado['nm_pronuncia']; 
        $this->ID_TIPO[]      = $aResultado['id_tipo']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tclv_produtos(
                             NM_PRODUTO, 
                             DE_PRODUTO, 
                             CD_STATUS, 
                             NU_CLIQUES, 
                             NM_PROD_REL, 
                             NM_PRONUNCIA, 
                             ID_TIPO 
)
      VALUES(
              '".$this->NM_PRODUTO[0]."', 
              '".$this->DE_PRODUTO[0]."', 
              '".$this->CD_STATUS[0]."', 
              '".$this->NU_CLIQUES[0]."', 
              '".$this->NM_PROD_REL[0]."', 
              '".$this->NM_PRONUNCIA[0]."', 
              '".$this->ID_TIPO[0]."' 
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
      $sQuery = "DELETE FROM tclv_produtos
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
      $sQuery = "UPDATE tclv_produtos
        SET
          nm_produto   = '".$this->NM_PRODUTO[0]."', 
          de_produto   = '".$this->DE_PRODUTO[0]."', 
          cd_status    = '".$this->CD_STATUS[0]."', 
          nu_cliques   = '".$this->NU_CLIQUES[0]."', 
          nm_prod_rel  = '".$this->NM_PROD_REL[0]."', 
          nm_pronuncia = '".$this->NM_PRONUNCIA[0]."', 
          id_tipo      = '".$this->ID_TIPO[0]."' 
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

      $this->ID[0]           = '';
      $this->NM_PRODUTO[0]   = '';
      $this->DE_PRODUTO[0]   = '';
      $this->CD_STATUS[0]    = '';
      $this->NU_CLIQUES[0]   = '';
      $this->NM_PROD_REL[0]  = '';
      $this->NM_PRONUNCIA[0] = '';
      $this->ID_TIPO[0]      = '';
      
    }
  }