<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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

session_start();

include ("libs/db_conecta.php");
include ("libs/db_stdlib.php");
include ("libs/db_sql.php");
include ("libs/db_utils.php");
include ("libs/db_mens.php");
include ("dbforms/db_funcoes.php");

$oGet  = db_utils::postMemory($_GET);
$oPost = db_utils::postMemory($_POST);

?>
<body>
<div id='int_perc1' align="left" style="position:absolute;top:60%;left:35%; float:left; width:200; background-color:#ECEDF2; padding:5px; margin:0px; border:1px #C2C7CB solid; margin-left:10px; font-size:80%; visibility:hidden">
<div style="border:1px #ffffff solid; margin:8px 3px 3px 3px;">
<div id='int_perc2' style="width:0%; background-color:#888888;">&nbsp;
</div>
</div>
</div>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="config/estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" src="scripts/scripts.js"></script>
<script>

function js_somatudo(){
 var F = document.form1;
 var valor = 0;
 var valorcorr = 0;
 var juros = 0;
 var multa = 0;
 var desconto = 0;
 var total = 0;
 var emrec = 0;
 for(var i = 0;i < F.length;i++){
    if((F.elements[i].type == "checkbox" || F.elements[i].type == "submit")){
      var indi = js_parse_int(F.elements[i].id);
      valor += new Number(document.getElementById('valor'+indi).value.replace(",",""));
      valorcorr += new Number(document.getElementById('valorcorr'+indi).value.replace(",",""));
      juros += new Number(document.getElementById('juros'+indi).value.replace(",",""));
      multa += new Number(document.getElementById('multa'+indi).value.replace(",",""));
      desconto += new Number(document.getElementById('desconto'+indi).value.replace(",",""));
      total += new Number(document.getElementById('total'+indi).value.replace(",",""));
    }
 }
 parent.document.getElementById('valor1').innerHTML     = valor.toFixed(2);
 parent.document.getElementById('valorcorr1').innerHTML = valorcorr.toFixed(2);
 parent.document.getElementById('juros1').innerHTML     = juros.toFixed(2);
 parent.document.getElementById('multa1').innerHTML     = multa.toFixed(2);
 parent.document.getElementById('desconto1').innerHTML  = desconto.toFixed(2);
 parent.document.getElementById('total1').innerHTML     = total.toFixed(2);
}

