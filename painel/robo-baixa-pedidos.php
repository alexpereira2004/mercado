<?php
  /*
   * Busca os pedidos que estão muito tempo inativos e atualiza a situação deles 
   * para 'Compra Cancelada'
   */
  include '../modulosPHP/class.carrinho.php';
  $oPedidos = new carrinho();
  $oPedidos->baixarPedidoInatividade();


?>
