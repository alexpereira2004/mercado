<?php
  session_start();
  $sPgAtual = 'index';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';
  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="pt-br" xmlns="http://www.w3.org/1999/xhtml" xml:lang="pt-br" dir="ltr">
  <head>
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo']; ?></title>

    <link href='http://fonts.googleapis.com/css?family=Quando' rel='stylesheet' type='text/css' />
    <link href="comum/estilos.css" media="all"  rel="stylesheet" type="text/css" />
    
  </head>
  <body>
    <div id="pagina" style="margin-top: 50px;">
      <div id="conteudo">
        <div style="height: 100px">&nbsp;</div>
        <div class="centro">
          <img src="comum/imagens/site/Mercado-dos-Sabores.png" alt="Mercado dos Sabores" title="Mercado dos Sabores" />
          <h1>Em breve</h1>
        </div>
        <div style="height: 100px">&nbsp;</div>
      </div>

    </div>
  </body>
</html>