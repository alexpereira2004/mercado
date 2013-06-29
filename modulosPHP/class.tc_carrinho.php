
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       24-02-2013
   **/

  class tc_carrinho {
  
    public    $id;
    public    $cd_carrinho;
    public    $cd_pagseguro;
    public    $sq_carrinho;
    public    $cd_sit;
    public    $cd_sit_pagseguro;
    public    $cd_pagamento;
    public    $nu_itens;
    public    $id_cliente;
    public    $id_end_entrega;
    public    $vl_item;
    public    $vl_adicional;
    public    $vl_taxas;
    public    $vl_desconto;
    public    $vl_frete;
    public    $vl_total;
    public    $dt_criacao;
    public    $hr_criacao;
    public    $dt_fechamento;
    public    $hr_fechamento;
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
        $aValidar = array ( 1 => array('Carrinho'     , $_POST['CMPcarrinho-id'], 'varchar(255)', true),
                            2 => array('Carrinho'     , $_POST['CMPcarrinho-codigo'], 'int(5)', true),
                            3 => array('Sit'          , $_POST['CMPcarrinho-sit'], 'varchar(2)', true),
                            4 => array('Pagamento'    , $_POST['CMPcarrinho-pagamento'], 'varchar(10)', true),
                            5 => array('Itens'        , $_POST['CMPcarrinho-itens'], 'int(10)', true),
                            6 => array('Cliente'      , $_POST['CMPcarrinho-cliente'], 'int(10)', true),
                            7 => array('End-entrega'  , $_POST['CMPcarrinho-end-entrega'], 'int(10)', true),
                            8 => array('Item'         , $_POST['CMPcarrinho-item'], 'decimal(10,2)', true),
                            9 => array('Adicional'    , $_POST['CMPcarrinho-adicional'], 'decimal(10,2)', true),
                            10 => array('Taxas'       , $_POST['CMPcarrinho-taxas'], 'decimal(10,2)', true),
                            11 => array('Desconto'    , $_POST['CMPcarrinho-desconto'], 'decimal(10,2)', true),
                            12 => array('Frete'       , $_POST['CMPcarrinho-frete'], 'decimal(10,2)', true),
                            13 => array('Total'       , $_POST['CMPcarrinho-total'], 'decimal(10,2)', true),
                            14 => array('Criacao'     , $_POST['CMPcarrinho-criacao'], 'date', true),
                            15 => array('Criacao'     , $_POST['CMPcarrinho-criacao'], 'time', true),
                            16 => array('Fechamento'  , $_POST['CMPcarrinho-fechamento'], 'date', true),
                            17 => array('Fechamento'  , $_POST['CMPcarrinho-fechamento'], 'time', true),
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
                        cd_carrinho, 
                        cd_pagseguro, 
                        sq_carrinho, 
                        cd_sit,
                        cd_sit_pagseguro,
                        cd_pagamento, 
                        nu_itens, 
                        id_cliente, 
                        id_end_entrega, 
                        vl_item, 
                        vl_adicional, 
                        vl_taxas, 
                        vl_desconto, 
                        vl_frete, 
                        vl_total, 
                        date_format(dt_criacao, "%d/%m/%Y") AS dt_criacao, 
                        date_format(hr_criacao, "%H:%i") AS hr_criacao, 
                        date_format(dt_fechamento, "%d/%m/%Y") AS dt_fechamento, 
                        date_format(hr_fechamento, "%H:%i") AS hr_fechamento 
                   FROM tc_carrinho
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_carrinho = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]               = $aResultado['id']; 
        $this->CD_CARRINHO[]      = $aResultado['cd_carrinho']; 
        $this->CD_PAGSEGURO[]     = $aResultado['cd_pagseguro']; 
        $this->SQ_CARRINHO[]      = $aResultado['sq_carrinho']; 
        $this->CD_SIT[]           = $aResultado['cd_sit']; 
        $this->CD_SIT_PAGSEGURO[] = $aResultado['cd_sit_pagseguro']; 
        $this->CD_PAGAMENTO[]     = $aResultado['cd_pagamento']; 
        $this->NU_ITENS[]         = $aResultado['nu_itens']; 
        $this->ID_CLIENTE[]       = $aResultado['id_cliente']; 
        $this->ID_END_ENTREGA[]   = $aResultado['id_end_entrega']; 
        $this->VL_ITEM[]          = $aResultado['vl_item']; 
        $this->VL_ADICIONAL[]     = $aResultado['vl_adicional']; 
        $this->VL_TAXAS[]         = $aResultado['vl_taxas']; 
        $this->VL_DESCONTO[]      = $aResultado['vl_desconto']; 
        $this->VL_FRETE[]         = $aResultado['vl_frete']; 
        $this->VL_TOTAL[]         = $aResultado['vl_total']; 
        $this->DT_CRIACAO[]       = $aResultado['dt_criacao']; 
        $this->HR_CRIACAO[]       = $aResultado['hr_criacao']; 
        $this->DT_FECHAMENTO[]    = $aResultado['dt_fechamento']; 
        $this->HR_FECHAMENTO[]    = $aResultado['hr_fechamento']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_carrinho(
                             CD_CARRINHO, 
                             CD_PAGSEGURO, 
                             SQ_CARRINHO, 
                             CD_SIT, 
                             CD_PAGAMENTO, 
                             NU_ITENS, 
                             ID_CLIENTE, 
                             ID_END_ENTREGA, 
                             VL_ITEM, 
                             VL_ADICIONAL, 
                             VL_TAXAS, 
                             VL_DESCONTO, 
                             VL_FRETE, 
                             VL_TOTAL, 
                             DT_CRIACAO, 
                             HR_CRIACAO
)
      VALUES(
              '".$this->CD_CARRINHO[0]."', 
              '".$this->CD_PAGSEGURO[0]."', 
              '".$this->SQ_CARRINHO[0]."', 
              '".$this->CD_SIT[0]."', 
              '".$this->CD_PAGAMENTO[0]."', 
              '".$this->NU_ITENS[0]."', 
              '".$this->ID_CLIENTE[0]."', 
              '".$this->ID_END_ENTREGA[0]."', 
              '".$this->VL_ITEM[0]."', 
              '".$this->VL_ADICIONAL[0]."', 
              '".$this->VL_TAXAS[0]."', 
              '".$this->VL_DESCONTO[0]."', 
              '".$this->VL_FRETE[0]."', 
              '".$this->VL_TOTAL[0]."', 
              curdate(),
              curtime() )";
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
      $sQuery = "DELETE FROM tc_carrinho
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

    public function editar($sFiltro) {
      $sQuery = "UPDATE tc_carrinho
        SET
          cd_carrinho      = '".$this->CD_CARRINHO[0]."', 
          cd_pagseguro     = '".$this->CD_PAGSEGURO[0]."', 
          sq_carrinho      = '".$this->SQ_CARRINHO[0]."', 
          cd_sit           = '".$this->CD_SIT[0]."', 
          cd_sit_pagseguro = '".$this->CD_SIT_PAGSEGURO[0]."', 
          cd_pagamento     = '".$this->CD_PAGAMENTO[0]."',
          nu_itens         = '".$this->NU_ITENS[0]."', 
          id_cliente       = '".$this->ID_CLIENTE[0]."', 
          id_end_entrega   = '".$this->ID_END_ENTREGA[0]."', 
          vl_item          = '".$this->VL_ITEM[0]."', 
          vl_adicional     = '".$this->VL_ADICIONAL[0]."', 
          vl_taxas         = '".$this->VL_TAXAS[0]."', 
          vl_desconto      = '".$this->VL_DESCONTO[0]."', 
          vl_frete         = '".$this->VL_FRETE[0]."', 
          vl_total         = '".$this->VL_TOTAL[0]."' 
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

      $this->ID[0]               = (isset ($_POST['CMPcarrinho-id'])            ? $_POST['CMPcarrinho-id']            : '');
      $this->CD_CARRINHO[0]      = (isset ($_POST['CMPcarrinho-carrinho'])      ? $_POST['CMPcarrinho-carrinho']      : '');
      $this->CD_PAGSEGURO[0]     = (isset ($_POST['CMPcarrinho-pagseguro'])     ? $_POST['CMPcarrinho-pagseguro']     : '');
      $this->SQ_CARRINHO[0]      = (isset ($_POST['CMPcarrinho-carrinho'])      ? $_POST['CMPcarrinho-carrinho']      : '');
      $this->CD_SIT[0]           = (isset ($_POST['CMPcarrinho-sit'])           ? $_POST['CMPcarrinho-sit']           : '');
      $this->CD_SIT_PAGSEGURO[0] = (isset ($_POST['CMPcarrinho-sit-pagseguro']) ? $_POST['CMPcarrinho-sit-pagseguro'] : '');
      $this->CD_PAGAMENTO[0]     = (isset ($_POST['CMPcarrinho-pagamento'])     ? $_POST['CMPcarrinho-pagamento']     : '');
      $this->NU_ITENS[0]         = (isset ($_POST['CMPcarrinho-itens'])         ? $_POST['CMPcarrinho-itens']         : '');
      $this->ID_CLIENTE[0]       = (isset ($_POST['CMPcarrinho-cliente'])       ? $_POST['CMPcarrinho-cliente']       : '');
      $this->ID_END_ENTREGA[0]   = (isset ($_POST['CMPcarrinho-end-entrega'])   ? $_POST['CMPcarrinho-end-entrega']   : '');
      $this->VL_ITEM[0]          = (isset ($_POST['CMPcarrinho-item'])          ? $_POST['CMPcarrinho-item']          : '');
      $this->VL_ADICIONAL[0]     = (isset ($_POST['CMPcarrinho-adicional'])     ? $_POST['CMPcarrinho-adicional']     : '');
      $this->VL_TAXAS[0]         = (isset ($_POST['CMPcarrinho-taxas'])         ? $_POST['CMPcarrinho-taxas']         : '');
      $this->VL_DESCONTO[0]      = (isset ($_POST['CMPcarrinho-desconto'])      ? $_POST['CMPcarrinho-desconto']      : '');
      $this->VL_FRETE[0]         = (isset ($_POST['CMPcarrinho-frete'])         ? $_POST['CMPcarrinho-frete']         : '');
      $this->VL_TOTAL[0]         = (isset ($_POST['CMPcarrinho-total'])         ? $_POST['CMPcarrinho-total']         : '');
      $this->DT_CRIACAO[0]       = (isset ($_POST['CMPcarrinho-criacao'])       ? $_POST['CMPcarrinho-criacao']       : '');
      $this->HR_CRIACAO[0]       = (isset ($_POST['CMPcarrinho-criacao'])       ? $_POST['CMPcarrinho-criacao']       : '');
      $this->DT_FECHAMENTO[0]    = (isset ($_POST['CMPcarrinho-fechamento'])    ? $_POST['CMPcarrinho-fechamento']    : '');
      $this->HR_FECHAMENTO[0]    = (isset ($_POST['CMPcarrinho-fechamento'])    ? $_POST['CMPcarrinho-fechamento']    : '');
      
    }
  }