<?php
/**
 * Centralização de funções que tratam com sessões
 *
 * @author Alex Lunardelli
 */
include_once 'class.wTools.php';
include_once 'adapter.produtos.php';
include_once 'class.frete.php';
include_once 'class.descontos.php';

class carrinho {
  public $sUsuarioSessao;
  public $iQntProdRestantes;
  public $iIdCliente;
  public $aTotais;
  public $oUtil;
  public $oProdutos;
  public $oCarrinho;
  public $aMsg = array();
  
  public $CD_CARRINHO; 
  public $CD_PAGSEGURO; 
  public $SQ_CARRINHO; 
  public $CD_SIT; 
  public $CD_PAGAMENTO; 
  public $NU_ITENS; 
  public $ID_CLIENTE; 
  public $ID_END_ENTREGA; 
  public $VL_ITEM; 
  public $VL_ADICIONAL; 
  public $VL_TAXAS; 
  public $VL_DESCONTO; 
  public $VL_FRETE; 
  public $VL_TOTAL; 
  public $CD_NF; 
  public $TX_OBS; 
  public $DT_CRIACAO; 
  public $HR_CRIACAO; 
  public $DT_FECHAMENTO; 
  public $HR_FECHAMENTO; 
  public $CD_TIPO_ENTREGA; 
  public $DE_ENTREGA;
  
  public $NM_LOGRADOURO; 
  public $TP_LOGRADOURO; 
  public $TX_NUMERO; 
  public $TX_COMPLEMENTO; 
  public $NU_CEP; 
  public $TX_BAIRRO; 
  public $NM_UF; 
  public $NM_CID;
  
  public $NM_CLIENTE;
  public $NM_SOBRENOME;

  public $NR_NF;
  public $DT_FINALIZACAO;

  public $DT_COLETA;
  public $ID_TRANSPORTADORA; 
  public $OBS_COLETA; 
  public $CD_CANHOTO; 

  public $NM_TRANSPORTADORA; 
  public $TX_TEL_TRANSPORTADORA; 
  public $ID_ENDERECO; 
  public $TX_OBS_TRANSPORTADORA; 
  
  public $oCliente;
  private $bDebug;

  public $aParamDesc = array('sAvisoDescontoQnt');

  public $iLinhas;
  
  public function __construct() {
    include 'conecta.php';
    include_once 'class.tc_carrinho.php';
    $this->DB_LINK = $link;

    $this->sUsuarioSessao = self::getUsuarioSessao();
    $this->oUtil       = new wTools();
    $this->oProdutos   = new produtos();                                                        
    $this->oCarrinho   = new tc_carrinho();
    $_SESSION[$this->sUsuarioSessao]['navegacao'] = array('sUltimaPagina' => '',
                                                           'sUrlBackPage' => (isset($_POST['CMPsUrlBackPage']) ? $_POST['CMPsUrlBackPage'] : $this->oUtil->sUrlBase)
                                                          );
    $this->bDebug = false;
  }
  
  public function listar($sFiltro) {
    
    $sSql = 'SELECT * 
               FROM v_pedidos
              '.$sFiltro;

    $sResultado = mysql_query($sSql, $this->DB_LINK);
    if (!$sResultado) {
      die('Erro ao criar a listagem: ' . mysql_error());
      return false;
    }

    $this->iLinhasConsulta = mysql_num_rows($sResultado);

    $iIdCarrinho = 0;
    $i = 0;
    while ($aResultado = mysql_fetch_array($sResultado)) {
      $iIdCliente = $aResultado['id_cliente'];

      if ($iIdCarrinho != $aResultado['id']) {
        $i++;

        $this->ID[$i]             = $aResultado['id']; 
        $this->CD_CARRINHO[$i]    = $aResultado['cd_carrinho']; 
        $this->CD_PAGSEGURO[$i]   = $aResultado['cd_pagseguro']; 
        $this->SQ_CARRINHO[$i]    = $aResultado['sq_carrinho']; 
        $this->CD_SIT[$i]         = $aResultado['cd_sit']; 
        $this->CD_PAGAMENTO[$i]   = $aResultado['cd_pagamento']; 
        $this->NU_ITENS[$i]       = $aResultado['nu_itens']; 
        $this->ID_CLIENTE[$i]     = $aResultado['id_cliente']; 
        $this->ID_END_ENTREGA[$i] = $aResultado['id_end_entrega']; 
        $this->VL_ITEM[$i]        = $aResultado['vl_item']; 
        $this->VL_ADICIONAL[$i]   = $aResultado['vl_adicional']; 
        $this->VL_TAXAS[$i]       = $aResultado['vl_taxas']; 
        $this->VL_DESCONTO[$i]    = $aResultado['vl_desconto']; 
        $this->VL_FRETE[$i]       = $aResultado['vl_frete']; 
        $this->VL_TOTAL[$i]       = $aResultado['vl_total']; 
        $this->CD_NF[$i]          = $aResultado['cd_nf']; 
        $this->TX_OBS[$i]         = $aResultado['tx_obs']; 
        $this->DT_CRIACAO[$i]     = $aResultado['dt_criacao']; 
        $this->HR_CRIACAO[$i]     = $aResultado['hr_criacao']; 
        $this->DT_FECHAMENTO[$i]  = $aResultado['dt_fechamento']; 
        $this->HR_FECHAMENTO[$i]  = $aResultado['hr_fechamento']; 
        $this->CD_TIPO_ENTREGA[$i]= $aResultado['cd_tipo_entrega']; 
        $this->DE_ENTREGA[$i]     = $aResultado['de_entrega']; 
        
        $this->NM_LOGRADOURO[$i]  = $aResultado['nm_logradouro']; 
        $this->TP_LOGRADOURO[$i]  = $aResultado['tp_logradouro']; 
        $this->TX_NUMERO[$i]      = $aResultado['tx_numero']; 
        $this->TX_COMPLEMENTO[$i] = $aResultado['tx_complemento']; 
        $this->NU_CEP[$i]         = $aResultado['nu_cep']; 
        $this->TX_BAIRRO[$i]      = $aResultado['tx_bairro']; 
        $this->NM_UF[$i]          = $aResultado['nm_uf']; 
        $this->NM_CID[$i]         = $aResultado['nm_cid'];
        
        $this->NM_CLIENTE[$i]     = $aResultado['nm_cliente']; 
        $this->NM_SOBRENOME[$i]   = $aResultado['nm_sobrenome']; 

        $this->NR_NF[$i]          = $aResultado['nr_nf']; 
        $this->DT_FINALIZACAO[$i] = $aResultado['dt_finalizacao']; 
        
        $this->DT_COLETA[$i]         = $aResultado['dt_coleta']; 
        $this->ID_TRANSPORTADORA[$i] = $aResultado['id_transportadora']; 
        $this->OBS_COLETA[$i]        = $aResultado['obs_coleta']; 
        $this->CD_CANHOTO[$i]        = $aResultado['cd_canhoto']; 
        
        $this->NM_TRANSPORTADORA[$i]     = $aResultado['nm_transportadora']; 
        $this->TX_TEL_TRANSPORTADORA[$i] = $aResultado['tx_tel_transportadora']; 
        $this->ID_ENDERECO[$i]           = $aResultado['id_endereco']; 
        $this->TX_OBS_TRANSPORTADORA[$i] = $aResultado['obs_transportadora']; 

		
        
        
      }
      
      $this->ITENS[$i]['nm_produto'][]    = $aResultado['nm_produto'];
      $this->ITENS[$i]['nu_quantidade'][] = $aResultado['nu_quantidade'];
      $this->ITENS[$i]['vl_desconto'][]   = 0;
      $this->ITENS[$i]['vl_unidade'][]    = $aResultado['vl_final'];
      $this->ITENS[$i]['vl_final'][]      = $aResultado['vl_final'] * $aResultado['nu_quantidade'];
      
      $iIdCarrinho = $aResultado['id'];
    }
    $this->iLinhas = $i;

  }


