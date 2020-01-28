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
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_histocorrencia_classe.php");
require_once("classes/db_histocorrenciacgm_classe.php");
require_once("classes/db_histocorrenciamatric_classe.php");
require_once("classes/db_histocorrenciainscr_classe.php");
require_once("classes/db_numpref_classe.php");

db_postmemory($HTTP_POST_VARS);
db_postmemory($_GET);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clnumpref  = new cl_numpref();
$resnumpref = $clnumpref->sql_record( $clnumpref->sql_query_file( db_getsession("DB_anousu"), db_getsession('DB_instit'), "k03_certissvar") );
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
  $j18_nomefunc = "func_iptubase.php";
  if ($db21_usasisagua == true) {
    $j18_nomefunc = "func_aguabase.php";
  }
} else {

  $db21_usasisagua = false;
  $j18_nomefunc = "func_iptubase.php";
}

$clhistocorrencia = new cl_histocorrencia;
$clhistocorrencia->rotulo->label("ar23_sequencial");
$clhistocorrencia->rotulo->label("ar23_descricao");

$clrotulo = new rotulocampo();
$clrotulo->label('z01_numcgm');
$clrotulo->label('z01_nome');
$clrotulo->label('j01_matric');
$clrotulo->label('q02_inscr');

if (isset($chave_ar24_numcgm)) {

  $z01_numcgm = $chave_ar24_numcgm;
  $z01_nome   = $chave_ar24_nome;
}

if(isset($chave_ar25_matric)) {
  $j01_matric = $chave_ar25_matric;
}

if(isset($chave_ar26_inscr)) {
  $q02_inscr = $chave_ar26_inscr;
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0" align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr>
    <td height="63" align="center" valign="top">
    <table border="0" align="center" cellspacing="0">
      <form name="form2" method="post" action="" onsubmit="return validaForm()">
      <tr>
        <td align="left" valign="top" bgcolor="#CCCCCC">
        <?
          db_ancora($Lz01_nome, 'js_mostranomes(true);', 4);
        ?>
        </td>
        <td>
        <?
          db_input("z01_numcgm", 10, $Iz01_numcgm, true, 'text', 4, " onchange='js_mostranomes(false);'");
        ?>
        <?
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
          db_input("j01_matric", 10, $Ij01_matric, true, 'text', 1, "onchange=\"js_mostramatricula(false,'$j18_nomefunc')\"");
        ?>
        </td>
      </tr>
      <?php
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
          db_input('q02_inscr', 10, $Iq02_inscr,true,'text',1,"onchange='js_inscr(false)'", "", "", $cssInscricao);
        ?>
        </td>
      </tr>

      <tr>
        <td colspan="2" align="center">
        <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar" />
        <input name="limpar"    type="reset"  id="limpar"     value="Limpar"    />
        <input name="Fechar"    type="button" id="fechar"     value="Fechar" onClick="parent.db_iframe_histocorrencia.hide();" />
        </td>
      </tr>
      </form>
    </table>
    </td>
  </tr>
  <tr>
    <td align="center" valign="top">
    <?php

    if(!isset($pesquisa_chave)){

      if(isset($campos) == false){

        if(file_exists("funcoes/db_func_histocorrencia.php")==true){
          include("funcoes/db_func_histocorrencia.php");
        }else{
          $campos = "histocorrencia.*";
        }
      }

      $campos  = "histocorrencia.ar23_sequencial,                                                       ";
      $campos .= "case when histocorrencia.ar23_tipo = '1' then 'Manual' else 'Automatica' end as Tipo, ";
      $campos .= "histocorrencia.ar23_descricao,                                                        ";
      $campos .= "histocorrencia.ar23_ocorrencia,                                                       ";
      $campos .= "histocorrencia.ar23_data,                                                             ";
      $campos .= "histocorrencia.ar23_hora,                                                             ";
      $campos .= "db_usuarios.login,                                                                    ";
      $campos .= "db_modulos.nome_modulo                                                                ";

      $repassa = array();
      if(isset($z01_numcgm) && (trim($z01_numcgm) != '')) {

        $campos    = "histocorrenciacgm.ar24_numcgm, ".$campos;
        $clhistocorrenciacgm = new cl_histocorrenciacgm;
        $sql       = $clhistocorrenciacgm->sql_query("", "$campos", "histocorrencia.ar23_data", "histocorrenciacgm.ar24_numcgm = $z01_numcgm and ar23_instit =" . db_getsession("DB_instit"));
        $repassa   = array("chave_ar24_numcgm"=>$z01_numcgm,"chave_ar24_nome"=>$z01_nome);
        $funcao_js = 'parent.js_preenchepesquisaCGM|ar23_sequencial|ar24_numcgm';

      }elseif(isset($j01_matric) && (trim($j01_matric) != '')) {

        $campos    = "histocorrenciamatric.ar25_matric, ".$campos;
        $clhistocorrenciamatric = new cl_histocorrenciamatric;
        $sql       = $clhistocorrenciamatric->sql_query("", "$campos", "histocorrencia.ar23_data", "histocorrenciamatric.ar25_matric = $j01_matric and ar23_instit = ". db_getsession("DB_instit"));
        $repassa   = array("chave_ar25_matric"=>$j01_matric);
        $funcao_js = 'parent.js_preenchepesquisaMatric|ar23_sequencial|ar25_matric';

      }elseif(isset($q02_inscr) && (trim($q02_inscr) != '')) {

        $campos    = "histocorrenciainscr.ar26_inscr, ".$campos;
        $clhistocorrenciainscr = new cl_histocorrenciainscr;
        $sql       = $clhistocorrenciainscr->sql_query("", "$campos", "histocorrencia.ar23_data", "histocorrenciainscr.ar26_inscr = $q02_inscr and ar23_instit =". db_getsession("DB_instit"));
        $repassa   = array("chave_ar26_inscr"=>$q02_inscr);
        $funcao_js = 'parent.js_preenchepesquisaInscr|ar23_sequencial|ar26_inscr';
      }

      if(isset($z01_numcgm) || isset($j01_matric) || isset($q02_inscr)){
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }

    }else{

      if($pesquisa_chave!=null && $pesquisa_chave!=""){

        $result = $clhistocorrencia->sql_record($clhistocorrencia->sql_query($pesquisa_chave));
        if($clhistocorrencia->numrows != 0){

          db_fieldsmemory($result,0);
          echo "<script>".$funcao_js."('$ar23_descricao',false);</script>";
        }else{
          echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
        }
      }else{
        echo "<script>".$funcao_js."('',false);</script>";
      }
    }
    ?>
    </td>
  </tr>
</table>
</body>
</html>
<script type="text/javascript">

function js_mostranomes(mostra){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_nomes','func_nome.php?funcao_js=parent.js_preenche|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_nomes','func_nome.php?pesquisa_chave='+document.form2.z01_numcgm.value+'&funcao_js=parent.js_preenche1','Pesquisa',false);
  }
}

