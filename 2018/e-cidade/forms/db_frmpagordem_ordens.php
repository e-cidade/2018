<?
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2014  DBselller Servicos de Informatica
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
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clempelemento = new cl_empelemento;
$clempempenho = new cl_empempenho;
$clpagordemele = new cl_pagordemele;
$clpagordem = new cl_pagordem;

$clrotulo = new rotulocampo;
$clrotulo->label("e50_data");
$clrotulo->label("e53_valor");
$clrotulo->label("e53_vlrpag");
$clrotulo->label("e53_vlranu");
$clrotulo->label("e50_codord");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js">
</script>
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
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr>
    <td  align="left" valign="top" bgcolor="#CCCCCC">
    <form name='form1'>
    <center>
<?
if(isset($e60_numemp) && $e60_numemp!=""){
   //rotina que traz os dados do empenho
     $result = $clempempenho->sql_record($clempempenho->sql_query_file($e60_numemp));
     db_fieldsmemory($result,0);
   	//fim

   $result02 = $clpagordem->sql_record($clpagordem->sql_query_file(null,'e50_codord as codord,e50_data','',"e50_numemp=$e60_numemp"));
   $numrows02 = $clpagordem->numrows;

   if($numrows02>0){
        echo "<table border='1' cellspacing='1' cellpadding='0' class='bordas'> ";
	echo "<tr>";
        echo "  <td class='bordas02' colspan='5' align='center' nowrap >
    		    <b><small>ORDENS DO EMPENHO</small></b>
		 </td>";
	echo "</tr>";
 	echo "<tr class='bordas' >
		<td class='bordas02' align='center'><b>$RLe50_codord</b></td>
		<td class='bordas02' align='center'><b>$RLe53_valor</b></td>
		<td class='bordas02' align='center'><b>$RLe53_vlrpag</b></td>
		<td class='bordas02' align='center'><b>$RLe53_vlranu</b></td>
		<td class='bordas02' align='center'><b>$RLe50_data</b></td>
	       </tr>
           ";
		//<td style='border-style:outset' align='center'><b>Saldo</b></td>

	 $tot2_valor  = 0 ;
	 $tot2_vlrpag = 0  ;
	 $tot2_vlranu = 0 ;
	 for($e=0; $e<$numrows02; $e++){
	   db_fieldsmemory($result02,$e);
	   $result  = $clpagordem->sql_record($clpagordemele->sql_query_file($codord));
	   $numrows = $clpagordem->numrows;
	   //rotina que totaliza os valores
               $tot_vlrpag  = '0.00';
               $tot_vlranu = '0.00';
               $tot_valor  = '0.00';
	     for($i=0; $i<$numrows; $i++){
	       db_fieldsmemory($result,$i);
	       $tot_valor  += $e53_valor ;
	       $tot_vlrpag += $e53_vlrpag;
	       $tot_vlranu += $e53_vlranu;
	     }
	   //fim
 	    echo "<tr  class='bordas'>
	    	  <td


		  class='bordas' align='center'>

		  $codord


       <input name='imprimir' type='button' id='imprimir' value='I' onclick='js_imprimir2($codord);' >

		  </td>
		  <td  class='bordas' align='center'>".db_formatar($tot_valor,"f")."</td>
		  <td  class='bordas' align='center'>".db_formatar($tot_vlrpag,"f")."</td>
		  <td  class='bordas' align='center'>".db_formatar($tot_vlranu,"f")."</td>
		  <td  class='bordas' align='center'>".db_formatar($e50_data,"d")."</td>
	         </tr>
            ";
	    $tot2_valor  +=  $tot_valor ;
	    $tot2_vlrpag +=  $tot_vlrpag;
	    $tot2_vlranu +=  $tot_vlranu;
	 }
 	    echo "<tr >
	    	  <td class='bordas'  align='center'><b>Total</b></td>
		  <td class='bordas'  align='center'><b>".db_formatar($tot2_valor,"f")."</b></td>
		  <td class='bordas'  align='center'><b>".db_formatar($tot2_vlrpag,"f")."</b></td>
		  <td class='bordas'  align='center'><b>".db_formatar($tot2_vlranu,"f")."</b></td>
	    	  <td class='bordas'  align='center'><b>&nbsp;</b></td>
	         </tr>
            ";
      echo "</table>	  ";
	    	  //<td  align='center'><b>".db_formatar(($e60_vlrliq - $e60_vlrpag)-($tot2_valor-$tot2_vlranu-$tot2_vlrpag),"f")."</b></td>
   }
}
?>


    </center>
    </form>
    </td>
  </tr>
</table>
</body>
</html>