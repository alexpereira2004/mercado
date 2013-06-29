<?php
/**
 * IMPORTANTE: Para que isto funcione é necessário arquivo de JS contendo a função
 * ativaAba, jQuery e arquivo de CSS.
 *
 * @author Alex Lunardelli
 */
class abas {
  protected $sIdConteinerContAbas;
  protected $aConfigAbas = array();
  protected $sAbaSelecionada;

  public function __construct($sIdConteinerContAbas = '', $aConfigAbas = array(), $sAbaSelecionada = '') {
    if ($sIdConteinerContAbas != '' && is_array($aConfigAbas) && $sAbaSelecionada != '') {
      $this->abas_PrepararDados($sIdConteinerContAbas, $aConfigAbas, $sAbaSelecionada);
    }
  }
  /* wTools::abas_PrepararDados
   * Recebe os parâmetros necessários para criação de um sistema de abas
   *
   * @param string $sIdConteinerContAbas - Nome do id da DIV de conteiner do sistema de abas
   * @param array  $aConfigAbas          - Dentro desde array existe um array para cada "aba", ele contem:
                                           1) Nome apresentado no botão da aba
                                           2) Id da aba
                                           3) Variável que recebe o conteúdo da aba
                                            ex.: $aConfigAbas = array( 0 => array('Tarefas'
                                                                                  'id_da_aba'
                                                                                  $sConteudoDaAba )
                                                                      );
   * @param string $sAbaSelecionada      - Id da aba que deve vir selecionada
   * @date  13/05/2012
   * @param
   * @return true
   */
  public function abas_PrepararDados($sIdConteinerContAbas, $aConfigAbas, $sAbaSelecionada) {
    $this->sIdConteinerContAbas = $sIdConteinerContAbas;
    $this->aConfigAbas          = $aConfigAbas;
    $this->sAbaSelecionada      = $sAbaSelecionada;
  }

  /* wTools::montarBotoesAbas
   * Monta os botões que fazem a troca do conteudo da aba.
   * Esta preparado para exibir um controle de botões por lista de conteúdo.
   * Suporta mais de um sistema de abas na mesma página
   *
   * @date  13/05/2012
   * @param
   * @return true
   */
  public function abas_MontarBotoes() {
    $iQntAbas = count($this->aConfigAbas);
    ?>
    <div class="botoes_abas"> <?php
      foreach ($this->aConfigAbas as $iChave => $aDados) { ?>
        <input name="BtAbas" type="button" id="bt_aba_<?php echo $this->sIdConteinerContAbas; ?>_<?php echo $iChave; ?>" class="bt_aba <?php echo ($this->sAbaSelecionada == $aDados[1]) ? 'bt_aba_selecionada' : ''  ?>" value="<?php echo $aDados[0]; ?>" onclick="return ativaAba('<?php echo $this->sIdConteinerContAbas; ?>', '<?php echo $aDados[1]; ?>')" />
      <?php
      }
    ?>
    </div>
    <?php
  }

  /* wTools::abas_MontarConteiner
   * Montará os dados do sistema de abas. Não é necessário ser usado este método, dependendo da
   * situação poderá ser mais fácil que ele seja montado direto na página.
   *
   * @date  13/05/2012
   * @param
   * @return true
   */
  public function abas_MontarConteiner() { ?>
    <div id="<?php echo $this->sIdConteinerContAbas; ?>" >
      <?php
        foreach ($this->aConfigAbas as $aDados) { ?>
          <div id="<?php echo $aDados[1]; ?>" class="<?php echo ($aDados[1] == $this->sAbaSelecionada) ? 'abas_mostrar' : 'abas_esconder' ?>">
            <?php echo $aDados[2]; ?>
          </div>
          <?php
        }
      ?>
    </div>
    <div class="limpa">&nbsp;</div>
    <?php
  }
}
?>