function js_soma(linha) {
  
  linha = ((typeof(linha)=="undefined") || (typeof(linha)=="object")?2:linha);
  
  var F 		  = document.form1;
  var numpres     = 0;
  var valor       = 0;
  var valorcorr   = 0;
  var juros 	  = 0;
  var multa 	  = 0;
  var desconto    = 0;
  var total       = 0;
  var emrec	      = 0;
  var vcto_atraso = false;
  var data_hoje   = "<?=date('Ymd',db_getsession('DB_datausu'))?>";
  var var_vcto    = "";
  var vcto_calc   = "";
  var cont 		  = 0;
  
  for(var i = 0;i < F.length;i++){
    
    if((F.elements[i].type == "checkbox" || F.elements[i].type == "submit") && (F.elements[i].checked == true )){
      var indi = js_parse_int(F.elements[i].id);
      valor     += new Number(document.getElementById('valor'+indi).value.replace(",",""));
      valorcorr += new Number(document.getElementById('valorcorr'+indi).value.replace(",",""));
      juros     += new Number(document.getElementById('juros'+indi).value.replace(",",""));
      multa     += new Number(document.getElementById('multa'+indi).value.replace(",",""));
      desconto  += new Number(document.getElementById('desconto'+indi).value.replace(",",""));
      total     += new Number(document.getElementById('total'+indi).value.replace(",",""));
      numpres += 'N'+document.getElementById('CHECK'+indi).value ;
      parent.document.getElementById('numpres').value = numpres;
	  if (parent.document.getElementById('debito')) {
        parent.document.getElementById('debito').disabled = false;
	  }
      var_vcto = document.getElementById('vcto_parcela'+indi).innerHTML;
      var_vcto2 = var_vcto.substr(6,4)+var_vcto.substr(3,2)+var_vcto.substr(0,2);
	  cont++;
	   
      if(var_vcto2 < data_hoje){
       vcto_atraso = true;
       vcto_calc = data_hoje;
      }else{
       vcto_calc = var_vcto2;
      }
    }
  }
  
  if(cont>1 && vcto_atraso==true){
  	vcto_calc = data_hoje;
  }else if(cont==1 && vcto_atraso==false){
  	 vcto_calc = var_vcto2;
  }else if(cont>1 && vcto_atraso==false){
  	vcto_calc = data_hoje;
  }
  if(cont==0){
  	vcto_calc = data_hoje;
  }
  
  parent.document.getElementById('dia_vcto').value = vcto_calc.substr(6,2);
  parent.document.getElementById('mes_vcto').value = vcto_calc.substr(4,2);
  parent.document.getElementById('ano_vcto').value = vcto_calc.substr(0,4);
  parent.document.getElementById('valor'+linha).innerHTML     = valor.toFixed(2);
  parent.document.getElementById('valorcorr'+linha).innerHTML = valorcorr.toFixed(2);
  parent.document.getElementById('juros'+linha).innerHTML     = juros.toFixed(2);
  parent.document.getElementById('multa'+linha).innerHTML     = multa.toFixed(2);
  parent.document.getElementById('desconto'+linha).innerHTML  = desconto.toFixed(2);
  parent.document.getElementById('total'+linha).innerHTML     = total.toFixed(2);

  if(linha == 2){

    valor     = Number(parent.document.getElementById('valor1').innerHTML) - valor;
    valorcorr = Number(parent.document.getElementById('valorcorr1').innerHTML) - valorcorr;
    juros     = Number(parent.document.getElementById('juros1').innerHTML) - juros;
    multa     = Number(parent.document.getElementById('multa1').innerHTML) - multa;
    desconto  = Number(parent.document.getElementById('desconto1').innerHTML) - desconto;
    total     = Number(parent.document.getElementById('total1').innerHTML) - total;

    parent.document.getElementById('valor3').innerHTML     = valor.toFixed(2);
    parent.document.getElementById('valorcorr3').innerHTML = valorcorr.toFixed(2);
    parent.document.getElementById('juros3').innerHTML     = juros.toFixed(2);
    parent.document.getElementById('multa3').innerHTML     = multa.toFixed(2);
    parent.document.getElementById('desconto3').innerHTML  = desconto.toFixed(2);
    parent.document.getElementById('total3').innerHTML     = total.toFixed(2);

  }

  if(emrec == 't') {
    var aux = 0;
    for(i = 0;i < F.length;i++) {
      if(F.elements[i].type == "checkbox")
       if(F.elements[i].checked == true)
        aux = 1;
    }
    if(aux == 0) {
     
      parent.document.getElementById("enviar").disabled = true;
      document.getElementById('marca').innerHTML = "M";
      document.getElementById('btmarca').value = "Marcar Todas";
    }
  }

 if(Number(parent.document.getElementById('total2').innerHTML)==0)
  parent.document.getElementById("enviar").disabled = true;


}

function js_marca() {
  var ID = document.getElementById('marca');
  if(!ID)
    return false;
  var F = document.form1;
  if(ID.innerHTML == 'M') {
    var dis = true;
    ID.innerHTML = 'D';
  } else {
    var dis = false;
    ID.innerHTML = 'M';
  }
  for(i = 0;i < F.elements.length;i++) {
    if(F.elements[i].type == "checkbox"){
      if(F.elements[i].style.visibility!="hidden")
        F.elements[i].checked = dis;
    }
  }
  js_soma(this);
}


function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
</script>

