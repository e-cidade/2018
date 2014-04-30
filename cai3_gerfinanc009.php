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

// select k00_numpre,k00_numpar,k00_receit from arrecad where k00_numpre = 11111454;

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
$emrec = 't';
if($ver_inscr == ''){
   echo 'erro';
   exit;
}
$inscr = $ver_inscr;


//se for submit, ele cria o recibo
//if(isset($HTTP_POST_VARS["ver_matric"])) {
//  include("cai3_gerfinanc003.php");
//}

?>
<html>
<head>
<title>Documento sem t&iacute;tulo</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" src="scripts/scripts.js"></script>
<script>

function js_recalcula(){
    var F = document.form1;
    var aux = "";
    for(i = 0;i < F.length;i++) {
      if(F[i].type == "text" && F[i].value != "" && F[i].value != "0" ) {
	    aux += F[i].name + 'NP' + F[i].value +'NP';
	  }
    }
    if(aux!=""){
	   <?
	   if(isset($numcgm))     
	     echo "document.location.href='cai3_gerfinanc002.php?nucmgm=".$numcgm."&calculavalor='+aux;";
	   if(isset($inscr))     
	     echo "document.location.href='cai3_gerfinanc002.php?inscr=".$inscr."&calculavalor='+aux;";
	   if(isset($numpre))     
	     echo "document.location.href='cai3_gerfinanc002.php?numpre=".$numpre."&calculavalor='+aux;";
       ?>
	}else{
	   alert('Voce deverá preencher os valores.');
	}
}

function js_soma(linha) {
  linha = (typeof(linha)=="undefined"?2:linha);
  var F = document.form1;
  var valor = 0;
  var valorcorr = 0;
  var juros = 0;
  var multa = 0;
  var desconto = 0;
  var total = 0;
  var emrec = '<?=$emrec?>';
  var tab = document.getElementById('tabdebitos');
  if(emrec == 't')
    parent.document.getElementById("enviar").disabled = false;
  for(var i = 0;i < F.length;i++) {
    if((F.elements[i].type == "checkbox" || F.elements[i].type == "submit") && (F.elements[i].checked == true || linha == 1)) {
      var indi = js_parse_int(F.elements[i].id);	  
	  valor += new Number(document.getElementById('valor'+indi).value.replace(",",""));
      valorcorr += new Number(document.getElementById('valorcorr'+indi).value.replace(",",""));
      juros += new Number(document.getElementById('juros'+indi).value.replace(",",""));
      multa += new Number(document.getElementById('multa'+indi).value.replace(",",""));
      desconto += new Number(document.getElementById('desconto'+indi).value.replace(",",""));
      total += new Number(document.getElementById('total'+indi).value.replace(",",""));
	}
  }
  parent.document.getElementById('valor'+linha).innerText = valor.toFixed(2);
  parent.document.getElementById('valorcorr'+linha).innerText = valorcorr.toFixed(2);
  parent.document.getElementById('juros'+linha).innerText = juros.toFixed(2);
  parent.document.getElementById('multa'+linha).innerText = multa.toFixed(2);
  parent.document.getElementById('desconto'+linha).innerText = desconto.toFixed(2);
  parent.document.getElementById('total'+linha).innerText = total.toFixed(2);
  if(linha == 2) {
    valor = Number(parent.document.getElementById('valor1').innerText) - valor;
    valorcorr = Number(parent.document.getElementById('valorcorr1').innerText) - valorcorr;
    juros = Number(parent.document.getElementById('juros1').innerText) - juros;
    multa = Number(parent.document.getElementById('multa1').innerText) - multa;
    desconto = Number(parent.document.getElementById('desconto1').innerText) - desconto;
    total = Number(parent.document.getElementById('total1').innerText) - total;
	parent.document.getElementById('valor3').innerText = valor.toFixed(2);
    parent.document.getElementById('valorcorr3').innerText = valorcorr.toFixed(2);
    parent.document.getElementById('juros3').innerText = juros.toFixed(2);
    parent.document.getElementById('multa3').innerText = multa.toFixed(2);
    parent.document.getElementById('desconto3').innerText = desconto.toFixed(2);
    parent.document.getElementById('total3').innerText = total.toFixed(2);
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
	  document.getElementById('marca').innerText = "M";
      parent.document.getElementById('btmarca').value = "Marcar Todas";
 	}
  }
}
function js_marca() {
  var ID = document.getElementById('marca');
  var BT = parent.document.getElementById('btmarca');
  if(!ID)
    return false;
  var F = document.form1;
  if(ID.innerText == 'M') {
    var dis = true;
	ID.innerText = 'D';
	BT.value = "Desmarcar Todas";
  } else {
    var dis = false;
	ID.innerText = 'M';
	BT.value = "Marcar Todas";
  }
  for(i = 0;i < F.elements.length;i++) {
    if(F.elements[i].type == "checkbox")
      F.elements[i].checked = dis;
  }
  js_soma(2);
}




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
<script language="JavaScript" type="text/JavaScript">
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>

