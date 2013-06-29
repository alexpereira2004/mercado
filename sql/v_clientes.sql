 DROP VIEW IF EXISTS v_clientes;

 CREATE VIEW v_clientes AS 
 
		 SELECT tc_clientes.id,
			tc_clientes.nm_cliente 	 	AS nm_cliente,
			tc_clientes.nm_sobrenome 	AS nm_sobrenome,
			tc_clientes.sg_cliente AS sg_cliente,
			tc_clientes.nu_rg AS nu_rg,
			tc_clientes.nu_cpf AS nu_cpf,
			CONCAT(MID(tc_clientes.nu_cpf,1,3), '.', MID(tc_clientes.nu_cpf,4,3), '.', MID(tc_clientes.nu_cpf,7,3), '-', MID(tc_clientes.nu_cpf,10,2)) AS nu_cpf_formatado,
			DATE_FORMAT(tc_clientes.dt_nascimento, "%d/%m/%Y") AS dt_nascimento,
			tc_clientes.tx_tel AS tx_tel,
			tc_clientes.tx_cel AS tx_cel,
			tc_clientes.cd_sexo AS cd_sexo,
			tc_clientes.tx_setor AS tx_setor,
			tc_clientes.tx_cargo AS tx_cargo,
			tc_clientes.nu_cnpj AS nu_cnpj,   
			CONCAT(MID(tc_clientes.nu_cnpj,1,2), '.', MID(tc_clientes.nu_cnpj,3,3), '.', MID(tc_clientes.nu_cnpj,6,3), '/', MID(tc_clientes.nu_cnpj,9,4), '.', MID(tc_clientes.nu_cnpj,13,2)) AS nu_cnpj_formatado,       
			tc_clientes.nu_ie AS nu_ie,
			tc_clientes.nm_razao_social AS nm_razao_social,
			tc_clientes.nm_fantasia AS nm_fantasia,
			tc_clientes.tx_segmento AS tx_segmento,
			tc_clientes.cd_recebe_news AS cd_recebe_news,
			tc_clientes.tx_email AS tx_email,
			tc_clientes.tx_senha AS tx_senha,
			DATE_FORMAT(tc_clientes.dt_cad, "%d/%m/%Y") AS dt_cad,
			tc_clientes.cd_status AS cd_status,
			tc_clientes.cd_nivel AS cd_nivel,
			tc_clientes.tx_token AS tx_token,

                        -- Tb Endereços                       
			tc_clientes_enderecos.nm_logradouro AS nm_logradouro,
			tc_clientes_enderecos.tp_logradouro AS tp_logradouro,
			tc_clientes_enderecos.tx_numero AS tx_numero,
			tc_clientes_enderecos.tx_complemento AS tx_complemento,
			tc_clientes_enderecos.nu_cep AS nu_cep,
			tc_clientes_enderecos.tx_bairro AS tx_bairro,
			tc_clientes_enderecos.nm_uf AS nm_uf,
			tc_clientes_enderecos.nm_cid AS nm_cid

                        FROM tc_clientes
		   LEFT JOIN tc_clientes_enderecos ON(tc_clientes_enderecos.id_cliente = tc_clientes.id )