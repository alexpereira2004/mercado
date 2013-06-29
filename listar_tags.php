<?php
  session_start();
  $sPgAtual = 'listagem-tags-det';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  $oSite       = new pimentas();
  $oProdutos   = new produtos();
  $oTags = new tc_tags();
  
  /****************************************************************************
  Trata GET
  *****************************************************************************/

  $aDados = explode('/', $_GET['n']);  
  $aDados = explode('-', $aDados[0]);
  
  $iId = (int) array_pop($aDados);
  $sTxLink = implode('-', $aDados);

  try {
    
    // Validação do ID
    if ((!is_numeric($iId)) )
      throw new Exception;
    
    $sFiltro = 'WHERE id = '.$iId;
    $oTags->listar($sFiltro);


  } catch (Exception $exc) {
    header('location:'.$oSite->sUrlBase);
  }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'].' - Tags - '.$oTags->NM_TAG[0]; ?></title>

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
        <?php
          $sFiltro  = " WHERE 1 = 1 "."\n";
          $sFiltro .= " AND cd_status = 'A'"."\n";
          $sFiltro .= " AND id_tag_agrupado  REGEXP '".$iId."'"."\n";
          $sFiltro .= " ORDER BY id ";

          $oProdutos->buscarItensVitrine($sFiltro);
          if ($oProdutos->iQntItens > $CFGiQntItensPorPagina) { ?>
            <div class="holder"></div> <?php
          } ?>
        
        <ul id="itemContainer">
          <?php 
          for ($i = 0; $i < $oProdutos->iQntItens; $i++) { ?>
            <li class="item"><?php echo $oProdutos->aProdVitrine[$i]; ?></li>
            <?php
          }
          ?>
        </ul>
        <div class="limpa"></div>
        <?php
          if ($oProdutos->iQntItens > $CFGiQntItensPorPagina) { ?>
            <div class="holder"></div> <?php
          } ?>
        </div>
        <div class="limpa"></div>
        

      </div>
      <div class="limpa"></div>
    </div>

    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>
    
  </body>
</html>
