<?php

  //if (!isset($sPgAtual)) { die('No direct script access allowed'); }
  include_once 'adapter.usuarioAdmin.php';

  $mResultado = '';
  $sMsg       = '';
  $sMsgErro   = '';
  
  $CFGtxObrigatorio = '<span class="campo-obrigatorio"> *campo obrigat�rio</span>';

  $CFGaPgAtual = array (
      
      
      
       'listagem-produtos-promocoes' => array('titulo'   => 'Mercado dos Sabores - As melhores promo��es selecionadas para voc�',
                                              'backPage' => 'listar_promocoes.php' ),
        'listagem-produtos-det'      => array('titulo'   => 'Mercado dos Sabores',
                                              'backPage' => 'index.php' ),
        'listagem-categorias-det'    => array('titulo'   => 'Mercado dos Sabores',
                                              'backPage' => 'categorias-detalhe' ),
        'listagem-tags-det'          => array('titulo'   => 'Mercado dos Sabores',
                                              'backPage' => 'tags-detealhe' ),
        'listagem-fabricantes-det'   => array('titulo'   => 'Mercado dos Sabores',
                                              'backPage' => 'fabricantes-detalhe/' ),
                   'usuario-dados'   => array('titulo'   => 'Mercado dos Sabores - Dados',
                                              'backPage' => 'login/'),
                 'usuario-pedidos'   => array('titulo'   => 'Mercado dos Sabores - Pedidos',
                                              'backPage' => 'login/'),
                   'usuario-login'   => array('titulo'   => 'Mercado dos Sabores - Login',
                                              'backPage' => 'login/'),
                'usuario-cadastro'   => array('titulo'   => 'Mercado dos Sabores - Cadastro',
                                              'backPage' => 'index.php'),
                        'checkout'   => array('titulo'   => 'Mercado dos Sabores - Checkout',
                                              'backPage' => 'index.php'),
      
                        'index'      => array('titulo'   => 'Mercado dos Sabores',
                                              'backPage' => 'index.php' ),
                      'contato'      => array('titulo'   => 'Mercado dos Sabores - Fale Conosco',
                                              'backPage' => 'index.php' ),
      
                        'teste'      => array('titulo'   => 'teste',
                                              'backPage' => 'index.php' ),

                          'adm'      => array('titulo'   => 'Painel de Administra��o',
                                              'backPage' => 'index.php' ),
                       'fabricantes' => array('titulo'   => 'Fabricantes',
                                              'backPage' => 'fabricantes.php' ),
                           'vitrine' => array('titulo'   => 'Vitrine',
                                              'backPage' => 'vitrine.php' ),
                   'transportadoras' => array('titulo'   => 'Transportadoras',
                                              'backPage' => 'transportadoras.php' ),
                        'categorias' => array('titulo'   => 'Categorias',
                                              'backPage' => 'categorias.php' ),
                              'tags' => array('titulo'   => 'Tags',
                                              'backPage' => 'tags.php' ),
                         
                        'novapagina' => array('titulo'   => 'Nova P�gina HTML',
                                              'backPage' => 'novapagina.php' ),

                          'clientes' => array('titulo'   => 'Clientes',
                                              'backPage' => 'clientes.php' ),
                          'produtos' => array('titulo'   => 'Produtos',
                                              'backPage' => 'produtos.php' ),
                          'usuarios' => array('titulo'   => 'Usu�rios',
                                              'backPage' => 'usuarios.php' ),
                     'configuracoes' => array('titulo'   => 'Configura��es do sistema',
                                              'backPage' => 'configuracoes.php' ),
                      'preferencias' => array('titulo'   => 'Prefer�ncias',
                                              'backPage' => 'preferencias.php' ),
                'extrato-financeiro' => array('titulo'   => 'Extrato Financeiro',
                                              'backPage' => 'extrato-financeiro.php' ),
                'extrato-transacoes' => array('titulo'   => 'Extrato Transa��es',
                                              'backPage' => 'extrato-transacoes.php' ),
                             'taxas' => array('titulo'   => 'Taxas',
                                              'backPage' => 'taxas.php' ),
                         'descontos' => array('titulo'   => 'Descontos',
                                              'backPage' => 'descontos.php' ),
                         'descontos' => array('titulo'   => 'Descontos',
                                              'backPage' => 'descontos.php' ),
                         'promocoes' => array('titulo'   => 'Promo��es',
                                              'backPage' => 'promocoes.php' ),
                        'liberacoes' => array('titulo'   => 'Libera��es',
                                              'backPage' => 'liberacoes.php' ),
                 'finalizar-compras' => array('titulo'   => 'Finalizar Compras',
                                              'backPage' => 'finalizar-compras.php' ),
               'pedidos-finalizados' => array('titulo'   => 'Pedidos Finalizados',
                                              'backPage' => 'pedidos-finalizados.php' ),
                'pedidos-efetivados' => array('titulo'   => 'Pedidos Efetivados',
                                              'backPage' => 'pedidos-efetivados.php' ),
                 'pedidos-pendentes' => array('titulo'   => 'Pedidos Pendentes',
                                              'backPage' => 'pedidos-pendentes.php' ),
                'pedidos-cancelados' => array('titulo'   => 'Pedidos Cancelados',
                                              'backPage' => 'pedidos-cancelados.php' ),

                             'login' => array('titulo'   => 'Acesso � administra��o',
                                              'backPage' => 'login.php' ),



                        );
  $CFGaNiveisUsuarios = array (1  => 'Master',
                               5  => 'Administrador',
                               10 => 'Usu�rio',
  );

  // Subse��es das p�ginas criadas via HTML GERAL
  $CFGaSecoesHtml = array ('conteudo'   => 'conteudo',
                           'divulgacao' => 'divulgacao');


  $CFGsPastaImagensProdutos = '/Mercadodossabores/comum/imagens/produtos'; // Relative to the root

  // Quantidade de imagem por produto
  $CFGiQntImgProduto = 20;

  $CFGaConfigUpload = array('pasta'      => '../comum/img/anunciantes/logotipo/logo_',
                             'tamanho'    => 1048576,
                             'novonome'   => '',
                             'extensoes'  => array('jpg', 'png', 'gif', 'jpeg'),
                             'renomeia'   => false,
                             'altura'     => 500,
                             'largura'    => 400,
                             'errors'     => array( 0 => 'N�o houve erro',
                                                    1 => 'O arquivo no upload � maior do que o limite do PHP',
                                                    2 => 'O arquivo ultrapassa o limite de tamanho especifiado no HTML',
                                                    3 => 'O upload do arquivo foi feito parcialmente',
                                                    4 => 'N�o foi feito o upload do arquivo') );

  $CFGaImagensStatusParam = array('A' => 'link.png',
                                  'I' => 'link_break.png' );
  
  $CFGsCdEmpresa = usuario_admin::getEmpresa();
  
  $CFGaSituacao = array ('A' => 'Ativo',
                         'I' => 'Inativo');
  
  $CFGaTiposContato = array('Esclarecer uma d�vida' => 'Esclarecer uma d�vida',
                            'Dar uma sugest�o'      => 'Dar uma sugest�o',
                            'Fazer uma reclama��o'  => 'Fazer uma reclama��o',
                            'Fazer um elogio'       => 'Fazer um elogio');
  
  $CFGaTipoImagens = array('PR' => 'Imagem principal',
                           'DP' => 'Imagem de demonstra��o');
  
  $CFGaSexo = array('M' => 'Masculino',
                    'F' => 'Feminino');
  
  $CFGsEmailPagSeguro = 'financeiro@mercadodossabores.com.br';
  
  $CFGsNomeSite = 'Mercado dos Sabores';
  
  $CFGiQntItensPorPagina = 12;
  
  $CFGaTiposValores = array('V' => 'Reais',
                            'P' => 'Percentual');
  
  $CFGaTiposDesconto = array ( 'T' => 'Valor Total da Compra',
                               'Q' => 'Quantidade de itens',
                               'U' => 'Desconto Por Unidade',
                               //'B' => 'Brinde'
      );
  
  $CFGaTiposValoresDesconto = array('V' => 'Reais',
                                    'P' => 'Percentual',
                                    'I' => 'Inteiro');
  
  $CFGaCodSitPedido = array ('AB' => 'Aberto',
                             'EP' => 'Enviado ao Pag Seguro',
                             'AP' => 'Aguardando Pagamento',
                             'AC' => 'Aguardando Confirma��o de Pagamento',
                             'DI' => 'Em disputa',
                             'PC' => 'Pagamento Confirmado',
                             'DE' => 'Pagamento devolvido e compra desfeita',
                             'EX' => 'Expedi��o',
                             'EV' => 'Enviado',
                             'FI' => 'Compra Finalizada',
                             'CA' => 'Compra Cancelada',
                             'CI' => 'Compra Cancelada por Inatividade'
      );
  
  /*
   * Grupos de situa��es. Usado em telas de pesquisa
   */
  $CFGaGrupoPedidosPendentes  = array('AB', 'EP', 'AP');
  $CFGaGrupoPedidosAguardando = array('AC', 'DI', 'EX');
  $CFGaGrupoPedidosEfetivados = array('PC', 'EV', 'FI');
  $CFGaGrupoPedidosCancelados = array('DE', 'CA', 'CI');
  
  $CFGaStatusPagSeguro = array( 1 => 'Aguardando pagamento: o comprador iniciou a transa��o, mas at� o momento o PagSeguro n�o recebeu nenhuma informa��o sobre o pagamento',
                                2 => 'Em an�lise: o comprador optou por pagar com um cart�o de cr�dito e o PagSeguro est� analisando o risco da transa��o',
                                3 => 'Paga: a transa��o foi paga pelo comprador e o PagSeguro j� recebeu uma confirma��o da institui��o financeira respons�vel pelo processamento',
                                4 => 'Dispon�vel: a transa��o foi paga e chegou ao final de seu prazo de libera��o sem ter sido retornada e sem que haja nenhuma disputa aberta',
                                5 => 'Em disputa: o comprador, dentro do prazo de libera��o da transa��o, abriu uma disputa',
                                6 => 'Devolvida: o valor da transa��o foi devolvido para o comprador',
                                7 => 'Cancelada: a transa��o foi cancelada sem ter sido finalizada');

  // De   : Situa��o do Pag Seguro
  // Para : Situa��o na tabela tc_carrinho
  $CFGaCruzamentoSituacoes = array( 1 => 'AP',
                                    2 => 'AC',
                                    3 => 'PC',
                                    4 => 'PC',
                                    5 => 'DI',
                                    6 => 'DE',
                                    7 => 'CA');


  $CFGaMotivosCancelamento = array( 'INTERNAL' =>	'PagSeguro',
                                    'EXTERNAL' => 'Institui��es Financeiras' );

  $CFGaTipoMetodoPagamento = array ( 1 => 'Cart�o de cr�dito: o comprador escolheu pagar a transa��o com cart�o de cr�dito',
                                     2 => 'Boleto: o comprador optou por pagar com um boleto banc�rio',
                                     3 => 'D�bito online (TEF): o comprador optou por pagar a transa��o com d�bito online de algum dos bancos conveniados',
                                     4 => 'Saldo PagSeguro: o comprador optou por pagar a transa��o utilizando o saldo de sua conta PagSeguro',
                                     5 => 'Oi Paggo: o comprador escolheu pagar sua transa��o atrav�s de seu celular Oi' );

  $CFGaCodigoMetodoPagamento = array( 101 => 'Cart�o de cr�dito Visa',
                                      102 => 'Cart�o de cr�dito MasterCard',
                                      103 => 'Cart�o de cr�dito American Express',
                                      104 => 'Cart�o de cr�dito Diners',
                                      105 => 'Cart�o de cr�dito Hipercard',
                                      106 => 'Cart�o de cr�dito Aura',
                                      107 => 'Cart�o de cr�dito Elo',
                                      108 => 'Cart�o de cr�dito PLENOCard',
                                      109 => 'Cart�o de cr�dito PersonalCard',
                                      110 => 'Cart�o de cr�dito JCB',
                                      111 => 'Cart�o de cr�dito Discover',
                                      112 => 'Cart�o de cr�dito BrasilCard',
                                      113 => 'Cart�o de cr�dito FORTBRASIL',
                                      114 => 'Cart�o de cr�dito CARDBAN',
                                      115 => 'Cart�o de cr�dito VALECARD',
                                      116 => 'Cart�o de cr�dito Cabal',
                                      117 => 'Cart�o de cr�dito Mais!',
                                      201 => 'Boleto Bradesco',
                                      202 => 'Boleto Santander',
                                      301 => 'D�bito online Bradesco',
                                      302 => 'D�bito online Ita�',
                                      303 => 'D�bito online Unibanco',
                                      304 => 'D�bito online Banco do Brasil',
                                      305 => 'D�bito online Banco Real',
                                      306 => 'D�bito online Banrisul',
                                      307 => 'D�bito online HSBC',
                                      401 => 'Saldo PagSeguro',
                                      501 => 'Oi Paggo',
    );
  
  $CFGaCardinalF = array( '1' => 'Uma', '2' => 'Duas', '3' => 'Tr�s', '4' => 'Quatro', '5' => 'Cinco', '6' => 'Seis', '7' => 'Sete', '8' => 'Oito', '9' => 'Nove', '10' => 'Dez', '11' => 'Onze', '12' => 'Doze', '13' => 'Treze', '14' => 'Quatorze', '15' => 'Quinze', '16' => 'Dezesseis', '17' => 'Dezessete', '18' => 'Dezoito', '19' => 'Dezenove', '20' => 'Vinte');
  $CFGaCardinalM = array( '1' => 'Um', '2' => 'Dois', '3' => 'Tr�s', '4' => 'Quatro', '5' => 'Cinco', '6' => 'Seis', '7' => 'Sete', '8' => 'Oito', '9' => 'Nove', '10' => 'Dez', '11' => 'Onze', '12' => 'Doze', '13' => 'Treze', '14' => 'Quatorze', '15' => 'Quinze', '16' => 'Dezesseis', '17' => 'Dezessete', '18' => 'Dezoito', '19' => 'Dezenove', '20' => 'Vinte');
  $CFGaOrdinal = array( '1' => 'primeiro',
                        '2' => 'segundo',
                        '3' => 'terceiro',
                        '4' => 'quarto',
                        '5' => 'quinto',
                        '6' => 'sexto',
                        '7' => 's�timo',
                        '8' => 'oitavo',
                        '9' => 'nono',
                       '10' => 'd�cimo',
                       '20' => 'vig�simo',
                       '30' => 'trig�simo',
                       '40' => 'quadrag�simo',
                       '50' => 'quinquag�simo',
                       '60' => 'sexag�simo',
                       '70' => 'septuag�simo',
                       '80' => 'octog�simo',
                       '90' => 'nonag�simo',
                      '100' => 'cent�simo',
                      '200' => 'ducent�simo',
                      '300' => 'trecent�simo',
                      '400' => 'quadrigent�simo',
                      '500' => 'quingent�simo',
                      '600' => 'sexcent�simo',
                      '700' => 'septigent�simo',
                      '800' => 'octigent�simo',
                      '900' => 'nongent�simo',
                     '1000' => 'mil�simo',
                    '10000' => 'milion�simo',
                    '20000' => 'bilion�simo' );

  $aMsg = array('iCdMsg' => '', 'sMsg' => '', 'sMsgErro' => '', 'sResultado' => '');

?>
