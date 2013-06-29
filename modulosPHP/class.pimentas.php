<?php
include_once 'class.wTools.php';
include_once 'class.carrinho.php';
class pimentas extends wTools{
  public function __construct() {
    parent::__construct();
    $this->oCarrinho = new carrinho();
  }

  public function incluirCss($sPgAtual) { ?>
    <link href='http://fonts.googleapis.com/css?family=Happy+Monkey' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Headland+One' rel='stylesheet' type='text/css'>
    <link href="<?php echo $this->sUrlBase ?>/comum/skin-mercado-dos-sabores.css" media="all" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->sUrlBase ?>/comum/base.css" media="all" rel="stylesheet" type="text/css" />

    <?php
  }
  
  public function incluirJs($sPgAtual) { ?>

    <script src="<?php echo $this->sUrlBase?>/modulosJS/jquery-1.8.2.min.js"></script>
    <script src="<?php echo $this->sUrlBase ?>/modulosJS/util-loja.js" type="text/javascript"></script>
    <script src="<?php echo $this->sUrlBase ?>/modulosJS/corner.js" type="text/javascript"></script>
    <script src="<?php echo $this->sUrlBase ?>/modulosJS/funcoes.js" type="text/javascript"></script>
    <script src="<?php echo $this->sUrlBase ?>/modulosJS/bxGalleryThumbnail.js" type="text/javascript"></script>

    <link rel="stylesheet" href="<?php echo $this->sUrlBase;?>/modulosJS/jPages/css/jPages.css">
    <script src="<?php echo $this->sUrlBase;?>/modulosJS/jPages/js/jPages.js"></script>

    <script type="text/javascript">
      $(document).ready(function() {
        $('.animacao-start').fadeIn(1000);
        $('#pagina').corner('bottom 30px');
        $('#listagem, #carrinho').corner('bottom 20px');
        //$('.botao1').corner('5px');
        $('#produto .dados .imagens, .botao-comprar , #vitrine img, .botao1, .titulo,  input[type="submit"], .bt_salvar, .bt').corner('5px');
        
        $(".holder").jPages({
            containerID: "itemContainer"
        });

        $('.item').hover(
          function(){
            $(this).addClass('item-hover');
          },
          function(){
            $(this).removeClass('item-hover');
          }
        );
        
        $('#btPesquisar').click(function(){
          //var sPesquisa = $('#CMPpesquisar').val();
          $('#FRMpesquisar').submit();
        });
        /*
        $('.msg-atencao, .msg-erro, .msg-ok').fadeOut(2000, function() {
          alert($(this).html());
        });
        */
       //$('.msg-atencao, .msg-erro, .msg-ok').toggleClass('msg-erro', function(){
         //$(this).removeClass('.msg-atencao');
       //});

          

      });
    </script>
    <?php
  }

  public function incluirMetaTags($sPgAtual, $aDados = null) {
    include 'config.php';
    $aContTag = array();

    if (is_array($aDados)) {
      $aContTag['title'] = 'Mercado dos Sabores - '.$aDados['tx_meta_titulo'];
      $aContTag['description'] = $aDados['de_meta_description'];
      $aContTag['keywords']    = $aDados['tx_keywords'];
    } else {
      $aContTag['title'] = 'Mercado dos Sabores';
      $aContTag['description'] = 'Mercado dos Sabores';
      $aContTag['keywords']    = '';
    }


  ?>
    <meta http-equiv="Content-Type" content="text/html;charset=ISO-8859-1" />
    <meta name="description" content="<?php echo $aContTag['description']; ?>"/>
    <meta property="og:title" content="<?php echo $aContTag['title']; ?>"/>
    <meta property="og:type" content="website"/>
    <meta property="og:url" content="mercadodossabores.com.br"/>
    <meta property="og:image" content="http://profile.ak.fbcdn.net/hprofile-ak-snc4/276509_22344895408_3484107_q.jpg"/>
    <meta property="og:site_name" content="mercadodossabores.com.br"/>
    <meta property="og:description" content="<?php echo $aContTag['description']; ?>"/>
    <meta name="keywords" content="<?php echo $aContTag['keywords']; ?>" />
    <meta content="pt-br" name="language" />
    <meta content="Lunacom" name="author" />
    <meta content="ALL" name="robots" />
    <meta content="2 days" name="revisit-after" />
    <meta content="Web Page" name="document-type" />
    <!-- Enquanto o site não estiver finalizado, não será indexado pelas ferramentas de busca -->
    <meta name="robots" content="noindex">
  <?php
  }

