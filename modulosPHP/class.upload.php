<?php
/*    Criado por Lunacom Marketing Digital
 *
 *
 *

  Array de configura��o para o tratamento do upload
  $CFGaConfigUpload = array('pasta'      => '../comum/img/anunciantes/logotipo/logo_',
                           'tamanho'    => 1048576,
                           'extensoes'  => array('jpg', 'png', 'gif'),
                           'renomeia'   => false,
                           'errors'     => array( 0 => 'N�o houve erro',
                                                  1 => 'O arquivo no upload � maior do que o limite do PHP',
                                                  2 => 'O arquivo ultrapassa o limite de tamanho especifiado no HTML',
                                                  3 => 'O upload do arquivo foi feito parcialmente',
                                                  4 => 'N�o foi feito o upload do arquivo') );

  Estrutura de array de configura��o do formul�rio
  $oUpload->aConfig = array('sAction'  => 'modulosPHP/ajaxTrataUpload.php',  --- Arquivo respons�vel por tratar o upload
                            'sEstampa' => 'Imagem do logotipo',              --- Campo de texto que o usu�rio l� e identifica qual tipo de arquivo � recebido
                            'sAcao'    => 'salvarLogo',                      --- Usado para diferenciar formularios diversos dentro do arquivo de tratamento do upload
                            'sNome'    => 'CMPlogo' );                       --- Nome e id do campo na tabela apresentada na tela




 *
 *
 *
 *
 *
 *
 */
  include_once 'class.wTools.php';

  class upload {
    public $iCdMsg;
    public $sMsg;
    public $sResultado;
    public $aConfig = array();

    function __construct() {
      $this->oUtil = new wTools();
    }

    /* upload::formEnvio
     *
     * Escreve o formul�rio que o usu�rio utiliza para fazer o upload do arquivo
     * @date 23/08/2011
     * @param  file    $_FILES          - Array de dados recebido de um campo do formul�rio. Ex.: $_FILES['CMPlogotipo']
     * @param  array $aInputAdicional - Possibilidade de adicionar ao formul�rio novos campos 
     *                                  espec�ficos para entrada de dados.
     *                                  O tipo de campo "select" � aceito
     *                                  Exemplo de array para um campo extra do tipo select:
     *   $aInputAdicional = array( array( 'type' => 'select',
                                  'value' => 'teste',
                                   'name' => 'CMPcd_tipo',
                                  'label' => 'Tipo da imagem',
                 
                       // Valores adicionais para select
                           'aDadosSelect' => $aValores,
                     'aDadosSelectPadrao' => 'PR' )
                          );
     * 
     * 
     * @return true
     *
     */
    public function formEnvio($iId, $bBtPadrao = false, $sParamListagem ='', $sCampoDescricao = '', $sClasse = 'FRMtrataImagens', $aInputAdicional = '') {
      ?>
      <form action="<?php echo $this->aConfig['sAction']; ?>" id="<?php echo $this->aConfig['sIdForm']; ?>" <?php echo ($sClasse != '' ? 'class = "'.$sClasse.'"' : '')?> method="post" enctype="multipart/form-data" style="font-family: Helvetica, sans-serif;">
        <table>
          <tr>
            <td class="infoheader"><?php echo $this->aConfig['sEstampa'];?></td>
            <td>
              <input type="hidden" name="sAcao" value="<?php echo $this->aConfig['sAcao'];?>" />
              <input type="hidden" name="CMPid" value="<?php echo $iId;?>" />
              <input type="file" class="campo_f" name="<?php echo $this->aConfig['sNome'];?>" value="Buscar" title="Buscar"/>
            </td>
          </tr>
          <?php
            if($sCampoDescricao != '') { ?>
              <tr>
                <td class="infoheader"><?php echo $sCampoDescricao; ?></td>
                <td><input class="w08" type="text" name="CMPdescricao" value="" /></td>
              </tr>

              <?php
            }
            
            if (is_array($aInputAdicional)) {

              foreach ($aInputAdicional as $aDados) { 
                
                if ($aDados['type'] == 'select') { ?>
                  <tr>
                    <td class="infoheader"><?php echo $aDados['label']; ?></td>
                    <td><?php $this->oUtil->montaSelect($aDados['name'], $aDados['aDadosSelect'], $aDados['aDadosSelectPadrao']); ?></td>
                  </tr>
                <?php
                  continue;
                } ?>

              <tr>
                <td class="infoheader"><?php echo $aDados['label']; ?></td>
                <td><input class="w08" type="<?php echo $aDados['type']; ?>" name="<?php echo $aDados['name']; ?>" value="<?php echo $aDados['value']; ?>" /></td>
              </tr>
              <?php
              }
            }
          ?>
          <tr>
            <td>&nbsp;</td>
            <td>
              <?php
                if($bBtPadrao) { ?>
                  <input type="submit" value="Enviar Imagem" class="bt_salvar" />
                  <?php
                } else { ?>
                  <img id="submit_<?php echo $this->aConfig['sIdForm']; ?>" class="bt_link" src="../comum/img/estrutura/icon_add01.png" alt="Adicionar Arquivo" />
                  <?php
                }
              ?>
            </td>
          </tr>

            
        </table>
      </form>

      
    <?php

    }

    /* upload::uploadArquivos
     *
     * Upload de arquivos
     * @date 23/08/2011
     * @param  file    $_FILES          - Array de dados recebido de um campo do formul�rio. Ex.: $_FILES['CMPlogotipo']
     * @param  array   $$aConfigUpload  - Dados de configura��es de mensagens, formato dos arquivos, tamanho, pasta a ser salva etc.
     * @param  integer $iTamMax         - Tamanho m�ximo do arquivo em MB
     * @param  array   $aExtensoes      - Contem as extensoes de arquivos aceitos para upload
     * @param  bool    $bRenomear       - Renomear arquivo com nome aleat�rio
     * @return true
     *
     */
    public function uploadArquivos($_FILES, $aConfigUpload){

      // Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
      if ($_FILES['error'] != 0) {
        die("N�o foi poss�vel fazer o upload, erro:<br />" . $aConfigUpload['erros'][$_FILES['arquivo']['error']]);
        exit; // Para a execu��o do script
      }

      

      // Caso script chegue a esse ponto, n�o houve erro com o upload e o PHP pode continuar

      // Faz a verifica��o da extens�o do arquivo
      $sExtensao = strtolower(end(explode('.', $_FILES['name'])));
      if (array_search($sExtensao, $aConfigUpload['extensoes']) === false) {
        echo "Por favor, envie arquivos com as seguintes extens�es: ";
      }

      // Faz a verifica��o do tamanho do arquivo
      else if ($aConfigUpload['tamanho'] < $_FILES['size']) {
        echo "O arquivo enviado � muito grande, envie arquivos de at� $iTamMax.";

      // O arquivo passou em todas as verifica��es, hora de tentar mov�-lo para a pasta
      } else {

        // Primeiro verifica se deve trocar o nome do arquivo
        if ($aConfigUpload['renomeia'] == true) {
        // Cria um nome baseado no UNIX TIMESTAMP atual e com extens�o .jpg
        $nome_final = time().'.jpg';
        } else {

        // Mant�m o nome original do arquivo
        $nome_final = str_replace(' ', '', $_FILES['name']);
        }

        // Depois verifica se � poss�vel mover o arquivo para a pasta escolhida
        if (move_uploaded_file($_FILES['tmp_name'], $aConfigUpload['pasta'] . $nome_final)) {
          // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo
          $sRet  = "Upload efetuado com sucesso!";
          $sRet .= '<br /><a href="' . $aConfigUpload['pasta'] . $nome_final . '">Clique aqui para acessar o arquivo</a>';
          $bRet = true;
        } else {
          // N�o foi poss�vel fazer o upload, provavelmente a pasta est� incorreta
          $sRet = "N�o foi poss�vel enviar o arquivo, tente novamente";
          $bRet = false;
        }
        return $bRet;
      }
    }

    /* upload::enviaImg
     *
     * Upload de imagens
     * @date 03/09/2011
     * @param  file    $_FILES          - Array de dados recebido de um campo do formul�rio. Ex.: $_FILES['CMPlogotipo']
     * @param  array   $$aConfigUpload  - Dados de configura��es de mensagens, formato dos arquivos, tamanho, pasta a ser salva etc.     
     * @return true
     *
     */
    public function enviarImagem($oArquivo, $aConfigUpload){

    $sNomeUpload = '';

    if($oArquivo["name"] == ''){      
      //Mensagem de aviso
      $this->iCdMsg     = 2;
      $this->sMsg       = "Por favor, selecione uma figura";
      $this->sResultado = 'erro';
      return false;
    }

        /*((($oArquivo["type"] == "image/gif")
      || ($oArquivo["type"] == "image/jpeg")
      || ($oArquivo["type"] == "image/pjpeg")) */
      if ($oArquivo["size"] < $aConfigUpload['tamanho']) {
          if ($oArquivo["error"] == 0) {
            $aDimensoes = getimagesize($oArquivo["tmp_name"]);
                 
            if( ($aDimensoes[0] <= $aConfigUpload['largura'] && ($aDimensoes[1] <= $aConfigUpload['altura'])) ) {

              // Pega extens�o do arquivo
              $sExtensao = strtolower(end(explode('.', $oArquivo['name'])));
              if (array_search($sExtensao, $aConfigUpload['extensoes']) === false) {
                $this->iCdMsg     = 1;
                $this->sMsg       = "Por favor, envie arquivos com as seguintes extens�es: ".  implode(',', $aConfigUpload['extensoes']);
                $this->sResultado = 'erro';
                return false;
              }

              // Gera um nome para a imagem
              if ($aConfigUpload['renomeia'] == true) {
                $sNomeUpload = md5(uniqid(time())) . "." .$sExtensao;
              } else {
                $sNomeUpload = $aConfigUpload['novonome'].'.'.$sExtensao;
              }              

              // Caminho de onde a imagem ficar�
              $imagem_dir = $aConfigUpload['pasta'] . $sNomeUpload;

              // Faz o upload da imagem
              move_uploaded_file($oArquivo["tmp_name"], $imagem_dir);

              //Mensagem de sucesso
              $this->iCdMsg     = 0;
              $this->sMsg       = "Sua foto foi enviada com sucesso!";
              $this->sResultado = 'sucesso';
              return $sNomeUpload;

            }else{
              //Mensagem de erro              
              $this->iCdMsg     = 1;
              $this->sMsg       = 'Insira uma imagem de no m�ximo '.$aConfigUpload['largura'].' x '.$aConfigUpload['altura'].' pixels';
              $this->sResultado = 'erro';
              return false;
            }
          } else {
            //Mensagem de erro
            $this->iCdMsg     = 2;
            $this->sMsg       = 'Erro no carregamento da imagem:'.$aConfigUpload[$oArquivo["error"]];
            $this->sResultado = 'erro';
            return false;
            
          }
      } else {
          //Mensagem de erro          
          $this->iCdMsg     = 1;
          $this->sMsg       = 'O tamanho da imagem ('.round(($oArquivo["size"] / 1024),2).'KB) � maior que o permitido ('.round(($aConfigUpload['tamanho'] / 1024), 2).'KB).';
          $this->sResultado = 'erro';
          return false;
      }

    }

    function remover($arquivo,$local){

      #Testa para ver se existe arquivo
      if($arquivo == ''){
        return;
      }
      $deletar = $local.$arquivo;
      unlink($deletar);
    }

  }


?>
