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
include ("classes/db_pcfornesubgrupo_classe.php");
include ("classes/db_pcsubgrupo_classe.php");
require ("libs/db_conecta.php");
include ("dbforms/db_funcoes.php");
$clpcfornesubgrupo = new cl_pcfornesubgrupo;
$clpcsubgrupo = new cl_pcsubgrupo;
$clpcsubgrupo->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("pc76_pcsubgrupo");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if (isset ($atualizar)) {
	db_inicio_transacao();
	$result03 = $clpcfornesubgrupo->sql_record($clpcfornesubgrupo->sql_query_file(null, "*", null, "pc76_pcforne=$pc76_pcforne"));
	if ($clpcfornesubgrupo->numrows > 0) {
		$clpcfornesubgrupo->pc76_pcforne = $pc76_pcforne;
		$clpcfornesubgrupo->excluir(null, "pc76_pcforne=$pc76_pcforne");
		if ($clpcfornesubgrupo->erro_status == '0') {
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
			$clpcfornesubgrupo->pc76_pcforne = $pc76_pcforne;
			$clpcfornesubgrupo->pc76_pcsubgrupo = $dados[1];
			$clpcfornesubgrupo->incluir(null);
			if ($clpcfornesubgrupo->erro_status == '0') {
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="document.form1.pc76_pcforne.focus();" >
<form name="form1" method="post">
 <table border="0" width="100%" cellspacing="0" cellpadding="0" nowrap >
  <tr>
    <td align="center" valign="top">
<? 


db_input('pc76_pcforne', 8, '', true, 'hidden', 3);
db_input('atualizar', 8, '', true, 'hidden', 3);
?>     
      <table border='1' width="100%" nowrap>
<? 


if (isset ($pc76_pcforne)) {
	$result01 = $clpcsubgrupo->sql_record($clpcsubgrupo->sql_query(null, "*", "pc04_descrsubgrupo","(pc04_tipoutil=2 or pc04_tipoutil=3)"));
	$numrows01 = $clpcsubgrupo->numrows;
	if ($numrows01 > 0) {
		echo " 
		           <tr>
			     <td class='cabec'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
			     <td class='cabec' align='center'  title='$Tpc04_codsubgrupo'>".str_replace(":", "", $Lpc04_codsubgrupo)."</td>
			     <td class='cabec' align='center'  title='$Tpc04_descrsubgrupo'>".str_replace(":", "", $Lpc04_descrsubgrupo)."</td>
			     
			   </tr>
		          ";
	}
	for ($i = 0; $i < $numrows01; $i ++) {
		db_fieldsmemory($result01, $i);
		$che = "";
		$result02 = $clpcfornesubgrupo->sql_record($clpcfornesubgrupo->sql_query_file(null, "*", null, "pc76_pcforne=$pc76_pcforne and pc76_pcsubgrupo=$pc04_codsubgrupo"));
		$numrows02 = $clpcfornesubgrupo->numrows;
		if ($numrows02 > 0) {
			$che = "checked";
		}
	echo "
	           <tr>
	  	           <td  class='corpo' title='Inverte a marcação' align='center'><input $che type='checkbox' name='CHECK_$pc04_codsubgrupo' id='CHECK_".$pc04_codsubgrupo."'></td>
	              <td  class='corpo'  align='center' title='$Tpc04_codsubgrupo'><label for='CHECK_".$pc04_codsubgrupo."' style=\"cursor: hand\"><small>$pc04_codsubgrupo</small></label></td>
	              <td  class='corpo'  align='center' title='$Tpc04_descrsubgrupo'><label for='CHECK_".$pc04_codsubgrupo."' style=\"cursor: hand\"><small>$pc04_descrsubgrupo</small></label></td>
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



if (isset ($atualizar)) {

}
?>