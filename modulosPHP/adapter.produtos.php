<?php

  include_once 'class.tc_produtos.php';
  include_once 'class.tc_prod_medidas.php';
  include_once 'class.tc_prod_precos.php';
  include_once 'class.tr_prod_cat.php';
  include_once 'class.tr_prod_tag.php';
  include_once 'class.tc_prod_estoque.php';

  include_once 'class.wTools.php';
  
  require_once 'class.tc_imagens.php';
  require_once 'class.tr_prod_img.php';
  require_once 'class.upload.php';

  class produtos {
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
    public $tx_link;
    public $fDescUnidCalculado = 0;
    
    
    public $ID;
    public $NM_PRODUTO;
    public $CD_PRODUTO;
    public $DE_CURTA;
    public $DE_LONGA;
    public $CD_STATUS;
    public $NU_CLIQUES;
    public $NM_PRONUNCIA;
    public $ID_TIPO;
    public $ID_FABRICANTE;
    public $TX_LINK;
    
    // Preços
    public $VL_ADICIONAIS;
    public $VL_TAXAS;
    public $VL_CUSTO;
    public $PC_MARGEM;
    public $VL_FINAL;
    public $CD_VISIVEL;
    
    public $ID_DESCONTO;
    public $NM_DESCONTO;
    public $DE_DESCONTO;
    public $TP_VALOR;
    public $TP_DESCONTO;
    public $VL_MIN;
    public $VL_DESCONTO;
    
    //Categorias
    public $ID_CAT_AGRUPADO;
    public $NM_CATEGORIA_AGRUPADO;

    // Imagens
    public $ID_IMAGEM_AGRUPADO;
    public $NM_IMAGEM_AGRUPADO;
    public $NM_IMAGEM_PRINCIPAL;

    public $iLinhas;
    public $aMsg;
    
    // Váriaveis
    private $iQntItensVitrine;
    public $aProdVitrine;

    public function __construct() {
  
      include 'conecta.php';
      $this->DB_LINK = $link;
      $this->oUtil = new wTools();

      $this->oProduto    = new tc_produtos;
      $this->oMedidas    = new tc_prod_medidas;
      $this->oPrecos     = new tc_prod_precos;
      $this->oCategorias = new tr_prod_cat();
      $this->oTags       = new tr_prod_tag();
      $this->oEstoque    = new tc_prod_estoque();
      
      
    }


    public function salvar($aDados, $sAcao) {

      try {

        mysql_query("START TRANSACTION", $this->DB_LINK);
        
        $iCategoriasRelacionadas = 0;
        $iTagsRelacionadas = 0;

        if (isset($_POST['CMPcategorias'])) {
          $iCategoriasRelacionadas = count($_POST['CMPcategorias']);
        }
        if (isset($_POST['CMPtags'])) {
          $iTagsRelacionadas = count($_POST['CMPtags']);
        }

        $aValidar = array (0 => array('Nome'              , $_POST['CMPnome'],        ''      , true),
                           1 => array('Fabricante'        , $_POST['CMPfabricante'],  ''   , true),
                           2 => array('Descrição Breve'   , $_POST['CMPdeBreve'],     ''   , true),
                           3 => array('Descrição Completa', $_POST['CMPdeLonga'],     ''   , true),
                          10 => array('Categorias'        , $iCategoriasRelacionadas, 'faixa-baixa', true, '0', 'Selecione ao menos uma <b>Categoria</b> para o produto!'),
                          11 => array('Tags'              , $iTagsRelacionadas,       'faixa-baixa', true, '0', 'Selecione ao menos uma <b>Tag</b> para o produto!'),
                          20 => array('Largura(x)'        , $_POST['CMPlargura_x'],   'float', true),
                          21 => array('Largura(y)'        , $_POST['CMPlargura_y'],   'float', true),
                          22 => array('Altura'            , $_POST['CMPaltura'],      'float', true),
                          23 => array('Peso em Kg'        , $_POST['CMPpeso'],        'float', true),
                          40 => array('Preço de custo'    , $_POST['CMPprecoCusto'],  'moeda', true, '0'),
                          45 => array('Preço do final'    , $this->oUtil->parseValue($_POST['CMPvalorFinal'], 'moeda-bd'),  'faixa-baixa', true, '0', 'O <b>Preço Final</b> do produto deve ser calculado! '),
            
                          
            );

        
        if ($this->oUtil->valida_Preenchimento($aValidar) !== true) {
          $this->aMsg = $this->oUtil->aMsg;
             throw new Exception;
        }
        
        //Anti SQL injection
        foreach ($aDados as $sNome => $mValor) {
          if (!is_array($mValor)) {
            $aDados[$sNome] = $this->oUtil->anti_sql_injection($mValor);
          }
        }

        //*********************************************************************
        // Campos da tabela produtos
        $this->oProduto->NM_PRODUTO[0]    = $aDados['CMPnome'];
        $this->oProduto->CD_PRODUTO[0]    = $aDados['CMPcodigo'];
        $this->oProduto->DE_CURTA[0]      = $aDados['CMPdeBreve'];
        $this->oProduto->DE_LONGA[0]      = $aDados['CMPdeLonga'];
        $this->oProduto->CD_STATUS[0]     = isset($aDados['CMPstatus']) ? 'A' : 'I';
        $this->oProduto->NU_CLIQUES[0]    = 0;
        $this->oProduto->NM_PRONUNCIA[0]  = soundex($aDados['CMPnome']);
        $this->oProduto->ID_TIPO[0]       = 0;
        $this->oProduto->ID_FABRICANTE[0] = $aDados['CMPfabricante'];
        $this->oProduto->ID_CATEGORIA[0]  = is_array($_POST['CMPcategorias']) ? $_POST['CMPcategorias'][0] : $_POST['CMPcategorias'];
        $this->oProduto->TX_LINK[0]       = '';

        /********** Salvar **********/
        if ($sAcao == 'inserir') {
          $this->oProduto->inserir();
        } else {
          $this->oProduto->editar($aDados['CMPid']);
        }

        if ($this->oProduto->aMsg['iCdMsg'] != 0) {
          $this->aMsg = $this->oProduto->aMsg;
          throw new Exception;
        }

        if ($sAcao == 'inserir') {
          $aRet = $this->oUtil->pegaInfoDB('tc_produtos', 'max(id)');
          $iIdProd = $aRet[0];
        } else {
          $iIdProd = $aDados['CMPid'];
        }

        //*********************************************************************
        // Atualizar link
        $sTxLink = $this->oUtil->montaUrlAmigavel($this->oProduto->NM_PRODUTO[0].'-'.$iIdProd);
        if (!$this->atualizarLink($iIdProd, $sTxLink)) {
          throw new Exception; 
        }
        
        //*********************************************************************
        //Categorias
        if (isset($_POST['CMPcategorias'])) {

          // Remove tudo
          if (!$this->oCategorias->remover('WHERE id_prod = '.$iIdProd)) {
            $this->aMsg = $this->oCategorias->aMsg;
            throw new Exception; 
          }

          foreach ($_POST['CMPcategorias'] as $iIdCategoria) {
            $this->oCategorias->ID_PROD[0] = $iIdProd;
            $this->oCategorias->ID_CAT[0]  = $iIdCategoria;
            
            
            /********** Salvar **********/
            $this->oCategorias->inserir();

            if ($this->oCategorias->aMsg['iCdMsg'] != 0) {
              $this->aMsg = $this->oCategorias->aMsg;
              throw new Exception;
            }
          }
        }

        //*********************************************************************
        //Tags
        if (isset($_POST['CMPtags'])) {
          
          // Remover tudo
          if (!$this->oTags->remover('WHERE id_prod = '.$iIdProd)) {
            $this->aMsg = $this->oTags->aMsg;
            throw new Exception;            
          }

          foreach ($_POST['CMPtags'] as $iIdTag) {
            $this->oTags->ID_PROD[0] = $iIdProd;
            $this->oTags->ID_TAG[0]  = $iIdTag;
            
            /********** Salvar **********/
            $this->oTags->inserir();
  
            if ($this->oTags->aMsg['iCdMsg'] != 0) {
              $this->aMsg = $this->oTags->aMsg;
              throw new Exception;
            }
          }
        }

        //*********************************************************************
        //Medidas
        $this->oMedidas->NU_X[0]    = $this->oUtil->parseValue($aDados['CMPlargura_x'], 'decimal-bd');
        $this->oMedidas->NU_Y[0]    = $this->oUtil->parseValue($aDados['CMPlargura_y'], 'decimal-bd');
        $this->oMedidas->NU_Z[0]    = $this->oUtil->parseValue($aDados['CMPaltura'], 'decimal-bd');
        $this->oMedidas->NU_PESO[0] = $this->oUtil->parseValue($aDados['CMPpeso'], 'decimal-bd');
        $this->oMedidas->ID_PROD[0] = $iIdProd;

        /********** Salvar **********/
        if ($sAcao == 'inserir') {
          $this->oMedidas->inserir();
        } else {
          $this->oMedidas->editar('WHERE id_prod = '.$iIdProd);
        }

        if ($this->oMedidas->aMsg['iCdMsg'] != 0) {
          $this->aMsg = $this->oMedidas->aMsg;
          throw new Exception;
        }

        //*********************************************************************
        //Preços
        $this->oPrecos->VL_ADICIONAIS[0] = $this->oUtil->parseValue($aDados['CMPprecoAdicional'], 'moeda-bd');
        $this->oPrecos->VL_TAXAS[0]      = $this->oUtil->parseValue($aDados['CMPtaxas'],          'moeda-bd');
        $this->oPrecos->VL_CUSTO[0]      = $this->oUtil->parseValue($aDados['CMPprecoCusto'],     'moeda-bd');
        $this->oPrecos->PC_MARGEM[0]     = $this->oUtil->parseValue($aDados['CMPmargem'],         'decimal-bd');
        $this->oPrecos->VL_FINAL[0]      = $this->oUtil->parseValue($aDados['CMPvalorFinal'],     'moeda-bd');
        $this->oPrecos->CD_VISIVEL[0]    = isset($aDados['CMPprecoVisivel']) ? 'S' : 'N';
        $this->oPrecos->ID_PROD[0]       = $iIdProd;

        /********** Salvar **********/
        if ($sAcao == 'inserir') {
          $this->oPrecos->inserir();
        } else {
          $this->oPrecos->editar('WHERE id_prod = '.$iIdProd);
        }

        if ($this->oPrecos->aMsg['iCdMsg'] != 0) {
          $this->oPrecos->aMsg['sMsg'] = 'Falha ao tentar salvar o preço do produto!';
          $this->aMsg = $this->oPrecos->aMsg;
          throw new Exception;
        }
        
        //*********************************************************************
        //Estoque
        $this->oEstoque->NU_ATUAL[0]            = $this->oUtil->parseValue($aDados['CMPatual'], 'decimal-bd');
        $this->oEstoque->NU_MINIMO[0]           = $this->oUtil->parseValue($aDados['CMPmin'], 'decimal-bd');
        $this->oEstoque->TX_FALTA_PROD[0]       = $aDados['CMPfaltaProduto'];
        $this->oEstoque->CD_VISIVEL_EM_FALTA[0] = isset($aDados['CMPapresentarEmFalta']) ? 'V' : 'I'; // Visível ou Invisivel
        $this->oEstoque->ID_PROD[0]             = $iIdProd;
        

        /********** Salvar **********/
        if ($sAcao == 'inserir') {
          $this->oEstoque->inserir();
        } else {
          $this->oEstoque->editar('WHERE id_prod = '.$iIdProd);
        }

        if ($this->oEstoque->aMsg['iCdMsg'] != 0) {
          $this->oEstoque->aMsg['sMsg'] = 'Falha ao tentar salvar dados referntes ao estoque!';
          $this->aMsg = $this->oEstoque->aMsg;
          throw new Exception;
        }
        
        //*********************************************************************
        //Tudo certo? commit!
        mysql_query('COMMIT', $this->DB_LINK);
        
      } catch (Exception $exc) {
        mysql_query('ROLLBACK', $this->DB_LINK);
        return false;
      }

      // Mensagem final sucesso!
      $this->iCdMsg = 0;
      $this->sMsg  = 'O registro foi adicionado com sucesso!';
      $this->sResultado = 'sucesso';
      $this->aMsg = array('iCdMsg' => $this->iCdMsg,
                            'sMsg' => $this->sMsg,
                      'sResultado' => $this->sResultado );
      return true;

    }
    
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT *
                   FROM v_produtos
                   '.$sFiltro;

      $sResultado = mysql_query($sQuery, $this->DB_LINK);
      $this->iLinhas = mysql_num_rows($sResultado);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      $i = 0;
      while ($aResultado = mysql_fetch_array($sResultado)) {
        
        $this->ID[$i]              = $aResultado['id'];
        $this->NM_PRODUTO[$i]      = $aResultado['nm_produto'];
        $this->CD_PRODUTO[$i]      = $aResultado['cd_produto'];
        $this->DE_CURTA[$i]        = $aResultado['de_curta'];
        $this->DE_LONGA[$i]        = $aResultado['de_longa'];
        $this->CD_STATUS[$i]       = $aResultado['cd_status'];
        $this->NU_CLIQUES[$i]      = $aResultado['nu_cliques'];
        $this->NM_PRONUNCIA[$i]    = $aResultado['nm_pronuncia'];
        $this->ID_TIPO[$i]         = $aResultado['id_tipo'];
        $this->ID_FABRICANTE[$i]   = $aResultado['id_fabricante'];
        $this->TX_LINK[$i]         = $aResultado['tx_link'];
        
        
	// Medidas
        $this->NU_X[$i]    = $aResultado['nu_x'];
        $this->NU_Z[$i]    = $aResultado['nu_z'];
        $this->NU_Y[$i]    = $aResultado['nu_y'];
        $this->NU_PESO[$i] = $aResultado['nu_peso'];
        
      	//Estoque
        $this->NU_ATUAL[$i]            = $aResultado['nu_atual'];
        $this->NU_MINIMO[$i]           = $aResultado['nu_minimo'];
        $this->TX_FALTA_PROD[$i]       = $aResultado['tx_falta_prod'];
        $this->CD_VISIVEL_EM_FALTA[$i] = $aResultado['cd_visivel_em_falta'];

        
	//Preços
        $this->VL_ADICIONAIS[$i] = $aResultado['vl_adicionais'];
        $this->VL_TAXAS[$i]      = $aResultado['vl_taxas'];
        $this->VL_CUSTO[$i]      = $aResultado['vl_custo'];
        $this->PC_MARGEM[$i]     = $aResultado['pc_margem'];
        $this->VL_FINAL[$i]      = $aResultado['vl_final'];
        $this->CD_VISIVEL[$i]    = $aResultado['cd_visivel'];
        
        $this->CD_OFERTA[$i]      = 'N';
        $this->PC_DESCONTO[$i]    = '0';
        $this->VL_ANTERIOR[$i]    = '0';

        // Descontos
        $this->ID_DESCONTO[$i]    = $aResultado['id_desconto'];
        $this->NM_DESCONTO[$i]    = $aResultado['nm_desconto'];
        $this->DE_DESCONTO[$i]    = $aResultado['de_desconto'];
        $this->TP_VALOR[$i]       = $aResultado['tp_valor'];
        $this->TP_DESCONTO[$i]    = $aResultado['tp_desconto'];
        $this->VL_MIN[$i]         = $aResultado['vl_min'];
        $this->VL_DESCONTO[$i]    = empty($aResultado['vl_desconto']) ? 0 : $aResultado['vl_desconto'];
               
        $this->VL_ADICIONAIS_REAL[$i] = $this->oUtil->parseValue($aResultado['vl_adicionais'], 'reais');
        $this->VL_TAXAS_REAL[$i]      = $this->oUtil->parseValue($aResultado['vl_taxas'],      'reais');
        $this->VL_CUSTO_REAL[$i]      = $this->oUtil->parseValue($aResultado['vl_custo'],      'reais');        
        $this->VL_FINAL_REAL[$i]      = $this->oUtil->parseValue($aResultado['vl_final'],      'reais');

        //Tags
        $this->TAGS_AGRUP[$i]    = $aResultado['nm_tag_agrupado'];
 
        // Categorias
        $this->ID_CAT_AGRUPADO[$i]          = $aResultado['id_cat_agrupado'];
        $this->NM_CATEGORIA_AGRUPADO[$i]    = $aResultado['nm_categoria_agrupado'];
        $this->CD_STATUS_CATEGORIA[$i]      = $aResultado['cd_status_categoria'];
        
        
        //Imagens
        $this->ID_IMAGEM_AGRUPADO[$i] = $aResultado['id_imagem_agrupado'];
	$this->NM_IMAGEM_AGRUPADO[$i] = $aResultado['nm_imagem_agrupado'];
	$this->NM_IMAGEM_PRINCIPAL[$i] = $aResultado['nm_imagem_principal'];
        
        $i++;
      }
    }
    public function calcularDescontos() {
      // alterado para classe descontos
    }

    public function inicializaAtributos() {
      
      $this->ID[0]            = (isset($_POST['CMPid'])        ? $_POST['CMPid'] : '');
      $this->ID_PROD[0]       = (isset($_POST['CMPid'])        ? $_POST['CMPid'] : '');

      $this->NM_PRODUTO[0]    = (isset($_POST['CMPnome'])      ? $_POST['CMPnome'] : '');
      $this->CD_PRODUTO[0]    = (isset($_POST['CMPcodigo'])    ? $_POST['CMPcodigo'] : '');
      $this->DE_CURTA[0]      = (isset($_POST['CMPdeBreve'])   ? $_POST['CMPdeBreve'] : '');
      $this->DE_LONGA[0]      = (isset($_POST['CMPdeLonga'])   ? $_POST['CMPdeLonga'] : '');
      $this->CD_STATUS[0]     = (isset($_POST['CMPstatus'])    ? $_POST['CMPstatus'] : '');
      $this->NU_CLIQUES[0]    = (isset($_POST['CMPcliques'])   ? $_POST['CMPcliques'] : '');
      $this->NM_PRONUNCIA[0]  = (isset($_POST['NM_PRONUNCIA']) ? $_POST['NM_PRONUNCIA'] : '');
      $this->ID_TIPO[0]       = (isset($_POST['CMPtipo'])      ? $_POST['CMPtipo'] : '');
      $this->ID_FABRICANTE[0] = (isset($_POST['CMPfabricante'])? $_POST['CMPfabricante'] : '');
      $this->ID_CATEGORIA[0]  = (isset($_POST['CMPcategoria']) ? $_POST['CMPcategoria'] : '');


      $this->NU_X[0]    = (isset($_POST['CMPlargura_x']) ? $_POST['CMPlargura_x'] : '');
      $this->NU_Y[0]    = (isset($_POST['CMPlargura_y']) ? $_POST['CMPlargura_y'] : '');
      $this->NU_Z[0]    = (isset($_POST['CMPaltura'])    ? $_POST['CMPaltura'] : '');
      $this->NU_PESO[0] = (isset($_POST['CMPpeso'])      ? $_POST['CMPpeso'] : '');
      $this->ID_PROD[0] = (isset($_POST['CMPidProduto']) ? $_POST['CMPidProduto'] : '');

      $this->VL_ADICIONAIS[0] = (isset($_POST['CMPprecoAdicional']) ? $_POST['CMPprecoAdicional'] : '');
      $this->VL_TAXAS[0]      = (isset($_POST['CMPtaxas'])          ? $_POST['CMPtaxas'] : '');
      $this->VL_CUSTO[0]      = (isset($_POST['CMPprecoCusto'])     ? $_POST['CMPprecoCusto'] : '');
      $this->PC_MARGEM[0]     = (isset($_POST['CMPmargem'])         ? $_POST['CMPmargem'] : '');
      $this->VL_FINAL[0]      = (isset($_POST['CMPvalorFinal'])     ? $_POST['CMPvalorFinal'] : '');
      $this->CD_VISIVEL[0]    = (isset($_POST['CMPprecoVisivel'])   ? $_POST['CMPprecoVisivel'] : '');
      
      
      $this->NU_ATUAL[0]            = (isset($_POST['CMPatual']) ? $_POST['CMPatual'] : '');
      $this->NU_MINIMO[0]           = (isset($_POST['CMPmin']) ? $_POST['CMPmin'] : '');
      $this->TX_FALTA_PROD[0]       = (isset($_POST['CMPfaltaProduto']) ? $_POST['CMPfaltaProduto'] : '');
      $this->CD_VISIVEL_EM_FALTA[0] = (isset($_POST['CMPapresentarEmFalta']) ? $_POST['CMPapresentarEmFalta'] : '');
    }
    
  /* produtos::removerImagem
   *
   * Remove imagens de produtos, usado no sistema de admin
   * Para remover imagens, basta passar o ID de cada imagem
   * 
   * @date 06/12/2012
   * @param  $aIdImg - Dados das imagens a serem removidas
   * @return 
   */
    public function removerImagem($aIdImg) {
      include 'config.php';
     
      try {
        
        // Se o não existem imagens para remover, volta da função
        if (!count($aIdImg))
          return false;
        
        $oImg = new tc_imagens();
        $oImg->listar("WHERE id IN (".implode(',', $aIdImg).")");
        
        // Remove as imagens da pasta
        for ($i = 0; $i < $oImg->iLinhas; $i++) {
          $sFile = '../comum/imagens/produtos/'.$oImg->NM_IMAGEM[$i];
          if (file_exists($sFile)) unlink($sFile);
        }
        
        //Remove registros da tabela de cadastro
        $oImg->remover('WHERE id IN ('.implode(',', $aIdImg).')');
        
        //Remove registros da tabela de relacionamento
        $oManRelProdImg = new tr_prod_img();
        $oManRelProdImg->remover('WHERE id_img IN ('.implode(',', $aIdImg).')');
        $this->aMsg = $oManRelProdImg->aMsg;
        
      } catch (Exception $e) {
        
      }
          
    }

  /* produtos::salvarImagem
   *
   * @date 06/12/2012
   * @param  $iIdProd - Produto dono da imagem
   * @param  $sTags   - Nome da imagem levará o nome das tags
   * @return 
   */
    public function salvarImagem($iIdProd, $sTags) {
      include 'config.php';
      try {
        $oUpload = new upload();

        // $sPrefixoNome = str_replace(',','-', $sTags);
        // Limita a quantidade de imagem para nao exceder o limite permitido de uploads
        $this->oUtil->pegaInfoDB('tr_prod_img', 'count(1)', 'WHERE id_prod = '.$iIdProd);
        if($this->oUtil->RETDB[0][0] > $CFGiQntImgProduto) {
          $oUpload->iCdMsg = 2;
          $oUpload->sMsg   = 'Limite de '.$CFGiQntImgProduto.' imagens por produto!';
          throw new Exception;
        } else {
  
          $sRand = time();
          $CFGaConfigUpload['pasta']    = '../comum/imagens/produtos/';
          $CFGaConfigUpload['novonome'] = $sRand;
          $CFGaConfigUpload['largura']  = 800;
          $CFGaConfigUpload['altura']   = 800;
          $sNomeImagem = $oUpload->enviarImagem($_FILES['CMPimagem'], $CFGaConfigUpload);

          if($sNomeImagem) {
            $oImg = new tc_imagens();
            $oImg->DE_BREVE[0]   = ($_POST['CMPdescricao'] != '' ) ? $this->oUtil->anti_sql_injection($_POST['CMPdescricao']) : $this->NM_PRODUTO[0];
            $oImg->NM_IMAGEM[0]    = $sNomeImagem;
            $oImg->CD_TIPO[0]    =  ($_POST['CMPcd_tipo'] == 'PR' ? 'PR' : 'DP');
            $oImg->TX_LINK[0]    = '';
            $oImg->CD_STATUS[0]  = 'A';
            $oImg->CD_EXTENSAO[0]= '';
            $oImg->inserir();
            
            $oManRelProdImg = new tr_prod_img();
            $this->oUtil->pegaInfoDB('tc_imagens', 'max(id)');
            $oManRelProdImg->ID_IMG[0] = $this->oUtil->RETDB[0][0];
            $oManRelProdImg->ID_PROD[0] = $iIdProd;
            $oManRelProdImg->inserir();
            
            $this->aMsg = $oImg->aMsg;
          }
        
        }
        //mysql_query('COMMIT', $this->DB_LINK);
      } catch (Exception $exc) {
        //mysql_query('ROLLBACK', $this->DB_LINK);
        return false;
      }
    }
    
    public function remover($aId) {
/*
      $this->oProduto    = new tc_produtos;
      $this->oMedidas    = new tc_prod_medidas;
      $this->oPrecos     = new tc_prod_precos;
      $this->oCategorias = new tr_prod_cat();
      $this->oTags       = new tr_prod_tag();
      $this->oEstoque    = new tc_prod_estoque();
*/
      try {
        mysql_query("START TRANSACTION", $this->DB_LINK);

        $sId = implode(',', $aId);

        // Busca o ID de todas as imagens dos produtos selecionados
        $this->oUtil->pegaInfoDB('tr_prod_img', 'id_img', 'WHERE id_prod IN ( '. $sId .' )');
        $aIdImagens = array();
        foreach ($this->oUtil->RETDB as $aIdImg) {
          $aIdImagens[] = $aIdImg[0];
        }

        if (!$this->oProduto->remover('WHERE id IN ('.$sId.')')) {
          $this->aMsg = $this->oProduto->aMsg;
          $this->aMsg['sMsg'] = 'Erro ao tentar excluir produto.';
          throw new Exception;          
        }
        
        if (!$this->oMedidas->remover('WHERE id_prod IN ('.$sId.')')) {
          $this->aMsg = $this->oMedidas->aMsg;
          $this->aMsg['sMsg'] = 'Erro ao tentar excluir dados referentes às medidas do produto.';
          throw new Exception;
        }

        if (!$this->oPrecos->remover('WHERE id_prod IN ('.$sId.')')) {
          $this->aMsg = $this->oPrecos->aMsg;
          $this->aMsg['sMsg'] = 'Erro ao tentar excluir dados referentes ao preço do produto.';
          throw new Exception;
        }
        
        if (!$this->oCategorias->remover('WHERE id_prod IN ('.$sId.')')) {
          $this->aMsg = $this->oCategorias->aMsg;
          $this->aMsg['sMsg'] = 'Erro ao tentar excluir dados referentes às categorias do produto.';
          throw new Exception;
        }
        
        if (!$this->oTags->remover('WHERE id_prod IN ('.$sId.')')) {
          $this->aMsg = $this->oTags->aMsg;
          $this->aMsg['sMsg'] = 'Erro ao tentar excluir dados referentes às tags do produto.';
          throw new Exception;
        }

        if (!$this->oEstoque->remover('WHERE id_prod IN ('.$sId.')')) {
          $this->aMsg = $this->oEstoque->aMsg;
          $this->aMsg['sMsg'] = 'Erro ao tentar excluir dados referentes ao estoque do produto.';
          throw new Exception;
        }
        
        $this->removerImagem($aIdImagens);
        
        $this->aMsg = $this->oProduto->aMsg;
        mysql_query('COMMIT', $this->DB_LINK);

        //mysql_query('ROLLBACK', $this->DB_LINK);
      } catch (Exception $exc) {

        mysql_query('ROLLBACK', $this->DB_LINK);
      }
    }
    
  private function atualizarLink($iId, $sLink) {
    $sQuery = "UPDATE tc_produtos
                  SET tx_link = '".$sLink."'
                WHERE id = ".$iId;

    $sResultado = mysql_query($sQuery, $this->DB_LINK);

    if (!$sResultado) {
      $this->iCdMsg = 1;
      $this->sMsg  = 'Ocorreu um erro ao tentar salvar o link para o produto';
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
    
    public function montarVitrine4x4($bCriarTable = true) {
      
      ?>
      <ul id="itemContainer"> <?php
      $iCont = 0;
      for ($i = 0; $i < $this->iQntItensVitrine; $i++) {?>
        <tr>
          <li class="item"><?php echo $this->aProdVitrine[$iCont++];?></li>
          <li class="item"><?php echo $this->aProdVitrine[$iCont++];?></li>
          <li class="item"><?php echo $this->aProdVitrine[$iCont++];?></li>
          <li class="item"><?php echo $this->aProdVitrine[$iCont++];?></li>
        </tr> <?php        
      }?>
      </ul> <?php 
    }
    
    public function buscarItensVitrine($sFiltro = '', $sSecao = '') {
      if ($sFiltro == '') {
        $sFiltro = "INNER JOIN tc_vitrine_itens ON v_produtos.id = tc_vitrine_itens.id_prod
                         WHERE cd_status = 'A'
                           AND nm_imagem_principal IS NOT NULL 
                           AND cd_grupo = 'index'
                           -- and cd_visivel_em_falta = V
                      ORDER BY tc_vitrine_itens.nu_ordem
                         LIMIT 0,16";
      }
      
      switch ($sSecao) {
        case 'categorias':

          $sFiltro  = " WHERE 1 = 1 "."\n";
          $sFiltro .= " AND cd_status = 'A'"."\n";
          //$sFiltro .= " -- AND nm_categoria_agrupado REGEXP '".$sTxLink."'"."\n";
          $sFiltro .= " AND id_cat_agrupado  REGEXP '".$_POST['iId']."'"."\n";
          $sFiltro .= " ORDER BY id ";
          $sFiltro .= " LIMIT ".$_POST['iStart'].",16";
          break;
        
        case 'tags':
          $sFiltro  = "    WHERE 1 = 1 "."\n";
          $sFiltro .= "      AND cd_status = 'A'"."\n";
          $sFiltro .= "      AND id_tag_agrupado  REGEXP '".$_POST['iId']."'"."\n";
          $sFiltro .= " ORDER BY id ";
          $sFiltro .= "    LIMIT ".$_POST['iStart'].",16 ";
          break;

        case 'fabricantes':
          $sFiltro  = "    WHERE 1 = 1 "."\n";
          $sFiltro .= "      AND cd_status = 'A'"."\n";
          $sFiltro .= "      AND id_fabricante  = '".$_POST['iId']."'"."\n";
          $sFiltro .= " ORDER BY id ";
          $sFiltro .= "    LIMIT ".$_POST['iStart'].",16 ";
          break;

      }

      $this->listar($sFiltro);
      
      for ($i = 0; $i < $this->iLinhas; $i++) {
        ob_start(); ?>

          <a href="<?php echo $this->oUtil->sUrlBase; ?>/produtos-detalhe/<?php echo $this->TX_LINK[$i]; ?>">
            <?php 
             $sImagem = $this->oUtil->sUrlBase.'/comum/imagens/produtos/'.$this->NM_IMAGEM_PRINCIPAL[$i];
            ?>
            <img src="<?php echo $sImagem; ?>" alt="" />
            <div class="limpa"></div>
            <div class="nome-produto"><?php echo $this->NM_PRODUTO[$i];?></div>
            <div class="preco">
              <?php 
                echo $this->CD_VISIVEL[$i] == 'S' ? 'Por: ' : '<br />'; 
                echo $this->CD_VISIVEL[$i] == 'S' ? '<br /><span class="avista">R$ '.$this->VL_FINAL_REAL[$i].'</span>' : '<br />consulte';
                echo $this->CD_VISIVEL[$i] == 'S' ? '<br /><span class="prazo">3 x de R$ '.$this->oUtil->parseValue(round($this->VL_FINAL[$i] / 3 , 2), 'reais').'</span>' : '<br />';
              ?>
            </div>
          </a>
        <?php
         $this->aProdVitrine[$i] = ob_get_clean();
      }
      $this->iQntItens = count($this->aProdVitrine);
      $this->iQntItensVitrine =  ceil($this->iLinhas / 4);
    }
    
    public function listarProdutosHorizontal($aOpcoes) { ?>
      <div id="produto">
        <?php
          if ($this->iLinhas > 0) { 
            
            if (isset($aOpcoes['sDescTop'])) {
              echo $aOpcoes['sDescTop'];
            }?>
          
          <table class="w98 f80" >
          <?php
            for ($i = 0; $i < $this->iLinhas; $i++) { ?>
            <tr style="border-bottom: #CCC solid; margin-bottom: 10px;">
              <td style="width: 150px;">
                <?php 
                 $sImagem = $this->oUtil->sUrlBase.'/comum/imagens/produtos/'.$this->NM_IMAGEM_PRINCIPAL[$i];
                ?>
                <img class="vitrine" src="<?php echo $sImagem; ?>" alt="" />
              </td>
              <td>
                <h2>
                  <?php echo $this->NM_PRODUTO[$i];?>
                </h2>

                <?php 
                  echo $this->oUtil->resumo($this->DE_CURTA[$i], 200); 
                ?>
                <br />
                <a class="link-1" href="<?php echo $this->oUtil->sUrlBase; ?>/produtos-detalhe/<?php echo $this->TX_LINK[$i]; ?>">Ver mais detalhes</a>
                
              </td>
            </tr> <?php
            } ?>
          </table> <?php
          } else {
            echo $aOpcoes['sDescSemResultados'];
           
          }
        ?>   
      </div>
      <?php
    }

  }
?>

