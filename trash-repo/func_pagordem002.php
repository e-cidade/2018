<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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
include ("classes/db_empelemento_classe.php");
include ("classes/db_empempenho_classe.php");
include ("classes/db_pagordemele_classe.php");
include ("classes/db_pagordem_classe.php");
include ("classes/db_cgm_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clempelemento = new cl_empelemento;
$clempempenho  = new cl_empempenho;
$clpagordemele = new cl_pagordemele;
$clpagordem    = new cl_pagordem;
$clcgm         = new cl_cgm;

$clrotulo = new rotulocampo;
$clrotulo->label("e50_data");
$clrotulo->label("e53_valor");
$clrotulo->label("e53_vlrpag");
$clrotulo->label("e53_vlranu");
$clrotulo->label("e50_codord");
$clrotulo->label("c80_data");
$clrotulo->label("c61_reduz");
$clrotulo->label("c60_descr");
$clrotulo->label("c70_valor");
$RLc70_valor = "Valor";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC" >
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
  <tr> 
    <td  align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    </center>
    <form name='form1'>
    <center>
<?


if (isset ($e60_numemp) && $e60_numemp != "") {
	//rotina que traz os dados do empenho
	$ordem = "";
	$result = $clempempenho->sql_record($clempempenho->sql_query_file($e60_numemp));
	db_fieldsmemory($result, 0);
	//fim  

	$result02 = $clpagordem->sql_record(
	                    $clpagordem->sql_query_pag(
	                          null,
	                          'e50_codord as codord,e50_data,c80_data,c61_reduz,c60_descr,c70_valor,c53_tipo,c53_descr,e49_numcgm,z01_numcgm,z01_nome',
	                          'e50_codord ',
	                          "e50_numemp=$e60_numemp    "));
	$numrows02 = $clpagordem->numrows;

//	db_criatabela($result02);

	if ($numrows02 > 0) {
		echo "<table border='1' cellspacing='1' cellpadding='0' class='bordas'> ";
		echo "<tr>";
		echo "<td class='bordas02' colspan='7' align='center' nowrap >
				    		    <b><small>Pagamentos</small></b>
						   </td>
		                   <td class='bordas02' colspan='7' align='center' nowrap >
				    		    <b><small>DADOS DO LANÇAMENTO</small></b>
						   </td>";
		echo "</tr>";
		echo "<tr class='bordas' >
              <td class='bordas02' align='center'><b>Nota </b></td>
						<td class='bordas02' align='center'><b>$RLe53_valor  </b></td>
						<td class='bordas02' align='center'><b>$RLe53_vlrpag </b></td>
						<td class='bordas02' align='center'><b>$RLe53_vlranu </b></td>
						<td class='bordas02' align='center'><b>$RLe50_data   </b></td>
						<td class='bordas02' align='center'><b>Cgm           </b></td>
						<td class='bordas02' align='center'><b>Credor        </b></td>";

		echo "
				<td class='bordas02' align='center'><b>Tipo    </b></td>
		                <td class='bordas02' align='center'><b>$RLc70_valor    </b></td>
		                <td class='bordas02' align='center'><b>$RLc80_data    </b></td>
		                <td class='bordas02' align='center'><b>$RLc61_reduz   </b></td>
		                <td class='bordas02' align='center'><b>$RLc60_descr   </b></td>
					    </tr>	  
				           ";
		//<td style='border-style:outset' align='center'><b>Saldo</b></td>

		$tot22_valor = 0;
		$tot22_vlrpag = 0;
		$tot22_vlranu = 0;
		for ($e = 0; $e < $numrows02; $e ++) {
			db_fieldsmemory($result02, $e);
			$result = $clpagordemele->sql_record($clpagordemele->sql_query_file($codord));
			$numrows = $clpagordemele->numrows;
			//rotina que tot2aliza os valores
			$tot2_vlrpag = '0.00';
			$tot2_vlranu = '0.00';
			$tot2_valor = '0.00';
			for ($i = 0; $i < $numrows; $i ++) {
				db_fieldsmemory($result, $i);
				$tot2_valor += $e53_valor;
				$tot2_vlrpag += $e53_vlrpag;
				$tot2_vlranu += $e53_vlranu;
			}
			//fim
			if ($ordem == "" || $ordem != $codord) {
				$ordem = $codord;
				echo "<tr  class='bordas'>
				 	         	  <td  class='bordas' align='center'>$codord</td>
					 			  <td  class='bordas' align='right'>".db_formatar($tot2_valor, "f")."</td>
					 			  <td  class='bordas' align='right'>".db_formatar($tot2_vlrpag, "f")."</td>
					  			  <td  class='bordas' align='right'>".db_formatar($tot2_vlranu, "f")."</td>
					 			  <td  class='bordas' align='right'>".db_formatar($e50_data, "d")."</td>";
        if (isset($e49_numcgm) && trim(@$e49_numcgm)!=""){
             if ($e49_numcgm != $z01_numcgm){
                  $res_cgm = $clcgm->sql_record($clcgm->sql_query_file($e49_numcgm,"z01_numcgm,z01_nome"));            
                  if ($clcgm->numrows > 0){
                       db_fieldsmemory($res_cgm,0);
                  }
             }
        }

        echo "    <td  class='bordas' align='right'>".$z01_numcgm."</td>
					 			  <td  class='bordas' align='left'>".$z01_nome."</td>";

				$tot22_valor += $tot2_valor;
				$tot22_vlrpag += $tot2_vlrpag;
				$tot22_vlranu += $tot2_vlranu;
			} else {
				echo "<tr  class='bordas'><td colspan='7'>&nbsp;</td>";
			}
			/*
			if ($c53_tipo == 30 ){
				echo "<td  class='bordas' align='right'>PGTO</td>";				
			}		elseif ($c53_tipo <> "") {
				echo "<td  class='bordas' align='right'>ESTORNO</td>";
			}
			*/
			echo "<td  class='bordas' align='right'>$c53_descr</td>";
			echo "<td  class='bordas' align='right'>".($c53_tipo == ""?"":db_formatar($c70_valor, "f"))."</td>
					   <td  class='bordas' align='center'>".($c53_tipo == ""?"":db_formatar($c80_data, "d"))."</td>
			           <td  class='bordas' align='center'>".($c53_tipo == ""?"":$c61_reduz)."</td>
			           <td  class='bordas' align='left'  nowrap>".($c53_tipo == ""?"":$c60_descr)."</td>
					   </tr>	  
					    ";

		}
		echo "<tr >
					   	  <td class='bordas'  align='center'><b>Total</b></td>
						  <td class='bordas'  align='right'><b>".db_formatar($tot22_valor, "f")."</b></td>
						  <td class='bordas'  align='right'><b>".db_formatar($tot22_vlrpag, "f")."</b></td>
						  <td class='bordas'  align='right'><b>".db_formatar($tot22_vlranu, "f")."</b></td>
					   	  <td class='bordas'  align='center'><b>&nbsp;</b></td>
					   	  <td class='bordas'  align='center'><b>&nbsp;</b></td>
					   	  <td class='bordas'  align='center'><b>&nbsp;</b></td>
				   </tr>	  
				   ";
		echo "</table>	  ";
		//<td  align='center'><b>".db_formatar(($e60_vlrliq - $e60_vlrpag)-($tot22_valor-$tot22_vlranu-$tot22_vlrpag),"f")."</b></td>
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