<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="parent.document.getElementById('processando').style.visibility = 'hidden'">
<center>
<?
//verifica se clicou no link da matricula



if(isset($inscricao) && !empty($inscricao)) {
  $inscr = $inscricao;
  $tipo = $tipo2;
}
//verifica o tipo e da o select dependendo se é numcgm, matric numpre ou inscr		
    if(isset($numcgm))
      if(($result = debitos_numcgm_var($numcgm,0,$tipo,db_getsession("DB_datausu"),db_getsession("DB_anousu"))))
	    echo "<script> numcgm = '$numcgm'; </script>\n";
	  else
	    db_redireciona("cai3_gerfinanc007.php?erro1=1");
    else if(isset($inscr))	  
      if(($result = debitos_inscricao_var($inscr,0,$tipo,db_getsession("DB_datausu"),db_getsession("DB_anousu"))))
	    echo "<script> numcgm = '$numcgm'; </script>\n";
	  else
	    db_redireciona("cai3_gerfinanc007.php?erro1=1");
	else if(isset($numpre))
	  if(($result = debitos_numpre_var($numpre,0,$tipo,db_getsession("DB_datausu"),db_getsession("DB_anousu"))))
	    echo "<script> numcgm = '$numcgm'; </script>\n";
	  else
	    db_redireciona("cai3_gerfinanc007.php?erro1=1");

  echo "<form name=\"form1\" method=\"post\" target=\"reciboweb\">\n";
  echo "<input type=\"hidden\" name=\"ver_matric\" value=\"".pg_result($result,0,"k00_matric")."\">\n";
  echo "<input type=\"hidden\" name=\"ver_inscr\" value=\"".pg_result($result,0,"k00_inscr")."\">\n";
  echo "<input type=\"hidden\" name=\"ver_numcgm\" value=\"".pg_result($result,0,"k00_numcgm")."\">\n";
  echo "<table border=\"0\" cellspacing=\"0\" cellpadding=\"3\" id=\"tabdebitos\">\n";
