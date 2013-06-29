<?php
  session_start();
  $sPgAtual = 'checkout';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  $oSite       = new pimentas();
  $oProdutos   = new produtos();
  $oNuvem      = new nuvem_tags('tags');

  if (isset($_POST['sAcao'])) {
    if ($_POST['sAcao'] == 'barra-pesquisa') {
      $sValor = $oSite->anti_sql_injection($_POST['CMPpesquisar']);
      $sFiltro = "WHERE nm_pronuncia LIKE '".soundex($sValor)."'\n";
      $sFiltro .= "  OR nm_produto LIKE '%".$sValor."%'\n";
      $sFiltro .= "  OR nm_categoria_agrupado LIKE '%".$sValor."%'\n";
      $sFiltro .= "  OR nm_tag_agrupado LIKE '%".$sValor."%'\n";
      
      $oProdutos->listar($sFiltro);
    }
  }
  
//  $oLogin = new clientes();
//  $bLogado = $oLogin->validar();
//  $oLogin->listar('WHERE id = '.$_SESSION[carrinho::getUsuarioSessao()]['login']['id_usu']);
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo']; ?></title>

    <?php
      $oSite->incluirCss($sPgAtual);
      $oSite->incluirJs($sPgAtual);
      $oSite->incluirMetaTags($sPgAtual);
    ?>
    <script type="text/javascript">
      $(document).ready(function() {
        $('.continuar-comprando').click(function(){
          window.location = $('#CMPsUrlBackPage').val();
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
        
        $aOpcoes['sDescTop'] = '<h1 class="titulo-02">Resultado Pesquisa</h1>';
        $aOpcoes['sDescTop'] .= 'Termo de busca: ' .$_POST['CMPpesquisar'];
        
        $aOpcoes['sDescSemResultados'] = '<h1 class="titulo-02">Pesquisa sem resultados</h1>';
        $aOpcoes['sDescSemResultados'] .= isset($_POST['CMPpesquisar']) ? 'Termo de busca: '.$_POST['CMPpesquisar'] : '';
      
        $oProdutos->listarProdutosHorizontal($aOpcoes);
      ?>

      <div class="limpa"></div>
    </div>
    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>    
  </body>
</html>
