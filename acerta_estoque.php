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

set_time_limit(0);

//************************************************/
$dbname  = "auto_bag_2108";
$dbhost  = "192.168.0.43";
$dbport  = "5433";
//***********************************************/

$conn = pg_connect("dbname=$dbname user=postgres port=$dbport host=$dbhost") or die('ERRO AO CONECTAR NA BASE DE DADOS !!');
	
pg_query("BEGIN;");

$erro=false;
$result=pg_exec("select m82_matestoqueitem,
				       	sum(case when m81_entrada = 't' then m82_quant else 0 end)as quant_entrada,
       					sum(case when m81_entrada = 'f' then m82_quant else 0 end)as quant_saida 
				from matestoqueini 
     				inner join matestoqueinimei on m82_matestoqueini = m80_codigo 
     				inner join matestoquetipo on m81_codtipo = m80_codtipo 
				group by m82_matestoqueitem");
if($result==false){
	$erro=true;
}				
if ($erro==false){
	for($w=0;$w<pg_numrows($result);$w++){
		
		$cod_matestoqueitem =  pg_result($result,$w,"m82_matestoqueitem");
		$quant_entrada      =  pg_result($result,$w,"quant_entrada");
		$quant_saida        =  pg_result($result,$w,"quant_saida");
		
		$update_matestoqueitem = pg_query("UPDATE matestoqueitem SET m71_quant = '$quant_entrada' , m71_quantatend = '$quant_saida' WHERE m71_codlanc = $cod_matestoqueitem");
		if ($update_matestoqueitem==false){
			$erro=true;
			break;
		}	
	}
}
if ($erro==false){
	$result_estoque=pg_exec("select m71_codmatestoque, 
 									sum(m71_quant)as soma_entrada,
									sum(m71_quantatend)as soma_saida,
 									sum(m71_valor)as soma_valor
							 from matestoqueitem 
							 group by m71_codmatestoque");
	if($result_estoque==false){
		$erro=true;
	}									
	if ($erro==false){
		for($w=0;$w<pg_numrows($result_estoque);$w++){
			$m71_codmatestoque =  pg_result($result_estoque,$w,"m71_codmatestoque");
			$soma_entrada =  pg_result($result_estoque,$w,"soma_entrada");
			$soma_saida   =  pg_result($result_estoque,$w,"soma_saida");
			$soma_valor   =  pg_result($result_estoque,$w,"soma_valor");
			$valor_uni = '0';
			$valor_est = '0';
			$quant_est = $soma_entrada-$soma_saida;
			if ($soma_entrada!=0){
				$valor_uni = $soma_valor/$soma_entrada; 
			}
			$valor_est = $valor_uni*$quant_est; 
			$update_matestoque = pg_query("UPDATE matestoque SET m70_quant = '$quant_est' , m70_valor ='$valor_est' WHERE m70_codigo = $m71_codmatestoque");
			if ($update_matestoque==false){
				$erro=true;
				break;
			}	
		}
	}		 
}
if ($erro==true){
	pg_query("ROLLBACK;");
	echo "Processamento Cancelado!!";
}else{
	pg_query("COMMIT;");
	echo "Processamento Efetuado com Sucesso!!";
}
?>