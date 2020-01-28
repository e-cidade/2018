<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

include("fpdf151/pdf.php");
include("libs/db_sql.php");
include("classes/db_projmelhorias_classe.php");
include("classes/db_editalproj_classe.php");

$clprojmelhorias = new cl_projmelhorias;
$cleditalproj = new cl_editalproj;
$clprojmelhorias->rotulo->label();

$clrotulo = new rotulocampo;
$clrotulo->label('j14_nome');
$clrotulo->label('d01_numero');
$clrotulo->label('nome');
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$dbwhere="1=1";
$and="";

if(isset($valor) && $valor != ""){
  if($perc=="ma"){
    $dbwhere .=" and (percentual_imposto::float8 + percentual_taxas::float8) > $valor ";
  } elseif ($perc=="me"){
    $dbwhere .=" and (percentual_imposto::float8 + percentual_taxas::float8) < $valor ";
  }elseif ($perc=="mame"){
    $dbwhere .=" and abs(percentual_imposto::float8 + percentual_taxas::float8) > $valor ";
  }

}  

$inner = " ";
$isen="nao";
if($isen=='nao' or 1==1) {
  $dbwhere .= " and j01_matric
	         not in(  select j01_matric as matric from iptubase  
                             inner join iptuisen on j46_matric = j01_matric 
     		             inner join isenexe on j47_anousu = $ano1 and j46_codigo=j47_codigo 
		       ) ";
}


if(isset($ordem) && $ordem != ""){
  $dbwhere   .= " order by $ordem $order";
}

$sql =  "
	select	h.*, 
					(select j23_m2terr from iptucalc where j23_anousu = $ano1 and j23_matric = h.j01_matric) as j23_m2terr_ano1,
					(select j23_arealo from iptucalc where j23_anousu = $ano1 and j23_matric = h.j01_matric) as j23_arealo_ano1,
					(select j23_areaed from iptucalc where j23_anousu = $ano1 and j23_matric = h.j01_matric) as j23_areaed_ano1,
					(select j23_vlrter from iptucalc where j23_anousu = $ano1 and j23_matric = h.j01_matric) as j23_vlrter_ano1,
					coalesce((select sum(j22_valor) from iptucale where j22_anousu = $ano1 and j22_matric = h.j01_matric),0) as j23_vlredi_ano1,
					(select j23_tipoim from iptucalc where j23_anousu = $ano1 and j23_matric = h.j01_matric) as j23_tipoim_ano1,
					(select j23_m2terr from iptucalc where j23_anousu = $ano2 and j23_matric = h.j01_matric) as j23_m2terr_ano2,
					(select j23_arealo from iptucalc where j23_anousu = $ano2 and j23_matric = h.j01_matric) as j23_arealo_ano2,
					(select j23_areaed from iptucalc where j23_anousu = $ano2 and j23_matric = h.j01_matric) as j23_areaed_ano2,
					(select j23_vlrter from iptucalc where j23_anousu = $ano2 and j23_matric = h.j01_matric) as j23_vlrter_ano2,
					(select j23_tipoim from iptucalc where j23_anousu = $ano2 and j23_matric = h.j01_matric) as j23_tipoim_ano2,
					coalesce((select sum(j22_valor) from iptucale where j22_anousu = $ano2 and j22_matric = h.j01_matric),0) as j23_vlredi_ano2
					from (
	  select j01_matric, 
		valor_imposto_1, valor_imposto_2, case when valor_imposto_1 = 0 or valor_imposto_2 = 0 then 0 else round(100 - (valor_imposto_2 / valor_imposto_1 * 100),5) * -1 end as percentual_imposto, 
		valor_taxas_1, valor_taxas_2, case when valor_taxas_1 = 0 or valor_taxas_2 = 0 then 0 else round(100 - (valor_taxas_2 / valor_taxas_1 * 100),5) * -1 end as percentual_taxas
		
		from (
	    select * from ( 
		select j01_matric, sum_imposto_1 as valor_imposto_1, sum_imposto_2 as valor_imposto_2, sum_taxas_1 as valor_taxas_1, sum_taxas_2 as valor_taxas_2 from (
			select j01_matric, sum_imposto_1, sum_imposto_2, sum_taxas_1, sum_taxas_2
			from	(
							select j01_matric, coalesce(x.sum,0) as sum_imposto_1, coalesce(y.sum,0) as sum_imposto_2, coalesce(xx.sum,0) as sum_taxas_1, coalesce(yy.sum,0) as sum_taxas_2 from iptubase
							left join	(	select j21_matric, sum(j21_valor) from iptucalv where j21_anousu = $ano1 and j21_codhis in (1,5)
													group by iptucalv.j21_matric) as x on x.j21_matric = iptubase.j01_matric
							left join (	select j21_matric, sum(j21_valor) from iptucalv where j21_anousu = $ano2 and j21_codhis in (1,5)
													group by iptucalv.j21_matric) as y on y.j21_matric = iptubase.j01_matric
							left join	(	select j21_matric, sum(j21_valor) from iptucalv where j21_anousu = $ano1 and j21_codhis in (2)
													group by iptucalv.j21_matric) as xx on xx.j21_matric = iptubase.j01_matric
							left join (	select j21_matric, sum(j21_valor) from iptucalv where j21_anousu = $ano2 and j21_codhis in (2)
													group by iptucalv.j21_matric) as yy on yy.j21_matric = iptubase.j01_matric
							where j01_baixa is null 
						) 
			as z) as a
	     ) as f) as g) as h
	     where $dbwhere
		
         ";
