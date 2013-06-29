 DROP VIEW IF EXISTS v_pedidos; 
 CREATE VIEW v_pedidos AS 

	SELECT 
		tc_carrinho.id,
		tc_carrinho.cd_carrinho,
		tc_carrinho.cd_pagseguro,
		tc_carrinho.sq_carrinho,
		tc_carrinho.cd_sit,
		tc_carrinho.cd_pagamento,
		tc_carrinho.nu_itens,
		tc_carrinho.id_cliente,
		tc_carrinho.id_end_entrega,
		tc_carrinho.vl_item,
		tc_carrinho.vl_adicional,
		tc_carrinho.vl_taxas,
		tc_carrinho.vl_desconto,
		tc_carrinho.vl_frete,
		tc_carrinho.vl_total,
		tc_carrinho.cd_nf,
		tc_carrinho.tx_obs,
		DATE_FORMAT(tc_carrinho.dt_criacao,'%d/%m/%Y') AS dt_criacao,
		tc_carrinho.hr_criacao,
		DATE_FORMAT(tc_carrinho.dt_fechamento,'%d/%m/%Y') AS dt_fechamento,
		tc_carrinho.hr_fechamento, 

		'Transportadora' AS cd_tipo_entrega,
		'&nbsp;' AS de_entrega,

		tr_carrinho_itens.nu_quantidade,
		tr_carrinho_itens.vl_final,

		tc_clientes_enderecos.nm_logradouro,
		tc_clientes_enderecos.tp_logradouro,
		tc_clientes_enderecos.tx_numero,
		tc_clientes_enderecos.tx_complemento,
		tc_clientes_enderecos.nu_cep,
		tc_clientes_enderecos.tx_bairro,
		tc_clientes_enderecos.nm_uf,
		tc_clientes_enderecos.nm_cid,

		tc_produtos.nm_produto,
		
		-- Resumo de clientes
		tc_clientes.nm_cliente,
		tc_clientes.nm_sobrenome,
		
		-- Dados sobre a finalização de pedidos
		tr_carrinhos_finalizados.nr_nf,
		tr_carrinhos_finalizados.dt_finalizacao,
       
		-- Dados coleta de itens pela transportadora
		DATE_FORMAT(tr_coletas.dt_coleta,'%d/%m/%Y') AS dt_coleta,
		tr_coletas.id_transportadora,
		tr_coletas.tx_obs AS obs_coleta,
		tr_coletas.cd_canhoto,

		-- Dados da transportadora		
		tc_transportadoras.nm_transportadora,
		tc_transportadoras.tx_tel AS tx_tel_transportadora,
		tc_transportadoras.id_endereco,
		tc_transportadoras.tx_obs AS obs_transportadora
       
	   FROM tc_carrinho
  
     INNER JOIN tr_carrinho_itens 		ON tr_carrinho_itens.id_carrinho = tc_carrinho.id
     INNER JOIN tc_clientes_enderecos 		ON tc_clientes_enderecos.id = tc_carrinho.id_end_entrega
     INNER JOIN tc_produtos 			ON tc_produtos.id = tr_carrinho_itens.id_prod
     INNER JOIN tc_clientes 			ON tc_clientes.id = tc_carrinho.id_cliente
      LEFT JOIN tr_coletas  			ON tr_coletas.id_carrinho = tc_carrinho.id
      LEFT JOIN tr_carrinhos_finalizados 	ON tr_carrinhos_finalizados.id_carrinho = tc_carrinho.id
      LEFT JOIN tc_transportadoras 		ON tc_transportadoras.id = tr_coletas.id_carrinho
       ORDER BY tc_carrinho.id DESC;
  
  
