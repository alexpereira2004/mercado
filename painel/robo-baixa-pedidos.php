<?php
  /*
   * Busca os pedidos que est�o muito tempo inativos e atualiza a situa��o deles 
   * para 'Compra Cancelada'
   */
  include '../modulosPHP/class.carrinho.php';
  $oPedidos = new carrinho();
  $oPedidos->baixarPedidoInatividade();


?>
