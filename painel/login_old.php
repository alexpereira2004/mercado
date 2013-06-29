<?php
  session_start();
  $sPgAtual = 'adm';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/adapter.usuarioAdmin.php';

  $oLogin = new usuario_admin();
  $oAdmin = new admin();
  
  $bRet = false;
  $sUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
  if (isset($_POST['CMPacao'])) {
    $bRet = $oLogin->validarLogin($_POST['CMPlogin'], $_POST['CMPsenha'], true);

    if ($bRet) {
      $_SESSION['adm_usuario']  = $oLogin->TX_EMAIL[0];
      $_SESSION['adm_password'] = $oLogin->TX_SENHA[0];
      $oLogin->registrarLogin();
      header('location:index.php');
    } else {
      $oLogin->sOrigem = $_POST['CMPsOrigem'];
      $oLogin->sMsg    = 'Login ou senha incorreto(s)';
      $oLogin->registrarTentativaDeLogin($_POST['CMPlogin'], $_POST['CMPsenha']);
    }
  }
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title><?php //echo $CFGaPgAtual[$sPgAtual]['titulo']; ?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
    ?>
  </head>
  <body>
    <div id="pagina">
      <?php
        $oAdmin->minwidth('800');
        $oAdmin->minheight('800');
      ?>
      <div id="conteiner-login">
        <div id="esquerda">&nbsp;</div>
        <div id="centro">
          <form name="FRMlogin" id="FRMlogin" action="login.php" method="post">
            <input type="hidden" name="CMPacao" value="login" />
            <input type="hidden" name="CMPsOrigem" value="<?php echo (isset($_POST['CMPsOrigem']) ? $_POST['CMPsOrigem'] : $sUrl );?>" />
            <table class="w90" style="margin-top: 90px">
              <tr>
                <td colspan="2">
                  <div class="<?php echo $oLogin->sResultado == 'erro'? 'msg-erro' : 'msg-vazio'?>  w90">
                    <?php echo $oLogin->sMsg; ?>
                  </div>
                </td>
              </tr>
              <tr>
                <td><b>Login</b></td>
                <td class="w90"><input type="text" value="" class="input w90" name="CMPlogin" /></td>
              </tr>
              <tr>
                <td><b>Senha</b></td>
                <td><input type="password" value="" style="margin-top: 10px" class="input w90" name="CMPsenha" /></td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td><input type="submit" class="bt" value="Acessar" /></td>
              </tr>
            </table>
          </form>
        </div>
        <div id="direita">&nbsp;</div>
        <div class="limpa">&nbsp;</div>
      </div>
    </div>
  </body>
</html>
