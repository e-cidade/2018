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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_far_retirada_classe.php");
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clfar_retirada = new cl_far_retirada;
//echo $clfar_retirada->sql_query(null,'*',"fa04_d_data desc","fa04_i_cgsund=$fa04_i_cgsund");

$result = $clfar_retirada->sql_record($clfar_retirada->sql_query(null,'*',"fa04_i_codigo desc","fa04_i_cgsund=$fa04_i_cgsund"));
db_fieldsmemory($result,0);
if($clfar_retirada->numrows==0){?>
<script>
 parent.parent.document.formaba.a2.disabled = true;  
 parent.parent.document.formaba.a3.disabled = true; 	
 </script>
<?}else{?>
<script>
 parent.parent.document.formaba.a2.disabled = false;  
 </script>	
<?}
?>
<html>
<body>
<table border="1" width="100%">
<tr>
	<td>
		Retirada
	</td>
	<td >
		Requisição
	</td>
	<td>
		Data
	</td>
	<td>
		Receita
	</td>
	<td width="96">
		Medicamento
	</td>
</tr>	
<?

for($i=0;$i<$clfar_retirada->numrows;$i++){
 db_fieldsmemory($result,$i);
?>			
<tr>

	<td><b><?=$fa04_i_codigo?></b></td>
	<td ><b><?=$fa07_i_matrequi?></b></td>
	<td><b><?=db_formatar($fa04_d_data,'d')?></b></td>
	<td ><b><?=$fa04_c_numeroreceita==""?"&nbsp;":$fa04_c_numeroreceita?></b></td>
	<td width="96">
		<input name="consultas" type="button" id="consultas" value="Consultar Medicamento" onClick='js_consulta(<?=$fa04_i_codigo?>);' >
	</td>
</tr>				
<?}
?>
 
</table>
</body
</html>
<script>
function js_consulta(fa04_i_codigo){
	//alert(fa04_i_codigo);
	parent.parent.document.formaba.a1.disabled = true;
	parent.parent.document.formaba.a2.disabled = true;
    parent.parent.document.formaba.a3.disabled = false;                              
    parent.parent.iframe_a3.location.href='far3_consultaretirada002.php?chavepesquisaconsulta='+fa04_i_codigo+'&fa04_i_cgsund=<?=$fa04_i_cgsund?>';                                          
    parent.parent.mo_camada('a3');		
}	
</script>