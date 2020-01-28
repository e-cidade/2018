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
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clrotulo = new rotulocampo;
$clrotulo->label("k00_numpre");
$clrotulo->label("k00_numpar");
$clrotulo->label("k00_dtvenc");
$clrotulo->label("k00_valor");
$clrotulo->label("k00_tipo");
$clrotulo->label("k00_descr");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
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
<style>
<?$cor="#999999"?>
.bordas{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
<?$cor="999999"?>
.bordas_corp{
         border: 1px solid #cccccc;
         border-right-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
       }
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"> 
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
    <form name='form1'>
    <center>
 <table border='1' cellspacing="0" cellpadding="0">   
 <?
$sql = "select arrecad.k00_numpre,k00_numpar,k00_dtvenc,sum(k00_valor)as k00_valor,arrecad.k00_tipo,k00_descr
	              from $tab 
	                inner join arrecad    on arrecad.k00_numpre = $tab.k00_numpre
	                inner join arretipo   on arrecad.k00_tipo   = arretipo.k00_tipo 
	                inner join arreinstit on arrecad.k00_numpre = arreinstit.k00_numpre 
	              where $where 
                  and arreinstit.k00_instit = ".db_getsession("DB_instit")."
	              group by arrecad.k00_numpre,k00_numpar,k00_dtvenc,arrecad.k00_tipo,k00_descr
				  order by arrecad.k00_numpre,arrecad.k00_numpar                  ";
$result = pg_exec($sql);
$numrows = pg_numrows($result);
if ($numrows > 0) {
	echo "
	    <tr class='bordas'>
          <td class='bordas'  title='Inverte marcação' align='center'><a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
	      <td class='bordas' align='center'><b><small>$RLk00_numpre</small></b></td>
	      <td class='bordas' align='center'><b><small>$RLk00_numpar</small></b></td>
	      <td class='bordas' align='center'><b><small>$RLk00_dtvenc</small></b></td>
	      <td class='bordas' align='center'><b><small>$RLk00_valor</small></b></td>
	      <td class='bordas' align='center'><b><small>$RLk00_tipo</small></b></td>
	      <td class='bordas' align='center'><b><small>$RLk00_descr</small></b></td>";
} else {
  	echo "<b>Nenhum registro encontrado...</b>";
}
echo " </tr>";
for ($i = 0; $i < $numrows; $i ++) {
	db_fieldsmemory($result, $i);
	$sql_marca = "select arrecad.k00_numpre,k00_numpar,arrecad.k00_tipo 
                  from debcontapedidotiponumpre 
                       inner join arrecad    on arrecad.k00_numpre = d67_numpre 
                                            and arrecad.k00_numpar = d67_numpar
                       inner join arretipo   on arrecad.k00_tipo   = arretipo.k00_tipo
                       inner join arreinstit on arrecad.k00_numpre = arreinstit.k00_numpre
                 where d67_codigo = $codigo 
                   and d67_numpar = $k00_numpar
                   and d67_numpre = $k00_numpre
                   and arreinstit.k00_instit = ".db_getsession("DB_instit");
                  
    $chek="";
    $result_marca=pg_exec($sql_marca);               
    if (pg_numrows($result_marca)>0){
    	$chek="checked";
    }
	echo "<tr>	    
                    <td class='bordas_corp' title='Inverte a marcação' align='center'><input type='checkbox' name='CHECK_$k00_numpre"."_".$k00_numpar."_".$k00_tipo."' id='CHECK_$k00_numpre"."_".$k00_numpar."_".$k00_tipo."' $chek ></td>
   	            	<td	 class='bordas_corp' align='center'><small>$k00_numpre</small></td>
					<td	 class='bordas_corp' align='center'><small>$k00_numpar</small></td>
					<td	 class='bordas_corp' align='center'><small>".db_formatar($k00_dtvenc,"d")."</small></td>
					<td	 class='bordas_corp' align='center'><small>".db_formatar($k00_valor,"f")."</small></td>
					<td	 class='bordas_corp' align='center'><small>$k00_tipo</small></td>
					<td	 class='bordas_corp' align='center'><small>$k00_descr</small></td>					   	            	
               </tr> ";
}
?>     
 </table>
    </form> 
    </center>
    </td>
  </tr>
</table>
<script>
</script>
</body>
</html>