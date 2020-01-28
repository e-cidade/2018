<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBselller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */


function  db_dotacao_elemento_recurso ($anousu,$dataini,$datafim,$condicao="",$subelemento="nao" ) {
	if ($condicao!="")
	   $condicao = " and $condicao";
	   
   $tipo_saldo=2;
   $sql = " /*create temp table work_dotacao as  */
             select 
                o56_elemento as o58_elemento,
                o56_descr       as o56_descr,
                o15_codigo  ,
                o15_descr      ,                
                sum(dot_ini) as dot_ini,
	            sum(saldo_anterior) as saldo_anterior,
	            sum(empenhado ) as empenhado,
	            sum(anulado  ) as anulado,
	            sum(liquidado ) as liquidado,
	            sum(pago  ) as pago,
	            sum(suplementado  ) as suplementado,
	            sum(reduzido ) as reduzido,
	            sum(atual ) as  atual,
	            sum( reservado ) as reservado,
	            sum( atual_menos_reservado ) as atual_menos_reservado,
	            sum( atual_a_pagar ) as atual_a_pagar,
		        sum( atual_a_pagar_liquidado ) as atual_a_pagar_liquidado,
	            sum( empenhado_acumulado ) as empenhado_acumulado,
	            sum( anulado_acumulado ) as  anulado_acumulado,
	            sum( liquidado_acumulado ) as  liquidado_acumulado,
	            sum( pago_acumulado ) as  pago_acumulado,
	            sum(suplementado_acumulado) as suplementado_acumulado,
	            sum(reduzido_acumulado) as  reduzido_acumulado,
	            sum(suplemen) as suplemen,
	            sum(suplemen_acumulado) as suplemen_acumulado,
	            sum(especial) as especial,
	            sum(especial_acumulado) as especial_acumulado
            from ( 
                  select *              
				   from 
			       (select o58_anousu, 
				              o58_orgao,
				              o58_unidade,
				              o58_funcao,
				              o58_subfuncao,
				              o58_programa,
				              o58_projativ,
				              o56_codele as o58_codele,
				  	          case when '$subelemento'='sim' 
					             then 9999999 
						      else o58_coddot
				                 end as o58_coddot, 		    
					          case when '$subelemento'='sim' 
					          then substr(o56_elemento,1,7) 
						      else o56_elemento 
					          end as o58_elemento,
					          /*
					             24/08/05-> esse era o trecho de codigo anterior aos cases acima
			  		             o58_coddot,
				                 o56_elemento as o58_elemento,
					         */
					          o58_codigo,
				              substr(fc_dotacaosaldo,3,12)::float8   as dot_ini,
				              substr(fc_dotacaosaldo,16,12)::float8  as saldo_anterior,
				              substr(fc_dotacaosaldo,29,12)::float8  as empenhado,
				              substr(fc_dotacaosaldo,42,12)::float8  as anulado,
				              substr(fc_dotacaosaldo,55,12)::float8  as liquidado,
				              substr(fc_dotacaosaldo,68,12)::float8  as pago,
				              substr(fc_dotacaosaldo,81,12)::float8  as suplementado,
				              substr(fc_dotacaosaldo,094,12)::float8 as reduzido,
				              substr(fc_dotacaosaldo,107,12)::float8 as atual,
				              substr(fc_dotacaosaldo,120,12)::float8 as reservado,
				              substr(fc_dotacaosaldo,133,12)::float8 as atual_menos_reservado,
				              substr(fc_dotacaosaldo,146,12)::float8 as atual_a_pagar,
					          substr(fc_dotacaosaldo,159,12)::float8 as atual_a_pagar_liquidado,
				              substr(fc_dotacaosaldo,172,12)::float8 as empenhado_acumulado,
				              substr(fc_dotacaosaldo,185,12)::float8 as anulado_acumulado,
				              substr(fc_dotacaosaldo,198,12)::float8 as liquidado_acumulado,
				              substr(fc_dotacaosaldo,211,12)::float8 as pago_acumulado,
				              substr(fc_dotacaosaldo,224,12)::float8 as suplementado_acumulado,
				              substr(fc_dotacaosaldo,237,12)::float8 as reduzido_acumulado,
				              substr(fc_dotacaosaldo,250,12)::float8 as suplemen,
				              substr(fc_dotacaosaldo,263,12)::float8 as suplemen_acumulado,
				              substr(fc_dotacaosaldo,276,12)::float8 as especial,
				              substr(fc_dotacaosaldo,289,12)::float8 as especial_acumulado
				       from(select *, fc_dotacaosaldo($anousu,o58_coddot,$tipo_saldo,'$dataini','$datafim')
				            from orcdotacao w
				            inner join orcelemento e on w.o58_codele = e.o56_codele and  w.o58_anousu = e.o56_anousu and e.o56_orcado is true
				            where o58_anousu = $anousu 
					        $condicao
					    order by
				              o58_orgao,
				              o58_unidade,
				              o58_funcao,
				              o58_subfuncao,
				              o58_programa,
				              o58_projativ,
					          o56_codele,
				              o56_elemento,
				              o58_coddot,
					          o58_codigo
					    ) as x 
					) as xxx
			             left  outer join orcelemento  oe on oe.o56_elemento = o58_elemento and oe.o56_anousu = o58_anousu
					     left  outer join orctiporec  otr on o15_codigo   	 = o58_codigo
               ) as yy
           group by 
                o56_elemento,
                o56_descr,
                o15_codigo,
                o15_descr
            order by 
                o15_codigo,
                o56_elemento
		    ";	    
	//  echo $sql;
	$result = pg_exec($sql);
	return $result;
	// db_criatabela($result);
	
}
?>