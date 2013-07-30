<?php
  session_start();
  $sPgAtual = 'adm';

  require_once '../modulosPHP/class.wTools.php';
  include_once '../modulosPHP/class.admin.php';
  include_once '../modulosPHP/adapter.usuarioAdmin.php';
  include_once '../modulosPHP/adapter.produtos.php';
  include_once '../modulosPHP/adapter.menu.php';
  include      '../modulosPHP/config.php';
  include      '../modulosPHP/load.php';
  include_once '../modulosPHP/class.tc_produtos.php';
  include_once '../modulosPHP/class.tc_vitrine_itens.php';

  $oLogin = new usuario_admin();
  $oLogin->validar();

  $oAdmin = new admin();

  if (isset($_POST['sAcao'])) {
    
    if ($_POST['sAcao'] == 'atualizar-vitrine') {
      $oManItensVitrine = new tc_vitrine_itens();
      try {
        if (!$oManItensVitrine->remover("WHERE cd_grupo = 'index'")) {
          $aMsg = $oManItensVitrine->aMsg;      
          throw new Exception;
        }
        
        if (!isset($_POST['CMPidProd'])) {
          $aMsg = array('iCdMsg' => 2,
                          'sMsg' => 'Sem dados selecionados');
          throw new Exception;
        }

        foreach ($_POST['CMPidProd'] as $iIndice => $iIdProd) {
          if ($iIndice > 15) break;
          $oManItensVitrine->ID_PROD[0]  = $iIdProd;
          $oManItensVitrine->NU_ORDEM[0] = $iIndice + 1;
          $oManItensVitrine->NM_LOCAL[0] = 'index';
          $oManItensVitrine->CD_GRUPO[0] = 'index';
          $oManItensVitrine->inserir();
        }

        $aMsg = $oManItensVitrine->aMsg;      
      } catch (Exception $exc) { 
        
      }
    }
    if ($_POST['sAcao'] == 'remover') {
      include_once '../modulosPHP/adapter.produtos.php';
      $oManProdutos = new produtos();
      $oManProdutos->remover($_POST['CMPaId']);
      $aMsg = $oManProdutos->aMsg;
    }

  }
  
  $oProdutos = new produtos();
  $oProdutos->listar('ORDER BY id DESC');
  
  $oItensVitrine = new tc_vitrine_itens();
  $oItensVitrine->listar("WHERE cd_grupo = 'index'");
  
  $aDetItemVitrine = array();
  for ($i = 0; $i < $oItensVitrine->iLinhas; $i++) {
    $aDetItemVitrine[$oItensVitrine->ID_PROD[$i]] = array ( 'id' => $oItensVitrine->ID[$i], 
                                                       'id_prod' => $oItensVitrine->ID_PROD[$i],
                                                      'nu_ordem' => $oItensVitrine->NU_ORDEM[$i], 
                                                      'nm_local' => $oItensVitrine->NM_LOCAL[$i],
                                                      'cd_grupo' => $oItensVitrine->CD_GRUPO[$i]
    );
  }
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo $CFGaPgAtual[$sPgAtual]['titulo'];?></title>
    <?php
      $oAdmin->incluirJs($sPgAtual);
      $oAdmin->incluirCss($sPgAtual);
    ?>
    <script type="text/javascript">
      $(document).ready(function() {
        $('.dataTable').dataTable({
          "iDisplayLength": 25
        });

        // Botão toolbar para remover produto
        $('.remover').click(function(){
          removerViaCheckBox('Deseja realmente excluir os produtos selecionados?', 'produtos.php', 'remover');
        });
        
        // Habilita função para arrastar DIVs
        $( "#sortable1" ).sortable({
          connectWith: ".connectedSortable"
        }).disableSelection();
        
        // Botão da toolbar
        $('.ajustarVitrine').click(function() {
          $( "#dialog-form" ).dialog( "open" );

          $('.nao-salvo').remove();

          $('.checkRemover').each(function(iSeq, oElemento){
            if ($(oElemento).attr('checked')) {
              iId = $(oElemento).val();

              var sNome = $('#nome_reg_'+iId).html()
              var sOrdemAtual = $('#orderm_vitrine_'+iSeq).html()
              if (sOrdemAtual == '' || sOrdemAtual == undefined) {
                
                sHtml  = '<li class="ui-state-default nao-salvo">';
                sHtml += sNome;
                sHtml += '<input type="hidden" name="CMPidProd[]" value="'+iId+'">';
                sHtml += '</li>';
                $('#sortable1').append(sHtml);  
              }
            }
          });
        });
        
        $( "#dialog-form" ).dialog({
          autoOpen: false,
          height: 600,
          width: 470,
          modal: true,
          buttons: {
            "Salvar": function() {
              $('#FRMvitrine').submit();

            },
            Cancel: function() {
              $( this ).dialog( "Cancelar" );
            }
          },
          close: function() {
            allFields.val( "" ).removeClass( "ui-state-error" );
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
          $oAdmin->msgRetAlteracoes($aMsg);
          //$oAdmin->toolBar($aItens);
          $oAdmin->breadCrumbs();
          //$oAdmin->minheight('600');
        ?>
        <div id="toolBar">
          <a href="produtos_edt.php"><img src="../comum/imagens/icones/add.png" alt="Adicionar" /></a> 
          <span style="margin-left: 5px" class="bt_img remover"><img src="../comum/imagens/icones/cross.png" alt="Remover" /></span>
          <span style="margin-left: 5px" class="bt_img ajustarVitrine"><img src="../comum/imagens/icones/grid.ico" alt="Ajustar Vitrine" /></span>
        </div>
        <table class="dataTable" style="z-index: 1">
          <thead>
            <tr>
              <td style="width: 15px">&nbsp;</td>
              <td>Nome</td>
              <td>Categoria</td>
              <td>Situação</td>
              <td>Vitrine</td>
              <td>Desconto</td>
              <td>Promoção</td>
            </tr>
          </thead>
          <tbody>
            <?php
              if ($oProdutos->iLinhas > 0) {
                for ($i = 0; $i < $oProdutos->iLinhas; $i++) {
                  $bLinha = $i%2 ? true : false;
                  ?>
                  <tr>
                    <td class="multiCheck2">
                      <input type="checkbox" class="checkRemover" name="CMPremover_<?php echo $oProdutos->ID[$i]; ?>" value="<?php echo $oProdutos->ID[$i]; ?>" />
                    </td>
                    <td>
                      <a href="produtos_edt.php?n=<?php echo $oProdutos->ID[$i]; ?>">
                        <span id="nome_reg_<?php echo $oProdutos->ID[$i]; ?>">
                          <?php echo $oProdutos->NM_PRODUTO[$i]; ?>
                        </span>
                      </a>
                    </td>
                    <td><?php echo $oProdutos->NM_CATEGORIA_AGRUPADO[$i]; ?></td>
                    <td><?php echo $CFGaSituacao[$oProdutos->CD_STATUS[$i]]; ?></td>
                    <td class="bt_img acao_vitrine">
                      <?php
                        $sValor = '';
                        if (in_array($oProdutos->ID[$i], $oItensVitrine->ID_PROD)) {
                          $sValor = $aDetItemVitrine[$oProdutos->ID[$i]]['nu_ordem'];
                          $aDetItemVitrine[$oProdutos->ID[$i]]['nm_prod'] = $oProdutos->NM_PRODUTO[$i];
                        }
                      ?>
                      <span id="orderm_vitrine_<?php echo $i; ?>"><?php echo $sValor; ?></span>
                    </td>
                    <td><?php echo $oProdutos->NM_DESCONTO[$i]; ?></td>
                    <td><?php echo $oProdutos->NM_PROMOCAO[$i]; ?></td>
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
  
  <style>
    #sortable1 { list-style-type: none; margin: 0; padding: 0; width: 450px; }
    #sortable1 li { margin: 3px 3px 3px 0; padding: 1px; float: left; width: 100px; height: 90px; font-size: 1em; text-align: center; }
  </style>
  <div id="dialog-form" title="Configuração de produtos visualizados na vitrine">
    <form id="FRMvitrine" action="produtos.php" method="POST">
      <input type="hidden" name="sAcao" value="atualizar-vitrine" />
      <ul id="sortable1" class="connectedSortable">
        <?php 
          foreach ($aDetItemVitrine as $iChave => $aDados) {  ?>
            <li class="ui-state-default">
              <input type="hidden" name="CMPidProd[]" value="<?php echo $aDados['id_prod']?>">
              <?php echo $aDados['nm_prod']; ?>
            </li><?php   
          }
        ?>  
      </ul>
    </form>
  </div>
</html>