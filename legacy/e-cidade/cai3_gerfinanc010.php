<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

set_time_limit(0);

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_sql.php");
require_once("dbforms/db_funcoes.php");


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clrotulo = new rotulocampo;
$clrotulo->label("v01_exerc");


$sTipoRelatorio = (isset($tiposoma) || ( isset($tiposoma) && ($tiposoma=="Por Tipo") )) ? "" :"";

if (isset($db_datausu)) {
  if (!checkdate(substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4))) {
    echo "Data para Clculo Invlida. <br><br>";
    echo "Data dever se superior a : ".date('Y-m-d',db_getsession("DB_datausu"));
    exit;
  }
  if (mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4)) <
      mktime(0,0,0,date('m',db_getsession("DB_datausu")),date('d',db_getsession("DB_datausu")),date('Y',db_getsession("DB_datausu"))) ) {
    echo "Data no permitida para clculo. <br><br>";
    echo "Data dever se superior a : ".date('Y-m-d',db_getsession("DB_datausu"));
    exit;
  }
  $DB_DATACALC = mktime(0,0,0,substr($db_datausu,5,2),substr($db_datausu,8,2),substr($db_datausu,0,4));
} else {
  $DB_DATACALC = db_getsession("DB_datausu");
}
//$parametrob = "'width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 '";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/strings.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_limpadtfim(){
	document.form1.data2_ano.value = "";
	document.form1.data2_mes.value = "";
	document.form1.data2_dia.value = "";
	document.form1.data2.value = "";
}
function js_limpaexercfim(){
	document.form1.exercfim.value = "";	
}
function js_testaexerc(){
	if (document.form1.exercfim.value<document.form1.exercini.value){
		alert("Exercicio inicial não pode ser maior que o exercicio final!!");
		document.form1.exercfim.value = "";
		document.form1.exercini.value = "";
		document.form1.exercini.focus();
	}	
}
function js_testadt(){
  if (document.form1.data2_ano.value!=""&&document.form1.data2_mes.value!=""&&document.form1.data2_dia.value!=""){
	datafim = document.form1.data2_ano.value+"-"+document.form1.data2_mes.value+"-"+document.form1.data2_dia.value;
	dataini = document.form1.data1_ano.value+"-"+document.form1.data1_mes.value+"-"+document.form1.data1_dia.value;
	if (datafim<dataini){
		alert("Data inicial não pode ser maior que a data final!!");				
		document.form1.data2_ano.value = "";
		document.form1.data2_mes.value = "";
		document.form1.data2_dia.value = "";
		document.form1.data2.value = "";
		document.form1.data1_ano.value = "";
		document.form1.data1_mes.value = "";
		document.form1.data1_dia.value = "";
		document.form1.data1.value = "";
	}	
	}
}
function js_somatotal(linha) {
  
  var his   = Number(document.getElementById('his'+linha).value);
	var cor   = Number(document.getElementById('cor'+linha).value);
	var jur   = Number(document.getElementById('jur'+linha).value);
	var mul   = Number(document.getElementById('mul'+linha).value);
	var des   = Number(document.getElementById('des'+linha).value);
	var valor = Number(document.getElementById('total'+linha).value);
		
	//js_strToFloat == função que transforma 2.500,00 em 2500.00 (string para numero)	
  var historicoatual = js_strToFloat(document.getElementById('historico').innerHTML) ;
	var corrigidoatual = js_strToFloat(document.getElementById('corrigido').innerHTML);
	var juroatual      = js_strToFloat(document.getElementById('juro').innerHTML);
	var multaatual     = js_strToFloat(document.getElementById('multa').innerHTML);
	var descontoatual  = js_strToFloat(document.getElementById('desconto').innerHTML);
	var totalzaoatual  = js_strToFloat(document.getElementById('totalzao').innerHTML);
	
	if (document.getElementById('check'+linha).checked == false) {
    historicoatual = historicoatual - his;
		corrigidoatual = corrigidoatual - cor;
		juroatual      = juroatual - jur;
		multaatual     = multaatual - mul;
		descontoatual  = descontoatual - des;
		totalzaoatual  = totalzaoatual - valor;		
		
  } else {
    historicoatual = historicoatual + his;
		corrigidoatual = corrigidoatual + cor;
		juroatual      = juroatual + jur;
		multaatual     = multaatual + mul;
		descontoatual  = descontoatual + des;
		totalzaoatual  = totalzaoatual + valor;	
  }
		
  document.getElementById('historico').innerHTML = js_formatar( historicoatual,'f',2);
  document.getElementById('corrigido').innerHTML = js_formatar( corrigidoatual,'f',2);
  document.getElementById('juro').innerHTML      = js_formatar( juroatual,'f',2);
	document.getElementById('multa').innerHTML     = js_formatar( multaatual,'f',2);
	document.getElementById('desconto').innerHTML  = js_formatar( descontoatual,'f',2);
	document.getElementById('totalzao').innerHTML  = js_formatar( totalzaoatual,'f',2);

}

  function js_AbreJanelaRelatorioold() { 
    jandb = window.open('arr3_relatoriototaldebitossintetico001.php?db_datausu=<?
	  echo $db_datausu."&";
	  if(isset($matric)){
         echo "matric=$matric";
      }else if(isset($inscr)){
         echo "inscr=$inscr";
      }else if(isset($numcgm)){
         echo "numcgm=$numcgm";
      }else if(isset($numpre)){
         echo "numpre=$numpre";
      }
	?>','','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jandb.moveTo(0,0);
   }
  
   function js_AbreJanelaRelatorio(programa) {
     var F = document.form1.length;
     tipos = '';
     tipostodos = '';
     tiposc="";
     tiposctodos="";
		 vReceit= '';
		 viRec  = '';
		 itensReceit = js_getElementbyClass(form1,'creceit');
     // alert(itensReceit);  
		 for (r = 0;r < itensReceit.length;r++){

            //alert(itensReceit[r].tipo);
            if (itensReceit[r].checked == true){
  		    	    vReceit = vReceit+viRec+itensReceit[r].value;
								viRec = ",";

						}

		 }
     for(i=0;i<F;i++){
       if(document.form1.elements[i].type == 'checkbox' && document.form1.elements[i].checked == true ){
          tipos = tipos+tiposc+document.form1.elements[i].value ;
          tiposc=",";
       }
       if(document.form1.elements[i].type == 'checkbox'){
	 tipostodos = tipostodos+tiposctodos+document.form1.elements[i].value ;
	 tiposctodos=",";
       }
     }

     if(tipos==''){
       alert("Você deve selecionar um tipo para emissâo.");
     }else{

	   query="";
		 query+='&dtini='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value;
 	   query+='&dtfim='+document.form1.data2_ano.value+'-'+document.form1.data2_mes.value+'-'+document.form1.data2_dia.value;
 	   query+='&exercini='+document.form1.exercini.value;
 	   query+='&exercfim='+document.form1.exercfim.value;
     jandb = window.open(programa+'&tipos='+tipos+'&parReceit='+vReceit+'&tipostodos='+tipostodos+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
     jandb.moveTo(0,0);
     }
   }
   
function js_setMarca(obj) {

  var aElemOpcao = $$("class='creceit' input[type='checkbox']");
	for (var i = 0; i < aElemOpcao.length; i++) {
	
		if (aElemOpcao[i].tipo == obj.value) {
	           
		  if (obj.checked == true){
			  aElemOpcao[i].checked = true;
			} else {
		    aElemOpcao[i].checked = false;
		  }
		}
	}
}

function js_marcaPai(obj) {

  var aElemOpcao = $$("class='creceit' input[type='checkbox']");
  aElemOpcao.each(
 
    function (oElemOpcao, iInd) {

	    if (oElemOpcao.id == 'check' + obj.tipo) {
        oElemOpcao.checked = true;
	    }
    }
  );
}

function js_getElementByProperties(objRoot,propriedade,valor){

	var temparray = new Array();
	var inc       = 0;
	var rootlength=objRoot.length;

  alert(eval(propriedade));	

	for (i=0; i<rootlength; i++){
		   alert(objRoot[i].eval(propriedade));
		if (objRoot[i].eval(propriedade) == valor){
			temparray[inc++] = objRoot[i];
	  }
  }
	return temparray;
}

function js_verifTipo(obj){
   
	 vtotal = 0;
	 itensReceit = js_getElementByProperties(form1,'tipo',obj.tipo);

}

</script>
<style type="text/css">
<!--
.borda {
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
.creceit {
	border-right-width: 1px;
	border-right-style: solid;
	border-right-color: #000000;
}
-->
</style>
<script>
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);


</script>
</head>
<body bgcolor=#CCCCCC onload="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>

<form name="form1" method="post">

<table border="1" cellpadding="0" cellspacing="0">
<tr bgcolor="#FFCC66">
<th class="borda" style="font-size:12px" nowrap>Tipo</th>
<th class="borda" style="font-size:12px" nowrap>Descrio</th>
<th class="borda" style="font-size:12px" nowrap>Valor Histrico</th>
<th class="borda" style="font-size:12px" nowrap>Valor Corrigido</th>
<th class="borda" style="font-size:12px" nowrap>Valor Juros</th>
<th class="borda" style="font-size:12px" nowrap>Valor Multa</th>
<th class="borda" style="font-size:12px" nowrap>Valor Desconto</th>
<th class="borda" style="font-size:12px" nowrap>Valor Total</th>
<th class="borda" style="font-size:12px" nowrap></th>
</tr>
<?
$erro = false;
if(isset($matric)){
  $result = debitos_tipos_matricula($matric);
  $chave = $matric;
}else if(isset($inscr)){
  $result = debitos_tipos_inscricao($inscr);
  $chave = $inscr;
}else if(isset($numcgm)){
  $result = debitos_tipos_numcgm($numcgm);
  $chave = $numcgm;
}else if(isset($numpre)){
  $result = debitos_tipos_numpre($numpre);
  $chave = $numpre;
}else if(isset($Parcelamento)){
  $sql = "select termo.v07_numpre as numpre 
 		    from termo 
		   where termo.v07_parcel = ".$Parcelamento;
  $numpre = pg_result(pg_query($sql),0,0);	
  $result = debitos_tipos_numpre($numpre);  
  $chave = $Parcelamento;
}else{
  $chave = 0;
}
if($chave !=0){
  if($result!=false && pg_numrows($result)>0){
    
    $cor = "#EFE029";
    $ttvlrhis       = 0;
    $ttvlrcor       = 0;
    $ttvlrjuros     = 0;
    $ttvlrmulta     = 0;
    $ttvlrdesconto  = 0;
    $tttotal        = 0;
    $tttotalmarcado = 0;

    /**
     * Armazenarao os valores dos debitos que estao selecionados
     */
    $ttvlrhismarcado      = 0;
    $ttvlrcormarcado      = 0;
    $ttvlrjurosmarcado    = 0;
    $ttvlrmultamarcado    = 0;
    $ttvlrdescontomarcado = 0;
    
    for($x=0;$x<pg_numrows($result);$x++){
	  db_fieldsmemory($result,$x,true);

      if(isset($matric)){
         $debitos = debitos_matricula($matric,0,$k00_tipo,$DB_DATACALC,db_getsession("DB_anousu"),"k00_receit,k02_descr");
      }else if(isset($inscr)){
         $debitos = debitos_inscricao($inscr,0,$k00_tipo,$DB_DATACALC,db_getsession("DB_anousu"),"k00_receit,k02_descr");         
      }else if(isset($numcgm)){
         $debitos = debitos_numcgm($numcgm,0,$k00_tipo,$DB_DATACALC,db_getsession("DB_anousu"),"k00_receit,k02_descr");
      }else if(isset($numpre)){
         $debitos = debitos_numpre($numpre,0,$k00_tipo,$DB_DATACALC,db_getsession("DB_anousu"),"k00_receit,k02_descr");
      }else{
        echo "Sem Débitos.";
        $erro = true;
        break;
      }
      if ($debitos==false||$debitos==1){
      	continue;
      }
      if(pg_numrows($debitos)>0){
        $tvlrhis=0;
        $tvlrcor=0;
        $tvlrjuros=0;
        $tvlrmulta=0;
        $tvlrdesconto=0;
        $ttotal=0;
        
        
		
        if(isset($tiposoma) && ($tiposoma=="Por Receita")){
	 	  ?>
          <tr>
          <td colspan="9" style="font-size:12px" nowrap bgcolor="<?=$cor?>" align="center"><?=$k00_tipo."-".$k00_descr?></td>
          </tr>
		  <?
        }

		
        for($xx=0;$xx<pg_numrows($debitos);$xx++){
        
		   db_fieldsmemory($debitos,$xx);
           $tvlrhis+=$vlrhis;
           $tvlrcor+=$vlrcor;
           $tvlrjuros+=$vlrjuros;
           $tvlrmulta+=$vlrmulta;
           $tvlrdesconto+=$vlrdesconto;
           $ttotal+=$total;
           if(isset($tiposoma) && ($tiposoma=="Por Receita")){
             if($cor=="#EFE029")
		       $cor="#E4F471";
             else if($cor=="#E4F471")
		       $cor="#EFE029";
					 	
		     ?>
             <tr>
             <td style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=$k00_receit?></td>
             <td style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=$k02_descr?></td>
             <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=db_formatar($vlrhis,'f')?></td>
             <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=db_formatar($vlrcor,'f')?></td>
             <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=db_formatar($vlrjuros,'f')?></td>
             <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=db_formatar($vlrmulta,'f')?></td>
             <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=db_formatar($vlrdesconto,'f')?></td>
             <td align="right" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=db_formatar($total,'f')?></td>
             <td align="right" style="font-size:11px"  id="coluna$i" nowrap bgcolor="<?=$cor?>">
						 <input type='checkbox' id="chkrec-<?=(isset($k00_tipo) ? $k00_tipo : 'totalizador');?>-<?=$k00_receit;?>"  checked class='creceit' onclick='js_marcaPai(this)'
						  name="chkrec<?=$k00_receit;?>" value="<?=$k00_receit;?>"></td>
						 <script>document.getElementById('chkrec-<?=(isset($k00_tipo) ? $k00_tipo : 'totalizador');?>-<?=$k00_receit;?>').tipo=<?=$k00_tipo;?>;</script>
             </tr>
		   <?
           }
	    }
        $ttvlrhis+=$tvlrhis;
        $ttvlrcor+=$tvlrcor;
        $ttvlrjuros+=$tvlrjuros;
        $ttvlrmulta+=$tvlrmulta;
        $ttvlrdesconto+=$tvlrdesconto;
        $tttotal+=$ttotal;
        
        
 	if ($k00_marcado=="t") {

 	  $ttvlrhismarcado      += $tvlrhis;
 	  $ttvlrcormarcado      += $tvlrcor;
 	  $ttvlrjurosmarcado    += $tvlrjuros;
 	  $ttvlrmultamarcado    += $tvlrmulta;
 	  $ttvlrdescontomarcado += $tvlrdesconto;
	  $tttotalmarcado       += $ttotal;
 	}
        if($cor=="#EFE029")
           $cor="#E4F471";
        else if($cor=="#E4F471")
		   $cor="#EFE029";
		?>
        <tr>
        <?
		if(!isset($tiposoma) || ( isset($tiposoma) && ($tiposoma=="Por Tipo"))){	 
		
		?>
        <td class="borda" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=$k00_tipo?></td>
        <td class="borda" style="font-size:12px" nowrap bgcolor="<?=$cor?>"><?=$k00_descr?></td>
        <?
		}else{
		?>
        <td align="left" colspan="2" style="font-size:12px" nowrap bgcolor="<?=$cor?>">Total Tipo:</td>
		<?
		}
		?>
        <td align="right" class="borda" style="font-size:12px" nowrap bgcolor="<?=$cor?>">     <input type="hidden" id="his<?=$k00_tipo?>" value="<?=$tvlrhis?>">       <?=db_formatar($tvlrhis,'f')?></td>
        <td align="right" class="borda" style="font-size:12px" nowrap bgcolor="<?=$cor?>">     <input type="hidden" id="cor<?=$k00_tipo?>" value="<?=$tvlrcor?>">       <?=db_formatar($tvlrcor,'f')?></td>
        <td align="right" class="borda" style="font-size:12px" nowrap bgcolor="<?=$cor?>">     <input type="hidden" id="jur<?=$k00_tipo?>" value="<?=$tvlrjuros?>">     <?=db_formatar($tvlrjuros,'f')?></td>
        <td align="right" class="borda" style="font-size:12px" nowrap bgcolor="<?=$cor?>">     <input type="hidden" id="mul<?=$k00_tipo?>" value="<?=$tvlrmulta?>">     <?=db_formatar($tvlrmulta,'f')?></td>
        <td align="right" class="borda" style="font-size:12px" nowrap bgcolor="<?=$cor?>">     <input type="hidden" id="des<?=$k00_tipo?>" value="<?=$tvlrdesconto?>">  <?=db_formatar($tvlrdesconto,'f')?></td>
        <td align="right" class="borda" style="font-size:12px" nowrap bgcolor="<?=$cor?>">     <input type="hidden" id="total<?=$k00_tipo?>" value="<?=$ttotal?>">			<?=db_formatar($ttotal,'f')?>
			  </td>

        <td align="right" style="font-size:11px" id="coluna$i" nowrap bgcolor="<?=$cor?>"> 
	        <input style="font-size:11px" type="checkbox" value="<?=$k00_tipo?>" id="check<?=$k00_tipo?>" name="check<?=$k00_tipo?>" <?echo($k00_marcado=="t"?"checked":""); echo " onclick=\"js_somatotal($k00_tipo);js_setMarca(this);\"" ?>>
        </td>

        </tr>
		<?
	  }
	}
  }else{
    echo "Sem Débitos para esta chave.";
    $erro = true;
  }
}
if($erro==false){
?>
<tr bgcolor="#FFCC66">
<td class="borda" style="font-size:12px" nowrap>&nbsp;</td>
<td class="borda" style="font-size:12px" nowrap>Total:</td>
<td align="right" class="borda" style="font-size:12px" nowrap ><?=db_formatar($ttvlrhis,'f')?></td>
<td align="right" class="borda" style="font-size:12px" nowrap ><?=db_formatar($ttvlrcor,'f')?></td>
<td align="right" class="borda" style="font-size:12px" nowrap ><?=db_formatar($ttvlrjuros,'f')?></td>
<td align="right" class="borda" style="font-size:12px" nowrap ><?=db_formatar($ttvlrmulta,'f')?></td>
<td align="right" class="borda" style="font-size:12px" nowrap ><?=db_formatar($ttvlrdesconto,'f')?></td>
<td align="right" class="borda" style="font-size:12px" nowrap ><?=db_formatar($tttotal,'f')?></td>
<th class="borda" style="font-size:12px" nowrap> </th>
<td align="right" style="font-size:11px" id="coluna" nowrap bgcolor="<?=$cor?>">
</tr>
<tr bgcolor="#FFCC66">
<td class="borda" style="font-size:12px" nowrap>&nbsp;</td>
<td class="borda" style="font-size:12px" nowrap>Total marcado:</td>
<td align="right" id="historico"  class="borda" style="font-size:12px" nowrap ><?=db_formatar($ttvlrhismarcado,'f')?> </td>
<td align="right" id="corrigido"  class="borda" style="font-size:12px" nowrap ><?=db_formatar($ttvlrcormarcado,'f')?></td>
<td align="right" id="juro"       class="borda" style="font-size:12px" nowrap ><?=db_formatar($ttvlrjurosmarcado,'f')?></td>
<td align="right" id="multa"      class="borda" style="font-size:12px" nowrap ><?=db_formatar($ttvlrmultamarcado,'f')?></td>
<td align="right" id="desconto"   class="borda" style="font-size:12px" nowrap ><?=db_formatar($ttvlrdescontomarcado,'f')?></td>
<td align="right" id="totalzao"   class="borda" style="font-size:12px" nowrap ><?=db_formatar($tttotalmarcado,'f')?></td>
<th class="borda" style="font-size:12px" nowrap> </th>
<td align="right" style="font-size:11px" id="coluna" nowrap bgcolor="<?=$cor?>">
</tr>

<?
}
?>

</table>
    <input type="submit" name="tiposoma" value="<?=(@$tiposoma=="Por Receita"?"Por Tipo":"Por Receita")?>">
    <input type="hidden" name="db_datausu" value="$db_datausu">
    &nbsp;&nbsp; 
    <input type="button" name="Submit3" value="Relat&oacute;rio Sint&eacute;tico" onclick="js_AbreJanelaRelatorio('arr3_relatoriototaldebitossintetico001.php?db_datausu=<?
          echo $db_datausu."&";
          if(isset($matric)){
         echo "matric=$matric";
      }else if(isset($inscr)){
         echo "inscr=$inscr";
      }else if(isset($numcgm)){
         echo "numcgm=$numcgm";
      }else if(isset($numpre)){
         echo "numpre=$numpre";
      }
     ?>');">
    &nbsp;&nbsp; 
    <input type="button" name="Submit32" value="Relat&oacute;rio Anal&iacute;tico" onClick="js_AbreJanelaRelatorio('arr3_relatoriototaldebitosanalitico001.php?db_datausu=<?
	  echo $db_datausu."&";
	  if(isset($matric)){
         echo "matric=$matric";
      }else if(isset($inscr)){
         echo "inscr=$inscr";
      }else if(isset($numcgm)){
         echo "numcgm=$numcgm";
      }else if(isset($numpre)){
         echo "numpre=$numpre";
      }
	?>');">	

	<table>
	 	<tr>
     		<td align='center'  colspan=2 >
            <b> Periodo </b>
            <? 
	        db_inputdata('data1','','','',true,'text',1,"onchange='js_limpadtfim()';");   		          
            echo "<b> a</b> ";
            db_inputdata('data2','','','',true,'text',1,"onchange='js_testadt()';");
            ?>&nbsp;
			</td>
      	</tr>
      	<tr>
     		<td align='center'  colspan=2 >
            <b> Exercicio </b>
            <? 
	        	db_input('exercini',4,@$Iv01_exerc,true,'text',1,"onchange='js_limpaexercfim()';");   		          
            echo "<b> a</b> ";
            db_input('exercfim',4,@$Iv01_exerc,true,'text',1,"onchange='js_testaexerc()';");
            ?>&nbsp;
			</td>
      	</tr>
 
     </table>
   </form>
</center>
</body>
</html>
<script>

function js_emiteRelatorioSintetico(programa) {

  var iItensFormulario          = document.form1.length;
  var aTiposDebitosSelecionados = new Array();
  var aTiposDbietos             = new Array();

  var vReceit                   = '';
  var viRec                     = '';
  var aReceitasSelecionadas     = new Array();
  var aItensReceita             = $$('.creceit');
  
  for (var iIndiceReceita in aItensReceita) {

    if ( aItensReceita[iIndiceReceita].checked) {
      aReceitasSelecionadas.push(aItensReceita[iIndiceReceita].value);
    }
  }





  
  for( i=0; i < iItensFormulario; i++){
    if(document.form1.elements[i].type == 'checkbox' && document.form1.elements[i].checked == true ){
      tipos = tipos+tiposc+document.form1.elements[i].value ;
      tiposc=",";
    }
    if(document.form1.elements[i].type == 'checkbox'){
      tipostodos = tipostodos+tiposctodos+document.form1.elements[i].value ;
      tiposctodos=",";
    }
  }

  if( tipos=='' ){
    alert("Você deve selecionar um tipo para emissâo.");
  }else{

    query="";
    query+='&dtini='+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value;
    query+='&dtfim='+document.form1.data2_ano.value+'-'+document.form1.data2_mes.value+'-'+document.form1.data2_dia.value;
    query+='&exercini='+document.form1.exercini.value;
    query+='&exercfim='+document.form1.exercfim.value;
    jandb = window.open(programa+'&tipos='+tipos+'&parReceit='+vReceit+'&tipostodos='+tipostodos+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jandb.moveTo(0,0);
  }
}
</script>