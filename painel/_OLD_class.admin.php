<?php
include_once 'class.tcl_htmlgeral.php';
include_once 'class.wTools.php';
include_once 'adapter.usuarios.php';

class admin extends wTools{

public function  __construct() {
    $this->oHtmlGeral = new tcl_htmlgeral();
    $this->sUrlBase = ($_SERVER['SERVER_NAME'] == 'localhost') ? 'http://localhost/lunacom' : 'http://www.lunacom.com.br';
    $this->usuarios = new usuarios();
    parent::__construct();
    $this->buscarParametro(array('CABECALHO','RODAPE'));   
    $this->sCabecalho = $this->aParametros['CABECALHO'][0];
    $this->sRodape    = $this->aParametros['RODAPE'][0];
  }
public function montarMenu($sSecao) {
  include 'config.php';
?>

  <div id="menu">

  </div>
  <?php
  }
  public function incluirScripts($sSecao = '') {
    switch ($sSecao) {
      //<script src="../modulosJS/jquery-1.2.6.js" type="text/javascript"></script>
    } ?>

    <!-- <script src="../modulosJS/funcoesAjax.js"    type="text/javascript"></script> -->
    <script src="modulosJS/jQuery-1.7.1.js"  type="text/javascript"></script>
    

    <!-- Tool Tip -->
    <script src="modulosJS/jquery.qtip/jquery.qtip-1.0.0-rc3.min.js"    type="text/javascript"></script>
    <!-- Tool Tip -->

    <!-- Máscara -->
    <script src="modulosJS/maskedinput/jquery.maskMoney.js"             type="text/javascript"></script>
    <script src="modulosJS/maskedinput/jquery.maskedinput-1.3.min.js"  type="text/javascript"></script>
    <!-- Máscara -->

    <!-- Editor Rich text-->
    <script src="modulosJS/jwysiwyg/jquery.wysiwyg.js"   type="text/javascript"></script>
    <link   href="modulosJS/jwysiwyg/jquery.wysiwyg.css" type="text/css" rel="stylesheet" media="screen"/>
    <!-- Editor Rich text-->

    <!-- UI -->
    <script src="modulosJS/ui/js/jquery-ui-1.8.18.custom.min.js"         type="text/javascript"></script>
    <script src="modulosJS/ui/js/jquery-ui-timepicker-addon.js"          type="text/javascript"></script>
    <link rel="stylesheet"  href="modulosJS/ui/css/smoothness/jquery-ui-1.8.18.custom.css" type="text/css" media="screen"/>
    <!-- UI -->

    <script src="modulosJS/simpleImageCheck/jquery.simpleImageCheck-0.4.min.js"   type="text/javascript"></script>
    <script src="modulosJS/funcoes.js"       type="text/javascript"></script>
    <?php
  }
  public function montarCabecalho() { ?>
    <div id="cabecalho">
      <div id="conteudo">
        <?php
          echo $this->sCabecalho;
        ?>
      </div>
      <div class="limpa"></div>
      <div id="acoes">
        <?php echo isset($_SESSION['nm_usu']) ? 'Bem vindo '.$_SESSION['nm_usu'].'! | ' : ''; ?>
        <a href="logoff.php">Sair</a>
      </div>
      <div class="limpa"></div>
		</div>
    <?php
  }

  public function montarRodape() {?>
    <div id="rodape">
      <div id="acoes">

      </div><div class="limpa"></div>
      <div id="conteudo">
        <?php
          echo $this->sRodape;
        ?>
      </div><div class="limpa"></div>
    </div><?php
  }