  public function cabecalho() { ?>
    <div id="cabecalho">
      <div id="interior">
        <div id="logotipo">
          <a href="<?php echo $this->sUrlBase ?>">
            <img src="<?php echo $this->sUrlBase ?>/comum/imagens/estrutura/logotipo.png" alt="Mercado dos Sabores">
          </a>
        </div>
        <div id="itens">
          <span id="dados">
            <span style="float: right;">
              <?php
                if (isset($_SESSION[carrinho::getUsuarioSessao()]['login']['nm_usu'])) {
                  $sFrase = 'Bem vindo '.$_SESSION[carrinho::getUsuarioSessao()]['login']['nm_usu'].'! ';
                  $sFrase .= '&nbsp;<a href="'.$this->sUrlBase.'/conta/logout/">Sair</a>';
                  echo $sFrase;
                } else { ?>
                  Já possui cadastro? Faça <a href="<?php echo $this->sUrlBase; ?>/conta/login/">Login</a>
                  <?php
                }
              ?>              
            </span>
            <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a href="<?php echo $this->sUrlBase ?>/conta/meus-dados/">Meus Dados</a> | 
            <a href="<?php echo $this->sUrlBase ?>/conta/meus-pedidos/">Meus Pedidos</a>
          </span>
          <div id="carrinho">
            <a href="<?php echo $this->sUrlBase ?>/checkout/itens/">
              <img src="<?php echo $this->sUrlBase ?>/comum/imagens/estrutura/carrinho.png" alt="Mercado dos Sabores">
              Meu carrinho <br />
              <?php
                $iQntItens = 0;
                $oCarrinhoCliente = new clientes();
                $oCarrinhoCliente->validarTempoSessaoCarrinho();
                
                $oCarrinho = new carrinho();
                $oCarrinho->apresentarResumoCarrinho();
              ?>
            </a>
          </div>
          <div id="vendas">
            <h2>Tele Vendas</h2>
            <h2>(51) 3627-4024</h2>
          </div>
        </div>
        <div class="limpa"></div>
        <div id="menu">
          <div id="links">
            <a href="<?php echo $this->sUrlBase ?>/">Página Principal</a>
            <a href="<?php echo $this->sUrlBase ?>/promocoes/">Promoções</a>
            <a href="<?php echo $this->sUrlBase ?>/divulgacao/sobre-a-loja/">Sobre a Loja</a>
            <a href="<?php echo $this->sUrlBase ?>/divulgacao/atendimento/">Atendimento</a>
            <form id="FRMpesquisar" method="post" style="display: inline" action="<?php echo $this->sUrlBase; ?>/pesquisa/">             
              <input type="hidden" name="sAcao" value="barra-pesquisa" />
              <input type="text" class="pesquisar" id="CMPpesquisar" placeholder="Pesquisar" name="CMPpesquisar" value="" />
              <span id="btPesquisar" class="botao1"  >OK</span>
            </form>
          </div>
          <img src="<?php echo $this->sUrlBase ?>/comum/imagens/estrutura/dobras.png" alt="Mercado dos Sabores">
        </div>
      </div>
    </div>
    <div class="limpa"></div>
    <?php
  }

