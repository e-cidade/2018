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

set_time_limit(0);
require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="" target=''>
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0">
<tr align="center" width="100%">
   <td width="25%" height="30" colspan=""><font size="3">
   </td>
   <td width="50%" height="30" colspan=""><font size="3">
   </td>
   <td width="25%" height="30" colspan=""><font size="3">
   </td>
</tr>

<tr align="center" width="100%">
   <td align="right" width="35%" height="30" colspan=""><font size="3">
   <b>Periodo de : &nbsp;&nbsp;</b>
   </td>
   <td align="left" width="50%" height="30" colspan=""><font size="3">
		<?
		db_inputdata('datai', "", "", "", true, 'text', 1, "");
		echo " a ";
		db_inputdata('dataf', "", "", "", true, 'text', 1, "");
		?>
   </td>
   <td width="15%" height="30" colspan=""><font size="3">
   </td>
</tr>
<tr>
<td align='right' >Tipo de relatorio:</td>
<td align='left'> <select name='tiporel'>
                  <option value='1'>Data Arquivo</option>
                  <option value='2'>Data Processamento</option>
                  </select>
</td>
</tr>
<tr>
<td align='right' >Tipo de relatorio:</td>
<td align='left'> <select name='agrupa'>
                  <option value='1'>Estrutural</option>
                  <option value='2'>Receita</option>
                  </select>
</td>

</tr>
	<tr align="center"  width="100%">
	<td width="25%" height="30" colspan=""><font size="3">
	</td>
	<td align="left" width="50%" height="30" colspan=""><font size="3">
	     <input name="Relatorio" id="emite2" type="button" value="Gera Relatorio" onClick="js_relatorio()">&nbsp;&nbsp;
	</td>
	<td width="25%" height="30" colspan=""><font size="3">
	</td>
	</tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
<script>

function js_relatorio(){
   //alert('entrou');
   dti=document.form1.datai_ano.value+document.form1.datai_mes.value+document.form1.datai_dia.value;
   dtf=document.form1.dataf_ano.value+document.form1.dataf_mes.value+document.form1.dataf_dia.value;
	if (dti != '' || dtf != ''){
		if (dti>dtf){
		    alert('Periodo invalido!');
		    document.form1.datai_dia.focus();
		    return false;
		}
	}
   datai=document.form1.datai_ano.value+'-'+document.form1.datai_mes.value+'-'+document.form1.datai_dia.value;
   dataf=document.form1.dataf_ano.value+'-'+document.form1.dataf_mes.value+'-'+document.form1.dataf_dia.value;
   
   jan = window.open('cai3_totarrecada002.php?tiporel='+document.form1.tiporel.value+'&datai='+datai+'&dataf='+dataf+'&agrupa='+document.form1.agrupa.value,"",'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
   
}
</script>
</html>