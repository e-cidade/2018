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
include ("classes/db_histcalc_classe.php");
include ("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clhistcalc = new cl_histcalc;
$db_opcao = 1;
$db_botao = true;
if (isset ($incluir)) {
	db_inicio_transacao();
	$sqlerro = false;
	$cod_cert = true;
	$contador = 1;		

	while ($cod_cert == true) {        		
		$ResultMax = $clhistcalc->sql_record($clhistcalc->sql_query_file($contador));
		
		if ($clhistcalc->numrows == 0) {
			$k01_codigo = $contador;
			$codigo_cem = $k01_codigo +100;
			$ResultCem = $clhistcalc->sql_record($clhistcalc->sql_query_file($codigo_cem));
			if ($clhistcalc->numrows == 0) {				
				$clhistcalc->incluir($k01_codigo);
				$erro_msg = $clhistcalc->erro_msg;
				if ($clhistcalc->erro_status == 0) {
					$sqlerro = true;
					$erro_msg = $clhistcalc->erro_msg;

				} else {
                    $clhistcalc->k01_descr = "PGTO ".substr($clhistcalc->k01_descr, 0,15);
					$clhistcalc->incluir($codigo_cem);
					if ($clhistcalc->erro_status == 0) {
						$sqlerro = true;
						$erro_msg = $clhistcalc->erro_msg;
					}
				}
				$cod_cert = false;
			} else {
				$contador ++;
			}
		} else {
			$contador ++;
		}
	}
	db_fim_transacao($sqlerro);
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
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
	<?


include ("forms/db_frmhistcalc.php");
?>
    </center>
	</td>
  </tr>
</table>
<?


db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>
<?


if (isset ($incluir)) {
	if ($clhistcalc->erro_status == "0") {
		$clhistcalc->erro(true, false);
		$db_botao = true;
		echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
		if ($clhistcalc->erro_campo != "") {
			echo "<script> document.form1.".$clhistcalc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
			echo "<script> document.form1.".$clhistcalc->erro_campo.".focus();</script>";
		};
	} else {
		db_msgbox($erro_msg);
		echo "<script>location.href='cai1_histcalc001.php';</script>";
		//$clhistcalc->erro(true,true);
	};
};
?>