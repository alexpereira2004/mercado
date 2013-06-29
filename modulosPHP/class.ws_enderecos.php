<?php



class ws_enderecos {
  public $sCdTpCep;
  public $sCidade;
  public $sSgUf;
  public $sTipoLogradouro;
  public $sLogradouro;
  public $sBairro;


/*
 *	Funзгo de busca de Endereзo pelo CEP
 *	-	Desenvolvido Felipe Olivaes para ajaxbox.com.br
 *	-	Utilizando WebService de CEP da republicavirtual.com.br
 */
  function buscar_cep($cep){
    $sResultado = @file_get_contents('http://republicavirtual.com.br/web_cep.php?cep='.urlencode($cep).'&formato=query_string');
    if(!$sResultado){
      $sResultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";
    }
    parse_str($sResultado, $aRetorno);

    //return $aRetorno;
  

    switch($aRetorno['resultado']){
      case '2':
        $this->sCdTpCep        = 'Unico';
        $this->sCidade         = $aRetorno['cidade'];
        $this->sSgUf           = $aRetorno['uf'];
        $this->sTipoLogradouro = '';
        $this->sLogradouro     = '';
        $this->sBairro         = '';
        $bSucesso = true;
      break;

      case '1':
        $this->sCdTpCep        = 'Completo';
        $this->sCidade         = $aRetorno['cidade'];
        $this->sSgUf           = $aRetorno['uf'];
        $this->sTipoLogradouro = $aRetorno['tipo_logradouro'];
        $this->sLogradouro     = $aRetorno['logradouro'];
        $this->sBairro         = $aRetorno['bairro'];
        $bSucesso = true;
      break;

      default:
        $this->sCdTpCep        = 'Erro';
        $this->sCidade         = '';
        $this->sSgUf           = '';
        $this->sTipoLogradouro = '';
        $this->sLogradouro     = '';
        $this->sBairro         = '';
        $bSucesso = false;
      break;
    }

    return $bSucesso;
  }
}
?>