  public function criar($aIdProd) {
    $sFiltro = 'WHERE id IN ('.$this->oUtil->montarIN($aIdProd).')';
    $this->oProdutos->listar($sFiltro);

    $_SESSION[$this->sUsuarioSessao]['tmp_atv_carrinho'] = time();
    
    for ($i = 0; $i < $this->oProdutos->iLinhas; $i++) {

      if (!is_null($this->oProdutos->TP_DESCONTO[$i])) {      
        $oDesconto = descontos::carregarClasseDesconto($this->oProdutos->TP_DESCONTO[$i]);
        $oDesconto->calcularDescontoListagem(&$this->oProdutos);
        echo $this->oProdutos->TP_DESCONTO[$i];
      }

      $_SESSION[$this->sUsuarioSessao]['carrinho'][$this->oProdutos->ID[$i]] =  
              array( 'id' => $this->oProdutos->ID[$i],
                   'iQnt' => 1,
             'sNmProduto' => $this->oProdutos->NM_PRODUTO[$i],
                  'sLink' => $this->oProdutos->TX_LINK[$i],
              'sNmImagem' => $this->oProdutos->NM_IMAGEM_PRINCIPAL[$i],
                'fVlUnid' => $this->oProdutos->VL_FINAL[$i],
     'fDescUnidCalculado' => ($this->oProdutos->TP_DESCONTO[$i] == 'U') ? $this->oProdutos->VL_DESCONTO[$i] : 0,
            'sTpDesconto' => $this->oProdutos->TP_DESCONTO[$i],
               'fVlTotal' => $this->oProdutos->VL_FINAL[$i]
                  );
    }
      // @TODO: Salvar carrinho na hora em que ele foi criado
//      $sAcao = 'inserir';
//      $this->aMsg = $oManCarrinho->aMsg;
//      $oManCarrinho->CD_SIT[0] = 'AB';
//      $oManCarrinho->SQ_CARRINHO[0]    = $iSeqCarrinho;
//      $bSucesso = $oManCarrinho->inserir();
  }
  
  public function getCodigoCarrinho($iIdCliente, $iSeqCarrinho = null) {

    $this->bNovoCodigo = false;
    
    $aRet = $this->oUtil->pegaInfoDB('tc_clientes', 'sg_cliente', 'WHERE id = '.$iIdCliente);
    $sSgCliente = $aRet[0];
    
    // Se o código do carrinho já existir na sessão, retorna o valor
    if (isset($_SESSION[$this->sUsuarioSessao]['codigo_carrinho'])) {
      $sSgClienteSessao = substr($_SESSION[$this->sUsuarioSessao]['codigo_carrinho'], 0 , 3);
      
      // Valida se o código existente é do usuário que está logado
      if ($sSgClienteSessao == $sSgCliente) {
        $this->sCdCarrinho = $_SESSION[$this->sUsuarioSessao]['codigo_carrinho'];
        $this->bNovoCodigo = false;
        return true;        
      }
    }
    
    if (is_null($iSeqCarrinho)) {
      return false;
    }

    $sCdCarrinho = $sSgCliente.'-';
    $sCdCarrinho .= str_pad($iSeqCarrinho, 5, '0', STR_PAD_LEFT);
    
    if (isset($sCdCarrinho)) {
      $_SESSION[$this->sUsuarioSessao]['codigo_carrinho'] = $sCdCarrinho;
      $this->sCdCarrinho = $sCdCarrinho;
      return true;
    }
    return false;
  }


  public function alterarQuantidadeItensCarrinho($iIdProd, $sTpAcao) {
    if ($sTpAcao == 'adicionar') {
      $_SESSION[$this->sUsuarioSessao]['carrinho'][$iIdProd]['iQnt']++;
    } elseif ($sTpAcao == 'remover' && $_SESSION[$this->sUsuarioSessao]['carrinho'][$iIdProd]['iQnt'] != 1) {
      $_SESSION[$this->sUsuarioSessao]['carrinho'][$iIdProd]['iQnt']--;
    }

    $_SESSION[$this->sUsuarioSessao]['carrinho'][$iIdProd]['fVlTotal'] = 
            $_SESSION[$this->sUsuarioSessao]['carrinho'][$iIdProd]['fVlUnid'] * 
            $_SESSION[$this->sUsuarioSessao]['carrinho'][$iIdProd]['iQnt'];
    //$_SESSION[$this->sUsuarioSessao]['carrinho'][$iIdProd]['fVlTotal'] = $this->oUtil->parseValue($_SESSION[$this->sUsuarioSessao]['carrinho'][$iIdProd]['fVlTotal'], 'reais');
    $_SESSION[$this->sUsuarioSessao]['carrinho'][$iIdProd]['fVlTotal'] = $_SESSION[$this->sUsuarioSessao]['carrinho'][$iIdProd]['fVlTotal'];
  }


