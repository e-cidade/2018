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
include ("classes/db_pctipodoccertif_classe.php");
include ("classes/db_pcdoccertif_classe.php");
require ("libs/db_conecta.php");
include ("dbforms/db_funcoes.php");
$clpctipodoccertif = new cl_pctipodoccertif;
$clpcdoccertif = new cl_pcdoccertif;
$clrotulo = new rotulocampo;
$clrotulo->label("pc71_codigo");
$clrotulo->label("pc71_descr");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
if (isset ($atualizar)) {
	db_inicio_transacao();
	$result03 = $clpctipodoccertif->sql_record($clpctipodoccertif->sql_query_file(null, "*", "pc72_pcdoccertif","pc72_pctipocertif=$pc72_pctipocertif"));
	if ($clpctipodoccertif->numrows > 0) {
		$clpctipodoccertif->pc72_pctipocertif = $pc72_pctipocertif;
		$clpctipodoccertif->excluir(null,"pc72_pctipocertif=$pc72_pctipocertif");
		$erro_msg=$clpctipodoccertif->erro_msg;
		if ($clpctipodoccertif->erro_status == 0) {
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
			$obtes = $HTTP_POST_VARS;
			$tam = sizeof($obtes);
			if (array_key_exists("OB_".$dados[1],$obtes)){
				$ob=1;
			}else{
				$ob=0;
			}
			/*
			reset($obtes);
			$ob="f";
			for ($w = 0; $w < $tam; $w ++) {
				$chaveob = key($obtes);
				if ( $chaveob == "OB_".$dados[1]) {
					$dadosob = split("_", $chaveob);
					
				}
				$proximo = next($obtes);
			}
			*/
			$clpctipodoccertif->pc72_obrigatorio = "$ob";
			$clpctipodoccertif->pc72_pctipocertif = $pc72_pctipocertif;
			$clpctipodoccertif->pc72_pcdoccertif = $dados[1];
			$clpctipodoccertif->incluir(null);
			$erro_msg=$clpctipodoccertif->erro_msg;
			if ($clpctipodoccertif->erro_status == '0') {
				$sqlerro = true;
				break;
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
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<script>
function js_atualizar(){
  document.form1.atualizar.value="ok";
  document.form1.submit();
}
function js_marca(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].name.substr(0,1) == "C"){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
   return false;
}
function js_marcaob(obj){ 
   var OBJ = document.form1;
   for(i=0;i<OBJ.length;i++){
     if(OBJ.elements[i].type == 'checkbox' && OBJ.elements[i].name.substr(0,1) == "O"){
       OBJ.elements[i].checked = !(OBJ.elements[i].checked == true);            
     }
   }
   return false;
}
</script>
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
<body>
  <form name="form1" method="post">
  <table border="0" width="100%" cellspacing="0" cellpadding="0" nowrap >
  <tr>
    <td align="center" valign="top">
<? 


db_input('pc72_pctipocertif', 8, '', true, 'hidden', 3);
db_input('atualizar', 8, '', true, 'hidden', 3);
?>     
      <table border='1' width="100%" nowrap>
<? 


if (isset ($pc72_pctipocertif)&&$pc72_pctipocertif!="") {
	$result01 = $clpcdoccertif->sql_record($clpcdoccertif->sql_query(null, "pc71_codigo,pc71_descr", "pc71_descr"));
	$numrows01 = $clpcdoccertif->numrows;
	if ($numrows01 > 0) {
		echo " 
		         <tr>
			       <td class='cabec'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'><b>M</b></a></td>
			       <td class='cabec' align='center'  title='$Tpc71_codigo'>".str_replace(":", "", $Lpc71_codigo)."</td>
			       <td class='cabec' align='center'  title='$Tpc71_descr'>".str_replace(":", "", $Lpc71_descr)."</td>
	               <td class='cabec'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marcaob(this);return false;'><b>Obrigatório<b></a></td>
			     </tr>
		          ";
	}
	for ($i = 0; $i < $numrows01; $i ++) {
		db_fieldsmemory($result01, $i);
		$che = "";
		$ob = "";
		$result02 = $clpctipodoccertif->sql_record($clpctipodoccertif->sql_query_file(null, "*", "pc72_pcdoccertif","pc72_pctipocertif=$pc72_pctipocertif and pc72_pcdoccertif=$pc71_codigo"));
	    $numrows02 = $clpctipodoccertif->numrows;
		for ($h = 0; $h < $numrows02; $h ++) {
			db_fieldsmemory($result02, $h);
			if ($pc72_obrigatorio == 't') {
				$ob = "checked";
			}else{
				$ob = "";
			}
			if ($pc72_pcdoccertif == $pc71_codigo) {
				$che = "checked";
			}
		}
		echo "
		           <tr>
			          <td  class='corpo' title='Inverte a marcação' align='center'><input $che type='checkbox' name='CHECK_$pc71_codigo' id='CHECK_".$pc71_codigo."'></td>
		              <td  class='corpo'  align='center' title='$Tpc71_codigo'><label for='CHECK_".$pc71_codigo."' style=\"cursor: hand\"><small>$pc71_codigo</small></label></td>
		              <td  class='corpo'  align='center' title='$Tpc71_descr'><label for='CHECK_".$pc71_codigo."' style=\"cursor: hand\"><small>$pc71_descr</small></label></td>
	                  <td  class='corpo' title='Inverte a marcação' align='center'><input $ob type='checkbox' name='OB_$pc71_codigo' id='OB_".$pc71_codigo."'></td>
		           </tr>";
	}
}
?>    </table>
    </td>
  </tr>  
  </table>
<body>
</html>
<?



if (isset ($atualizar)) {
	if ($sqlerro==true){
	  db_msgbox($erro_msg);
	  $clpctipodoccertif->erro(true, false);
	}else{
	  db_msgbox($erro_msg);
	}
	db_redireciona("com1_pctipodoccertifalt003.php?pc72_pctipocertif=$pc72_pctipocertif");
}
?>