//die($sql);
$result = pg_query($sql) or die($sql);
$numrows = pg_numrows($result); 

if ($numrows == 0) {
	$erro = true;
	$descricao_erro = "Não existe matrículas cadastradas!";
}

$quant=0;

$nomedoarquivo = "/tmp/compara_calculo_iptu_" . $ano1 . "_com_" . $ano2 . "_" . date("Y-m-d_His",db_getsession("DB_datausu")) . ".txt";

$erro = false;
$descricao_erro = false;
set_time_limit(0);
$clabre_arquivo = new cl_abre_arquivo($nomedoarquivo);

if ($clabre_arquivo->arquivo != false) {
	fputs($clabre_arquivo->arquivo, "matricula;");
	fputs($clabre_arquivo->arquivo, "valor_imposto_1;");
	fputs($clabre_arquivo->arquivo, "valor_imposto_2;");
	fputs($clabre_arquivo->arquivo, "percentual_imposto;");
	fputs($clabre_arquivo->arquivo, "tipoim_1;");
	fputs($clabre_arquivo->arquivo, "tipoim_2;");
	fputs($clabre_arquivo->arquivo, "diftipoim;");
	fputs($clabre_arquivo->arquivo, "valor_taxas_1;");
	fputs($clabre_arquivo->arquivo, "valor_taxas_2;");
	fputs($clabre_arquivo->arquivo, "percentual_taxas;");
	fputs($clabre_arquivo->arquivo, "vlrm2$ano1;");
	fputs($clabre_arquivo->arquivo, "arealote$ano1;");
	fputs($clabre_arquivo->arquivo, "areaedif$ano1;");
	fputs($clabre_arquivo->arquivo, "vlrvenalterreno$ano1;");
	fputs($clabre_arquivo->arquivo, "vlrvenaledif$ano1;");
	fputs($clabre_arquivo->arquivo, "vlrm2$ano2;");
	fputs($clabre_arquivo->arquivo, "arealote$ano2;");
	fputs($clabre_arquivo->arquivo, "areaedif$ano2;");
	fputs($clabre_arquivo->arquivo, "vlrvenalterreno$ano2;");
	fputs($clabre_arquivo->arquivo, "vlrvenaledif$ano2;");
	fputs($clabre_arquivo->arquivo, "\n");

}

for ($i = 0;$i < $numrows;$i++){
  db_fieldsmemory($result,$i,true);

  if ($imprimirsemdif == "nao") {
    if (($percentual_imposto + $percentual_taxas) == 0) {
      continue;
    }
  }
  
	fputs($clabre_arquivo->arquivo, str_pad($j01_matric,10,'0',STR_PAD_LEFT).";");

	fputs($clabre_arquivo->arquivo, trim(db_formatar($valor_imposto_1   ,'f')).";");
	fputs($clabre_arquivo->arquivo, trim(db_formatar($valor_imposto_2   ,'f')).";");
	fputs($clabre_arquivo->arquivo, trim(db_formatar($percentual_imposto,'f')).";");

	fputs($clabre_arquivo->arquivo, trim($j23_tipoim_ano1)                    .";");
	fputs($clabre_arquivo->arquivo, trim($j23_tipoim_ano2)                    .";");

	fputs($clabre_arquivo->arquivo, ( trim($j23_tipoim_ano1) == trim($j23_tipoim_ano2)?"IGUAL":"DIFERENTE" ) .";");

	fputs($clabre_arquivo->arquivo, trim(db_formatar($valor_taxas_1		  ,'f')).";");
	fputs($clabre_arquivo->arquivo, trim(db_formatar($valor_taxas_2		  ,'f')).";");
	fputs($clabre_arquivo->arquivo, trim(db_formatar($percentual_taxas  ,'f')).";");

	fputs($clabre_arquivo->arquivo, trim(db_formatar($j23_m2terr_ano1   ,'f')).";");
	fputs($clabre_arquivo->arquivo, trim(db_formatar($j23_arealo_ano1   ,'f')).";");
	fputs($clabre_arquivo->arquivo, trim(db_formatar($j23_areaed_ano1   ,'f')).";");
	fputs($clabre_arquivo->arquivo, trim(db_formatar($j23_vlrter_ano1   ,'f')).";");
	fputs($clabre_arquivo->arquivo, trim(db_formatar($j23_vlredi_ano1   ,'f')).";");
	
	fputs($clabre_arquivo->arquivo, trim(db_formatar($j23_m2terr_ano2   ,'f')).";");
	fputs($clabre_arquivo->arquivo, trim(db_formatar($j23_arealo_ano2   ,'f')).";");
	fputs($clabre_arquivo->arquivo, trim(db_formatar($j23_areaed_ano2   ,'f')).";");
	fputs($clabre_arquivo->arquivo, trim(db_formatar($j23_vlrter_ano2   ,'f')).";");
	fputs($clabre_arquivo->arquivo, trim(db_formatar($j23_vlredi_ano2   ,'f')).";");
	fputs($clabre_arquivo->arquivo, "\n");
	
}

$descricao_erro = "Arquivo $nomedoarquivo gerado com sucesso.";

fclose($clabre_arquivo->arquivo);

if (isset($local) or 1==1) {
	echo "<script>jan = window.open('db_download.php?arquivo=" . $clabre_arquivo->nomearq . "','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');";
	echo "jan.moveTo(0,0);</script>";
}

db_msgbox($descricao_erro);
  
?>