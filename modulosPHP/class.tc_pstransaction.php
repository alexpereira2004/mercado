
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       27-03-2013
   **/

  class tc_pstransaction {
  
    public    $id;
    public    $dt_criacao;
    public    $lastEventDate;
    public    $cd_codigo;
    public    $reference;
    public    $cd_tipo;
    public    $cd_status;
    public    $cancellationSource;
    public    $paymentMethod_type;
    public    $paymentMethod_code;
    public    $grossAmount;
    public    $discountAmount;
    public    $feeAmount;
    public    $netAmount;
    public    $extraAmount;
    public    $installmentCount;
    public    $itemCount;
    public    $sender_email;
    public    $shipping_type;
    public    $shipping_cost;
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
        $aValidar = array ( 1 => array('Criacao' , $_POST['CMPpstransaction-criacao'], 'date', true),
                            2 => array('LastEventDate' , $_POST['CMPpstransaction-lastEventDate'], 'date', true),
                            3 => array('Codigo' , $_POST['CMPpstransaction-codigo'], 'varchar(40)', true),
                            4 => array('Reference' , $_POST['CMPpstransaction-reference'], 'varchar(250)', true),
                            5 => array('Tipo' , $_POST['CMPpstransaction-tipo'], 'int(2)', true),
                            6 => array('Status' , $_POST['CMPpstransaction-status'], 'int(2)', true),
                            7 => array('CancellationSource' , $_POST['CMPpstransaction-cancellationSource'], 'varchar(20)', true),
                            8 => array('Type' , $_POST['CMPpstransaction-type'], 'int(1)', true),
                            9 => array('Code' , $_POST['CMPpstransaction-code'], 'int(3)', true),
                            10 => array('GrossAmount' , $_POST['CMPpstransaction-grossAmount'], 'decimal(10,2)', true),
                            11 => array('DiscountAmount' , $_POST['CMPpstransaction-discountAmount'], 'decimal(10,2)', true),
                            12 => array('FeeAmount' , $_POST['CMPpstransaction-feeAmount'], 'decimal(10,2)', true),
                            13 => array('NetAmount' , $_POST['CMPpstransaction-netAmount'], 'decimal(10,2)', true),
                            14 => array('ExtraAmount' , $_POST['CMPpstransaction-extraAmount'], 'decimal(10,2)', true),
                            15 => array('InstallmentCount' , $_POST['CMPpstransaction-installmentCount'], 'int(10)', true),
                            16 => array('ItemCount' , $_POST['CMPpstransaction-itemCount'], 'int(10)', true),
                            17 => array('Email' , $_POST['CMPpstransaction-email'], 'varchar(100)', true),
                            18 => array('Type' , $_POST['CMPpstransaction-type'], 'varchar(100)', true),
                            19 => array('Cost' , $_POST['CMPpstransaction-cost'], 'decimal(10,2)', true),
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
                        date_format(dt_criacao, "%d/%m/%Y") AS dt_criacao, 
                        date_format(lastEventDate, "%d/%m/%Y") AS lastEventDate, 
                        cd_codigo, 
                        reference, 
                        cd_tipo, 
                        cd_status, 
                        cancellationSource, 
                        paymentMethod_type, 
                        paymentMethod_code, 
                        grossAmount, 
                        discountAmount, 
                        feeAmount, 
                        netAmount, 
                        extraAmount, 
                        installmentCount, 
                        itemCount, 
                        sender_email, 
                        shipping_type, 
                        shipping_cost 
                   FROM tc_pstransaction
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_pstransaction = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]                 = $aResultado['id']; 
        $this->DT_CRIACAO[]         = $aResultado['dt_criacao']; 
        $this->LASTEVENTDATE[]      = $aResultado['lastEventDate']; 
        $this->CD_CODIGO[]          = $aResultado['cd_codigo']; 
        $this->REFERENCE[]          = $aResultado['reference']; 
        $this->CD_TIPO[]            = $aResultado['cd_tipo']; 
        $this->CD_STATUS[]          = $aResultado['cd_status']; 
        $this->CANCELLATIONSOURCE[] = $aResultado['cancellationSource']; 
        $this->PAYMENTMETHOD_TYPE[] = $aResultado['paymentMethod_type']; 
        $this->PAYMENTMETHOD_CODE[] = $aResultado['paymentMethod_code']; 
        $this->GROSSAMOUNT[]        = $aResultado['grossAmount']; 
        $this->DISCOUNTAMOUNT[]     = $aResultado['discountAmount']; 
        $this->FEEAMOUNT[]          = $aResultado['feeAmount']; 
        $this->NETAMOUNT[]          = $aResultado['netAmount']; 
        $this->EXTRAAMOUNT[]        = $aResultado['extraAmount']; 
        $this->INSTALLMENTCOUNT[]   = $aResultado['installmentCount']; 
        $this->ITEMCOUNT[]          = $aResultado['itemCount']; 
        $this->SENDER_EMAIL[]       = $aResultado['sender_email']; 
        $this->SHIPPING_TYPE[]      = $aResultado['shipping_type']; 
        $this->SHIPPING_COST[]      = $aResultado['shipping_cost']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_pstransaction(
                             DT_CRIACAO, 
                             LASTEVENTDATE, 
                             CD_CODIGO, 
                             REFERENCE, 
                             CD_TIPO, 
                             CD_STATUS, 
                             CANCELLATIONSOURCE, 
                             PAYMENTMETHOD_TYPE, 
                             PAYMENTMETHOD_CODE, 
                             GROSSAMOUNT, 
                             DISCOUNTAMOUNT, 
                             FEEAMOUNT, 
                             NETAMOUNT, 
                             EXTRAAMOUNT, 
                             INSTALLMENTCOUNT, 
                             ITEMCOUNT, 
                             SENDER_EMAIL, 
                             SHIPPING_TYPE, 
                             SHIPPING_COST 
)
      VALUES(
              '".$this->DT_CRIACAO[0]."', 
              '".$this->LASTEVENTDATE[0]."', 
              '".$this->CD_CODIGO[0]."', 
              '".$this->REFERENCE[0]."', 
              '".$this->CD_TIPO[0]."', 
              '".$this->CD_STATUS[0]."', 
              '".$this->CANCELLATIONSOURCE[0]."', 
              '".$this->PAYMENTMETHOD_TYPE[0]."', 
              '".$this->PAYMENTMETHOD_CODE[0]."', 
              '".$this->GROSSAMOUNT[0]."', 
              '".$this->DISCOUNTAMOUNT[0]."', 
              '".$this->FEEAMOUNT[0]."', 
              '".$this->NETAMOUNT[0]."', 
              '".$this->EXTRAAMOUNT[0]."', 
              '".$this->INSTALLMENTCOUNT[0]."', 
              '".$this->ITEMCOUNT[0]."', 
              '".$this->SENDER_EMAIL[0]."', 
              '".$this->SHIPPING_TYPE[0]."', 
              '".$this->SHIPPING_COST[0]."' 
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
      $sQuery = "DELETE FROM tc_pstransaction
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
      $sQuery = "UPDATE tc_pstransaction
        SET
          dt_criacao         = '".$this->DT_CRIACAO[0]."', 
          lastEventDate      = '".$this->LASTEVENTDATE[0]."', 
          cd_codigo          = '".$this->CD_CODIGO[0]."', 
          reference          = '".$this->REFERENCE[0]."', 
          cd_tipo            = '".$this->CD_TIPO[0]."', 
          cd_status          = '".$this->CD_STATUS[0]."', 
          cancellationSource = '".$this->CANCELLATIONSOURCE[0]."', 
          paymentMethod_type = '".$this->PAYMENTMETHOD_TYPE[0]."', 
          paymentMethod_code = '".$this->PAYMENTMETHOD_CODE[0]."', 
          grossAmount        = '".$this->GROSSAMOUNT[0]."', 
          discountAmount     = '".$this->DISCOUNTAMOUNT[0]."', 
          feeAmount          = '".$this->FEEAMOUNT[0]."', 
          netAmount          = '".$this->NETAMOUNT[0]."', 
          extraAmount        = '".$this->EXTRAAMOUNT[0]."', 
          installmentCount   = '".$this->INSTALLMENTCOUNT[0]."', 
          itemCount          = '".$this->ITEMCOUNT[0]."', 
          sender_email       = '".$this->SENDER_EMAIL[0]."', 
          shipping_type      = '".$this->SHIPPING_TYPE[0]."', 
          shipping_cost      = '".$this->SHIPPING_COST[0]."' 
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

      $this->ID[0]                 = (isset ($_POST['CMPpstransaction-id'])                 ? $_POST['CMPpstransaction-id']                 : '');
      $this->DT_CRIACAO[0]         = (isset ($_POST['CMPpstransaction-criacao'])         ? $_POST['CMPpstransaction-criacao']         : '');
      $this->LASTEVENTDATE[0]      = (isset ($_POST['CMPpstransaction-lastEventDate'])      ? $_POST['CMPpstransaction-lastEventDate']      : '');
      $this->CD_CODIGO[0]          = (isset ($_POST['CMPpstransaction-codigo'])          ? $_POST['CMPpstransaction-codigo']          : '');
      $this->REFERENCE[0]          = (isset ($_POST['CMPpstransaction-reference'])          ? $_POST['CMPpstransaction-reference']          : '');
      $this->CD_TIPO[0]            = (isset ($_POST['CMPpstransaction-tipo'])            ? $_POST['CMPpstransaction-tipo']            : '');
      $this->CD_STATUS[0]          = (isset ($_POST['CMPpstransaction-status'])          ? $_POST['CMPpstransaction-status']          : '');
      $this->CANCELLATIONSOURCE[0] = (isset ($_POST['CMPpstransaction-cancellationSource']) ? $_POST['CMPpstransaction-cancellationSource'] : '');
      $this->PAYMENTMETHOD_TYPE[0] = (isset ($_POST['CMPpstransaction-type']) ? $_POST['CMPpstransaction-type'] : '');
      $this->PAYMENTMETHOD_CODE[0] = (isset ($_POST['CMPpstransaction-code']) ? $_POST['CMPpstransaction-code'] : '');
      $this->GROSSAMOUNT[0]        = (isset ($_POST['CMPpstransaction-grossAmount'])        ? $_POST['CMPpstransaction-grossAmount']        : '');
      $this->DISCOUNTAMOUNT[0]     = (isset ($_POST['CMPpstransaction-discountAmount'])     ? $_POST['CMPpstransaction-discountAmount']     : '');
      $this->FEEAMOUNT[0]          = (isset ($_POST['CMPpstransaction-feeAmount'])          ? $_POST['CMPpstransaction-feeAmount']          : '');
      $this->NETAMOUNT[0]          = (isset ($_POST['CMPpstransaction-netAmount'])          ? $_POST['CMPpstransaction-netAmount']          : '');
      $this->EXTRAAMOUNT[0]        = (isset ($_POST['CMPpstransaction-extraAmount'])        ? $_POST['CMPpstransaction-extraAmount']        : '');
      $this->INSTALLMENTCOUNT[0]   = (isset ($_POST['CMPpstransaction-installmentCount'])   ? $_POST['CMPpstransaction-installmentCount']   : '');
      $this->ITEMCOUNT[0]          = (isset ($_POST['CMPpstransaction-itemCount'])          ? $_POST['CMPpstransaction-itemCount']          : '');
      $this->SENDER_EMAIL[0]       = (isset ($_POST['CMPpstransaction-email'])       ? $_POST['CMPpstransaction-email']       : '');
      $this->SHIPPING_TYPE[0]      = (isset ($_POST['CMPpstransaction-type'])      ? $_POST['CMPpstransaction-type']      : '');
      $this->SHIPPING_COST[0]      = (isset ($_POST['CMPpstransaction-cost'])      ? $_POST['CMPpstransaction-cost']      : '');
      
    }
  }