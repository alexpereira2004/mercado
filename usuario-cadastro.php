<?php
  session_start();
  $sPgAtual = 'usuario-cadastro';

  include      'modulosPHP/config.php';
  include      'modulosPHP/load.php';

  include      'modulosPHP/PHPMailer_5.2.1/class.phpmailer.php';
  
  $oSite       = new pimentas();

  $oDadosAdm = new usuario_admin();
  
  $sPagRetorno = (isset($_POST['CMPpagRetorno'])) ? $_POST['CMPpagRetorno'] : $oSite->sUrlBase.'/conta/cadastro/';

  
  /**********************************************************************
    Cabeçalho de tratamento do formulário
  **********************************************************************/
  $oPessoaFisica = new clientes('PF');
  $oPessoaJuridica = new clientes('PJ');

  if (isset($_POST['sAcao'])) {

    // Persistencia de dados na tela
    $oPessoaFisica->inicializaAtributos();
    $oPessoaJuridica->inicializaAtributos();

    //Anti SQL injection
    foreach ($_POST as $sNome => $mValor) {
      if (!is_array($mValor)) {
        $_POST[$sNome] = $oSite->anti_sql_injection($mValor);
      }
    }

    // Manipulação dos dados
    $oManClientes = new clientes($_POST['CMPtpCadastro']);
    $oManClientes->inicializaAtributos();


    try {

      $oManClientes->sBackpage = $CFGaPgAtual[$sPgAtual]['backPage'];
      $oManClientes->salvar();
      $aMsg = $oManClientes->aMsg;
      
      // No sucesso, cria a sessão do usuário e redireciona a página
      if ($oManClientes->aMsg['iCdMsg'] == 0) {
        $sEmail = $oManClientes->oCli->TX_EMAIL[0];
        $sSenha = $oManClientes->oCli->TX_SENHA[0];
        
        //Envia email de confirmação de cadastro
        $oEmail = new emails();
        $oEmail->criacaoConta($sEmail, $oManClientes->oCli);

        $oManClientes = new clientes();
        $oManClientes->validarLogin($sEmail, $sSenha);
        header('location:'.$_POST['CMPpagRetorno']);
      }

    } catch (excecoes $e) {
      $aMsg = $e->aMsg;
    }

  } else {
    try {

      $oPessoaFisica->inicializaAtributos();
      $oPessoaJuridica->inicializaAtributos();
      
      // Caso exista dados de usuário na sessão, aproveita-los adicionando
      //  automaticamente ao formulário
      if (isset($_SESSION['tmp']['cadastro'])) {
        $oPessoaFisica->oCli->TX_EMAIL[0] = $_SESSION['tmp']['cadastro']['sEmail'];
        $oPessoaFisica->oEnd->NU_CEP[0]   = $_SESSION['tmp']['cadastro']['sCep'];

        $oPessoaJuridica->oCli->TX_EMAIL[0] = $_SESSION['tmp']['cadastro']['sEmail'];
        $oPessoaJuridica->oEnd->NU_CEP[0]   = $_SESSION['tmp']['cadastro']['sCep'];
        
        $sPagRetorno = $_SESSION['tmp']['cadastro']['sPagRetorno'];

        unset($_SESSION['tmp']['cadastro']);
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
    <script type="text/javascript" src="<?php echo $oSite->sUrlBase;?>/modulosJS/maskedinput/jquery.maskedinput-1.3.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $(".mask_cep").mask("99999-999");
        $(".mask_data").mask("99/99/9999");
        $(".mask_telefone").mask("(99)9999-9999");
        $(".mask_cnpj").mask("99.999.999/9999-99");
        $(".mask_cpf").mask("999.999.999-99");

        $('.buscar_cep_pf').click(function(){
          sCep = $('#CMPcepPF').val();
          buscarCepCliente(sCep, 'PF');
        });
        $('.buscar_cep_pj').click(function(){
          sCep = $('#CMPcepPJ').val();
          buscarCepCliente(sCep, 'PJ');
        });

        $('.conteiner-interno-abas').corner('tl br 20px');
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
        <h1 class="titulo-02">Cadastro</h1>
        <div id="msg_ret"><?php $oSite->msgRetAlteracoes($aMsg, '', '', false); ?></div>
        <?php
          /*********************************************************************
            Aba 01: Pessoa Física
          **********************************************************************/
          ob_start(); ?>
          <div class="conteiner-interno-abas">
            <form method="post" action="<?php echo $oSite->sUrlBase; ?>/conta/cadastro/">
              <input type="hidden" name="sAcao" value="inserir" />
              <input type="hidden" name="CMPtpCadastro" value="PF" />
              <input type="hidden" name="CMPpagRetorno" value="<?php echo $sPagRetorno; ?>" />
              <input type="hidden" id="CMPsUrlBase" name="CMPsUrlBase" value="<?php echo $oSite->sUrlBase; ?>" />
              <h1 class="titulo">Dados Pessoais</h1>
              <table style="width: 98%">
                <tr>
                  <td class="infoheader">Nome*</td>
                  <td class="infoheader">Sobrenome*</td>
                  <td class="infoheader">RG*</td>
                  <td class="infoheader">Telefone Fixo*</td>
                </tr>
                <tr>
                  <td class="infovalue"><input type="text" name="CMPclientes-cliente-PF" value="<?php echo $oPessoaFisica->oCli->NM_CLIENTE[0]; ?>" /></td>
                  <td class="infovalue"><input type="text" name="CMPclientes-sobrenome-PF" value="<?php echo $oPessoaFisica->oCli->NM_SOBRENOME[0]; ?>" /></td>
                  <td class="infovalue"><input type="text" name="CMPclientes-rg-PF" value="<?php echo $oPessoaFisica->oCli->NU_RG[0]; ?>" /></td>
                  <td class="infovalue"><input type="text" name="CMPclientes-tel-PF" value="<?php echo $oPessoaFisica->oCli->TX_TEL[0]; ?>" class="mask_telefone" /></td>
                </tr>
                <tr>
                  <td class="infoheader">Data Nascimento*</td>
                  <td class="infoheader">Sexo*</td>
                  <td class="infoheader">CPF*</td>
                  <td class="infoheader">Telefone Celular</td>
                </tr>
                <tr>
                  <td class="infovalue"><input type="text" name="CMPclientes-nascimento-PF" value="<?php echo $oPessoaFisica->oCli->DT_NASCIMENTO[0]; ?>" class="mask_data" /></td>
                  <td class="infovalue">
                    <?php 
                      $oSite->montaSelect('CMPclientes-sexo-PF', $CFGaSexo, $oPessoaFisica->oCli->CD_SEXO[0]);
                    ?>
                  </td>
                  <td class="infovalue"><input type="text" name="CMPclientes-cpf-PF" value="<?php echo $oPessoaFisica->oCli->NU_CPF[0]; ?>" class="mask_cpf" /></td>
                  <td class="infovalue"><input type="text" name="CMPclientes-cel-PF" value="<?php echo $oPessoaFisica->oCli->TX_CEL[0]; ?>" class="mask_telefone" /></td>
                </tr>
              </table>

              <h1 class="titulo">Dados de Endereço</h1>
              <table>
                <tr>
                  <td class="infoheader">Digite seu CEP:</td>
                  <td class="infovalue">
                    <input type="text" id="CMPcepPF" name="CMPclientes-enderecos-cep-PF" value="<?php echo $oPessoaFisica->oEnd->NU_CEP[0]; ?>" class="mask_cep"/>
                    <input type="button" class="bt_salvar buscar_cep_pf" value="Ok" />
                  </td>
                </tr>
              </table>
              <table style="width: 98%">
                <tr>
                  <td class="infoheader" style="width: 70%">Endereço Residencial*</td>
                  <td class="infoheader" style="width: 30%">Número*</td>
                </tr>
                <tr>
                  <td class="infovalue">
                    <input class="w98" type="text" id="CMPclientes-enderecos-logradouro-PF" name="CMPclientes-enderecos-logradouro-PF" value="<?php echo $oPessoaFisica->oEnd->NM_LOGRADOURO[0]; ?>" />
                    <input type="hidden" id="CMPclientes-enderecos-tp-logradouro-PF" name="CMPclientes-enderecos-tp-logradouro-PF" value="<?php echo $oPessoaFisica->oEnd->TP_LOGRADOURO[0]; ?>" />
                  </td>
                  <td class="infovalue"><input class="w98" type="text" name="CMPclientes-enderecos-numero-PF" value="<?php echo $oPessoaFisica->oEnd->TX_NUMERO[0]; ?>" /></td>
                </tr>
              </table>
              <table style="width: 98%">
                <tr>                 
                  <td class="infoheader" style="width: 40%">Complemento</td>
                  <td class="infoheader" style="width: 25%">Bairro*</td>
                  <td class="infoheader" style="width: 25%">Cidade*</td>
                  <td class="infoheader" style="width: 10%">Estado*</td>
                </tr>
                <tr>
                  <td class="infovalue"><input class="w98" type="text" id="CMPclientes-enderecos-complemento-PF" name="CMPclientes-enderecos-complemento-PF" value="<?php echo $oPessoaFisica->oEnd->TX_COMPLEMENTO[0]; ?>" /></td>
                  <td class="infovalue"><input class="w98" type="text" id="CMPclientes-enderecos-bairro-PF" name="CMPclientes-enderecos-bairro-PF" value="<?php echo $oPessoaFisica->oEnd->TX_BAIRRO[0]; ?>" /></td>
                  <td class="infovalue"><input class="w98" type="text" id="CMPclientes-enderecos-cid-PF" name="CMPclientes-enderecos-cid-PF" value="<?php echo $oPessoaFisica->oEnd->NM_CID[0]; ?>" /></td>
                  <td class="infovalue">
                    <?php $oSite->montaSelectDB('CMPclientes-enderecos-uf-PF', 'tc_estados', 'sg_uf', 'nm_uf', $oPessoaFisica->oEnd->NM_UF[0] , true)?>
                  </td>
                </tr>
              </table>
              <h1 class="titulo">Dados de Acesso ao Site</h1>
              <table style="width: 98%">
                <tr>                 
                  <td class="infoheader" style="width: 40%">E-mail*</td>
                  <td class="infoheader" style="width: 30%">Senha*</td>
                  <td class="infoheader" style="width: 30%">Confirmar Senha*</td>
                </tr>
                <tr>
                  <td class="infovalue"><input class="w98" type="text" name="CMPclientes-email-PF" value="<?php echo $oPessoaFisica->oCli->TX_EMAIL[0]; ?>" /></td>
                  <td class="infovalue"><input class="w98" type="password" autocomplete="off" name="CMPclientes-senha-PF" value="" /></td>
                  <td class="infovalue"><input class="w98" type="password" autocomplete="off" name="CMPclientes-senha2-PF" value="" /></td>
                </tr>
              </table>
              <br />
              <table style="width: 98%">
                <tr>
                  <td class="infoheader w30">Desejo receber informativos</td>
                  <td class="infovalue w70"><input type="checkbox" name="CMPclientes-recebe-news-PF" <?php echo $oPessoaFisica->oCli->CD_RECEBE_NEWS[0] == 'S' ? 'checked="checked"' : ''; ?> value="S"></td>
                </tr>
              </table>
              <br /><br />
              <input type="submit" value="Confirmar Cadastro" />
            </form>
          </div> <?php
          $sDadosContAbas_01 = ob_get_clean();
          /*********************************************************************
            Aba 02: Pessoa Jurídica
          **********************************************************************/
          ob_start(); ?>
          <div class="conteiner-interno-abas">
            <form method="post" action="<?php echo $oSite->sUrlBase; ?>/conta/cadastro/">
              <input type="hidden" name="sAcao" value="inserir" />
              <input type="hidden" name="CMPtpCadastro" value="PJ" />
              <input type="hidden" name="CMPpagRetorno" value="<?php echo $sPagRetorno; ?>" />
              <h1 class="titulo">Dados Pessoais</h1>
              <table style="width: 98%">
                <tr>
                  <td class="infoheader">Nome do comprador*</td>
                  <td class="infoheader">Sobrenome*</td>
                  <td class="infoheader">Setor*</td>
                  <td class="infoheader">Cargo*</td>
                </tr>
                <tr>
                  <td class="infovalue"><input type="text" name="CMPclientes-cliente-PJ" value="<?php echo $oPessoaJuridica->oCli->NM_CLIENTE[0]; ?>" /></td>
                  <td class="infovalue"><input type="text" name="CMPclientes-sobrenome-PJ" value="<?php echo $oPessoaJuridica->oCli->NM_SOBRENOME[0]; ?>" /></td>
                  <td class="infovalue"><input type="text" name="CMPclientes-setor-PJ" value="<?php echo $oPessoaJuridica->oCli->TX_SETOR[0]; ?>" /></td>
                  <td class="infovalue"><input type="text" name="CMPclientes-cargo-PJ" value="<?php echo $oPessoaJuridica->oCli->TX_CARGO[0]; ?>" /></td>
                </tr>
              </table>
              <table style="width: 98%">
                <tr>
                  <td class="infoheader w30">CNPJ*</td>
                  <td class="infoheader w30">Inscrição Estatual</td>
                  <td class="infoheader w40" colspan="2">Razão Social*</td>
                </tr>
                <tr>
                  <td class="infovalue"><input type="text" name="CMPclientes-cnpj-PJ" value="<?php echo $oPessoaJuridica->oCli->NU_CNPJ[0]; ?>" class="mask_cnpj w98"/></td>
                  <td class="infovalue"><input type="text" name="CMPclientes-ie-PJ" value="<?php echo $oPessoaJuridica->oCli->NU_IE[0]; ?>" class="w98" /></td>
                  <td class="infovalue"><input type="text" name="CMPclientes-razao-social-PJ" value="<?php echo $oPessoaJuridica->oCli->NM_RAZAO_SOCIAL[0]; ?>" class="w98" /></td>
                </tr>
              </table>
              <table style="width: 98%">
                <tr>
                  <td class="infoheader">Nome Fantasia*</td>
                  <td class="infoheader">Ramo/Atividade*</td>
                  <td class="infoheader">Telefone 1*</td>
                  <td class="infoheader">Telefone 2</td>
                </tr>
                <tr>
                  <td class="infovalue"><input type="text" name="CMPclientes-fantasia-PJ" value="<?php echo $oPessoaJuridica->oCli->NM_FANTASIA[0]; ?>" /></td>
                  <td class="infovalue"><input type="text" name="CMPclientes-segmento-PJ" value="<?php echo $oPessoaJuridica->oCli->TX_SEGMENTO[0]; ?>" /></td>
                  <td class="infovalue"><input type="text" name="CMPclientes-tel-PJ" value="<?php echo $oPessoaJuridica->oCli->TX_TEL[0]; ?>" class="mask_telefone" /></td>
                  <td class="infovalue"><input type="text" name="CMPclientes-cel-PJ" value="<?php echo $oPessoaJuridica->oCli->TX_CEL[0]; ?>" class="mask_telefone"/></td>
                </tr>
              </table>
              
              <h1 class="titulo">Dados de Endereço</h1>
              <table>
                <tr>
                  <td class="infoheader">Digite seu CEP:</td>
                  <td class="infovalue">
                    <input type="text" id="CMPcepPJ" name="CMPclientes-enderecos-cep-PJ" value="<?php echo $oPessoaJuridica->oEnd->NU_CEP[0]; ?>" class="mask_cep"/>
                    <input type="button" class="bt_salvar buscar_cep_pj" value="Ok" />
                  </td>
                </tr>
              </table>
              <table style="width: 98%">
                <tr>
                  <td class="infoheader" style="width: 70%">Endereço Residencial*</td>
                  <td class="infoheader" style="width: 30%">Número*</td>
                </tr>
                <tr>
                  <td class="infovalue">
                    <input class="w98" type="text" id="CMPclientes-enderecos-logradouro-PJ" name="CMPclientes-enderecos-logradouro-PJ" value="<?php echo $oPessoaJuridica->oEnd->NM_LOGRADOURO[0]; ?>" />
                    <input type="hidden" id="CMPclientes-enderecos-tp-logradouro-PJ" name="CMPclientes-enderecos-tp-logradouro-PJ" value="<?php echo $oPessoaJuridica->oEnd->TP_LOGRADOURO[0]; ?>" />
                  </td>
                  <td class="infovalue"><input class="w98" type="text" name="CMPclientes-enderecos-numero-PJ" value="<?php echo $oPessoaJuridica->oEnd->TX_NUMERO[0]; ?>" /></td>
                </tr>
              </table>
              <table style="width: 98%">
                <tr>                 
                  <td class="infoheader" style="width: 40%">Complemento</td>
                  <td class="infoheader" style="width: 25%">Bairro*</td>
                  <td class="infoheader" style="width: 25%">Cidade*</td>
                  <td class="infoheader" style="width: 10%">Estado*</td>
                </tr>
                <tr>
                  <td class="infovalue"><input class="w98" type="text" id="CMPclientes-enderecos-complemento-PJ" name="CMPclientes-enderecos-complemento-PJ" value="<?php echo $oPessoaJuridica->oEnd->TX_COMPLEMENTO[0]; ?>" /></td>
                  <td class="infovalue"><input class="w98" type="text" id="CMPclientes-enderecos-bairro-PJ" name="CMPclientes-enderecos-bairro-PJ" value="<?php echo $oPessoaJuridica->oEnd->TX_BAIRRO[0]; ?>" /></td>
                  <td class="infovalue"><input class="w98" type="text" id="CMPclientes-enderecos-cid-PJ" name="CMPclientes-enderecos-cid-PJ" value="<?php echo $oPessoaJuridica->oEnd->NM_CID[0]; ?>" /></td>
                  <td class="infovalue">
                    <?php $oSite->montaSelectDB('CMPclientes-enderecos-uf-PJ', 'tc_estados', 'sg_uf', 'nm_uf', $oPessoaJuridica->oEnd->NM_UF[0] , true)?>
                  </td>
                </tr>
              </table>

              <h1 class="titulo">Dados de Acesso ao Site</h1>
              <table style="width: 98%">
                <tr>                 
                  <td class="infoheader" style="width: 40%">E-mail*</td>
                  <td class="infoheader" style="width: 30%">Senha*</td>
                  <td class="infoheader" style="width: 30%">Confirmar Senha*</td>
                </tr>
                <tr>
                  <td class="infovalue"><input class="w98" type="text" name="CMPclientes-email-PJ" value="<?php echo $oPessoaJuridica->oCli->TX_EMAIL[0]; ?>" /></td>
                  <td class="infovalue"><input class="w98" type="password" autocomplete="off" name="CMPclientes-senha-PJ" value="" /></td>
                  <td class="infovalue"><input class="w98" type="password" autocomplete="off" name="CMPclientes-senha2-PJ" value="" /></td>
                </tr>
              </table>
              <br />
              <table style="width: 98%">
                <tr>
                  <td class="infoheader w30">Desejo receber informativos</td>
                  <td class="infovalue w70"><input type="checkbox" name="CMPclientes-recebe-news-PJ" <?php echo $oPessoaFisica->oCli->CD_RECEBE_NEWS[0] == 'S' ? 'checked="checked"' : ''; ?> value="S"></td>
                </tr>
              </table>
              <br /><br />
              <input type="submit" value="Confirmar Cadastro" />
            </form>
          </div> <?php
          $sDadosContAbas_02 = ob_get_clean();
          //ob_end_clean();

          $sIdConteinerContAbas = 'conteiner_abas_01';
          $aConfigAbas[0] = array('Pessoa Física'   , 'aba_pf' , $sDadosContAbas_01);
          $aConfigAbas[1] = array('Pessoa Jurídica' , 'aba_pj' , $sDadosContAbas_02);
          $sAbaSelecionada = (isset($_POST['CMPtpCadastro']) && $_POST['CMPtpCadastro'] == 'PJ') ? 'aba_pj' : 'aba_pf';

          $oAbas = new abas($sIdConteinerContAbas, $aConfigAbas, $sAbaSelecionada);
          $oAbas->abas_MontarBotoes();
          $oAbas->abas_MontarConteiner();
        ?>
      </div>
      <div class="limpa"></div>
        
    </div>
    <?php 
      echo $oSite->rodape($sPgAtual);
    ?>    
  </body>
</html>
