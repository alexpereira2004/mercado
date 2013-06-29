validar<?php
  session_start();
  $sPgAtual = 'clientes';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tc_clientes.php';

  $oLogin = new usuario_admin();
  $oLogin->confereLogin();


  $oAdmin = new admin();
  $oMenu = new adapter_tclv_menu();
  $oClientes = new tc_clientes();

  if (isset ($_POST['sAcao'])) {

    try {

      // Validação dos campos obrigatórios
      $aValidar = array (0 => array('Nome' , $_POST['CMPnome']) );
      if ($oAdmin->valida_Preenchimento($aValidar) !== true) {
        $aMsg = $oAdmin->aMsg;
        throw new Exception;
      }

      // Manipular dados
      $oManClientes = new tc_clientes();
      $oManClientes->NM_CLIENTE[0] = $oAdmin->anti_sql_injection($_POST['CMPnome']);
      $sAcao = $_POST['sAcao'];

      /********** Novo registro **********/
      if ($sAcao == 'novo') {
        if ($oManClientes->inserir() !== true) {
          $mResultado = $oManClientes->aMsg;
          throw new Exception;
        }
        // Se inseriu com sucesso, manda para página de retorno
        $aCampos = $oManClientes->aMsg;
        $aCampos['retMsg'] = true;
        $oAdmin->redirFRM($CFGaPgAtual[$sPgAtual]['backPage'], $aCampos);

      /********** Editar dados **********/
      } elseif ($sAcao == 'editar') {
        $oManClientes->ID[0] = $_POST['CMPid'];
        $oManClientes->editar($oManClientes->ID[0]);
        $oClientes = $oManClientes;
        $mResultado = $oManClientes->aMsg;
      }

    } catch (Exception $exc) {
      $oClientes = $oManClientes;
      $mResultado = $aMsg;
    }



  } else {

    try {
      if (isset($_GET['n'])) {
        $_GET['n'] = $oAdmin->anti_sql_injection($_GET['n']);

        // Editar conteúdo
        if(is_numeric($_GET['n'])) {
          $iId = $_GET['n'];
          $sFiltro = 'WHERE id = '.$iId;
          $oClientes->listar($sFiltro);
          $sAcao = 'editar';
          if ($oClientes->iLinhas != 1) {
            throw new Exception;
          }
        } else {

          // Adicionar conteúdo
          if ($_GET['n'] == 'novo') {
            $oClientes->inicializaAtributos();
            $sAcao = 'novo';
          } else {
            throw new Exception;
          }
        }
      }
    } catch (Exception $exc) {
      $oAdmin->redirFRM($CFGaPgAtual[$sPgAtual]['backPage']);
      exit;
    }

  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'].' - '.($sAcao == 'inserir' ? 'Inserir novo registro' : 'Editar '.$oClientes->NM_CLIENTE[0]);?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script type="text/javascript" src="../modulosJS/jwysiwyg/jquery.wysiwyg.js"></script>
    <link rel="stylesheet" href="../modulosJS/jwysiwyg/jquery.wysiwyg.css" type="text/css" />
    <script type="text/javascript">
      $(document).ready(function(){

      });
    </script>

  </head>
  <body>
    <div id="pagina">
      <?php
        $oAdmin->cabecalho();
        $oAdmin->montarMenu($sPgAtual);
      ?>
      <div id="corpo">
        <?php
          $oAdmin->msgRetAlteracoes($mResultado);
          $oAdmin->breadCrumbs();
          $oAdmin->minheight('600');
        ?>
        <div id="toolBar">
          <a href="<?php echo $CFGaPgAtual[$sPgAtual]['backPage']; ?>"><img src="../comum/imagens/icones/doc_page_previous.png" alt="Voltar" /></a>
        </div>

        <form id="FRMclientes" name="FRMclientes" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" style="margin-top: 10px;">
          <input type="hidden" name="sAcao" value="<?php echo $sAcao; ?>" />
          <input type="hidden" name="CMPid" value="<?php echo $oClientes->ID[0]; ?>" />
          <fieldset>
            <legend>Cadastro de Clientes:</legend>
            <table>
              <tr>
                <td>Nome:</td>
                <td><input type="text" name="CMPnome" value="<?php echo $oClientes->NM_CLIENTE[0]; ?>" /></td>
              </tr>
            </table>
          </fieldset>

          <table style="margin-top: 20px">
            <tr>
              <td>&nbsp;</td>
              <td>
                <input class="bt" type="reset" value="Limpar" />
                <input class="bt" type="submit" value="Salvar" />
              </td>
            </tr>
          </table>
        </form>
      </div>
      <div class="limpa"></div>
      <?php
        $oAdmin->rodape($sPgAtual);
      ?>
    </div>
  </body>
</html>