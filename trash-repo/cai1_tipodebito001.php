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
$db_opcao=1;
if(isset($HTTP_POST_VARS["enviar"])) {
  db_postmemory($HTTP_POST_VARS);
  $result = pg_exec("select max(k00_tipo) + 1 from arretipo");
  $k00_tipo = pg_result($result,0,0);
  $k00_tipo = $k00_tipo==""?"1":$k00_tipo;
  $k00_codbco = trim($k00_codbco)==""?"null":$k00_codbco;  
  
  pg_exec("insert into arretipo(k00_tipo,
                                k00_descr,
								k00_emrec,
								k00_agnum,
								k00_agpar,
							    k00_codbco,
                                k00_codage,
                                k00_hist1,
                                k00_hist2,
                                k00_hist3,
                                k00_hist4,
                                k00_hist5,
                                k00_hist6,
                                k00_hist7,
                                k00_hist8,
				codmodelo,k00_impval,k00_vlrmin,k03_tipo)
                       values($k00_tipo,
					          '$k00_descr',
							  '$k00_emrec',
							  '$k00_agnum',
							  '$k00_agpar',
                              $k00_codbco,
                              '$k00_codage',
                              '$k00_hist1',
                              '$k00_hist2',
                              '$k00_hist3',
                              '$k00_hist4',
                              '$k00_hist5',
                              '$k00_hist6',
                              '$k00_hist7',
                              '$k00_hist8',
                              $codmodelo,'$k00_impval','$k00_vlrmin',$k03_tipo)") or die("Erro(12) inserindo em arretipo");
  db_redireciona();  
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
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<? 
	  include("forms/db_frmtipodebito.php");
    ?>
	</td>
  </tr>
</table>
<?
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>