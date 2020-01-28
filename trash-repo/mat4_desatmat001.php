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
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$db_botao = true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<!--
<script>
		function js_mostradiv(liga,evt,vlr,vlr1,vlr2){
		  evt= (evt)?evt:(window.event)?window.event:""; 
		  if(liga){
		     document.getElementById('vlr').innerHTML=vlr;
		     document.getElementById('vlr1').innerHTML=vlr1;
		     document.getElementById('vlr2').innerHTML=vlr2;
		     document.getElementById('divlabel').style.left=0;
		     document.getElementById('divlabel').style.top=500;
		     document.getElementById('divlabel').style.visibility='visible';
		  }else{
		    document.getElementById('divlabel').style.visibility='hidden';
		  }  
		}
	      </script>
		<div align="left" id="divlabel" style="position:absolute; z-index:12; top:0; left:0; visibility: hidden; border: 2px outset #666666; background-color: #6699cc; font-style:italic;">
		  <table cellpadding="2" border='1'>
		    <tr nowrap>
		      <td align="center" nowrap>
		        <strong>Reduzido:</strong><span color="#9966cc" id="vlr"></span>&nbsp;&nbsp;&nbsp;<br> 
		      </td>
		      <td align="center" nowrap>
		        <strong>Desdobramento:</strong><span color="#9966cc" id="vlr1"></span><br> 
		      </td>
		      <td align="center" nowrap>
		        <strong>Descr:</strong><span color="#9966cc" id="vlr2"></span><br> 
		      </td>
		    </tr>
		  </table>  
		</div>
		-->
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
	include("forms/db_frmdesatmatalm.php");
	?>
    </center>
	</td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>