  public function listagem($sPgAtual = '') { ?>
    <div id="listagem">
      <?php 
        $aPaginasUsuario = array ('usuario-dados', 'usuario-cadastro', 'usuario-pedidos');
        if (in_array($sPgAtual, $aPaginasUsuario)) { ?>
      <h3>Meus Pedidos</h3>
        <ul>
          <li><a href="<?php echo $this->sUrlBase; ?>/conta/meus-pedidos/finalizados/">Pedidos Finalizados</a></li>
          <li><a href="<?php echo $this->sUrlBase; ?>/conta/meus-pedidos/aberto/">Pedidos em Aberto</a></li>
        </ul>
      <h3>Meus Dados</h3>
        <ul>
          <li><a href="<?php echo $this->sUrlBase; ?>/conta/alterar-cadastro/">Alterar Cadastro</a></li>
          <li><a href="<?php echo $this->sUrlBase; ?>/conta/alterar-cadastro/enderecos/">Alterar Endereços</a></li>
          <li><a href="<?php echo $this->sUrlBase; ?>/conta/alterar-cadastro/email/">Alterar Email</a></li>
          <li><a href="<?php echo $this->sUrlBase; ?>/conta/alterar-cadastro/senha/">Alterar Senha</a></li>
        </ul>
      <?php
        }
        $sQuery = "SELECT * 
                     FROM ( SELECT id,
                                   nm_categoria, 
                                   tx_link,
                                  (SELECT COUNT(1) 
                                     FROM tr_prod_cat 
                                    WHERE tr_prod_cat.id_cat = tc_prod_categorias.id) AS qnt_cadastrados
                              FROM tc_prod_categorias
                             WHERE tc_prod_categorias.cd_status = 'A') consulta
                  ORDER BY qnt_cadastrados DESC ";
        $aDados = $this->buscarInfoDB($sQuery, true);
        
        ?>
      <h3>Categorias</h3>
      <ul>
        <?php
          for ($i = 0; $i < $this->iLinhas; $i++) { ?>
            <li><a href="<?php echo $this->montarUrlLinkId('categorias', $aDados['tx_link'][$i], $aDados['id'][$i]); ?>"><?php echo $aDados['nm_categoria'][$i].'('.$aDados['qnt_cadastrados'][$i].')';?></a></li>
          <?php
          }
        ?>
      </ul>

      <?php 
        $sQuery = "SELECT * 
                     FROM ( SELECT id,
                                   nm_fabricante, 
                                   tx_link,
                                  (SELECT COUNT(1) 
                                     FROM tc_produtos
                                    WHERE tc_produtos.id_fabricante = tc_prod_fabricantes.id) AS qnt_cadastrados
                              FROM tc_prod_fabricantes
                             WHERE tc_prod_fabricantes.cd_status = 'A') consulta
                  ORDER BY qnt_cadastrados DESC ";
        $aDados = $this->buscarInfoDB($sQuery, true);
      ?>
      <h3>Fabricantes</h3>
      <ul>
        <?php
          for ($i = 0; $i < $this->iLinhas; $i++) {
            ?>
            <li><a href="<?php echo $this->montarUrlLinkId('fabricante', $aDados['tx_link'][$i], $aDados['id'][$i]); ?>"><?php echo $aDados['nm_fabricante'][$i].'('.$aDados['qnt_cadastrados'][$i].')';?></a></li>
          <?php
          }
        ?>
      </ul>
    </div>
    <?php
  }
  

  public function rodape($sPgAtual = '') {?>
    <div id="rodape">
      <div id="interior">
        <br />
        <br />
        <br />
        <table style=" width: 300px; margin: 10px 0 0 70px; float: left;">
          <tr>
            <td style="width: 50%"><a href="<?php echo $this->sUrlBase?>/conta/meus-dados/">Meus Dados</a></td>
            <td style="width: 50%"><a href="<?php echo $this->sUrlBase?>/divulgacao/servicos/">Como comprar</a></td>
          </tr>
          <tr>
            <td><a href="<?php echo $this->sUrlBase?>/conta/meus-pedidos/">Meus Pedidos</a></td>
            <td><a href="<?php echo $this->sUrlBase?>/divulgacao/formas-de-pagamento/">Formas de Pagamento</a></td>
          </tr>
          <tr>
            <td><a href="<?php echo $this->sUrlBase?>/divulgacao/sobre-a-loja/">A Empresa</a></td>
            <td><a href="<?php echo $this->sUrlBase?>/divulgacao/prazos-de-entrega/">Prazos de Entrega</a></td>
          </tr>
          <tr>
            <td><a href="<?php echo $this->sUrlBase?>/divulgacao/servicos/">Serviços</a></td>
            <td><a href="<?php echo $this->sUrlBase?>/divulgacao/politica-de-troca/">Política de Troca</a></td>
          </tr>
          <tr>
            <td><a href="<?php echo $this->sUrlBase?>/divulgacao/transporte/">Transporte</a></td>
            <td><a href="<?php echo $this->sUrlBase?>/contato/">Contato</a></td>
          </tr>
        </table>
        <table style=" width: 500px; margin: 10px 0 0 80px; float: left;">
          <tr>
            <td><b>Formas de pagamento</b></td>
            <td><b>Certificados</b></td>
          </tr>
          <tr>
            <td><img src="<?php echo $this->sUrlBase?>/comum/imagens/estrutura/pagamento-rodape.png" alt="Pagamento" /></td>
            <td></td>
          </tr>
        </table>
        <div class="limpa">&nbsp;</div>
        <div style="float: right">
          <a href="http://www.lunacom.com.br">Desenvolvido por Lunacom Web</a>
        </div>

      </div>
    </div>
    <?php
  }