  public function adicionarProdCarrinho() {
  }
  
  public function contarItensRestantes() {
    if (isset($_SESSION[$this->sUsuarioSessao]['carrinho'])) {
      $this->iQntProdRestantes = count($_SESSION[$this->sUsuarioSessao]['carrinho']);
    } else {
      $this->iQntProdRestantes = 0;
    }
    return $this->iQntProdRestantes;
  }

  public function removerProdCarrinho($iIdProd) {
    unset($_SESSION[$this->sUsuarioSessao]['carrinho'][$iIdProd]);
    $this->contarItensRestantes();
    
    // Apaga carrinho caso não exista mais nenhum item 
    if ($this->iQntProdRestantes == 0) {
      unset($_SESSION[$this->sUsuarioSessao]['carrinho']);
    }
  }
  
  public function excluirCarrinho() {
    if (isset($_SESSION[$this->sUsuarioSessao]['carrinho']))
      unset($_SESSION[$this->sUsuarioSessao]['carrinho']);
    
    if (isset($_SESSION[$this->sUsuarioSessao]['codigo_carrinho'])) 
      unset($_SESSION[$this->sUsuarioSessao]['codigo_carrinho']);
  }
  
  public static function getUsuarioSessao() {
    return 'INFO';
  }
  
  public function apresentarProdutosCarrinho() {
    $this->contarItensRestantes();
    $this->calcularTotais();

    $bPossuiItens = ($this->iQntProdRestantes > 0) ? true : false;
    if ($bPossuiItens) {
      ?>
      <div id="lista-itens-carrinho">
        <table class="box-01" cellspacing="0">
          <tr class="cabecalho f80" style="text-align: right; padding: 10px">
            <td class="cabecalho w20" style="text-align: left">Produto</td>
            <td class="w20">&nbsp;</td>
            <td class="w20" style="text-align: center">Quantidade</td>
            <td class="w10" style="text-align: center">Remover</td>
            <td class="w10">Preço Un.</td>
            <td class="w10">Desconto</td>
            <td class="w10">Total</td>
          </tr>
          <?php
            foreach ($_SESSION[$this->sUsuarioSessao]['carrinho'] as $iIdProd => $aDados) {
              $iQnt = $aDados['iQnt'];
              $fTotal = $aDados['fVlUnid'];
              ?>
              <tr id="conteiner-produto-<?php echo $iIdProd; ?>" class="conteudo f80" style="text-align: right">
                <td style="text-align: left;">
                  <img style="width: 140px; height: 120px " src="<?php echo $this->oUtil->sUrlBase;?>/comum/imagens/produtos/<?php echo $aDados['sNmImagem']; ?>" alt="" />
                </td>
                <td><a href="<?php echo $this->oUtil->sUrlBase; ?>/produtos-detalhe/<?php echo $aDados['sLink']; ?>"><?php echo $aDados['sNmProduto']; ?></a></td>
                <td>
                  <table style="text-align: center">
                    <tr>
                      <td><img id="adicionar-item-<?php echo $iIdProd; ?>" style="text-align: center" src="<?php echo $this->oUtil->sUrlBase;?>/comum/imagens/icones/up.gif" alt="Adicionar" class="bt_img alterarQuantidadeItensCarrinho"/></td>
                    </tr>
                    <tr>
                      <td>
                        <input style="text-align: center;" type="text" class="w30" style="text-align: right" id="CMPiQnt-<?php echo $iIdProd; ?>" name="CMPiQnt-<?php echo $iIdProd; ?>" value="<?php echo $iQnt; ?>"/>
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <img id="remover-item-<?php echo $iIdProd; ?>" style="text-align: center;" src="<?php echo $this->oUtil->sUrlBase;?>/comum/imagens/icones/down.gif" alt="Remover" class="bt_img alterarQuantidadeItensCarrinho"/>
                        <br />
                        <span id="sAvisoDescontoQnt-<?php echo $iIdProd; ?>"class="blink" style="font-size: 12px">&nbsp;</span>
                      </td>
                    </tr>
                  </table>
                </td>
                <td style="text-align: center;">
                  <img id="CMPremover-produto-<?php echo $iIdProd; ?>" src="<?php echo $this->oUtil->sUrlBase; ?>/comum/imagens/icones/cross.png" class="bt_img removerItemCarrinhoSessao" />
                </td>
                <td>R$ <?php echo $this->oUtil->parseValue($aDados['fVlUnid'], 'reais'); ?></td>
                <td>R$ <?php echo $this->oUtil->parseValue($aDados['fDescUnidCalculado'], 'reais'); ?></td>
                <td id="HTMLfVlTotal-<?php echo $iIdProd; ?>">R$ <?php echo $this->oUtil->parseValue($aDados['fVlTotal'], 'reais'); ?></td>
              </tr>
          <?php
            }
          ?>
          <tr>
            <td colspan="4">&nbsp;</td>
            <td colspan="1" class="f90" style="text-align: right;">Subtotal</td>
            <td colspan="2" class="f70" style="text-align: right;">
              <span class="vl_produtos">
                R$ <?php echo $this->oUtil->parseValue($this->aTotais['produtos'],'reais'); ?>
              </span>
            </td>
          </tr>
          <tr>
            <td colspan="4">&nbsp;</td>
            <td colspan="1" class="f90" style="text-align: right;">Desconto</td>
            <td colspan="2" class="f70" style="text-align: right;">
              <span class="vl_descontos">
                R$ <?php echo $this->oUtil->parseValue($this->aTotais['descontos'],'reais'); ?>
              </span>
            </td>
          </tr>
          <tr>
            <td colspan="4">&nbsp;</td>
            <td colspan="1" class="f90" style="text-align: right;">Frete</td>
            <td colspan="2" class="f70" style="text-align: right;">
              <span class="vl_frete">
                R$ <?php echo $this->oUtil->parseValue($this->aTotais['frete'],'reais'); ?>
              </span>
            </td>
          </tr>
          <tr>
            <td colspan="4">&nbsp;</td>
            <td colspan="1" class="f90" style="text-align: right;"><b>Total</b></td>
            <td colspan="2" style="text-align: right;">
              <span class="vl_total">
                R$ <?php echo $this->oUtil->parseValue($this->aTotais['total'],'reais'); ?>
              </span></td>
          </tr>
        </table>
      </div>
    <?php
    } else {
      echo $this->aviso('Seu carrinho esta vazio');
    }
  }
  
