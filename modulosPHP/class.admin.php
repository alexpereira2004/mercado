<?php

include_once 'class.wTools.php';

class admin extends wTools{
  public function __construct() {
    include '../modulosPHP/config.php';
    //$this->CFGpath = $CFGpath;
    $this->sUrlBase = ($_SERVER['SERVER_NAME'] == 'localhost') ? 'http://localhost/Mercadodossabores' : 'http://www.mercadodossabores.com.br/homologa';
    parent::__construct();
  }

  public function incluirCss($sSecao = '') {
    
      $aParam = $this->buscarParametro('CSS_PADRAO');

      switch ($aParam['CSS_PADRAO'][0]) {
        case 'skin1':
          echo '<link href="../comum/skin_facebook.css" media="all" rel="stylesheet" type="text/css" />';
          break;

        case 'skin2':
          echo '<link href="../comum/skin_2.css" media="all" rel="stylesheet" type="text/css" />';
          break;

        case 'skin3':
          echo '<link href="../comum/skin-mercado-dos-sabores.css" media="all" rel="stylesheet" type="text/css" />';
          break;
      
        default :
          echo '<link href="../comum/skin_facebook.css" media="all" rel="stylesheet" type="text/css" />';
          break;
      }
    ?>    
    <link href="../comum/estilos_admin.css" media="all" rel="stylesheet" type="text/css" />
    <link href="../modulosJS/Multi-menu/css/style.css" media="screen, projection" rel="stylesheet"  type="text/css" />
    <link href="../modulosJS/ui/css/smoothness/jquery-ui-1.8.18.custom.css" rel="stylesheet" type="text/css" />

  <?php
  }

  public function linhaApresentacao($sNomeUsuario) { ?>
      <h1>Painel de Administração</h1>Bem vindo <?php echo $sNomeUsuario; ?> | <a href="../principal">Voltar ao site</a> | <a href="logout.php">Deslogar</a> <hr />
    <?php
  }


  public function incluirJs($sSecao) { ?>

    <script src="../modulosJS/funcoes.js"                            type="text/javascript"></script>
    <!--<script src="../modulosJS/admin.js"                              type="text/javascript"></script>-->
    <!--<script src="../modulosJS/jquery-1.8.2.min.js"                   type="text/javascript"></script>-->
    <script src="../modulosJS/jquery-1.7.1.min.js"                   type="text/javascript"></script>
    
    <script src="../modulosJS/Multi-menu/js/jquery.dropdownPlain.js" type="text/javascript"></script>
    <script src="../modulosJS/ui/js/jquery-ui-1.8.18.custom.min.js"  type="text/javascript" ></script>
    <script src="../modulosJS/ui/js/jquery-ui-timepicker-addon.js"   type="text/javascript" ></script>

<!--
    <script src="../modulosJS/jCrumb/jquery.jcrumbs.min.js"          type="text/javascript" ></script>
    <link href="../modulosJS/jCrumb/jCrumb.css" rel="stylesheet" type="text/css" />
-->
    <!-- Paginador -->
    <script src="../modulosJS/tableSorter/js/jquery.dataTables.js" type="text/javascript" ></script>
    <link href="../modulosJS/tableSorter/css/jquery.dataTables.css" media="all" rel="stylesheet"  type="text/css" />
    <?php
      switch ($sSecao) {

        default:
          break;
      }
  }

  public function cabecalho() {?>
      <div id="cabecalho">
        <h2>Administratção Mercado dos Sabores</h2>
        <a href="logout.php">Logout</a> | <a href="<?php echo $this->sUrlBase; ?>">ir ao site</a>
      </div>
    <?php
  }
  
  public function rodape($sSecao) { ?>
      <div id="rodape">
        <div id="criacao">
          <a href="http://www.lunacom.com.br">Desenvolvido por Lunacom Marketing Digital</a>
        </div>
      </div>
    <?php
  }

