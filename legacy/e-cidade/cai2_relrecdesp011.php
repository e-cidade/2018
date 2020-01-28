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
include("libs/db_liborcamento.php");

db_postmemory($HTTP_POST_VARS);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
  </head>
  <body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#cccccc">
<center>
<form name="form1" method="post" action="cai2_relrecdesp002.php">
<table border='0'>
  <tr>
     <td colspan=2>
      <?  db_selinstit();    ?> 

    </td>
 </tr>
    <?
        echo "	      <tr>\n";
        echo "	      <td><fieldset><table width='100%'>\n";
        echo "           <td  ><strong>Posição Até :</strong> </td>\n";
        echo "	         <td align='right'>\n";
        db_inputdata('posicaoate',date("d", db_getsession("DB_datausu")), 
                                  date("m", db_getsession("DB_datausu")), 
                                  date("Y", db_getsession("DB_datausu")),true,"text", 1);
        echo "</td>";
		echo "<tr>";
		echo "<td>";
		echo "<b>Deficit/Superavit baseado em:</b>";
		echo "</td>";
		echo "<td align='right'>";
	  $x = array ("e" => "Empenhado", "l" => "Liquidado");
	  db_select('deficitsuperavit', $x, true, 4, "");
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>";
		echo "<b>Quebrar por recurso:</b>";
		echo "</td>";
		echo "<td align='right'>";
	  $x = array ("s" => "Sim", "n" => "Não");
		$quebrarporrecurso = 'n';
	  db_select('quebrarporrecurso', $x, true, 4, "");
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>";
		echo "<b>Considerar Rec/Desp Extra-Orcamentárias:</b>";
		echo "</td>";
		echo "<td align='right'>";
	  $x = array ("s" => "Sim", "n" => "Não");
		$consideraextra = 'n';
	  db_select('consideraextra', $x, true, 4, "");
		echo "</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>";
		echo "<b>Totalização Acumulada:</b>";
		echo "</td>";
		echo "<td align='right'>";
	  $x = array ("2" => "Sim", "1" => "Não");
		$sAgrupa = '1';
	  db_select('sAgrupa', $x, true, 4, "");
		echo "</td>";
		echo "</tr>";
		echo "</table></fieldset>";
    ?>
    </td>
    <td ></td>
  </tr>
  <tr>
    <td colspan="2" align="center"><br>
      <input name="rel" type="button" onclick='js_gerarel()' value="Gerar relatório">
      <input  name="filtra_despesa" id="filtra_despesa" type="hidden" value="" >
    </td>
  </tr>
</table>
</form>
</center>
<script>
//--------------------------------
variavel = 1;
function js_gerarel(){
  if (document.getElementById('posicaoate').value == '') {
  
   alert('Informe a data final');
   document.getElementById('posicaoate').focus();
   return false;
   
  }
  document.form1.filtra_despesa.value = parent.iframe_filtro.js_atualiza_variavel_retorno();
  jan = window.open('','safo' + variavel,'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');  
  document.form1.target = 'safo' + variavel++;
  setTimeout("document.form1.submit()",1000);
  return true;

// document.form1.filtra_despesa.value = parent.iframe_filtro.js_atualiza_variavel_retorno();
// jan = window.open('cai2_relrecdesp002.php?filtra_despesa='+,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  
}
</script>
</body>
</html>
<script>
document.getElementById('quebrarporrecurso').style.width='123';
document.getElementById('consideraextra').style.width='123';
document.getElementById('deficitsuperavit').style.width='123';
document.getElementById('sAgrupa').style.width='123';
</script>