<style type="text/css">
<!--
.borda {
        border-right-width: 1px;
        border-right-style: solid;
        border-right-color: #000000;
}
-->
</style>
</head>
<body leftmargin="4" topmargin="5" marginwidth="4" marginheight="4" onLoad="js_somatudo()">
<center>
  <form name="form1" method="post" action="">
    <?
    
    if (isset($inscr) && trim($inscr) != "" ){
      $sSqlInner  = "inner join arreinscr  on arreinscr.k00_numpre  = arresusp.k00_numpre";
      $sSqlWhere  = "k00_inscr = {$inscr}"; 
    } else if (isset($matric) && trim($matric) != "") {
      $sSqlInner  = "inner join arrematric on arrematric.k00_numpre = arresusp.k00_numpre";
      $sSqlWhere  = "k00_matric = {$matric}";     
    } else if (isset($numcgm) && trim($numcgm) != "") {
      $sSqlInner  = "inner join arrenumcgm on arrenumcgm.k00_numpre = arresusp.k00_numpre";
      $sSqlWhere  = "arrenumcgm.k00_numcgm = {$numcgm}";   		 	
    } 
    	
    $sSqlVerificaSuspensao  = " select arresusp.*,";
    $sSqlVerificaSuspensao .= "   	   k02_descr, ";
    $sSqlVerificaSuspensao .= "   	   k00_descr  ";
    $sSqlVerificaSuspensao .= "   from arresusp       ";
    $sSqlVerificaSuspensao .= " 	   {$sSqlInner}   ";
    $sSqlVerificaSuspensao .= " 	   inner join suspensao on suspensao.ar18_sequencial = arresusp.k00_suspensao ";
    $sSqlVerificaSuspensao .= " 	   inner join tabrec    on tabrec.k02_codigo = arresusp.k00_receit ";
    $sSqlVerificaSuspensao .= " 	   inner join arretipo  on arretipo.k00_tipo = arresusp.k00_tipo   ";
    $sSqlVerificaSuspensao .= "  where {$sSqlWhere}      ";
    $sSqlVerificaSuspensao .= "    and ar18_situacao = 1 ";
    
	$rsVerificaDebitosSuspensos = db_query($sSqlVerificaSuspensao);
	$iNroLinhasDebitosSuspensos = pg_num_rows($rsVerificaDebitosSuspensos);
    

	if ( $iNroLinhasDebitosSuspensos > 0  ) {
	
	  for ($iInd=0; $iInd < $iNroLinhasDebitosSuspensos; $iInd++) {
	  
	    $oArresusp = db_utils::fieldsMemory($rsVerificaDebitosSuspensos,$iInd);

	    $nTotal = ($oArresusp->k00_vlrcor + $oArresusp->k00_vlrjur + $oArresusp->k00_vlrmul) - $oArresusp->k00_vlrdes;
	      
	    if ( isset($aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit])) {
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['Valor' ] += $oArresusp->k00_valor;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['VlrCor'] += $oArresusp->k00_vlrcor;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['VlrJur'] += $oArresusp->k00_vlrjur;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['VlrMul'] += $oArresusp->k00_vlrmul;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['VlrDes'] += $oArresusp->k00_vlrdes;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['Total' ] += $nTotal;
	    } else {
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['k00_dtoper'] = $oArresusp->k00_dtoper;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['k00_dtvenc'] = $oArresusp->k00_dtvenc;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['k00_tipo'  ] = $oArresusp->k00_tipo;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['k02_descr' ] = $oArresusp->k02_descr;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['k00_descr' ] = $oArresusp->k00_descr;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['Valor' ]  = $oArresusp->k00_valor;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['VlrCor']  = $oArresusp->k00_vlrcor;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['VlrJur']  = $oArresusp->k00_vlrjur;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['VlrMul']  = $oArresusp->k00_vlrmul;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['VlrDes']  = $oArresusp->k00_vlrdes;
	      $aDebitos[$oArresusp->k00_numpre][$oArresusp->k00_numpar][$oArresusp->k00_receit]['Total' ]  = $nTotal;	    
	    }
	  }
    
    if (!empty ($inscr)){
      $arg = "inscr=".$inscr;
    } else if (!empty ($numcgm)) {
	  $arg = "numcgm=".$numcgm;
    } else if (!empty ($matric)) {
	  $arg = "matric=".$matric;
    } else if (!empty ($numpre)) {
	  $arg = "numpre=".$numpre;
    }			
	 	
    echo "<table border='0' cellspacing='0' cellpadding='3' id='tabdebitos'> ";
    echo " <tr bgcolor='#FFCC66'>											 ";
	echo " <th class='borda' style='font-size:12px' >Numpre   </th>";
	echo " <th class='borda' style='font-size:12px' >Parcela  </th>";
	echo " <th class='borda' style='font-size:12px' >Dt.Oper. </th>";
	echo " <th class='borda' style='font-size:12px' >Dt.Venc. </th>";
	echo " <th class='borda' style='font-size:12px' >Receita  </th>";
	echo " <th class='borda' style='font-size:12px' >Descrição</th>";
	echo " <th class='borda' style='font-size:12px' >Tipo	  </th>";
	echo " <th class='borda' style='font-size:12px' >Descrição</th>";
	echo " <th class='borda' style='font-size:12px' >Val. 	  </th>";
	echo " <th class='borda' style='font-size:12px' >Val Cor. </th>";
	echo " <th class='borda' style='font-size:12px' >Jur. 	  </th>";
	echo " <th class='borda' style='font-size:12px' >Mul. 	  </th>";
	echo " <th class='borda' style='font-size:12px' >Desc.	  </th>";	
	echo " <th class='borda' style='font-size:12px' >Tot. 	  </th>";
	echo " </tr>";

    $dtDataUsu = db_getsession('DB_datausu');    
    $iInd      = 0;
    $nValor    = 0;
    $nValCor   = 0;
    $nValJur   = 0;
    $nValMul   = 0;
    $nValDes   = 0;
    $nValTot   = 0;
     
      foreach ( $aDebitos as $iNumpre => $aDebitos2  ) {
        foreach ( $aDebitos2 as $iNumpar => $aDebitos3  ) {
      	  foreach ( $aDebitos3 as $iReceit => $aValoresDebitos  ) {
      	  
      	  	$dtVenc   = db_formatar($aValoresDebitos['k00_dtvenc'],"d");
      	  	$dtOper   = db_formatar($aValoresDebitos['k00_dtoper'],"d");
      	  	$iTipo    = $aValoresDebitos['k00_tipo'  ];
      	    $sDescr1  = $aValoresDebitos['k00_descr' ];
      	    $sDescr2  = $aValoresDebitos['k02_descr' ];
      	     
      	    $nValor  += $aValoresDebitos['Valor'];
      	    $nValCor += $aValoresDebitos['VlrCor'];
      	    $nValJur += $aValoresDebitos['VlrJur'];
      	    $nValMul += $aValoresDebitos['VlrMul'];
      	    $nValDes += $aValoresDebitos['VlrDes'];
      	    $nValTot += $aValoresDebitos['Total' ];
     	  }
        }
        
      	echo "<label for='CHECK$iInd'>";
		echo "  <tr style='cursor: hand' bgcolor='#EFE029'>";
        echo "	  <td class='borda' style='font-size:11px' >{$iNumpre}</td>";
        echo "	  <td class='borda' style='font-size:11px' >{$iNumpar}</td>";
        echo "	  <td class='borda' style='font-size:11px' >{$dtOper} </td>";
        echo "	  <td class='borda' style='font-size:11px' id='vcto_parcela$iInd' name='vcto_parcela$iInd'>{$dtVenc} </td>";
        echo "	  <td class='borda' style='font-size:11px' >{$iReceit}</td>";
        echo "	  <td class='borda' style='font-size:11px' >{$sDescr2}</td>";
        echo "	  <td class='borda' style='font-size:11px' >{$iTipo}</td>";
        echo "	  <td class='borda' style='font-size:11px' >{$sDescr1}</td>";
        echo "	  <td class='borda' style='font-size:11px' align='right' nowrap><input type='hidden' id='valor$iInd'     value='".$nValor." '>".db_formatar($nValor,"f") ."</td>";
        echo "	  <td class='borda' style='font-size:11px' align='right' nowrap><input type='hidden' id='valorcorr$iInd' value='".$nValCor."'>".db_formatar($nValCor,"f")."</td>";
        echo "	  <td class='borda' style='font-size:11px' align='right' nowrap><input type='hidden' id='juros$iInd' 	 value='".$nValJur."'>".db_formatar($nValJur,"f")."</td>";
        echo "	  <td class='borda' style='font-size:11px' align='right' nowrap><input type='hidden' id='multa$iInd' 	 value='".$nValMul."'>".db_formatar($nValMul,"f")."</td>";
        echo "	  <td class='borda' style='font-size:11px' align='right' nowrap><input type='hidden' id='desconto$iInd'  value='".$nValDes."'>".db_formatar($nValDes,"f")."</td>";
        echo "	  <td class='borda' style='font-size:11px' align='right' nowrap><input type='hidden' id='total$iInd' 	 value='".$nValTot."'>".db_formatar($nValTot,"f")."</td>";
        echo "    <td class='borda' style='font-size:11px; display:none' id='coluna$iInd'    ><input style='visibility:'visible'' type='checkbox' value='$iNumpre' onclick='js_soma(2)' id='CHECK$iInd' name='CHECK$iInd' checked disabled></td>";        
        echo "  </tr>";
		echo "</label>";
        
      }
      
 	 echo "<input type='hidden' name='var_vcto'>";
	 echo "</table> ";
	 echo "</form>  ";
	 
	} else {
	  echo "<table>"; 
	  echo "<tr><td><small>Nenhum registro encontrado</small></td></tr>";
	  echo "</table>";
	}
?>
 
</center>
</body>
</html>