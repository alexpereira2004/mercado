<?php
  session_start();
  $sPgAtual = 'clientes';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/adapter.clientes.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  $oMenu = new adapter_tclv_menu();

  $oClientes = new clientes();
  $oClientes->inicializaAtributos();

  $bPessJuridico = false;

  // Flag para exibir div com os endereços
  $bChamarWsEndereco = true;

  // Exibir caixa de seleção para tipo de pessoa
  $bLoadPagina = true;

  if (isset ($_POST['sAcao'])) {
    $sAcao = $_POST['sAcao'];
    $bPessJuridico = $_POST['CMPtpCadastro'] == 'J' ? true : false;
    $bChamarWsEndereco = false;
    $bLoadPagina = false;

    try {
      $oManClientes = new clientes();
      $oManClientes->inicializaAtributos();

    
      // Manipular dados
      $oManClientes->oCli->ID[0]              = $oAdmin->anti_sql_injection($_POST['CMPid']);
      $oManClientes->oCli->NM_CLIENTE[0]      = $oAdmin->anti_sql_injection($_POST['CMPnome']);
      $oManClientes->oCli->NM_SOBRENOME[0]    = $oAdmin->anti_sql_injection($_POST['CMPsobrenome']);
      $oManClientes->oCli->TX_TEL_FIXO[0]     = $oAdmin->anti_sql_injection($_POST['CMPtelefone']);
      $oManClientes->oCli->TX_TEL_CEL[0]      = $oAdmin->anti_sql_injection($_POST['CMPcelular']);
      $oManClientes->oCli->TX_EMAIL[0]        = $oAdmin->anti_sql_injection($_POST['CMPemail']);
      $oManClientes->oCli->CD_SEXO[0]         = $oAdmin->anti_sql_injection($_POST['CMPsexo']);
      $oManClientes->oCli->DT_NASCIMENTO[0]   = $oAdmin->anti_sql_injection($_POST['CMPnascimento']);
      $oManClientes->oCli->CD_RECEBE_NEWS[0]  = (isset($_POST['CMPrecebeNews']) && $_POST['CMPrecebeNews'] == 1 ? 'S' : 'N');
      $oManClientes->oCli->TX_LOGIN[0]        = $oAdmin->anti_sql_injection($_POST['CMPlogin']);
      $oManClientes->oCli->TX_PASS[0]         = $oAdmin->anti_sql_injection($_POST['CMPpass']);

      if ($bPessJuridico) {
        $oManClientes->oCli->NM_RAZAO_SOCIAL[0] = $oAdmin->anti_sql_injection($_POST['CMPrazaoSocial']);
        $oManClientes->oCli->NM_FANTASIA[0]     = $oAdmin->anti_sql_injection($_POST['CMPfantasia']);
        $oManClientes->oCli->NU_CNPJ[0]         = $oAdmin->anti_sql_injection($_POST['CMPcnpj']);
        $oManClientes->oCli->NU_IE[0]           = $oAdmin->anti_sql_injection($_POST['CMPie']);
        $oManClientes->oCli->DT_FUNDACAO[0]     = $oAdmin->anti_sql_injection($_POST['CMPfundacao']);

      }

      $oManClientes->oEnd->NU_CEP[0]        = $oAdmin->anti_sql_injection($_POST['CMPcep']);
      $oManClientes->oEnd->TP_LOGRADOURO[0] = $oAdmin->anti_sql_injection($_POST['CMPtpLogradouro']);
      $oManClientes->oEnd->NM_LOGRADOURO[0] = $oAdmin->anti_sql_injection($_POST['CMPnomeLogradouro']);
      $oManClientes->oEnd->TX_NUMERO[0]     = $oAdmin->anti_sql_injection($_POST['CMPenderecoNumero']);
      $oManClientes->oEnd->TX_BAIRRO[0]     = $oAdmin->anti_sql_injection($_POST['CMPbairro']);
      $oManClientes->oEnd->NM_UF[0]         = $oAdmin->anti_sql_injection($_POST['CMPuf']);
      $oManClientes->oEnd->NM_CID[0]        = $oAdmin->anti_sql_injection($_POST['CMPcidade']);
      



      // Validação dos campos obrigatórios
      $aValidar = array (0 => array('Nome'          , $_POST['CMPnome']),
                         1 => array('Sobrenome'     , $_POST['CMPsobrenome']),
                         2 => array('Telefone'      , $_POST['CMPtelefone']),
                         3 => array('Celular'       , $_POST['CMPcelular']),
                         4 => array('Email'         , $_POST['CMPemail']),
                         5 => array('Nascimento'    , $_POST['CMPnascimento']),
                         6 => array('Usuário'       , $_POST['CMPlogin']),
                         7 => array('Senha'         , $_POST['CMPpass']),

                         11 => array('CEP'          , $_POST['CMPcep']),
                         12 => array('Tipo'         , $_POST['CMPtpLogradouro']),
                         13 => array('Logradouro'   , $_POST['CMPnomeLogradouro']),
                         14 => array('Número'       , $_POST['CMPenderecoNumero']),
                         15 => array('Bairro'       , $_POST['CMPbairro']),
                         16 => array('UF'           , $_POST['CMPuf']),
                         17 => array('Cidade'       , $_POST['CMPcidade']),
      );

      // Em caso de pessoa Juridica, valida alguns campos a mais
      if ($bPessJuridico) {
        $aValidar[8]  = array('Razão social'  , $_POST['CMPrazaoSocial']);
        $aValidar[9]  = array('Nome Fantasia' , $_POST['CMPfantasia']);
        $aValidar[10] = array('CNPJ'          , $_POST['CMPcnpj']);
      }


      if ($oAdmin->valida_Preenchimento($aValidar) !== true) {
        $mResultado = $oAdmin->aMsg;
        throw new Exception;
      }
       



      /********** Novo registro **********/
      if ($sAcao == 'novo') {
        if ($oManClientes->inserir() !== true) {
          $mResultado = $oManClientes->oCli->aMsg;
          throw new Exception;
        }
        // Se inseriu com sucesso, manda para página de retorno
        $aCampos = $oManClientes->oCli->aMsg;
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
      $oClientes     = $oManClientes;
      $mResultado = $mResultado;
    }



  } else {

    // Carregar página
    try {
      if (isset($_GET['n'])) {
        $_GET['n'] = $oAdmin->anti_sql_injection($_GET['n']);

        // Editar conteúdo
        if(is_numeric($_GET['n'])) {
          $iId = $_GET['n'];
          $sFiltro = 'WHERE id = '.$iId;
          $oClientes = new clientes();
          $oClientes->listar($sFiltro);
          $sAcao = 'editar';
          $bChamarWsEndereco = false;
          $bLoadPagina = false;
          $bPessJuridico = $oClientes->oCli->NU_CNPJ[0] != '' && $oClientes->oCli->NU_CNPJ[0] != '../.' ? true : false;
  
          if ($oClientes->iLinhas != 1) {
            throw new Exception;
          }
        } else {

          // Adicionar conteúdo
          if ($_GET['n'] == 'novo') {
            //$oClientes->inicializaAtributos();
            $sAcao = 'novo';
            $bLoadPagina = true;
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
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'].' - '.($sAcao == 'novo' ? 'Inserir novo registro' : 'Editar '.$oClientes->oCli->NM_CLIENTE[0]);?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script type="text/javascript" src="../modulosJS/maskedinput/jquery.maskedinput-1.3.min.js"></script>
    <script type="text/javascript" src="../modulosJS/jwysiwyg/jquery.wysiwyg.js"></script>
    <link rel="stylesheet" href="../modulosJS/jwysiwyg/jquery.wysiwyg.css" type="text/css" />
    

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
          <input type="hidden" name="CMPid" value="<?php echo $oClientes->oCli->ID[0]; ?>" />
          <input type="hidden" name="CMPtpCadastro" id="CMPtpCadastro" value="<?php echo ($bPessJuridico) ? 'J' : ''; ?>" />
          <input type="hidden" name="CMPexibirDecTpPes" id="CMPexibirDecTpPes" value="<?php echo ($bLoadPagina) ? 'S' : ''; ?>" />
          <fieldset>
            <legend>Dados de Pessoais:</legend>
            <table class="w90">
              <tr>
                <td style="width: 150px">Nome:</td>
                <td><input type="text" name="CMPnome" value="<?php echo $oClientes->oCli->NM_CLIENTE[0]; ?>" /><?php echo $CFGtxObrigatorio; ?> </td>
                <td style="width: 150px">Sobrenome:</td>
                <td><input type="text" name="CMPsobrenome" value="<?php echo $oClientes->oCli->NM_SOBRENOME[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
              </tr>
              <tr>
                <td>Telefone:</td>
                <td><input type="text" name="CMPtelefone" class="mask_telefone" value="<?php echo $oClientes->oCli->TX_TEL[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
                <td>Celular:</td>
                <td><input type="text" name="CMPcelular" class="mask_telefone" value="<?php echo $oClientes->oCli->TX_TEL[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
              </tr>
              <tr>
                <td>Email:</td>
                <td><input type="text" name="CMPemail" value="<?php echo $oClientes->oCli->TX_EMAIL[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
                <td>Sexo:</td>
                <td>
                  M <input type="radio" name="CMPsexo" value="M" checked="checked" />
                  F <input type="radio" name="CMPsexo" value="F" <?php echo ($oClientes->oCli->CD_SEXO[0] == 'F') ? 'checked="checked""' : '' ?>/>
                </td>
              </tr>
              <tr>
                <td>Nascimento:</td>
                <td><input type="text" name="CMPnascimento" class="mask_data" value="<?php echo $oClientes->oCli->DT_NASCIMENTO[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
                <td>Enviar emails promocionais:</td>
                <td><input type="checkbox" name="CMPrecebeNews" value="1" <?php echo ($oClientes->oCli->CD_RECEBE_NEWS[0] == 'S' ? 'checked="checked"' : '') ;?> /></td>
              </tr>
              
<!--              <tr>
                <td>Usuário:</td>
                <td><input type="text" name="CMPlogin" value="<?php echo $oClientes->oCli->TX_LOGIN[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
                <td>Senha:</td>
                <td><input type="password" name="CMPpass" value="<?php echo $oClientes->oCli->TX_PASS[0] ?>" /><?php echo $CFGtxObrigatorio; ?></td>
              </tr>-->
            </table>
          </fieldset>

          <fieldset id="fsDadosEmpresa" class="w98" style="display: <?php echo ($bPessJuridico) ? 'block;' : 'none;' ?>;">
            <legend>Dados de Empresa:</legend>
            <table class="w90" >
              <tr>
                <td style="width: 150px">Razão Social:</td>
                <td><input type="text" name="CMPrazaoSocial" value="<?php echo $oClientes->oCli->NM_RAZAO_SOCIAL[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
                <td style="width: 150px">Nome Fantasia:</td>
                <td><input type="text" name="CMPfantasia" value="<?php echo $oClientes->oCli->NM_FANTASIA[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
              </tr>
              <tr>
                <td>CNPJ:</td>
                <td><input type="text" name="CMPcnpj" class="mask_cnpj" value="<?php echo $oClientes->oCli->NU_CNPJ[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
                <td>IE:</td>
                <td><input type="text" name="CMPie" value="<?php echo $oClientes->oCli->NU_IE[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
              </tr>
            </table>
          </fieldset>         

          <fieldset>
            <legend>Endereço de entrega:</legend>
            <table class="w90" >
              <tr>
                <td style="width: 150px">CEP:</td>
                <td>
                  <input id="CMPcep" type="text" class="mask_cep" name="CMPcep" value="<?php echo $oClientes->oEnd->NU_CEP[0]; ?>" />
                  <input type="button" class="bt btBuscarEndereco" id="btBuscarEndereco" value="Ok" />
                  <a href="http://www.buscacep.correios.com.br/servicos/dnec/menuAction.do?Metodo=menuEndereco" target="blank">Não sei meu cep</a>
                </td>
              </tr>
            </table>

            <div id="ret_endereco_entrega" class="<?php echo $bChamarWsEndereco ? 'invisivel' : 'visivel'; ?>">
            <?php

              // Nos casos onde o endereço já foi carregado, não chamar novamente o serviço
              if (!$bChamarWsEndereco) {
            ?>
              <table class="w90">
                <tr>
                  <td style="width: 150px">Tipo:</td>
                  <td><input type="text" name="CMPtpLogradouro" value="<?php echo $oClientes->oEnd->TP_LOGRADOURO[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
                  <td style="width: 150px">Logradouro:</td>
                  <td><input type="text" name="CMPnomeLogradouro" value="<?php echo $oClientes->oEnd->NM_LOGRADOURO[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
                </tr>
                <tr>
                  <td>Número:</td>
                  <td><input type="text" name="CMPenderecoNumero" value="<?php echo $oClientes->oEnd->TX_NUMERO[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
                  <td>Bairro:</td>
                  <td><input type="text" name="CMPbairro" value="<?php echo $oClientes->oEnd->TX_BAIRRO[0] ; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
                </tr>
                <tr>
                  <td>UF:</td>
                  <td>
                    <?php
                      $iIdUf = $oClientes->oEnd->NM_UF[0];
                      $oAdmin->montaSelectDB('CMPuf', 'tc_estados', 'sg_uf', 'nm_uf', $iIdUf, true);
                      echo $CFGtxObrigatorio;
                      ?>
                  </td>
                  <td>Cidade:</td>
                  <td>
                    <div id="ret_cid">
                      <?php
                        $sTabela = "tc_cidades \n";
                        $sTabela .= "INNER JOIN tc_estados ON tc_estados.id = tc_cidades.id_uf \n";
                        $oAdmin->montaSelectDB('CMPcidade', $sTabela, 'tc_cidades.id', 'nm_cidade', $oClientes->oEnd->NM_CID[0], true, '', '', 'Selecionar uma cidade', ' WHERE sg_uf = \''.$iIdUf."'");
                        echo $CFGtxObrigatorio;
                      ?>
                    </div>
                  </td>
                </tr>
              </table>

            <?php
              }
            ?>
            </div>
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
<script type="text/javascript">
      $(document).ready(function(){


        // Ajax das cidades
        $('#CMPuf').change(function(){
          iIdUf = $(this).val();

          $.ajax({
            type: "POST",
            data: "sAcao=buscarCidades&iIdUf="+iIdUf,
            url: "../modulosPHP/tratarAjax.php",
            context: document.body,
            async: false,

            beforeSend: function() {
              $("#ret_cid").html('<img src="../comum/imagens/icones/loading19.gif" alt="" />');
            },

            success: function(html){
              $("#ret_cid").html(html);
            }

          });
        })

        $(".mask_cep").mask("99999-999");
        $(".mask_data").mask("99/99/9999");
        $(".mask_telefone").mask("(99)9999-9999");
        $(".mask_cnpj").mask("99.999.999/9999-99");
        $(".mask_cpf").mask("999.999.999-99");


        sBox = '<div id="dialog-confirm" style="display: none;">Tipo de cadastro</div>';
        $('#corpo').append(sBox);

        // Abre caixa de seleção para escolher qual tipo de cadastro
        // Pessoa fisica ou juridica
        if($('#CMPexibirDecTpPes').val() == 'S') {
          $( "#dialog-confirm" ).dialog({
            resizable: false,
            title: 'Confirme',
            minHeight: '140',
            modal: true,
            buttons: {
              "Pessoa Física": function() {
                $( this ).dialog( "close" );
                $('#CMPtpCadastro').val('F');
                $('#fsDadosEmpresa').toggleClass('visivel', 500);
              },
              "Pessoa Jurídica": function() {
                $( this ).dialog( "close" );
                $('#CMPtpCadastro').val('J');
                $('#fsDadosEmpresa').fadeIn('slow');
              }
            }
          });
        }

        // Chamadas para atualização do endereço via CEP
        // - Ao clicar no botao
        // - Ao carrega a página caso já tenha valor preenchido no campo
        $('.btBuscarEndereco').click(function(){
          buscarCep();
        })
        if($('#CMPcep').val() != '') {
          //buscarCep();
        }


        $('#FRMclientes').submit(function(){

          if($('#CMPcep').val() == '') {
            alert('Validar endereço');
            return false;
          }
        });
      });


    </script>
</html>