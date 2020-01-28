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
include("libs/db_liborcamento.php");
include("dbforms/db_funcoes.php");
include("classes/db_pcorcamforne_classe.php");
db_postmemory($HTTP_GET_VARS);
db_postmemory($HTTP_POST_VARS);
$clpcorcamforne = new cl_pcorcamforne;
$db_opcao=1;
$db_botao=true;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.bordas{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #cccccc;
}
.bordas01{
         border: 1px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #DEB887;
}
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: #999999;
         border-right-color: #999999;
         border-left-color: #999999;
         border-bottom-color: #999999;
         background-color: #999999;
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<form name="form1">
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr> 
    <td align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?
    $result_fornecedores = $clpcorcamforne->sql_record($clpcorcamforne->sql_query(null,"z01_numcgm,z01_nome","","pc21_codorc=$pc21_codorc"));
    $numrows_fornecedores = $clpcorcamforne->numrows;
    echo "<center>";
    echo "<table border='1' align='center'>\n";
    for($i=0;$i<$numrows_fornecedores;$i++){
      db_fieldsmemory($result_fornecedores,$i);
      if($i==0){
	echo "<tr>";
	echo "  <td colspan='2' align='center'><strong> Fornecedores a imprimir </strong></td>";
	echo "</tr>";
	echo "<tr bgcolor=''>\n";
	echo "  <td nowrap class='bordas02' align='center' title='Marcar todos os fornecedores'><strong>";db_ancora("M","js_marcatudo();",1);echo "</strong></td>\n";
	echo "  <td nowrap class='bordas02' align='center'><strong>Nome</strong></td>\n";
	echo "</tr>\n";
  echo "</tr>\n";
  echo "  <td nowrap colspan='1' class='bordas' align='center'><strong><input type='checkbox' checked name='branco' value='branco'></strong></td>\n";
  echo "  <td nowrap colspan='10' class='bordas' align='left'>Emitir em branco</td>\n";
  echo "</tr>\n";
      }
      echo "</tr>\n";
      echo "  <td nowrap colspan='1' class='bordas' align='center'><strong><input type='checkbox' checked name='forn_$z01_numcgm' value='forn_$z01_numcgm'></strong></td>\n";
      echo "  <td nowrap colspan='10' class='bordas' align='left'>$z01_nome</td>\n";
      echo "</tr>\n";
    } 
    ?>
    </center>
    </td>
  </tr>
</table>
</form>
</body>
</html>
<script>
function js_marcatudo(){
  x = document.form1;
  for(i=0;i<x.length;i++){
    if(x.elements[i].type=='checkbox'){
      if(x.elements[i].checked==true){
	x.elements[i].checked=false;
      }else{
	x.elements[i].checked=true;
      }
    }
  }
}
</script>