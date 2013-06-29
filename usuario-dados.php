<?php
  session_start();
  $sPgAtual = 'usuario-dados';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  include      'modulosPHP/PHPMailer_5.2.1/class.phpmailer.php';
  
  $oSite       = new pimentas();

  $oLogin      = new clientes();
  $oLogin->validar(true, $oSite->sUrlBase.'/conta/login/');

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
      ?>
      <div id="conteudo" class="animacao-start">
        <h1 class="titulo-02">Conta de usuário</h1>
        <p>Que bom que você esta aqui!</p>
        <p>Verifique através do menu de usuário as ações que você pode executar.</p>
      </div>
      <div class="limpa"></div>
        
    </div>
    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>    
  </body>
</html>