  public function aviso($sMsg) { 
    ob_start();
    ?>
    <div class="box-01" style="padding: 50px">
      <?php echo $sMsg; ?>
    </div>
    <?php
    $sRet = ob_get_clean();
    return $sRet;
  }

  function calcularFrete() {
    return 0;
  }
  
  function calcularTotais($sCdOrigInfo = 'sessao') {
    $this->aTotais['produtos']  = 0;
    $this->aTotais['descontos'] = 0;
    $this->aTotais['frete']     = 0;
    $this->aTotais['total']     = 0;

    
    if (isset($_SESSION[$this->sUsuarioSessao]['carrinho'])) {
      
      if ($sCdOrigInfo == 'sessao') {




        // Calcula valor total dos produtos
        foreach ($_SESSION[$this->sUsuarioSessao]['carrinho'] as $aDadosSessao) {

          $fDesconto = 0;
          //Calcula valor total de descontos
          if (!empty($aDadosSessao['sTpDesconto'])) {

            
            $oDesconto = descontos::carregarClasseDesconto($aDadosSessao['sTpDesconto']);
            $oDesconto->setUsuarioSessao($this->sUsuarioSessao);
            $oDesconto->calcularDescontoTotais($aDadosSessao);
            $fDesconto = $oDesconto->fDesconto;
            
            $oDesconto->criarParametros();
            $this->aParamDesc['sAvisoDescontoQnt'][$aDadosSessao['id']] = $oDesconto->aParamDesc['sAvisoDescontoQnt'];

            //$this->aParamDesc[$aDadosSessao['id']] = $oDesconto->aParamDesc;
            //$fDesconto = $aDadosSessao['fDescUnidCalculado'] * $aDadosSessao['iQnt'];
          } else {
            $this->aParamDesc['sAvisoDescontoQnt'][$aDadosSessao['id']] = '&nbsp;';

          }
          
          
          $this->aTotais['descontos'] += $fDesconto;
          $this->aTotais['produtos']  += ($aDadosSessao['fVlUnid'] * $aDadosSessao['iQnt']) + $fDesconto;
        }


      } elseif ($sCdOrigInfo == 'banco') {
        
        $aSubConstulta = array();
        $sSubConstulta = '';
        foreach ($_SESSION[$this->sUsuarioSessao]['carrinho'] as $aDadosSessao) {

          for ($i = 0; $i < $aDadosSessao['iQnt']; $i++) {

            $aSubConstulta[] = "SELECT vl_final
                                  FROM v_produtos 
                                 WHERE id = ".$aDadosSessao['id']."\n";
          }

        }

        $sSubConstulta = implode(" union all \n", $aSubConstulta);

        $sSQL = 'SELECT SUM(vl_final) 
                   FROM (
                          '.$sSubConstulta.'
                        ) vl_total ';
      }
    }

    if (isset($_SESSION[$this->sUsuarioSessao]['login']['id_usu'])) {
      $iIdCliente = $_SESSION[$this->sUsuarioSessao]['login']['id_usu'];
      $aRet = $this->oUtil->pegaInfoDB('tc_clientes_enderecos', 'nm_cid, nm_uf', 'WHERE id_cliente = '.$iIdCliente);
      $oFrete = frete::carregarClasseFrete();
      $fValorFrete = $oFrete->calcularFretePorEndereco($aRet[0], $aRet[1]);
      $this->aTotais['frete'] = $fValorFrete;
    }

    $this->aTotais['total'] = 
      $this->aTotais['produtos'] - 
      $this->aTotais['descontos'] + 
      $this->aTotais['frete'];
  }
  
  function apresentarResumoCarrinho() {
    $iQntItens = $this->contarItensRestantes();?>
    <span class="f70">
      <?php
        if ($iQntItens > 0 ) {
          $this->calcularTotais();
          echo '<span class="qnt-prod-pestantes">'.$iQntItens .' itens</span> <br />';
          echo 'Total <span class="vl_total"> R$ '.$this->oUtil->parseValue($this->aTotais['total'], 'reais').'</span>';
        } else {
          echo 'Vazio';
        }  ?>
    </span><?php

  }
  /* carrinho::pagamento
   *
   * @date 24/03/2013
   * @param  
   * @param  
   * @return 
   */
  function checkoutPagamento($bLogado) {
    
    try {
      if (!isset($_SESSION[carrinho::getUsuarioSessao()]['login']['id_usu'])) {
        $aMsgExcecao = array('Falha 01 Checkout', 'Tentativa de acesso sem sessão','CHECKOUT_SEM_SESSAO', 0);
        throw new excecoes(99);
      }

      $iIdCliente = $_SESSION[carrinho::getUsuarioSessao()]['login']['id_usu'];

      if (!$bLogado) {
        $aMsgExcecao = array('Falha 02 Checkout', 'Tentativa de acesso sem login de usuário válido','CHECKOUT_SEM_LOGIN', 0);
        throw new excecoes(99);
      }

      $iIdCliente = $this->oUtil->anti_sql_injection($_SESSION[carrinho::getUsuarioSessao()]['login']['id_usu']);

      if (!is_numeric($iIdCliente)) {
        $aMsgExcecao = array('Falha 03 Checkout', 'Código de cliente não numérico','ERRO_ID_CLI', 0);
        throw new excecoes(99);
      }


      
      if (!$this->salvarCarrinho($iIdCliente)) {
        $aMsgExcecao = array($this->aRetMsgExcecao[0], $this->aRetMsgExcecao[1],$this->aRetMsgExcecao[2], $iIdCliente);
        throw new excecoes(99);
      }


      if (!$this->salvarItensCarrinho($this->iIdCarrinho)) {
        $aMsgExcecao = array($this->aRetMsgExcecao[0], $this->aRetMsgExcecao[1],$this->aRetMsgExcecao[2], $iIdCliente);
        throw new excecoes(99);
      }
      
      if (!$this->atualizarTotaisCarrinho($iIdCliente, $this->iIdCarrinho, $this->aTotaisCarrinho)) {
        $aMsgExcecao = array($this->aRetMsgExcecao[0], $this->aRetMsgExcecao[1],$this->aRetMsgExcecao[2], $iIdCliente);
        throw new excecoes(99);
      }


      


    } catch (Exception $e) {
      $e->sNmLog = $aMsgExcecao[0];
      $e->sTxLog = $aMsgExcecao[1];
      $e->sCdLog = $aMsgExcecao[2];
      $e->iCdUsu = $aMsgExcecao[3];
      $e->getErrorByCode();
    }
    
    $this->enviarTransacaoPagSeguro($iIdCliente);
    
    
  }
  
