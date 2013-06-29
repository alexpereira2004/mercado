<?php
  session_start();
  $sPgAtual = 'usuarios';

  include      '../modulosPHP/load.php';
  include      '../modulosPHP/config.php';
  include_once '../modulosPHP/adapter.clientes.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();

  $oUsuarios = new usuario_admin();


  $oAdmin = new admin();
  $sFiltro = '';

  if (isset($_POST['sAcao'])) {
    // $mResultado = $oAdmin->msgRetPost();

    if (isset($_POST['sAcao'])) {
      $sAcao = $_POST['sAcao'];

      $oUsuarios->ID[0]         = $oAdmin->anti_sql_injection($_POST['CMPid']);
      $oUsuarios->NM_USUARIO[0] = $oAdmin->anti_sql_injection($_POST['CMPnome']);
      $oUsuarios->TX_EMAIL[0]   = $oAdmin->anti_sql_injection($_POST['CMPemail']);
      $oUsuarios->CD_STATUS[0]  = 'A';
      $oUsuarios->CD_NIVEL[0]   = $oAdmin->anti_sql_injection($_POST['CMPnivel']);
      $oUsuarios->TX_SENHA[0]   = '';
      $aValidar = array ( 0 => array ('Nome', $oUsuarios->NM_USUARIO[0]),
                          1 => array ('Email', $oUsuarios->TX_EMAIL[0]),
                          2 => array ('Nível', $oUsuarios->CD_NIVEL[0]),
                         );

      if (isset($_POST['CMPtrocar_senha']) || $_POST['sAcao'] == 'novo') {
        $oUsuarios->TX_SENHA[0]   = $oAdmin->anti_sql_injection($_POST['CMPsenha']);
        $aValidar[3] = array ('Senha', $oUsuarios->TX_SENHA[0]);
      }

        // Salvar dados
        try {

          if ($oAdmin->valida_Preenchimento($aValidar) !== true) {
            $oUsuarios->aMsg = $oAdmin->aMsg;
            throw new Exception;
          }

          if (isset ($_POST['CMPsenha'])) {

            if ((!isset ($_POST['CMPsenha_2'])) || $_POST['CMPsenha'] != $_POST['CMPsenha_2']) {
              $oUsuarios->aMsg['iCdMsg'] = 2;
              $oUsuarios->aMsg['sMsg'] = 'A senha não foi confirmada corretamente!';
              throw new Exception;
            }
          }


          if ($_POST['sAcao'] == 'novo') {
            if (!$oUsuarios->inserir()) {
              throw new Exception;
            }
            $sUrl    = 'usuarios.php';
            $aCampos = array('iCdMsg' => 0,
                                   'sMsg' => 'Usuário foi criado com sucesso!',
                               'sMsgErro' => '',
                          'CMPmsgRetorno' => 'ret');
            $oAdmin->redirFRM($sUrl, $aCampos);

          } elseif ($_POST['sAcao'] == 'editar') {

            $oUsuarios->editar($oUsuarios->ID[0]);
            throw new Exception;
            exit;
            
            if (!$oUsuarios->editar($oUsuarios->ID[0])) {
              throw new Exception;
            }

            // Atualiza Primeiro as permissões
            $oManPermUsu = new tr_usuarios_permissoes();
            $oManPermUsu->remover('WHERE id_usuario = '.$oUsuarios->ID[0]);
            if (isset ($_POST['CMPpermissao'])) {
              foreach ($_POST['CMPpermissao'] as $iIdPermissao => $aAcoes) {
                  $oManPermUsu->ID_USUARIO[0]    = $oUsuarios->ID[0];
                  $oManPermUsu->ID_PERMISSAO[0]  = $iIdPermissao;
                  $oManPermUsu->CD_INSERIR[0]    = in_array('I', $aAcoes) ? 'L' : 'N';
                  $oManPermUsu->CD_REMOVER[0]    = in_array('R', $aAcoes) ? 'L' : 'N';
                  $oManPermUsu->CD_EDITAR[0]     = in_array('E', $aAcoes) ? 'L' : 'N';
                  $oManPermUsu->CD_ACESSAR[0]    = in_array('A', $aAcoes) ? 'L' : 'N';
                  $oManPermUsu->CD_VISUALIZAR[0] = in_array('V', $aAcoes) ? 'L' : 'N';
                  $oManPermUsu->inserir();
              }
            }

            // Atualiza projetos que participa
            $oManProjetoUsu = new tr_usuarios_projeto();
            $oManProjetoUsu->remover('WHERE id_usuario = '.$oUsuarios->ID[0]);
            if (isset ($_POST['CMPprojetos'])) {
              foreach ($_POST['CMPprojetos'] as $iValor) {
                $oManProjetoUsu->ID_USUARIO[0] = $oUsuarios->ID[0];
                $oManProjetoUsu->ID_PROJETO[0] = $iValor;
                $oManProjetoUsu->ID_GRUPO[0]   = 0;
                $oManProjetoUsu->inserir();
              }
            }

          }

        } catch (Exception $exc) {
          $mResultado = $oUsuarios->aMsg;
        }


    }
  } elseif ($_GET['sAcao'] == 'novo') {
    $sAcao = 'novo';
    $oUsuarios->inicializaAtributos();
  } elseif ($_GET['sAcao'] == 'editar') {
    $sAcao = 'editar';
    $iId = (isset($_GET['n']) ? $_GET['n'] : $_POST['CMPid']);
    $sFiltro = 'WHERE id = '.$iId;
    $oUsuarios->listar($sFiltro);
  } else {
    $sUrl    = 'usuarios.php';
    $aCampos = array('mResultado' => 2,
                           'sMsg' => 'Erro ao selecionar um usuário',
                       'sMsgErro' => '',
                  'CMPmsgRetorno' => 'ret');
    $oUtil->redirFRM($sUrl, $aCampos);
  }
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'];?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script src="../modulosJS/tableSorter/js/jquery.dataTables.js" type="text/javascript" ></script>
    <link href="../modulosJS/tableSorter/css/jquery.dataTables.css" media="all" rel="stylesheet"  type="text/css" />
    <script type="text/javascript">
      $(document).ready(function() {
        $('.dataTable').dataTable({
          "iDisplayLength": 25
        });

       $('#CMPtrocar_senha').change(function(){
         if (!$(this).attr('checked')) {
           $('#CMPsenha').attr('disabled', 'disabled');
           $('#CMPsenha_2').attr('disabled', 'disabled');

         } else {
           $('#CMPsenha').attr('disabled','');
           $('#CMPsenha_2').attr('disabled','');
         }

       });
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
          //$oAdmin->minheight('600');
        ?>
        <div id="toolBar">
          <a href="usuarios.php"><img src="../comum/imagens/icones/doc_page_previous.png" alt="Voltar" /></a>
        </div>

          <form id="FRMusuario" name="FRMusuario" action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
            <input type="hidden" name="sAcao" value="<?php echo $sAcao;?>" />
            <input type="hidden" name="CMPid" value="<?php echo $oUsuarios->ID[0];?>" />
            <fieldset>
              <legend>Dados do usuário</legend>
              <table class="tab_edicao w98">
                <tr>
                  <td class="infoheader" style="width: 120px">Nome</td>
                  <td><input class="w50" name="CMPnome" type="text" value="<?php echo $oUsuarios->NM_USUARIO[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
                </tr>
                <tr>
                  <td class="infoheader">Email</td>
                  <td><input class="w50" name="CMPemail" type="text" value="<?php echo $oUsuarios->TX_EMAIL[0]; ?>" /><?php echo $CFGtxObrigatorio; ?></td>
                </tr>
                <tr>
                  <td class="infoheader">Nível</td>
                  <td>
                    <?php
                      $oAdmin->montaSelect('CMPnivel', $CFGaNiveisUsuarios, $oUsuarios->CD_NIVEL[0]);
                      echo $CFGtxObrigatorio; ?>
                  </td>
                </tr>
              </table>
            </fieldset>
            <fieldset>
              <legend>Senha</legend>
              <table class="tab_edicao w98">
              <?php
                  // Botão que ativa e desativa campo de senhas
                  if ($sAcao == 'editar') { ?>
                    <tr>
                      <td class="infoheader">Trocar senha</td>
                      <td>
                        <input id="CMPtrocar_senha" name="CMPtrocar_senha" type="checkbox" value="1" <?php echo (isset($_POST['CMPtrocar_senha'])) ? 'checked="checked"' : ''; ?> />
                      </td>
                    </tr>
                    <?php
                  } ?>
                <tr>
                  <td class="infoheader" style="width: 120px">Senha</td>
                  <td>
                    <input <?php echo (isset($_POST['CMPtrocar_senha']) || $sAcao == 'novo') ? '' : 'disabled'; ?> class="w50" id="CMPsenha" class="CMPsenha" name="CMPsenha" type="password" value="" />
                    <?php echo $CFGtxObrigatorio; ?>
                  </td>
                </tr>
                <tr>
                  <td class="infoheader">Confirmar Senha</td>
                  <td><input <?php echo (isset($_POST['CMPtrocar_senha']) || $sAcao == 'novo') ? '' : 'disabled'; ?> class="w50" id="CMPsenha_2" class="CMPsenha" name="CMPsenha_2" type="password" value="" /><?php echo $CFGtxObrigatorio; ?></td>
                </tr>
              </table>
            </fieldset>

            <input type="reset" value="Limpar" class="bt"/>
            <input type="submit" value="Salvar" class="bt" />
          <!-- </form> -->

          <?php
            // Só exibino na EDIÇÃO de usuários
            if ($sAcao == 'editar--desabilitado') {
              $sFiltro = 'WHERE id_usuario = '.$oUsuarios->ID[0];
              $oAdpterUsuarios->listarUsuariosGrupos($sFiltro);
              $oPermUsu = new permissoes();
              ?>
            <table>
              <tr>
                <td class="infoheader">Participante do(s) grupo(s)</td>
                <td><?php echo $oAdpterUsuarios->iLinhasUsuariosGrupos == 0 ? 'Não esta vinculado a nenhum grupo' : implode(', ', $oAdpterUsuarios->NM_GRUPO); ?></td>
              </tr>
            </table><?php


              //-----------------------------
              // Inicio conteúdo da Aba 01
              ob_start(); ?>
              <div class="conteiner-interno-abas">
                <div class="toolBar" style="margin-top: 10px">&nbsp;</div>
                <?php
                  $aDadosTbCadastro = array('tcl_projetos', 'id', 'tx_titulo');
                  $aDadosTbRelacionamento = array('tr_usuarios_projeto', 'id_projeto','id_usuario');
                  $oAdmin->montarTwinList('CMPprojetos', $oUsuarios->ID[0],$aDadosTbCadastro, $aDadosTbRelacionamento);
                ?>
              </div>
              <?php
              $sDadosContAbas_01 = ob_get_clean();

              //-----------------------------
              // Inicio conteúdo da Aba 02
              ob_start();
              $oPermissoes = new tc_permissoes;
              $oPermissoes->listar("WHERE cd_tipo = 'A'");
              $oPermUsu->buscarPermissoesPorUsuario("WHERE id_usuario = ".$oUsuarios->ID[0]);
              ?>
              <div class="conteiner-interno-abas">
                <div class="toolBar" style="margin-top: 10px">&nbsp;</div>

                <table class="listagem_dados" cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="width: 40%"></td>
                    <td style="width: 10%"><img src="comum/imagens/icones/add.png" alt="Permissão para inserir dados" title="Permissão para inserir dados" /></td>
                    <td style="width: 10%"><img src="comum/imagens/icones/delete.png" alt="Permissão para inserir remover" title="Permissão para inserir remover" /></td>
                    <td style="width: 10%"><img src="comum/imagens/icones/drawer.png" alt="Permissão para inserir editar" title="Permissão para inserir editar" /></td>
                    <td>&nbsp;</td>
                  </tr>
                <?php
                  for ($i = 0; $i < $oPermissoes->iLinhas; $i++) {
                    $iIdPerm = $oPermissoes->ID[$i];
                    ?>
                  <tr class="<?php echo $i%2 ? 'corSim' : 'corNao'; ?>">
                    <td><?php echo $oPermissoes->NM_PERMISSAO[$i] ?></td>
                    <td><input type="checkbox" name="CMPpermissao[<?php echo $oPermissoes->ID[$i];?>][]" value="I" <?php echo (isset($oPermUsu->aDadosPorPermissao[$iIdPerm]['I']) && $oPermUsu->aDadosPorPermissao[$iIdPerm]['I'] == 'L') ? 'checked' : '' ?> /></td>
                    <td><input type="checkbox" name="CMPpermissao[<?php echo $oPermissoes->ID[$i];?>][]" value="R" <?php echo (isset($oPermUsu->aDadosPorPermissao[$iIdPerm]['R']) && $oPermUsu->aDadosPorPermissao[$iIdPerm]['R'] == 'L') ? 'checked' : '' ?>/></td>
                    <td><input type="checkbox" name="CMPpermissao[<?php echo $oPermissoes->ID[$i];?>][]" value="E" <?php echo (isset($oPermUsu->aDadosPorPermissao[$iIdPerm]['E']) && $oPermUsu->aDadosPorPermissao[$iIdPerm]['E'] == 'L') ? 'checked' : '' ?>/></td>
                    <td>&nbsp;</td>
                  </tr> <?php
                  }
                ?>
                </table>
              </div>
              <?php
              $sDadosContAbas_02 = ob_get_clean();

              //-----------------------------
              // Inicio conteúdo da Aba 03
              ob_start();
              $oPermissoes = new tc_permissoes;
              $oPermissoes->listar("WHERE cd_tipo = 'P'");
              ?>
              <div class="conteiner-interno-abas">
                <div class="toolBar" style="margin-top: 10px">&nbsp;</div>

                <table class="listagem_dados" cellpadding="0" cellspacing="0">
                  <tr>
                    <td style="width: 40%"></td>
                    <td style="width: 10%"><img src="comum/imagens/icones/direction.png" alt="Permissão para acessar link" title="Permissão para acessar link" /></td>
                    <td style="width: 10%"><img src="comum/imagens/icones/eye.png" alt="Permissão para visualizar link" title="Permissão para visualizar link" /></td>
                    <td style="width: 10%">&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr>
                <?php
                  for ($i = 0; $i < $oPermissoes->iLinhas; $i++) {
                    $iIdPerm = $oPermissoes->ID[$i];
                    ?>
                  <tr class="<?php echo $i%2 ? 'corSim' : 'corNao'; ?>">
                    <td><?php echo $oPermissoes->NM_PERMISSAO[$i] ?></td>
                    <td><input type="checkbox" name="CMPpermissao[<?php echo $oPermissoes->ID[$i];?>][]" value="A" <?php echo (isset($oPermUsu->aDadosPorPermissao[$iIdPerm]['A']) && $oPermUsu->aDadosPorPermissao[$iIdPerm]['A'] == 'L') ? 'checked' : '' ?>/></td>
                    <td><input type="checkbox" name="CMPpermissao[<?php echo $oPermissoes->ID[$i];?>][]" value="V" <?php echo (isset($oPermUsu->aDadosPorPermissao[$iIdPerm]['V']) && $oPermUsu->aDadosPorPermissao[$iIdPerm]['V'] == 'L') ? 'checked' : '' ?>/></td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                  </tr> <?php
                  }
                ?>
                </table>
              </div>
              <?php
              $sDadosContAbas_03 = ob_get_clean();
              //ob_end_clean();

              $sIdConteinerContAbas = 'conteiner_abas_01';
              $aConfigAbas[0] = array('Projetos', 'aba_projetos' , $sDadosContAbas_01);
              $aConfigAbas[1] = array('Ações'   , 'aba_acoes'    , $sDadosContAbas_02);
              $aConfigAbas[2] = array('Páginas' , 'aba_paginas'  , $sDadosContAbas_03);
              $sAbaSelecionada = 'aba_acoes';

              $oAbas = new abas($sIdConteinerContAbas, $aConfigAbas, $sAbaSelecionada);
              $oAbas->abas_MontarBotoes();
              $oAbas->abas_MontarConteiner();

            }
          ?>
          </form>


      </div>
      <div class="limpa"></div>
      <?php
        $oAdmin->rodape($sPgAtual);
      ?>
    </div>
  </body>
</html>