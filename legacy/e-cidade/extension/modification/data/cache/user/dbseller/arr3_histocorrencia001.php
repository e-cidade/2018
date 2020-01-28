<?php
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("dbforms/db_funcoes.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("classes/db_numpref_classe.php");

$clrotulo = new rotulocampo();
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');

$clnumpref = new cl_numpref();
$resnumpref = $clnumpref->sql_record($clnumpref->sql_query_file(db_getsession("DB_anousu"), db_getsession('DB_instit'), "k03_certissvar"));
if ($resnumpref == false || $clnumpref->numrows == 0) {
  throw new \ECidade\V3\Extension\Exceptions\ResponseException("Tabela de parâmetro (numpref) não configurada! Verifique com administrador");
  db_redireciona("corpo.php");
  exit();
} else {
  db_fieldsmemory($resnumpref, 0);
}

// Verifica se Sistema de Agua esta em Uso
db_sel_instit(null, "db21_usasisagua, db21_regracgmiptu, db21_regracgmiss");

if (isset($db21_usasisagua) && $db21_usasisagua != '') {
  $db21_usasisagua = ($db21_usasisagua == 't');
  if ($db21_usasisagua == true) {
    $j18_nomefunc = "func_aguabase.php";
  } else {
    $j18_nomefunc = "func_iptubase.php";
  }
} else {
  $db21_usasisagua = false;
  $j18_nomefunc = "func_iptubase.php";
}

db_destroysession("j01_matric");
db_destroysession("z01_numcgm");
db_destroysession("q02_inscr");
db_destroysession("z01_nome");

$z01_matric = "";
$z01_nome   = "";
$j01_matric = "";
$q02_inscr  = "";
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body class="body-default">
  <div class="container">
    <form name="form1" method="POST" action="arr1_histocorrencia001.php" onsubmit="return validaForm()">
    <fieldset>
      <legend>Ocorrências</legend>

        <table border="0">
          <tr>
            <td align="left" valign="top" bgcolor="#CCCCCC">
        		 <?
                db_ancora($Lz01_nome, 'js_mostranomes(true);', 4);
             ?>
          	</td>
          	<td>
            <?
                db_input("z01_numcgm", 10, $Iz01_numcgm, true, 'text', 4, "onfocus=\"apagaInputs()\" onchange='js_mostranomes(false);'");
                db_input("z01_nome", 40, $Iz01_nome, true, 'text', 3, " readonly = \"readonly\"");
            ?>
        		</td>
          </tr>

         	<tr>
          	<td title="<?=$Tj01_matric?>">
            <?
              db_ancora($Lj01_matric, "js_mostramatricula(true,'$j18_nomefunc');", 2);
            ?>
          	</td>
          	<td>
            <?
              db_input("j01_matric", 10, $Ij01_matric, true, 'text', 1, "onfocus=\"apagaInputs()\"  onchange=\"js_mostramatricula(false,'$j18_nomefunc')\"");
            ?>
          	</td>
          </tr>
          <?
            $cssInscricao = "";
            if ($db21_usasisagua == true) {
              $cssInscricao = "visibility: hidden;";
            }
          ?>
          <tr>
            <td>
            <?
              db_ancora($Lq02_inscr,' js_inscr(true); ',1, "$cssInscricao");
            ?>
            </td>
            <td>
            <?
              db_input('q02_inscr', 10, $Iq02_inscr,true,'text',1," onfocus=\"apagaInputs()\" onchange='js_inscr(false)'", "", "", $cssInscricao);
            ?>
            </td>
          </tr>
        </table>
      </fieldset>
     	<input type="submit" value="Avan&ccedil;ar" id="avancar" name="avancar"/>
   </form>
  </div>
  <?php
    db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
  ?>
</body>
</html>
<script type="text/javascript">

document.form1.avancar.disabled = true;

function apagaInputs(){

  document.form1.j01_matric.value = "";
  document.form1.q02_inscr.value  = "";
  document.form1.z01_numcgm.value = "";
  document.form1.z01_nome.value   = "";
}

function js_mostranomes(mostra){
  document.form1.j01_matric.value = "";
  document.form1.q02_inscr.value  = "";
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_nome.php?funcao_js=parent.js_preenche|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe_nomes','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_preenche1','Pesquisa',false);
  }
}

function js_preenche(chave,chave1){
  document.form1.z01_numcgm.value = chave;
  document.form1.z01_nome.value   = chave1;
  db_iframe_nomes.hide();
  document.form1.avancar.disabled = false;
}

