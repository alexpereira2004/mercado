<?php
  session_start();
  $sPgAtual = 'finalizar-compras';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';

  include_once '../modulosPHP/class.tc_clientes.php';
  include_once '../modulosPHP/adapter.clientes.php';

  include_once '../modulosPHP/class.tr_carrinhos_finalizados.php';
  include_once '../modulosPHP/class.tr_coletas.php';
  $oLogin = new usuario_admin();
  $oLogin->validar();


  $oAdmin = new admin();
  //$oMenu = new adapter_tclv_menu();
  
  if (isset ($_POST)) {
    $aMsg = $oAdmin->msgRetPost($_POST);

    if (isset($_POST['sAcao'])) {

      if ($_POST['sAcao'] == 'finalizar-pedido') {
        $oManPedido = new carrinho();
        $iIdPedido = $_POST['CMPid-carrinho'];
        $oManPedido->CD_NF[0]             = $oAdmin->anti_sql_injection($_POST['CMPcarrinho-nf']);
        $oManPedido->TX_OBS[0]            = $oAdmin->anti_sql_injection($_POST['CMPcarrinho-obs']);
        $oManPedido->DT_COLETA[0]         = $_POST['CMPcoletas-data'];
        $oManPedido->ID_TRANSPORTADORA[0] = $_POST['CMPcoletas-transportadora'];
        
        $oManPedido->finalizarPedido($iIdPedido);
        $aMsg = $oManPedido->aMsg;
      }

      if ($_POST['sAcao'] == 'pesquisar') {

      }
    }

  }

  $oPedidos = new carrinho();
  $oPedidos->listar("WHERE cd_sit = 'EX' ORDER BY id DESC");
  
  $oColetas = new tr_coletas();
  $oColetas->inicializaAtributos();
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'];?></title>
    <?php
      $oAdmin->incluirCss($sPgAtual);
      $oAdmin->incluirJs($sPgAtual);
    ?>
    <script type="text/javascript" src="../modulosJS/maskedinput/jquery.maskedinput-1.3.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $('.dataTable').dataTable({
          "iDisplayLength": 25
        });

        $(".mask_data").mask("99/99/9999");
        $('submit_salvar').blur(function (){
          $('.sAcao').val('submit_salvar');
        });
        $('submit_finalizar').blur(function () {
          $('.sAcao').val('submit_finalizar');
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
        <div id="toolBar"></div>

          <table class="dataTable" style="z-index: 1">
            <thead>
              <tr class="header">
                <td style="width: 15px">&nbsp;</td>
                <td>Nome</td>
                <td>Código</td>
                <td>Situação</td>
                <td>Data da Compra</td>
              </tr>
            </thead>
            <tbody>
              <?php
                if ($oPedidos->iLinhas > 0) {
                  for ($i = 1; $i <= $oPedidos->iLinhas; $i++) {
                    $bLinha = $i%2 ? true : false;
                    ?>
                    <tr class="<?php echo ($bLinha) ? 'corSim' : 'corNao'; ?>">
                      <td style="width: 20px; cursor: pointer" onclick="abreFechaDiv('detalhes-<?php echo $i; ?>')" >
                        <img src="<?php echo $oAdmin->sUrlBase; ?>/comum/imagens/icones/bullet_toggle_plus.png" />
                      </td>
                      <td><?php echo $oPedidos->NM_CLIENTE[$i]; ?></td>
                      <td><?php echo $oPedidos->CD_CARRINHO[$i]; ?></td>
                      <td><?php echo $CFGaCodSitPedido[$oPedidos->CD_SIT[$i]]; ?></td>
                      <td><?php echo $oPedidos->DT_CRIACAO[$i]; ?></td>
                    </tr>

                    <tr id="detalhes-<?php echo $i; ?>" class="<?php echo ($bLinha) ? 'corSim' : 'corNao'; ?>" 
                        <?php echo (isset($_POST['CMPid-carrinho']) && $_POST['CMPid-carrinho'] == $oPedidos->ID[$i]) ? '' : 'style="display: none"'  ?> >
                      <td colspan="5">
                        <table class="w90">
                          <form method="post" action="finalizar-compras.php" class="FRMfinalizar">
                            <input type="hidden" name="sAcao" class="sAcao" value="finalizar-pedido">
                            <input type="hidden" name="CMPid-carrinho" value="<?php echo $oPedidos->ID[$i]; ?>">
                            <input type="hidden" name="CMPcd-Carrinho" value="<?php echo $oPedidos->CD_CARRINHO[$i]; ?>">
                            <input type="hidden" name="CMPnm-Cliente" value="<?php echo $oPedidos->NM_CLIENTE[$i].' '.$oPedidos->NM_SOBRENOME[$i]; ?>">
                            <tr>
                              <td>
                                <fieldset>
                                  <legend>Nota Fiscal:</legend>
                                  <table class="tab_lista_registros" style="width: 80%">
                                    <tr>
                                      <td class="infoheader" style="width: 30%">Número NF*</td>
                                      <td class="infovalue" style="width: 70%">
                                        <input type="text" name="CMPcarrinho-nf" value="<?php echo $oPedidos->CD_NF[$i]; ?>" />
                                      </td>
                                    </tr>
                                  </table>
                                </fieldset>
                                <fieldset>
                                  <legend>Coleta:</legend>
                                  <table class="tab_lista_registros" style="width: 80%">
                                    <tr>
                                      <td class="infoheader" style="width: 30%">Coletado em*:</td>
                                      <td class="infovalue" style="width: 70%"><input type="text" class="mask_data" name="CMPcoletas-data" value="<?php echo $oPedidos->DT_COLETA[$i]; ?>" /></td>
                                    </tr>
                                    <tr>
                                      <td class="infoheader">Transportadora*:</td>
                                      <td class="infovalue">
                                        <?php
                                          $oAdmin->montaSelectDB('CMPcoletas-transportadora', 'tc_transportadoras', 'id', 'nm_transportadora', $oPedidos->ID_TRANSPORTADORA[$i]);
                                        ?>
                                      </td>
                                    </tr>
                                  </table>
                                </fieldset>
                                <fieldset>
                                  <legend>Informações:</legend>
                                  <table class="tab_lista_registros" style="width: 80%">
                                    <tr>
                                      <td class="infoheader" style="width: 30%">Observações gerais:</td>
                                      <td class="infovalue"  style="width: 70%">
                                        <textarea name="CMPcarrinho-obs" cols="50" rows="3"><?php echo $oPedidos->TX_OBS[$i] ?></textarea>
                                      </td>                                       
                                    </tr>
                                  </table>                                    
                                </fieldset>

                              </td>
                              <tr>
                                <td>
                                <!-- <input id="submit_salvar" type="submit" class="bt" value="Salvar">-->
                                  <input id="submit_finalizar" type="submit" class="bt" value="Finalizar Pedido">
                                </td>
                              </tr>
                            </tr>
                          </form>
                        </table>
                      </td>
                    </tr>
                    <?php
                  }
                }
              ?>
            </tbody>
          </table>
      </div>
      <div class="limpa"></div>
      <?php
        $oAdmin->rodape($sPgAtual);
      ?>
    </div>
  </body>
</html>