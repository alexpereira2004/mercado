<?php
  session_start();
  $sPgAtual = 'pagina-atual';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  $oSite       = new pimentas();
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
      <div id="vitrine">

        <h1 class="titulo">Título</h1> <br /><br />
        <span class="botao1" >Teste 2</span><br /><br />
        <a class="botao-comprar" href="#" >Comprar</a><br /><br />
        <a href="<?php echo $oSite->sUrlBase; ?>/painel" class="botao1">Administração</a><br /><br />
        <input type="submit" class="bt_salvar" value="Salvar" /><br /><br />
        
        <div class="msg-ok">Mensagem ok</div>
        <div class="msg-erro">Mensagem de Erro</div>
        <div class="msg-atencao">Mensagem de Atenção</div><br />
        <a href="#">Elemento "a"</a><br />
        <span class="link">Link 01</span><br />
        <span class="link-1">Link 02</span><br />
        <span class="link-2" style="background: #F2CB3E; height: 30px;">Link 03</span>
        
        <div class="box-01">
          <div class="cabecalho">Box-01</div>
          <div class="conteudo">A Serra Circular de Bancada Black & Decker BT1800 é projetada para fornecer desempenho de alta qualidade.</div>
        </div>
      </div>
      <div class="limpa"></div>
    </div>

    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>
    
  </body>
</html>
