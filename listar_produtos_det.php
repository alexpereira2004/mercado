<?php
  session_start();
  $sPgAtual = 'listagem-produtos-det';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  $oSite       = new pimentas();
  $oProdutos   = new produtos();
  
  $aDados = explode('/', $_GET['n']);  
  $aDados = explode('-', $aDados[0]);

  $iId = array_pop($aDados);
  $sTxLink = implode('-', $aDados);

  try {
    
    // Validação do ID
    if ((!is_numeric($iId)) )
      throw new Exception;
    

    $sFiltro  = ' WHERE 1 = 1';
    $sFiltro .= " AND cd_status = 'A'";
    $sFiltro .= " AND tx_link LIKE '".$oSite->anti_sql_injection($sTxLink.'-'.$iId)."%'";
    $sFiltro .= " AND id = ".$iId;

    $oProdutos->listar($sFiltro);
    if (!is_null($oProdutos->TP_DESCONTO[0])) {      
      $oDesconto = descontos::carregarClasseDesconto($oProdutos->TP_DESCONTO[0]);
      $oDesconto->calcularDescontoListagem(&$oProdutos);
    }
    
    // Validação se o produto foi encontrado
    if ($oProdutos->iLinhas != 1)
      throw new Exception;
    
  } catch (Exception $exc) {
    header('location:'.$oSite->sUrlBase);
  }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'].' - '.$oProdutos->NM_PRODUTO[0]; ?></title>

    <?php
      $oSite->incluirCss($sPgAtual);
      $oSite->incluirJs($sPgAtual);
      $oSite->incluirMetaTags($sPgAtual);
    ?>
    
    <script type="text/javascript">
      $(document).ready(function() {
        $('.btCalcularFrete').click(function(){
          calcularFrete();
        });
        $('#slides1').bxGallery({
           maxheight: 250,
           maxwidth: 350,
           thumbwidth: 50,
           thumbplacement: 'bottom',
           thumbcontainer: 350,
           thumbcrop: true,
           croppercent: .20
        });
      });
    </script>
  </head>
  <body>
    <?php 
      echo $oSite->cabecalho();
    ?>

    <div id="pagina">
      <?php 
        echo $oSite->listagem($sPgAtual);
      ?>
      <div id="produto">


        <div class="dados">
          <div class="imagens">
            <?php 
              //<a href="#" class="botao-comprar">Comprar</a>
              $sFiltro = "INNER JOIN tr_prod_img ON tr_prod_img.id_img = tc_imagens.id
                               WHERE id_prod = ".$oProdutos->ID[0]." ORDER BY cd_tipo DESC ";
              $oImagens = new tc_imagens();
              $oImagens->listar($sFiltro);
            ?>
            <ul id="slides1"> <?php
              for ($i = 0; $i < $oImagens->iLinhas; $i++) { ?>
                <li><img src="<?php echo $oSite->sUrlBase;?>/comum/imagens/produtos/<?php echo $oImagens->NM_IMAGEM[$i]; ?>" alt="<?php echo $oProdutos->NM_PRODUTO[0]; ?>" title="<?php echo $oImagens->DE_BREVE[$i] != '' ? $oImagens->DE_BREVE[$i] : $oProdutos->NM_PRODUTO[0]; ?>" /></li><?php
              } ?>
            </ul>
          </div>
          <div class="informacoes">
            <h2><?php echo $oProdutos->NM_PRODUTO[0];?></h2>
            <span class="min"><?php echo $oProdutos->CD_PRODUTO[0] == '' ? '' : 'cód. '.$oProdutos->CD_PRODUTO[0];?></span><br />
            <div style="height: 20px; margin-bottom: 10px; border-bottom: dotted 1px #CCC;">&nbsp;</div>
            <?php
              if ($oProdutos->VL_ANTERIOR[0] != 0) { ?>
                de: <span class="preco-anterior">R$ <?php echo $oSite->parseValue($oProdutos->VL_ANTERIOR[0], 'reais'); ?></span><br />
                <?php
              }
            ?>
            por: <span class="preco-destaque">R$ <?php echo $oSite->parseValue($oProdutos->VL_FINAL[0], 'reais'); ?></span>
            <?php
              if ($oProdutos->TP_DESCONTO[0] == 'Q' && $oProdutos->DE_DESCONTO[0] != '') {
                echo '<br />'.$oProdutos->DE_DESCONTO[0];
              }
            ?>
            <div style="height: 20px;">&nbsp;</div>

            
            <span style="float: right;">
              <form action="<?php echo $oSite->sUrlBase?>/checkout/itens/" method="post" >
                <input type="hidden" name="sAcao" value="adicionarProdutoCarrinho">
                <input type="hidden" name="CMPiIdProd" value="<?php echo $oProdutos->ID[0]; ?>">
                <input type="hidden" name="CMPsUrlBackPage" value="<?php echo $oSite->montarUrlLinkId('categorias', '', $oProdutos->ID_CAT_AGRUPADO[0]).'/' ?>">
                <input type="submit" class="botao1" value="Comprar" />
              </form>
            </span><br /><br />
                <br /><br />
            <span style="float: right;">
              <a href="#" class="f80">Adicionar ao carrinho e continuar comprando</a>
            </span>
            <div style="margin-bottom: 10px; height: 20px; border-bottom: dotted 1px #CCC;">&nbsp;</div>
            <?php
              $sConteudo  = '<input type="hidden" id="CMPiIdProd" name="CMPiIdProd" value="'.$oProdutos->ID[0].'" />&nbsp;';
              $sConteudo .= '<input type="hidden" id="CMPsUrlBase" name="CMPsUrlBase" value="'.$oSite->sUrlBase.'" />&nbsp;';
              $sConteudo .= '<input class="w20" style="padding: 3; text-align: right" type="text" maxlength="5" id="CMPcalcularFrete-01" name="CMPcalcularFrete-01" value="" />&nbsp;';
              $sConteudo .= '<input class="w10" style="padding: 3; text-align: right" type="text" maxlength="3" id="CMPcalcularFrete-02" name="CMPcalcularFrete-02" value="" />&nbsp;&nbsp;';
              $sConteudo .= '<input type="button" class="bt btCalcularFrete" id="btBuscarEndereco" value="Ok" />';
              $sConteudo .= '<div id="retCalculoFrete"></div>';
              $oSite->box01('Calcular frete:', $sConteudo);
            ?>
          </div>
          <div id="retCalculoFrete"></div>

          <div class="limpa"></div>
          <div class="detalhes">
            <?php $oSite->box01('Informações do produto', $oProdutos->DE_LONGA[0]); ?>
          </div>
        </div>
      </div>
      <div class="limpa"></div>
    </div>

    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>
    
  </body>
</html>
