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

include_once ("fpdf151/pdf.php");

include_once ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("libs/db_jsplibwebseller.php");
include ("libs/db_utils.php");
require ("libs/db_app.utils.php");

include ("dbforms/db_funcoes.php");
include ("classes/db_sau_fechamento_classe.php");
include ("classes/db_sau_atualiza_classe.php");

db_postmemory ( $HTTP_POST_VARS );

$clsau_atualiza = new cl_sau_atualiza ( );
$clsau_atualiza->rotulo->label ();

$db_opcao = isset ( $enabled_ver ) && $enabled_ver == "true" ? 1 : 3;
$db_botao = true;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?php
db_app::load ( "scripts.js" );
db_app::load ( "prototype.js" );
db_app::load ( "datagrid.widget.js" );
db_app::load ( "strings.js" );
db_app::load ( "grid.style.css" );
db_app::load ( "estilos.css" );
?>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0" onLoad="a=1">
<table width="100%" border="0" cellpadding="0" cellspacing="0"
	bgcolor="#5786B2">
	<tr>
		<td width="360" height="18">&nbsp;</td>
		<td width="263">&nbsp;</td>
		<td width="25">&nbsp;</td>
		<td width="140">&nbsp;</td>
	</tr>
</table>
<center>

<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td align="left" valign="top" bgcolor="#CCCCCC"><br>
		<form name="form1" method="post" action=""
			enctype="multipart/form-data">
		<center>
		<fieldset><legend><b>Atualização SIA</b></legend>
		<table border="0" align="left">
			<tr>
				<td nowrap title="<?=@$Ts100_i_mescom?>"><b>
				    <? db_ancora ( "Competencia", "js_pesquisas100_i_mescomp(true);", 1 )?>
				</td>
				<td>
				   <?
					db_input ( 's100_i_codigo', 10, @$Is100_i_codigo, true, 'hidden', 3 );
					db_input ( 's100_i_mescomp', 2, @$Is100_i_mescomp, true, 'text', 3 );
					db_input ( 's100_i_anocomp', 4, @$Is100_i_anocomp, true, 'text', 3 );
					?>
		         </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Ts100_i_login?>"><b>
					<?=$Ls100_i_login ?>
				</td>
				<td>
					<?db_input ( 's100_i_login', 10, @$Is100_i_login, true, 'text', 3 )?>
		         </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Ts100_d_data?>"><b>
					<?=$Ls100_d_data ?>
				</td>
				<td>
					<? db_inputdata ( 's100_d_data', @$s100_d_data_dia, @$s100_d_data_mes, @$s100_d_data_ano, true, 'text',3 ) ?>
		         </td>
			</tr>
			<tr>
				<td nowrap title="<?=@$Ts100_c_hora?>"><b>
					<?=$Ls100_c_hora ?>
				</td>
				<td>
					<?db_input ( 's100_c_hora', 5, @$Is100_c_hora, true, 'text', 3 )?>
		         </td>
			</tr>

		</table>
		</fieldset>

		<table border="0">
			<tr>
				<td height="18">&nbsp;</td>
				<td height="18">&nbsp;</td>
			</tr>
			<tr>
				<td>
					<input name="remover" 
						type="button" 
						id="processar"
						onclick="js_remover()"
						value="Remover">
				</td>
			</tr>
		</table>
		
		</form>
		</center>
		</td>
	</tr>
</table>
</center>
     <?
					db_menu ( db_getsession ( "DB_id_usuario" ), db_getsession ( "DB_modulo" ), db_getsession ( "DB_anousu" ), db_getsession ( "DB_instit" ) );
					?>
  </body>
</html>

<script>
	strURL = 'sau4_atualizacaoRPC.php';
	/**
	 * Ajax$F('s100_i_anocomp')
	 */
	function js_ajax( objParam, strCarregando, jsRetorno ){ 
	
	var objAjax = new Ajax.Request(
	                         strURL, 
	                         {
	                          method    : 'post', 
	                          parameters: 'json='+Object.toJSON(objParam),
	                          onCreate  : function(){
	                          				js_divCarregando( strCarregando, 'msgbox');
	                          			},
	                          onComplete: function(objAjax){
	                          				var evlJS = jsRetorno+'( objAjax )';
	                          				js_removeObj('msgbox');
	                          				eval( evlJS );
	                          			}
	                         }
	                        );
	
	}

	function js_pesquisas100_i_mescomp(mostra){
		var strParam = '';
		strParam += 'func_sau_atualiza.php';
		strParam += '?funcao_js=parent.js_mostrasau_atualiza1|s100_i_codigo|login|s100_d_data|s100_c_hora|s100_i_mescomp|s100_i_anocomp';
		strParam += '&campos=sau_atualiza.*, login';
        if(mostra==true){
           js_OpenJanelaIframe('top.corpo','db_iframe_sau_atualiza',strParam,'Pesquisa',true);
        }
    }
    function js_mostrasau_atualiza1(s100_i_codigo,login,s100_d_data,s100_c_hora,s100_i_mescomp,s100_i_anocomp){
    	$('s100_i_codigo').value  = s100_i_codigo;
    	$('s100_i_login').value   = login;
    	$('s100_d_data').value    = s100_d_data;
    	$('s100_c_hora').value    = s100_c_hora
        $('s100_i_mescomp').value = s100_i_mescomp;
        $('s100_i_anocomp').value = s100_i_anocomp;
        db_iframe_sau_atualiza.hide();

    }
	/**
	 * Função remover
	 */
	function js_remover(){
		if( $F('s100_i_codigo') == '' ){
			alert('Informe uma competência para remover');
		}else if( confirm("Comfirma remoção da competência "+$F('s100_i_mescomp')+"/"+$F('s100_i_anocomp') ) ){
			var objParam            = new Object();
			objParam.exec           = "getCompetencia";
			objParam.s100_i_codigo  = $F('s100_i_codigo');
			objParam.s100_i_mescomp = $F('s100_i_mescomp');
			objParam.s100_i_anocomp = $F('s100_i_anocomp');
	  		
			js_ajax( objParam, 'Aguarde, Pesquisando....', 'js_retornoRemover' );			
		}
	}
	/**
	 * Retorno Lanca
	 */
	function js_retornoRemover( objAjax ){
		var objRetorno = eval("("+objAjax.responseText+")");
	
		if (objRetorno.status == 1) {		  
		}
		alert(objRetorno.message.urlDecode());
	
	} 
	
</script>