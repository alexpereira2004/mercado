<?php
  session_start();
  $sPgAtual = 'usuario-login';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  include      'modulosPHP/PHPMailer_5.2.1/class.phpmailer.php';
  
  $oSite       = new pimentas();

  
  $oLogin = new clientes();
  $bLogado = $oLogin->validar();
  
  $aMsgCliCadastrado = $aMsg;
  $aMsgCliNovo       = $aMsg;
  
  if (isset($_POST['sAcao'])) {

    $oManCliente = new clientes();

    // Testa a tentativa de acesso ao sistema
    if ($_POST['sAcao'] == 'logout') {
      $oManCliente->logoutCliente();
      header('location:'.$oSite->sUrlBase);
    }
    
    if ($_POST['sAcao'] == 'continuar') {
      header('location:'.$oSite->sUrlBase);
    }
  }

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
    <script type="text/javascript" src="<?php echo $oSite->sUrlBase;?>/modulosJS/maskedinput/jquery.maskedinput-1.3.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        
        $(".mask_cep").mask("99999-999");
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
        <h1 class="titulo-02">Encerrar sessão</h1>
        <?php
          // Se já estiver logado, simplesmente mostrar os dados do usuário
          if ($bLogado) {
            $oLogin->listar('WHERE id = '.$_SESSION[carrinho::getUsuarioSessao()]['login']['id_usu']);
            //$oSite->box01('Confirmação dos dados de identificação', $oSite->apresentarDadosCliente($oLogin));
            ?>
            <form action="<?php echo $oSite->sUrlBase; ?>/conta/logout/" method="post">
              <input type="hidden" name="sAcao" value="logout" />
              <table class="w90">
                <tr>
                  <td class="infoheader w40">Deseja encerrar a sua navegação?</td>
                  <td style="float: left"><input type="submit" value="Sim!" /></td>
                </tr>
              </table>
            </form>
            <form action="<?php echo $oSite->sUrlBase; ?>/conta/logout/" method="post">
              <input type="hidden" name="sAcao" value="continuar" />
              <table class="w90">
                <tr>
                  <td class="infoheader w40">Deseja continuar navegando?</td>
                  <td style="float: left"><input type="submit" value="Continuar navegando!" /></td>
                </tr>
              </table>
            </form>
            <?php

            // Se ainda não estiver logado, então deverá mostrar o formulário de login
          } else { ?>
            <div>
              No momento você não esta logado no sistema!
            </div>
            <div class="limpa"></div>
        <?php   
          }
        ?>
      </div>
      <div class="limpa"></div>
        
    </div>
    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>    
  </body>
</html>