function js_preenche1(chave,chave1){

	document.form1.j01_matric.value = "";
	document.form1.q02_inscr.value  = "";
  document.form1.z01_nome.value   = chave1;
  if(chave==true){
	  document.form1.z01_numcgm.focus();
    document.form1.z01_numcgm.value = "";
    document.form1.z01_nome.value = chave1;
    document.form1.avancar.disabled = true;
  }else {
  	document.form1.avancar.disabled = false;
  }
  if(document.form1.z01_numcgm.value == ''){
	  document.form1.z01_nome.value = '';
	  document.form1.avancar.disabled = true;
  }
}

function js_mostramatricula(mostra, nome_func){
	document.form1.z01_numcgm.value = "";
	document.form1.q02_inscr.value  = "";
	if(mostra==true){
    if(nome_func != "func_iptubase.php") {
  	  js_OpenJanelaIframe('top.corpo','db_iframe_matric',nome_func+'?funcao_js=parent.js_preenchematricula|0|1','Pesquisa',true);
    } else {
    	js_OpenJanelaIframe('top.corpo','db_iframe_matric',nome_func+'?funcao_js=parent.js_preenchematricula3|0|1|2','Pesquisa',true);
    }
  }else {
    js_OpenJanelaIframe('top.corpo','db_iframe_matric',nome_func+'?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_preenchematricula2','Pesquisa',false);
  }
}
function js_preenchematricula3(chave,chave1,chave2){

    document.form1.j01_matric.value = chave;
    document.form1.z01_nome.value   = chave2;
    db_iframe_matric.hide();
    document.form1.avancar.disabled = false;
}
function js_preenchematricula(chave,chave1){

 	document.form1.j01_matric.value = chave;
  document.form1.z01_nome.value   = chave1;
  db_iframe_matric.hide();
  document.form1.avancar.disabled = false;
}

function js_preenchematricula2(chave,chave1){

	if(chave1 == false) {
		document.form1.z01_nome.value = chave;
    db_iframe_matric.hide();
    document.form1.avancar.disabled = false;
	}else {
		document.form1.j01_matric.value = "";
		document.form1.z01_nome.value   = chave;
    db_iframe_matric.hide();
    document.form1.avancar.disabled = true;
	}
	if(document.form1.j01_matric.value == ''){
		document.form1.z01_nome.value   = '';
		document.form1.avancar.disabled = true;
	}
}

function js_inscr(mostra){

	document.form1.j01_matric.value = "";
	document.form1.z01_numcgm.value = "";
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_issbase.php?funcao_js=parent.js_mostra|q02_inscr|z01_nome|q02_dtbaix','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
  }
}
function js_mostra(chave1,chave2,baixa){

	if (baixa != ""){

		document.form1.q02_inscr.value = "";
    document.form1.z01_nome.value  = "";
    db_iframe.hide();
    alert("Inscrição já  Baixada");
  }else{

	  if(chave2 != false) {

      document.form1.q02_inscr.value = chave1;
      document.form1.z01_nome.value  = chave2;
      db_iframe.hide();
      document.form1.avancar.disabled = false;
    }else {

      document.form1.z01_nome.value  = chave1;
      db_iframe.hide();
      document.form1.avancar.disabled = false;
    }
  }

  if(document.form1.q02_inscr.value == '') {

		document.form1.z01_nome.value   = '';
		document.form1.avancar.disabled = true;
	}
}

function validaForm() {
	var matricula = document.form1.j01_matric;
	var numerocgm = document.form1.z01_numcgm;
	var inscricao = document.form1.q02_inscr;
	var nome      = document.form1.z01_nome;
	if((matricula.value == "") &&
		 (numerocgm.value == "") &&
		 (inscricao.value == "") ||
		 (nome.value      == "") &&
		 (nome.value      == 'CHAVE('+matricula.value+') NÃO ENCONTRADO')||
		 (nome.value      == 'CHAVE('+numerocgm.value+') NÃO ENCONTRADO')||
		 (nome.value      == 'CHAVE('+inscricao.value+') NÃO ENCONTRADO')||
		 (nome.value      == 'CÓDIGO ('+matricula.value+') NÃO ENCONTRADO')||
		 (nome.value      == 'CÓDIGO ('+numerocgm.value+') NÃO ENCONTRADO')||
		 (nome.value      == 'CÓDIGO ('+inscricao.value+') NÃO ENCONTRADO')) {
		 alert('Valor de pesquisa invalido.');
		 document.form1.avancar.disabled = true;
		 return false;
	}
	var conta = 0;
	if(matricula.value != "") conta++;
	if(numerocgm.value != "") conta++;
	if(inscricao.value != "") conta++;

	if(conta > 1) {
		return false;
	}
}
</script>