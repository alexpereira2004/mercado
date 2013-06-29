<?php

  session_start();
  $sPgAtual = 'produtos';

  require_once '../modulosPHP/class.wTools.php';
  require_once '../modulosPHP/class.upload.php';
  require_once '../modulosPHP/class.tc_imagens.php';
  require_once '../modulosPHP/class.tr_prod_img.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';
  include      '../modulosPHP/class.excecoes.php';

  include_once '../modulosPHP/adapter.produtos.php';

  $oUtil = new wTools();
  $oLogin = new usuario_admin();
  $oLogin->validar();

  $oProdutos = new produtos();
  $oUpload = new upload();

  $iIdProd = (isset($_GET['n'])) ? $_GET['n'] : $_POST['CMPref'];;
  
  if (!is_numeric($iIdProd)) {
    exit;
  }
  
  $oProdutos->listar('WHERE id = '.$iIdProd);
  /*
  
  if(isset($_POST['sAcao'])) {

    if($_POST['sAcao'] == 'salvarImagem') {

      try {
        
        // Limita a quantidade de imagem para nao exceder o limite permitido de uploads
        $oUtil->pegaInfoDB('tr_prod_img', 'count(1)', 'WHERE id_prod = '.$iIdProd);
        if($oUtil->RETDB[0][0] > $CFGiQntImgProduto) {
          $oUpload->iCdMsg = 2;
          $oUpload->sMsg   = 'Limite de '.$CFGiQntImgProduto.' imagens por produto!';
          throw new Exception;
        } else {

          $sRand = time();
          $CFGaConfigUpload['pasta']    = '../comum/imagens/produtos/';
          $CFGaConfigUpload['novonome'] = 'Mercado-dos-Sabores-'.str_replace(' ', '-', $oProdutos->NM_PRODUTO[0]).'-'.$sRand;
          $CFGaConfigUpload['largura']  = 800;
          $CFGaConfigUpload['altura']   = 800;
          $sNomeImagem = $oUpload->enviarImagem($_FILES['CMPimagem'], $CFGaConfigUpload);

          if($sNomeImagem) {
            $oImg = new tc_imagens();
            $oImg->DE_BREVE[0]   = ($_POST['CMPdescricao'] != '' ) ? $oUtil->anti_sql_injection($_POST['CMPdescricao']) : $oProdutos->NM_PRODUTO[0];
            $oImg->NM_IMAGEM[0]    = $sNomeImagem;
            $oImg->CD_TIPO[0]    =  ($_POST['CMPcd_tipo'] == 'PR' ? 'PR' : 'DP');
            $oImg->TX_LINK[0]    = '';
            $oImg->CD_STATUS[0]  = 'A';
            $oImg->CD_EXTENSAO[0]= '';
            $oImg->inserir();
            
            $oManRelProdImg = new tr_prod_img();
            $oUtil->pegaInfoDB('tc_imagens', 'max(id)');
            $oManRelProdImg->ID_IMG[0] = $oUtil->RETDB[0][0];
            $oManRelProdImg->ID_PROD[0] = $iIdProd;
            $oManRelProdImg->inserir();
          }
        
        }
        //mysql_query('COMMIT', $this->DB_LINK);
      } catch (Exception $exc) {
        //mysql_query('ROLLBACK', $this->DB_LINK);
        return false;
      }
    }
  }

  if(isset($_GET['sAcao'])) {

    if($_GET['sAcao'] == 'remover') {

      if(is_numeric($_GET['iIdImg'])) {

        $iId  = $_GET['iIdImg'];

        
        // Remove do banco de dados
        $oImg = new tc_imagens();
        $oImg->listar('WHERE id = '.$iId);
        $oImg->remover('WHERE id = '.$iId);

        $oManRelProdImg = new tr_prod_img();
        $oManRelProdImg->remover('WHERE id_prod = '.$iIdProd.' AND id_img = '.$iId);

        // Remove o arquivo da imagem
        $oUpload = new upload();
        $sLocal   = "../comum/imagens/produtos/";
        $sArquivo = $oImg->NM_IMAGEM[0];
        $oUpload->remover($sArquivo, $sLocal);
      }

    }
  }*/

  ?>

<link href="../comum/estilos_admin.css" media="all"  rel="stylesheet" type="text/css" />  


  <?php


  $oUtil->msgRetAlteracoes($oUpload->iCdMsg, $oUpload->sMsg);



  $oUpload->aConfig = array('sAction'  => 'frame-imagens-produtos.php',
      'sAction'  => 'produtos_edt.php',
                            'sEstampa' => 'Imagens Produto',
                            'sAcao'    => 'salvarImagem',
                            'sIdForm'  => 'FRMimg',
                            'sNome'    => 'CMPimagem');
  $aValores['DP'] = $CFGaTipoImagens['DP'];

  
  
  // Somente será permitido uma imagem "Principal" por produto, caso uma já tenha
  // sido cadastrada, o select não apresentará a opção para o usuário.
  $aRet = $oUtil->buscarInfoDB("SELECT cd_tipo
                                  FROM tc_imagens
                            INNER JOIN tr_prod_img ON tr_prod_img.id_img =  tc_imagens.id
                                 WHERE id_prod = ".$iIdProd."
                                   AND cd_tipo = 'PR'");

  if (empty($aRet)) {
    $aValores['PR'] = $CFGaTipoImagens['PR'];
  }

  $aInputAdicional = array( array( 'type' => 'select',
                                  'value' => 'teste',
                                   'name' => 'CMPcd_tipo',
                                  'label' => 'Tipo da imagem',
                           'aDadosSelect' => $aValores,
                     'aDadosSelectPadrao' => 'DP'
                                  )
                          );

  $oUpload->formEnvio($iIdProd, true, '', 'Descrição da imagem', '', $aInputAdicional);

  $oImagensProduto = new tc_imagens();
  $sFiltro = "INNER JOIN tr_prod_img ON tr_prod_img.id_img = tc_imagens.id
                   WHERE id_prod = ".$iIdProd." ORDER BY CD_TIPO desc ";
  $oImagensProduto->listar($sFiltro);

  for ($i = 0; $i < $oImagensProduto->iLinhas; $i++) {?>
    <div style="width: 160px; float: left; margin-left: 5px; border: 1px solid #CCC; padding: 5px; height: 160px; overflow: hidden; <?php echo $oImagensProduto->CD_TIPO[$i] == 'PR' ? 'background: #CCC;' : '' ?>">
      <a href="frame-imagens-produtos.php?n=<?php echo $iIdProd; ?>&sAcao=remover&sIdImg=<?php echo $oImagensProduto->TX_NOME[$i];?>&iIdImg=<?php echo $oImagensProduto->ID[$i];?>">
        Remover
      </a> <br />
        <?php echo $CFGaTipoImagens[$oImagensProduto->CD_TIPO[$i]];?><br />
      
      <img src="../comum/imagens/produtos/<?php echo $oImagensProduto->NM_IMAGEM[$i]?>" alt="" style="margin-left: 15px;width: 120px;"/>
    </div>
    <?php
  }
?>


