<?php
  session_start();
  $sPgAtual = 'index';

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
        //<div id="banner-promo"> banner promo </div> 
        //<div id="listagem-secundaria">secundary</div>
        $oProdutos->buscarItensVitrine();
      ?>
      <div id="vitrine">
        <?php 
          $oProdutos->montarVitrine4x4();
          echo '<br /><br />';
          $oNuvem->montarNuvem();
        ?>
        
      </div>
      <div class="limpa"></div>
    </div>
    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>    
  </body>
</html>