  private function salvarCarrinho($iIdCliente) {

    // Testa se o carrinho existe antes de tentar salvar
    if (!isset($_SESSION[$this->sUsuarioSessao]['carrinho'])) {
      $this->aRetMsgExcecao = array('Falha 05 Checkout', 'Carrinho não existe','SEM_ITENS_CARRINHO');
      return false;
    }

    // Cria ou testa o código do carrinho
    $aRet = $this->oUtil->pegaInfoDB('tc_carrinho', 'IFNULL( max(sq_carrinho) + 1, 1 )', 'WHERE id_cliente = '.$iIdCliente);
    
    $iSeqCarrinho = $aRet[0];
    
    // Pega o código do carrinho
    if (!$this->getCodigoCarrinho($iIdCliente, $iSeqCarrinho)) {
      $this->aRetMsgExcecao = array('Falha 10 Checkout', '','ERRO_CRIAR_CD_CLI', 0);
      return false;
    }
    $sCdCarrinho = $this->sCdCarrinho;


    // Busca os dados do endereço do cliente
    $aRet = $this->oUtil->pegaInfoDB('tc_clientes_enderecos', 'IFNULL(id , 0)', 'WHERE id_cliente = '.$iIdCliente);
    if (!isset($aRet[0])) {
      $this->aRetMsgExcecao = array('Falha 06 Checkout', 'Cliente não possui endereço cadastrado','CLI_SEM_ENDERECO');
      return false;
    }
    $iIdEndereco = $aRet[0];

    // Busca os dados do carrinho, caso não exista ele será criado (salvo na tabela tc_carrinho)
    $oManCarrinho = new tc_carrinho();
    $sFiltro  = 'WHERE id_cliente = '.$iIdCliente;
    $sFiltro .= "  AND cd_carrinho = '".$sCdCarrinho."'";
    $oManCarrinho->listar($sFiltro);


    $oManCarrinho->CD_CARRINHO[0]    = $sCdCarrinho;
    $oManCarrinho->CD_PAGSEGURO[0]   = '';
    $oManCarrinho->CD_PAGAMENTO[0]   = '';
    $oManCarrinho->NU_ITENS[0]       = 0;
    $oManCarrinho->ID_CLIENTE[0]     = $iIdCliente;
    $oManCarrinho->ID_END_ENTREGA[0] = $iIdEndereco;
    $oManCarrinho->VL_ITEM[0]        = 0;
    $oManCarrinho->VL_ADICIONAL[0]   = 0;
    $oManCarrinho->VL_TAXAS[0]       = 0;
    $oManCarrinho->VL_DESCONTO[0]    = 0;
    $oManCarrinho->VL_FRETE[0]       = 0;
    $oManCarrinho->VL_TOTAL[0]       = 0;


    if ($oManCarrinho->iLinhas > 0) {
      $sAcao = 'editar';
      $this->aMsg = $oManCarrinho->aMsg;
      $sFiltro  = 'WHERE id_cliente = '.$iIdCliente;
      $sFiltro .= "  AND cd_carrinho = '$sCdCarrinho'";
      $bSucesso = $oManCarrinho->editar($sFiltro);
      $this->iIdCarrinho = $oManCarrinho->ID[0];
    } else {
      
      // Salva carrinho
      $sAcao = 'inserir';
      $this->aMsg = $oManCarrinho->aMsg;
      $oManCarrinho->CD_SIT[0] = 'AB';
      $oManCarrinho->SQ_CARRINHO[0]    = $iSeqCarrinho;
      $bSucesso = $oManCarrinho->inserir();
      
      // Traz os dados salvos
      $sFiltro  = 'WHERE id_cliente = '.$iIdCliente;
      $sFiltro .= "  AND cd_carrinho = '$sCdCarrinho'";
      $oManCarrinho->listar($sFiltro);
      $this->iIdCarrinho = $oManCarrinho->ID[0];
    }

    if ($oManCarrinho->aMsg['iCdMsg'] != 0) {
      $this->aRetMsgExcecao = array('Falha 11 Checkout', 'Falha ao '.$sAcao.' um carrinho.','ERRO_ATUALIZAR_CARRINHO');
      return false;      
    }

    if (!isset($this->iIdCarrinho)) {
      $this->aRetMsgExcecao = array('Falha 07 Checkout', 'Código identificador do carrinho não foi encontrado','ID_CARRINHO_NULL');
      return false;
    }
    
    return $bSucesso;
  }
  
