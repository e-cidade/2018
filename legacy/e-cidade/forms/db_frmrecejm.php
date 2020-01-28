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

$cltabrecjm->rotulo->label();
if(isset($HTTP_POST_VARS)) {
  db_postmemory($HTTP_POST_VARS);
	 if(!empty($verfEstrut)) {
	   $verfEstrut = "";
	   if($k02_tipo == "O") {
	     $tipo = "Orçamentária";
	     $result = pg_exec("select o02_descr from orcam where o02_anousu = ".db_getsession("DB_anousu")." and o02_codigo = '$k02_estrut'");
	   } else if($k02_tipo == "E") {
	     $tipo = "Extra-orçamentária";
	     $result = pg_exec("select c01_descr from plano where c01_anousu = ".db_getsession("DB_anousu")." and c01_estrut = '$k02_estrut'");
	   }
	   if(pg_numrows($result) == 0) {
	     echo "<script>alert('Código da receita $tipo não encontrado!')</script>\n";
	   } else {
	     $k02_drecei = pg_result($result,0,0);
	   }
	 }
}
?>
<center>
<form name="form1" method="post" id="form1">

<fieldset style="width:800px;">
<legend><b>Detalhes</b></legend>

<table width="800px" >
	<tr>
		<td>
		<table width="100%">
			<tr>
				<td width="180px" align="left" height="30px">&nbsp;&nbsp; C&oacute;digo:</td>
				<td align="left">
				<? db_input('k02_codjm',5,$Ik02_codjm,true,'text',3,'')?>
				</td>
			</tr>
		</table>
		</td>
	</tr>
	<tr>
		<td>
		<fieldset><Legend align="left"><b> Correção</b></Legend>
		<table width="100%">
			<tr>
				<td width="180px" align="left">Inflator Corre&ccedil;&atilde;o:</td>
				<td align="left" ><select name="k02_corr">
							<option value="null">Nenhum...</option>
							<?
					  $result = pg_exec("select i01_codigo,i01_descr from inflan");
					  $numrows = pg_numrows($result);
					  for($i = 0;$i < $numrows;$i++)
					  echo "<option value=\"".pg_result($result,$i,"i01_codigo")."\" ".(isset($k02_corr)?($k02_corr==pg_result($result,$i,"i01_codigo")?"selected":""):"").">".pg_result($result,$i,"i01_codigo")." - ".pg_result($result,$i,"i01_descr")."</option>\n";
					  ?>
					 </select>
			    </td>
			</tr>
			<tr>
				<td align="left" >Corre&ccedil;&atilde;o pelo Vencimento:</td>
				<td align="left"><select name="k02_corven" id="k02_corven">
					<option value="f"
					<? echo isset($k02_corven)?($k02_corven=="f"?"selected":""):"" ?>>N&atilde;o</option>
					<option value="t"
					<? echo isset($k02_corven)?($k02_corven=="t"?"selected":""):"" ?>>Sim</option>
				</select></td>
			    </td>
			</tr>
		</table>
		</fieldset>
		</td>
	</tr>
	<tr>
		<td>
		<fieldset><Legend align="left"><b> Juro</b></Legend>
		<table width="100%" border="0">
			<tr>
				<td align="left">Juros por m&ecirc;s (%):</td>
				<td align="left">
					<? db_input('k02_juros',5,$Ik02_juros,true,'text',$db_opcao,'')?>
					até
					<? db_input('k02_juroslimite',5,$Ik02_juros,true,'text',$db_opcao,'')?>
				</td>
				<td align="left">&nbsp;&nbsp; Fra&ccedil;&atilde;o Diaria:</td>
				<td align="left">
					<select name="k02_jurdia" id="k02_jurdia">
							<option value="f" <? echo isset($k02_jurdia)?($k02_jurdia=="f"?"selected":""):"" ?>>N&atilde;o</option>
							<option value="t" <? echo isset($k02_jurdia)?($k02_jurdia=="t"?"selected":""):"" ?>>Sim</option>
					</select>
				</td>
				<td align="left">Calc Cons Sab/Dom:&nbsp; 
					<select name="k02_sabdom">
						<option value="f" <? echo isset($k02_sabdom)?($k02_sabdom=="f"?"selected":""):"" ?>>N&atilde;o</option>
						<option value="t" <? echo isset($k02_sabdom)?($k02_sabdom=="t"?"selected":""):"" ?>>Sim</option>
					</select>
				</td>
		    </tr>
        
		    <tr>
		    	<td align="left">Juros Financiamento(%):</td>
		    	<td align="left"><? db_input('k02_jurpar',5,$Ik02_jurpar,true,'text',$db_opcao,'')?></td>
		        <td align="left">&nbsp;&nbsp;Acumulativo:</td>
		        <td align="left">
		        	<select name="k02_juracu" id="k02_juracu">
							<option value="f"
							<? echo isset($k02_juracu)?($k02_juracu=="f"?"selected":""):"" ?>>N&atilde;o</option>
							<option value="t"
							<? echo isset($k02_juracu)?($k02_juracu=="t"?"selected":""):"" ?>>Sim</option>
					</select>
				</td>
				<td>&nbsp;</td>
		    </tr>
		</table>
		</fieldset>
        </td>
	</tr>
	
	<tr>
		<td>
		<fieldset><Legend align="left"><b> Desconto</b></Legend>
		<table width="100%">
			<tr>
				<td colspan="2">&nbsp;</td>
				<td align="left">Parcela Unica</td>
			</tr>
			<tr>
				<td width="53px"  align="left">Até:</td>
				<td width="150px" align="left"><? db_inputdata('k02_dtdes4',@$k02_dtdes4_dia,@$k02_dtdes4_mes,@$k02_dtdes4_ano,true,'text',$db_opcao,"");  ?> </td>
				<td width="230px" align="left"><? db_input('k02_desco4',5,$Ik02_desco4,true,'text',$db_opcao,'')?>%</td>
				<td width="120px"  align="left">Desconto Integral:</td>
				<td align="left">
					<select name="k02_integr" id="k02_integr">
						<option value="f"
						<? echo isset($k02_integr)?($k02_integr=="f"?"selected":""):"" ?>>N&atilde;o</option>
						<option value="t"
						<? echo isset($k02_integr)?($k02_integr=="t"?"selected":""):"" ?>>Sim</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Até:</td>
				<td align="left"><? db_inputdata('k02_dtdes5',@$k02_dtdes5_dia,@$k02_dtdes5_mes,@$k02_dtdes5_ano,true,'text',$db_opcao,"");  ?> </td>
				<td align="left"><? db_input('k02_desco5',5,$Ik02_desco5,true,'text',$db_opcao,'')?>%</td>
				<td align="left">Desconto Após o Vencimento:</td>
				<td align="left">
					<select name="k02_caldes" id="k02_caldes">
						<option value="f"
						<? echo isset($k02_caldes)?($k02_caldes=="f"?"selected":""):"" ?>>N&atilde;o</option>
						<option value="t"
						<? echo isset($k02_caldes)?($k02_caldes=="t"?"selected":""):"" ?>>Sim</option>
					</select>
				</td>
			</tr>
			<tr>
				<td align="left">Até:</td>
				<td align="left"><? db_inputdata('k02_dtdes6',@$k02_dtdes6_dia,@$k02_dtdes6_mes,@$k02_dtdes6_ano,true,'text',$db_opcao,"");  ?> </td>
				<td align="left" colspan="2"><? db_input('k02_desco6',5,$Ik02_desco6,true,'text',$db_opcao,'')?>%</td>
			</tr>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td align="left" colspan="2">Outras Parcelas:</td>
			</tr>
			<tr>
				<td align="left">Até:</td>
				<td align="left"><? db_inputdata('k02_dtdes1',@$k02_dtdes1_dia,@$k02_dtdes1_mes,@$k02_dtdes1_ano,true,'text',$db_opcao,"");  ?> </td>
				<td align="left" colspan="2"><? db_input('k02_desco1',5,$Ik02_desco1,true,'text',$db_opcao,'')?>%</td>
			</tr>
			<tr>
				<td align="left">Até:</td>
				<td align="left"><? db_inputdata('k02_dtdes2',@$k02_dtdes2_dia,@$k02_dtdes2_mes,@$k02_dtdes2_ano,true,'text',$db_opcao,"");  ?> </td>
				<td align="left" colspan="2"><? db_input('k02_desco2',5,$Ik02_desco2,true,'text',$db_opcao,'')?>%</td>
			</tr>
			<tr>
				<td align="left">Até:</td>
				<td align="left"><? db_inputdata('k02_dtdes3',@$k02_dtdes3_dia,@$k02_dtdes3_mes,@$k02_dtdes3_ano,true,'text',$db_opcao,"");  ?> </td>
				<td align="left" colspan="2"><? db_input('k02_desco3',5,$Ik02_desco3,true,'text',$db_opcao,'')?>%</td>
			</tr>
		</table>
		</fieldset>
		</td>
	</tr>
</table>
</fieldset>

  <br />
  <input name="<?=($db_opcao==1?"incluir":($db_opcao==2||$db_opcao==22?"alterar":"excluir"))?>" type="submit" id="db_opcao" value="<?=($db_opcao==1?"Incluir":($db_opcao==2||$db_opcao==22?"Alterar":"Excluir"))?>">
  <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();" >

</form>
</center>
<script >
function js_pesquisa(){
   js_OpenJanelaIframe('', 'db_iframe_tabrecjm', 'func_tabrecjm.php?funcao_js=parent.js_mostratabrecjm1|k02_codjm|k02_corr', 'Pesquisa', true);
}

function js_mostratabrecjm1(chave1, chave2) {

  db_iframe_tabrecjm.hide();
  
  var opcao = <?=$db_opcao?>;  

  if(opcao == 1) {
    location.href = 'cai1_recejm005.php?chavepesquisa=' + chave1;
    return;
  }

  location.href = '<?php echo basename($GLOBALS["HTTP_SERVER_VARS"]["PHP_SELF"]); ?>?chavepesquisa=' + chave1;
}
</script>