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

//21.833.694.
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_iptubase_classe.php");
include("classes/db_issbase_classe.php");
include("classes/db_cgm_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
include("libs/db_libpessoal.php");
include("funcoes/db_func_pesdiver.php");


parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
//echo "<BR><BR>".$HTTP_SERVER_VARS['QUERY_STRING'];
db_postmemory($HTTP_POST_VARS);
$clcgm = new cl_cgm;
$clcgm->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label('r01_regist');
$clrotulo->label('q02_inscr');
$clrotulo->label('k00_numpre');
$clrotulo->label('v07_parcel');

	 
if(!isset($ano)){
  $ano = db_anofolha();
}
if(!isset($mes)){
  $mes = db_mesfolha();
}

//db_msgbox($ano ." -$pesquisar- ". $mes);
if(@$pesquisar != 'Atualizar'){
   $sqlanomes = "select max(r11_anousu||lpad(r11_mesusu,2,0)) from cfpess";
   $resultanomes = pg_exec($sqlanomes);
   db_fieldsmemory($resultanomes,0);
   $ano = substr($max,0,4);
   $mes = substr($max,4,2);
}

//db_msgbox($ano .' -- '. $mes);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<style type="text/css">
<!--
.tabcols {
  font-size:11px;
}
.tabcols1 {
  text-align: right;
  font-size:11px;
}
.btcols {
	height: 17px;
	font-size:10px;
}
.links {
	font-weight: bold;
	color: #0033FF;
	text-decoration: none;
	font-size:10px;
    cursor: hand;
}
a.links:hover {
    color:black;
	text-decoration: underline;
}
.links2 {
	font-weight: bold;
	color: #0587CD;
	text-decoration: none;
	font-size:10px;
}
a.links2:hover {
    color:black;
	text-decoration: underline;
}
.nome {
  color:black;  
}
a.nome:hover {
  color:blue;
}
-->
</style>
<script>
function js_chama_link(ponto){
 debitos.location.href = 'pes3_gerfinanc018.php?opcao='+ponto+'&numcgm='+document.formatu.z01_numcgm.value+'&matricula='+document.formatu.matricula.value+'&ano=<?=@$ano?>&mes=<?=@$mes?>&tbprev=<?=@$r01_tbprev?>&bases='+document.formatu.bases.value+'&rub_bases='+document.formatu.rub_bases.value+'&rub_cond='+document.formatu.rub_cond.value;
}
function js_chama_link2(ponto){
 debitos.location.href = 'pes3_consponto021.php?opcao='+ponto+'&numcgm='+document.formatu.z01_numcgm.value+'&matricula='+document.formatu.matricula.value+'&ano=<?=@$ano?>&mes=<?=@$mes?>&tbprev=<?=@$r01_tbprev?>&bases='+document.formatu.bases.value+'&rub_bases='+document.formatu.rub_bases.value+'&rub_cond='+document.formatu.rub_cond.value;
}
function js_MudaLink(nome) {
  document.getElementById('processando').style.visibility = 'visible';
/*
  document.getElementById("RRR").innerHTML = "<br><br><br>";
  for(i in 	document.getElementById('processandoTD').style) {
    document.getElementById("RRR").innerHTML += i + " => " + 	document.getElementById('processandoTD').style[i] + "<br>";
  }
*/
  if(navigator.appName == "Netscape") {
    TIPO = document.getElementById(nome).childNodes[1].firstChild.nodeValue;
  } else {
    TIPO = document.getElementById(nome).innerText;
	document.getElementById('processando').style.top = 113;
  }
//  if(nome.indexOf("tiposemdeb") != -1) 
//    document.getElementById('outrasopcoes').disabled = true;
//  else
//    document.getElementById('outrasopcoes').disabled = false;

  document.getElementById('processandoTD').innerHTML = '<h3>Aguarde, processando ' + TIPO + '...</h3>';
  for(i = 0;i < document.links.length;i++) {
    var L = document.links[i].id;
	if(L!=""){
 	  document.getElementById(L).style.backgroundColor = '#CCCCCC';
	  document.getElementById(L).hideFocus = true;
	}
  }
  document.getElementById(nome).style.backgroundColor = '#E8EE6F';
}

function js_relatorio(){
  jan = window.open('pes3_gerfinanc017.php?opcao='+document.formatu.opcao.value+'&numcgm='+document.formatu.numcgm.value+'&matricula='+document.formatu.matricula.value+'&ano=<?=$ano?>&mes=<?=$mes?>&tbprev='+document.formatu.tbprev.value,'sdjklsdklsdf','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
function js_Pesquisa(solicitacao) {
  js_OpenJanelaIframe('top.corpo','func_pesquisa','pes3_conspessoal002_detalhes.php?solicitacao='+solicitacao+'&parametro=<?=$r01_regist?>&ano=<?=$ano?>&mes=<?=$mes?>','CONSULTA DE FUNCIONÁRIOS',true,'20');
}


</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id="DDD"></div>
<div id="processando" style="position:absolute; left:27px; top:126px; width:957px; height:344px; z-index:1; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000;">
<Table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td align="center" valign="middle" id="processandoTD" onclick="document.getElementById('processando').style.visibility='hidden'">
    </td>
  </tr>
</Table>
</div>
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
    <center>
    <?	
	$mensagem_semdebitos = false;
	$com_debitos = true;
	if(isset($HTTP_POST_VARS["pesquisar"]) || isset($matricula) ) {
      echo "<form name=\"formatu\" action=\"pes3_gerfinanc001.php\" method=\"post\">\n";
      //aqui é pra se clicar no link da matricula em cai3_gerfinanc002.php
      if(isset($matricula) && !empty($matricula))
	    $HTTP_POST_VARS["r01_regist"] = $matricula;
	
	  if(!empty($HTTP_POST_VARS["r01_regist"])) {
  	    $sql = "select r01_regist,
                       r01_numcgm as k00_numcgm,
                       z01_numcgm,r01_tbprev 
                from   pessoal 
                       inner join cgm on r01_numcgm = z01_numcgm 
		        where 
                       r01_regist = ".$HTTP_POST_VARS["r01_regist"]." limit 1" ;
        // echo $sql;
	    $result = pg_exec($sql);
	    if(pg_numrows($result) == 0) {
	      echo "
                <script>
                  alert('Funcionário sem cálculo')
                </script>";
	      db_redireciona("pes3_gerfinanc001.php");
          // exit;
	    } else {
	      db_fieldsmemory($result,0);
	      $resultaux = $result;
          $arg = "matric=".$HTTP_POST_VARS["r01_regist"]; 
	    }

            
        ///////// VERIFICA SE A MATRÍCULA POSSUI SALÁRIO
	    $matricula = $HTTP_POST_VARS["r01_regist"];
 	    $resultgerfsal = pg_exec("select * 
	                              from gerfsal 
		                          where     r14_regist = $matricula 
                                        and r14_anousu = $ano 
                                        and r14_mesusu = $mes");
        if(pg_numrows($resultgerfsal) != 0){
	      $temsalario = true;
	    }else{
          $temsalario = false;
	    }
        ///////// VERIFICA SE A MATRÍCULA POSSUI FÉRIAS
 	    $resultgerffer = pg_exec("select * 
	                              from gerffer 
			                      where     r31_regist = $matricula 
			                            and r31_anousu = $ano 
				                        and r31_mesusu = $mes");
        if(pg_numrows($resultgerffer) != 0){
	      $temferias = true;
	    }else{
          $temferias = false;
	    }
        ///////// VERIFICA SE A MATRÍCULA POSSUI RESCISAO
 	    $resultgerfres = pg_exec("select * 
	                              from gerfres 
                                  where     r20_regist = $matricula 
			                            and r20_anousu = $ano 
				                        and r20_mesusu = $mes");
        if(pg_numrows($resultgerfres) != 0){
	      $temrescisao = true;
	    }else{
          $temrescisao = false;
	    }
        ///////// VERIFICA SE A MATRÍCULA POSSUI ADIANTAMENTO	
 	    $resultgerfadi = pg_exec("select * 
	                              from gerfadi 
			                      where     r22_regist = $matricula 
			                            and r22_anousu = $ano 
				                        and r22_mesusu = $mes");
        if(pg_numrows($resultgerfadi) != 0){
	      $temadiantamento = true;
	    }else{
          $temadiantamento = false;
	    }
        ///////// VERIFICA SE A MATRÍCULA POSSUI 13 SALÁRIO
 	    $resultgerfs13 = pg_exec("select * 
	                              from gerfs13 
			                      where     r35_regist = $matricula 
			                            and r35_anousu = $ano 
				                        and r35_mesusu = $mes");
        if(pg_numrows($resultgerfs13) != 0){
	      $tem13salario = true;
	    }else{
          $tem13salario = false;
	    }
        ///////// VERIFICA SE A MATRÍCULA POSSUI complementar
 	    $resultgerfcom = pg_exec("select * 
	                              from gerfcom 
              		              where     r48_regist = $matricula 
			                            and r48_anousu = $ano 
				                        and r48_mesusu = $mes");
        if(pg_numrows($resultgerfcom) != 0){
	      $temcomplementar = true;
	    }else{
          $temcomplementar = false;
	    }
        ///////// VERIFICA SE A MATRÍCULA POSSUI ponto fixo
 	    $resultgerffx = pg_exec("select * 
	                             from gerffx 
			                     where     r53_regist = $matricula 
			                           and r53_anousu = $ano 
				                       and r53_mesusu = $mes");
        if(pg_numrows($resultgerffx) != 0){
	      $tempontofixo = true;
	    }else{
          $tempontofixo = false;
	    }
        ///////// VERIFICA SE A MATRÍCULA POSSUI ajuste previdencia
 	    $resultpreviden = pg_exec("select * 
                                   from pessoal
				                        inner join previden on r60_numcgm = r01_numcgm
					                                       and r60_anousu = $ano
							                               and r60_mesusu = $mes
			                       where     r01_regist = $matricula 
			                             and r01_anousu = $ano 
				                         and r01_mesusu = $mes limit 1");
        if(pg_numrows($resultpreviden) != 0){
	      $temajustepreviden = true;
	    }else{
          $temajustepreviden = false;
	    }

        //////// VERIFICA SE A MATRÍCULA POSSUI ajuste irf
 	    $resultajusteir = pg_exec("select * 
	                               from pessoal
		                                inner join ajusteir on r61_numcgm = r01_numcgm
					                                       and r61_anousu = $ano
					                                       and r61_mesusu = $mes
			                       where     r01_regist = $matricula 
			                             and r01_anousu = $ano 
				                         and r01_mesusu = $mes limit 1");
        if(pg_numrows($resultajusteir) != 0){
	      $temajusteir = true;
	    }else{
          $temajusteir = false;
	    }
	  }
	  $dados = pg_exec("select z01_numcgm,
                               z01_nome,
                               z01_ender,
                               z01_munic,
                               z01_uf,
                               z01_cgccpf,
                               z01_ident 
	                    from cgm 
						where z01_numcgm = ".pg_result($result,0,"k00_numcgm"));
	  db_fieldsmemory($dados,0);	  

	?>
        <table width="100%" height="80%" border="1" cellspacing="0" cellpadding="0">
          <tr> 
            <td colspan="2" height="15%"> 
	          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="33%"> 
		            <table width="104%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td nowrap title="Clique Aqui para ver os dados cadastrais." class="tabcols">
                          <strong style=\"color:blue\>
                            <a href='' onclick='js_mostracgm();return false;'>NumCgm:&nbsp;</a>
                          </strong>
                        </td>
                        <td class="tabcols" nowrap title="Clique Aqui para ver os dados cadastrais."> 
                          <input class="btcols" type="text" name="z01_numcgm" value="<?=@$z01_numcgm?>" size="5" readonly> 
                          &nbsp;&nbsp;&nbsp; 
                          <?
					      parse_str($arg);
					      $Label = "<a href='' onclick='js_mostrapessoal();return false;'>$Lr01_regist</a>";
					      echo "<strong style=\"color:blue\">$Label</strong> <input style=\"border: 1px solid blue;font-weight: bold;background-color:#80E6FF\" class=\"btcols\" type=\"text\" name=\"Label\" value=\"".@$matric.@$inscr.@$numpre.@$Parcelamento."\" size=\"10\" readonly>\n";
					      ?>
                        </td>
                      </tr>
                      <tr> 
                        <td nowrap class="tabcols"><strong>Nome:</strong></td>
                        <td nowrap>
                          <input class="btcols" type="text" name="z01_nome" value="<?=@$z01_nome?>" size="46" readonly> 
                          &nbsp;
                        </td>
                      </tr>
                      <tr> 
                        <td nowrap class="tabcols"><strong>Endereço:</strong></td>
                        <td nowrap>
                          <input class="btcols" type="text" name="z01_ender" value="<?=@$z01_ender?>" size="46" readonly> 
                        </td>
                      </tr>
                      <tr> 
                        <td nowrap class="tabcols"><strong>Município:</strong></td>
                        <td>
                          <input class="btcols" type="text" name="z01_munic" value="<?=@$z01_munic?>" size="20" readonly> 
                          <strong class="tabcols">
                            UF:
                          </strong>
                          <input class="btcols" type="text" name="z01_uf" value="<?=@$z01_uf?>" size="2" maxlength="2" readonly=""> 
                          &nbsp;
                        </td>
                      </tr>
                      <tr> 
                        <td height="21" colspan="2" nowrap class="tabcols"> 
                          <?
                          if(isset($HTTP_POST_VARS["r01_regist"]) && !empty($HTTP_POST_VARS["r01_regist"]))
                            echo "<input type=\"hidden\" name=\"r01_regist\"  value=\"".$HTTP_POST_VARS["r01_regist"]."\">";
                          if(isset($HTTP_POST_VARS["q02_inscr"]) && !empty($HTTP_POST_VARS["q02_inscr"]))
                            echo "<input type=\"hidden\" name=\"q02_inscr\"  value=\"".$HTTP_POST_VARS["q02_inscr"]."\">";
                          if(isset($HTTP_POST_VARS["z01_numcgm"]) && !empty($HTTP_POST_VARS["z01_numcgm"]))
                            echo "<input type=\"hidden\" name=\"z01_numcgm\"  value=\"".$HTTP_POST_VARS["z01_numcgm"]."\">";
                          if(isset($HTTP_POST_VARS["v07_parcel"]) && !empty($HTTP_POST_VARS["v07_parcel"]))
                            echo "<input type=\"hidden\" name=\"v07_parcel\"  value=\"".$HTTP_POST_VARS["v07_parcel"]."\">";
	                      if(isset($HTTP_POST_VARS["k00_numpre"]) && !empty($HTTP_POST_VARS["k00_numpre"]))
                            echo "<input type=\"hidden\" name=\"k00_numpre\"  value=\"".$HTTP_POST_VARS["k00_numpre"]."\">";
					      ?>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td width="67%" valign="top"> 
                    <table border="1" cellspacing="0" cellpadding="0" >
		              <tr class="links">
		                <td valign="top" style="font-size:11px">
      <fieldset>
        <legend><strong>Calculos</strong></legend>
                          <?
                          $xopcao = '';
                          if(@$temsalario == true ){
		                    if($xopcao == '')
			                  $xopcao = 'salario';
                            echo "
                            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	                          <tr>
		                        <td valign=\"top\" class=\"links2\" id=\"temsalario\">
<a href=\"#\" class=\"links2\" onClick=\"js_chama_link('salario');js_MudaLink('temsalario');\" id=\"temsalario\">SALARIO</a>
				                </td>
                              </tr>
			                </table>\n";
		                  }
                          if(@$temferias == true ){
		                    if($xopcao == '')
			                  $xopcao = 'ferias';
                            echo "
                              <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		                        <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temferias\">
<a href=\"#\" class=\"links2\" onClick=\"js_chama_link('ferias');js_MudaLink('temferias');\" id=\"temferias\">FÉRIAS</a>
				                  </td>
                                </tr>
			                  </table>\n";
                          }
                          if(@$temrescisao == true ){
		                    if($xopcao == '')
			                  $xopcao = 'rescisao';
                            echo "
                              <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temrescisao\">
<a href=\"#\" class=\"links2\" onClick=\"js_chama_link('rescisao');js_MudaLink('temrescisao');\" id=\"temrescisao\">RESCISÃO</a>
				                  </td>
			                    </tr>
			                  </table>\n";
		                  }
                          if(@$temadiantamento == true ){
		                    if($xopcao == '')
			                  $xopcao = 'adiantamento';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temadiantamento\">
<a href=\"#\" class=\"links2\" onClick=\"js_chama_link('adiantamento');js_MudaLink('temadiantamento');\" id=\"temadiantamento\">ADIANTAMENTO</a>
                                  </td>
                                </tr>
                              </table>\n";
		                  }
                          if(@$tem13salario == true ){
		                    if($xopcao == '')
			                  $xopcao = '13salario';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"tem13salario\">
<a href=\"#\" class=\"links2\" onClick=\"js_chama_link('13salario');js_MudaLink('tem13salario');\" id=\"tem13salario\">13o. SALÁRIO</a>
				                  </td>
			                    </tr>
			                  </table>\n";
		                  }
                          if(@$temcomplementar == true ){
		                    if($xopcao == '')
			                  $xopcao = 'complementar';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temcomplementar\">
<a href=\"#\" class=\"links2\" onClick=\"js_chama_link('complementar');js_MudaLink('temcomplementar');\" id=\"temcomplementar\">COMPLEMENTAR</a>
				                  </td>
                                </tr>
			                  </table>\n";
		                  }
                          if(@$tempontofixo == true ){
		                    if($xopcao == '')
			                  $xopcao = 'fixo';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"tempontofixo\">
<a href=\"#\" class=\"links2\" onClick=\"js_chama_link('fixo');js_MudaLink('tempontofixo');\" id=\"tempontofixo\">PONTO FIXO</a>

				                  </td>
			                    </tr>
			                  </table>\n";
		                  }
                          if(@$temajustepreviden == true ){
	                        if($xopcao == '')
		                      $xopcao = 'previden';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temajustepreviden\">
			                        <a class=\"links2\" onClick=\"js_MudaLink('temajustepreviden')\" id=\"temajustepreviden\"  href=\"pes3_gerfinanc018.php?opcao=previden&numcgm=".$z01_numcgm."&matricula=".$matricula."&ano=".$ano."&mes=".$mes."&tbprev=".$r01_tbprev."&bases=\"+document.formatu.bases.value+\"&rub_bases=\"+document.formatu.rub_bases.value+\" target=\"debitos\">AJUSTE PREVIDÊNCIA</a>
				                  </td>
			                    </tr>
			                  </table>\n";
		                  }
                          if(@$temajusteir == true ){
		                    if($xopcao == '')
			                  $xopcao = 'irf';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temajusteir\">
			                        <a class=\"links2\" onClick=\"js_MudaLink('temajusteir')\" id=\"temajusteir\"  href=\"pes3_gerfinanc018.php?opcao=irf&numcgm=".$z01_numcgm."&matricula=".$matricula."&ano=".$ano."&mes=".$mes."&tbprev=".$r01_tbprev."&bases=\"+document.formatu.bases.value+\"&rub_bases=\"+document.formatu.rub_bases.value+\" target=\"debitos\">AJUSTE I.R.R.F</a>
				                  </td>
			                    </tr>
                              </table>\n";
		                  }
                          ?>
      </fieldset>
		                </td>
		                <td valign="top" style="font-size:11px">
      <fieldset>
        <legend><strong>Pontos</strong></legend>
                          <?
                          $xopcao = '';
                          if(@$temsalario == true ){
		                    if($xopcao == '')
			                  $xopcao = 'salario';
                            echo "
                            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	                          <tr>
		                        <td valign=\"top\" class=\"links2\" id=\"temsalario2\">
<a href=\"#\" class=\"links2\" onClick=\"js_chama_link2('salario');js_MudaLink('temsalario2');\" id=\"temsalario2\">SALARIO</a>
				                </td>
                              </tr>
			                </table>\n";
		                  }
                          if(@$temferias == true ){
		                    if($xopcao == '')
			                  $xopcao = 'ferias';
                            echo "
                              <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		                        <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temferias2\">
<a href=\"#\" class=\"links2\" onClick=\"js_chama_link2('ferias');js_MudaLink('temferias2');\" id=\"temferias2\">FÉRIAS</a>
				                  </td>
                                </tr>
			                  </table>\n";
                          }
                          if(@$temrescisao == true ){
		                    if($xopcao == '')
			                  $xopcao = 'rescisao';
                            echo "
                              <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temrescisao2\">
<a href=\"#\" class=\"links2\" onClick=\"js_chama_link2('rescisao');js_MudaLink('temrescisao2');\" id=\"temrescisao2\">RESCISÃO</a>
				                  </td>
			                    </tr>
			                  </table>\n";
		                  }
                          if(@$temadiantamento == true ){
		                    if($xopcao == '')
			                  $xopcao = 'adiantamento';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temadiantamento2\">
<a href=\"#\" class=\"links2\" onClick=\"js_chama_link2('adiantamento');js_MudaLink('temadiantamento2');\" id=\"temadiantamento2\">ADIANTAMENTO</a>
                                  </td>
                                </tr>
                              </table>\n";
		                  }
                          if(@$tem13salario == true ){
		                    if($xopcao == '')
			                  $xopcao = '13salario';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"tem13salario2\">
<a href=\"#\" class=\"links2\" onClick=\"js_chama_link2('13salario');js_MudaLink('tem13salario2');\" id=\"tem13salario2\">13o. SALÁRIO</a>
				                  </td>
			                    </tr>
			                  </table>\n";
		                  }
                          if(@$temcomplementar == true ){
		                    if($xopcao == '')
			                  $xopcao = 'complementar';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temcomplementar2\">
<a href=\"#\" class=\"links2\" onClick=\"js_chama_link2('complementar2');js_MudaLink('temcomplementar2');\" id=\"temcomplementar2\">COMPLEMENTAR</a>
				                  </td>
                                </tr>
			                  </table>\n";
		                  }
                          if(@$tempontofixo == true ){
		                    if($xopcao == '')
			                  $xopcao = 'fixo';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"tempontofixo2\">
<a href=\"#\" class=\"links2\" onClick=\"js_chama_link2('fixo');js_MudaLink('tempontofixo2');\" id=\"tempontofixo2\">PONTO FIXO</a>

				                  </td>
			                    </tr>
			                  </table>\n";
		                  }
                          if(@$temajustepreviden == true ){
	                        if($xopcao == '')
		                      $xopcao = 'previden';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temajustepreviden\">
			                        <a class=\"links2\" onClick=\"js_MudaLink('temajustepreviden')\" id=\"temajustepreviden\"  href=\"pes3_gerfinanc018.php?opcao=previden&numcgm=".$z01_numcgm."&matricula=".$matricula."&ano=".$ano."&mes=".$mes."&tbprev=".$r01_tbprev."&bases=\"+document.formatu.bases.value+\"&rub_bases=\"+document.formatu.rub_bases.value+\" target=\"debitos\">AJUSTE PREVIDÊNCIA</a>
				                  </td>
			                    </tr>
			                  </table>\n";
		                  }
                          if(@$temajusteir == true ){
		                    if($xopcao == '')
			                  $xopcao = 'irf';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temajusteir\">
			                        <a class=\"links2\" onClick=\"js_MudaLink('temajusteir')\" id=\"temajusteir\"  href=\"pes3_gerfinanc018.php?opcao=irf&numcgm=".$z01_numcgm."&matricula=".$matricula."&ano=".$ano."&mes=".$mes."&tbprev=".$r01_tbprev."&bases=\"+document.formatu.bases.value+\"&rub_bases=\"+document.formatu.rub_bases.value+\" target=\"debitos\">AJUSTE I.R.R.F</a>
				                  </td>
			                    </tr>
                              </table>\n";
		                  }
                          ?>
      </fieldset>
                        </td>
	                <td valign="top" style="font-size:11px">
                        <fieldset>
                         <legend><strong>Legendas</strong></legend>
	                    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                              <td nowrap class="tabcols"><strong>&nbsp;&nbsp;&nbsp;# - Incidencia da Base </strong></td>
                            </table>
	                    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                              <td nowrap class="tabcols"><strong>&nbsp;&nbsp;&nbsp;B - Formula com a Base</strong></td>
                            </table>
	                    <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                              <td nowrap class="tabcols"><strong>&nbsp;&nbsp;&nbsp;F - Incide na Formula com Condicao </strong></td>
                            </table>
                        </fieldset>
                  </td>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr> 
            <td colspan="2"  height="80%"  align="center" valign="middle"> 
	          <table border="0" height="100%" width="100%" cellspacing="0" cellpadding="0">
                <tr> 
                  <td align="center">
                    <iframe id="debitos" height="90%" width="95%" name="debitos" src="pes3_gerfinanc018.php?opcao=<?=$xopcao?>&numcgm=<?=$z01_numcgm?>&matricula=<?=$matricula?>&ano=<?=$ano?>&mes=<?=$mes?>&tbprev=<?$r01_tbprev?>&bases=<?=@$bases?>&rub_bases=<?=@$rub_bases?>&rub_cond=<?=@$rub_cond?>"></iframe> 
                    <input type="hidden" name="matricula"  value="<?=$matricula?>">
                    <input type="hidden" name="numcgm"  value="<?=$z01_numcgm?>">
                    <input type="hidden" name="opcao"  value="<?=$xopcao?>">
                    <input type="hidden" name="tbprev"  value="<?=$r01_tbprev?>">
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr> 
            <td  height="5%" colspan="2" align="center"> 
              <?
              if(!isset($novapesquisa)){
                $novapesquisa = "pes3_gerfinanc001.php";
              } 
              if(isset($voltarcorreto)){
              	if(isset($rubric)){
              	  $novapesquisa = "pes3_codfinanc001.php";
              	  echo "
                  <input name='retornar' type='button' id='voltar' value='Voltar' title='Voltar para consulta financeira por código' onclick='location.href=\"pes3_codfinanc002.php?rubric=".$rubric."&ano=".$ano."&mes=".$mes."&opcao=".$xopcao."\"'>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  ";
              	}else if(isset($funcao)){
              	  $novapesquisa = "pes3_consrhfuncao001.php";
              	  echo "
                  <input name='retornar' type='button' id='voltar' value='Voltar' title='Voltar para consulta cargo' onclick='location.href=\"pes3_consrhfuncao002.php?funcao=".$funcao."&ano=".$ano."&mes=".$mes."\"'>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  ";
              	}else if(isset($lotacao)){
              	  $novapesquisa = "pes3_consrhlotacao001.php";
              	  echo "
                  <input name='retornar' type='button' id='voltar' value='Voltar' title='Voltar para consulta lotação' onclick='location.href=\"pes3_consrhlotacao002.php?lotacao=".$lotacao."&ano=".$ano."&mes=".$mes."\"'>
                  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  ";
              	}
              }
              db_input("novapesquisa",8,0,true,'hidden',4);
              db_input("rub_bases",8,0,true,'hidden',4);
              db_input("rub_cond",8,0,true,'hidden',4);
	      
	      global $subpes;
	      $subpes = db_anofolha()."/".db_mesfolha();
              global $diversos;
              db_selectmax( "diversos", "select * from pesdiver ".bb_condicaosubpes( "r07_" ));
              for($Idiversos=0;$Idiversos<count($diversos);$Idiversos++){
                $codigo = $diversos[$Idiversos]["r07_codigo"];
	        global $$codigo;
	        eval('$$codigo = '.$diversos[$Idiversos]["r07_valor"].";");
              }
              $result_variaveis = db_retorno_variaveis($ano, $mes, $matricula);
	      
              $campos_pessoal_  = "r01_tbprev,r01_regime, r01_tpvinc,r01_regist,r01_funcao,";
              $campos_pessoal_ .= "r01_salari, r01_padrao,r01_hrssem,r01_hrsmen, r01_tpcont,";
              $campos_pessoal_ .= "r01_anter,  r01_trien, r01_progr, r01_prores, r01_clas1,r01_clas2 ";
              $condicaoaux      = " and r01_regist = ".db_sqlformat( $matricula );
              global $pessoal,$Ipessoal;
              db_selectmax( "pessoal", "select ".$campos_pessoal_." from pessoal ".bb_condicaosubpes( "r01_" ).$condicaoaux );
	      
              $Ipessoal = 0;
              if ( $xopcao == 'salario' ){
                $result = &$resultgerfsal;
                $sigla   = 'r14_';
              }elseif ( $xopcao == 'ferias' ){
                $result = &$resultgerffer;
                $sigla   = 'r31_';
              }elseif ( $xopcao == 'rescisao' ){
                $result = &$resultgerfres;
                $sigla   = 'r20_';
              }elseif ($xopcao == 'adiantamento'){
                $result = &$resultgerfadi;
                $sigla   = 'r22_';
              }elseif ($xopcao == '13salario'){
                $result = &$resultgerfs13;
                $sigla   = 'r35_';
              }elseif ($xopcao == 'complementar'){
                $result = &$resultgerfcom;
                $sigla   = 'r48_';
              }elseif ($xopcao == 'fixo'){
                $result = &$resultgerffx;
                $sigla   = 'r53_';
              }
	      if(isset($sigla)){
		$arr_cond = array();
		$arr_bases = array();
		$arr_rub_base = array();
		for($x=0;$x<pg_numrows($result);$x++){
		   db_fieldsmemory($result,$x,true);
		   $strrubrica = $sigla."rubric";
		   $rubrica = $$strrubrica;
		     $condicaoaux = " and r06_codigo = ".db_sqlformat( $rubrica);
		     global $rubr_;
		     db_selectmax( "rubr_", "select * from rubricas ".bb_condicaosubpes("r06_").$condicaoaux );
		     $r10_pd = ('t' == $rubr_[0]["r06_pd"] );
		     $formula = $rubr_[0]["r06_form"];
		     $qual_form = 1;
		     $cond = trim($rubr_[0]["r06_cond2"]);
		     $cond = str_replace('$F','$f',$cond);
		     if( !db_empty($cond) ){
			$cond = '$condicao = '.$cond.";";
			eval($cond);
		       if( $condicao ){
			  $formula =  $rubr_[0]["r06_form2"];
			  $qual_form = 2;
		       }
		     }
		     $cond = trim($rubr_[0]["r06_cond3"]);
		     $cond = str_replace('$F','$f',$cond);
		     if( !db_empty($cond) ){
			$cond = '$condicao = '.$cond.';';
		  //      echo "<BR> cond --> $cond";
			eval($cond);
			if( $condicao ){
			   $formula =  $rubr_[0]["r06_form3"];
			  $qual_form = 3;
			}
		     }
		     
		     $arr_cond[$rubrica] =$rubrica.$qual_form;	     
		     $r10_form = '('.trim($formula).')';
		     if( $r10_pd){
			 $r10_form = "+".$r10_form;
		     }else{
			 $r10_form = "-".$r10_form;
		     }
		     $r10_form = str_replace('D','$D',$r10_form);
		     $r10_form = str_replace('F','$F',$r10_form);
		     
		     $formula = $r10_form;
  //		 echo "<BR> rubrica --> $rubrica formula --> $formula";
		     $pos_base = strpos("#".$formula,"B")+0;
		     if( $pos_base > 0 && db_val(substr("#".$formula,$pos_base+1,3)) > 0 ){
		       $base_mae = substr("#".$formula,$pos_base,4);
		       while( $pos_base  > 0 && db_val(substr("#".$formula,$pos_base+1,3)) > 0 ){
			 $base = substr("#".$formula,$pos_base,4);
			 $pos = db_ascan($arr_bases,$base);
			 if($pos == 0){
			    $arr_bases[$base] = $base;
			 }	  
			 $arr_rub_base[$base.$rubrica] = $base.$rubrica; 
			 $formula = db_strtran($formula,$base,"|") ;
			 $pos_base = (strpos("#".$formula,"B")+0);
		       }		 
		     }
				   
		} 
		if(@$temferias == true ){
		  
		   $condicaoaux = " and r33_codtab = ".db_sqlformat($r01_tbprev+2);
		   global $inssirf_;
		   $achou_tabela = db_selectmax( "inssirf_", "select * from inssirf ".bb_condicaosubpes( "r33_" ).$condicaoaux );
		   $inssirf_base_ferias = "B002";
		   if( !db_empty( $inssirf_[0]["r33_basfer"] )){
		      $arr_bases[$base] = $inssirf_[0]["r33_basfer"];
		   }
		}
		echo "<SCRIPT>document.formatu.rub_cond.value = '".implode($arr_cond,',')."';</SCRIPT>";
		echo "<SCRIPT>document.formatu.rub_bases.value = '".implode($arr_rub_base,',')."';</SCRIPT>";
		db_select("bases", $arr_bases, true, 1,"onchange='document.formatu.submit();'");
	      }
              ?>
	      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	      
              <input type="button" name="vars1" style="width:80px" value="Bases"       onclick="js_Pesquisa('Bases');" >
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="button" name="vars2" style="width:80px" value="Diversos"       onclick="js_Pesquisa('Diversos');" >
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input type="button" name="vars" style="width:80px" value="Variáveis"       onclick="js_Pesquisa('Variaveis');" >
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input name="retornar" type="button" id="retornar" value="Nova Pesquisa" title="Inicio da Consulta" onclick="location.href='<?=($novapesquisa)?>'"> 
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input name="pesquisar" type="submit" id="pesquisar"  title="Atualiza a Consulta" value="Atualizar">	
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
	          <input name="imprimir" type="button" id="imprimir" value="Imprimir" title="Imprimir" onclick="js_relatorio();">
	          <strong>
                &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;
                Período:
              </strong>
              &nbsp;&nbsp;
       	      <?
    	      db_input("ano",4,'',true,'text',4)
	          ?>
	          &nbsp;/&nbsp;
	          <?
    	      db_input("mes",2,'',true,'text',4)
	          ?>
            </td>   
           </tr>
        </table>
      </form>
    <?
	} else {
	?>
      <form name="form1" method="post">
	    <table border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr> 
            <td align="right" title="<?=$Tr01_regist?>"> 
              <?
    		  db_ancora($Lr01_regist,'js_pesquisaregist(true);',2)
    		  ?>
    		  &nbsp;&nbsp;&nbsp;
            </td >
            <td align="left" > 
              <?
    		  db_input("r01_regist",8,$Ir01_regist,true,'text',4,"onchange='js_pesquisaregist(false);'")
    		  ?>
              <?
    		  db_input("z01_nome",40,$Iz01_nome,true,'text',3)
    		  ?>
            </td>
          </tr>
	 <?echo "<script>document.form1.r01_regist.focus();</script>" ; ?>
          <tr> 
            <td height="25" align="center" colspan="2">
	    <input onClick="return js_verificaregistro();"  type="submit" value="Pesquisar" name="pesquisar"></td>
          </tr>
        </table>
      </form>
    <?
	}
	?>
  </center>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_verificaregistro(){
  if(document.form1.r01_regist.value=='' ) 
  { 
    alert('Informe matricula.');
    return false; 
  }
  return true;
}

function js_pesquisaregist(mostra){
     if(mostra==true){
       db_iframepessoal.jan.location.href = 'func_rhpessoal.php?funcao_js=parent.js_mostraregist1|rh01_regist|z01_nome';
       db_iframepessoal.mostraMsg();
       db_iframepessoal.show();
       db_iframepessoal.focus();
     }else{
       db_iframepessoal.jan.location.href = 'func_rhpessoal.php?pesquisa_chave='+document.form1.r01_regist.value+'&funcao_js=parent.js_mostraregist';
     }
}
function js_mostraregist(chave,erro){
  document.form1.z01_nome.value = chave;
  if(erro==true){
     document.form1.r01_regist.focus();
     document.form1.r01_regist.value = '';
  }
}
function js_mostraregist1(chave1,chave2){
 document.form1.r01_regist.value = chave1;
 document.form1.z01_nome.value = chave2;
 db_iframepessoal.hide();
}


function js_mostradetalhes(chave){
  db_iframepessoal.jan.location.href = chave;
  db_iframepessoal.mostraMsg();
  db_iframepessoal.show();
  db_iframepessoal.focus();
}

// mostra os dados do cgm do contribuinte
function js_mostracgm(){
  db_iframepessoal.jan.location.href = 'prot3_conscgm002.php?fechar=func_nome&numcgm=<?=@$z01_numcgm?>';
  db_iframepessoal.mostraMsg();
  db_iframepessoal.show();
  db_iframepessoal.focus();
}


// esta funcao é utilizada quando clicar na matricula após pesquisar
// a mesma
function js_mostrapessoal(){
  db_iframepessoal.jan.location.href = 'pes3_conspessoal002.php?regist=<?=@$matric?>';
  db_iframepessoal.mostraMsg();
  db_iframepessoal.show();
  db_iframepessoal.focus();
}
// esta funcao é utilizada quando clicar na inscricao após pesquisar
// a mesma
	

function js_mostradetalhes(chave){
  db_iframepessoal.jan.location.href = chave;
  db_iframepessoal.mostraMsg();
  db_iframepessoal.show();
  db_iframepessoal.focus();
}

</script>

<?

$func_nome = new janela('db_iframepessoal','');
$func_nome ->posX=1;
$func_nome ->posY=20;
$func_nome ->largura=780;
$func_nome ->altura=430;
$func_nome ->titulo="Pesquisa";
$func_nome ->iniciarVisivel = false;
$func_nome ->mostrar();

$fnome = new janela('fnome','');
$fnome ->posX=20;
$fnome ->posY=20;
$fnome ->largura=770;
$fnome ->altura=430;
$fnome ->titulo="Pesquisa";
$fnome ->iniciarVisivel = false;
$fnome ->mostrar();

?>