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

parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_fiscal_classe.php");
include("classes/db_fiscalocal_classe.php");
include("classes/db_fiscexec_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clfiscal     = new cl_fiscal;
$clfiscalocal  = new cl_fiscalocal;
$clfiscexec = new cl_fiscexec;
$db_botao = false;
if(isset($y30_codnoti) && $y30_codnoti != ""){
  $result = $clfiscal->sql_record($clfiscal->sql_query($y30_codnoti)); 
  if($clfiscal->numrows > 0){ 
    db_fieldsmemory($result,0);
  }
   $result = $clfiscalocal->sql_record($clfiscalocal->sql_query($y30_codnoti,"*")); 
   if($clfiscalocal->numrows > 0){
     db_fieldsmemory($result,0);
   }
   $result = $clfiscexec->sql_record($clfiscexec->sql_query($y30_codnoti,"*")); 
   if($clfiscexec->numrows > 0){
     db_fieldsmemory($result,0);
   }
  $db_botao = true;
}
$db_opcao = 3;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?
	$consulta = 1;
	include("forms/db_frmfiscal.php");
	echo "<script>document.form1.db_opcao.type='hidden'</script>"; 
	echo "<script>document.form1.pesquisar.type='hidden'</script>"; 
	?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>