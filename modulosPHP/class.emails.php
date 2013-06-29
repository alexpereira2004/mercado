<?php
/**
 * Centraliza��o de fun��es de envio de emails
 *
 * @author Alex Lunardelli
 */
include_once 'class.wTools.php';
include_once 'PHPMailer_5.2.1/class.phpmailer.php';

class emails extends PHPMailer{
  private $oUtil;
  private $sDestinatario;
  private $aDestinatariosContatoCC;
  
  public function __construct() {
    $this->oUtil       = new wTools();
  }
  
   /* emails::buscarDestinatarios
   *
   * Busca os endere�os que receber�o os emails.
   * Pode retornar emails do mercado dos sabores (incoming)
   * Pode retornar emails de clientes (sending)
   * 
   * @date 21/03/2013
   * @param mixed $mEmail - Bool true caso os dados de destinat�rio sejam do Mercado do sabores
    *                       Array com emails caso os destinat�rio seja um cliente
   * @return
   */
  private function buscarDestinatarios($mEmail) {

    if (is_bool($mEmail)) {     
      $this->oUtil->buscarParametro(array('DESTINATARIOS'));
      $aEmails = $this->oUtil->aParametros['DESTINATARIOS'];
    } elseif (is_array($mEmail)) {
      $aEmails = $mEmail;
    } else {
      $aEmails = array($mEmail);
    }

    $this->sDestinatario = array_shift($aEmails);

    $this->aDestinatariosContatoCC = array();
    if (is_array($aEmails)) {
      $this->aDestinatariosContatoCC = $aEmails;
    }
  }

   /* emails::prapararEmail
   *
   * Ap�s preparado o HTML da mensagem, este m�todo faz a transa��o e envia os emails
   * 
   * @date 21/03/2013
   * @param string $sMsgHtml - Mensagem HTML montada
   * @param mixed  $mEmail   - Bool true caso os dados de destinat�rio sejam do Mercado do sabores
    *                          Array com emails caso os destinat�rio seja um cliente
   * @return
   */
  public function prapararEmail($sMsgHtml, $mEmail) {
    $oDadosAdm = new usuario_admin();
    
    $this->buscarDestinatarios($mEmail);

    $this->oUtil->buscarParametro(array('HOST', 'PASS_MAIL', 'REMETENTE'));

    $this->IsSMTP();
    $this->IsHTML();
    $this->Host     = $this->oUtil->aParametros['HOST'][0];
    $this->Password = base64_encode($oDadosAdm->SCAPE.$this->oUtil->aParametros['PASS_MAIL'][0]);
    $this->SetFrom($this->oUtil->aParametros['REMETENTE'][0], 'Mercado Dos Sabores');
    $this->AddAddress($this->sDestinatario);

    foreach ($this->aDestinatariosContatoCC as $sDestinatarioCC) {
      $this->AddBCC($sDestinatarioCC);
    }

    //$this->Subject = 'Contato enviado pelo site Mercado dos Sabores';
    $this->Body = $sMsgHtml;

    if (!$this->Send()) {
      //@TODO - Salvar LOG falha de envio de email
      echo $this->ErrorInfo;
    }

    
  }

    public function criacaoConta($sEmail, $oCliente) {
      include 'config.php';
      $this->Subject = 'Nova conta criada no Mercado dos Sabores';
      ob_start(); ?>
        <table style="width: 90%">
          <tr>
            <td><img src="<?php echo $this->oUtil->sUrlBase; ?>/comum/imagens/site/Mercado-dos-Sabores-100-51.jpg" alt="<?php echo $CFGsNomeSite; ?>"/> </td>
          </tr>
          <tr>
            <td><hr /></td>
          </tr>
          <tr>
            <td>
              <h2>Ol� <?php echo $oCliente->NM_CLIENTE[0].' '.$oCliente->NM_SOBRENOME[0] ?>,</h2>

              Obrigado por criar sua conta no <?php echo $CFGsNomeSite; ?>.<br />

              Atrav�s deste cadastro voc� poder� fazer compras e acompanhar seus pedidos

              atrav�s do painel de administra��o, que voc� pode acessar 

              atrav�s do seguinte link: 

              <a href="<?php echo $this->oUtil->sUrlBase;?>/conta/meus-dados/">Meus dados</a><br /><br />

              <b>Detalhes de acesso a conta:</b><br />

              Endere�o de e-mail: <?php echo $oCliente->TX_EMAIL[0]; ?> <br />
              Senha: <?php echo $oCliente->TX_SENHA[0]; ?> <br /><br />

              Agora voc� j� pode fazer compras em nosso site: <a href="<?php echo $this->oUtil->sUrlBase;?>"><?php echo $CFGsNomeSite; ?></a><br />

              Dicas de Seguran�a:<br />

              * Mantenha as informa��es da sua conta em seguran�a.<br />
              * N�o revele a suas informa��es a ningu�m.<br />
              * Recomendamos que mude sua senha regularmente.<br />
              * Se voc� suspeitar que algu�m est� tentando ou usando ilegalmente <br />
                sua conta, por favor, informe-nos imediatamente.
                <a href="<?php echo $this->oUtil->sUrlBase;?>/contato/"><?php echo $this->oUtil->sUrlBase;?>/contato/</a><br />

            </td>
          </tr>
        </table>
    <?php
    $sMsgHtml = ob_get_clean();
    $this->prapararEmail($sMsgHtml, $sEmail);
  }
  
  public function confirmacaoPagamento() {
    $this->Subject = 'Recebemos a confirma��o de pagamento da sua compra no Mercado dos Sabores';
    
  }
  
  public function compraCancelada() {
    $this->Subject = 'Compra cancelada no Mercado dos Sabores';
    
  }
  
  public function tokenTrocarSenha() {
    $this->Subject = 'Altera��o de senha do site Mercado dos Sabores';
    $sMsgHtml = 'Trocar a senha garoto!';
    $this->prapararEmail($sMsgHtml);
  }
  
  
  
}
?>
