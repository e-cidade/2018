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
include ("classes/db_db_docparagpadrao_classe.php");
include ("classes/db_db_paragrafopadrao_classe.php");
require ("libs/db_conecta.php");
include ("dbforms/db_funcoes.php");
$cldb_docparagpadrao = new cl_db_docparagpadrao;
$cldb_paragrafopadrao = new cl_db_paragrafopadrao;
$cldb_paragrafopadrao->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("db04_idparag");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
if (isset ($atualizar)&&$atualizar!="") {
	db_inicio_transacao();
	$result03 = $cldb_docparagpadrao->sql_record($cldb_docparagpadrao->sql_query_file(null,null, "*", null, "db62_coddoc=$db62_coddoc"));
	if ($cldb_docparagpadrao->numrows > 0) {
		$cldb_docparagpadrao->db62_coddoc = $db62_coddoc;
		$cldb_docparagpadrao->excluir($db62_coddoc);
		if ($cldb_docparagpadrao->erro_status == '0') {
			$sqlerro = true;
		
		}
	}
	$sqlerro = false;
	$vt = $HTTP_POST_VARS;
	$ta = sizeof($vt);
	reset($vt);
	for ($i = 0; $i < $ta; $i ++) {
		$chave = key($vt);
		if (substr($chave, 0, 5) == "CHECK") {
			$dados = split("_", $chave);
			$result_ord = $cldb_docparagpadrao->sql_record($cldb_docparagpadrao->sql_query_file($db62_coddoc, null, "max(db62_ordem)as ordem"));
			if ($cldb_docparagpadrao->numrows > 0) {
				db_fieldsmemory($result_ord, 0);
				$ordem = $ordem +1;
			} else {
				$ordem = 1;
			}
			$cldb_docparagpadrao->db62_coddoc = $db62_coddoc;
			$cldb_docparagpadrao->db62_codparag = $dados[1];
			$cldb_docparagpadrao->db62_ordem = $ordem;
			$cldb_docparagpadrao->incluir($db62_coddoc, $dados[1]);
			if ($cldb_docparagpadrao->erro_status == '0') {
				$erro_msg = $cldb_docparagpadrao->erro_msg;
				$sqlerro = true;
			}
		}
		$proximo = next($vt);
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
<script>
function js_atualizar(){
  document.form1.atualizar.value="ok";
  document.form1.submit();
}
function js_marca(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox'){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
   return false;
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.cabec {
text-align: center;
color: darkblue;
background-color:#aacccc;       
border-color: darkblue;
}
.corpo {
text-align: center;
color: black;
background-color:#ccddcc;       
}
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"  >
<form name="form1" method="post">
 <table border="0" width="100%" cellspacing="0" cellpadding="0" nowrap >
  <tr>
    <td align="center" valign="top">
<? 


db_input('db62_coddoc', 8, '', true, 'hidden', 3);
db_input('atualizar', 8, '', true, 'hidden', 3);
db_input('ordem', 8, '', true, 'hidden', 3);
?>     
      <table border='1' width="100%" nowrap>
<? 


if (isset ($db62_coddoc)) {
	$result01 = $cldb_paragrafopadrao->sql_record($cldb_paragrafopadrao->sql_query(null,"*",$ordem));
	$numrows01 = $cldb_paragrafopadrao->numrows;
	if ($numrows01 > 0) {
		echo " 
			           <tr>
				     <td class='cabec'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
				     <td class='cabec' align='center'  title='$Tdb61_codparag'>".str_replace(":", "", $Ldb61_codparag)."</td>
				     <td class='cabec' align='center'  title='$Tdb61_descr'>".str_replace(":", "", $Ldb61_descr)."</td>
                     <td class='cabec' align='center'  title='$Tdb61_texto'>".str_replace(":", "", $Ldb61_texto)."</td>
				     
				   </tr>
			          ";
	}
	for ($i = 0; $i < $numrows01; $i ++) {
		db_fieldsmemory($result01, $i);
		$che = "";
		$result02 = $cldb_docparagpadrao->sql_record($cldb_docparagpadrao->sql_query_file(null,null,"*", null, "db62_coddoc=$db62_coddoc and db62_codparag=$db61_codparag"));
		$numrows02 = $cldb_docparagpadrao->numrows;
		if ($numrows02 > 0) {
			$che = "checked";
		}
		echo "
		           <tr>
		  	           <td  class='corpo' title='Inverte a marcação' align='center'><input $che type='checkbox' name='CHECK_$db61_codparag' id='CHECK_".$db61_codparag."'></td>
		              <td  class='corpo'  align='center' title='$Tdb61_codparag'><label for='CHECK_".$db61_codparag."' style=\"cursor: hand\"><small>$db61_codparag</small></label></td>
		              <td  class='corpo'  align='center' title='$Tdb61_descr'><label for='CHECK_".$db61_codparag."' style=\"cursor: hand\"><small>$db61_descr</small></label></td>
                     <td  class='corpo'  align='center' title='$Tdb61_texto'><label for='CHECK_".$db61_codparag."' style=\"cursor: hand\"><small>$db61_texto</small></label></td>
	              </tr>";
	}
}
?>    </table>
    </td>
  </tr>  
  </table>
</form>
</body>  
</html>
<?



if (isset ($atualizar)&&$atualizar!="") {
	if ($sqlerro == false) {
		echo "<script>parent.js_conclui();</script>";
	} else {
		db_msgbox($erro_msg);
	}
}
?>