  public function incluirCss($sSecao = '') {
    $aSkins = array(1 => 'skin_01.css',
                    2 => 'skin_facebook.css',
                    3 => 'skin_02.css');
    $aParam = $this->buscarParametro('SKIN');
    
    switch ($sSecao) {

    } ?>

    <link rel="stylesheet"  href="comum/estilos.css"               type="text/css" media="screen"/>
    <link rel="stylesheet"  href="comum/<?php echo $aSkins[$aParam['SKIN'][0]]; ?>" type="text/css" media="screen"/>
    <link rel="icon"        href="comum/imagens/icones/lock_open.png" type="image/x-icon" />
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

  public function montarFormPesquisa() { ?>
    <span id="pesquisar" style="float: right">
      <form name="FRMpesquisar" id="FRMpesquisar" action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
        <input type="text" name="CMPpesquisa" value="<?php echo (isset($_POST['CMPpesquisa'])) ? $_POST['CMPpesquisa'] : ''; ?>" />
        <input type="hidden" name="sAcao" value="pesquisar" />
        <input type="submit" value="Pesquisar" />
      </form>
    </span>
    <?php
  }

  public function montarMenuAdministrativo() {
    ?>

    <div id="menu" style="float: right">
      <?php
      if ($this->usuarios->verificarPermissaoAcesso('PG_ADMSIS', 'V')) {
      ?>
      <ul class="dropdown">
        <li class="top"><a href="#">Administrar</a>
          <ul class="sub_menu">
             <li><a href="usuarios.php">Usuários</a></li>
             <li><a href="grupos.php">Grupos de usuários</a></li>
             <li><a href="parametros.php">Parâmetros</a></li>
             <li><a href="lixeira.php">Lixeira</a></li>
          </ul>
        </li>
      </ul><?php
      } ?>
    </div>
    <?php
  }

  public function criarSigla($sStr) {
    $aNomes = array();
    $aDados = explode(' ', $sStr);
    $iQnt = count($aDados);

    // Passo 1
    foreach ($aDados as $sNome) {

      // Nome escolhido tem que ter mais de 2 caracteres e guarda no array somente 3 nomes
      if (strlen($sNome) > 2 && count($aNomes) < 3) {
        $aNomes[] = trim($sNome);
      }
    }

    // Passo 2
    if (count($aNomes) < 3) {
      for ($i = count($aNomes); $i < 3; $i++) {
        $aNomes[$i] = $aNomes[0];
      }
    }

    // Passo 3
    $aNomesOk = array();
    foreach ($aNomes as $sNome) {
      $aNomesOk[] = strtoupper($this->montaUrlAmigavel($sNome, false));
    }

    // Passo 4
    $this->pegaInfoDB('tc100_projetos', 'distinct(cd_projeto)');
    $aCdProjExistentes = array();
    foreach ($this->RETDB as $aRet) {
      $aCdProjExistentes[] = $aRet[0];
    }
    $aCdProjExistentes[] = '';

    // Passo 5
    $c = 1;
    $this->a = 0;
    $this->b = 0;
    $this->c = 0;
    $this->iQnt1 = strlen($aNomesOk[0]);
    $this->iQnt2 = strlen($aNomesOk[1]);
    $this->iQnt3 = strlen($aNomesOk[2]);



    do {
      $sSigla = $this->logicaSigla($aNomesOk);
      $c++;
    } while (in_array($sSigla, $aCdProjExistentes) && ($c < 15));
    $sSigla = $sSigla == '' ? 'ZER' : $sSigla;

    return $sSigla;

  }

  public function logicaSigla($aNomesOk) {

    $sSigla  = $aNomesOk[0][$this->a];
    $sSigla .= $aNomesOk[1][$this->b];
    $sSigla .= $aNomesOk[2][$this->c];

    if (($this->iQnt1 > $this->a +1 )) {
      $this->a++;
    } else {
      $this->a = 0;
    }
    if (($this->iQnt2 > $this->a + 1)) {
      $this->b = $this->a + 1;
    } else {
      $this->b = 0;
    }
    if ($this->iQnt3 > $this->b + 1) {
      $this->c = $this->b + 1;
    } else {
      $this->c = 0;
    }

    //echo '<hr>';
    //echo 'A:'.$this->a.' B:'.$this->b.' C:'.$this->c.' '.$sSigla.'<br />';

    if ($sSigla[0] == $sSigla[1] && $sSigla[1] == $sSigla[2]) {
      $sSigla = $this->logicaSigla($aNomesOk);
    }
    return $sSigla;
  }


  /* admin::roteadorDeAcesso
   *
   * Se o parâmetro for true, redireciona o usuário para uma página pois a tentativa
   * de acesso não foi validada
   *
   * @date 01/07/2012
   * @param bool $bPermissao - Resultado da consulta às permissões concedidas ao usuário
   * @param mixed $mLocal    - Nome da página que teve a tentativa de acesso
   * @return true
   */
  public function roteadorDeAcesso($bPermissao, $mLocal = '') {


    $aLocais = array();
    $sLocais = '';

    if (!$bPermissao) {
      $sFiltro = $this->montarIN($mLocal);

      $this->pegaInfoDB('tc_permissoes', 'nm_permissao', 'WHERE cd_codigo IN ('.$sFiltro.') ');

      foreach ($this->RETDB as $aDados) {
        $aLocais[] = $aDados[0];
      }

      $sLocais = implode(', ', $aLocais);
      $sMsg = ($sLocais != '') ? 'Para acessar esta página seu usuário deve possuir permissão à: '.$sLocais : 'O acesso para esta página é restrito.';
      $sUrl    = 'gerenciadorSuporte.php';
      $aCampos = array('mResultado' => 2,
                       'sMsg' => $sMsg,
                       'sMsgErro' => '',
                       'CMPmsgRetorno' => 'ret');
      $this->redirFRM($sUrl, $aCampos);
      die();
    }

    return true;
  }
}
?>