  /* pimentas::box01
   *
   * Caixa box com título
   * @date 12/01/2013
   * @param  string 
   * @param  string $sTitulo
   * @param  string $sTexto
   * @return true
   */
  public function box01($sTitulo, $sTexto) { ?>
    <div class="box-01">
      <div class="cabecalho"><?php echo $sTitulo; ?></div>
      <div class="conteudo"><?php echo $sTexto; ?></div>
    </div>
    <?php
    return true;
  }
  /* pimentas::box01
   *
   * Caixa box com título
   * @date 12/01/2013
   * @param  string 
   * @param  string $sTitulo
   * @param  string $sTexto
   * @return true
   */
  public function box02($sTitulo, $sTexto) { ?>
    <div class="box-02">
      <div class="cabecalho"><?php echo $sTitulo; ?></div>
      <div class="conteudo"><?php echo $sTexto; ?></div>
    </div>
    <?php
    return true;
  }

  /* pimentas::montarUrlLinkId
   *
   * O link usado na URL amigável será composto com o nome e o ID do item. Este
   * método prepara este link de uma forma padrão para os diferentes pontos onde
   * serão usados URL amigável
   * 
   * @date 24/01/2013
   * @param  string $sDiretorio - fabricante, categorias
   * @param  string $sTxLink    - Nome(tx_link) do fabricante ou da categoria. Se chegar 
   *                              vazio, será buscado por SQL o devido campo.
   * @param  string $iId
   * @return true
   */
  public function montarUrlLinkId($sDiretorio, $sTxLink = '', $iId = '') {
    $aDiretorios = array('fabricante' => 'fabricantes-detalhe',
                         'categorias' => 'categorias-detalhe'
        );
    if (!is_numeric($iId)) {
      $iId = explode(',', $iId);
      $iId = array_shift($iId);
    }
    if ($sTxLink == '') {
      $sQuery = "SELECT id,
                        nm_categoria, 
                        tx_link
                   FROM tc_prod_categorias
                  WHERE tc_prod_categorias.cd_status = 'A' 
                    AND id = ".$iId;
      $aDados = $this->buscarInfoDB($sQuery);

      $sTxLink = $aDados[2];
    }
        
        return $this->sUrlBase.'/'.$aDiretorios[$sDiretorio].'/'.$sTxLink.'-'.str_pad($iId, 5, '0', 0);
  }
  
  public function montarFormLogin($sAction, $aMsg) {
    ob_start();
    $this->msgRetAlteracoes($aMsg, '', '', true);
    ?>
    <form action="<?php echo $sAction; ?>" method="post">
      <input type="hidden" name="sAcao" value="acessar" />
      <table class="w90">
        <tr>
          <td class="infoheader">E-mail</td>
        </tr>
        <tr>
          <td class="infovalue"><input class="w98" type="text" name="CMPemail" value="<?php echo isset($_POST['CMPemail']) ? $_POST['CMPemail'] : ''; ?>" autocomplete="off" /></td>
        </tr>
        <tr>
          <td class="infoheader">Senha</td>
        </tr>
        <tr>
          <td class="infovalue"><input class="w98" type="password" name="CMPsenha" value="" /></td>
        </tr>
        <tr style="text-align: right">
          <td><input type="submit" value="Login" /></td>
        </tr>
      </table>
    </form>
    <?php
    $sRet = ob_get_clean();
    return $sRet;
  }
  
