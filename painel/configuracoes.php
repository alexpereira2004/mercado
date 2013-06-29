<?php
  session_start();
  $sPgAtual = 'configuracoes';

  
  include      '../modulosPHP/config.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.parametros.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();

  $oAdmin = new admin();



  if (isset($_POST['sAcao'])) {
    if ($_POST['sAcao'] == 'trocarStatus') {
      $oManParam = new adapter_parametros();
      $iId = $oAdmin->tratarString($_POST['CMPidParametro'], 2);
      $oManParam->trocarStatus($iId);
      $aMsg = $oManParam->aMsg;
    }

    if ($_POST['sAcao'] == 'removerParametro') {
      $oManParam = new adapter_parametros();
      $iId = $oAdmin->tratarString($_POST['CMPidParametroRemovido'], 2);
      $oManParam->removerValorParametro($iId);
      $aMsg = $oManParam->oValores->aMsg;
    }

    if ($_POST['sAcao'] == 'criarNovoCampoParametro') {
      $oManParam = new adapter_parametros();
      $oManParam->ID_PARAMETRO[0] = $_POST['CMPidParametroRelacionado'];
      $oManParam->TX_FUNC[0]      = $_POST['CMPfuncao'];
      $oManParam->ID_USU[0]       = $_POST['CMPidUsuarioCriador'];
      $oManParam->criarNovoCampoParametro();
    }
    if ($_POST['sAcao'] == 'salvar') {
      $oManParam = new adapter_parametros();
      $oManParam->salvarParametros($_POST['CMPaIds'], $_POST['CMPaValoresParametros'], $_POST['CMPaIdParametros']);
    }

    if ($_POST['sAcao'] == 'pesquisar') {
      $sPesquisa = $oAdmin->anti_sql_injection($_POST['CMPpesquisa']);
      $sFiltro   = "WHERE 1 = 1 ";
      $sFiltro  .= "  AND ( ";
      $sFiltro  .= "    nm_usuario LIKE '%".$sPesquisa."%'";
      $sFiltro  .= " OR tx_email LIKE '%".$sPesquisa."%'";
      $sFiltro  .= "      ) ";
    }
  }
  
  $oParam    = new adapter_parametros();
  $oParam->listar("WHERE cd_tipo_uso = 'PM'");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'];?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script type="text/javascript">
      $(document).ready(function() {
        $('.trocarStatus').click(function(){
          sId = $(this).attr('id');
          $('#CMPidParametro').val(sId);
          $('#FRMtrocarStatus').submit();
        });
        $('.removerParametro').click(function(){
          sId = $(this).attr('id');
          $('#CMPidParametroRemovido').val(sId);
          $('#FRMremoverParametro').submit();
        });

        $('.add_parametro').click(function(){
          sId = $(this).attr('id');
          $('#CMPidParametroRelacionado').val(sId);

          // Altera a url para que após submit retorne ao ponto do botão onde foi clicado
          sAction = $('#FRMcriarNovoCampoParametro').attr('action')+'#cont'+sId;
          $('#FRMcriarNovoCampoParametro').attr('action',sAction);
          $('#FRMcriarNovoCampoParametro').submit();
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
          $oAdmin->msgRetAlteracoes($aMsg);
          $oAdmin->breadCrumbs();
        ?>
        <div id="toolBar">

        </div>
        <form id="FRMparametros" name="FRMparametros" method="post" action="configuracoes.php">
          <input type="hidden" name="sAcao" value="salvar" />
          <table class="tab_lista_registros" border="0" cellpadding="0" cellspacing="0" width="">
            <thead>
              <tr>
                <td style="width: 10px">Novo</td>
                <td style="width: 30%">Parâmetro</td>
                <td style="width: 50%">Ações</td>
                <td style="width: 10px">Status</td>
                <td style="width: 10px">Excluir</td>
              </tr>
            </thead>
            <tbody>
              <?php
                if($oParam->iLinhas > 0) {
                  $sClass = 'corSim';
                  $sCodParamAnterior = '';
                  $iQntValores = 0;
                  
                  for ($i = 0; $i < $oParam->iLinhas; $i++) {

                    // Controle para agrupar tipos de parâmetros iguais
                    if ($sCodParamAnterior == $oParam->CD_PARAMETRO[$i]) {
                      $bAgruparPorTipo = true;
                      $iQntValores++;
                    } else {
                      $sClass = ($sClass == 'corNao') ? 'corSim' : 'corNao';
                      $bAgruparPorTipo = false;
                      $iQntValores = 1;
                    }
                    $sCodParamAnterior = $oParam->CD_PARAMETRO[$i];
                    $sValor = $oParam->TX_VALOR[$i] != '' ? $oParam->TX_VALOR[$i] : $oParam->VL_PADRAO[$i] ;
                    //escreve o resultado na tela dentro de uma tabela
                    ?>
                    <tr class="<?php echo $sClass; ?>"> <?php

                      if (!$bAgruparPorTipo) { ?>
                      <td>
                        <?php
                        if ($oParam->NU_LIMITE_CADASTRO[$i] > 1) {

                          if ($iQntValores == 1) {

                            // Quantidade de valores já cadastrados para o parâmetro
                            $iTotalValores = count($oParam->iTotais[$oParam->CD_PARAMETRO[$i]]);

                            // Controla a quantidade de valores para cada parâmetro de acordo com o que foi cadastrado
                            if ($iTotalValores < $oParam->NU_LIMITE_CADASTRO[$i]) { ?>
                              <img id="prm<?php echo $oParam->ID[$i]; ?>" src="../comum/imagens/icones/add.png" class="bt_img add_parametro" alt="Inserir mais um parâmetro"/>
                              <input type="hidden" name="CMPvalor" value="3" />
                              <?php
                            }
                          }
                        }
                        ?>
                      </td>
                      <td class="infoheader" id="contprm<?php echo $oParam->ID[$i]; ?>"> <?php echo $oParam->NM_PARAMETRO[$i] ?></td> <?php
                      } else { ?>
                        <td></td>
                        <td></td> <?php
                      } ?>
                      <td class="infoValue">
                        <input type="text" name="CMPaValoresParametros[]" value="<?php echo htmlentities($sValor);  ?>" />
                        <input type="hidden" name="CMPaIds[]" value="<?php echo $oParam->ID_VALOR[$i];?>" />
                        <input type="hidden" name="CMPaIdParametros[]" value="<?php echo $oParam->ID[$i];?>" />
                      </td>
                        <td style="text-align: center"><img id="trocar_status_<?php echo $oParam->ID_VALOR[$i]; ?>" class="bt_img trocarStatus" src="../comum/imagens/icones/<?php echo $CFGaImagensStatusParam[$oParam->CD_ATIVO[$i]] ?>" alt="" title="Status" /> </td>
                      <td style="text-align: center">
                        <?php
                          // Remover parâmetro
                          if ($oParam->NU_LIMITE_CADASTRO[$i] > 1 && $iQntValores > 1) { ?>
                            <img src="../comum/imagens/icones/cross.png" id="Remover_Parametro_<?php echo $oParam->ID_VALOR[$i]; ?>" class="bt_img removerParametro" alt="Remover" title="Remover parâmetro" /> <?php
                          } ?>
                      </td>
                    </tr> <?php
                  }
                } else { ?>
                  <tr class="corSim">
                    <td colspan="5">Nenhum registro</td>
                  </tr><?php
                }?>

            </tbody>
          </table>
        <input type="submit" value="Salvar" class="bt" />
        </form>
        <br />
        <form name="FRMremoverParametro" id="FRMremoverParametro" action="configuracoes.php" method="post">
          <input type="hidden" name="sAcao" value="removerParametro" />
          <input type="hidden" name="CMPidParametroRemovido" id="CMPidParametroRemovido" value="" />
        </form>
        <form name="FRMtrocarStatus" id="FRMtrocarStatus" action="configuracoes.php" method="post">
          <input type="hidden" name="sAcao" value="trocarStatus" />
          <input type="hidden" name="CMPidParametro" id="CMPidParametro" value="" />
        </form>
        <form name="FRMcriarNovoCampoParametro" id="FRMcriarNovoCampoParametro" action="configuracoes.php" method="post">
          <input type="hidden" name="sAcao" value="criarNovoCampoParametro" />
          <input type="hidden" name="CMPidUsuarioCriador" value="1" />
          <input type="hidden" name="CMPidParametroRelacionado" id="CMPidParametroRelacionado" value="" />
          <input type="hidden" name="CMPfuncao" value="" />
        </form>
        
      </div>
      <div class="limpa"></div>
      <?php
        $oAdmin->rodape($sPgAtual);
      ?>
    </div>
  </body>
</html>