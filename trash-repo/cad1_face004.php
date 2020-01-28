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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
include ("classes/db_face_classe.php");
include ("classes/db_carface_classe.php");
include ("classes/db_cfiptu_classe.php");
include ("classes/db_facevalor_classe.php");
$clface = new cl_face;
$clface = new cl_face;
$clcarface = new cl_carface;
$clcfiptu = new cl_cfiptu;
$clfacevalor = new cl_facevalor;

db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
if (isset ($incluir)) {
	$sqlerro = false;
	db_inicio_transacao();
	$j37_quadra = str_pad($j37_quadra, 4, "0", STR_PAD_LEFT);
	$clface->j37_quadra = $j37_quadra;
	$clface->j37_valor = '0';
	$clface->j37_vlcons = '0';
	$clface->incluir($j37_face);
	if ($clface->erro_status == 0) {
		$sqlerro = true;
	}
	$erro_msg = $clface->erro_msg;
	$j37_face = $clface->j37_face;
	$matriz = split("X", $caracteristica);
	for ($i = 0; $i < sizeof($matriz); $i++) {
		$j38_caract = $matriz[$i];
		if ($j38_caract != "") {
			$clcarface->incluir($j37_face, $j38_caract);
			if ($clcarface->erro_status == 0) {
				$sqlerro = true;
				$erro_msg = $clcarface->erro_msg;
			}
		}
	}
	/*
	$clface->incluir($j37_face);
	if($clface->erro_status==0){
	  $sqlerro=true;
	} 
	$erro_msg = $clface->erro_msg;
	*/
	db_fim_transacao($sqlerro);
	$j37_face = $clface->j37_face;
	$db_opcao = 1;
	$db_botao = true;
}
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

include ("forms/db_frmface.php");
?>
    </center>
	</td>
  </tr>
</table>
</body>
</html>
<?

if (isset ($incluir)) {
	if ($sqlerro == true) {
		db_msgbox($erro_msg);
		if ($clface->erro_campo != "") {
			echo "<script> document.form1." . $clface->erro_campo . ".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1." . $clface->erro_campo . ".focus();</script>";
		};
	} else {
		db_msgbox($erro_msg);
		db_redireciona("cad1_face005.php?liberaaba=true&chavepesquisa=$j37_face");
	}
}
?>