  private function salvarItensCarrinho($iIdCarrinho) {
    $oManItensCarrinho = new tr_carrinho_itens();

    $iQntItens = 0;
    $aTotais['VL_ITEM']       = 0;
    $aTotais['VL_ADICIONAL']  = 0;
    $aTotais['VL_TAXAS']      = 0;
    $aTotais['VL_DESCONTO']   = 0;
    $aTotais['VL_FRETE']      = 0;
    $aTotais['VL_TOTAL']      = 0;

    $oManItensCarrinho->remover('WHERE id_carrinho = '.$iIdCarrinho);

    if (isset($_SESSION[carrinho::getUsuarioSessao()]['carrinho'])) {
      foreach ($_SESSION[carrinho::getUsuarioSessao()]['carrinho'] as $iChave => $aDados) {
        $oManItensCarrinho->ID_PROD[0]       = $aDados['id'];
        $oManItensCarrinho->ID_CARRINHO[0]   = $iIdCarrinho;
        $oManItensCarrinho->NU_QUANTIDADE[0] = $aDados['iQnt'];
        $oManItensCarrinho->VL_FINAL[0]      = $aDados['fVlUnid'];
        $oManItensCarrinho->inserir();

        if ($oManItensCarrinho->aMsg['iCdMsg'] != 0) {
          $this->aRetMsgExcecao = array('Falha 08 Checkout', 'Falha ao salvar produtos do carrinho','ERRO_ADD_ITEM_CARRINHO');
          return false;
        }
        
        // Calcula os totais
        $iQntItens++;
        $aTotais['VL_ITEM']       += $aDados['fVlUnid'] * $aDados['iQnt'];
        $aTotais['VL_ADICIONAL']  += 0;
        $aTotais['VL_TAXAS']      += 0;
        $aTotais['VL_DESCONTO']   += 0;
        $aTotais['VL_FRETE']      += 0;
      }
        $aTotais['VL_TOTAL']      += 
            $aTotais['VL_ITEM']
          + $aTotais['VL_ADICIONAL']
          + $aTotais['VL_TAXAS']
          + $aTotais['VL_DESCONTO']
          + $aTotais['VL_FRETE'];
    }
    $aTotais['NU_ITENS'] = $iQntItens;
    
    $this->aTotaisCarrinho = $aTotais;
    
    return true;
  }
  
