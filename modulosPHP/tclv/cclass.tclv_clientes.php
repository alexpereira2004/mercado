
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

  class tclv_clientes {
  
    public $id;
    public $nm_cliente;
    public $nm_sobrenome;
    public $tx_tel_fixo;
    public $tx_tel_cel;
    public $tx_email;
    public $cd_sexo;
    public $dt_nascimento;
    public $nm_razao_social;
    public $nm_fantasia;
    public $nu_cnpj;
    public $nu_ie;
    public $dt_fundacao;
    public $tx_login;
    public $tx_pass;
    public $cd_recebe_news;
    public $nu_cep;
    public $tp_logradouro;
    public $nm_logradouro;
    public $tx_numero;
    public $tx_complemento;
    public $tx_bairro;
    public $cd_uf;
    public $cd_cid;
    public $iCdMsg;
    public $sMsg;
    public $sErro;

    public function getNm_cliente($nm_cliente) {
      return $this->nm_cliente;
    }

    public function getNm_sobrenome($nm_sobrenome) {
      return $this->nm_sobrenome;
    }

    public function getTx_tel_fixo($tx_tel_fixo) {
      return $this->tx_tel_fixo;
    }

    public function getTx_tel_cel($tx_tel_cel) {
      return $this->tx_tel_cel;
    }

    public function getTx_email($tx_email) {
      return $this->tx_email;
    }

    public function getCd_sexo($cd_sexo) {
      return $this->cd_sexo;
    }

    public function getDt_nascimento($dt_nascimento) {
      return $this->dt_nascimento;
    }

    public function getNm_razao_social($nm_razao_social) {
      return $this->nm_razao_social;
    }

    public function getNm_fantasia($nm_fantasia) {
      return $this->nm_fantasia;
    }

    public function getNu_cnpj($nu_cnpj) {
      return $this->nu_cnpj;
    }

    public function getNu_ie($nu_ie) {
      return $this->nu_ie;
    }

    public function getDt_fundacao($dt_fundacao) {
      return $this->dt_fundacao;
    }

    public function getTx_login($tx_login) {
      return $this->tx_login;
    }

    public function getTx_pass($tx_pass) {
      return $this->tx_pass;
    }

    public function getCd_recebe_news($cd_recebe_news) {
      return $this->cd_recebe_news;
    }

    public function getNu_cep($nu_cep) {
      return $this->nu_cep;
    }

    public function getTp_logradouro($tp_logradouro) {
      return $this->tp_logradouro;
    }

    public function getNm_logradouro($nm_logradouro) {
      return $this->nm_logradouro;
    }

    public function getTx_numero($tx_numero) {
      return $this->tx_numero;
    }

    public function getTx_complemento($tx_complemento) {
      return $this->tx_complemento;
    }

    public function getTx_bairro($tx_bairro) {
      return $this->tx_bairro;
    }

    public function getCd_uf($cd_uf) {
      return $this->cd_uf;
    }

    public function getCd_cid($cd_cid) {
      return $this->cd_cid;
    }



    public function setNm_cliente($nm_cliente) {
      $this->nm_cliente = $nm_cliente;
    }

    public function setNm_sobrenome($nm_sobrenome) {
      $this->nm_sobrenome = $nm_sobrenome;
    }

    public function setTx_tel_fixo($tx_tel_fixo) {
      $this->tx_tel_fixo = $tx_tel_fixo;
    }

    public function setTx_tel_cel($tx_tel_cel) {
      $this->tx_tel_cel = $tx_tel_cel;
    }

    public function setTx_email($tx_email) {
      $this->tx_email = $tx_email;
    }

    public function setCd_sexo($cd_sexo) {
      $this->cd_sexo = $cd_sexo;
    }

    public function setDt_nascimento($dt_nascimento) {
      $this->dt_nascimento = $dt_nascimento;
    }

    public function setNm_razao_social($nm_razao_social) {
      $this->nm_razao_social = $nm_razao_social;
    }

    public function setNm_fantasia($nm_fantasia) {
      $this->nm_fantasia = $nm_fantasia;
    }

    public function setNu_cnpj($nu_cnpj) {
      $this->nu_cnpj = $nu_cnpj;
    }

    public function setNu_ie($nu_ie) {
      $this->nu_ie = $nu_ie;
    }

    public function setDt_fundacao($dt_fundacao) {
      $this->dt_fundacao = $dt_fundacao;
    }

    public function setTx_login($tx_login) {
      $this->tx_login = $tx_login;
    }

    public function setTx_pass($tx_pass) {
      $this->tx_pass = $tx_pass;
    }

    public function setCd_recebe_news($cd_recebe_news) {
      $this->cd_recebe_news = $cd_recebe_news;
    }

    public function setNu_cep($nu_cep) {
      $this->nu_cep = $nu_cep;
    }

    public function setTp_logradouro($tp_logradouro) {
      $this->tp_logradouro = $tp_logradouro;
    }

    public function setNm_logradouro($nm_logradouro) {
      $this->nm_logradouro = $nm_logradouro;
    }

    public function setTx_numero($tx_numero) {
      $this->tx_numero = $tx_numero;
    }

    public function setTx_complemento($tx_complemento) {
      $this->tx_complemento = $tx_complemento;
    }

    public function setTx_bairro($tx_bairro) {
      $this->tx_bairro = $tx_bairro;
    }

    public function setCd_uf($cd_uf) {
      $this->cd_uf = $cd_uf;
    }

    public function setCd_cid($cd_cid) {
      $this->cd_cid = $cd_cid;
    }


      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_cliente, 
                        nm_sobrenome, 
                        tx_tel_fixo, 
                        tx_tel_cel, 
                        tx_email, 
                        cd_sexo, 
                        date_format(dt_nascimento, "%d/%m/%Y") AS dt_nascimento, 
                        nm_razao_social, 
                        nm_fantasia, 
                        nu_cnpj, 
                        nu_ie, 
                        date_format(dt_fundacao, "%d/%m/%Y") AS dt_fundacao, 
                        tx_login, 
                        tx_pass, 
                        cd_recebe_news, 
                        nu_cep, 
                        tp_logradouro, 
                        nm_logradouro, 
                        tx_numero, 
                        tx_complemento, 
                        tx_bairro, 
                        cd_uf, 
                        cd_cid 
                   FROM tclv_clientes
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTclv_clientes = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) {
        $this->ID[]              = $aResultado['id']; 
        $this->NM_CLIENTE[]      = $aResultado['nm_cliente']; 
        $this->NM_SOBRENOME[]    = $aResultado['nm_sobrenome']; 
        $this->TX_TEL_FIXO[]     = $aResultado['tx_tel_fixo']; 
        $this->TX_TEL_CEL[]      = $aResultado['tx_tel_cel']; 
        $this->TX_EMAIL[]        = $aResultado['tx_email']; 
        $this->CD_SEXO[]         = $aResultado['cd_sexo']; 
        $this->DT_NASCIMENTO[]   = $aResultado['dt_nascimento']; 
        $this->NM_RAZAO_SOCIAL[] = $aResultado['nm_razao_social']; 
        $this->NM_FANTASIA[]     = $aResultado['nm_fantasia']; 
        $this->NU_CNPJ[]         = $aResultado['nu_cnpj']; 
        $this->NU_IE[]           = $aResultado['nu_ie']; 
        $this->DT_FUNDACAO[]     = $aResultado['dt_fundacao']; 
        $this->TX_LOGIN[]        = $aResultado['tx_login']; 
        $this->TX_PASS[]         = $aResultado['tx_pass']; 
        $this->CD_RECEBE_NEWS[]  = $aResultado['cd_recebe_news']; 
        $this->NU_CEP[]          = $aResultado['nu_cep']; 
        $this->TP_LOGRADOURO[]   = $aResultado['tp_logradouro']; 
        $this->NM_LOGRADOURO[]   = $aResultado['nm_logradouro']; 
        $this->TX_NUMERO[]       = $aResultado['tx_numero']; 
        $this->TX_COMPLEMENTO[]  = $aResultado['tx_complemento']; 
        $this->TX_BAIRRO[]       = $aResultado['tx_bairro']; 
        $this->CD_UF[]           = $aResultado['cd_uf']; 
        $this->CD_CID[]          = $aResultado['cd_cid']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tclv_clientes(
                             NM_CLIENTE, 
                             NM_SOBRENOME, 
                             TX_TEL_FIXO, 
                             TX_TEL_CEL, 
                             TX_EMAIL, 
                             CD_SEXO, 
                             DT_NASCIMENTO, 
                             NM_RAZAO_SOCIAL, 
                             NM_FANTASIA, 
                             NU_CNPJ, 
                             NU_IE, 
                             DT_FUNDACAO, 
                             TX_LOGIN, 
                             TX_PASS, 
                             CD_RECEBE_NEWS, 
                             NU_CEP, 
                             TP_LOGRADOURO, 
                             NM_LOGRADOURO, 
                             TX_NUMERO, 
                             TX_COMPLEMENTO, 
                             TX_BAIRRO, 
                             CD_UF, 
                             CD_CID 
)
      VALUES(
              '".$this->NM_CLIENTE[0]."', 
              '".$this->NM_SOBRENOME[0]."', 
              '".$this->TX_TEL_FIXO[0]."', 
              '".$this->TX_TEL_CEL[0]."', 
              '".$this->TX_EMAIL[0]."', 
              '".$this->CD_SEXO[0]."', 
              '".$this->DT_NASCIMENTO[0]."', 
              '".$this->NM_RAZAO_SOCIAL[0]."', 
              '".$this->NM_FANTASIA[0]."', 
              '".$this->NU_CNPJ[0]."', 
              '".$this->NU_IE[0]."', 
              '".$this->DT_FUNDACAO[0]."', 
              '".$this->TX_LOGIN[0]."', 
              '".$this->TX_PASS[0]."', 
              '".$this->CD_RECEBE_NEWS[0]."', 
              '".$this->NU_CEP[0]."', 
              '".$this->TP_LOGRADOURO[0]."', 
              '".$this->NM_LOGRADOURO[0]."', 
              '".$this->TX_NUMERO[0]."', 
              '".$this->TX_COMPLEMENTO[0]."', 
              '".$this->TX_BAIRRO[0]."', 
              '".$this->CD_UF[0]."', 
              '".$this->CD_CID[0]."' 
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
      $sQuery = "DELETE FROM tclv_clientes
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
      $sQuery = "UPDATE tclv_clientes
        SET
          nm_cliente      = '".$this->NM_CLIENTE[0]."', 
          nm_sobrenome    = '".$this->NM_SOBRENOME[0]."', 
          tx_tel_fixo     = '".$this->TX_TEL_FIXO[0]."', 
          tx_tel_cel      = '".$this->TX_TEL_CEL[0]."', 
          tx_email        = '".$this->TX_EMAIL[0]."', 
          cd_sexo         = '".$this->CD_SEXO[0]."', 
          dt_nascimento   = '".$this->DT_NASCIMENTO[0]."', 
          nm_razao_social = '".$this->NM_RAZAO_SOCIAL[0]."', 
          nm_fantasia     = '".$this->NM_FANTASIA[0]."', 
          nu_cnpj         = '".$this->NU_CNPJ[0]."', 
          nu_ie           = '".$this->NU_IE[0]."', 
          dt_fundacao     = '".$this->DT_FUNDACAO[0]."', 
          tx_login        = '".$this->TX_LOGIN[0]."', 
          tx_pass         = '".$this->TX_PASS[0]."', 
          cd_recebe_news  = '".$this->CD_RECEBE_NEWS[0]."', 
          nu_cep          = '".$this->NU_CEP[0]."', 
          tp_logradouro   = '".$this->TP_LOGRADOURO[0]."', 
          nm_logradouro   = '".$this->NM_LOGRADOURO[0]."', 
          tx_numero       = '".$this->TX_NUMERO[0]."', 
          tx_complemento  = '".$this->TX_COMPLEMENTO[0]."', 
          tx_bairro       = '".$this->TX_BAIRRO[0]."', 
          cd_uf           = '".$this->CD_UF[0]."', 
          cd_cid          = '".$this->CD_CID[0]."' 
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

      $this->ID[0]              = '';
      $this->NM_CLIENTE[0]      = '';
      $this->NM_SOBRENOME[0]    = '';
      $this->TX_TEL_FIXO[0]     = '';
      $this->TX_TEL_CEL[0]      = '';
      $this->TX_EMAIL[0]        = '';
      $this->CD_SEXO[0]         = '';
      $this->DT_NASCIMENTO[0]   = '';
      $this->NM_RAZAO_SOCIAL[0] = '';
      $this->NM_FANTASIA[0]     = '';
      $this->NU_CNPJ[0]         = '';
      $this->NU_IE[0]           = '';
      $this->DT_FUNDACAO[0]     = '';
      $this->TX_LOGIN[0]        = '';
      $this->TX_PASS[0]         = '';
      $this->CD_RECEBE_NEWS[0]  = '';
      $this->NU_CEP[0]          = '';
      $this->TP_LOGRADOURO[0]   = '';
      $this->NM_LOGRADOURO[0]   = '';
      $this->TX_NUMERO[0]       = '';
      $this->TX_COMPLEMENTO[0]  = '';
      $this->TX_BAIRRO[0]       = '';
      $this->CD_UF[0]           = '';
      $this->CD_CID[0]          = '';
      
    }
  }