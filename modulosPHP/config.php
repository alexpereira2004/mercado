<?php

  //if (!isset($sPgAtual)) { die('No direct script access allowed'); }
  include_once 'adapter.usuarioAdmin.php';

  $mResultado = '';
  $sMsg       = '';
  $sMsgErro   = '';
  
  $CFGtxObrigatorio = '<span class="campo-obrigatorio"> *campo obrigatório</span>';

  $CFGaPgAtual = array (
      
      
      
       'listagem-produtos-promocoes' => array('titulo'   => 'Mercado dos Sabores - As melhores promoções selecionadas para você',
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

                          'adm'      => array('titulo'   => 'Painel de Administração',
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
                         
                        'novapagina' => array('titulo'   => 'Nova Página HTML',
                                              'backPage' => 'novapagina.php' ),

                          'clientes' => array('titulo'   => 'Clientes',
                                              'backPage' => 'clientes.php' ),
                          'produtos' => array('titulo'   => 'Produtos',
                                              'backPage' => 'produtos.php' ),
                          'usuarios' => array('titulo'   => 'Usuários',
                                              'backPage' => 'usuarios.php' ),
                     'configuracoes' => array('titulo'   => 'Configurações do sistema',
                                              'backPage' => 'configuracoes.php' ),
                      'preferencias' => array('titulo'   => 'Preferências',
                                              'backPage' => 'preferencias.php' ),
                'extrato-financeiro' => array('titulo'   => 'Extrato Financeiro',
                                              'backPage' => 'extrato-financeiro.php' ),
                'extrato-transacoes' => array('titulo'   => 'Extrato Transações',
                                              'backPage' => 'extrato-transacoes.php' ),
                             'taxas' => array('titulo'   => 'Taxas',
                                              'backPage' => 'taxas.php' ),
                         'descontos' => array('titulo'   => 'Descontos',
                                              'backPage' => 'descontos.php' ),
                         'descontos' => array('titulo'   => 'Descontos',
                                              'backPage' => 'descontos.php' ),
                         'promocoes' => array('titulo'   => 'Promoções',
                                              'backPage' => 'promocoes.php' ),
                        'liberacoes' => array('titulo'   => 'Liberações',
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

                             'login' => array('titulo'   => 'Acesso à administração',
                                              'backPage' => 'login.php' ),



                        );
  $CFGaNiveisUsuarios = array (1  => 'Master',
                               5  => 'Administrador',
                               10 => 'Usuário',
  );

  // Subseções das páginas criadas via HTML GERAL
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
                             'errors'     => array( 0 => 'Não houve erro',
                                                    1 => 'O arquivo no upload é maior do que o limite do PHP',
                                                    2 => 'O arquivo ultrapassa o limite de tamanho especifiado no HTML',
                                                    3 => 'O upload do arquivo foi feito parcialmente',
                                                    4 => 'Não foi feito o upload do arquivo') );

  $CFGaImagensStatusParam = array('A' => 'link.png',
                                  'I' => 'link_break.png' );
  
  $CFGsCdEmpresa = usuario_admin::getEmpresa();
  
  $CFGaSituacao = array ('A' => 'Ativo',
                         'I' => 'Inativo');
  
  $CFGaTiposContato = array('Esclarecer uma dúvida' => 'Esclarecer uma dúvida',
                            'Dar uma sugestão'      => 'Dar uma sugestão',
                            'Fazer uma reclamação'  => 'Fazer uma reclamação',
                            'Fazer um elogio'       => 'Fazer um elogio');
  
  $CFGaTipoImagens = array('PR' => 'Imagem principal',
                           'DP' => 'Imagem de demonstração');
  
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
                             'AC' => 'Aguardando Confirmação de Pagamento',
                             'DI' => 'Em disputa',
                             'PC' => 'Pagamento Confirmado',
                             'DE' => 'Pagamento devolvido e compra desfeita',
                             'EX' => 'Expedição',
                             'EV' => 'Enviado',
                             'FI' => 'Compra Finalizada',
                             'CA' => 'Compra Cancelada',
                             'CI' => 'Compra Cancelada por Inatividade'
      );
  
  /*
   * Grupos de situações. Usado em telas de pesquisa
   */
  $CFGaGrupoPedidosPendentes  = array('AB', 'EP', 'AP');
  $CFGaGrupoPedidosAguardando = array('AC', 'DI', 'EX');
  $CFGaGrupoPedidosEfetivados = array('PC', 'EV', 'FI');
  $CFGaGrupoPedidosCancelados = array('DE', 'CA', 'CI');
  
  $CFGaStatusPagSeguro = array( 1 => 'Aguardando pagamento: o comprador iniciou a transação, mas até o momento o PagSeguro não recebeu nenhuma informação sobre o pagamento',
                                2 => 'Em análise: o comprador optou por pagar com um cartão de crédito e o PagSeguro está analisando o risco da transação',
                                3 => 'Paga: a transação foi paga pelo comprador e o PagSeguro já recebeu uma confirmação da instituição financeira responsável pelo processamento',
                                4 => 'Disponível: a transação foi paga e chegou ao final de seu prazo de liberação sem ter sido retornada e sem que haja nenhuma disputa aberta',
                                5 => 'Em disputa: o comprador, dentro do prazo de liberação da transação, abriu uma disputa',
                                6 => 'Devolvida: o valor da transação foi devolvido para o comprador',
                                7 => 'Cancelada: a transação foi cancelada sem ter sido finalizada');

  // De   : Situação do Pag Seguro
  // Para : Situação na tabela tc_carrinho
  $CFGaCruzamentoSituacoes = array( 1 => 'AP',
                                    2 => 'AC',
                                    3 => 'PC',
                                    4 => 'PC',
                                    5 => 'DI',
                                    6 => 'DE',
                                    7 => 'CA');


  $CFGaMotivosCancelamento = array( 'INTERNAL' =>	'PagSeguro',
                                    'EXTERNAL' => 'Instituições Financeiras' );

  $CFGaTipoMetodoPagamento = array ( 1 => 'Cartão de crédito: o comprador escolheu pagar a transação com cartão de crédito',
                                     2 => 'Boleto: o comprador optou por pagar com um boleto bancário',
                                     3 => 'Débito online (TEF): o comprador optou por pagar a transação com débito online de algum dos bancos conveniados',
                                     4 => 'Saldo PagSeguro: o comprador optou por pagar a transação utilizando o saldo de sua conta PagSeguro',
                                     5 => 'Oi Paggo: o comprador escolheu pagar sua transação através de seu celular Oi' );

  $CFGaCodigoMetodoPagamento = array( 101 => 'Cartão de crédito Visa',
                                      102 => 'Cartão de crédito MasterCard',
                                      103 => 'Cartão de crédito American Express',
                                      104 => 'Cartão de crédito Diners',
                                      105 => 'Cartão de crédito Hipercard',
                                      106 => 'Cartão de crédito Aura',
                                      107 => 'Cartão de crédito Elo',
                                      108 => 'Cartão de crédito PLENOCard',
                                      109 => 'Cartão de crédito PersonalCard',
                                      110 => 'Cartão de crédito JCB',
                                      111 => 'Cartão de crédito Discover',
                                      112 => 'Cartão de crédito BrasilCard',
                                      113 => 'Cartão de crédito FORTBRASIL',
                                      114 => 'Cartão de crédito CARDBAN',
                                      115 => 'Cartão de crédito VALECARD',
                                      116 => 'Cartão de crédito Cabal',
                                      117 => 'Cartão de crédito Mais!',
                                      201 => 'Boleto Bradesco',
                                      202 => 'Boleto Santander',
                                      301 => 'Débito online Bradesco',
                                      302 => 'Débito online Itaú',
                                      303 => 'Débito online Unibanco',
                                      304 => 'Débito online Banco do Brasil',
                                      305 => 'Débito online Banco Real',
                                      306 => 'Débito online Banrisul',
                                      307 => 'Débito online HSBC',
                                      401 => 'Saldo PagSeguro',
                                      501 => 'Oi Paggo',
    );
  
  $CFGaCardinalF = array( '1' => 'Uma', '2' => 'Duas', '3' => 'Três', '4' => 'Quatro', '5' => 'Cinco', '6' => 'Seis', '7' => 'Sete', '8' => 'Oito', '9' => 'Nove', '10' => 'Dez', '11' => 'Onze', '12' => 'Doze', '13' => 'Treze', '14' => 'Quatorze', '15' => 'Quinze', '16' => 'Dezesseis', '17' => 'Dezessete', '18' => 'Dezoito', '19' => 'Dezenove', '20' => 'Vinte');
  $CFGaCardinalM = array( '1' => 'Um', '2' => 'Dois', '3' => 'Três', '4' => 'Quatro', '5' => 'Cinco', '6' => 'Seis', '7' => 'Sete', '8' => 'Oito', '9' => 'Nove', '10' => 'Dez', '11' => 'Onze', '12' => 'Doze', '13' => 'Treze', '14' => 'Quatorze', '15' => 'Quinze', '16' => 'Dezesseis', '17' => 'Dezessete', '18' => 'Dezoito', '19' => 'Dezenove', '20' => 'Vinte');
  $CFGaOrdinal = array( '1' => 'primeiro',
                        '2' => 'segundo',
                        '3' => 'terceiro',
                        '4' => 'quarto',
                        '5' => 'quinto',
                        '6' => 'sexto',
                        '7' => 'sétimo',
                        '8' => 'oitavo',
                        '9' => 'nono',
                       '10' => 'décimo',
                       '20' => 'vigésimo',
                       '30' => 'trigésimo',
                       '40' => 'quadragésimo',
                       '50' => 'quinquagésimo',
                       '60' => 'sexagésimo',
                       '70' => 'septuagésimo',
                       '80' => 'octogésimo',
                       '90' => 'nonagésimo',
                      '100' => 'centésimo',
                      '200' => 'ducentésimo',
                      '300' => 'trecentésimo',
                      '400' => 'quadrigentésimo',
                      '500' => 'quingentésimo',
                      '600' => 'sexcentésimo',
                      '700' => 'septigentésimo',
                      '800' => 'octigentésimo',
                      '900' => 'nongentésimo',
                     '1000' => 'milésimo',
                    '10000' => 'milionésimo',
                    '20000' => 'bilionésimo' );

  $aMsg = array('iCdMsg' => '', 'sMsg' => '', 'sMsgErro' => '', 'sResultado' => '');

?>
