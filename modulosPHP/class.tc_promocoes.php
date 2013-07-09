
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       06-07-2013
   **/

  class tc_promocoes {
  
    public    $id;
    public    $nm_promocao;
    public    $de_promocao;
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
        $aValidar = array ( 1 => array('Promocao' , $_POST['CMPpromocoes-promocao'], 'varchar', true),
                            2 => array('Promocao' , $_POST['CMPpromocoes-desc'], 'text', true),
                            3 => array('Desconto' , $_POST['CMPpromocoes-desconto'], 'int', true),
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
//          $this->oUtil->redirFRM($this->sBackpage, $this->aMsg);
//          header('location:'.$this->sBackpage);
//          exit;
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
      $sQuery = 'SELECT id,
                        nm_promocao, 
                        de_promocao, 
                        id_desconto 
                   FROM tc_promocoes
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_promocoes = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]          = $aResultado['id']; 
        $this->NM_PROMOCAO[] = $aResultado['nm_promocao']; 
        $this->DE_PROMOCAO[] = $aResultado['de_promocao']; 
        $this->ID_DESCONTO[] = $aResultado['id_desconto']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_promocoes(
                             NM_PROMOCAO, 
                             DE_PROMOCAO, 
                             ID_DESCONTO 
)
      VALUES(
              '".$this->NM_PROMOCAO[0]."', 
              '".$this->DE_PROMOCAO[0]."', 
              '".$this->ID_DESCONTO[0]."' 
    )";
      $sResultado = mysql_query($sQuery, $this->DB_LINK);
  // Debug - tstAlex
      echo '<pre>';
      print_r($sQuery);
      echo '</pre>';
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
      $sQuery = "DELETE FROM tc_promocoes ".$sFiltro;
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
      $sQuery = "UPDATE tc_promocoes
        SET
          nm_promocao = '".$this->NM_PROMOCAO[0]."', 
          de_promocao = '".$this->DE_PROMOCAO[0]."', 
          id_desconto = '".$this->ID_DESCONTO[0]."' 
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

      $this->ID[0]          = (isset ($_POST['CMPpromocoes-id'])       ? $_POST['CMPpromocoes-id']          : '');
      $this->NM_PROMOCAO[0] = (isset ($_POST['CMPpromocoes-promocao']) ? $_POST['CMPpromocoes-promocao'] : '');
      $this->DE_PROMOCAO[0] = (isset ($_POST['CMPpromocoes-desc'])     ? $_POST['CMPpromocoes-desc'] : '');
      $this->ID_DESCONTO[0] = (isset ($_POST['CMPpromocoes-desconto']) ? $_POST['CMPpromocoes-desconto'] : '');
      
    }
  }