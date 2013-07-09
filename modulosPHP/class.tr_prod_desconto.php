
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       06-07-2013
   **/

  class tr_prod_desconto {
  
    public    $id_prod;
    public    $id_desconto;
    public    $iCdMsg;
    public    $sMsg;
    public    $aMsg;
    public    $sErro;
    public    $sBackpage;
    protected $DB_LINK;
    protected $oUtil;

    public function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
      $this->oUtil   = new wTools();
    }

    public function salvar() {

      try {
        $aValidar = array ( 1 => array('Desconto' , $_POST['CMPprod-desconto-desconto'], 'int(10)', true),
                            );

        // Validação de preenchimento
        if ($this->oUtil->valida_Preenchimento($aValidar) !== true) {
          $this->aMsg = $this->oUtil->aMsg;
          throw new excecoes(25);
        }

        // Editar conteúdo
        if ($_POST['sAcao'] == 'editar') {
          $this->editar($this->ID[0]);

        } elseif ($_POST['sAcao'] == 'inserir') {
          $this->inserir();
          $this->oUtil->redirFRM($this->sBackpage, $this->aMsg);
          header('location:'.$this->sBackpage);
          exit;
        } else {

          // Tipo de ação não é válido
          throw new excecoes(20, $this->oUtil->anti_sql_injection($_POST['CMPpgAtual']));
        }

      } catch (excecoes $e) {
        $e->bReturnMsg = false;
        $e->getErrorByCode();
        if (is_array($e->aMsg)) {
          $this->aMsg = $e->aMsg;
        }
        return false;
      }
      return true;
    }

      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id_prod,
                        id_desconto 
                   FROM tr_prod_desconto
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTr_prod_desconto = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID_PROD[]     = $aResultado['id_prod']; 
        $this->ID_DESCONTO[] = $aResultado['id_desconto']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tr_prod_desconto(
                             ID_PROD,
                             ID_DESCONTO )
      VALUES(
              '".$this->ID_PROD[0]."', 
              '".$this->ID_DESCONTO[0]."' )";
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
      $sQuery = "DELETE FROM tr_prod_desconto ".$sFiltro;
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
      $sQuery = "UPDATE tr_prod_desconto
        SET
          id_desconto = '".$this->ID_DESCONTO[0]."' ,
          id_prod     = '".$this->ID_PROD[0]."' 
          WHERE id_prod = ".$iId;
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

      $this->ID_PROD[0]     = (isset ($_POST['CMPprod-desconto-prod'])     ? $_POST['CMPprod-desconto-prod']     : '');
      $this->ID_DESCONTO[0] = (isset ($_POST['CMPprod-desconto-desconto']) ? $_POST['CMPprod-desconto-desconto'] : '');
      
    }
  }