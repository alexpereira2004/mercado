
<?php
  /**
   * Descricao
   *
   * @package    Site Loja Virtual
   * @author     Alex Lunardelli <alex@lunacom.com.br>
   * @copyright  Lunacom marketing Digital
   * @date       24-05-2013
   **/

  class tc_vitrine_itens {
  
    public    $id;
    public    $id_prod;
    public    $nu_ordem;
    public    $nm_local;
    public    $cd_grupo;
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
        $aValidar = array ( 1 => array('Prod' , $_POST['CMPvitrine-itens-prod'], 'int(10)', true),
                            2 => array('Ordem' , $_POST['CMPvitrine-itens-ordem'], 'int(11)', true),
                            3 => array('Local' , $_POST['CMPvitrine-itens-local'], 'varchar(50)', true),
                            4 => array('Grupo' , $_POST['CMPvitrine-itens-grupo'], 'varchar(30)', true),
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
                        id_prod, 
                        nu_ordem, 
                        nm_local, 
                        cd_grupo 
                   FROM tc_vitrine_itens
                   '.$sFiltro;
      $sResultado = mysql_query($sQuery, $this->DB_LINK);

      if (!$sResultado) {
        die('Erro ao criar a listagem: ' . mysql_error());
        return false;
      }

      //$this->iLinhasTc_vitrine_itens = mysql_num_rows($sResultado);
      $this->iLinhas = mysql_num_rows($sResultado);
        
      while ($aResultado = mysql_fetch_array($sResultado)) { 
        $this->ID[]       = $aResultado['id']; 
        $this->ID_PROD[]  = $aResultado['id_prod']; 
        $this->NU_ORDEM[] = $aResultado['nu_ordem']; 
        $this->NM_LOCAL[] = $aResultado['nm_local']; 
        $this->CD_GRUPO[] = $aResultado['cd_grupo']; 
      }
    }

    public function inserir() {
      $sQuery = "INSERT INTO tc_vitrine_itens(
                             ID_PROD, 
                             NU_ORDEM, 
                             NM_LOCAL, 
                             CD_GRUPO 
)
      VALUES(
              '".$this->ID_PROD[0]."', 
              '".$this->NU_ORDEM[0]."', 
              '".$this->NM_LOCAL[0]."', 
              '".$this->CD_GRUPO[0]."' 
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

    public function remover($sFiltro = '') {
      $sQuery = "DELETE FROM tc_vitrine_itens ".$sFiltro;
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
      $sQuery = "UPDATE tc_vitrine_itens
        SET
          id_prod  = '".$this->ID_PROD[0]."', 
          nu_ordem = '".$this->NU_ORDEM[0]."', 
          nm_local = '".$this->NM_LOCAL[0]."', 
          cd_grupo = '".$this->CD_GRUPO[0]."' 
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

      $this->ID[0]       = (isset ($_POST['CMPvitrine-itens-id'])       ? $_POST['CMPvitrine-itens-id']       : '');
      $this->ID_PROD[0]  = (isset ($_POST['CMPvitrine-itens-prod'])  ? $_POST['CMPvitrine-itens-prod']  : '');
      $this->NU_ORDEM[0] = (isset ($_POST['CMPvitrine-itens-ordem']) ? $_POST['CMPvitrine-itens-ordem'] : '');
      $this->NM_LOCAL[0] = (isset ($_POST['CMPvitrine-itens-local']) ? $_POST['CMPvitrine-itens-local'] : '');
      $this->CD_GRUPO[0] = (isset ($_POST['CMPvitrine-itens-grupo']) ? $_POST['CMPvitrine-itens-grupo'] : '');
      
    }
  }