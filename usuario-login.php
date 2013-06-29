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
    if ($_POST['sAcao'] == 'acessar') {
      $oManCliente->tratarFormLogin(1);
      $aMsgCliCadastrado = isset($oManCliente->aMsg['iCdMsg']) ? $oManCliente->aMsg : $aMsg;
    }
    
    if ($_POST['sAcao'] == 'novo-cadastro') {
      $oManCliente->tratarFormLoginNaoCadastrado(1);
      $aMsgCliNovo = isset($oManCliente->aMsg['iCdMsg']) ? $oManCliente->aMsg : $aMsg;
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
        <h1 class="titulo-02">Identificação</h1>
        <?php
          // Se já estiver logado, simplesmente mostrar os dados do usuário
          if ($bLogado) {
            $oLogin->listar('WHERE id = '.$_SESSION[carrinho::getUsuarioSessao()]['login']['id_usu']);
            $oSite->box01('Confirmação dos dados de identificação', $oSite->apresentarDadosCliente($oLogin));


            // Se ainda não estiver logado, então deverá mostrar o formulário de login
          } else { ?>
            <div style="width: 49%; float: left;">
              <?php 
                $sAction = $oSite->sUrlBase.'/conta/login#target-identificacao';
                $oSite->box01('Já sou cadastrado', $oSite->montarFormLogin($sAction, $aMsgCliCadastrado));
              ?>
            </div>
            <div style="width: 49%; float: right;">
              <?php
                $sAction = $oSite->sUrlBase.'/conta/login#target-identificacao';
                $oSite->box01('Cadastre-se!', $oSite->montarFormLoginNaoCadastrado($sAction, $aMsgCliNovo));
              ?>
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
