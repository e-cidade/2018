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
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<center>
<table align="center" width="790" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> <br>
    <center>
      <table width="790" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="430" align="center" valign="top" bgcolor="#CCCCCC"> <br>
<?
$where = "";
if(isset($e60_numemp) && trim($e60_numemp)!=""){
  $where .= " where empempenho.e60_numemp=".$e60_numemp;
}else if(isset($e60_codemp) && trim($e60_codemp)!=""){
  $arr = split("/",$e60_codemp);
  $where .= " where empempenho.e60_codemp=".$arr[0];
  if(count($arr) == 2  && isset($arr[1]) && $arr[1] != '' ){
    $where   .= " and empempenho.e60_anousu = ".$arr[1];
  }else{
    $where   .= " and empempenho.e60_anousu = ".db_getsession("DB_anousu");
  }
}

  
  $sql = "select * 
	  from conlancamdoc 
	       inner join conlancamemp on conlancamemp.c75_codlan = conlancamdoc.c71_codlan and conlancamdoc.c71_coddoc=2 
	       inner join conlancamcompl on conlancamcompl.c72_codlan = conlancamemp.c75_codlan
	       inner join empempenho on empempenho.e60_numemp = conlancamemp.c75_numemp
	       inner join orcdotacao on orcdotacao.o58_coddot = empempenho.e60_coddot
          $where";
// die($sql); 
  db_lovrot($sql,15,"()","",$funcao_js);
?>
          </td>
        </tr>
      </table>
    </center>
    </td>
  </tr>
</table>
</center>
</body>
</html>
<?
/*
  echo "<html>";
  echo "<head>";
  echo "<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>";
  echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">";
  echo "<meta http-equiv=\"Expires\" CONTENT=\"0\">";
  echo "<script language=\"JavaScript\" type=\"text/javascript\" src=\"scripts/scripts.js\"></script>";
  echo "<link href=\"estilos.css\" rel=\"stylesheet\" type=\"text/css\">";
  echo "<body bgcolor=#CCCCCC leftmargin=\"0\" topmargin=\"0\" marginwidth=\"0\" marginheight=\"0\" onLoad=\"a=1\" >";
  echo "<table width=\"790\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\" bgcolor=\"#5786B2\">";
  echo "  <tr>";
  echo "    <td width=\"360\" height=\"18\">&nbsp;</td>";
  echo "    <td width=\"263\">&nbsp;</td>";
  echo "    <td width=\"25\">&nbsp;</td>";
  echo "    <td width=\"140\">&nbsp;</td>";
  echo "  </tr>";
  echo "</table>";
  echo "<table width=\"790\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
    echo "  <tr>";
  echo "    <td height=\"430\" align=\"left\" valign=\"top\" bgcolor=\"#CCCCCC\"> <br>";
  echo "    <center>";
  echo "      <table width=\"790\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
  echo "        <tr>";
  echo "          <td height=\"430\" align=\"left\" valign=\"top\" bgcolor=\"#CCCCCC\"> <br>";
  db_lovrot($sql,15,"()","","js_retorna|c72_codlan");
  echo "          </td>";
  echo "        </tr>";
  echo "      </table>";
  echo "    </center>";
  echo "    </td>";
  echo "  </tr>";
  echo "</table>";
  echo "</body>";
  echo "</html>";
*/
?>