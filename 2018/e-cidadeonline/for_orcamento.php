<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

session_start();

include("libs/db_conecta.php");
include("libs/db_stdlib.php");
include("libs/db_sql.php");

parse_str(base64_decode($HTTP_SERVER_VARS["QUERY_STRING"]));
db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title><?=$w01_titulo?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/db_script.js"></script>
</head>
<script>
function js_mostra(){
	document.form1.submit(); 
}
function js_imprime(orc,cgm,origem){
	if(origem=='1'){
		
		jan = window.open('com2_solorc002.php?cgm='+cgm+'&pc20_codorc='+orc,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
	}else{
			
			jan = window.open('com2_procorc002.php?cgm='+cgm+'&pc20_codorc='+orc,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
		}
	}
function js_alterar(orc,sol,forne,cgm){
	location.href='for_orcamlista.php?orc='+orc+'&sol='+sol+'&forne='+forne+'&cgm='+cgm;
}
</script>
<style type="text/css">
<?
db_estilosite()
?>
</style>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="<?=$w01_corbody?>">
<br>
<table width='600px' align='center' class="tab"  >
<form name="form1" method="post" target="">
<?
if($id_usuario!=""){
	if(!isset($mostra)){
		$mostra = 1;
	}

?>  
 <div align="center" class='titulo'> Situação dos orçamentos
  <select name="mostra"  onchange="js_mostra()">
   <option value=1 <? if($mostra=="1"){ echo "selected"; }?> >Abertos</option>				 
	 <option value=2 <? if($mostra=="2"){ echo "selected"; }?> >Vencidos</option>
   <option value=3 <? if($mostra=="3"){ echo "selected"; }?> >Todos</option>
  </select> 
 </div>
 <br>  
<?	
if ($mostra==1) {	// se for em ABERTO .............
 	$sql="select distinct on(pc20_codorc) 
 	             pc20_codorc,
 	             pc20_dtate,
 	             pc20_hrate,
 	             z01_nome,
 	             z01_numcgm,
 	             z01_cgccpf,
 	             pc21_orcamforne,
		           z01_ender,
		           z01_compl,
		           z01_munic,
		           z01_uf,
		           z01_cep,
		           z01_telef,
		           z01_fax,
		           z01_contato,
		           pc23_vlrun,
		           case when pc29_orcamitem is not null then 'SOLICITAÇÃO' else 'PROCESSO DE COMPRAS' end as origem,
		           case when exists ( select 1 from pcorcamjulg where pc24_orcamitem = pc22_orcamitem ) 
                    then 'Julgado' 
                    else 'Aberto' 
               end as situacao
		      from pcorcamforne 
		     inner join cgm             on cgm.z01_numcgm             = pcorcamforne.pc21_numcgm 
		     inner join pcorcam         on pcorcam.pc20_codorc        = pcorcamforne.pc21_codorc 
		     inner join pcorcamitem     on pc22_codorc                = pc20_codorc
		      left join pcorcamitemsol  on pc29_orcamitem             = pc22_orcamitem
		      left join pcorcamitemproc on pc31_orcamitem             = pc22_orcamitem
		      left join pcorcamval      on pcorcamitem.pc22_orcamitem = pc23_orcamitem  
		                               and pc23_orcamforne            = pc21_orcamforne
		     where z01_numcgm = $id_usuario 
		       and pc20_dtate >= '".date('Y-m-d',db_getsession('DB_datausu'))."'
		       and (pc29_orcamitem is not null or pc31_orcamitem is not null)
		       and not exists ( select 1 from pcorcamjulg where pc24_orcamitem = pc22_orcamitem )";
} else if ($mostra==2) {// se for VENCIDOS ........

	$sql="select distinct 
	             pc20_codorc, 
	             pc20_dtate,
	             pc20_hrate,
	             z01_nome,
	             z01_numcgm,
	             z01_cgccpf,
	             pc21_orcamforne,
		           z01_ender,
		           z01_compl,
		           z01_munic,
		           z01_uf,
		           z01_cep,
		           z01_telef,
		           z01_fax,
		           z01_contato, 
		           case when pc29_orcamitem is not null then 'SOLICITAÇÃO' else 'PROCESSO DE COMPRAS' end as origem
		      from pcorcamforne 
		     inner join cgm             on cgm.z01_numcgm      = pcorcamforne.pc21_numcgm 
		     inner join pcorcam         on pcorcam.pc20_codorc = pcorcamforne.pc21_codorc 
		     inner join pcorcamitem     on pc22_codorc         = pc20_codorc
 		      left join pcorcamitemsol  on pc29_orcamitem      = pc22_orcamitem
		      left join pcorcamitemproc on pc31_orcamitem      = pc22_orcamitem
		     where z01_numcgm =$id_usuario 
		       and pc20_dtate <= '".date('Y-m-d',db_getsession('DB_datausu'))."'
		       and (pc29_orcamitem is not null or pc31_orcamitem is not null)
		       and not exists ( select 1 from pcorcamjulg where pc24_orcamitem = pc22_orcamitem )
		     order by z01_numcgm";
	
}
if ($mostra==3){// se for TODOS ...........
	$data = date('Y-m-d',db_getsession('DB_datausu'));
	
	$sql="select distinct on(pc20_codorc) pc20_codorc,
	             pc20_dtate,
	             pc20_hrate,
	             z01_nome,
	             z01_numcgm,
	             z01_cgccpf,
	             pc21_orcamforne,
		           z01_ender,
		           z01_compl,
		           z01_munic,
		           z01_uf,
		           z01_cep,
		           z01_telef,
		           z01_fax,
		           z01_contato, 
		           pc23_vlrun, 
		           case when pc29_orcamitem is not null then 'SOLICITAÇÃO' else 'PROCESSO DE COMPRAS' end as origem,
		           case when pc20_dtate <= '$data' 
		                then 'Vencido' 
		                else case when exists ( select 1 from pcorcamjulg where pc24_orcamitem = pc22_orcamitem ) 
		                          then 'Julgado' 
		                          else 'Aberto' 
		                     end 
		           end as situacao
		      from pcorcamforne 
		     inner join cgm             on cgm.z01_numcgm             = pcorcamforne.pc21_numcgm 
		     inner join pcorcam         on pcorcam.pc20_codorc        = pcorcamforne.pc21_codorc 
		     inner join pcorcamitem     on pc22_codorc                = pc20_codorc
		      left join pcorcamitemsol  on pc29_orcamitem             = pc22_orcamitem
		      left join pcorcamitemproc on pc31_orcamitem             = pc22_orcamitem
		      left join pcorcamval      on pcorcamitem.pc22_orcamitem = pc23_orcamitem 
		                               and pc23_orcamforne            = pc21_orcamforne
		     where z01_numcgm =$id_usuario 
		       and (pc29_orcamitem is not null or pc31_orcamitem is not null)";

}
		
	$result = db_query($sql);
	$linhas = pg_num_rows($result);
	if($linhas>0){
		    echo"
		    <tr >
				<th align='center'> Orçamento
				</th>
				<th align='center'> Data
				</th> 
				<th align='center'> Hora
				</th>
				<th align='center'>Origem 
				</th>
				<th align='center'>Imprimir
				</th>";
				if($mostra==3){echo"<th>Situacao</th>";}
			echo"</tr>";
		
		for ($i = 0; $i < $linhas; $i ++) {
			db_fieldsmemory($result,$i);
			
			if($origem=="SOLICITAÇÃO"){ 
				$sol= 1;
				
			}else{
				$sol= 2;
				
			}	
			
			echo "<tr align='center' class='texto'>";
			echo"<td>$pc20_codorc</td>";
			
			if($origem=="SOLICITAÇÃO"){
				$sol=1;
			}else{
				$sol=2;
			}
			
			
				echo"
				</td>
				<td> ".db_formatar($pc20_dtate, 'd')."
				</td>
				<td>$pc20_hrate
				</td>
				<td align='center'>$origem $pc21_orcamforne
				</td>
				</td>
				<td align='left'>
					<input name='imprimir' type='button' value='Imprimir' class='botao' onclick='js_imprime($pc20_codorc,$id_usuario,$sol)'>
					";
					if ($mostra==1) {// se for aberto
						if($pc23_vlrun!="" && $situacao=="Aberto"){ // se tiver valor
							echo"<input name='alterar'  type='button' value='Alterar'  class='botao' onclick='js_alterar($pc20_codorc,$sol,$pc21_orcamforne,$id_usuario)'>";
						}else{// se não tiver valor
							echo"<input name='incluir'  type='button' value='Incluir'  class='botao' onclick='js_alterar($pc20_codorc,$sol,$pc21_orcamforne,$id_usuario)'>";
						}
					}
					if($mostra==3){
						if($situacao=="Aberto"){ // se for aberto
							if($pc23_vlrun!="" ){ // se tiver valor
								echo"<input name='alterar'  type='button' value='Alterar'  class='botao' onclick='js_alterar($pc20_codorc,$sol,$pc21_orcamforne,$id_usuario)'>";
							}else{// se não tiver valor
								echo"<input name='incluir'  type='button' value='Incluir'  class='botao' onclick='js_alterar($pc20_codorc,$sol,$pc21_orcamforne,$id_usuario)'>";
							}
						}
					}
					
				echo"</td>";
				if($mostra==3){echo"<td>$situacao</td>";}
				echo"
				</tr>";
			
		}
	}
	
}else{ 
	echo " não logado";
}
?>
</form>
</table>
</body>
</html>