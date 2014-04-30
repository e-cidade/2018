<?
$campos = "db51_codigo as db_db51_codigo,
           db50_descr,
           db_layoutlinha.db51_descr,
           case db_layoutlinha.db51_tipolinha when 1 then 'Header de arquivo'
	                                      when 2 then 'Header de lote'
		                              when 3 then 'Registro'
		                              when 4 then 'Trailler de lote'
					      when 5 then 'Trailler de arquivo' 
           end as db51_tipolinha,
           db_layoutlinha.db51_tamlinha,
	   db_layoutlinha.db51_obs";
?>
