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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
db_postmemory($HTTP_POST_VARS);
$aux = new cl_arquivo_auxiliar;
$rotulo = new rotulocampo();
$rotulo->label("m60_codmater");
$rotulo->label("m60_descr");
$rotulo->label("m81_codtipo");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="800" align="center">
<tr>
<td>
<fieldset>
<legend><b>Material</b></legend>
<form name="form1" method="post" action="">
		<table    align="center">
		<tr>
			<td nowrap title="<?=@$Tm60_codmater?>" align="right">
				<?
				db_ancora(@$Lm60_codmater,"js_pesquisam60_codmater(true);",1);
				?>
			</td>
			<td> 
				<?
				db_input('m60_codmater',10,$Im60_codmater,true,'text',1," onchange='js_pesquisam60_codmater(false);'")
				?>
				<?
				db_input('m60_descr',40,$Im60_descr,true,'text',3,'')
				?>
			</td>
		</tr>
		<tr>
			<td align='right' ></td>
			<td></td>
		</tr>
		<tr>
		<td colspan=2 >
		<?
		$aux->cabecalho = "<strong>Tipo de Mov.</strong>";
		$aux->codigo = "m81_codtipo"; //chave de retorno da func
		$aux->descr  = "m81_descr";   //chave de retorno
		$aux->nomeobjeto = 'tipos';
		$aux->funcao_js = 'js_mostra';
		$aux->funcao_js_hide = 'js_mostra1';
		$aux->sql_exec  = "";
		$aux->func_arquivo = "func_matestoquetipo.php";  //func a executar
		$aux->nomeiframe = "db_iframe_matestoquetipo";
		$aux->localjan = "";
		$aux->onclick = "";
		$aux->db_opcao = 2;
		$aux->tipo = 2;
		$aux->top = 0;
		$aux->linhas = 10;
		$aux->vwhidth = 400;
		//$aux->funcao_gera_formulario();
		?>
		</td>
		</tr>
		
		<tr>
		<td align="right">
		<b> Período: </b>
		</td>
		<td>
		<? 
		db_inputdata('data1','','','',true,'text',1,"");   		          
		echo "<b> a</b> ";
		db_inputdata('data2','','','',true,'text',1,"");
		?>
		&nbsp;
		</td>
		</tr>
		<tr>
		<td colspan="2" align = "center"> 
		<input  name="emite2" id="emite2" type="button" value="Processar" onclick="js_mandadados();" >
		</td>
		</tr>
		</table>
</form>
</fieldset>
</td>
</tr>
</table>

</body>
</html>
<script>
function js_mandadados(){

  if (document.form1.m60_codmater.value==""){
    alert('Informe um Material!!Campo vazio!!');
    document.form1.m60_codmater.focus();
	//return false;
 }
  listatipo = "";
  listadepto = parent.iframe_g2.js_retorna_chaves().replace(/#/g,",");
  qry='codmater='+document.form1.m60_codmater.value+'&listatipo='+listatipo+'&listadepto='+listadepto+'&data='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value+'&data1='+document.form1.data2_ano.value+'-'+document.form1.data2_mes.value+'-'+document.form1.data2_dia.value+'&vertipo=&verdepto='+parent.iframe_g2.document.form1.ver.value;
  jan = window.open('mat2_controlest002.php?'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_pesquisam60_codmater(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_matmater','func_matmater.php?funcao_js=parent.js_mostramatmater1|m60_codmater|m60_descr','Pesquisa',true,0);
  }else{
    if(document.form1.m60_codmater.value != ''){ 
      js_OpenJanelaIframe('','db_iframe_matmater','func_matmater.php?pesquisa_chave='+document.form1.m60_codmater.value+'&funcao_js=parent.js_mostramatmater','Pesquisa',false);
    }else{
      document.form1.m60_descr.value = ''; 
    }
  }
}
function js_mostramatmater(chave,erro){
  document.form1.m60_descr.value = chave; 
  if(erro==true){ 
    document.form1.m60_codmater.focus(); 
    document.form1.m60_codmater.value = ''; 
  }
}
function js_mostramatmater1(chave1,chave2){
  document.form1.m60_codmater.value = chave1;
  document.form1.m60_descr.value = chave2;
  db_iframe_matmater.hide();
}
</script>