  public function montarFormLoginNaoCadastrado($sAction, $aMsg) {
    ob_start();
    $this->msgRetAlteracoes($aMsg, '', '', true);
    ?>
    <form action="<?php echo $sAction; ?>" method="post">
      <input type="hidden" name="sAcao" value="novo-cadastro" />
      <table class="w90">
        <tr>
          <td class="infoheader">E-mail</td>
        </tr>
        <tr>
          <td class="infovalue"><input class="w98" type="text" name="CMPnovo-email" value="<?php echo isset($_POST['CMPnovo-email']) ? $_POST['CMPnovo-email'] : ''; ?>" autocomplete="off" /></td>
        </tr>
        <tr>
          <td class="infoheader">Cep:</td>
        </tr>
        <tr>
          <td class="infovalue"><input class="w98 mask_cep" type="text" name="CMPcep" value="<?php echo isset($_POST['CMPcep']) ? $_POST['CMPcep'] : ''; ?>" class="mask_cep" /></td>
        </tr>
        <tr style="text-align: right">
          <td><input type="submit" value="Cadastrar" /></td>
        </tr>
      </table>
    </form>
    <?php
    $sRet = ob_get_clean();
    return $sRet;
    
  }
  

  /* pimentas::apresentarDadosCliente
   *
   * Irá mostrar ao cliente os dados de sua conta
   * 
   * @date 14/02/2013
   * @param  int $iIdCli - Id do cliente
   * @param  string $iId
   * @return true
   */
  public function apresentarDadosCliente($oDadosCliente) {
    ob_start();
    ?>
    <table class="w90">
      <tr>
        <td class="infoheader w30">Nome:</td>
        <td class="infovalue w70"><?php echo $oDadosCliente->oCli->NM_CLIENTE[0] ?></td>
      </tr>
      <tr>
        <td class="infoheader">Email:</td>
        <td class="infovalue"><?php echo $oDadosCliente->oCli->TX_EMAIL[0] ?></td>
      </tr>
    </table>
    <?php
    $sRet = ob_get_clean();
    return $sRet;
  }
  
  public function apresentarDadosEndereçoCliente($oDadosCliente) {
    ob_start();
    ?>
    <table class="w90">
      <tr>
        <td class="infoheader w30"><?php echo $oDadosCliente->oEnd->TP_LOGRADOURO[0]; ?>:</td>
        <td class="infovalue w70"><?php echo $oDadosCliente->oEnd->NM_LOGRADOURO[0] ?></td>
      </tr>
      <tr>
        <td class="infoheader">Número:</td>
        <td class="infovalue">
          <?php 
            echo $oDadosCliente->oEnd->TX_NUMERO[0];
          ?>
        </td>
      </tr>
        <?php 
          if ($oDadosCliente->oEnd->TX_COMPLEMENTO[0] != '') { ?>
        <tr>
          <td class="infoheader">Complemento:</td>
          <td class="infovalue">
            <?php 
              echo $oDadosCliente->oEnd->TX_COMPLEMENTO[0];
            ?>
          </td>
        </tr>
          <?php    
          }
        ?>
      <tr>
        <td class="infoheader">Cep:</td>
        <td class="infovalue">
          <?php 
            echo $oDadosCliente->oEnd->NU_CEP[0];
          ?>
        </td>
      </tr>
      <tr>
        <td class="infoheader">Bairro:</td>
        <td class="infovalue">
          <?php 
            echo $oDadosCliente->oEnd->TX_BAIRRO[0];
          ?>
        </td>
      </tr>
      <tr>
        <td class="infoheader">Localidade:</td>
        <td class="infovalue">
          <?php 
            echo $oDadosCliente->oEnd->NM_CID[0].' / '.$oDadosCliente->oEnd->NM_UF[0];
          ?>
        </td>
      </tr>
    </table>
    <?php
    $sRet = ob_get_clean();
    return $sRet;
  }
}
?>
