<?php
  session_start();
  $sPgAtual = 'contato';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  include      'modulosPHP/PHPMailer_5.2.1/class.phpmailer.php';
  
  $oSite       = new pimentas();
  $oProdutos   = new produtos();

  $oDadosAdm = new usuario_admin();
  
  /**********************************************************************
    Cabeçalho de tratamento do formulário
  **********************************************************************/
  $oContatos = new tc_contatos();

  if (isset($_POST['sAcao'])) {

    // Persistencia de dados na tela
    $oContatos->inicializaAtributos();

    //Anti SQL injection
    foreach ($_POST as $sNome => $mValor) {
      if (!is_array($mValor)) {
        $_POST[$sNome] = $oSite->anti_sql_injection($mValor);
      }
    }

    /*
     Campos com tratamento adicional
    */
    $_POST['CMPcontatos-empresa']         = '';
    $_POST['CMPcontatos-cargo']           = '';
    $_POST['CMPcontatos-endereco']        = '';
    $_POST['CMPcontatos-cidade']          = '';
    $_POST['CMPcontatos-uf']              = '';
    $_POST['CMPcontatos-ip']              = $_SERVER['REMOTE_ADDR'];
    $_POST['CMPcontatos-comonosconheceu'] = '';

    // Manipulação dos dados
    $oManContatos = new tc_contatos();
    $oManContatos->inicializaAtributos();


    try {

      $oManContatos->sBackpage = $CFGaPgAtual[$sPgAtual]['backPage'];
      $oManContatos->salvar();
      
      // Em caso de sucesso ao salvar a imagem manda email e reinicia atributos
      if ($oManContatos->aMsg['iCdMsg'] == 0) {
        ob_start(); ?>
        <table style="width: 98%" border="0">
          <tr>
            <td style="width: 30%"><b>Contato:</b></td>
            <td style="width: 70%"><?php echo $oManContatos->NM_CONTATO[0]; ?></td>
          </tr>
          <tr>
            <td><b>Email</b></td>
            <td><?php echo $oManContatos->TX_EMAIL[0]; ?></td>
          </tr>
          <tr>
            <td><b>Telefone:</b></td>
            <td><?php echo $oManContatos->TX_TELEFONE[0]; ?></td>
          </tr>
          <tr>
            <td><b>Celular:</b></td>
            <td><?php echo $oManContatos->TX_CELULAR[0]; ?></td>
          </tr>
          <tr>
            <td><b>Gostaria de :</b></td>
            <td><?php echo $oManContatos->TX_ASSUNTO[0]; ?></td>
          </tr>
          <tr><td colspan="2" ><b>Mensagem:</b></td></tr>
          <tr><td colspan="2" ><?php echo $oManContatos->DE_MENSAGEM[0]; ?></td></tr>
          <tr><td colspan="2" style="font-style: italic;"><?php echo date('h:i:s d/m/Y ').' - IP: '.$oManContatos->TX_IP[0]; ?></td></tr>
        </table>
        <?php
        
        $sMsgHtml = ob_get_contents();
        ob_end_clean();
//        $this->oUtil->sRemetente     = 'site@referenciamarketing.com.br';
//        $this->oUtil->aDestinatarios = $CFGaDestinatariosContato;
//        $this->oUtil->sAssunto       = 'Contato enviado pelo site';
//        $this->oUtil->sMensagem      = $sMsgHtml;

        $oSite->buscarParametro(array('HOST', 'PASS_MAIL', 'REMETENTE', 'DESTINATARIOS'));
        
        $sDestinatario = array_shift($oSite->aParametros['DESTINATARIOS']);
        
        $aDestinatariosContatoCC = array();
        if (is_array($oSite->aParametros['DESTINATARIOS'])) {
          $aDestinatariosContatoCC = $oSite->aParametros['DESTINATARIOS'];
        }
        

        $oEmail = new PHPMailer();
        $oEmail->IsSMTP();
        $oEmail->IsHTML();
        $oEmail->Host     = $oSite->aParametros['HOST'][0];
        $oEmail->Password = base64_encode($oDadosAdm->SCAPE.$oSite->aParametros['PASS_MAIL'][0]);
        $oEmail->SetFrom($oSite->aParametros['REMETENTE'][0], 'Mercado Dos Sabores');
        $oEmail->AddAddress($sDestinatario);

        foreach ($aDestinatariosContatoCC as $sDestinatarioCC) {
          $oEmail->AddBCC($sDestinatarioCC);
        }
        
        $oEmail->Subject = 'Contato enviado pelo site Mercado dos Sabores';
        $oEmail->Body = $sMsgHtml;

        if (!$oEmail->Send()) {
          echo $oEmail->ErrorInfo;
        }
        
        unset($_POST);
        $oContatos->inicializaAtributos();
      }
        
      $aMsg = $oManContatos->aMsg;

    } catch (excecoes $e) {
      $aMsg = $e->aMsg;
    }

  } else {
    try {
      if (isset ($_REQUEST['n'])) {

        if (!is_numeric($_REQUEST['n'])) {
          throw new excecoes(10, $sPgAtual);
        }

        $iId = $oSite->anti_sql_injection($_REQUEST['n']);
        $oContatos->listar('WHERE id = '.$iId);

        if ($oContatos->iLinhas < 1) {
          throw new excecoes(15, $sPgAtual);
        }
      } else {
        $oContatos->inicializaAtributos();
      }

    } catch (excecoes $e) {

      $e->getErrorByCode();
      header('location:'.$CFGaPgAtual[$sPgAtual]['backPage']);
      exit;
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
      <div id="conteudo">
        <h1 class="titulo">Contato</h1>
          <div id="msg_ret"><?php $oSite->msgRetAlteracoes($aMsg, '', '', false); ?></div>

          <form id="FRMcontatos" name="FRMcontatos" action="<?php echo $oSite->sUrlBase.'/contato/'?> " method="post">
            <input type="hidden" name="sAcao" value="<?php echo (isset($_GET['n']) ? 'editar' : 'inserir'); ?>" />
            <input type="hidden" name="CMPcontatos-id" value="<?php echo $oContatos->ID[0]; ?>" />
            <table class="tab_lista_registros" style="width: 600px">

              <tr>
                <td class="infoheader" style="width: 30%">Contato:</td>
                <td class="infovalue" style="width: 70%"><input class="w70" type="text" name="CMPcontatos-contato" value="<?php echo $oContatos->NM_CONTATO[0]; ?>" /></td>
              </tr>
              <tr>
                <td class="infoheader">Email:</td>
                <td class="infovalue"><input class="w70" type="text" name="CMPcontatos-email" value="<?php echo $oContatos->TX_EMAIL[0]; ?>" /></td>
              </tr>
              <tr>
                <td class="infoheader">Telefone:</td>
                <td class="infovalue"><input class="w70" type="text" name="CMPcontatos-telefone" value="<?php echo $oContatos->TX_TELEFONE[0]; ?>" /></td>
              </tr>
              <tr>
                <td class="infoheader">Celular:</td>
                <td class="infovalue"><input class="w70" type="text" name="CMPcontatos-celular" value="<?php echo $oContatos->TX_CELULAR[0]; ?>" /></td>
              </tr>
              <tr>
                <td class="infoheader">Eu gostaria de:</td>
                <td class="infovalue">
                  <?php 
                    $oSite->montaSelect('CMPcontatos-assunto', $CFGaTiposContato, $oContatos->TX_ASSUNTO[0]);
                  ?>
                </td>
              </tr>
              <tr>
                <td class="infoheader">Mensagem:</td>
                <td class="infovalue">
                  <textarea class="w80" rows="4" cols="10" name="CMPcontatos-mensagem"><?php echo $oContatos->DE_MENSAGEM[0]; ?></textarea>
                </td>
              </tr>
              <tr>
                <td></td>
                <td>
                  <input class="bt" type="submit" value="Enviar" />
                </td>
              </tr>
            </table>
          </form>
        <h1 class="titulo">Telefones</h1>
          <p>Vivo&nbsp;&nbsp;&nbsp;<b>(51) 8228-6475</b></p>
          <p>Claro&nbsp;<b>(51) 9682-2854</b></p>
        <h1 class="titulo">Email</h1>
        <p><a href="mailto:atendimento@mercadodossabores.com.br">atendimento@mercadodossabores.com.br</a></p>
        <div style="height: 100px">&nbsp;</div>
      </div>
      <div class="limpa"></div>
    </div>
    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>    
  </body>
</html>
