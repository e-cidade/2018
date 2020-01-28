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
include("classes/db_averbacgm_classe.php");
include("classes/db_averbacgmold_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$claverbacgm = new cl_averbacgm;
$claverbacgmold = new cl_averbacgmold;
$claverbacgm->rotulo->label();
$claverbacgmold->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<script>


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
 $where="";
 
 if (isset($codigo) && $codigo!= "") {

      $result=$claverbacgm->sql_record($claverbacgm->sql_query(null,"*",null,"j76_averbacao=$codigo"));
      $numrows = $claverbacgm->numrows;
	if($numrows>0){
	echo "
	    <tr class='bordas'>
	      <td class='bordas' align='center' colspan=4 ><b><small>Adquirentes</small></b></td>       
			</tr>
	    <tr class='bordas'>
	      <td class='bordas' align='center'><b><small>$RLj76_codigo</small></b></td>
        <td class='bordas' align='center'><b><small>$RLj76_numcgm</small></b></td>
        <td class='bordas' align='center'><b><small>$RLz01_nome</small></b></td>
        <td class='bordas' align='center'><b><small>$RLj76_principal</small></b></td>
	    ";
              }else echo"<b>Nenhum registro encontrado...</b>";
	 echo " </tr>";
         for($i=0; $i<$numrows; $i++){
         	db_fieldsmemory($result,$i);
	    if ($j76_principal=='t'){
	    	$j76_principal = "Sim";
	    }else{
	    	$j76_principal = "Não";
	    }
	     echo "<tr>	    
   	            <td	 class='bordas_corp' align='center'><small>$j76_codigo </small></td>
   	            <td	 class='bordas_corp' align='center'><small>$j76_numcgm </small></td>
				<td	 class='bordas_corp' align='center'><small>$z01_nome</small></td>
		<td	 class='bordas_corp' align='center'><small>$j76_principal</small></td>
	          </tr> ";
	 }
 }
?>     
 </table>
 
 <br><br>
  
 <table border='1' cellspacing="0" cellpadding="0">   
 
 <?
 $where="";
 
 if (isset($codigo) && $codigo!= "") {
    
      $result=$claverbacgmold->sql_record($claverbacgmold->sql_query(null,"*",null,"j79_averbacao=$codigo"));
      $numrows = $claverbacgmold->numrows;
	if($numrows>0){
	echo "
	    <tr class='bordas'>
	      <td class='bordas' align='center' colspan=4 ><b><small>Transmitentes</small></b></td>       
			</tr>
	    <tr class='bordas'>
	      <td class='bordas' align='center'><b><small>$RLj79_codigo</small></b></td>
<td class='bordas' align='center'><b><small>$RLj79_numcgm</small></b></td>
<td class='bordas' align='center'><b><small>$RLz01_nome</small></b></td>
<td class='bordas' align='center'><b><small>$RLj79_principal</small></b></td>
	    ";
              }else{ 
								echo"
	    <tr class='bordas'>
	      <td class='bordas' align='center' colspan=4 ><b><small>Transmitentes</small></b></td>       
			</tr>
	    <tr class='bordas'>
	      <td class='bordas' align='center' colspan=4 ><b><small>Nenhum registro encontrado...</small></b></td>       
				";
							}
	 echo " </tr>";
         for($i=0; $i<$numrows; $i++){
         	db_fieldsmemory($result,$i);
	    if ($j79_principal=='t'){
	    	$j79_principal = "Sim";
	    }else{
	    	$j79_principal = "Não";
	    }
	     echo "<tr>	    
   	            <td	 class='bordas_corp' align='center'><small>$j79_codigo </small></td>
   	            <td	 class='bordas_corp' align='center'><small>$j79_numcgm </small></td>
				<td	 class='bordas_corp' align='center'><small>$z01_nome</small></td>
		<td	 class='bordas_corp' align='center'><small>$j79_principal</small></td>
	          </tr> ";
	 }
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