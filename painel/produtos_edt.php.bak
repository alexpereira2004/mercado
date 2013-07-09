<?php
  session_start();
  $sPgAtual = 'produtos';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';
  include      '../modulosPHP/class.excecoes.php';

  include_once '../modulosPHP/adapter.produtos.php';

  $oUtil = new wTools();
  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  $oMenu = new adapter_tclv_menu();

  $oProdutos = new produtos();
  
  $sAcao = (isset($_POST['sAcao']) && $_POST['sAcao'] == 'editar' || isset($_GET['n'])) ? 'editar' : 'novo';
  $aMsgImagem = $aMsg;

  if (isset ($_POST['sAcao'])) {

    $oManProdutos = new produtos();
    if ($_POST['sAcao'] == 'novo') {
      //$oManProdutos = new produtos();
      $oManProdutos->salvar($_POST, 'inserir');
      $oProdutos->inicializaAtributos();
      $aMsg = $oManProdutos->aMsg;


    } elseif ($_POST['sAcao'] == 'editar') {
      $oManProdutos->salvar($_POST, $_POST['sAcao']);
      $oProdutos->inicializaAtributos();
      $aMsg = $oManProdutos->aMsg;

    } elseif ($_POST['sAcao'] == 'salvarImagem') {
      $oProdutos->listar('WHERE id = '.$_POST['CMPid']);
      $sAcao = 'editar';
      $oManProdutos->listar('WHERE id = '.$_POST['CMPid']);
      $oManProdutos->salvarImagem($_POST['CMPid'], $oProdutos->TAGS_AGRUP[0]);
      $aMsgImagem = $oManProdutos->aMsg;

      
    } elseif ($_POST['sAcao'] == 'excluirImagens') {
      $sAcao = 'editar';
      $oProdutos->listar('WHERE id = '.$_POST['CMPid']);

      if (!isset($_POST['CMPaId'])) {
        $aMsg = $oUtil->msgRetAlteracoes_montar(2, 'Selecione ao menos um item', 'erro');
      } else {
        $oManProdutos->removerImagem($_POST['CMPaId']);
        $aMsgImagem = $oManProdutos->aMsg;
      }
    }      
  } else {
    try {
      if (isset ($_REQUEST['n'])) {

        if (!is_numeric($_REQUEST['n'])) {
          throw new excecoes(10, $sPgAtual);
        }

        $iId = $oUtil->anti_sql_injection($_REQUEST['n']);
        $oProdutos->listar('WHERE id = '.$iId);

        if ($oProdutos->iLinhas < 1) {
          throw new excecoes(15, $sPgAtual);
        }
      } else {
        $oProdutos->inicializaAtributos();
      }

    } catch (excecoes $e) {

      $e->getErrorByCode();
      header('location:'.$CFGaPgAtual[$sPgAtual]['backPage']);
      exit;
    }
  }

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'];?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script type="text/javascript" src="../modulosJS/jwysiwyg/jquery.wysiwyg.js"></script>
    <script type="text/javascript" src="../modulosJS/util-loja.js"></script>
    <link rel="stylesheet" href="../modulosJS/jwysiwyg/jquery.wysiwyg.css" type="text/css" />

    <script type="text/javascript">
     

      $(document).ready(function(){
        $('.moeda').keyup(function(){
          moeda(this);
        });
       
        $('.salvar').click(function() {
          $('#FRMprodutos').submit();
        });
        
        $('.removerImagens').click(function(){
          removerViaCheckBox('Deseja realmente excluir as imagens selecionadas ?', 'produtos_edt.php#removerImagens', 'excluirImagens');
        });

        $('#CMPdeBreve').wysiwyg();
        $('#CMPdeLonga').wysiwyg();


        <?php $oAdmin->montarJsTwinList('FRMprodutos'); ?>
        
      });
    </script>

  </head>
  <body>
    <div id="pagina">
      <?php
        $oAdmin->cabecalho();
        $oAdmin->montarMenu($sPgAtual);
      ?>
      <div id="corpo">
        <?php
          $oAdmin->msgRetAlteracoes($aMsg);
          $oAdmin->breadCrumbs();
          $oAdmin->minheight('600');
        ?>
        <div id="toolBar">
          <a href="<?php echo $CFGaPgAtual[$sPgAtual]['backPage']; ?>"><img src="../comum/imagens/icones/doc_page_previous.png" alt="Voltar" /></a>
        </div>
        <form id="FRMprodutos" name="FRMprodutos" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
          <input type="hidden" name="sAcao" value="<?php echo $sAcao; ?>" />
          <input type="hidden" name="CMPid" id="CMPid" value="<?php echo ($sAcao == 'novo') ? 0 : $oProdutos->ID[0]; ?>" />
          <input type="hidden" name="CMPcliques" value="<?php echo $oProdutos->NU_CLIQUES[0] == '' ? 0 : $oProdutos->NU_CLIQUES[0]?>" />
          <table style="margin-top: 20px">
            <tr>
              <td>&nbsp;</td>
              <td>
                <input class="bt" type="reset" value="Limpar" />
                <input class="bt" type="submit" value="Salvar" />
              </td>
            </tr>
          </table>
          
          <fieldset>
            <legend>Produto:</legend>
            <table class="tab_lista_registros">
              <tr>
                <td class="infoheader">Nome:</td>
                <td><input type="text" name="CMPnome" value="<?php echo $oProdutos->NM_PRODUTO[0]; ?>" /></td>
                <td class="infoheader">Código:</td>
                <td><input type="text" name="CMPcodigo" value="<?php echo $oProdutos->CD_PRODUTO[0]; ?>" /></td>
                <td class="infoheader">Produto Ativo:</td>
                <td><input type="checkbox" name="CMPstatus" value="A"  <?php echo $oProdutos->CD_STATUS[0] == 'A' ? ' checked="checked" ': ''; ?> /></td>
              </tr>
              <tr>
                <td class="infoheader">Fabricante:</td>
                <td>
                  <?php
                    $oAdmin->montaSelectDB('CMPfabricante', 'tc_prod_fabricantes', 'id', 'nm_fabricante', $oProdutos->ID_FABRICANTE[0], 'CMPfabricante');
                  ?>
                </td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
              </tr>
            </table>

            <table class="tab_lista_registros">
              <tr>
                <td class="infoheader" colspan="3">Descrição Breve:</td>
                <td colspan="3">
                  <textarea class="w90" name="CMPdeBreve" id="CMPdeBreve" rows="5" cols="47"><?php echo $oProdutos->DE_CURTA[0]; ?></textarea><span class="campo-obrigatorio">*campo obrigatório</span>
                </td>
              </tr>
              <tr>
                <td class="infoheader" colspan="3">Descrição Completa:</td>
                <td colspan="3">
                  <textarea class="w90" name="CMPdeLonga" id="CMPdeLonga" rows="5" cols="47" ><?php echo $oProdutos->DE_LONGA[0]; ?></textarea><span class="campo-obrigatorio">*campo obrigatório</span>
                </td>
              </tr>
              <tr>
                <td class="infoheader" colspan="3">Categorias</td>
                <td colspan="3">
                  <?php
                    $iIdRegEditado = ($sAcao == 'novo') ? 0 : $oProdutos->ID[0] ;
                    $aDadosTbCadastro = array('tc_prod_categorias', 'id', 'nm_categoria');
                    $aDadosTbRelacionamento = array('tr_prod_cat', 'id_cat', 'id_prod');
                    $sFiltro = "AND cd_status = 'A'";
                    $oAdmin->montarTwinList('CMPcategorias', $iIdRegEditado, $aDadosTbCadastro, $aDadosTbRelacionamento, $sFiltro);
                  ?>
                </td>
              </tr>
              <tr>
                <td class="infoheader" colspan="3">Tags</td>
                <td colspan="3">
                  <?php
                    $iIdRegEditado = ($sAcao == 'novo') ? 0 : $oProdutos->ID[0] ;
                    $aDadosTbCadastro = array('tc_tags', 'id', 'nm_tag');
                    $aDadosTbRelacionamento = array('tr_prod_tag', 'id_tag', 'id_prod');
                    $oAdmin->montarTwinList('CMPtags', $iIdRegEditado, $aDadosTbCadastro, $aDadosTbRelacionamento);
                  ?>
                </td>
              </tr>
              <tr>
                <td></td>
                <td></td>
              </tr>
            </table>
          </fieldset>

          <fieldset>
            <legend>Medidas:</legend>
            <table>
              <tr>
                <td class="infoheader">Largura em cm(x):</td>
                <td class="infoheader">Largura em cm(y):</td>
                <td class="infoheader">Altura em cm:</td>
                <td class="infoheader">Peso em Kg:</td>
              </tr>
              <tr>
                <td><input type="text" name="CMPlargura_x" value="<?php echo $oProdutos->NU_X[0]; ?>" /></td>
                <td><input type="text" name="CMPlargura_y" value="<?php echo $oProdutos->NU_Y[0]; ?>" /></td>
                <td><input type="text" name="CMPaltura"    value="<?php echo $oProdutos->NU_Z[0]; ?>" /></td>
                <td><input type="text" name="CMPpeso"      value="<?php echo $oProdutos->NU_PESO[0]; ?>" /></td>
              </tr>
            </table>
          </fieldset>

          <fieldset>
            <legend>Preço:</legend>
            <table>
              <tr>
                <td class="infoheader">Preço de custo(R$):</td>
                <td><input type="text" class="moeda" id="CMPprecoCusto" name="CMPprecoCusto" value="<?php echo $oUtil->parseValue($oProdutos->VL_CUSTO[0], 'reais'); ?>" /></td>
              </tr>
              <tr>
                <td class="infoheader">Taxas(R$):</td>
                <td><input type="text" class="moeda" id="CMPtaxas" name="CMPtaxas" value="<?php echo $oUtil->parseValue($oProdutos->VL_TAXAS[0],'reais'); ?>" /></td>
              </tr>
              <tr>
                <td class="infoheader">Preço adicional(R$):</td>
                <td><input type="text" class="moeda" id="CMPprecoAdicional" name="CMPprecoAdicional" value="<?php echo $oUtil->parseValue($oProdutos->VL_ADICIONAIS[0],'reais'); ?>" /></td>
              </tr>
              <tr>
                <td class="infoheader">Margem de lucro(%):</td>
                <td><input type="text" id="CMPmargem" name="CMPmargem" value="<?php echo $oProdutos->PC_MARGEM[0]; ?>" /></td>
              </tr>
              <tr>
                <td class="infoheader">Visível ao cliente:</td>
                <td><input type="checkbox" name="CMPprecoVisivel" value="S" <?php echo $oProdutos->CD_VISIVEL[0] == 'S' ? 'checked="checked"' : ''; ?> /></td>
              </tr>
              <tr>
                <td class="infoheader">Preço final (R$):</td>
                <td>
                  <input id="CMPvalorFinal" name="CMPvalorFinal" type="text" readonly style="border: none" value="<?php echo ($oProdutos->VL_FINAL[0] != '' ? $oUtil->parseValue($oProdutos->VL_FINAL[0], 'reais') : 'Não calculado' ) ?>" />
                  <input type="button" class="bt" value="Calcular" onclick="calcularPrecoProduto();" />
                </td>
              </tr>
            </table>
          </fieldset>

          <fieldset>
            <legend>Estoque:</legend>
            <table>
              <tr>
                <td class="infoheader">Quantidade Atual:</td>
                <td><input type="text" name="CMPatual" value="<?php echo $oProdutos->NU_ATUAL[0]; ?>" /></td>
              </tr>
              <tr>
                <td class="infoheader">Quantidade Mínima:</td>
                <td><input type="text" name="CMPmin"  value="<?php echo $oProdutos->NU_MINIMO[0]; ?>" /></td>
              </tr>
              <tr>
                <td class="infoheader">Visualizar com produto em falta: </td>
                <td><input type="checkbox" name="CMPapresentarEmFalta" value="V" <?php echo $oProdutos->CD_VISIVEL_EM_FALTA[0] == 'V' ? 'checked="checked"' : ''; ?> /></td>
              </tr>
              <tr>
                <td class="infoheader">Texto explicativo na falta de produto:</td>
                <td><textarea name="CMPfaltaProduto" rows="4" cols="40"><?php echo $oProdutos->TX_FALTA_PROD[0]; ?></textarea></td>
              </tr>
            </table>
          </fieldset>
        </form>
          
          <?php
            if ($sAcao == 'editar') { ?>
              <fieldset>
                <legend>Imagens:</legend>
                <!-- <iframe id="upload_target" name="upload_target" src="frame-imagens-produtos.php?n=<?php echo $oProdutos->ID[0]; ?>" style="width:90%; margin: 10px; height:100%;border:none; background: #FFF"  scrolling="no"></iframe> -->
                <div id="upload_target">
                <?php
                  $oUpload = new upload();
                  $oUpload->aConfig = array('sAction'  => 'produtos_edt.php#upload_target',
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
                                                 WHERE id_prod = ".$oProdutos->ID[0]."
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

                  $oUpload->formEnvio($oProdutos->ID[0], true, '', 'Descrição da imagem', '', $aInputAdicional);

                  $oImagensProduto = new tc_imagens();
                  $sFiltro = "INNER JOIN tr_prod_img ON tr_prod_img.id_img = tc_imagens.id
                                   WHERE id_prod = ".$oProdutos->ID[0]." ORDER BY CD_TIPO desc ";
                  $oImagensProduto->listar($sFiltro);

                  if ($oImagensProduto->iLinhas > 0) {?>
                    <div id="removerImagens" class="bt_link removerImagens">Remover Selecionadas</div>
                    <?php
                      $oAdmin->msgRetAlteracoes($aMsgImagem);
                  }
                  
                  for ($i = 0; $i < $oImagensProduto->iLinhas; $i++) {?>
                    <div style="width: 160px; float: left; margin-left: 5px; border: 1px solid #CCC; padding: 5px; height: 160px; overflow: hidden; <?php echo $oImagensProduto->CD_TIPO[$i] == 'PR' ? 'background: #CCC;' : '' ?>">
                      <table style="width: 80%">
                        <tr>
                          <td style="width: 20px">
                            <input type="checkbox" class="checkRemover" name="CMPremover_<?php echo $oImagensProduto->ID[$i]; ?>" value="<?php echo $oImagensProduto->ID[$i]; ?>" />
                          </td>
                          <td>
                            <img src="../comum/imagens/icones/hammer_screwdriver.png" align="Editar" />
                          </td>
                        </tr>
                      </table>
                      
                        <?php echo $CFGaTipoImagens[$oImagensProduto->CD_TIPO[$i]];?><br />

                      <img src="../comum/imagens/produtos/<?php echo $oImagensProduto->NM_IMAGEM[$i]?>" alt="" style="margin-left: 15px;width: 120px;"/>
                    </div>
                    <?php
                  }
                ?>

                  
                </div>
              </fieldset>
          <?php
            }
          ?>

          <table style="margin-top: 20px">
            <tr>
              <td>&nbsp;</td>
              <td>
                <input class="bt" type="reset" value="Limpar" />
                <input class="bt salvar" type="button" value="Salvar" />
              </td>
            </tr>
          </table>
      </div>
      <div class="limpa"></div>
      <?php
        $oAdmin->rodape($sPgAtual);
      ?>
    </div>
  </body>
</html>