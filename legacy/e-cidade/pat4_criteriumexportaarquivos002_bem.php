<?php
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

$iTipo = $oParametro->valor == 0 ? '20' : '21';
$iIdDaEmpresa = db_getsession("DB_instit");

/*
 select exemplo para buscar o id da familia e id da especie

 select t52_bem as codigo_bem,
                 ( select t64_codcla 
                     from clabens 
                    where t64_class = rpad(substr(classificacao.t64_class,1,2),8,'0')
                 ) as familia,
                 t52_codcla as especie,
                 t52_descr as descricao 
            from bens 
                 inner join clabens classificacao on classificacao.t64_codcla = bens.t52_codcla
*/
$sSqlBem 	= "select distinct '$iTipo'    as tipo_de_registro, 								";
$sSqlBem .= "				t52_instit 	as id_da_empresa, 									";
$sSqlBem .= "		    t52_bem   	as id_do_bem, 											";
$sSqlBem .= "	      t52_bem 	  as codigo_do_bem,										";
$sSqlBem .= "				case when t55_codbem is null then 'A' else 'I' end as situacao_do_bem,";
//$sSqlBem .= "	      ( select t56_situac from histbem where t56_codbem = t52_bem order by t56_data desc limit 1)	
//											as situacao_do_bem,									";
$sSqlBem .= "	      t52_descr	  as nome_do_bem,											";
$sSqlBem .= "      	translate(t52_obs,'\r\n','') as descricao_complementar_do_bem,		";
$sSqlBem .= "	      t52_ident		  as tag_do_bem,											";
$sSqlBem .= "	      '0'		  as numero_de_serie_do_bem,					";
$sSqlBem .= "	      t52_depart  as id_do_centro_de_custo,						";
$sSqlBem .= "	      t30_codigo	as id_setor,											";
$sSqlBem .= "	      coddepto	  as id_localizacao,								";

//$sSqlBem .= "	      t52_codcla	as id_especie,									"; // select 

$sSqlBem .= "	      t70_situac	as id_estado_de_conservacao,			";
$sSqlBem .= "	      t52_numcgm	as id_fornecedor,									";
$sSqlBem .= "				( select t64_codcla 
                     from clabens 
                    	where t64_class = rpad(substr(classificacao.t64_class,1,2),8,'0')
                 		) as id_familia,
                 		t52_codcla as id_especie,";
//$sSqlBem .= "	      t52_codcla	as id_familia,										";

$sSqlBem .= "	      t52_bensmarca		as id_marca,										";
$sSqlBem .= "	      t52_bensmodelo	as id_modelo,										";
$sSqlBem .= "	      t52_bensmedida  as id_medida,										";
$sSqlBem .= "	      t52_valaqu	as valor_contabil_do_bem,						";
$sSqlBem .= "	      '0'		  as valor_de_mercado_do_bem,					";
$sSqlBem .= "	      t52_dtaqu	  as data_da_aquisicao,								";
$sSqlBem .= "	      '0'		  as percentual_de_depreciacao_anual,	";
$sSqlBem .= "	      ( SELECT t41_placa from bensplaca 
											where t41_bem = t52_bem order by t41_data desc limit 1 offset 1)		  
										as codigo_anterior_do_bem,	";
$sSqlBem .= "	      (select t93_depart from benstransfcodigo
										 inner join benstransf on t93_codtran = t95_codtran
										 inner join benstransfconf on t96_codtran = t93_codtran  
										 where t95_codbem = t52_bem order by t96_data desc limit 1)		  
										 as id_anterior_do_centro_de_custo,	";	
$sSqlBem .= "	      (select t93_divisao from benstransfcodigo
										 inner join benstransf on t93_codtran = t95_codtran
										 inner join benstransfconf on t96_codtran = t93_codtran  
										 where t95_codbem = t52_bem order by t96_data desc limit 1)		  
										 as id_anterior_setor,								";
$sSqlBem .= "	      (select t93_depart from benstransfcodigo
										 inner join benstransf on t93_codtran = t95_codtran
										 inner join benstransfconf on t96_codtran = t93_codtran  
										 where t95_codbem = t52_bem order by t96_data desc limit 1)		  
										 as id_anterior_localizacao,					";
