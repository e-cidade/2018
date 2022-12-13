<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2013  DBseller Servicos de Informatica
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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("classes/db_matordem_classe.php"));
require_once(modification("classes/db_matordemitem_classe.php"));
require_once(modification("classes/db_matestoqueitemoc_classe.php"));
require_once(modification("dbforms/db_funcoes.php"));

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

$clmatordemitem     = new cl_matordemitem;
$clmatestoqueitemoc = new cl_matestoqueitemoc;
$clmatordem         = new cl_matordem;
$clrotulo           = new rotulocampo;

$clmatordemitem->rotulo->label();
$clmatordem->rotulo->label();

$clrotulo->label("e62_item");
$clrotulo->label("e60_numemp");
$clrotulo->label("e60_codemp");
$clrotulo->label("pc01_descrmater");
$clrotulo->label("e62_descr");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="../scripts/scripts.js"></script>
<link href="../estilos.css" rel="stylesheet" type="text/css">
<link href="../estilos/grid.style.css" rel="stylesheet" type="text/css">
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
  				<fieldset> 
						<legend>
							<b>Itens</b>
						</legend>
						<table style='border:2px inset white' width='100%' cellspacing='0'>   
							 <?
								
								if (isset ($m51_codordem) && $m51_codordem != "") {

									$result = $clmatordemitem->sql_record($clmatordemitem->sql_query_servico(null, "*", "", "m52_codordem=$m51_codordem"));
									$numrows = $clmatordemitem->numrows;
									

									if ($numrows > 0) {
										
										echo "   <tr class='bordas'>";
										echo "     <td class='table_header' align='center'><small><b>$RLe60_codemp		 	</b></small></td>";
										echo "     <td class='table_header' align='center'><small><b>$RLe60_numemp		 	</b></small></td>";
										echo "     <td class='table_header' align='center'><small><b>$RLe62_item  		  </b></small></td>";
										echo "     <td class='table_header' align='center'><small><b>$RLpc01_descrmater </b></small></td>";
										echo "     <td class='table_header' align='center'><small><b>$RLm52_sequen		  </b></small></td>";
										echo "     <td class='table_header' align='center'><small><b>$RLe62_descr			  </b></small></td>";
										echo "     <td class='table_header' align='center'><small><b>$RLm52_quant			  </b></small></td>";
										echo "     <td class='table_header' align='center'><small><b>Valor						  </b></small></td>";
										echo "   </tr>";
										echo " <tbody id='dados' style='height:150;width:95%;overflow:scroll;overflow-x:hidden;background-color:white'>";

									} else {
										
										echo " <tr>";
										echo "	<b>Nenhum registro encontrado...</b>";
										echo " </tr>";
									
									}

									for ($i = 0; $i < $numrows; $i ++) {
										db_fieldsmemory($result, $i);
									  $sSqlEntrada = $clmatestoqueitemoc->sql_query(null, null, "*", null,
									                      "m73_codmatordemitem=$m52_codlanc and m73_cancelado is false");
										$result2 = $clmatestoqueitemoc->sql_record($sSqlEntrada);
										if ($clmatestoqueitemoc->numrows == 0) {
											echo "<tr>	    
															 <td	class='linhagrid' 			 align='center'											  ><small>$e60_codemp															 </small></td>
															 <td	class='linhagrid' 			 align='center'												><small>$m52_numemp															 </small></td>
															 <td	class='linhagrid' 			 align='center'												><small>$e62_item																 </small></td>		    
															 <td	class='linhagrid' nowrap align='left' title='$pc01_descrmater'><small>".substr($pc01_descrmater, 0, 20)."&nbsp;</small></td>
															 <td	class='linhagrid' 			 align='center'												><small>$m52_sequen															 </small></td>
															 <td	class='linhagrid' nowrap align='left' title='$e62_descr'			><small>".substr($e62_descr, 0, 20)."&nbsp;			 </small></td>";

                      /**
                       * Caso for um materail
                       * Caso for um serviço e este serviço ser controlado por quantidade
                       * 
                       * 
                       * Alterados todos inputs dentro do for dos itens
                       * que tratam valores para db_opcao = 3, não mais permitindo alterações
                       * de valores, dendo que para altera devera ser anulado na rotina de anulação
                       * e incluidos os itens novamente.
                       * 
                       */
										  if ($pc01_servico == 'f' || ($pc01_servico == "t" && $e62_servicoquantidade == "t")) {
												
												$quant 			 = $m52_quant;
												$quantidade  = "quant_$e60_numemp"."_"."$e62_sequen"."_"."$i";
												$$quantidade = $m52_quant;
												$valor 			 = "valor_$i";
												$$valor 		 = db_formatar($m52_valor, 'f');
												$valoruni 	 = $m52_valor / $m52_quant;
												
												echo " 	 <td class='linhagrid' align='center'>";
												echo "		 <small>";
																			 db_input("quant_$e60_numemp"."_"."$e62_sequen"."_"."$i", 6, 0, true, 'text', 3, "onchange='js_verifica($quant,this.value,this.name,$valoruni);'");
												echo "		 </small>";
												echo "	 </td>";
												echo "	 <td class='linhagrid' align='center'>";
												echo "		 <small>";
																			 db_input("valor_$i", 15, 0, true, 'text', 3);
												echo "		 </small>";
												echo "	 </td>";
												echo " </tr>";
											

											} else if ($pc01_servico == 't') {

												$quantidade  = "quant_$e60_numemp"."_"."$e62_sequen"."_"."$i";
												$$quantidade = $m52_quant;
												$valor 			 = "valor_$i";
												$$valor 		 = db_formatar($m52_valor, 'f');
												$valoruni 	 = $m52_valor / $m52_quant;
												
												echo "   <td class='linhagrid' align='center'>";
												echo "		 <small>";
																		 db_input("quant_$e60_numemp"."_"."$e62_sequen"."_"."$i", 6, 0, true, 'text', 3);
												echo "		 </small>";
												echo "	 </td>";
												echo "   <td class='linhagrid' align='center'>";
												echo "		 <small>";
																		 db_input("valor_$i", 6, 0, true, 'text', 3);
												echo "		 </small>";
												echo "	 </td>";
												echo " </tr>";
											}
										
										} else {
												echo " <tr>";
												echo " 	 <td class='linhagrid'				align='center'>												<small>$e60_codemp														  </small></td>";
												echo " 	 <td class='linhagrid'				align='center'>												<small>$m52_numemp														  </small></td>";
												echo "   <td class='linhagrid'				align='center'>												<small>$e62_item															  </small></td>";
												echo "   <td class='linhagrid' nowrap align='left' title='$pc01_descrmater'><small>".substr($pc01_descrmater, 0, 20)."&nbsp;</small></td>";
												echo "   <td class='linhagrid' 			  align='center'>												<small>$m52_sequen														  </small></td>";
												echo "   <td class='linhagrid' nowrap align='left' title='$e62_descr'>			<small>".substr($e62_descr, 0, 20)."&nbsp;			</small></td>";
											
                      /**
                       * Caso for um materail
                       * Caso for um serviço e este serviço ser controlado por quantidade
                       */
											if ($pc01_servico == 'f' || ($pc01_servico == "t" && $e62_servicoquantidade == "t")) {

												db_fieldsmemory($result2, 0);
												$quant 			 = $m52_quant - $m71_quant;
												$quantidade  = "quant_$e60_numemp"."_"."$e62_sequen"."_"."$i";
												$$quantidade = $m52_quant - $m71_quant;
												$valor 			 = "valor_$i";
												$vlr 				 = $m52_valor - $m71_valor;
												$$valor 		 = db_formatar($vlr, 'f');
												$valoruni 	 = $m52_valor / $m52_quant;

												echo "   <td class='linhagrid' align='center'>";
												echo "		 <small>";
																		 db_input("quant_$e60_numemp"."_"."$e62_sequen"."_"."$i", 6, 0, true, 'text', 3, "onchange='js_verifica($quant,this.value,this.name,$valoruni);'");
												echo "		 </small>";
												echo "	 </td>";
												echo "   <td class='linhagrid' align='center'>";
												echo "		 <small>";
																		 db_input("valor_$i", 15, 0, true, 'text', 3);
												echo "		 </small>";
												echo "   </td>";
												echo " </tr>";
											
											} else if ($pc01_servico == 't') {

											    db_fieldsmemory($result2, 0);
												$quantidade  = "quant_$e60_numemp"."_"."$e62_sequen"."_"."$i";
												$$quantidade = $m52_quant -@ $m71_quant;
												$valor 			 = "valor_$i";
												$vlr 				 = $m52_valor - @$m71_valor;
												$$valor 		 = db_formatar($vlr, 'f');
												$valoruni 	 = $m52_valor / $m52_quant;

												echo "	 <td class='linhagrid' align='center'>";
												echo "		 <small>";
																			db_input("quant_$e60_numemp"."_"."$e62_sequen"."_"."$i", 6, 0, true, 'text', 3);
												echo "		 </small>";
												echo "	 </td>";
												echo "	 <td class='linhagrid' align='center'>
																	 <small>";
																		 db_input("valor_$i", 6, 0, true, 'text', 3);
												echo "		 </small>";
												echo "	 </td>";
												echo " </tr>";
											}
										}
									}
								}
							?> 
                            <tr style='height:auto'><td>&nbsp;</td></tr>   
 							</table>
  					</fieldset> 
    			</form> 
    		</center>
    	</td>
  	</tr>
 		<tr>
    	<td nowrap title="<?=@$Tm51_valortotal?>"><?=$Lm51_valortotal?>&nbsp;&nbsp;
				<?  
    	  	db_input("m51_valortotal",10,"",true,"text",3); 
				?>
			</td>
 		</tr>
	</table>
<script>
function js_verifica(max,quan,nome,valoruni){
   
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
   
   eval("document.form1.valor_"+pos+".value=valortot.toFixed(2)");
   
   }
 


}
</script>
</body>
</html>