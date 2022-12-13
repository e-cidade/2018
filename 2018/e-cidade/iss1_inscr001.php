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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>
function js_relatorio(){
	
	// abre o arquivo que gera o relat�rio passando os parametros 
	// configurados por $_GET
	var pess = document.getElementById("pessoa").value;
	var ativ = document.getElementById("atividade").value;
	var baix = document.getElementById("baixada").value;
	var ord  = document.getElementById("ordem").value;
	var sUrl = 'iss1_inscr002.php?pessoa='+pess+'&atividade='+ativ+'&baixa='+baix+'&ordem='+ord;	
  jan = window.open(sUrl,'',
									  'width='+(screen.availWidth-5)+
									  ',height='+(screen.availHeight-40)+', scrollbars=1, location=0'
									 );
  jan.moveTo(0,0);
}

function js_removeElementSelect() {
	/*
	 * se o usu�rio selecionar no select Atividade a op��o todas
	 * exclui do select Ordem a posi��o que cont�m a op��o Atividade
	 */
	if (document.getElementById("atividade").value == "t") {
		document.getElementById("ordem").remove(2);
	} else {
	  /*
		* se o usu�rio n�o selecionar a op��o todos no select Atividade
		* mant�m o select Ordem com todas suas propriedades iniciais
		*/
		if (document.getElementById("atividade").value != "t") 
			document.getElementById("ordem").add(new Option("Atividade", "a"), null);
	}
}

</script>

<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table height="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	<tr><td height="5"></td></tr>
	<tr>
		<td height="430" valign="top">
			<fieldset style="margin-top:15px;">
				<legend> <b> Relat�rio de Inscri��o </b> </legend>
				<form name="form1" method="post" action="pro1_relcgm002.php" target="rel">
				<fieldset>
					<legend> <b> Filtrar por </b> </legend>
					<table>
						
						<tr>
							<td> <b> Pessoa </b> </td>
							<td>
							  <?
								  $aPessoa = array ("j" => "Jur�dica", "f" => "F�sica", "t" => "Todas");
								  db_select("pessoa", $aPessoa, null, 1); 
							  ?>
							</td> 
						</tr>
						
						<tr>		
							<td> <b> Baixada </b> </td>
							<td>
							  <?
								  $aBaixada = array ("n" => "N�o", "s" => "Sim", "t" => "Todas");
								  db_select("baixada", $aBaixada, null, 1); 
							  ?>
							</td> 
						</tr>
					
						<tr>	
							<td> <b> Atividade </b> </td>
							<td>
							  <?
								  $aAtividade = array ("p" => "Somente Principal", "t" => "Todas");
								  db_select("atividade", $aAtividade, null, 1,"onchange='js_removeElementSelect()'"); 
							  ?>
							</td> 
						<tr>
						
						<tr>
							<td> <b> Ordenar por </b> </td>
							<td>
							  <?
								  $aOrdem = array ("i" => "Inscri��o", "n" => "Nome", "a" => "Atividade");
								  db_select("ordem", $aOrdem, null, 1); 
							  ?>
							</td>
						</tr>

					</table>
				</fieldset>
				<div align="center" style="margin-top: 5px;">
					<input name="emite" onclick="return js_relatorio()" type="button" id="emite" value="Gerar Relat�rio">
				</div>
				</form>
			</fieldset>
		</td>
	</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>