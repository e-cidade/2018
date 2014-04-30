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
include_once ("dbforms/db_classesgenericas.php");
$cliframe_seleciona = new cl_iframe_seleciona;
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$db_opcao = 1;
$sql = "select * from cadescrito
              inner join cgm on z01_numcgm = q86_numcgm 
	order by z01_nome";
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" bgcolor="#CCCCCC" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1" method="post" action="" target=''>
<table  width="50%" height="100%" align="center" border="0" cellspacing="0" cellpadding="0">
<tr align="top" >
<td width="30%" height="30" colspan="6"><div align="left"><font size="2">
<b></b>
</td>
</tr>
<tr align="top">
<td width="30%" height="30" colspan="6"><div align="left"><font size="2">
</td>
</tr>
<tr align="top">
<td width="30%" height="30" colspan="6"><div align="left"><font size="2">
<b></b>
</td>
</tr>
<tr align="top">
</tr>
<tr>
 <td height=100% valign=top width=100%>
<?
$cliframe_seleciona->sql = $sql;
$cliframe_seleciona->campos = "q86_numcgm,z01_nome";
$cliframe_seleciona->legenda = "Escritórios";
$cliframe_seleciona->textocabec = "darkblue";
$cliframe_seleciona->textocorpo = "black";
$cliframe_seleciona->fundocabec = "#aacccc";
$cliframe_seleciona->fundocorpo = "#ccddcc";
$cliframe_seleciona->iframe_height = '400px';
$cliframe_seleciona->iframe_width = '100%';
$cliframe_seleciona->iframe_nome = "escrito";
$cliframe_seleciona->chaves = "q86_numcgm";
$cliframe_seleciona->marcador = true;
$cliframe_seleciona->dbscript = "onclick='parent.js_mandadados();'";
$cliframe_seleciona->js_marcador = "parent.js_mandadados();";
$cliframe_seleciona->iframe_seleciona($db_opcao);
?>
 </td>
 </tr>
</table>
</form>
</body>
</html>
<script>
function js_mandadados(){
   var virgula = '';
   var dados = '';
//   alert("entrou mandadados");
   for(i = 0;i < escrito.document.form1.elements.length;i++){
      if(escrito.document.form1.elements[i].type == "checkbox" &&  escrito.document.form1.elements[i].checked){
         dados = dados+virgula+escrito.document.form1.elements[i].value;
	 virgula = ', ';
      }
   }
   parent.iframe_g1.document.form1.cgmescrito.value = dados; 
}
</script>