<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

	<form name="form1" method="post" action="">
	<table align="center" border="0">
		<tr>
			<td colspan="2" >
				<fieldset><legend><b>Atendimento Médico</b></legend>
					<table border="0">
						<tr>
							<td colspan=2 >
								<?
								// $aux = new cl_arquivo_auxiliar;
								$aux->Labelancora    = "Profissional:";
								$aux->cabecalho      = "Profissional";
								$aux->codigo         = "sd03_i_codigo"; //chave de retorno da func
								$aux->descr          = "z01_nome";   //chave de retorno
								$aux->nomeobjeto     = 'profissional';
								$aux->funcao_js      = 'js_mostra';
								$aux->funcao_js_hide = 'js_mostra1';
								$aux->sql_exec       = "";
								$aux->func_arquivo   = "func_medicosclassegenericas.php";//?chave_sd06_i_unidade=".db_getsession("DB_coddepto");  //func a executar
								$aux->nomeiframe     = "db_iframe_medicos";
								$aux->localjan       = "";
								$aux->onclick        = "";
								$aux->db_opcao       = 2;
								$aux->tipo           = 2;
								$aux->top            = 0;
								$aux->linhas         = 10;
								$aux->vwidth         = 400;	
								$aux->tamanho_campo_descricao = 47;				
								$aux->funcao_gera_formulario();
								?>
							</td>
						</tr>
						<tr>
							<td> <strong>Opção:</strong>
								<select name="ver" style="width: 250px;">
									<option name="condicao1" value="com">Com Profissional</option>
									<option name="condicao1" value="sem">Sem Profissional</option>
								</select>
							</td>
						</tr>
						
						<tr>
							<td valign="top">
								<b>Período</b>
								<?db_inputdata('data1',@$dia1,@$mes1,@$ano1,true,'text',1,"")?>
							        A
							  <?db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"")?>								
							</td>
							<td width="40%">
								<fieldset><legend>Filtros</legend>
								<table>
									<tr>
										<td><b>Quebra:</b></td>
										<td>
											<select name="quebra" >
												<option name="condicao1" value="ups">UPS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
												<option name="condicao1" value="nhu">Nenhum&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
											</select>							
										</td>
									</tr>
									<tr>
										<td><b>Ordem:</b></td>
										<td>
											<select name="ordem" >
												<option name="condicao1" value="n">Numérica&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
												<option name="condicao1" value="a">Alfabética&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
											</select>							
										</td>						
									</tr>
								</table>
								</fieldset>
							</td>
						</tr>
						
						
					</table>				
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<center><input type="button" name="imprimir" value="&nbsp;&nbsp;&nbsp;Imprimir&nbsp;&nbsp;&nbsp;" onclick="js_imprimir()"></center>
			</td>
		</tr>
	</table>
	</form>
</body>
</html>
<script>
function js_testord(valor){	
	if (valor=='S'){
		document.form1.ordem.value='b';
		document.form1.ordem.disabled=true;
	}else{
		document.form1.ordem.value='a';
		document.form1.ordem.disabled=false;
	}
}
function js_imprimir(){
	var obj=document.form1;
	var query="";
	var vir="";
	var listaprof="";
	var listaups="";
	
	for(x=0;x<obj.profissional.length;x++){
		listaprof += vir+obj.profissional.options[x].value;
		vir=",";
	}
	
//	if( obj.ver.value == "com" && listaprof == "" ){
//		alert('Foi selecionado opção [Com Profissional], mas não foi lançado nenhum profissional.');
//		return false;
//	}

	
//	if( obj.data1.value == "" || obj.data2.value == "" ){
//		alert('Não foi informado período.');
//		obj.data1.focus();
//		return false;
//	}
	
	//UPS
	vir="";
	for(x=0;x<parent.iframe_a2.document.form1.ups.length;x++){
		listaups+=vir+parent.iframe_a2.document.form1.ups.options[x].value;
		vir=",";
	}
//	if( parent.iframe_a2.document.form1.ver.value == "com" && listaups == "" ){
//		alert('Foi selecionado opção [Com UPS], mas não foi lançado nenhuma UPS.');
//		parent.mo_camada('a2');
//		return false;
//	}
	
 	query  = 'sau2_atendimentomedico002.php';
 	query += '?listaprof='+listaprof;
 	query += '&listaups='+listaups;
 	query += '&verprof='+obj.ver.value;
 	query += '&verups='+parent.iframe_a2.document.form1.ver.value;
 	query += '&ordem='+obj.ordem.value;
 	query += '&quebra='+obj.quebra.value;
 	query += '&data1='+obj.data1_ano.value+'/'+obj.data1_mes.value+'/'+obj.data1_dia.value;
 	query += '&data2='+obj.data2_ano.value+'/'+obj.data2_mes.value+'/'+obj.data2_dia.value;
 	
 	jan = window.open(query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
 	jan.moveTo(0,0);
 
}
</script>