
<?php
  /**
   * Descricao
   *
   * @package    Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.bralex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       06-04-2012
   **/

  class tc_produtos {
  
    public $id;
    public $nm_produto;
    public $cd_produto;
    public $de_curta;
    public $de_longa;
    public $cd_status;
    public $nu_cliques;
    public $nm_pronuncia;
    public $id_tipo;
    public $id_fabricante;
    public $id_categoria;
    public    $iCdMsg;
    public    $sMsg;
    public    $aMsg;
    public    $sErro;
    protected $DB_LINK;

    public function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_produto, 
                        cd_produto, 
                        de_curta, 
                        de_longa, 
                        cd_status, 
                        nu_cliques, 
                        nm_pronuncia, 
                        id_tipo, 
                        id_fabricante, 
                        id_categoria,
                        tx_link
                   FROM tc_produtos
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_produtos = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]            = $aResultado['id']; 
        $this->NM_PRODUTO[]    = $aResultado['nm_produto']; 
        $this->CD_PRODUTO[]    = $aResultado['cd_produto']; 
        $this->DE_CURTA[]      = $aResultado['de_curta']; 
        $this->DE_LONGA[]      = $aResultado['de_longa']; 
        $this->CD_STATUS[]     = $aResultado['cd_status']; 
        $this->NU_CLIQUES[]    = $aResultado['nu_cliques']; 
        $this->NM_PRONUNCIA[]  = $aResultado['nm_pronuncia']; 
        $this->ID_TIPO[]       = $aResultado['id_tipo']; 
        $this->ID_FABRICANTE[] = $aResultado['id_fabricante']; 
        $this->ID_CATEGORIA[]  = $aResultado['id_categoria']; 
        $this->TX_LINK[]       = $aResultado['tx_link']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_produtos(
                             NM_PRODUTO, 
                             CD_PRODUTO, 
                             DE_CURTA, 
                             DE_LONGA, 
                             CD_STATUS, 
                             NU_CLIQUES, 
                             NM_PRONUNCIA, 
                             ID_TIPO, 
                             ID_FABRICANTE, 
                             ID_CATEGORIA,
                             TX_LINK
)
      VALUES(
              '".$this->NM_PRODUTO[0]."', 
              '".$this->CD_PRODUTO[0]."', 
              '".$this->DE_CURTA[0]."', 
              '".$this->DE_LONGA[0]."', 
              '".$this->CD_STATUS[0]."', 
              '".$this->NU_CLIQUES[0]."', 
              '".$this->NM_PRONUNCIA[0]."', 
              '".$this->ID_TIPO[0]."', 
              '".$this->ID_FABRICANTE[0]."', 
              '".$this->ID_CATEGORIA[0]."',
              '".$this->TX_LINK[0]."'
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
      $sQuery = "DELETE FROM tc_produtos
                       ".$sFiltro;
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
      $sQuery = "UPDATE tc_produtos
        SET
          nm_produto    = '".$this->NM_PRODUTO[0]."', 
          cd_produto    = '".$this->CD_PRODUTO[0]."', 
          de_curta      = '".$this->DE_CURTA[0]."', 
          de_longa      = '".$this->DE_LONGA[0]."', 
          cd_status     = '".$this->CD_STATUS[0]."', 
          nu_cliques    = '".$this->NU_CLIQUES[0]."', 
          nm_pronuncia  = '".$this->NM_PRONUNCIA[0]."', 
          id_tipo       = '".$this->ID_TIPO[0]."', 
          id_fabricante = '".$this->ID_FABRICANTE[0]."', 
          id_categoria  = '".$this->ID_CATEGORIA[0]."',
          tx_link       = '".$this->TX_LINK[0]."'
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

      $this->ID[0]            = '';
      $this->NM_PRODUTO[0]    = '';
      $this->CD_PRODUTO[0]    = '';
      $this->DE_CURTA[0]      = '';
      $this->DE_LONGA[0]      = '';
      $this->CD_STATUS[0]     = '';
      $this->NU_CLIQUES[0]    = '';
      $this->NM_PRONUNCIA[0]  = '';
      $this->ID_TIPO[0]       = '';
      $this->ID_FABRICANTE[0] = '';
      $this->ID_CATEGORIA[0]  = '';
      $this->TX_LINK[0]  = '';
      
    }
  }