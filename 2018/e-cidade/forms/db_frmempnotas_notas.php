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

require("../libs/db_stdlib.php");
require("../libs/db_conecta.php");
include("../libs/db_sessoes.php");
include("../libs/db_usuariosonline.php");
include("../classes/db_empelemento_classe.php");
include("../classes/db_empempenho_classe.php");
include("../classes/db_empnotaele_classe.php");
include("../classes/db_empnota_classe.php");
include("../dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempelemento = new cl_empelemento;
$clempempenho = new cl_empempenho;
$clempnotaele = new cl_empnotaele;
$clempnota = new cl_empnota;

$clempnotaele->rotulo->label();
$clempnota->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("e69_data");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<style>
<?$cor="#999999"?>
.bordas02{
         border: 2px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #999999;
}
.bordas{
         border: 1px solid #cccccc;
         border-top-color: <?=$cor?>;
         border-right-color: <?=$cor?>;
         border-left-color: <?=$cor?>;
         border-bottom-color: <?=$cor?>;
         background-color: #cccccc;
}
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table  border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
    <form name='form1'>
<?
if(isset($e60_numemp) && $e60_numemp!=""){
   //rotina que traz os dados do empenho
     $result = $clempempenho->sql_record($clempempenho->sql_query_file($e60_numemp)); 
     db_fieldsmemory($result,0);
   	//fim  
   
   $result02 = $clempnota->sql_record($clempnota->sql_query_file(null,"*",'',"e69_numemp=$e60_numemp")); 
   $numrows02 = $clempnota->numrows;
   
   if($numrows02>0){
        echo "<table border='1' cellspacing='1' cellpadding='0' class='bordas'> ";
	echo "<tr>";
        echo "  <td class='bordas02' colspan='6' align='center' nowrap >
    		    <b><small>NOTAS DO EMPENHO</small></b>
		 </td>";
	echo "</tr>";
 	echo "<tr class='bordas' >
		<td class='bordas02' align='center'><b>$RLe70_codnota</b></td>
		<td class='bordas02' align='center'><b>$RLe69_numero</b></td>
		<td class='bordas02' align='center'><b>$RLe70_valor</b></td>
		<td class='bordas02' align='center'><b>Liquidado</b></td>
		<td class='bordas02' align='center'><b>Anulado</b></td>
		  <td class='bordas02' align='center'><b>$RLe69_dtnota</b></td>
	       </tr>	  
           ";
		//<td style='border-style:outset' align='center'><b>Saldo</b></td>
  
	 $tot2_valor  = 0 ;
	 $tot2_vlrliq = 0  ;          
	 $tot2_vlranu = 0 ;
	 for($e=0; $e<$numrows02; $e++){ 
	   db_fieldsmemory($result02,$e);
	   $result  = $clempnotaele->sql_record($clempnotaele->sql_query_file($e69_codnota,null,"sum(e70_valor) as tot_valor,sum(e70_vlranu) as tot_vlranu,sum(e70_vlrliq) as tot_vlrliq")); 
	   $numrows = $clempnotaele->numrows;
	   //rotina que totaliza os valores
	   if($numrows==0){
	     $tot_valor  = '0.00' ;
             $tot_vlrliq = "0.00"  ;          
	     $tot_vlranu = "0.00" ;
	   }else{
	       db_fieldsmemory($result,0);
	   }
	   //fim
 	    echo "<tr  class='bordas'>
	    	  <td  class='bordas' align='center'>$e69_codnota</td>
	    	  <td  class='bordas' align='center'><small>$e69_numero</small></td>
		  <td  class='bordas' align='center'>".db_formatar($tot_valor,"f")."</td>
		  <td  class='bordas' align='center'>".db_formatar($tot_vlrliq,"f")."</td>
		  <td  class='bordas' align='center'>".db_formatar($tot_vlranu,"f")."</td>
		  <td  class='bordas' align='center'>".db_formatar($e69_dtnota,"d")."</td>
	         </tr>	  
            ";
	    $tot2_valor  +=  $tot_valor ;
	    $tot2_vlrliq +=  $tot_vlrliq;          
	    $tot2_vlranu +=  $tot_vlranu;
	 }  
 	    echo "<tr >
	    	  <td class='bordas' colspan ='2'  align='right'><b>Total</b></td>
		  <td class='bordas'  align='center'><b>".db_formatar($tot2_valor,"f")."</b></td>
		  <td class='bordas'  align='center'><b>".db_formatar($tot2_vlrliq,"f")."</b></td>
		  <td class='bordas'  align='center'><b>".db_formatar($tot2_vlranu,"f")."</b></td>
	    	  <td class='bordas'  align='center'><b>&nbsp;</b></td>
	         </tr>	  
            ";
      echo "</table>	  ";
   }  
}
?>

      
    </form> 
    </td>
  </tr>
</table>
</body>
</html>