//cria o cabeçalho 
  $numrows = pg_numrows($result);
  echo "<tr bgcolor=\"#FFCC66\">\n";
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>&nbsp;</th>\n";
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>Numpre</th>\n";
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>P</th>\n";
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>TP</th>\n";
//  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>Dt. oper.</th>\n";
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>Dt. Venc.</th>\n";
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>Descrição</th>\n";
  //Verifica se agrupado por numpre, cria link pra passar pro nivel 2, mostrando todos os numpres
  if(!empty($inscr))
    $arg = "inscr=".$inscr;
  else if(!empty($numcgm))
    $arg = "numcgm=".$numcgm;
  else if(!empty($matric))
    $arg = "matric=".$matric;
  else if(!empty($numpre))
    $arg = "numpre=".$numpre;
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>Rec</th>\n";
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>Descr. Rec.</th>\n";
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>Val.</th>\n";
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>Val Cor.</th>\n";
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>Jur.</th>\n";
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>Mul.</th>\n";
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>Desc.</th>\n";                  
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap>Tot.</th>\n";  
  echo "<th class=\"borda\" style=\"font-size:12px\" nowrap><a id=\"marca\" href=\"\" style=\"color:black\" onclick=\"js_marca();return false\">M</a></th>\n";
  echo "</tr>\n";
	$j = 0;
	$elementos_numpres[0] = "";
    for($i = 0;$i < $numrows;$i++) {	  
      if(!in_array(pg_result($result,$i,"k00_numpre"),$elementos_numpres))
  		  $elementos_numpres[$j++] = pg_result($result,$i,"k00_numpre");
	}				
    for($i = 0;$i < sizeof($elementos_numpres);$i++) {
      $auxValor = 0;
	  $auxValorcorr = 0;
	  $auxJuros = 0;
	  $auxMulta = 0;
	  $auxDesconto = 0;
	  $auxTotal = 0;
      for($j = 0;$j < $numrows;$j++) {
	    if($elementos_numpres[$i] == pg_result($result,$j,"k00_numpre")) {
		  if(pg_result($result,$j,"k00_numpar") == @pg_result($result,$j+1,"k00_numpar")) {
		    $auxValor += (float)pg_result($result,$j,"vlrhis");
		    $auxValorcorr += (float)pg_result($result,$j,"vlrcor");
		    $auxJuros += (float)pg_result($result,$j,"vlrjuros");
		    $auxMulta += (float)pg_result($result,$j,"vlrmulta");
		    $auxDesconto += (float)pg_result($result,$j,"vlrdesconto");
		    $auxTotal += (float)pg_result($result,$j,"total");															
			$SomaDasParcelasValor[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] = $auxValor;
			$SomaDasParcelasValorcorr[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] = $auxValorcorr;
			$SomaDasParcelasJuros[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] = $auxJuros;
			$SomaDasParcelasMulta[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] = $auxMulta;
			$SomaDasParcelasDesconto[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] = $auxDesconto;
			$SomaDasParcelasTotal[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] = $auxTotal;												
			//echo $elementos_numpres[$i]." == ".pg_result($result,$j,"k00_numpar")." == ".@pg_result($result,$j+1,"k00_numpar")." == ".$aux."<br>";
		  } else {
	        $SomaDasParcelasValor[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] += pg_result($result,$j,"vlrhis");
	        $SomaDasParcelasValorcorr[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] += pg_result($result,$j,"vlrcor");
	        $SomaDasParcelasJuros[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] += pg_result($result,$j,"vlrjuros");
	        $SomaDasParcelasMulta[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] += pg_result($result,$j,"vlrmulta");
	        $SomaDasParcelasDesconto[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] += pg_result($result,$j,"vlrdesconto");												
	        $SomaDasParcelasTotal[$elementos_numpres[$i]][pg_result($result,$j,"k00_numpar")] += pg_result($result,$j,"total");
          }
		} else {		  		  
		  continue;
		}
	  }
	}	
    $vlrtotal = 0;
	$verf_parc = "";
	$cont = 0;
	$cont2 = 0;	
	$bool = 0;
	$bool2 = 0;	
    $ConfCor1 = "#EFE029";
    $ConfCor2 = "#E4F471";
    for($i = 0;$i < $numrows;$i++) {
      $vlrtotal += pg_result($result,$i,"total");
     if(pg_result($result,$i,"total") > 0 ){
	  if($elementos_numpres[$cont] != pg_result($result,$i,"k00_numpre")) {
	    $cont++;
	    if($bool == 0) {
          $ConfCor1 = "#77EE20";
          $ConfCor2 = "#A9F471";
		  $bool = 1;
	     } else {
          $ConfCor1 = "#EFE029";
          $ConfCor2 = "#E4F471";
		  $bool = 0;
		}
	  }
      $vlrtotal += pg_result($result,$i,"total");
	  $dtoper = pg_result($result,$i,"k00_dtoper");
	  $dtoper = mktime(0,0,0,substr($dtoper,5,2),substr($dtoper,8,2),substr($dtoper,0,4));
	  //if($dtoper > time())
	  //  $corDtoper = "#FF5151";
	  //else
	  $corDtoper = "";
	  $dtvenc = pg_result($result,$i,"k00_dtvenc");
	  $dtvenc = mktime(0,0,0,substr($dtvenc,5,2),substr($dtvenc,8,2),substr($dtvenc,0,4));
	  if($dtvenc < time())
	    $corDtvenc = "red";
	  else
	    $corDtvenc = "";
	  if(pg_result($result,$i,"k00_numpar") == $salva_parcela) {
	    $cor = $ConfCor1;
	  } else {
  	    $cor = $ConfCor2;
	    if(pg_result($result,$i,"k00_numpar") == @pg_result($result,$i+1,"k00_numpar"))
	      $salva_parcela = "";
	    else
	      $salva_parcela = @pg_result($result,$i+1,"k00_numpar");		
	  }	
      echo "<label for=\"CHECK$i\"><tr style=\"cursor: hand\" bgcolor=\"".$cor."\">\n";	
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap><input style=\"background-color:$cor;border:none\" onclick=\"window.open('cai3_gerfinanc005.php?".base64_encode($tipo."#".pg_result($result,$i,"k00_numpre")."#".pg_result($result,$i,"k00_numpar"))."','','width=600,height=500')\" type=\"button\" value=\"M I\"></td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap><input style=\"border:none;background-color:$cor\" onclick=\"location.href='cai3_gerfinanc008.php?".base64_encode("numpre=".pg_result($result,$i,"k00_numpre"))."'\" type=\"button\" value=\"".pg_result($result,$i,"k00_numpre")."\"></td>\n";

//      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".pg_result($result,$i,"k00_numpre")."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k00_numpar"))==""?"&nbsp":pg_result($result,$i,"k00_numpar"))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k00_numtot"))==""?"&nbsp":pg_result($result,$i,"k00_numtot"))."</td>\n";
//      echo "<td class=\"borda\" style=\"font-size:11px\" ".($corDtoper==""?"":"bgcolor=$corDtoper")." nowrap>".date("d-m-Y",$dtoper)."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" ".($corDtvenc==""?"":"bgcolor=$corDtvenc")." nowrap>".date("d-m-Y",$dtvenc)."</td>\n";	
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k01_descr"))==""?"&nbsp":pg_result($result,$i,"k01_descr"))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k00_receit"))==""?"&nbsp":pg_result($result,$i,"k00_receit"))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" nowrap>".(trim(pg_result($result,$i,"k02_descr"))==""?"&nbsp":pg_result($result,$i,"k02_descr"))."</td>\n";
	  
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"valor$i\" value=\"".$SomaDasParcelasValor[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".number_format(pg_result($result,$i,"vlrhis"),2,".",",")."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"valorcorr$i\" value=\"".$SomaDasParcelasValorcorr[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"vlrcor"))==""?"&nbsp":number_format(pg_result($result,$i,"vlrcor"),2,".",","))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"juros$i\" value=\"".$SomaDasParcelasJuros[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"vlrjuros"))==""?"&nbsp":number_format(pg_result($result,$i,"vlrjuros"),2,".",","))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"multa$i\" value=\"".$SomaDasParcelasMulta[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"vlrmulta"))==""?"&nbsp":number_format(pg_result($result,$i,"vlrmulta"),2,".",","))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"desconto$i\" value=\"".$SomaDasParcelasDesconto[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"vlrdesconto"))==""?"&nbsp":number_format(pg_result($result,$i,"vlrdesconto"),2,".",","))."</td>\n";
      echo "<td class=\"borda\" style=\"font-size:11px\" align=\"right\" nowrap><input type=\"hidden\" id=\"total$i\" value=\"".$SomaDasParcelasTotal[pg_result($result,$i,"k00_numpre")][pg_result($result,$i,"k00_numpar")]."\">".(trim(pg_result($result,$i,"total"))==""?"&nbsp":number_format(pg_result($result,$i,"total"),2,".",","))."</td>\n";
	  if($elementos_numpres[$cont2] == pg_result($result,$i,"k00_numpre")) {
//	    if($verf_parc != pg_result($result,$i,"k00_numpar") && $emrec == "t") {
	    if($verf_parc != pg_result($result,$i,"k00_numpar")) {
          echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>".($tipo==3?"<input type=\"button\" name=\"calculavalor\" onclick=\"js_recalcula()\" id=\"calculavalor$i\" value=\"Calcular\">":"")."<input type=\"".($tipo==3?"hidden":"checkbox")."\" value=\"".pg_result($result,$i,"k00_numpre")."NP".pg_result($result,$i,"k00_numpar")."\" onclick=\"js_soma(2)\" id=\"CHECK$i\" name=\"CHECK$i\" ".((abs(pg_result($result,$i,"k00_valor"))!=0 && $tipo==3)?"disabled":"")."></td>\n";
	      $verf_parc = pg_result($result,$i,"k00_numpar");
	    } else {
	      $verf_parc = pg_result($result,$i,"k00_numpar");
	      echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>&nbsp;</td>\n";		
        }
	  } else {
 	    $cont2++;
        $verf_parc = pg_result($result,$i,"k00_numpar");
//		if($emrec == "t")
          echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>".($tipo==3?"<input type=\"button\" onclick=\"js_recalcula()\" name=\"calculavalor\" id=\"calculavalor$i\" value=\"Calcular\">":"")."<input type=\"".($tipo==3?"hidden":"checkbox")."\" value=\"".pg_result($result,$i,"k00_numpre")."NP".pg_result($result,$i,"k00_numpar")."\" onclick=\"js_soma(2)\" id=\"CHECK$i\" name=\"CHECK$i\" ".((abs(pg_result($result,$i,"k00_valor"))!=0 && $tipo==3)?"disabled":"")."></td>\n";
