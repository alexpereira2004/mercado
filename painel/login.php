<?php
  session_cache_expire(1);
  session_start();
  $sPgAtual = 'login';

  include '../modulosPHP/class.wTools.php';
  include '../modulosPHP/class.admin.php';
  include '../modulosPHP/config.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';

  $oUtil     = new wTools();
  $oAdmin    = new usuario_admin();


  if (isset($_POST['sAcao'])) {

    // Testa a tentativa de acesso ao sistema
    if ($_POST['sAcao'] == 'acessar') {
      $aCampos = array( 0 => array ('Usuário/email', $_POST['CMPlogin']),
                        1 => array ('Senha', $_POST['CMPpass'])
                      );
      try {

        // Valida preenchimento dos campos
        if ($oUtil->valida_Preenchimento($aCampos) !== true) {
          $aMsg = array('iCdMsg' => 2, 'sMsg' => 'O preenchimento de todos os campos é obrigatório!');
          throw new Exception;
        }

        // Faz o teste para ver se os dados são válidos
        $oManUsuario = new usuario_admin();
        if ($oManUsuario->validarLogin($_POST['CMPlogin'], $_POST['CMPpass'], true)) {
          header('location: index.php');
        } else {
          if (isset($oManUsuario->oUsuario->CD_STATUS[0]) && $oManUsuario->oUsuario->CD_STATUS[0] == 'I') {
            $aMsg = array('iCdMsg' => 2, 'sMsg' => 'Usuário inativo, consulte o administrador');
            throw new Exception;
          }
          $aMsg = array('iCdMsg' => 1, 'sMsg' => 'Usuário ou senha não é válido');
          throw new Exception;
        }

      } catch (Exception $exc) {
        // Não faz nada
      }
    }
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1"></meta>
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'];?></title>
    <link href="../comum/estilos.css" media="all" rel="stylesheet"  type="text/css" />
    <link href="../comum/login.css " media="all" rel="stylesheet"  type="text/css" />
  </head>
  <body class="login">
    <img src="../comum/imagens/site/Mercado-dos-Sabores.png" alt="All Ideas" />
    <div class="login">
      <?php
        $oUtil->msgRetAlteracoes($aMsg);
      ?>
      <form name="FRMlogin" id="FRMlogin" action="login.php" method="post">
        <input type="hidden" name="sAcao" value="acessar" />
        <label for="CMPlogin">Usuário ou email</label><br />
        <input type="text" id="CMPlogin" name="CMPlogin" value="" class="w80" /><br />
        <label for="CMPpass">Senha</label><br />
        <input type="password" id="CMPpass" name="CMPpass" value="" class="w80" /><br />
        <input type="submit" class="" value="Acessar" />
      </form>
      <div class="info-extra">
        <a href="#">Esqueci os dados de login</a>
      </div>
    </div>
    <div class="limpa"></div>
  </body>
</html>