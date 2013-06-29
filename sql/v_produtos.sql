
DROP VIEW IF EXISTS v_produtos;

CREATE VIEW v_produtos AS 

SELECT 

	tc_produtos.id,
	tc_produtos.nm_produto,
	tc_produtos.cd_produto,
	tc_produtos.de_curta,
	tc_produtos.de_longa,
	tc_produtos.cd_status,
	tc_produtos.nu_cliques,
	tc_produtos.nm_pronuncia,
	tc_produtos.id_tipo,
	tc_produtos.id_fabricante,
	tc_produtos.id_categoria,
	tc_produtos.tx_link,


	-- Medidas
	tc_prod_medidas.nu_x,
	tc_prod_medidas.nu_y,
	tc_prod_medidas.nu_z,
	tc_prod_medidas.nu_peso,

	-- Estoque
	tc_prod_estoque.nu_atual,
	tc_prod_estoque.nu_minimo,
	tc_prod_estoque.tx_falta_prod,
	tc_prod_estoque.cd_visivel_em_falta,

	-- Preços
	tc_prod_precos.vl_adicionais,
	tc_prod_precos.vl_taxas,
	tc_prod_precos.vl_custo,
	tc_prod_precos.pc_margem,
	tc_prod_precos.vl_final,
	tc_prod_precos.cd_visivel ,
	
	-- Desconto
	tc_descontos.id AS id_desconto,
	tc_descontos.nm_desconto,
	tc_descontos.de_desconto,
	tc_descontos.tp_valor,
	tc_descontos.tp_desconto,
	tc_descontos.vl_min,
	tc_descontos.vl_desconto,
	
	-- Categoria Principal
	categoria_principal.cd_status AS cd_status_categoria,
	

	-- Todas as Categorias do produto
	GROUP_CONCAT(DISTINCT(tr_prod_cat.id_cat)) AS id_cat_agrupado, 
	GROUP_CONCAT(DISTINCT(tc_prod_categorias.nm_categoria)) AS nm_categoria_agrupado,

	-- Tags
	GROUP_CONCAT(DISTINCT(tr_prod_tag.id_tag)) AS id_tag_agrupado,
	GROUP_CONCAT(DISTINCT(tc_tags.nm_tag)) AS nm_tag_agrupado,
	 
	 
	-- Imagens
	GROUP_CONCAT(DISTINCT(tc_imagens.id)) AS id_imagem_agrupado,
	GROUP_CONCAT(DISTINCT(tc_imagens.nm_imagem)) AS nm_imagem_agrupado,
	
	(SELECT GROUP_CONCAT(tcimg01.nm_imagem) 
	               FROM tc_imagens tcimg01
                  LEFT JOIN tr_prod_img trimagprod01   ON trimagprod01.id_img = tcimg01.id 
                      WHERE trimagprod01.id_prod = tc_produtos.id 
                        AND tcimg01.cd_tipo = 'PR'
                        ) AS nm_imagem_principal


	FROM tc_produtos
  INNER JOIN tc_prod_medidas ON tc_prod_medidas.id_prod = tc_produtos.id
   LEFT JOIN tc_prod_estoque ON tc_prod_estoque.id_prod = tc_produtos.id
   LEFT JOIN tc_prod_precos  ON tc_prod_precos.id_prod = tc_produtos.id

  INNER JOIN tr_prod_cat        ON tr_prod_cat.id_prod = tc_produtos.id
   LEFT JOIN tc_prod_categorias ON tc_prod_categorias.id = tr_prod_cat.id_cat 

   LEFT JOIN tc_prod_categorias categoria_principal ON categoria_principal.id = tc_produtos.id_categoria
  
  INNER JOIN tr_prod_tag    ON tr_prod_tag.id_prod = tc_produtos.id
   LEFT JOIN tc_tags	    ON tc_tags.id = tr_prod_tag.id_tag
   
   LEFT JOIN tr_prod_img    ON tr_prod_img.id_prod = tc_produtos.id
   LEFT JOIN tc_imagens     ON (    tc_imagens.id = tr_prod_img.id_img
				AND tc_imagens.cd_tipo = 'DP' 
				AND tc_imagens.cd_status = 'A' )

   LEFT JOIN tr_prod_desconto ON tr_prod_desconto.id_prod = tc_produtos.id
   LEFT JOIN tc_descontos     ON (tr_prod_desconto.id_desconto = tc_descontos.id
				  AND tc_descontos.cd_status = 'A'
				  AND (	   dt_vigencia_inicio < CURDATE() 
					OR dt_vigencia_inicio IS NULL )
				  AND (    dt_vigencia_fim > CURDATE()
					OR dt_vigencia_fim IS NULL)
			          )
   

   GROUP BY tc_produtos.id,
		tc_produtos.nm_produto,
		tc_produtos.cd_produto,
		tc_produtos.de_curta,
		tc_produtos.de_longa,
		tc_produtos.cd_status,
		tc_produtos.nu_cliques,
		tc_produtos.nm_pronuncia,
		tc_produtos.id_tipo,
		tc_produtos.id_fabricante,
		tc_produtos.id_categoria,
		tc_produtos.tx_link,

		-- Medidas
		tc_prod_medidas.nu_x,
		tc_prod_medidas.nu_y,
		tc_prod_medidas.nu_z,
		tc_prod_medidas.nu_peso,

		-- Estoque
		tc_prod_estoque.nu_atual,
		tc_prod_estoque.nu_minimo,
		tc_prod_estoque.tx_falta_prod,
		tc_prod_estoque.cd_visivel_em_falta,

		-- Preços
		tc_prod_precos.vl_adicionais,
		tc_prod_precos.vl_taxas,
		tc_prod_precos.vl_custo,
		tc_prod_precos.pc_margem,
		tc_prod_precos.vl_final,
		tc_prod_precos.cd_visivel,
		
		-- Desconto
		tc_descontos.id,
		tc_descontos.nm_desconto,
		tc_descontos.de_desconto,
		tc_descontos.tp_valor,
		tc_descontos.tp_desconto,
		tc_descontos.vl_min,
		tc_descontos.vl_desconto		
		;