function js_preenche(chave,chave1){

    document.form2.j01_matric.value = "";
    document.form2.q02_inscr.value  = "";
    document.form2.z01_numcgm.value = chave;
    document.form2.z01_nome.value   = chave1;
    db_iframe_nomes.hide();
}

function js_preenche1(chave,chave1){

  document.form2.j01_matric.value = "";
  document.form2.q02_inscr.value  = "";
  document.form2.z01_nome.value = chave1;
  if(chave==true){
    document.form2.z01_numcgm.value = "";
    document.form2.z01_numcgm.focus();
  }
}

function js_mostramatricula(mostra, nome_func){

  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_matric',nome_func+'?funcao_js=parent.js_preenchematricula|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_matric',nome_func+'?pesquisa_chave='+document.form2.j01_matric.value+'&funcao_js=parent.js_preenchematricula2','Pesquisa',false);
  }
}
function js_preenchematricula(chave,chave1){

  document.form2.z01_numcgm.value = "";
  document.form2.q02_inscr.value  = "";
  document.form2.j01_matric.value = chave;
  document.form2.z01_nome.value   = chave1;
  db_iframe_matric.hide();
}

function js_preenchematricula2(chave,chave1){

  if(chave1 == false) {
    document.form2.z01_numcgm.value = "";
    document.form2.q02_inscr.value  = "";
    document.form2.z01_nome.value = chave;
    db_iframe_matric.hide();
  }else {
    document.form2.j01_matric.value = "";
    document.form2.z01_numcgm.value = "";
    document.form2.q02_inscr.value  = "";
    document.form2.z01_nome.value   = chave;
    db_iframe_matric.hide();
  }
}

function js_inscr(mostra){

  if(mostra==true){
      js_OpenJanelaIframe('','db_iframe','func_issbase.php?funcao_js=parent.js_mostra|q02_inscr|z01_nome|q02_dtbaix','Pesquisa',true);
    }else{
      js_OpenJanelaIframe('','db_iframe','func_issbase.php?pesquisa_chave='+document.form2.q02_inscr.value+'&funcao_js=parent.js_mostra','Pesquisa',false);
    }
  }
function js_mostra(chave1,chave2,baixa){

  if (baixa!=""){

    db_iframe.hide();
    alert("Inscrição já  Baixada");
  }else{

    if(chave2 != false) {

      document.form2.j01_matric.value = "";
      document.form2.z01_numcgm.value = "";
      document.form2.q02_inscr.value = chave1;
      document.form2.z01_nome.value  = chave2;
      db_iframe.hide();
    }else {

      document.form2.j01_matric.value = "";
      document.form2.z01_numcgm.value = "";
      document.form2.z01_nome.value  = chave1;
      db_iframe.hide();
    }
  }
}

function validaForm() {

  var matricula = document.form2.j01_matric;
  var numerocgm = document.form2.z01_numcgm;
  var inscricao = document.form2.q02_inscr;
  var nome      = document.form2.z01_nome;

  if((matricula.value == "") &&
     (numerocgm.value == "") &&
     (inscricao.value == "") ||
     (nome.value      == "") &&
     (nome.value      == 'CHAVE('+matricula.value+') NÃO ENCONTRADO') ||
     (nome.value      == 'CHAVE('+numerocgm.value+') NÃO ENCONTRADO') ||
     (nome.value      == 'CHAVE('+inscricao.value+') NÃO ENCONTRADO')) {

     alert('Valor de pesquisa invalido.');
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