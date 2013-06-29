
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       24-02-2013
   **/

  class tr_carrinho_itens {
  
    public    $id;
    public    $id_prod;
    public    $id_carrinho;
    public    $nu_quantidade;
    public    $vl_final;
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
        $aValidar = array ( 1 => array('Prod' , $_POST['CMPcarrinho-itens-prod'], 'int(8)', true),
                            2 => array('Carrinho' , $_POST['CMPcarrinho-itens-carrinho'], 'int(8)', true),
                            3 => array('Quantidade' , $_POST['CMPcarrinho-itens-quantidade'], 'int(8)', true),
                            4 => array('Final' , $_POST['CMPcarrinho-itens-final'], 'decimal(10,2)', true),
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
      $sQuery = 'SELECT tr_carrinho_itens.id,
                        id_prod, 
                        id_carrinho, 
                        nu_quantidade, 
                        vl_final,
                        nm_produto
                   FROM tr_carrinho_itens
             INNER JOIN tc_produtos ON tc_produtos.id = tr_carrinho_itens.id_prod
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTr_carrinho_itens = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]            = $aResultado['id']; 
        $this->ID_PROD[]       = $aResultado['id_prod']; 
        $this->ID_CARRINHO[]   = $aResultado['id_carrinho']; 
        $this->NU_QUANTIDADE[] = $aResultado['nu_quantidade']; 
        $this->VL_FINAL[]      = $aResultado['vl_final']; 
        $this->NM_PRODUTO[]    = $aResultado['nm_produto']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tr_carrinho_itens(
                             ID_PROD, 
                             ID_CARRINHO, 
                             NU_QUANTIDADE, 
                             VL_FINAL )
      VALUES(
              '".$this->ID_PROD[0]."', 
              '".$this->ID_CARRINHO[0]."', 
              '".$this->NU_QUANTIDADE[0]."', 
              '".$this->VL_FINAL[0]."' )";

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

    public function remover($sWhere) {
      $sQuery = "DELETE FROM tr_carrinho_itens ".$sWhere;
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
      $sQuery = "UPDATE tr_carrinho_itens
        SET
          id_prod       = '".$this->ID_PROD[0]."', 
          id_carrinho   = '".$this->ID_CARRINHO[0]."', 
          nu_quantidade = '".$this->NU_QUANTIDADE[0]."', 
          vl_final      = '".$this->VL_FINAL[0]."' 
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

      $this->ID[0]            = (isset ($_POST['CMPcarrinho-itens-id'])            ? $_POST['CMPcarrinho-itens-id']            : '');
      $this->ID_PROD[0]       = (isset ($_POST['CMPcarrinho-itens-prod'])       ? $_POST['CMPcarrinho-itens-prod']       : '');
      $this->ID_CARRINHO[0]   = (isset ($_POST['CMPcarrinho-itens-carrinho'])   ? $_POST['CMPcarrinho-itens-carrinho']   : '');
      $this->NU_QUANTIDADE[0] = (isset ($_POST['CMPcarrinho-itens-quantidade']) ? $_POST['CMPcarrinho-itens-quantidade'] : '');
      $this->VL_FINAL[0]      = (isset ($_POST['CMPcarrinho-itens-final'])      ? $_POST['CMPcarrinho-itens-final']      : '');
      
    }
  }