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
include ("classes/db_transmater_classe.php");
include ("classes/db_pcmater_classe.php");
require ("libs/db_conecta.php");
include ("dbforms/db_funcoes.php");
$cltransmater = new cl_transmater;
$clpcmater = new cl_pcmater;
$clpcmater->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
if (isset ($atualizar)&&$atualizar!="") {
	db_inicio_transacao();
	$result03 = $cltransmater->sql_record($cltransmater->sql_query_file(null, "*", null, "m63_codmatmater=$m60_codmater"));
	if ($cltransmater->numrows > 0) {
		$cltransmater->m63_codmatmater = $m60_codmater;
		$cltransmater->excluir(null,"m63_codmatmater=$m60_codmater");
		if ($cltransmater->erro_status == '0') {
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
			
			$cltransmater->m63_codpcmater = $dados[1];
			$cltransmater->m63_codmatmater = $m60_codmater;
			$cltransmater->incluir();
			if ($cltransmater->erro_status == '0') {
				$erro_msg = $cltransmater->erro_msg;
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


db_input('m60_codmater', 8, '', true, 'hidden', 3);
db_input('atualizar', 8, '', true, 'hidden', 3);
db_input('pc01_descrmater', 8, '', true, 'hidden', 3);
?>     
      <table border='1' width="100%" nowrap>
<? 


if (isset ($pc01_descrmater)) {
	$result01 = $clpcmater->sql_record($clpcmater->sql_query_file(null,"*",null,"pc01_descrmater ilike '$pc01_descrmater%' and pc01_ativo is false"));
	$numrows01 = $clpcmater->numrows;
	if ($numrows01 > 0) {
		echo " 
			           <tr>
				     <td class='cabec'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
				     <td class='cabec' align='center'  title='$Tpc01_codmater'>".str_replace(":", "", $Lpc01_codmater)."</td>
				     <td class='cabec' align='center'  title='$Tpc01_descrmater'>".str_replace(":", "", $Lpc01_descrmater)."</td>    
				     
				   </tr>
			          ";
	}
	for ($i = 0; $i < $numrows01; $i ++) {
		db_fieldsmemory($result01, $i);
		$che = "";
		$result02 = $cltransmater->sql_record($cltransmater->sql_query_file(null,"*", null, "m63_codmatmater=$m60_codmater and m63_codpcmater=$pc01_codmater"));
		$numrows02 = $cltransmater->numrows;
		if ($numrows02 > 0) {
			$che = "checked";
		}
		echo "
		           <tr>
		  	           <td  class='corpo' title='Inverte a marcação' align='center'><input $che type='checkbox' name='CHECK_$pc01_codmater' id='CHECK_".$pc01_codmater."'></td>
		              <td  class='corpo'  align='center' title='$Tpc01_descrmater'><label for='CHECK_".$pc01_codmater."' style=\"cursor: hand\"><small>$pc01_codmater</small></label></td>
		              <td  class='corpo'  align='center' title='$Tpc01_descrmater'><label for='CHECK_".$pc01_codmater."' style=\"cursor: hand\"><small>$pc01_descrmater</small></label></td>
        
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