//        else
//		  echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>&nbsp;</td>\n";				  
	  }
/*
      if(pg_result($result,$i,"k00_numpre") == @pg_result($result,$i + 1,"k00_numpre")) {
	    if($verf_parc != pg_result($result,$i,"k00_numpar") && $emrec == "t") {
          echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>".($tipo==3?"<input type=\"submit\" name=\"calculavalor\" id=\"calculavalor$i\" value=\"Calcular\">":"")."<input type=\"".($tipo==3?"hidden":"checkbox")."\" value=\"".pg_result($result,$i,"k00_numpre")."P".pg_result($result,$i,"k00_numpar")."\" onclick=\"js_soma(2)\" id=\"CHECK$i\" name=\"CHECK$i\" ".((abs(pg_result($result,$i,"k00_valor"))!=0 && $tipo==3)?"disabled":"")."></td>\n";
	      $verf_parc = pg_result($result,$i,"k00_numpar");
	    } else {
	      $verf_parc = pg_result($result,$i,"k00_numpar");
	      echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>&nbsp;</td>\n";		
        }
	  } else if($emrec == "t")
        echo "<td class=\"borda\" style=\"font-size:11px\" id=\"coluna$i\" nowrap>".($tipo==3?"<input type=\"submit\" name=\"calculavalor\" id=\"calculavalor$i\" value=\"Calcular\">":"")."<input type=\"".($tipo==3?"hidden":"checkbox")."\" value=\"".pg_result($result,$i,"k00_numpre")."P".pg_result($result,$i,"k00_numpar")."\" onclick=\"js_soma(2)\" id=\"CHECK$i\" name=\"CHECK$i\" ".((abs(pg_result($result,$i,"k00_valor"))!=0 && $tipo==3)?"disabled":"")."></td>\n";
	 */
	  echo "</tr></label>\n"; 
    }
	}
  echo "</table>\n</form>\n";
  echo "<script>
          parent.document.getElementById('valor1').innerText = \"0.00\";
          parent.document.getElementById('valorcorr1').innerText = \"0.00\";
          parent.document.getElementById('juros1').innerText = \"0.00\";
          parent.document.getElementById('multa1').innerText = \"0.00\";
          parent.document.getElementById('desconto1').innerText = \"0.00\";
          parent.document.getElementById('total1').innerText = \"0.00\";

          parent.document.getElementById('valor2').innerText = \"0.00\";
          parent.document.getElementById('valorcorr2').innerText = \"0.00\";
          parent.document.getElementById('juros2').innerText = \"0.00\";
          parent.document.getElementById('multa2').innerText = \"0.00\";
          parent.document.getElementById('desconto2').innerText = \"0.00\";
          parent.document.getElementById('total2').innerText = \"0.00\";
		  
          parent.document.getElementById('valor3').innerText = \"0.00\";
          parent.document.getElementById('valorcorr3').innerText = \"0.00\";
          parent.document.getElementById('juros3').innerText = \"0.00\";
          parent.document.getElementById('multa3').innerText = \"0.00\";
          parent.document.getElementById('desconto3').innerText = \"0.00\";
          parent.document.getElementById('total3').innerText = \"0.00\";
          parent.document.getElementById('relatorio').disabled = false;  
          js_soma(1)
		</script>\n";  

?>
</center>
</body>
</html>
<script>
  parent.document.getElementById('btmarca').value = "Marcar Todas";
  parent.document.getElementById('variavel').style.visibility = 'hidden'; 
</script>