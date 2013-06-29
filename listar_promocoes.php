<?php
  session_start();
  $sPgAtual = 'listagem-produtos-promocoes';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  $oSite       = new pimentas();
  $oProdutos   = new produtos();
  $oNuvem      = new nuvem_tags('tags');

  
  

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
        //$oProdutos->buscarItensVitrine();

        $aOpcoes['sDescSemResultados'] = '<h1 class="titulo-02">No momemnto não temos promoções</h1>';    
        $aOpcoes['sDescSemResultados'] .= '<p>Mas em breve divulgaremos novas promoções que irão surpreender você! Confira!</p>';    
        $oProdutos->listarProdutosHorizontal($aOpcoes);
        ?>
      <span style="float: right; width: 700px; display: inline-block">
        <?php $oNuvem->montarNuvem();?>
      </span>
        
      
      <div class="limpa"></div>
    </div>
    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>    
  </body>
</html>
