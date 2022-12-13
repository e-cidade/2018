<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

$oDaoCaracteristica = db_utils::getDao("caracteristica");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
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
		<td valign="top">
		  <form name="form1" method="post" action="pro1_relcgm002.php" target="rel">
			<fieldset style="margin-top:15px;">
				<legend> <b> Relatório de Inscrição </b> </legend>

					<table>
						
						<tr>
							<td> <b> Tipo de Empresa: </b> </td>
							<td>
							  <?
								  $aPessoa = array ("j" => "Jurídica", "f" => "Física", "t" => "Todas");
								  db_select("pessoa", $aPessoa, null, 1, "style='width: 150px;'"); 
							  ?>
							</td> 
						</tr>
						
						<tr>		
							<td> <b> Baixada: </b> </td>
							<td>
							  <?
								  $aBaixada = array ("n" => "Não", "s" => "Sim", "t" => "Todas");
								  db_select("baixada", $aBaixada, null, 1, "style='width: 150px;'"); 
							  ?>
							</td> 
						</tr>
					
						<tr>	
							<td> <b> Atividade: </b> </td>
							<td>
							  <?
								  $aAtividade = array ("p" => "Somente Principal", "t" => "Todas");
								  db_select("atividade", $aAtividade, null, 1, "onchange='js_removeElementSelect()'; style='width: 150px;'"); 
							  ?>
							</td> 
						<tr>
						
						<tr>	
							<td> <b> Data de Início da Atividade: </b> </td>
							<td>
							  <?
							    db_inputdata('datainicioatividade',null,null,null,true,'text',1);
							  ?>
							</td> 
						<tr>
						
						<tr>	
							<td> <b> Data de Fim da Atividade: </b> </td>
							<td>
							  <?
							    db_inputdata('datafinalatividade',null,null,null,true,'text',1);
							  ?>
							</td> 
						<tr>
						
            <tr>		
							<td> <b> Regime Tributário: </b> </td>
							<td>
							  <?
							    $sCampos = "db140_sequencial, db140_descricao";
							    $sSqlRegimes = $oDaoCaracteristica->sql_query(null, $sCampos, null, "db138_sequencial = 1 and db140_grupocaracteristica = 4");
							    $rsRegimes = $oDaoCaracteristica->sql_record($sSqlRegimes);
							    db_selectrecord("regime", $rsRegimes, null, 1, "","","","0-","",1);								  
							  ?>
							</td> 
						</tr>
												
						<tr>
							<td> <b> Ordenar por </b> </td>
							<td>
							  <?
								  $aOrdem = array ("i" => "Inscrição", "n" => "Nome", "a" => "Atividade");
								  db_select("ordem", $aOrdem, null, 1, "style='width: 150px;'"); 
							  ?>
							</td>
						</tr>

					</table>
				
			</fieldset>
			<div align="center" style="margin-top: 5px;">
			  <input name="emite" onclick="return js_relatorio()" type="button" id="emite" value="Gerar Relatório">
			</div>		
			</form>
		</td>
	</tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>

<script>
function js_relatorio(){
	
	// abre o arquivo que gera o relatório passando os parametros 
	// configurados por $_GET
	var sUrl = 'iss2_relatorioinscricoes002.php?pessoa='+$F('pessoa')+
	                                           '&atividade='+$F('atividade')+
	                                           '&baixa='+$F('baixada')+
	                                           '&regime='+$F('regime')+
	                                           '&descricaoregime='+$('regime').options[$('regime').selectedIndex].text+
	                                           '&datainicioatividade='+$F('datainicioatividade_ano')+'-'+$F('datainicioatividade_mes')+'-'+$F('datainicioatividade_dia')+
	                                           '&datafinalatividade='+$F('datafinalatividade_ano')+'-'+$F('datafinalatividade_mes')+'-'+$F('datafinalatividade_dia')+
	                                           '&ordem='+$F('ordem');	
  jan = window.open(sUrl,'',
									  'width='+(screen.availWidth-5)+
									  ',height='+(screen.availHeight-40)+', scrollbars=1, location=0'
									 );
  jan.moveTo(0,0);
}

function js_removeElementSelect() {
	/*
	 * se o usuário selecionar no select Atividade a opção todas
	 * exclui do select Ordem a posição que contém a opção Atividade
	 */
	if (document.getElementById("atividade").value == "t") {
		document.getElementById("ordem").remove(2);
	} else {
	  /*
		* se o usuário não selecionar a opção todos no select Atividade
		* mantém o select Ordem com todas suas propriedades iniciais
		*/
		if (document.getElementById("atividade").value != "t") 
			document.getElementById("ordem").add(new Option("Atividade", "a"), null);
	}
}

</script>

</html>