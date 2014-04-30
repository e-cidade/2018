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

include ("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;
//MODULO: protocolo
$clprocandamint->rotulo->label();
$clprocandamintusu->rotulo->label();
$clprotprocesso->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("p58_codproc");
$clrotulo->label("p61_codandam");
$clrotulo->label("login");
$clrotulo->label("descrdepto");
$clrotulo->label("z01_nome");
$clrotulo->label("p51_descr");
$clrotulo->label("nome");
?>
<script>
function js_submit_form(){  
  js_gera_chaves();
  return true;
}
</script>
<form name="form1" method="post" action="">
<center>
<table border="0" align='left'>
    <tr>
    	<td colspan=2 >
    	<?
    	$dis="";
		$sql=$clsolicita->sql_query_andsol("distinct pc11_numero,pc11_codigo,pc11_quant,pc11_seq,pc11_vlrun,pc11_resum,pc01_codmater,pc01_descrmater,pc01_servico,pc17_unid,pc17_quant,m61_descr,m61_usaquant","where pc10_numero=$pc10_numero and  p64_codtran is not null and y.pc43_depto=".db_getsession("DB_coddepto"));
		$result=pg_exec($sql);		
		if (pg_numrows($result)==0){
			$dis="disabled";
		}
		$cliframe_seleciona->campos = "pc11_numero,pc11_codigo,pc11_quant,pc11_seq,pc11_vlrun,pc11_resum,pc01_codmater,pc01_descrmater,pc01_servico,pc17_unid,m61_descr,pc17_quant";
		$cliframe_seleciona->legenda = "Itens";
		$cliframe_seleciona->sql = @ $sql;
		//$cliframe_seleciona->iframe_height ="200";
		$cliframe_seleciona->iframe_width = "900";
		$cliframe_seleciona->iframe_nome = "itens_teste";
		$cliframe_seleciona->chaves = "pc11_codigo";
		$cliframe_seleciona->iframe_seleciona(1);
    	?>
    	</td>
    </tr>
	<tr>
    	<td nowrap title="<?=@$Tp78_despacho?>"align='left'>
    		<?=@$Lp78_despacho?>
   		</td>
    	<td nowrap title="<?=@$Tp78_despacho?>"align='left' colspan=3>       
			<?
			db_textarea('p78_despacho',12,100,$Ip78_despacho,true,'text',$db_opcao,"");
			?>
		</td>
  	</tr>	  
  	<tr>
  		<td colspan=4 align='center' >  		
			<input name="incluir" type="submit" id="db_opcao" value="Incluir" <?=@$dis?> onclick="return js_submit_form();">
			<input name="voltar" type="button" id="voltar" value="Voltar" onclick='top.corpo.location.href="com4_despsol001.php"' >
		</td>
	</tr>
</table>
</center>
</form>