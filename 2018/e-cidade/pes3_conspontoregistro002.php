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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_cgm_classe.php");
include("classes/db_rhpessoal_classe.php");
include("classes/db_pontofx_classe.php");
include("classes/db_pontofs_classe.php");
include("classes/db_pontofe_classe.php");
include("classes/db_pontofa_classe.php");
include("classes/db_pontofr_classe.php");
include("classes/db_pontof13_classe.php");
include("classes/db_pontocom_classe.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_GET_VARS,2);
//echo "<BR><BR>".$HTTP_SERVER_VARS['QUERY_STRING'];
$clcgm = new cl_cgm;
$clrhpessoal  = new cl_rhpessoal;
$clpontofx  = new cl_pontofx;
$clpontofs  = new cl_pontofs;
$clpontofe  = new cl_pontofe;
$clpontofa  = new cl_pontofa;
$clpontofr  = new cl_pontofr;
$clpontof13 = new cl_pontof13;
$clpontocom = new cl_pontocom;
$clrotulo = new rotulocampo;
$clcgm->rotulo->label();
$clrotulo->label('r01_regist');
$clrotulo->label('q02_inscr');
$clrotulo->label('k00_numpre');
$clrotulo->label('v07_parcel');

if(!isset($ano) || (isset($ano) && trim($ano)!="")){
  $ano = db_anofolha();
}
if(!isset($mes) || (isset($mes) && trim($mes)!="")){
  $mes = db_mesfolha();
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
function js_MudaLink(nome) {
  document.form1.opcao.value = nome;
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
function js_relatorio(){
  jan = window.open('pes2_conspontoregistro002.php?opcao='+document.form1.opcao.value+'&matricula='+document.form1.r01_regist.value+'&ano=<?=$ano?>&mes=<?=$mes?>','sdjklsdklsdf','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div id="DDD"></div>
<div id="processando" style="position:absolute; left:26px; top:148px; width:955px; height:359px; z-index:1; visibility: hidden; background-color: #FFFFFF; layer-background-color: #FFFFFF; border: 1px none #000000;">
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
	if(isset($r01_regist)){
      echo "<form name=\"form1\" method=\"post\">\n";
      
	  if(!empty($r01_regist)) {	  	
	  	$matricula = $r01_regist;
	  	
      $result_registro = $clrhpessoal->sql_record($clrhpessoal->sql_query_cgm($matricula,"rh02_regist as r01_regist,rh02_tbprev as r01_tbprev,z01_numcgm,z01_nome",null," rh02_regist = $matricula and rh02_instit = ".db_getsession("DB_instit")));	  	
	    if($clrhpessoal->numrows == 0) {
	      echo "
                <script>
                  alert('Funcionário sem cálculo')
                </script>";
	      db_redireciona("pes3_conspontoregistro001.php");
          // exit;
	    } else {
	      db_fieldsmemory($result_registro,0);
	      $resultaux = $result_registro;
          $arg = "matric=".$r01_regist; 
	    }
	    
        ///////// VERIFICA SE A MATRÍCULA POSSUI PONTO DE SALÁRIO        
 	    $result_pontofs = $clpontofs->sql_record($clpontofs->sql_query_file($ano,$mes,$matricula)); 
        if($clpontofs->numrows != 0){
	      $temsalario = true;
	    }else{
          $temsalario = false;
	    }
	    
        ///////// VERIFICA SE A MATRÍCULA POSSUI PONTO DE FÉRIAS
        $result_pontofe = $clpontofe->sql_record($clpontofe->sql_query_file($ano,$mes,$matricula)); 
        if($clpontofe->numrows != 0){
	      $temferias = true;
	    }else{
          $temferias = false;
	    }
	    
        ///////// VERIFICA SE A MATRÍCULA POSSUI PONTO DE RESCISÃO
        $result_pontofr = $clpontofr->sql_record($clpontofr->sql_query_file($ano,$mes,$matricula)); 
        if($clpontofr->numrows != 0){
          $temrescisao = true;
	    }else{
          $temrescisao = false;
	    }
	    
        ///////// VERIFICA SE A MATRÍCULA POSSUI PONTO DE ADIANTAMENTO	
        $result_pontofa = $clpontofa->sql_record($clpontofa->sql_query_file($ano,$mes,$matricula)); 
        if($clpontofa->numrows != 0){
	      $temadiantamento = true;
	    }else{
          $temadiantamento = false;
	    }
	    
        ///////// VERIFICA SE A MATRÍCULA POSSUI PONTO DE 13 SALÁRIO
        $result_pontof13 = $clpontof13->sql_record($clpontof13->sql_query_file($ano,$mes,$matricula)); 
        if($clpontof13->numrows != 0){
	      $tem13salario = true;
	    }else{
          $tem13salario = false;
	    }
	    
        ///////// VERIFICA SE A MATRÍCULA POSSUI PONTO COMPLEMENTAR
        $result_pontocom = $clpontocom->sql_record($clpontocom->sql_query_file($ano,$mes,$matricula)); 
        if($clpontocom->numrows != 0){
	      $temcomplementar = true;
	    }else{
          $temcomplementar = false;
	    }

        ///////// VERIFICA SE A MATRÍCULA POSSUI PONTO FIXO 
        $result_pontofx = $clpontofx->sql_record($clpontofx->sql_query_file($ano,$mes,$matricula)); 
        if($clpontofx->numrows != 0){
	      $tempontofixo = true;
	    }else{
          $tempontofixo = false;
	    }
	  }
	  $result_dados = $clcgm->sql_record($clcgm->sql_query_file($z01_numcgm,
	                                                            "z01_numcgm,
                                                                 z01_nome,
                                                                 z01_ender,
                                                                 z01_munic,
                                                                 z01_uf,
                                                                 z01_cgccpf,
                                                                 z01_ident"
	                                                          ));
	  db_fieldsmemory($result_dados,0);
	?>
        <table width="100%" height="90%" border="1" cellspacing="0" cellpadding="0">
          <tr> 
            <td colspan="2"> 
	          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr> 
                  <td width="33%"> 
		            <table width="104%" border="0" cellspacing="0" cellpadding="0">
                      <tr> 
                        <td nowrap title="Clique Aqui para ver os dados cadastrais." class="tabcols">
                          <strong style=\"color:blue\>
                            <?
                            db_ancora("NumCgm:&nbsp;","js_mostracgm();return false;", 1);
                            ?>
                          </strong>
                        </td>
                        <td class="tabcols" nowrap title="Clique Aqui para ver os dados cadastrais."> 
                          <input class="btcols" type="text" name="z01_numcgm" value="<?=@$z01_numcgm?>" size="5" readonly> 
                          &nbsp;&nbsp;&nbsp; 
                          <?
					      parse_str($arg);
					      echo "<strong style=\"color:blue\">";
					      db_ancora("$Lr01_regist","js_mostrapessoal();return false;", 1);
					      echo "</strong>
                          <input style=\"border: 1px solid blue;font-weight: bold;background-color:#80E6FF\" class=\"btcols\" type=\"text\" name=\"Label\" value=\"".@$matric.@$inscr.@$numpre.@$Parcelamento."\" size=\"10\" readonly>\n";
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
                          if(isset($r01_regist))
                            db_input('r01_regist', 8, $Ir01_regist, true, 'hidden', 3);
                          if(isset($z01_numcgm))
                            db_input('z01_numcgm', 8, $Iz01_numcgm, true, 'hidden', 3);
                          if(isset($mstricula))
                            db_input('matricula', 8,  0, true, 'hidden', 3);
           			      ?>
                        </td>
                      </tr>
                    </table>
                  </td>
                  <td width="67%" valign="top"> 
                    <table border="1" cellspacing="0" cellpadding="0">
		              <tr class="links">
		                <td valign="top" style="font-size:11px">
                          <?
                          if(!isset($xopcao) || (isset($xopcao) && trim($xopcao)=="")){
                            $xopcao = '';
                          }
                          if(@$temsalario == true ){
		                    if($xopcao == '')
			                  $xopcao = 'salario';
                            echo "
                            <table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
	                          <tr>
		                        <td valign=\"top\" class=\"links2\" id=\"temsalario\">
			                      <a class=\"links2\" onClick=\"js_MudaLink('temsalario')\" id=\"temsalario\"  href=\"pes3_conspontoregistro021.php?opcao=salario&numcgm=".$z01_numcgm."&matricula=".$matricula."&ano=".$ano."&mes=".$mes."\" target=\"debitos\">SALÁRIO</a>
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
			                        <a class=\"links2\" onClick=\"js_MudaLink('temferias')\" id=\"temferias\"  href=\"pes3_conspontoregistro021.php?opcao=ferias&numcgm=".$z01_numcgm."&matricula=".$matricula."&ano=".$ano."&mes=".$mes."&tbprev=".$r01_tbprev."\" target=\"debitos\">FÉRIAS</a>
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
			                        <a class=\"links2\" onClick=\"js_MudaLink('temrescisao')\" id=\"temrescisao\"  href=\"pes3_conspontoregistro021.php?opcao=rescisao&numcgm=".$z01_numcgm."&matricula=".$matricula."&ano=".$ano."&mes=".$mes."&tbprev=".$r01_tbprev."\" target=\"debitos\">RESCISÃO</a>
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
                                    <a class=\"links2\" onClick=\"js_MudaLink('temadiantamento')\" id=\"temtemadiantamento\"  href=\"pes3_conspontoregistro021.php?opcao=adiantamento&numcgm=".$z01_numcgm."&matricula=".$matricula."&ano=".$ano."&mes=".$mes."&tbprev=".$r01_tbprev."\" target=\"debitos\">ADIANTAMENTO</a>
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
			                        <a class=\"links2\" onClick=\"js_MudaLink('tem13salario')\" id=\"tem13salario\"  href=\"pes3_conspontoregistro021.php?opcao=13salario&numcgm=".$z01_numcgm."&matricula=".$matricula."&ano=".$ano."&mes=".$mes."&tbprev=".$r01_tbprev."\" target=\"debitos\">13o. SALÁRIO</a>
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
			                        <a class=\"links2\" onClick=\"js_MudaLink('temcomplementar')\" id=\"temcomplementar\"  href=\"pes3_conspontoregistro021.php?opcao=complementar&numcgm=".$z01_numcgm."&matricula=".$matricula."&ano=".$ano."&mes=".$mes."&tbprev=".$r01_tbprev."\" target=\"debitos\">COMPLEMENTAR</a>
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
			                        <a class=\"links2\" onClick=\"js_MudaLink('tempontofixo')\" id=\"tempontofixo\"  href=\"pes3_conspontoregistro021.php?opcao=fixo&numcgm=".$z01_numcgm."&matricula=".$matricula."&ano=".$ano."&mes=".$mes."&tbprev=".$r01_tbprev."\" target=\"debitos\">PONTO FIXO</a>
				                  </td>
			                    </tr>
			                  </table>\n";
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
            <td colspan="2" align="center" valign="middle" height="90%">
	          <table border="0" cellspacing="0" cellpadding="0" width="100%" height="90%">
                <tr> 
                  <td align="center"> 
                    <iframe id="debitos" height="90%" width="95%" name="debitos" src="pes3_conspontoregistro021.php?opcao=<?=$xopcao?>&numcgm=<?=$z01_numcgm?>&matricula=<?=$matricula?>&ano=<?=$ano?>&mes=<?=$mes?>&tbprev=<?$r01_tbprev?>"></iframe>
                    <? 
                     $opcao = $xopcao;
                     db_input('opcao', 8, 0, true, 'hidden', 3);
                    ?>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <?
              $novapesquisa = "pes3_conspontoregistro001.php";
              ?>
              <input name="retornar" type="button" id="retornar" value="Nova Pesquisa" title="Inicio da Consulta" onclick="location.href='<?=($novapesquisa)?>'"> 
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <input name="pesquisar" type="submit" id="pesquisar"  title="Atualiza a Consulta" value="Atualizar">
              &nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp; 
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
	}
	?>
      </center>

<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_pesquisaregist(mostra){
     if(mostra==true){
       func_nome.jan.location.href = 'func_pessoalalt.php?funcao_js=parent.js_mostraregist1|r01_regist|z01_nome';
       func_nome.mostraMsg();
       func_nome.show();
       func_nome.focus();
     }else{
       func_nome.jan.location.href = 'func_pessoalalt.php?pesquisa_chave='+document.form1.r01_regist.value+'&funcao_js=parent.js_mostraregist';
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
 func_nome.hide();
}


function js_mostradetalhes(chave){
  func_nome.jan.location.href = chave;
  func_nome.mostraMsg();
  func_nome.show();
  func_nome.focus();
}

// mostra os dados do cgm do contribuinte
function js_mostracgm(){
  func_nome.jan.location.href = 'prot3_conscgm002.php?fechar=func_nome&numcgm=<?=@$z01_numcgm?>';
  func_nome.mostraMsg();
  func_nome.show();
  func_nome.focus();
}


// esta funcao é utilizada quando clicar na matricula após pesquisar
// a mesma
function js_mostrapessoal(){
  func_nome.jan.location.href = 'pes3_conspessoal002.php?regist=<?=@$matric?>';
  func_nome.mostraMsg();
  func_nome.show();
  func_nome.focus();
}
// esta funcao é utilizada quando clicar na inscricao após pesquisar
// a mesma
	

function js_mostradetalhes(chave){
  func_nome.jan.location.href = chave;
  func_nome.mostraMsg();
  func_nome.show();
  func_nome.focus();
}

</script>

<?

$func_nome = new janela('func_nome','');
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