<?php
  session_start();
  $sPgAtual = 'usuario-cadastro';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  include      'modulosPHP/PHPMailer_5.2.1/class.phpmailer.php';
  
  $oSite       = new pimentas();

  $oLogin      = new clientes();
  $oLogin->validar(true, $oSite->sUrlBase.'/conta/login/');
  $iIdCliente = $_SESSION[carrinho::getUsuarioSessao()]['login']['id_usu'];
  $oCliente   = new tc_clientes();
  $oEnderecos = new tc_clientes_enderecos();
  $oAdpterCli = new clientes();
  
  
  if (isset($_POST['sAcao'])) {
    if ($_POST['sAcao'] == 'editar') {
      $oAdpterCli->tratarFormAtualizarCadastro($iIdCliente);
      $aMsg = $oAdpterCli->aMsg;
      $oCliente = $oAdpterCli->oManClientes;
    }

    if ($_POST['sAcao'] == 'editar-endereco') {
      $oAdpterCli->tratarformAtualizarEndereco($iIdCliente);
      $aMsg = $oAdpterCli->aMsg;
      $oEnderecos = $oAdpterCli->oManEnderecos;
    }

    if ($_POST['sAcao'] == 'editar-email') {
      $oAdpterCli->tratarformAtualizarEmail($iIdCliente);
      $aMsg = $oAdpterCli->aMsg;
      $oCliente = $oAdpterCli->oManClientes;
    }

    if ($_POST['sAcao'] == 'editar-senha') {
      $oAdpterCli->tratarformAtualizarSenha($iIdCliente);
      $aMsg = $oAdpterCli->aMsg;
      $oCliente = $oAdpterCli->oManClientes;
    }

  } elseif (isset ($_GET['n']) && ($_GET['n'] == 'enderecos' || $_GET['n'] == 'enderecos/')) {
    $oEnderecos->listar('WHERE id_cliente = '.$iIdCliente);
  } else {
    $oCliente->listar('WHERE id = '.$iIdCliente);
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
        $('.buscar_cep').click(function(){
          sCep = $('#CMPcep').val();
          buscarCepCliente(sCep, '');
        });

        $(".mask_cep").mask("99999-999");
        $(".mask_data").mask("99/99/9999");
        $(".mask_telefone").mask("(99)9999-9999");
        $(".mask_cnpj").mask("99.999.999/9999-99");
        $(".mask_cpf").mask("999.999.999-99");
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
        <?php
             

          if (isset($_GET)) {
            switch ($_GET['n']) {
              case 'enderecos/':
              case 'enderecos':
                $sAcao = 'atualizar-enderecos';
                $sPagina = 'Endereço';
                $sForm = $oAdpterCli->formAtualizarEndereco($oEnderecos);
                break;

              case 'email/':
              case 'email':
                $sAcao = 'atualizar-email';
                $sPagina = 'Email';
                $sForm = $oAdpterCli->formAtualizarEmail($oCliente);
                break;

              case 'senha/':
              case 'senha':
                $sAcao = 'atualizar-senha';
                $sPagina = 'Senha';
                $sForm = $oAdpterCli->formAtualizarSenha($oCliente);
                break;
              
              default:
                $sAcao = 'atualizar-cadastro';
                $sPagina = 'Cadastro';
                $sForm = $oAdpterCli->formAtualizarCadastro($oCliente);
                break;
            }
          }
        ?>
        <h3 class="titulo-02">Alterar <?php echo $sPagina; ?></h3>
        <div id="msg_ret"><?php $oSite->msgRetAlteracoes($aMsg, '', '', false); ?></div>
        <?php echo $sForm; ?>
      </div>
      <div class="limpa"></div>
        
    </div>
    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>    
  </body>
</html>