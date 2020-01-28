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
include("classes/db_saltes_classe.php");
include("classes/db_corrente_classe.php");

$clsaltes   = new cl_saltes;
$clcorrente = new cl_corrente;
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
$db_opcao = 1;
$db_botao = true;

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
	<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
		<table width="790" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
			<tr>
				<td width="360" height="18">&nbsp;</td>
				<td width="263">&nbsp;</td>
				<td width="25">&nbsp;</td>
				<td width="140">&nbsp;</td>
			</tr>
		</table>
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr> 
				<td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
				<center>
				<form name="form1" enctype="multipart/form-data" method="post" action="">
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

<script>
//alert('fahdhalf hlkasdhfkl hasdelk');

js_OpenJanelaIframe('top.corpo','db_iframe_concilia','func_concilia.php?funcao_js=parent.js_continuar|k68_sequencial|k68_contabancaria|k68_data|implantacao','Pesquisa',true);
function js_continuar(codigo,conta,data,implantacao){
  if (implantacao == 'S') {
  //  alert('AVISO:\n\nConciliação da conta selecionada não pode ser alterado.\nMovimento de implantação de conciliação.'); 
  //  return false;
  }
  document.location.href = 'cai4_concbanc001.php?conta='+conta+'&data='+data+'&concilia='+codigo;
	db_iframe_concilia.hide();
}

</script>