  private function atualizarTotaisCarrinho ($iIdCliente, $iIdCarrinho, $aDados) {
    
      $sQuery = "UPDATE tc_carrinho
        SET
          nu_itens       = ".$aDados['NU_ITENS'].", 
          vl_item        = ".$aDados['VL_ITEM'].", 
          vl_adicional   = ".$aDados['VL_ADICIONAL'].", 
          vl_taxas       = ".$aDados['VL_TAXAS'].", 
          vl_desconto    = ".$aDados['VL_DESCONTO'].", 
          vl_frete       = ".$aDados['VL_FRETE'].", 
          vl_total       = ".$aDados['VL_TOTAL']."
          WHERE id_cliente = ".$iIdCliente."
            AND id = ".$iIdCarrinho;
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


  private function enviarTransacaoPagSeguro($iIdCliente) {
    require_once "PagSeguroLibrary/PagSeguroLibrary.php";
    include "config.php";
    
    if (!isset($this->sCdCarrinho)) {
      $this->getCodigoCarrinho($iIdCliente);
    }

    $paymentRequest = new PagSeguroPaymentRequest();  
    
    // Testa se carrinho já foi enviado
    if (false) {
      $this->aRetMsgExcecao = array('Falha 09 Checkout', 'Tentativa de novo envio de dados ao PagSeguro','PAGSEG_ENVIO_DUPLO');
      return false;
    }

    // Dados do Cadastro do cliente
    $oCliente = $this->buscarDadosCliente($iIdCliente);
    
    $i = 0;
    foreach ($_SESSION[carrinho::getUsuarioSessao()]['carrinho'] as $iIdProd => $aDados) {
     $aItem = array( 'id' => str_pad(++$i, 4, 0, STR_PAD_LEFT),  
            'description' => $aDados['sNmProduto'],
               'quantity' => $aDados['iQnt'],   
                 'amount' => $this->oUtil->parseValue($aDados['fVlTotal'], 'moeda-db'),  
                 'weight' => 1000,  
           'shippingCost' => 2.00 );
      $paymentRequest->addItem($aItem);
    }
    
    $sTelefone = $oCliente->oCli->TX_TEL[0];
    $sTelefone = str_replace(array('(',')','-'), '', $sTelefone);
    $aDadosComprador = array( 'name' => $oCliente->oCli->NM_CLIENTE[0].' '.$oCliente->oCli->NM_SOBRENOME[0],
                             'email' => $oCliente->oCli->TX_EMAIL[0],
                          'areaCode' => substr($sTelefone,0,2),
                            'number' => substr($sTelefone,2,10),
    );
    $paymentRequest->setSender($aDadosComprador);  

    $aDadosEntrega = array( 'postalCode' => $oCliente->oEnd->NU_CEP[0],  
                                'street' => $oCliente->oEnd->NM_LOGRADOURO[0].' '.$oCliente->oEnd->NM_LOGRADOURO[0],
                                'number' => $oCliente->oEnd->TX_NUMERO[0],
                            'complement' => $oCliente->oEnd->TX_COMPLEMENTO[0],
                              'district' => $oCliente->oEnd->TX_BAIRRO[0],
                                  'city' => $oCliente->oEnd->NM_CID[0],
                                 'state' => $oCliente->oEnd->NM_UF[0],
                               'country' => 'BRA'
                     );
    $paymentRequest->setShippingAddress($aDadosEntrega);
    $paymentRequest->setShippingType(1);
    $paymentRequest->setCurrency("BRL");  
    $paymentRequest->setReference($this->sCdCarrinho);
    $paymentRequest->setExtraAmount("0.00");

    // Debug
    if ($this->bDebug) {
      
      echo $this->sCdCarrinho."<br />";
      echo $aDadosComprador['name']."<br />";
      echo $aDadosComprador['email']."<br />";
      echo $aDadosComprador['areaCode']."<br />";
      echo $aDadosComprador['number']."<br />";

      echo $aDadosEntrega['postalCode']."<br />";
      echo $aDadosEntrega['street']."<br />";
      echo $aDadosEntrega['number']."<br />";
      echo $aDadosEntrega['complement']."<br />";
      echo $aDadosEntrega['district']."<br />";
      echo $aDadosEntrega['city']."<br />";
      echo $aDadosEntrega['state']."<br />";
      echo $aDadosEntrega['country']."<br />";

    }


    $aRet = $this->oUtil->buscarParametro(array ('PG_RET_PS'));
    $sUrlRetorno = $aRet['PG_RET_PS'][0].'?code='.$this->sCdCarrinho.'/';
    $paymentRequest->setRedirectURL($sUrlRetorno);

    $paymentRequest->setMaxAge(172800); // 2 dias  

    $credentials = $this->buscarCredenciais();

    $this->atualizarSituacaoCarrinho('EP', $iIdCliente, $this->sCdCarrinho);
    
    
    //$url = $paymentRequest->register($credentials);
    //header("location: $url");
  }
  



  public function consultarTransacoesPorCodigo($sIdTransaction) {
    
    //echo $oTransaction->getReference();
    
    require_once "PagSeguroLibrary/PagSeguroLibrary.php";

    /* Definindo as credenciais  */    
    $credentials = $this->buscarCredenciais();
      
    /* Código identificador da transação  */    
    $transaction_id = $sIdTransaction;  
      
    /*  
        Realizando uma consulta de transação a partir do código identificador  
        para obter o objeto PagSeguroTransaction 
    */   
    $transaction = PagSeguroTransactionSearchService::searchByCode(  
        $credentials,  
        $transaction_id  
    );
    
    return $transaction;
  }
  
  public function atualizarSituacaoCarrinho($sSit, $iIdCliente, $sCdCarrinho) {
    $sQuery = "UPDATE tc_carrinho
                  SET cd_sit = '".$sSit."'
                WHERE cd_carrinho = '".$sCdCarrinho."'
                  AND id_cliente = ".$iIdCliente;
    $sResultado = mysql_query($sQuery, $this->DB_LINK);

    if (!$sResultado) {
      $this->iCdMsg = 1;
      $this->sMsg  = 'Ocorreu um erro ao atualizar a situação do carrinho.';
      $this->sErro = mysql_error();
      $this->sResultado = 'erro';
      $bSucesso = false;

    } else {
      $this->iCdMsg = 0;
      $this->sMsg  = 'A situação do carrinho foi atualizada com sucesso!';
      $this->sResultado = 'sucesso';
      $bSucesso = true;
    }
    // Monta array com mensagem de retorno
    $this->aMsg = array('iCdMsg' => $this->iCdMsg,
                          'sMsg' => $this->sMsg,
                    'sResultado' => $this->sResultado );
    return $bSucesso;
  }
  
  public function confirmacao($sCdCarrinho = null, $sIdTransaction = null) {
    include 'config.php';
    try {

      if (is_null($sCdCarrinho) || is_null($sIdTransaction)) {
        throw new Exception;
      }

      $sCdCarrinho    = str_replace('/','', $this->oUtil->anti_sql_injection($sCdCarrinho));
      $sIdTransaction = $this->oUtil->anti_sql_injection($sIdTransaction);
      $this->oItensCarrinho = new tr_carrinho_itens();

      $sFiltro = "WHERE cd_carrinho = '".$sCdCarrinho."'";
      $this->oCarrinho->listar($sFiltro);
      if ($this->oCarrinho->iLinhas == 0) {
        $sMsg = 'Carrinho com o código ['.$sCdCarrinho.'] não existe';
        throw new Exception;
      }
      
      $sFiltroItens = "WHERE id_carrinho = ".$this->oCarrinho->ID[0];
      $this->oItensCarrinho->listar($sFiltroItens);
      if ($this->oCarrinho->CD_SIT[0] != 'EP') {
        $sMsg = 'O status da sua compra é: '.$CFGaCodSitPedido[$this->oCarrinho->CD_SIT[0]];
        throw new Exception;
      }
      
      $this->oCarrinho->CD_SIT[0]       = 'AC';
      $this->oCarrinho->CD_PAGSEGURO[0] = $sIdTransaction;
      $this->oCarrinho->editar($sFiltro);  

    } catch (Exception $exc) {
      $aDados = array('cd_carrinho' => $sCdCarrinho,
                      'id_transaction' => $sIdTransaction );
      $sTxLog = $this->oUtil->montarStringDados($aDados);

      $this->oLog  = new tl_geral();
      $this->oLog->NM_LOG[0]   = (isset($sMsg)) ? $sMsg : 'Falha na Confirmação de dados do Checkout';
      $this->oLog->TX_LOG[0]   = $sTxLog;
      $this->oLog->CD_LOG[0]   = 'ERRO_DADOS_TRANSACAO';
      $this->oLog->CD_ACAO[0]  = 'L';
      $this->oLog->TX_IP[0]    = $_SERVER['REMOTE_ADDR'];
      $this->oLog->TX_TRACE[0] = $_SERVER['REQUEST_URI'];
      $this->oLog->ID_USU[0]   = isset($this->iIdCliente) ? $this->oUtil->anti_sql_injection($this->iIdCliente) : 0;
      $this->oLog->inserir();

      $this->excluirCarrinho();

      return false;
    }

    $this->excluirCarrinho();
    return true;
  }
  
  private function buscarDadosCliente($iIdCliente) {

    $oCliente = new clientes();
    $oCliente->listar('WHERE id = '.$iIdCliente);
    
    return $oCliente;
  }

  private function buscarCredenciais() {
    include "config.php";    
    $aRet = $this->oUtil->buscarParametro(array ('TOKENPS', 'PG_RET_PS'));
    $credentials = new PagSeguroAccountCredentials(  
      $CFGsEmailPagSeguro, 
      $aRet['TOKENPS'][0]    
    );
    return $credentials;
  }
  
  /* carrinho::baixarPedidoInatividade
   *
   * Busca todos os pedidos que já estão inativos a mais tempo que o parâmetro
   * configurado.
   *
   * @date 19/04/2013
   * @param  
   * @return 
   */
  public function baixarPedidoInatividade() {
    include 'config.php';

    // Busca quantidade de dias antes de cancelar um pedido por inatividade
    $aParam = $this->oUtil->buscarParametro('CANCELAR_PED_INATIVO');
    $iDias = $aParam['CANCELAR_PED_INATIVO'][0];


    $sSituacoes = implode("','", $CFGaGrupoPedidosPendentes);

    $sFiltro     = " WHERE cd_sit IN ('".$sSituacoes."') "."\n";  
    $sFiltroData = " AND DATE_FORMAT(STR_TO_DATE(dt_criacao, '%d/%m/%Y'), '%Y%m%d')  < (CURDATE() - ".$iDias.") "."\n";
    $sOrder      = " ORDER BY id DESC"."\n";

    $this->listar($sFiltro.$sFiltroData.$sOrder);

    $sTxLog = 'Total de '.$this->iLinhas.' pedidos inativos'."\n";
    for ($i = 1; $i < $this->iLinhas; $i++) {
      $sTxLog .= 'Criação '.$this->DT_CRIACAO[$i].' - Código '. $this->CD_CARRINHO[$i]."\n";
    }
    
    
    $sQuery  = "UPDATE tc_carrinho 
                   SET tx_obs = 'Pedido cancelado por inatividade.',
                       cd_sit = 'CI'
    ";
    $sQuery .= $sFiltro;
    $sQuery .= " AND DATE_FORMAT(STR_TO_DATE(dt_criacao, '%Y-%m-%d'), '%Y%m%d') < (CURDATE() - ".$iDias.") "."\n";

    $sResultado = mysql_query($sQuery, $this->DB_LINK);

    if (!$sResultado) {
      die('Erro ao criar a listagem: ' . mysql_error());
      return false;
    }
    
    $this->oLog  = new tl_geral();
    $this->oLog->NM_LOG[0]   = 'Cancelamento de pedidos por inatividade no dia '.date('d/m/Y H:i');
    $this->oLog->TX_LOG[0]   = $sTxLog;
    $this->oLog->CD_LOG[0]   = 'CANCELAMENTO_INATIVIDADE';
    $this->oLog->CD_ACAO[0]  = 'I';
    $this->oLog->TX_IP[0]    = $_SERVER['REMOTE_ADDR'];
    $this->oLog->TX_TRACE[0] = $_SERVER['REQUEST_URI'];
    $this->oLog->ID_USU[0]   = isset($this->iIdCliente) ? $this->oUtil->anti_sql_injection($this->iIdCliente) : 0;
    $this->oLog->inserir();
  }
  /* carrinho::finalizarPedido
   *
   * Última ação feita com um carrinho de compras.
   * Ao final do processo de compra e confirmação de pedidos, são feitas atualizações
   * de cadastro e a situação do pedido é atualizada aqui.
   *
   * @date 20/04/2013
   * @param  
   * @return 
   */
  public function finalizarPedido($iIdPedido) {
    try {
      mysql_query("START TRANSACTION", $this->DB_LINK);
      $sMsgComplementar = '';

      $bAtualizarSit = false;
      $sAtualizarSit = '';
      $sMsgAtualizarSit = 'Para que o pedido seja finalizado, é necessário preencher todos os campos obrigatórios.';

      if ($this->CD_NF[0] != '' && $this->ID_TRANSPORTADORA[0] != '' ) {
        $bAtualizarSit = true;
        $sAtualizarSit = " cd_sit = 'FI',";
        $sMsgAtualizarSit = '';
      }

      $sQuery = "UPDATE tc_carrinho
                    SET cd_nf = '".$this->CD_NF[0]."',
                        ".$sAtualizarSit."
                        tx_obs = '".$this->TX_OBS[0]."'
                  WHERE id = ".$iIdPedido;

      $sResultado = mysql_query($sQuery, $this->DB_LINK);
      if (!$sResultado) {
        $sMsgComplementar = ' Atualização do carrinho';
        throw new Exception;
      }
      
      // Salva os dados da coleta do produto feita por transportadora
      $oColetas = new tr_coletas();
      
      $sFiltro = " WHERE id_carrinho = ".$iIdPedido;
      $oColetas->listar($sFiltro);
      $sDtColeta = $this->DT_COLETA[0] == '' ? date('Y-m-d') : $this->oUtil->parseValue($this->DT_COLETA[0], 'dt-bd');
      $oColetas->DT_COLETA[0]         = $sDtColeta;
      $oColetas->ID_TRANSPORTADORA[0] = $this->ID_TRANSPORTADORA[0] != '' ? $this->ID_TRANSPORTADORA[0] : 0;
      $oColetas->TX_OBS[0]            = '';
      $oColetas->CD_CANHOTO[0]        = '';
      $oColetas->ID_CARRINHO[0]       = $iIdPedido;

      if ($oColetas->iLinhas > 0) {
        $oColetas->editar($sFiltro);
      } elseif ($oColetas->iLinhas == 0) {
        $oColetas->inserir();
      }

      if ($oColetas->aMsg['iCdMsg'] != 0) {
        $sMsgComplementar = ' Cadastro de Coleta';
        throw new Exception;
      }
      
      $this->iCdMsg = 0;
      $this->sMsg  = ($bAtualizarSit) ? 'O pedido de código ['.$_POST['CMPcd-Carrinho'].'] do cliente '.$_POST['CMPnm-Cliente'].' foi finalizado.' : $sMsgAtualizarSit;
      $this->sResultado = 'sucesso';
      $bSucesso = true;
      
      
      mysql_query('COMMIT', $this->DB_LINK);
    } catch (Exception $exc) {

      $this->iCdMsg = 1;
      $this->sMsg  = 'Ocorreu um erro ao tentar finalizar o pedido:<b>'.$sMsgComplementar.'</b>';
      $this->sErro = mysql_error();
      $this->sResultado = 'erro';
      $bSucesso = false;
      mysql_query('ROLLBACK', $this->DB_LINK);
      $bSucesso = false;
    }

    // Monta array com mensagem de retorno
    $this->aMsg = array('iCdMsg' => $this->iCdMsg,
                          'sMsg' => $this->sMsg,
                    'sResultado' => $this->sResultado );
    return $bSucesso;

  }
  
//  private function reiniParamDesc() {
//    $this->aParamDesc['sAvisoDescontoQnt'] = '&nbsp;';
//  }
  
  
}

?>
