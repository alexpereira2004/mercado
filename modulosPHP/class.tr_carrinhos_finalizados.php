
<?php
  /**
   * Descricao
   *
   * @package    Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       15-04-2013
   **/

  class tr_carrinhos_finalizados {
  
    public    $id;
    public    $id_carrinho;
    public    $nr_nf;
    public    $dt_finalizacao;
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
        $aValidar = array ( 1 => array('Carrinho' , $_POST['CMPcarrinhos-finalizados-carrinho'], 'int(10)', true),
                            2 => array('Nf' , $_POST['CMPcarrinhos-finalizados-nf'], 'varchar(25)', true),
                            3 => array('Finalizacao' , $_POST['CMPcarrinhos-finalizados-finalizacao'], 'date', true),
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
      $sQuery = 'SELECT id,
                        id_carrinho, 
                        nr_nf, 
                        date_format(dt_finalizacao, "%d/%m/%Y") AS dt_finalizacao 
                   FROM tr_carrinhos_finalizados
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTr_carrinhos_finalizados = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]             = $aResultado['id']; 
        $this->ID_CARRINHO[]    = $aResultado['id_carrinho']; 
        $this->NR_NF[]          = $aResultado['nr_nf']; 
        $this->DT_FINALIZACAO[] = $aResultado['dt_finalizacao']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tr_carrinhos_finalizados(
                             ID_CARRINHO, 
                             NR_NF, 
                             DT_FINALIZACAO 
)
      VALUES(
              '".$this->ID_CARRINHO[0]."', 
              '".$this->NR_NF[0]."', 
              '".$this->DT_FINALIZACAO[0]."' 
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
      $sQuery = "DELETE FROM tr_carrinhos_finalizados
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
      $sQuery = "UPDATE tr_carrinhos_finalizados
        SET
          id_carrinho    = '".$this->ID_CARRINHO[0]."', 
          nr_nf          = '".$this->NR_NF[0]."', 
          dt_finalizacao = '".$this->DT_FINALIZACAO[0]."' 
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

      $this->ID[0]             = (isset ($_POST['CMPcarrinhos-finalizados-id'])             ? $_POST['CMPcarrinhos-finalizados-id']             : '');
      $this->ID_CARRINHO[0]    = (isset ($_POST['CMPcarrinhos-finalizados-carrinho'])    ? $_POST['CMPcarrinhos-finalizados-carrinho']    : '');
      $this->NR_NF[0]          = (isset ($_POST['CMPcarrinhos-finalizados-nf'])          ? $_POST['CMPcarrinhos-finalizados-nf']          : '');
      $this->DT_FINALIZACAO[0] = (isset ($_POST['CMPcarrinhos-finalizados-finalizacao']) ? $_POST['CMPcarrinhos-finalizados-finalizacao'] : '');
      
    }
  }