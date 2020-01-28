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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("libs/db_app.utils.php");
include ("classes/db_ouvidor_classe.php");
include ("classes/db_db_depart_classe.php");
$clouvidor = new cl_ouvidor ( );
$cldepartamento = new cl_db_depart ( );

db_postmemory ( $HTTP_POST_VARS );

$db_opcao = 1;
$db_botao = true;
$iGrupo = 2; //2 proque esta na ouvidoria se protocolo = 1;


//Verifica se o usuário logado esta na tabela ouvidor;
$iCodUsuario = db_getsession ( 'DB_id_usuario' );
$iCodDeptoUsuario = db_getsession ( 'DB_coddepto' );
$lUsuarioOuvidor = false;
$rsUsuarioOuvidor = $clouvidor->sql_record ( $clouvidor->sql_query_file ( null, "*", null, "ov21_db_usuario = $iCodUsuario" ) );
if ($clouvidor->numrows > 0) {
	
	$lUsuarioOuvidor = true;
	/*
	 *verificar se limite for null ou maior que a data atual departamento ativo 
	 */
	$sWhere = " instit = " . db_getsession ( 'DB_instit' ) . "                                          ";
	$sWhere .= " and (limite is null or limite > '" . date ( 'y-m-d', db_getsession ( 'DB_datausu' ) ) . "') ";
	$rsDepartamentos = $cldepartamento->sql_record ( $cldepartamento->sql_query ( null, "coddepto,descrdepto", "descrdepto", $sWhere ) );
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<?
db_app::load ( 'strings.js,scripts.js,datagrid.widget.js,prototype.js' );
db_app::load ( 'estilos.css,grid.style.css' );
?>
<script type="text/javascript">
function js_pesquisap58_codigo(mostra){
	if (document.getElementById('p58_codigo').value == "" && mostra == false) {
	 
		document.getElementById('p58_codigo').value  = "";
		document.getElementById('p51_descr').value   = "";
	} else {
	  if (mostra == true) {
	  	js_OpenJanelaIframe('','db_iframe','func_tipoproc.php?funcao_js=parent.js_mostratipoproc1|0|1&grupo=<?=$iGrupo;?>','Pesquisa',true);
	  } else {
	  	js_OpenJanelaIframe('','db_iframe','func_tipoproc.php?pesquisa_chave='+document.form1.p58_codigo.value+'&funcao_js=parent.js_mostratipoproc&grupo=<?=$iGrupo;?>','Pesquisa',false);
	  }
	}
}
function js_pesquisaov01_numero(mostra) {

  if (document.getElementById('ov01_numero').value == "" && mostra == false) {
  
    document.getElementById('ov01_numero').value      = "";
    document.getElementById('ov01_solicitacao').value = "";
  } else {
    if (mostra == true) {
      js_OpenJanelaIframe('','db_iframe','func_ouvidoriaatendimento.php?funcao_js=parent.js_mostraatendimento|ov01_sequencial|ov01_requerente','Pesquisa',true);
    } else {
      js_OpenJanelaIframe('','db_iframe','func_ouvidoriaatendimento.php?pesquisa_chave='+document.form1.ov01_numero.value+'&requer=1&funcao_js=parent.js_mostraatendimentocomerro','Pesquisa',false);
    }
  }
}

function js_mostraatendimento(chave1, chave2) {

  document.form1.ov01_numero.value      = chave1;
  document.form1.ov01_solicitacao.value = chave2;
  db_iframe.hide();
}

function js_pesquisaov09_protprocesso(mostra) {

  if (document.getElementById('ov09_protprocesso').value == "" && mostra == false) {
  
    document.getElementById('ov09_protprocesso').value         = "";
    document.getElementById('ov09_ouvidoriaatendimento').value = "";
  } else {
    if (mostra == true) {
      js_OpenJanelaIframe('', 'db_iframe',
                          'func_processoouvidoria.php?'+
                          'funcao_js=parent.js_mostraprocesso|ov09_protprocesso|z01_nome',
                          'Pesquisar Processos de Ouvidoria',
                          true
                         );
    } else {
      js_OpenJanelaIframe('', 
                          'db_iframe',
                          'func_processoouvidoria.php?pesquisa_chave='+
                           document.form1.ov09_protprocesso.value+
                           '&filtrar=processo&funcao_js=parent.js_mostraprocessocomerro',
                           'Pesquisar Processos de Ouvidoria',
                           false);
    }
  }
}

function js_mostraprocessocomerro(valor, erro, titular) {

  
  if (arguments.length == 2 && arguments[1]) {
  
     document.form1.ov09_protprocesso.value         = '';
     document.form1.ov09_protprocesso.focus();
     document.form1.ov09_ouvidoriaatendimento.value = valor;
  } else { 
     document.form1.ov09_ouvidoriaatendimento.value = titular;
  }
}

function js_mostraprocesso(chave1, chave2) {
  document.form1.ov09_protprocesso.value         = chave1;
  document.form1.ov09_ouvidoriaatendimento.value = chave2;
  db_iframe.hide();
}

function js_mostraatendimentocomerro(valor, nome, erro) {
  
  if (arguments.length == 2 && arguments[1]) {
  
     document.form1.ov01_numero.value = '';
     document.form1.ov01_numero.focus();
     document.form1.ov01_solicitacao.value = valor;
  } else { 
     document.form1.ov01_solicitacao.value = nome;
  }
}

function js_mostratipoproc(chave,erro){
  document.form1.p51_descr.value = chave; 
  if (erro == true) { 
    document.form1.p58_codigo.focus(); 
    document.form1.p58_codigo.value = ''; 
  }  
}
function js_mostratipoproc1(chave1,chave2){
  document.form1.p58_codigo.value = chave1;
  document.form1.p51_descr.value  = chave2;
  db_iframe.hide();
}

function js_frmListaProcessos() {

	oDBGridListaProcessos = new DBGrid('processos');
	oDBGridListaProcessos.nameInstance = 'oDBGridListaProcessos';
	oDBGridListaProcessos.setHeader(new Array('','Número','Tipo de Processo','Requerente','Depto Atual','Data de Criação','Data Recebimento','Data Vencimento','Dias em Atraso'));
	oDBGridListaProcessos.setHeight(150);
	oDBGridListaProcessos.setCellAlign(new Array('center','center','left','left','left','center','center','center','center'));
	oDBGridListaProcessos.show($('listaProcessos'));	
}

function js_atualizar(){
	
	var p58_codigo         = $F('p58_codigo');
	var tipo               = $F('tipo');
	var strDataInicial     = $F('dt_inicio')!="" ? js_dataFormat($F('dt_inicio'),'b'):"";
	var strDataFim         = $F('dt_fim')   !="" ? js_dataFormat($F('dt_fim'),'b') : "";
	var iCodigoAtendimento = $F('ov01_numero');
	var iNumeroProcesso    = $F('ov09_protprocesso');
	var coddepto           = '';
	
	if($('coddepto')!=null){
		coddepto = $F('coddepto');
	}
	
	oPesquisar = new Object();
	
	oPesquisar.acao			= 'pesquisar';
	oPesquisar.processo	= new Array();
	oPesquisar.processo[0] = new Object();
	oPesquisar.processo[0].p58_codigo  	      = p58_codigo;
	oPesquisar.processo[0].tipo				 	      = tipo;
	oPesquisar.processo[0].dtinicial	 	      = strDataInicial;
	oPesquisar.processo[0].dtfim    	 	      = strDataFim;
	oPesquisar.processo[0].iCodigoAtendimento = iCodigoAtendimento;
	oPesquisar.processo[0].iNumeroProcesso    = iNumeroProcesso;
	oPesquisar.processo[0].p58_coddepto	      = coddepto;
	
	oDBGridListaProcessos.clearAll(true);
	oDBGridListaProcessos.renderRows();	
	
	var sDados = Object.toJSON(oPesquisar);
		
		js_divCarregando('Aguarde consultando dados do Processo...','msgBox');
	
		sUrl = 'ouv1_consprocessos.RPC.php';
		var sQuery = 'dados='+sDados;
		var oAjax   = new Ajax.Request( sUrl, {
                                            method: 'post', 
                                            parameters: sQuery, 
                                            onComplete: js_retornoAtualizaProcesso
                                          }
                                   );			
		
}

function js_retornoAtualizaProcesso(oAjax){
		
	js_removeObj("msgBox");
  
  var aRetorno = eval("("+oAjax.responseText+")");
  
  var sExpReg  = new RegExp('\\\\n','g');
  
  if ( aRetorno.status == 0) {
    alert(aRetorno.message.urlDecode().replace(sExpReg,'\n'));
    return false;
  }else{
  	js_PreencheListaProcesso(aRetorno.processos);
  } 
	
}

function js_PreencheListaProcesso(aProcessos){
 
	oDBGridListaProcessos.clearAll(true);
	
	var iNumRows = aProcessos.length;
	
	if(iNumRows > 0){
		aProcessos.each(
			function (oProcessos,iInd){

				var aRow		= new Array();
	      aRow[0] = "<a href='#' onClick='js_detalhesProcesso("+oProcessos.p58_codproc+");'>&nbsp;MI&nbsp;</a>";
				aRow[1] = oProcessos.p58_codproc;
				aRow[2] = oProcessos.p58_codigo+"-"+oProcessos.p51_descr.urlDecode();
				aRow[3] = oProcessos.p58_requer.urlDecode();
				aRow[4] = oProcessos.deptoatual.urlDecode();
				aRow[5] = js_dataFormat(oProcessos.p58_dtproc,'u');
				aRow[6] = js_dataFormat(oProcessos.p61_dtandam,'u');
				aRow[7] = js_dataFormat(oProcessos.ov15_dtfim,'u');
				if ( new Number(oProcessos.diasatraso) > 0 ) {
				  aRow[8] = oProcessos.diasatraso;
				} else {
				  aRow[8] = '';
				}
				  
				oDBGridListaProcessos.addRow(aRow);
				
	 		}	
				
			);
	}
	oDBGridListaProcessos.renderRows();	
}

function js_detalhesProcesso(iCodProcesso){
  js_OpenJanelaIframe('','db_iframe_detalhesProc','func_detalhesprocessoouvidoria.php?iCodProcesso='+iCodProcesso,'Detalhes Processo',true);
}

//Se o formato for b para o banco senao para usuario
function js_dataFormat(strData,formato){
	
	if(strData == "" || strData == null){
		return "";
	}
	
	if(formato=='b'){
		aData = strData.split('/');
		return  aData[2]+'-'+aData[1]+'-'+aData[0];
	}else{
		aData = strData.split('-');
		return  aData[2]+'/'+aData[1]+'/'+aData[0];
	}
}

function js_ImprimeProcesso(){
			
	if(oDBGridListaProcessos.getNumRows() == 0){
		alert('Usuário:\n\n Nenhum processo encontrado para emissão de relatorio!\n\nAdministrador:\n\n');
		return false;
	}
	
	var p58_codigo         = $F('p58_codigo');
	var tipo 					     = $F('tipo');
	var strDataInicial     = $F('dt_inicio')!="" ? js_dataFormat($F('dt_inicio'),'b'):"";
	var strDataFim		     = $F('dt_fim')   !="" ? js_dataFormat($F('dt_fim'),'b') : "";
	var sNumeroAtendimento = $F('ov01_numero');
	var coddepto			     = '';
	
	if($('coddepto')!=null){
		coddepto = $F('coddepto');
	}

	var query  = 'p58_codigo='+p58_codigo;
	query 		+= '&tipo='+tipo;
	query 		+= '&dtini='+strDataInicial;
	query 		+= '&dtfim='+strDataFim;
	query 		+= '&p58_coddepto='+coddepto;
	query     += '&ov01_numero='+sNumeroAtendimento;

	jan = window.open('ouv1_consprocessos002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0" onLoad="js_frmListaProcessos()">
<table width="100%" border="0" cellspacing="0" cellpadding="0"
	style="margin-top: 20px;">
	<tr align="center">
		<td height="430" align="left" valign="top" bgcolor="#CCCCCC">
		<center>
		<form action="" name="form1">
		<table width="680" style="margin-top: 20px;">
			<tr>
				<td>
				<fieldset><legend><b>Consulta Processos</b></legend>

				<table>
					<tr>
						<td align="left"><b>Tipo:</b></td>
						<td align="left">
						<?
						$x = array ('0' => 'Todos', '1' => 'Em Andamento', '2' => 'Em Atraso' );
						db_select ( 'tipo', $x, true, 1 );
						?>
						</td>
					</tr>
					<tr>
						<td align="left"><b>Data de Criação:</b></td>
						<td align="left">
						<?
						db_inputdata ( 'dt_inicio', '', '', '', true, 'text', 1 );
						echo "&nbsp;à&nbsp;";
						db_inputdata ( 'dt_fim', '', '', '', true, 'text', 1 );
						?>
						</td>
					</tr>
					<tr>
						<td>
		      	  <?
											db_ancora ( '<b>Tipo de Processo:</b>', "js_pesquisap58_codigo(true);", "" );
											?>
		    		</td>
						<td> 
						  <?
								$p58_codigo = null;
								$p51_descr = '';
								db_input ( 'p58_codigo', 5, 1, true, 'text', 1, " onchange='js_pesquisap58_codigo(false);'" );
								db_input ( 'p51_descr', 50, 0, true, 'text', 3, '' );
								?>
		    		</td>
					</tr>
					<tr>
						<td>
					    <?
									db_ancora ( '<b>Sequencial do Atendimento:</b>', "js_pesquisaov01_numero(true);", "" );
									?>
					  </td>
						<td>
					    <?
									db_input ( 'ov01_numero', 5, 1, true, 'text', 1, " onchange='js_pesquisaov01_numero(false);'" );
									db_input ( 'ov01_solicitacao', 50, 0, true, 'text', 3, '' );
									?>
					  </td>
					</tr>

					<tr>
					  <td>
					    <?
					      db_ancora('<b>Número do Processo:</b>', 'js_pesquisaov09_protprocesso(true);','')
					    ?>
					  </td>
					  <td>
					    <?
							  db_input('ov09_protprocesso', 5, 1, true, 'text', 1, " onchange='js_pesquisaov09_protprocesso(false);'");
							  db_input('ov09_ouvidoriaatendimento', 50, 0, true, 'text', 3, '');
							?>
					  </td>
					</tr>

					<?
					if ($lUsuarioOuvidor) {
						?>
							<tr>
						<td align="left"><b>Departamento:</b></td>
						<td align="left">
									<?
						db_selectrecord ( 'coddepto', $rsDepartamentos, true, 1, '', '', '', "0" );
						?>
								</td>
					</tr>
						  <?
					} else {
						?>
					<input type="hidden" value="<?=$iCodDeptoUsuario?>" name="coddepto"
						id="coddepto">
					<?
					}
					?>
				</table>

				</fieldset>
				</td>
			</tr>
			<tr align="center">
				<td><input name="atualizar" type="button" id="atualizar"
					value="Atualizar" onclick="js_atualizar();"> <input name="imprimir"
					type="button" id="imprimir" value="Imprimir"
					onclick="js_ImprimeProcesso();"></td>
			</tr>
			<tr>
				<td>
				<fieldset><legend><b>Lista de Processos</b></legend>
				<div id="listaProcessos"></div>
				</fieldset>
				</td>
			</tr>
		</table>
		</form>
		</center>
		</td>
	</tr>
</table>
<?
db_menu ( db_getsession ( "DB_id_usuario" ), db_getsession ( "DB_modulo" ), db_getsession ( "DB_anousu" ), db_getsession ( "DB_instit" ) );
?>
</body>
</html>