$sSqlBem .= "	      '' as id_original_do_bem								";
$sSqlBem .= "	from bens																							";	
$sSqlBem .= "		left join histbem as historico 	on historico.t56_codbem = t52_bem					";
$sSqlBem .= "		left join situabens 	on t70_situac = t56_situac		";
$sSqlBem .= "		left join bensdiv 	  on t33_bem = t52_bem					";
$sSqlBem .= "		left join departdiv	on t33_divisao = t30_codigo 		";	 
$sSqlBem .= "		left join db_depart 	on t52_depart = coddepto     	";
$sSqlBem .= "		left join bensbaix 	on t52_bem = t55_codbem		     	";
$sSqlBem .= "   left join clabens classificacao on classificacao.t64_codcla = bens.t52_codcla";
		
$sSqlBem .= "	where t52_instit = $iIdDaEmpresa ";

if($iCodigoDepto!="" && $iCodigoDepto != 0){
	$sSqlBem .= "	and bens.t52_depart = $iCodigoDepto ";
}
 
//echo $sSqlBem;
//
//$sSqlEmpresa  = "select '$iTipo'    as tipo_de_registro, ";
//$sSqlEmpresa .= "       '10101' as id_da_empresa, ";
//$sSqlEmpresa .= "       '10106' as id_do_bem, ";
//$sSqlEmpresa .= "       '15678' as codigo_do_bem, ";
//$sSqlEmpresa .= "       'A' as situacao_do_bem, ";
//$sSqlEmpresa .= "       'Nome do Bem' as nome_do_bem, ";
//$sSqlEmpresa .= "       'Descricao Complementar do bem' as descricao_complementar_do_bem, ";
//$sSqlEmpresa .= "       'Tag do Bem' as tag_do_bem, ";
//$sSqlEmpresa .= "       'X254s452s' as numero_de_serie_do_bem, ";
//$sSqlEmpresa .= "       '00002' as id_do_centro_de_custo, ";
//$sSqlEmpresa .= "       '10107' as id_setor, ";
//$sSqlEmpresa .= "       '10010' as id_localizacao, ";
//$sSqlEmpresa .= "       '10001' as id_especie, ";
//$sSqlEmpresa .= "       '10002' as id_estado_de_conservacao, ";
//$sSqlEmpresa .= "       '10100' as id_fornecedor, ";
//$sSqlEmpresa .= "       '10003' as id_familia, ";
//$sSqlEmpresa .= "       '10102' as id_marca, ";
//$sSqlEmpresa .= "       '10104' as id_modelo, ";
//$sSqlEmpresa .= "       '10013' as id_medida, ";
//$sSqlEmpresa .= "       '150.25' as valor_contabil_do_bem, ";
//$sSqlEmpresa .= "       '100.25' as valor_de_mercado_do_bem, ";
//$sSqlEmpresa .= "       '2009-03-25' as data_da_aquisicao, ";
//$sSqlEmpresa .= "       '111.00' as percentual_de_depreciacao_anual, ";
//$sSqlEmpresa .= "       '10012' as codigo_anterior_do_bem, ";
//$sSqlEmpresa .= "       '00001' as id_anterior_do_centro_de_custo, ";
//$sSqlEmpresa .= "       '10106' as id_anterior_setor, ";
//$sSqlEmpresa .= "       '10009' as id_anterior_localizacao, ";
//$sSqlEmpresa .= "       '10100' as id_original_do_bem ";

$rsBem		    = pg_query($sSqlBem);

$iNumeroLinhas = pg_num_rows($rsBem);
for ($i=0; $i<$iNumeroLinhas; $i++) {
	$oBem			    = db_utils::fieldsMemory($rsBem,$i);
	$oLayoutTxt->setByLineOfDBUtils($oBem,3,'20');
	db_atutermometro($i, $iNumeroLinhas, 'termometroitem', 1, "Processando Arquivo $arquivo");	
}
// var_dump($oEmpresa);
?>