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

require_once("std/db_stdClass.php");
require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_utils.php");
require_once("libs/db_app.utils.php");
require_once("libs/db_usuariosonline.php");
require_once("libs/JSON.php");
require_once("dbforms/db_funcoes.php");
require_once("dbforms/db_classesgenericas.php");

require_once("libs/db_app.utils.php");
require_once("classes/db_ouvidor_classe.php");
require_once("classes/db_db_depart_classe.php");


$clouvidor 			= new cl_ouvidor();
$cldepartamento 	= new cl_db_depart();

db_postmemory($HTTP_POST_VARS);

$db_opcao = 1;
$db_botao = true;
$iGrupo		= 2; //2 proque esta na ouvidoria se protocolo = 1;

//Verifica se o usuário logado esta na tabela ouvidor;
$iCodUsuario				=	db_getsession('DB_id_usuario');
$iCodDeptoUsuario  	=	db_getsession('DB_coddepto');
$lUsuarioOuvidor		= false;
$rsUsuarioOuvidor = $clouvidor->sql_record($clouvidor->sql_query_file(null,"*",null,"ov21_db_usuario = $iCodUsuario"));
if($clouvidor->numrows > 0 ){
	$lUsuarioOuvidor	=	true;
	//verificar se limite for null ou maior que a data atual departamento ativo
	
	$sWhere = "instit = ".db_getsession('DB_instit')." and (limite is null or limite > '".date('y-m-d',db_getsession('DB_datausu'))."')";
	
	$rsDepartamentos = $cldepartamento->sql_record($cldepartamento->sql_query(null,"coddepto,descrdepto","descrdepto",$sWhere));
}

$oDaoSituacaoAtendimento = db_utils::getDao('situacaoouvidoriaatendimento');
$sSqlSituacaoAtendimento = $oDaoSituacaoAtendimento->sql_query_file(null, "*", null);
$rsSituacaoAtendimento   = $oDaoSituacaoAtendimento->sql_record($sSqlSituacaoAtendimento);

if ($oDaoSituacaoAtendimento->numrows > 0) {
	
  //monta o array para o select
	$aSituacaoAtendimento = array();
  //qualquer tipo de Situação, sem filtros na pesquisa
  $aSituacaoAtendimento[0] = "Qualquer";
  for ( $i = 0 ; $i < $oDaoSituacaoAtendimento->numrows;$i++) {
  	
  	$oDadoSituacaoAtendimento = db_utils::fieldsMemory($rsSituacaoAtendimento, $i);
    $aSituacaoAtendimento[$oDadoSituacaoAtendimento->ov18_sequencial] = urldecode($oDadoSituacaoAtendimento->ov18_descricao);
  }
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<? 
	db_app::load('strings.js,scripts.js,datagrid.widget.js,prototype.js');
	db_app::load('estilos.css,grid.style.css');
?>
<script type="text/javascript">

function js_ImprimeProcesso(){

	var ordenacao   		= $F('ordenacao');
	var quebra		 			= $F('quebra');
	var strDataInicial	= $F('dt_ini');
	var strDataFim			= $F('dt_fim');
	var ouvidoria				= $F('ouvidoria');
  var situacaoAtendimento = $F('situacaoatendimento');

	
	if($('tipoProcesso').checked == true){
		var tipoProcesso		= 'S';
	}
	if($('formaReclamacao').checked == true){
		var formaReclamacao	= 'S';
	}
	if($('situacao').checked == true){
		var situacao				= 'S';
	}
	if($('ouvidor').checked == true){
		var ouvidor					= 'S';
	}
	if($F('ouvidoria')==0){
		var ouvidoria				= $F('deptoAtual');
	}
					
	var query  = 'ordenacao='+ordenacao;
	query 		+= '&quebra='+quebra;
	query 		+= '&ouvidoria='+ouvidoria;
	query 		+= '&dtini='+strDataInicial;
	query 		+= '&dtfim='+strDataFim;
	query 		+= '&tipoprocesso='+tipoProcesso;
	query 		+= '&formareclamacao='+formaReclamacao;
	query 		+= '&situacao='+situacao;
	query 		+= '&ouvidor='+ouvidor;
	query     += '&situacaoatendimento='+situacaoAtendimento;

//	alert(query);
//	return false;
	
	jan = window.open('ouv1_relatendimentos002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0"
	marginheight="0" onLoad="a=1">
<table width="100%" border="0" cellspacing="0" cellpadding="0"
	style="margin-top: 20px;">
	<tr align="center">
		<td height="430" align="left" valign="top" bgcolor="#CCCCCC">
		<center>
		<form action="" name="form1">
		<table width="420" style="margin-top: 20px;">
			<tr>
				<td>
				<fieldset><legend><b>Relatório de Atendimentos</b></legend>

				<table>
					<tr>
						<td align="left"><b>Período:</b></td>
						<td align="left">
						<? 
							db_inputdata('dt_ini','','','',true,'text',1);
							echo "&nbsp;à&nbsp;";
							db_inputdata('dt_fim','','','',true,'text',1);
						?>
						</td>
					</tr>
					<tr>
						<td align="left"><b>Ouvidoria:</b></td>
						<td align="left">
						<? 
							$x = array(0=>"Atual",1=>"Todos");
							db_select('ouvidoria',$x,true,1);
						?>
						</td>
					</tr>
					<tr>
						<td align="left"><b>Quebras:</b></td>
						<td align="left">
						<? 
							$x = array(0=>"Tipo de Processo",1=>"Ouvidor",2=>"Destino");
							db_select('quebra',$x,true,1);
						?>
						</td>
					</tr>
					
					<tr>
						<td align="left"><b>Ordenação:</b></td>
						<td align="left">
						<? 
							$x = array(0=>"Código do Atendimento",1=>"Situação");
							db_select('ordenacao',$x,true,1);
						?>
						</td>
					</tr>
					
					
					<tr>
						<td align="left"><b>Situação:</b></td>
						<td align="left">
						<? 
					
						db_select('situacaoatendimento',$aSituacaoAtendimento,true,1);
						?>
						</td>
					</tr>
					
					
					
					<tr>
						<td colspan="2">
							<fieldset><legend><b>Totalizadores</b></legend> 
									<table>
										<tr>
											<td width="60">&nbsp;</td>
											<td>
												<input type="checkbox" id="tipoProcesso" name="tipoProcesso">&nbsp;<b>Tipo de Processo</b>
											</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td>
												<input type="checkbox" id="formaReclamacao" name="formaReclamacao">&nbsp;<b>Forma de Reclamação</b>
											</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td>
												<input type="checkbox" id="situacao" name="situacao">&nbsp;<b>Situação</b>
											</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td>
												<input type="checkbox" id="ouvidor" name="ouvidor">&nbsp;<b>Ouvidor</b>
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
			<tr align="center">
				<td>
					<input type="hidden" name="deptoAtual" id="deptoAtual" value="<?= db_getsession('DB_coddepto') ?>"> 
					<input name="imprimir" type="button" id="imprimir" value="Imprimir"	onclick="js_ImprimeProcesso();">
				</td>
			</tr>
			
		</table>
		</form>
		</center>
		</td>
	</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>