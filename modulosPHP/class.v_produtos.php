
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       27-03-2013
   **/

  class v_produtos {
  
    public    $id;
    public    $nm_produto;
    public    $cd_produto;
    public    $de_curta;
    public    $de_longa;
    public    $cd_status;
    public    $nu_cliques;
    public    $nm_pronuncia;
    public    $id_tipo;
    public    $id_fabricante;
    public    $id_categoria;
    public    $tx_link;
    public    $nu_x;
    public    $nu_y;
    public    $nu_z;
    public    $nu_peso;
    public    $nu_atual;
    public    $nu_minimo;
    public    $tx_falta_prod;
    public    $cd_visivel_em_falta;
    public    $vl_adicionais;
    public    $vl_taxas;
    public    $vl_custo;
    public    $pc_margem;
    public    $vl_final;
    public    $cd_visivel;
    public    $cd_status_categoria;
    public    $id_cat_agrupado;
    public    $nm_categoria_agrupado;
    public    $id_tag_agrupado;
    public    $nm_tag_agrupado;
    public    $id_imagem_agrupado;
    public    $nm_imagem_agrupado;
    public    $nm_imagem_principal;
    public    $iCdMsg;
    public    $sMsg;
    public    $aMsg;
    public    $sErro;
    public    $sBackpage;
    protected $DB_LINK;
    protected $oUtil;

    public function __construct() {
      include 'conecta.php';
      $this->DB_LINK = $link;
      $this->oUtil   = new wTools();
    }

    public function salvar() {

      try {
        $aValidar = array ( 1 => array('Produto' , $_POST['CMPprodutos-produto'], 'varchar(255)', true),
                            2 => array('Produto' , $_POST['CMPprodutos-produto'], 'varchar(50)', true),
                            3 => array('Curta' , $_POST['CMPprodutos-curta'], 'text', true),
                            4 => array('Longa' , $_POST['CMPprodutos-longa'], 'text', true),
                            5 => array('Status' , $_POST['CMPprodutos-status'], 'varchar(1)', true),
                            6 => array('Cliques' , $_POST['CMPprodutos-cliques'], 'int(10)', true),
                            7 => array('Pronuncia' , $_POST['CMPprodutos-pronuncia'], 'varchar(255)', true),
                            8 => array('Tipo' , $_POST['CMPprodutos-tipo'], 'int(8)', true),
                            9 => array('Fabricante' , $_POST['CMPprodutos-fabricante'], 'int(8)', true),
                            10 => array('Categoria' , $_POST['CMPprodutos-categoria'], 'int(8)', true),
                            11 => array('Link' , $_POST['CMPprodutos-link'], 'varchar(255)', true),
                            12 => array('X' , $_POST['CMPprodutos-x'], 'decimal(8,2)', true),
                            13 => array('Y' , $_POST['CMPprodutos-y'], 'decimal(8,2)', true),
                            14 => array('Z' , $_POST['CMPprodutos-z'], 'decimal(8,2)', true),
                            15 => array('Peso' , $_POST['CMPprodutos-peso'], 'decimal(8,2)', true),
                            16 => array('Atual' , $_POST['CMPprodutos-atual'], 'int(5)', true),
                            17 => array('Minimo' , $_POST['CMPprodutos-minimo'], 'int(5)', true),
                            18 => array('Falta-prod' , $_POST['CMPprodutos-falta-prod'], 'varchar(255)', true),
                            19 => array('Visivel-em-falta' , $_POST['CMPprodutos-visivel-em-falta'], 'char(1)', true),
                            20 => array('Adicionais' , $_POST['CMPprodutos-adicionais'], 'decimal(15,2)', true),
                            21 => array('Taxas' , $_POST['CMPprodutos-taxas'], 'decimal(15,2)', true),
                            22 => array('Custo' , $_POST['CMPprodutos-custo'], 'decimal(15,2)', true),
                            23 => array('Margem' , $_POST['CMPprodutos-margem'], 'int(2)', true),
                            24 => array('Final' , $_POST['CMPprodutos-final'], 'decimal(15,2)', true),
                            25 => array('Visivel' , $_POST['CMPprodutos-visivel'], 'char(1)', true),
                            26 => array('Status-categoria' , $_POST['CMPprodutos-status-categoria'], 'char(2)', true),
                            27 => array('Cat-agrupado' , $_POST['CMPprodutos-cat-agrupado'], 'blob', true),
                            28 => array('Categoria-agrupado' , $_POST['CMPprodutos-categoria-agrupado'], 'text', true),
                            29 => array('Tag-agrupado' , $_POST['CMPprodutos-tag-agrupado'], 'blob', true),
                            30 => array('Tag-agrupado' , $_POST['CMPprodutos-tag-agrupado'], 'text', true),
                            31 => array('Imagem-agrupado' , $_POST['CMPprodutos-imagem-agrupado'], 'blob', true),
                            32 => array('Imagem-agrupado' , $_POST['CMPprodutos-imagem-agrupado'], 'text', true),
                            33 => array('Imagem-principal' , $_POST['CMPprodutos-imagem-principal'], 'text', true),
                            );

        // Validação de preenchimento
        if ($this->oUtil->valida_Preenchimento($aValidar) !== true) {
          $this->aMsg = $this->oUtil->aMsg;
          throw new excecoes(25);
        }

        // Editar conteúdo
        if ($_POST['sAcao'] == 'editar') {
          $this->editar($this->ID[0]);

        } elseif ($_POST['sAcao'] == 'inserir') {
          $this->inserir();
          $this->oUtil->redirFRM($this->sBackpage, $this->aMsg);
          header('location:'.$this->sBackpage);
          exit;
        } else {

          // Tipo de ação não é válido
          throw new excecoes(20, $this->oUtil->anti_sql_injection($_POST['CMPpgAtual']));
        }

      } catch (excecoes $e) {
        $e->bReturnMsg = false;
        $e->getErrorByCode();
        if (is_array($e->aMsg)) {
          $this->aMsg = $e->aMsg;
        }
        return false;
      }
      return true;
    }

      
    public function listar($sFiltro = '') {
      $sQuery = 'SELECT id,
                        nm_produto, 
                        cd_produto, 
                        de_curta, 
                        de_longa, 
                        cd_status, 
                        nu_cliques, 
                        nm_pronuncia, 
                        id_tipo, 
                        id_fabricante, 
                        id_categoria, 
                        tx_link, 
                        nu_x, 
                        nu_y, 
                        nu_z, 
                        nu_peso, 
                        nu_atual, 
                        nu_minimo, 
                        tx_falta_prod, 
                        cd_visivel_em_falta, 
                        vl_adicionais, 
                        vl_taxas, 
                        vl_custo, 
                        pc_margem, 
                        vl_final, 
                        cd_visivel, 
                        cd_status_categoria, 
                        id_cat_agrupado, 
                        nm_categoria_agrupado, 
                        id_tag_agrupado, 
                        nm_tag_agrupado, 
                        id_imagem_agrupado, 
                        nm_imagem_agrupado, 
                        nm_imagem_principal 
                   FROM v_produtos
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasV_produtos = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]                    = $aResultado['id']; 
        $this->NM_PRODUTO[]            = $aResultado['nm_produto']; 
        $this->CD_PRODUTO[]            = $aResultado['cd_produto']; 
        $this->DE_CURTA[]              = $aResultado['de_curta']; 
        $this->DE_LONGA[]              = $aResultado['de_longa']; 
        $this->CD_STATUS[]             = $aResultado['cd_status']; 
        $this->NU_CLIQUES[]            = $aResultado['nu_cliques']; 
        $this->NM_PRONUNCIA[]          = $aResultado['nm_pronuncia']; 
        $this->ID_TIPO[]               = $aResultado['id_tipo']; 
        $this->ID_FABRICANTE[]         = $aResultado['id_fabricante']; 
        $this->ID_CATEGORIA[]          = $aResultado['id_categoria']; 
        $this->TX_LINK[]               = $aResultado['tx_link']; 
        $this->NU_X[]                  = $aResultado['nu_x']; 
        $this->NU_Y[]                  = $aResultado['nu_y']; 
        $this->NU_Z[]                  = $aResultado['nu_z']; 
        $this->NU_PESO[]               = $aResultado['nu_peso']; 
        $this->NU_ATUAL[]              = $aResultado['nu_atual']; 
        $this->NU_MINIMO[]             = $aResultado['nu_minimo']; 
        $this->TX_FALTA_PROD[]         = $aResultado['tx_falta_prod']; 
        $this->CD_VISIVEL_EM_FALTA[]   = $aResultado['cd_visivel_em_falta']; 
        $this->VL_ADICIONAIS[]         = $aResultado['vl_adicionais']; 
        $this->VL_TAXAS[]              = $aResultado['vl_taxas']; 
        $this->VL_CUSTO[]              = $aResultado['vl_custo']; 
        $this->PC_MARGEM[]             = $aResultado['pc_margem']; 
        $this->VL_FINAL[]              = $aResultado['vl_final']; 
        $this->CD_VISIVEL[]            = $aResultado['cd_visivel']; 
        $this->CD_STATUS_CATEGORIA[]   = $aResultado['cd_status_categoria']; 
        $this->ID_CAT_AGRUPADO[]       = $aResultado['id_cat_agrupado']; 
        $this->NM_CATEGORIA_AGRUPADO[] = $aResultado['nm_categoria_agrupado']; 
        $this->ID_TAG_AGRUPADO[]       = $aResultado['id_tag_agrupado']; 
        $this->NM_TAG_AGRUPADO[]       = $aResultado['nm_tag_agrupado']; 
        $this->ID_IMAGEM_AGRUPADO[]    = $aResultado['id_imagem_agrupado']; 
        $this->NM_IMAGEM_AGRUPADO[]    = $aResultado['nm_imagem_agrupado']; 
        $this->NM_IMAGEM_PRINCIPAL[]   = $aResultado['nm_imagem_principal']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO v_produtos(
                             NM_PRODUTO, 
                             CD_PRODUTO, 
                             DE_CURTA, 
                             DE_LONGA, 
                             CD_STATUS, 
                             NU_CLIQUES, 
                             NM_PRONUNCIA, 
                             ID_TIPO, 
                             ID_FABRICANTE, 
                             ID_CATEGORIA, 
                             TX_LINK, 
                             NU_X, 
                             NU_Y, 
                             NU_Z, 
                             NU_PESO, 
                             NU_ATUAL, 
                             NU_MINIMO, 
                             TX_FALTA_PROD, 
                             CD_VISIVEL_EM_FALTA, 
                             VL_ADICIONAIS, 
                             VL_TAXAS, 
                             VL_CUSTO, 
                             PC_MARGEM, 
                             VL_FINAL, 
                             CD_VISIVEL, 
                             CD_STATUS_CATEGORIA, 
                             ID_CAT_AGRUPADO, 
                             NM_CATEGORIA_AGRUPADO, 
                             ID_TAG_AGRUPADO, 
                             NM_TAG_AGRUPADO, 
                             ID_IMAGEM_AGRUPADO, 
                             NM_IMAGEM_AGRUPADO, 
                             NM_IMAGEM_PRINCIPAL 
)
      VALUES(
              '".$this->ID[0]."', 
              '".$this->NM_PRODUTO[0]."', 
              '".$this->CD_PRODUTO[0]."', 
              '".$this->DE_CURTA[0]."', 
              '".$this->DE_LONGA[0]."', 
              '".$this->CD_STATUS[0]."', 
              '".$this->NU_CLIQUES[0]."', 
              '".$this->NM_PRONUNCIA[0]."', 
              '".$this->ID_TIPO[0]."', 
              '".$this->ID_FABRICANTE[0]."', 
              '".$this->ID_CATEGORIA[0]."', 
              '".$this->TX_LINK[0]."', 
              '".$this->NU_X[0]."', 
              '".$this->NU_Y[0]."', 
              '".$this->NU_Z[0]."', 
              '".$this->NU_PESO[0]."', 
              '".$this->NU_ATUAL[0]."', 
              '".$this->NU_MINIMO[0]."', 
              '".$this->TX_FALTA_PROD[0]."', 
              '".$this->CD_VISIVEL_EM_FALTA[0]."', 
              '".$this->VL_ADICIONAIS[0]."', 
              '".$this->VL_TAXAS[0]."', 
              '".$this->VL_CUSTO[0]."', 
              '".$this->PC_MARGEM[0]."', 
              '".$this->VL_FINAL[0]."', 
              '".$this->CD_VISIVEL[0]."', 
              '".$this->CD_STATUS_CATEGORIA[0]."', 
              '".$this->ID_CAT_AGRUPADO[0]."', 
              '".$this->NM_CATEGORIA_AGRUPADO[0]."', 
              '".$this->ID_TAG_AGRUPADO[0]."', 
              '".$this->NM_TAG_AGRUPADO[0]."', 
              '".$this->ID_IMAGEM_AGRUPADO[0]."', 
              '".$this->NM_IMAGEM_AGRUPADO[0]."', 
              '".$this->NM_IMAGEM_PRINCIPAL[0]."' 
    )";
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        $this->iCdMsg = 1;
        $this->sMsg  = 'Ocorreu um erro ao salvar o registro.';
        $this->sErro = mysql_error();
    		$this->sResultado = 'erro';
        $bSucesso = false;

    	} else {
        $this->iCdMsg = 0;
        $this->sMsg  = 'O registro foi adicionado com sucesso!';
        $this->sResultado = 'sucesso';
        $bSucesso = true;
      }

      // Monta array com mensagem de retorno
      $this->aMsg = array('iCdMsg' => $this->iCdMsg,
                            'sMsg' => $this->sMsg,
                      'sResultado' => $this->sResultado );
      return $bSucesso;
    }

    public function remover($iId = '') {
      $sQuery = "DELETE FROM v_produtos
                       WHERE id = ".$iId;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        $this->iCdMsg = 1;
        $this->sMsg  = 'Ocorreu um erro ao remover o registro.';
        $this->sErro = mysql_error();
        $this->sResultado = 'erro';
        $bSucesso = false;

      } else {
        $this->iCdMsg = 0;
        $this->sMsg  = 'O registro foi removido com sucesso!';
        $this->sResultado = 'sucesso';
        $bSucesso = true;
      }

    // Monta array com mensagem de retorno
    $this->aMsg = array('iCdMsg' => $this->iCdMsg,
                          'sMsg' => $this->sMsg,
                    'sResultado' => $this->sResultado );
    return $bSucesso;
  }

    public function editar($iId = '') {
      $sQuery = "UPDATE v_produtos
        SET
          nm_produto            = '".$this->NM_PRODUTO[0]."', 
          cd_produto            = '".$this->CD_PRODUTO[0]."', 
          de_curta              = '".$this->DE_CURTA[0]."', 
          de_longa              = '".$this->DE_LONGA[0]."', 
          cd_status             = '".$this->CD_STATUS[0]."', 
          nu_cliques            = '".$this->NU_CLIQUES[0]."', 
          nm_pronuncia          = '".$this->NM_PRONUNCIA[0]."', 
          id_tipo               = '".$this->ID_TIPO[0]."', 
          id_fabricante         = '".$this->ID_FABRICANTE[0]."', 
          id_categoria          = '".$this->ID_CATEGORIA[0]."', 
          tx_link               = '".$this->TX_LINK[0]."', 
          nu_x                  = '".$this->NU_X[0]."', 
          nu_y                  = '".$this->NU_Y[0]."', 
          nu_z                  = '".$this->NU_Z[0]."', 
          nu_peso               = '".$this->NU_PESO[0]."', 
          nu_atual              = '".$this->NU_ATUAL[0]."', 
          nu_minimo             = '".$this->NU_MINIMO[0]."', 
          tx_falta_prod         = '".$this->TX_FALTA_PROD[0]."', 
          cd_visivel_em_falta   = '".$this->CD_VISIVEL_EM_FALTA[0]."', 
          vl_adicionais         = '".$this->VL_ADICIONAIS[0]."', 
          vl_taxas              = '".$this->VL_TAXAS[0]."', 
          vl_custo              = '".$this->VL_CUSTO[0]."', 
          pc_margem             = '".$this->PC_MARGEM[0]."', 
          vl_final              = '".$this->VL_FINAL[0]."', 
          cd_visivel            = '".$this->CD_VISIVEL[0]."', 
          cd_status_categoria   = '".$this->CD_STATUS_CATEGORIA[0]."', 
          id_cat_agrupado       = '".$this->ID_CAT_AGRUPADO[0]."', 
          nm_categoria_agrupado = '".$this->NM_CATEGORIA_AGRUPADO[0]."', 
          id_tag_agrupado       = '".$this->ID_TAG_AGRUPADO[0]."', 
          nm_tag_agrupado       = '".$this->NM_TAG_AGRUPADO[0]."', 
          id_imagem_agrupado    = '".$this->ID_IMAGEM_AGRUPADO[0]."', 
          nm_imagem_agrupado    = '".$this->NM_IMAGEM_AGRUPADO[0]."', 
          nm_imagem_principal   = '".$this->NM_IMAGEM_PRINCIPAL[0]."' 
          WHERE id = ".$iId;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        $this->iCdMsg = 1;
        $this->sMsg  = 'Ocorreu um erro ao salvar o registro.';
        $this->sErro = mysql_error();
    		$this->sResultado = 'erro';
        $bSucesso = false;

    	} else {
        $this->iCdMsg = 0;
        $this->sMsg  = 'O registro foi editado com sucesso!';
        $this->sResultado = 'sucesso';
        $bSucesso = true;
      }
    // Monta array com mensagem de retorno
    $this->aMsg = array('iCdMsg' => $this->iCdMsg,
                          'sMsg' => $this->sMsg,
                    'sResultado' => $this->sResultado );
    return $bSucesso;
     
    }

    public function inicializaAtributos() {

      $this->ID[0]                    = (isset ($_POST['CMPprodutos-id'])                    ? $_POST['CMPprodutos-id']                    : '');
      $this->NM_PRODUTO[0]            = (isset ($_POST['CMPprodutos-produto'])            ? $_POST['CMPprodutos-produto']            : '');
      $this->CD_PRODUTO[0]            = (isset ($_POST['CMPprodutos-produto'])            ? $_POST['CMPprodutos-produto']            : '');
      $this->DE_CURTA[0]              = (isset ($_POST['CMPprodutos-curta'])              ? $_POST['CMPprodutos-curta']              : '');
      $this->DE_LONGA[0]              = (isset ($_POST['CMPprodutos-longa'])              ? $_POST['CMPprodutos-longa']              : '');
      $this->CD_STATUS[0]             = (isset ($_POST['CMPprodutos-status'])             ? $_POST['CMPprodutos-status']             : '');
      $this->NU_CLIQUES[0]            = (isset ($_POST['CMPprodutos-cliques'])            ? $_POST['CMPprodutos-cliques']            : '');
      $this->NM_PRONUNCIA[0]          = (isset ($_POST['CMPprodutos-pronuncia'])          ? $_POST['CMPprodutos-pronuncia']          : '');
      $this->ID_TIPO[0]               = (isset ($_POST['CMPprodutos-tipo'])               ? $_POST['CMPprodutos-tipo']               : '');
      $this->ID_FABRICANTE[0]         = (isset ($_POST['CMPprodutos-fabricante'])         ? $_POST['CMPprodutos-fabricante']         : '');
      $this->ID_CATEGORIA[0]          = (isset ($_POST['CMPprodutos-categoria'])          ? $_POST['CMPprodutos-categoria']          : '');
      $this->TX_LINK[0]               = (isset ($_POST['CMPprodutos-link'])               ? $_POST['CMPprodutos-link']               : '');
      $this->NU_X[0]                  = (isset ($_POST['CMPprodutos-x'])                  ? $_POST['CMPprodutos-x']                  : '');
      $this->NU_Y[0]                  = (isset ($_POST['CMPprodutos-y'])                  ? $_POST['CMPprodutos-y']                  : '');
      $this->NU_Z[0]                  = (isset ($_POST['CMPprodutos-z'])                  ? $_POST['CMPprodutos-z']                  : '');
      $this->NU_PESO[0]               = (isset ($_POST['CMPprodutos-peso'])               ? $_POST['CMPprodutos-peso']               : '');
      $this->NU_ATUAL[0]              = (isset ($_POST['CMPprodutos-atual'])              ? $_POST['CMPprodutos-atual']              : '');
      $this->NU_MINIMO[0]             = (isset ($_POST['CMPprodutos-minimo'])             ? $_POST['CMPprodutos-minimo']             : '');
      $this->TX_FALTA_PROD[0]         = (isset ($_POST['CMPprodutos-falta-prod'])         ? $_POST['CMPprodutos-falta-prod']         : '');
      $this->CD_VISIVEL_EM_FALTA[0]   = (isset ($_POST['CMPprodutos-visivel-em-falta'])   ? $_POST['CMPprodutos-visivel-em-falta']   : '');
      $this->VL_ADICIONAIS[0]         = (isset ($_POST['CMPprodutos-adicionais'])         ? $_POST['CMPprodutos-adicionais']         : '');
      $this->VL_TAXAS[0]              = (isset ($_POST['CMPprodutos-taxas'])              ? $_POST['CMPprodutos-taxas']              : '');
      $this->VL_CUSTO[0]              = (isset ($_POST['CMPprodutos-custo'])              ? $_POST['CMPprodutos-custo']              : '');
      $this->PC_MARGEM[0]             = (isset ($_POST['CMPprodutos-margem'])             ? $_POST['CMPprodutos-margem']             : '');
      $this->VL_FINAL[0]              = (isset ($_POST['CMPprodutos-final'])              ? $_POST['CMPprodutos-final']              : '');
      $this->CD_VISIVEL[0]            = (isset ($_POST['CMPprodutos-visivel'])            ? $_POST['CMPprodutos-visivel']            : '');
      $this->CD_STATUS_CATEGORIA[0]   = (isset ($_POST['CMPprodutos-status-categoria'])   ? $_POST['CMPprodutos-status-categoria']   : '');
      $this->ID_CAT_AGRUPADO[0]       = (isset ($_POST['CMPprodutos-cat-agrupado'])       ? $_POST['CMPprodutos-cat-agrupado']       : '');
      $this->NM_CATEGORIA_AGRUPADO[0] = (isset ($_POST['CMPprodutos-categoria-agrupado']) ? $_POST['CMPprodutos-categoria-agrupado'] : '');
      $this->ID_TAG_AGRUPADO[0]       = (isset ($_POST['CMPprodutos-tag-agrupado'])       ? $_POST['CMPprodutos-tag-agrupado']       : '');
      $this->NM_TAG_AGRUPADO[0]       = (isset ($_POST['CMPprodutos-tag-agrupado'])       ? $_POST['CMPprodutos-tag-agrupado']       : '');
      $this->ID_IMAGEM_AGRUPADO[0]    = (isset ($_POST['CMPprodutos-imagem-agrupado'])    ? $_POST['CMPprodutos-imagem-agrupado']    : '');
      $this->NM_IMAGEM_AGRUPADO[0]    = (isset ($_POST['CMPprodutos-imagem-agrupado'])    ? $_POST['CMPprodutos-imagem-agrupado']    : '');
      $this->NM_IMAGEM_PRINCIPAL[0]   = (isset ($_POST['CMPprodutos-imagem-principal'])   ? $_POST['CMPprodutos-imagem-principal']   : '');
      
    }
  }