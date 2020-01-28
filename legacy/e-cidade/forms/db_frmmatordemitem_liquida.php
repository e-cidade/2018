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


require ("../libs/db_stdlib.php");
require ("../libs/db_conecta.php");
include ("../libs/db_sessoes.php");
include ("../libs/db_usuariosonline.php");
include ("../classes/db_matordem_classe.php");
include ("../classes/db_matordemitem_classe.php");
include ("../dbforms/db_funcoes.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$errosomaquant = 0;
$passou = false;

$clmatordemitem = new cl_matordemitem;
$clmatordem = new cl_matordem;

$clrotulo = new rotulocampo;
$clmatordem->rotulo->label();
$clrotulo->label("e60_codemp");
$clrotulo->label("e60_numemp");
$clrotulo->label("e62_item");
$clrotulo->label("e62_descr");
$clrotulo->label("e62_sequen");
$clrotulo->label("e62_quant");
$clrotulo->label("pc01_descrmater");
$quant = "";
$soma = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
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
<script>
function pesquisa_emp(qchave){
 js_OpenJanelaIframe('top.corpo.iframe_oc','db_janelaReceita','func_empempenho001.php?fechar=top.corpo.db_janelaReceita&e60_numemp='+qchave,'Dados Cadastrais do Empenho',true,10);
}
</script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0"> 
<table  border="0" cellspacing="0" cellpadding="0" width='100%'>
<tr> 
<td  align="left" valign="top" bgcolor="#CCCCCC"> 
<form name='form1'>
<center>
      
<table border='1' cellspacing="0" cellpadding="0">   
<?


$where = "";
$where1 = "";

if (isset ($e60_numemp)) {
	$where = "and e60_numemp = $e60_numemp";
}

if (isset ($e60_codemp)) {
	$where1 = "and e60_codemp = $e60_codemp";
}

if ((isset ($e60_numcgm) && $e60_numcgm != "") && (isset ($erro) && $erro == 'false')) {
	$sql = ("
       select e60_numemp,e60_codemp, 
                  e62_item, 
              	  pc01_descrmater, 
			      e62_sequen, 
			      e62_descr, 
			      e62_quant,
			      pc01_servico,
			      e62_vltot
	   from empempenho 
			      inner join empempitem on e62_numemp = e60_numemp 
			      inner join pcmater on pc01_codmater = e62_item
			      inner join pcsubgrupo on pc04_codsubgrupo = pc01_codsubgrupo
			      inner join pctipo on pc05_codtipo = pc04_codtipo
	   where e60_numcgm = $e60_numcgm $where $where1
	   order by e60_numemp");
	$result = pg_exec($sql);
	$numrows = pg_numrows($result);

	if ($numrows > 0) {
		echo "<tr class='bordas'>
		          <td class='bordas'  title='Inverte marcação' align='center'>
                         <a  title='Inverte Marcação' href='' onclick='return js_marca(this);return false;'>M</a></td>
		          <td class='bordas' align='center'><b><small>$RLe60_codemp</small></b></td>
		          <td class='bordas' align='center'><b><small>$RLe60_numemp</small></b></td>
		          <td class='bordas' align='center'><b><small>$RLe62_item</small></b></td>
		          <td class='bordas' align='center'><b><small>$RLpc01_descrmater</small></b></td>
		          <td class='bordas' align='center'><b><small>$RLe62_sequen</small></b></td>
		          <td class='bordas' align='center'><b><small>$RLe62_descr</small></b></td>
	              <td class='bordas' align='center'><b><small>$RLe62_quant</small></b></td>
	              <td class='bordas' align='center'><b><small>Valor Total</small></b></td>
		          <td class='bordas' align='center'><b><small>Quantidade</small></b></td>
		          <td class='bordas' align='center'><b><small>Valor</small></b></td>";
	} else
		if ($numrows == 0) {
			echo "<b>Nenhum registro encontrado...</b>";
		}
	echo "</tr>";


	// variavel que controla o indice dos itens	 
    $i =0;
	for ($linha = 0; $linha < $numrows; $linha ++) {

		db_fieldsmemory($result, $linha, true);

		$result_acha = $clmatordemitem->sql_record($clmatordemitem->sql_query_file(null, "sum(m52_quant) as somaquant", "", "m52_numemp=$e60_numemp and m52_sequen=$e62_sequen and m52_codordem not in (select m53_codordem from matordemanu)group by m52_numemp,m52_sequen"));

		if ($clmatordemitem->numrows == 0) {
			$_achou = 0;
		} else {
			$_achou = 1;
			$result2 = $clmatordemitem->sql_record($clmatordemitem->sql_query_file(null, "sum(m52_quant) as somaquant,sum(m52_valor) as somaval", "", "m52_numemp=$e60_numemp and m52_sequen=$e62_sequen and m52_codordem not in (select m53_codordem from matordemanu) group by m52_numemp,m52_sequen "));

			if ($clmatordemitem->numrows != 0) {
				db_fieldsmemory($result2, 0);
				if ($pc01_servico == 'f') {
					$soma = $somaquant - $e62_quant;
				} else
					if ($pc01_servico == 't') {

						$soma = $somaval - $e62_vltot;

					}
			}

		}
		if ($soma == 0 and $_achou == 1) {
			if ($pc01_servico == 't') {

				if ($soma == 0) {
					$errosomaquant ++;
				}
			} else {
				$errosomaquant ++;
			}
		} else {
			// entra aqui quando da pesquisa por cgm			
			// monta o checkbox  que é selecionado
			
			echo "<tr>	    
			       	  <td class='bordas_corp' title='Inverte a marcação' align='center'>
						<input 
                            type='checkbox' 
                            name='CHECK_$e60_numemp"."_"."$e62_sequen"."' 
                            id='CHECK_".$e60_numemp."_"."$e62_sequen"."'
                           onchange='js_atuValor();'
					        ></td>
		              <td class='bordas_corp' align='center'><small>$e60_codemp </small></td>
		   	       	  <td class='bordas_corp' align='center'>$e60_numemp</td>
			       	  <td class='bordas_corp' align='center'><small>$e62_item  </small></td>		    
			     	  <td class='bordas_corp' nowrap align='left' title='$pc01_descrmater'><small>".substr($pc01_descrmater, 0, 20)."&nbsp;</small></td>
			     	  <td class='bordas_corp' align='center'><small>$e62_sequen</small></td>
		              <td class='bordas_corp' nowrap align='left' title='$e62_descr'><small>".substr($e62_descr, 0, 20)."&nbsp;</small></td>";

 			// pesquisa ítens cuja ordem não tenha sido anulada 			
			$result1 = $clmatordemitem->sql_record(
			                 $clmatordemitem->sql_query_anulado(
			                            null, 
  									   "sum(m52_quant) as m52_quant,
										sum(m52_valor) as m52_valor,
										e62_quant,e62_vltot",
										"", 
									   "m52_numemp=$e60_numemp and 
                                        m52_sequen=$e62_sequen and 
                                        m53_codordem is null  
										group by m52_sequen,m52_numemp,e62_quant,e62_vltot"));
		
			$valoruni = $e62_vltot / $e62_quant;
			if ($clmatordemitem->numrows == 0) {
				$valoruni = $e62_vltot / $e62_quant;
				$quant = "quant_$e60_numemp"."_"."$e62_sequen"."_"."$i";
				$$quant = $e62_quant;
				$val = "valor_$i"."_"."$e60_numemp"."_"."$e62_sequen";
				$$val = $e62_vltot;
				if ($e62_quant > 0) {
					echo "<td class='bordas_corp' align='center'><small>$e62_quant</small></td>
						   	  <td class='bordas_corp' align='center'><small>$e62_vltot</small></td>";
					if ($pc01_servico == 'f') {
						// quando não for serviço
						echo "<td class='bordas_corp' align='center'><small>";
						db_input("quant_$e60_numemp"."_"."$e62_sequen"."_"."$i", 6, 0, true, 'text', 1, "onchange='js_verifica($e62_quant,this.value,this.name,$valoruni,$e60_numemp,$e62_sequen);'");
						echo "</small></td>
							      <td class='bordas_corp' align='center'><small>";
						db_input("valor_$i"."_"."$e60_numemp"."_"."$e62_sequen", 6, 0, true, 'text', 3, '', '', '', 'text-align:right');
						echo "</small></td>";
						echo "</tr> ";
						$i++;
					} else {
						// quando for serviço
						$quan = "quant_$e60_numemp"."_"."$e62_sequen"."_"."$i";
						$$quan = $e62_quant;
						echo "<td class='bordas_corp' align='center'><small>";
						db_input("quant_$e60_numemp"."_"."$e62_sequen"."_"."$i", 6, 0, true, 'text', 3);
						echo "</small></td>
						               <td class='bordas_corp' align='center'><small>";
						db_input("valor_$i"."_"."$e60_numemp"."_"."$e62_sequen", 6, 0, true, 'text', 1, '', '', '', 'text-align:right');
						echo "</small></td>";
						echo "</tr> ";
						$i++;
					}
				}
			} else {
				db_fieldsmemory($result1, 0);
				$quantidade = $e62_quant - $m52_quant;
				$valorresta = $valoruni * $quantidade;
				$quant = "quant_$e60_numemp"."_"."$e62_sequen"."_"."$i";
				$val = "valor_$i"."_"."$e60_numemp"."_"."$e62_sequen";
				if ($pc01_servico == "f") {
					$$val = $valorresta;
					$$quant = $quantidade;
					echo "<td class='bordas_corp' align='center'><small>$quantidade</small></td>
					    	  <td class='bordas_corp' align='center'><small>$valorresta</small></td>
					          <td class='bordas_corp' align='center'><small>";
					db_input("quant_$e60_numemp"."_"."$e62_sequen"."_"."$i", 6, 0, true, 'text', 1, "onchange='js_verifica($quantidade,this.value,this.name,$valoruni,$e60_numemp,$e62_sequen);'");
					echo "</small></td>
						      <td class='bordas_corp' align='center'><small>";
					db_input("valor_$i"."_"."$e60_numemp"."_"."$e62_sequen", 6, 0, true, 'text', 3);
					echo "</small></td>";
					echo "</tr> ";
					$i++;
				} else {
					$quant = "quant_$e60_numemp"."_"."$e62_sequen"."_"."$i";
					$val = "valor_$i"."_"."$e60_numemp"."_"."$e62_sequen";
					$$quant = $e62_quant;
					
					$valo = $e62_vltot - $m52_valor;
					$$val = db_formatar($valo, 'f');
					$valorrestante = db_formatar($$val, 'f');
					echo "<td class='bordas_corp' align='center'><small>$e62_quant</small></td>
					    	  <td class='bordas_corp' align='center'><small>$valorrestante</small></td>
					          <td class='bordas_corp' align='center'><small>";
					db_input("quant_$e60_numemp"."_"."$e62_sequen"."_"."$i", 6, 0, true, 'text', 3);
					echo "</small></td>
						      <td class='bordas_corp' align='center'><small>";
					db_input("valor_$i"."_"."$e60_numemp"."_"."$e62_sequen", 10, 0, true, 'text', 1, '', '', '', 'text-align:right');
					echo "</small></td>";
					echo "</tr> ";
					$i++;
				}
			}			
		}		
	}
	if (($errosomaquant == $numrows) && ($numrows != 0)) {
		echo "<script>location.href='db_frmmatordemitem.php?erro=true&numrows=$numrows&e60_numcgm=$e60_numcgm'</script>";
	}
} else
	if ((isset ($erro) && $erro == "true") && (isset ($numrows) && $numrows != 0)) {
		echo "foram emitidas ordens de compra para todos os itens !!...";
		echo "<script>parent.document.form1.incluir.disabled=true</script>";
		echo " <tr class='bordas'>
			       <td class='bordas' align='center'><b>Codigo </b></td>
			       <td class='bordas' align='center'><b>Empenho </b></td>
			       <td class='bordas' align='center'><b>Data    </b></td>
			       <td class='bordas' align='center'><b>$RLm51_obs</b></td>
			       <td class='bordas' align='center'><b>Valor Total</b></td>
			     </tr>  
			     ";

		$result_ordem = $clmatordem->sql_record($clmatordem->sql_query_numemp("", "(e60_codemp||'/'||e60_anousu) as e60_codemp,m51_codordem,m51_data,m51_obs,m51_valortotal", "", "m51_numcgm=$e60_numcgm $where $where1 and m51_codordem not in(select m53_codordem from matordemanu)"));
		for ($x = 0; $x < $clmatordem->numrows; $x ++) {
			db_fieldsmemory($result_ordem, $x);
			echo "<tr>	    
			             <td class='bordas_corp' align='center'>$m51_codordem </td>
			             <td class='bordas_corp' align='center'>$e60_codemp   </td>
			             <td class='bordas_corp' align='center'>$m51_data     </td>
			             <td class='bordas_corp' align='left' title='$m51_obs'  >".substr($m51_obs, 0, 40)."&nbsp</td>		    
			             <td class='bordas_corp' align='center'>".db_formatar($m51_valortotal, 'f')."</td>
			          </tr>";
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
function js_verifica(max,quan,nome,valoruni,numemp,sequen){
 	if (max<quan){
	    alert("Informe uma quantidade valida!!");
	    eval("document.form1."+nome+".value='';");
	    eval("document.form1."+nome+".focus();");
 	}else{
	    i=nome.split("_");
	    pos=i[3];
	    quant=new Number(quan);
	    valor=new Number(valoruni);
	    valortot=quant*valor;
	    eval("document.form1.valor_"+pos+"_"+numemp+"_"+sequen+".value=valortot.toFixed(2)");
	    // 	atualiza aba de retenções
	    // 		alert(valortot);
	    //		js_atuValor(valortot);
 	}   
}

function js_atuValor(){
    // alert('atualizando valores');
	// soma os valores selecionados e atualiza a aba de retenções
	obj =document.form1;
	var tvalor =0;	
	cont  = 0;
	for (linha=0;linha < obj.length;linha++){
	       if (obj[linha].type=='checkbox'){
	              nome = obj[linha].name;
	              // alert(nome);
	              	              
 				  i=nome.split("_");				
 				  empenho = i[1];
 				  sequencial  = i[2];  
 				  // pos=i[3]; // não definida
 				  		  
                  valor = eval("document.form1.valor_"+cont+"_"+empenho+"_"+sequencial+".value;");
           		  if (obj[linha].checked==true)	                                                        
                       tvalor = parseFloat(tvalor)+ parseFloat(valor);
                                         
                  cont++;
	       }
	}	
	// alert('  Atualizando valor >> ' + tvalor);
	top.corpo.iframe_ret.setValorNota(tvalor);		 
}
</script>
</body>
</html>