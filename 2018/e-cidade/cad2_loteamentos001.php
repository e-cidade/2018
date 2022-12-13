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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_SERVER_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<script>

function js_emite() {

  var ordem = $('ordem').value;
  var lista = $('listaresponsaveis').value;
  var sUrl  = 'cad2_loteamentos002.php?ordem='+ordem+'&lista='+lista;
  var jan   = window.open(sUrl,'',
                          'width='+(screen.availWidth-5)+
                          ',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table  align="center">
  <form name="form1" method="post" action="" >
			<tr>
				 <td >&nbsp;</td>
				 <td >&nbsp;</td>
			</tr>
			<tr>
				<td>
					<fieldset>
						<legend>
							<b>&nbsp;Lotementos&nbsp;</b>
						</legend>
						<table>
              <tr >
                <td align="left" nowrap title="Ordem para a emissão do relatório." >
                  <strong>Listar Responsáveis:</strong>
                </td>
                <td align="left">&nbsp;&nbsp;&nbsp;
                  <?
                    $aListaResponsaveis = array("s"=>"Sim","n"=>"Não");
                    db_select('listaresponsaveis',$aListaResponsaveis,true,4,"");
                  ?>
                </td>
              </tr> 						
							<tr >
								<td align="left" nowrap title="Ordem para a emissão do relatório." >
									<strong>Ordem:</strong>
								</td>
								<td align="left">&nbsp;&nbsp;&nbsp;
									<?
										$aOrdem = array("a"=>"Alfabética","n"=>"Numérica");
										db_select('ordem',$aOrdem,true,4,"");
									?>
								</td>
							</tr>						
						</table>
					</fieldset>
				</td>
			</tr>
			<tr>
				<td colspan="2" align = "center"> 
				 <input name="imprime" type="button" id="imprime" value="Imprimir" onClick="js_emite();">
				</td>
			</tr>
  
	</form>
</table>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>