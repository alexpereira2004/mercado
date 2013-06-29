<?php
  session_start();
  $sPgAtual = 'index';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  $oSite       = new pimentas();
  $oHtmlGeral  = new tcctd_htmlgeral();
  
  $sFiltro = "WHERE tx_link LIKE '".$oSite->anti_sql_injection($_GET['n']) ."'
                 OR tx_link LIKE '".$oSite->anti_sql_injection($_GET['n']) ."/'";
  $oHtmlGeral->listar($sFiltro);
  if($oHtmlGeral->iLinhas != 1) {
    header('location: '.$oSite->sUrlBase);
    exit;
  }
  #escreve um cabecalho diferente para cada página
  $aDadosMetaTags = array ('de_meta_description' => $oHtmlGeral->DE_META_TAG[0],
                                'tx_meta_titulo' => $oHtmlGeral->TX_META_TITU[0],
                                   'tx_keywords' => $oHtmlGeral->TX_TAGS[0] );
//        include 'modulosPHP/class.emails.php';
//        $oEmail = new emails();
//        $oEmail->criacaoConta('alex@lunacom.com.br');
//        exit;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>Mercado dos Sabores - <?php echo $oHtmlGeral->TX_META_TITU[0] ?></title>

    <?php
      $oSite->incluirCss($sPgAtual);
      $oSite->incluirJs($sPgAtual);
      $oSite->incluirMetaTags($sPgAtual, $aDadosMetaTags);
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
      ?>
      <div id="conteudo">
        <?php echo $oHtmlGeral->TX_CONTEUDO[0] ?>
        
      </div>
      <div class="limpa"></div>
    </div>
    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>    
  </body>
</html>