  public function montarMenu($sPgAtual = '') {
    ?>
    <div id="menu" style="z-index: 1000; position: relative">
      <ul class="dropdown">
        <li><a href="#">Administração</a>
          <ul class="sub_menu">
             <li><a href="usuarios.php">Usuários</a></li>
             <li><a href="vitrine.php">Vitrine</a></li>
<!--             <li><a href="usuarios.php">Grupos de Usuários</a></li>-->
             <li><a href="usuarios.php">Relatórios</a>
              <ul class="sub_menu">
                 <li><a href="#">Vendas</a></li>
                 <li><a href="#">Carrinho de compras</a></li>
                 <li><a href="#">Produtos</a></li>
                 <li><a href="#">Clientes</a></li>
                 <li><a href="#">Opiniões</a></li>
                 <li><a href="#">Tags</a></li>
                 <li><a href="#">Termos de busca</a></li>
              </ul>
             </li>
             <li><a href="usuarios.php">Logs</a>
              <ul class="sub_menu">
                 <li><a href="logs-acessos.php">Acessos</a></li>
              </ul>
             </li>
          </ul>
        </li>
        <li><a href="#">Cadastro</a>
          <ul class="sub_menu">
             <li><a href="clientes.php">Clientes</a></li>
             <li><a href="transportadoras.php">Transportadoras</a></li>
             <li><a href="frete.php">Frete</a></li>
             <li><a href="taxas.php">Taxas</a></li>
             <li><a href="descontos.php">Descontos</a></li>
             <li><a href="promocoes.php">Promoções</a></li>
          </ul>
        </li>
        <li><a href="#">Catálogo</a>
          <ul class="sub_menu">
             <li><a href="produtos.php">Produtos</a></li>
             <li><a href="estoque.php">Estoque</a></li>
             <li><a href="fabricantes.php">Fabricantes</a></li>
             <li><a href="categorias.php">Categorias</a></li>
             <li><a href="tags.php">Tags</a></li>
<!--             <li><a href="tags.php">Produtos Inativos</a></li>-->
          </ul>
        </li>
        <li><a href="#">Pedidos</a>
          <ul class="sub_menu">
             <li><a href="pedidos-pendentes.php">Pendentes</a></li>
             <li><a href="pedidos-aguardando.php">Aguardando</a></li>
             <li><a href="pedidos-efetivados.php">Efetivados</a></li>
             <li><a href="pedidos-cancelados.php">Cancelados</a></li>
          </ul>
        </li>
        <li><a href="#">Pagamentos & Expedição</a>
          <ul class="sub_menu">
             <li><a href="liberacoes.php">Liberações</a></li>
             <li><a href="finalizar-compras.php">Finalizar Compras</a></li>
             <li><a href="extrato-financeiro.php">Extrato Financeiro</a></li>
             <li><a href="extrato-transacoes.php">Extrato Transações</a></li>
          </ul>
        </li>

        <li><a href="#">Counteúdo</a>
          <ul class="sub_menu">
           <li><a href="novapagina.php">Gerenciar Páginas</a></li>
           <li><a href="novapagina.php">Blocos Estáticos</a></li>
           <li><a href="novapagina.php">Banners</a></li>
            <?php
              $this->pegaInfoDB('tcctd_htmlgeral', array('id', 'nm_pagina'), 'WHERE tp_secao LIKE \'conteudo\'');
              for ($i = 0; $i < $this->iLinhas; $i++) {
                ?>
                <!-- <li><a href="novapagina_edt.php?n=<?php echo $this->RETDB[$i][0] ?>"><?php echo $this->RETDB[$i][1] ?></a></li> -->
                <?php
              }
            ?>
          </ul>
        </li>
        <li><a href="#">Sistema</a>
          <ul class="sub_menu">
             <li><a href="configuracoes.php">Configurações</a></li>
             <li><a href="preferencias.php">Preferências</a></li>
             <li><a href="#">Suporte</a></li>
          </ul>
        </li>

      </ul>
    </div>
    <?php
  }

  public function minheight($iTam) { ?>
    <div id="min-height" style="height: <?php echo $iTam; ?>px; width: 0px; background: turquoise; float: left;"></div>
    <?php
  }
  public function minwidth($iTam) { ?>
    <div id="min-width" style="width: <?php echo $iTam; ?>px; height: 1px; background: #FFF;"></div>
    <?php
  }

  public function menu($sSecao, $CFGpath) {
    $this->montaLink('Anunciantes', '/painel/supervisao_anunciantes.php',$CFGpath, true, 'link_tomato');
    echo ' | ';
    $this->montaLink('Menu lateral', '/painel/menu_lateral.php',$CFGpath, true, 'link_tomato');
    echo ' | ';
    $this->montaLink('Promoções', '/painel/adm_promocoes.php',$CFGpath, true, 'link_tomato');
    echo ' | ';
    //$this->montaLink('Moderação', '/painel/moderação.php',$CFGpath, true, 'link_tomato');
    //echo ' | ';
    $this->montaLink('Comentários', '/painel/adm_comentarios.php',$CFGpath, true, 'link_tomato');
    echo ' | ';
    $this->montaLink('Banner Topo', '/painel/banner_top.php',$CFGpath, true, 'link_tomato');
    echo ' | ';
    $this->montaLink('Cupons', '/painel/adm_cupons.php',$CFGpath, true, 'link_tomato');
    echo ' | ';
    $this->montaLink('Vitrine', '/painel/adm_vitrine.php',$CFGpath, true, 'link_tomato');
    echo ' | ';
    $this->montaLink('Usuários', '/painel/adm_usuarios.php',$CFGpath, true, 'link_tomato');
    echo ' | ';
    $this->montaLink('Cadastros', '/painel/adm_cadastros.php',$CFGpath, true, 'link_tomato');
  }

}
?>
