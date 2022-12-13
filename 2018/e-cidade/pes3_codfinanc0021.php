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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_rhrubricas_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
$clrhrubricas = new cl_rhrubricas;
$clrhrubricas->rotulo->label();
$clrotulo = new rotulocampo;

if(!isset($ano) || (isset($ano) && trim($ano)=="")){
  $ano = db_anofolha();
}
if(!isset($mes) || (isset($mes) && trim($mes)=="")){
  $mes = db_mesfolha();
}
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
function js_MudaLink(nome) {
  document.getElementById('processando').style.visibility = 'visible';
  if(navigator.appName == "Netscape") {
    TIPO = document.getElementById(nome).childNodes[1].firstChild.nodeValue;
  } else {
    TIPO = document.getElementById(nome).innerText;
	document.getElementById('processando').style.top = 150;
  }
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
/*
function js_relatorio(){
    jan = window.open('pes3_gerfinanc017.php?opcao='+document.formatu.opcao.value+'&numcgm='+document.formatu.numcgm.value+'&matricula='+document.formatu.matricula.value+'&ano=<?=$ano?>&mes=<?=$mes?>&tbprev='+document.formatu.tbprev.value,'sdjklsdklsdf','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
      jan.moveTo(0,0);

}
*/
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id="DDD"></div>
<div id="processando" style="position:absolute; left:25px; top:107px; width:975px; height:400px; z-index:1; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000;">
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
	if(isset($rubric) && trim($rubric) != "") {

	    $sCamposRubricas = "rh27_rubric,
	                        rh27_descr,
	                        case 
	                          when rh27_pd = 1 then 'PROVENTO' 
	                          when rh27_pd = 2 then 'DESCONTO'
	                          else 'BASE' 
	                        end as rh27_pd";
      $result_rubricas = $clrhrubricas->sql_record($clrhrubricas->sql_query_file($rubric,db_getsession('DB_instit'),$sCamposRubricas));
      if($clrhrubricas->numrows > 0){
      	db_fieldsmemory($result_rubricas,0);
      }else{
      	db_msgbox("Rubrica inexistente");
      	echo "<script>location.href = 'pes3_codfinanc001.php'</script>";
      }

      // echo "<form name=\"formatu\" action=\"pes3_gerfinanc001.php\" method=\"post\">\n";
      //aqui é pra se clicar no link da matricula em cai3_gerfinanc002.php
	
	  if(!empty($rubric)) {
        ///////// VERIFICA SE A RUBRICA POSSUI SALÁRIO
 	    $resultgerfsal = pg_exec("select * 
	                              from gerfsal 
		                          where     r14_rubric = '$rubric' 
                                        and r14_anousu = $ano 
                                        and r14_mesusu = $mes 
																				and r14_instit = ".db_getsession('DB_instit')." limit 1" );
        if(pg_numrows($resultgerfsal) != 0){
	      $temsalario = true;
	    }else{
          $temsalario = false;
	    }
        ///////// VERIFICA SE A RUBRICA POSSUI FÉRIAS
 	    $resultgerffer = pg_exec("select * 
	                              from gerffer 
			                      where     r31_rubric = '$rubric' 
			                            and r31_anousu = $ano 
				                        and r31_mesusu = $mes 
																and r31_instit = ".db_getsession('DB_instit')." limit 1");
        if(pg_numrows($resultgerffer) != 0){
	      $temferias = true;
	    }else{
          $temferias = false;
	    }
        ///////// VERIFICA SE A RUBRICA POSSUI RESCISAO
 	    $resultgerfres = pg_exec("select * 
	                              from gerfres 
                                  where     r20_rubric = '$rubric' 
			                            and r20_anousu = $ano 
				                          and r20_mesusu = $mes 
																	and r20_instit = ".db_getsession('DB_instit')." limit 1");
        if(pg_numrows($resultgerfres) != 0){
	      $temrescisao = true;
	    }else{
          $temrescisao = false;
	    }
        ///////// VERIFICA SE A RUBRICA POSSUI ADIANTAMENTO	
 	    $resultgerfadi = pg_exec("select * 
	                              from gerfadi 
			                      where     r22_rubric = '$rubric' 
			                          and r22_anousu = $ano 
				                        and r22_mesusu = $mes
																and r22_instit = ".db_getsession('DB_instit')."limit 1");
        if(pg_numrows($resultgerfadi) != 0){
	      $temadiantamento = true;
	    }else{
          $temadiantamento = false;
	    }
        ///////// VERIFICA SE A RUBRICA POSSUI 13 SALÁRIO
 	    $resultgerfs13 = pg_exec("select * 
	                              from gerfs13 
			                      where     r35_rubric = '$rubric' 
			                          and r35_anousu = $ano 
				                        and r35_mesusu = $mes
																and r35_instit = ".db_getsession('DB_instit')." limit 1");
        if(pg_numrows($resultgerfs13) != 0){
	      $tem13salario = true;
	    }else{
          $tem13salario = false;
	    }
        ///////// VERIFICA SE A RUBRICA POSSUI complementar
 	    $resultgerfcom = pg_exec("select * 
	                              from gerfcom 
              		              where     r48_rubric = '$rubric' 
			                            and r48_anousu = $ano 
				                        and r48_mesusu = $mes
																and r48_instit = ".db_getsession('DB_instit')." limit 1");
        if(pg_numrows($resultgerfcom) != 0){
	      $temcomplementar = true;
	    }else{
          $temcomplementar = false;
	    }
        ///////// VERIFICA SE A RUBRICA POSSUI ponto fixo
 	    $resultgerffx = pg_exec("select * 
	                             from gerffx 
			                     where     r53_rubric = '$rubric' 
			                         and r53_anousu = $ano 
				                       and r53_mesusu = $mes
															 and r53_instit = ".db_getsession('DB_instit')." limit 1");
        if(pg_numrows($resultgerffx) != 0){
	      $tempontofixo = true;
	    }else{
          $tempontofixo = false;
	    }

	  }
	?>
	    <form name='form1'>
        <table width="100%" height="90%" border="0" cellspacing="0" cellpadding="0">
          <tr> 
            <td colspan="2"> 
	          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="40%"> 
		            <table width="104%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td nowrap class="tabcols">
                          <strong style=\"color:blue\>
                            <?
                            db_ancora("$Lrh27_rubric","","3");
                            ?>
                          </strong>
                        </td>
                        <td class="tabcols" nowrap> 
                          <?
                           db_input('rh27_rubric', 8, $Irh27_rubric, true, 'text', 3);
                          ?>
                          <?
                          db_input('rh27_descr', 30, $Irh27_descr, true, 'text', 3);
                          ?>
                        </td>
                        <td nowrap>
                          &nbsp;&nbsp;&nbsp;&nbsp;
                          <b>
                          <?
                          echo ($rh27_pd);
                          ?>
                          </b>
                          &nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td width="67%" valign="top"> 
                    <table border="1" cellspacing="0" cellpadding="0">
		              <tr class="links">
		                <td valign="top" style="font-size:11px">
                          <?
                          $xopcao = '';
                          $erro   = 0;
                          if(@$temsalario == true ){
		                    if($xopcao == '')
			                  $xopcao = 'salario';
                            echo "
                              <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	                            <tr>
		                          <td valign=\"top\" class=\"links2\" id=\"temsalario\">
			                        <a class=\"links2\" onClick=\"js_MudaLink('temsalario')\" id=\"temsalario\"  href=\"pes3_codfinanc021.php?opcao=salario&rubric=".$rubric."&ano=".$ano."&mes=".$mes."\" target=\"registros\">SALÁRIO</a>
                                  </td>
                                </tr>
			                  </table>\n";
			                  $erro ++;
			                  
		                  }
                          if(@$temferias == true ){
		                    if($xopcao == '')
			                  $xopcao = 'ferias';
                            echo "
                              <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
		                        <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temferias\">
			                        <a class=\"links2\" onClick=\"js_MudaLink('temferias')\" id=\"temferias\"  href=\"pes3_codfinanc021.php?opcao=ferias&rubric=".$rubric."&ano=".$ano."&mes=".$mes."\" target=\"registros\">FÉRIAS</a>
				                  </td>
                                </tr>
			                  </table>\n";
			                  $erro ++;
                          }
                          if(@$temrescisao == true ){
		                    if($xopcao == '')
			                  $xopcao = 'rescisao';
                            echo "
                              <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temrescisao\">
			                        <a class=\"links2\" onClick=\"js_MudaLink('temrescisao')\" id=\"temrescisao\"  href=\"pes3_codfinanc021.php?opcao=rescisao&rubric=".$rubric."&ano=".$ano."&mes=".$mes."\" target=\"registros\">RESCISÃO</a>
				                  </td>
			                    </tr>
			                  </table>\n";
			                  $erro ++;
		                  }
                          if(@$temadiantamento == true ){
		                    if($xopcao == '')
			                  $xopcao = 'adiantamento';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temadiantamento\">
			                        <a class=\"links2\" onClick=\"js_MudaLink('temadiantamento')\" id=\"temadiantamento\"  href=\"pes3_codfinanc021.php?opcao=adiantamento&rubric=".$rubric."&ano=".$ano."&mes=".$mes."\" target=\"registros\">ADIANTAMENTO</a>
                                  </td>
                                </tr>
                              </table>\n";
			                  $erro ++;
		                  }
                          if(@$tem13salario == true ){
		                    if($xopcao == '')
			                  $xopcao = '13salario';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"tem13salario\">
                                    <a class=\"links2\" onClick=\"js_MudaLink('tem13salario')\" id=\"tem13salario\"  href=\"pes3_codfinanc021.php?opcao=13salario&rubric=".$rubric."&ano=".$ano."&mes=".$mes."\" target=\"registros\">13o. SALÁRIO</a>
				                  </td>
			                    </tr>
			                  </table>\n";
			                  $erro ++;
		                  }
                          if(@$temcomplementar == true ){
		                    if($xopcao == '')
			                  $xopcao = 'complementar';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"temcomplementar\">
			                        <a class=\"links2\" onClick=\"js_MudaLink('temcomplementar')\" id=\"temcomplementar\"  href=\"pes3_gerfinanc021.php?opcao=complementar&numcgm=".$z01_numcgm."&matricula=".$matricula."&ano=".$ano."&mes=".$mes."&tbprev=".$r01_tbprev."\" target=\"debitos\">COMPLEMENTAR</a>
				                  </td>
                                </tr>
			                  </table>\n";
			                  $erro ++;
		                  }
                          if(@$tempontofixo == true ){
		                    if($xopcao == '')
			                  $xopcao = 'fixo';
                            echo "
			                  <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
			                    <tr>
			                      <td valign=\"top\" class=\"links2\" id=\"tempontofixo\">
                                    <a class=\"links2\" onClick=\"js_MudaLink('tempontofixo')\" id=\"tempontofixo\"  href=\"pes3_codfinanc021.php?opcao=fixo&rubric=".$rubric."&ano=".$ano."&mes=".$mes."\" target=\"registros\">PONTO FIXO</a>
				                  </td>
			                    </tr>
			                  </table>\n";
			                  $erro ++;
		                  }
		                  if($erro == 0){
		                  	unset($opcao);
		                  	echo "
                            RUBRICA SEM LANÇAMENTOS
                            ";
		                  }
                          ?>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"  height="90%"  valign="middle"> 
	          <table width="100%" height="90%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td align="center">
                    <?
                    $qry = "";
                    if(isset($opcao) && trim($opcao)!=""){
                      $qry = "?opcao=$opcao&mes=$mes&ano=$ano&rubric=$rubric";
                    }
                    //echo $qry;
                    ?> 
                    <iframe id="registros" height="90%" width="95%" name="registros" src="pes3_codfinanc021.php<?=$qry?>"></iframe> 
                    <input type="hidden" name="rubric"  value="<?=$rubric?>">
                    <input type="hidden" name="opcao"  value="<?=$xopcao?>">
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="retornar" type="button" id="retornar" value="Nova Pesquisa" title="Inicio da Consulta" onclick="location.href='pes3_codfinanc001.php'"> 
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              <input name="pesquisar" type="submit" id="pesquisar"  title="Atualiza a Consulta" value="Atualizar">
              <!--	
              &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; 
	          <input name="imprimir" type="button" id="imprimir" value="Imprimir" title="Imprimir" onclick="js_relatorio();">
	          -->
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
	}
	?>
  </center>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>