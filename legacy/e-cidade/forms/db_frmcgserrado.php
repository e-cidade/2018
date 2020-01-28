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

//MODULO: protocolo
include ("dbforms/db_classesgenericas.php");
include ("classes/db_sau_cgscorreto_classe.php");

$cliframe_alterar_excluir = new cl_iframe_alterar_excluir ( );
$clcgserrado->rotulo->label ();
$clrotulo = new rotulocampo ( );
$clcgscorreto = new cl_sau_cgscorreto ( );
$clrotulo->label ( "s127_i_numcgs" );
$clrotulo->label ( "z01_v_nome" );

parse_str ( $HTTP_SERVER_VARS ["QUERY_STRING"] );
db_postmemory ( $HTTP_POST_VARS );

if (isset ( $opcao ) && $opcao == "alterar") {
	echo "<script>parent.iframe_cgserrado.location.href='sau4_cgserrado002.php?chavepesquisa=$s128_i_codigo&chavepesquisa1=$s128_i_numcgs'</script>";
}
if (isset ( $opcao ) && $opcao == "excluir") {
	echo "<script>parent.iframe_cgserrado.location.href='sau4_cgserrado003.php?chavepesquisa=$s128_i_codigo&chavepesquisa1=$s128_i_numcgs'</script>";
}
?>
<form name="form1" method="post" action="">
<center>
<table border="0">
	<tr>
		<td nowrap title="<?=@$Ts128_i_codigo?>">
       <?
							db_ancora ( @$Ls128_i_codigo, "js_pesquisas128_i_codigo(true);", 3 );
							?>
    </td>
		<td> 
<?
db_input ( 's128_i_codigo', 10, $Is128_i_codigo, true, 'text', 3, " onchange='js_pesquisas128_i_codigo(false);'" );
?>
       <?
							db_input ( 's127_i_numcgs', 8, $Is127_i_numcgs, true, 'hidden', 3, '' );
							echo "<script>js_OpenJanelaIframe('','db_iframe_cgscorreto','func_sau_cgscorreto.php?pesquisa_chave='+document.form1.s128_i_codigo.value+'&funcao_js=parent.js_mostracgscorreto','Pesquisa',false);</script>";
							?>
    </td>
	</tr>
	<tr>
		<td nowrap title="<?=@$Ts128_i_numcgs?>">
       <?
							db_ancora ( @$Ls128_i_numcgs, "js_pesquisas128_i_numcgs(true);", $db_opcao );
							?>
    </td>
		<td> 
<?
db_input ( 's128_i_numcgs', 8, $Is128_i_numcgs, true, 'text', $db_opcao, " onchange='js_pesquisas128_i_numcgs(false);'" );
if ($db_opcao == 2) {
	db_input ( 's128_i_numcgs', 8, $Is128_i_numcgs, true, 'hidden', 3, '', 's128_i_numcgs_old' );
	echo "<script>document.form1.s128_i_numcgs_old.value='$s128_i_numcgs'</script>";
}
?>
       <?
							db_input ( 's128_v_nome', 40, $Is128_v_nome, true, 'text', 3, '' )?>
    </td>
	</tr>
	<tr>
		<td align="center" colspan="2"><input name="db_opcao" type="submit"
			id="db_opcao"
			value="<?=($db_opcao == 1 ? "Incluir" : ($db_opcao == 2 || $db_opcao == 22 ? "Alterar" : "Excluir"))?>"
			<?=($db_botao == false ? "disabled" : "")?>></td>
	</tr>
	<tr>
		<td align="top" colspan="2">
   <?
			$sql = "select * from sau_cgserrado where s128_i_codigo=$s128_i_codigo";
			//$sql="$clcgserrado->sql_query("","","cgserrado.s128_||_numcgs,cgs.z01_nome,cgserrado.s128_||_codigo",""," cgserrado.s128_||_codigo = ".@$s128_||_codigo."")";
			//die($sql);
			//die($clcgserrado->sql_query("","","cgserrado.s128_||_numcgs,cgs.z01_nome,cgserrado.s128_||_codigo",""," cgserrado.s128_||_codigo = ".@$s128_||_codigo.""));
			$chavepri = array ("s128_i_codigo" => @$s128_i_codigo, "s128_i_numcgs" => @$s128_i_numcgs );
			$cliframe_alterar_excluir->chavepri = $chavepri;
			$cliframe_alterar_excluir->campos = "s128_i_codigo,s128_i_numcgs,s128_v_nome";
			$cliframe_alterar_excluir->sql = $sql;
			$cliframe_alterar_excluir->legenda = "cgs's";
			$cliframe_alterar_excluir->msg_vazio = "<font size='1'>Nenhum cgs Cadastrado!</font>";
			$cliframe_alterar_excluir->textocabec = "darkblue";
			$cliframe_alterar_excluir->textocorpo = "black";
			$cliframe_alterar_excluir->fundocabec = "#aacccc";
			$cliframe_alterar_excluir->fundocorpo = "#ccddcc";
			$cliframe_alterar_excluir->iframe_height = "170";
			$cliframe_alterar_excluir->iframe_alterar_excluir ( 1 );
			?>
   </td>
	</tr>
</table>
</center>
</form>
<script>
function js_pesquisas128_i_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sau_cgscorreto','func_sau_cgscorreto.php?funcao_js=parent.js_mostracgscorreto1|s127_i_codigo|s127_i_numcgs','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_sau_cgscorreto','func_sau_cgscorreto.php?pesquisa_chave='+document.form1.s128_i_codigo.value+'&funcao_js=parent.js_mostracgscorreto','Pesquisa',false);
  }
}
function js_mostracgscorreto(erro,chave){
  document.form1.s127_i_numcgs.value = chave; 
  if(erro==true){ 
    document.form1.s128_i_codigo.focus(); 
    document.form1.s128_i_codigo.value = ''; 
  }
}
function js_mostracgscorreto1(chave1,chave2){
  document.form1.s128_i_codigo.value = chave1;
  document.form1.s127_i_numcgs.value = chave2;
  db_iframe_sau_cgscorreto.hide();
}
function js_pesquisas128_i_numcgs(mostra){
  if(document.form1.s128_i_numcgs.value == document.form1.s127_i_numcgs.value){
    alert('Você não pode utilizar o mesmo número do cgs correto!');
    document.form1.s128_i_numcgs.value = '';
    document.form1.s128_i_numcgs.focus = '';
  }else{
    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?funcao_js=parent.js_mostracgs1|z01_i_cgsund|z01_v_nome','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('','db_iframe_cgs_und','func_cgs_und.php?pesquisa_chave='+document.form1.s128_i_numcgs.value+'&funcao_js=parent.js_mostracgs','Pesquisa',false);
    }
  }
}
function js_mostracgs(erro,chave){
  document.form1.s128_v_nome.value = chave; 
  if(erro==true){ 
    document.form1.s128_i_numcgs.focus(); 
    document.form1.s128_i_numcgs.value = ''; 
  }
}
function js_mostracgs1(chave1,chave2){	
  document.form1.s128_i_numcgs.value = chave1;
  document.form1.s128_v_nome.value = chave2;
  db_iframe_cgs_und.hide();
}
</script>