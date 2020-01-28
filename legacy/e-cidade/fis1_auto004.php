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
 *  Foundation, Inc., 59 Temple Place, Suite 350, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

require_once("classes/db_issbase_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_cgm_classe.php");
require_once("classes/db_sanitario_classe.php");
require_once("classes/db_fiscal_classe.php");

db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);

$db_botao = 1;
$db_opcao = 1;
$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label();
$clissbase  = new cl_issbase;
$clissbase->rotulo->label("q02_inscr");
$clcgm = new cl_cgm;
$clcgm->rotulo->label("z01_numcgm");
$clsanitario = new cl_sanitario;
$clsanitario->rotulo->label("y80_codsani");
$clfiscal = new cl_fiscal;
$clfiscal->rotulo->label("y30_codnoti");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="container">
<form name="form1" method="post" action="fis1_auto001.php?pri=true&abas=1"  onSubmit="return js_verifica_campos_digitados();">
  <fieldset>
   <legend>Auto de Infração</legend>
   <table border="0">
     <tr>
      <td>
      <?
       db_ancora($Lz01_numcgm,' js_cgm(true); ',1);
      ?>
       </td>
       <td>
      <?
       db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'");
       db_input('z01_nome',50,0,true,'text',3,"","z01_nomecgm");
      ?>
       </td>
     </tr>
     <tr>
       <td>
      <?
       db_ancora($Lj01_matric,' js_matri(true); ',1);
      ?>
       </td>
       <td>
      <?
       db_input('j01_matric',5,$Ij01_matric,true,'text',1,"onchange='js_matri(false)'");
      db_input('z01_nome',50,0,true,'text',3,"","z01_nomematri");
      ?>
       </td>
     </tr>

     <tr>
       <td>
      <?
       db_ancora($Lq02_inscr,' js_inscr(true); ',1);
      ?>
       </td>
       <td>
      <?
       db_input('q02_inscr',5,$Iq02_inscr,true,'text',1,"onchange='js_inscr(false)'");
      db_input('z01_nome',50,0,true,'text',3,"","z01_nomeinscr");
      ?>
       </td>
     </tr>
    <tr>
      <td nowrap title="<?=@$Ty80_codsani?>">
         <?
         db_ancora(@$Ly80_codsani,"js_sanitario(true);",1);
         ?>
      </td>
      <td>
        <?
        db_input('y80_codsani',5,$Iy80_codsani,true,'text',1,"onchange='js_sanitario(false)'");
        db_input('z01_nome',50,0,true,'text',3,"","z01_nomesani");
        ?>
      </td>
    </tr>
    <tr>
      <td nowrap title="<?=@$Ty30_codnoti?>">
         <?
         db_ancora(@$Ly30_codnoti,"js_noti(true);",1);
         ?>
      </td>
      <td>
        <?
        db_input('y30_codnoti',5,$Iy30_codnoti,true,'text',$db_opcao,"onchange='js_noti(false)'");
        db_input('z01_nome',50,0,true,'text',3,"","z01_nomenoti");
        ?>
      </td>
    </tr>
    </table>

   </fieldset>

   <input type="hidden" name="rnum"    value="" />
   <input type="hidden" name="rorigem" value="" />
   <input type="button" name="fiscal"  value="Incluir" onclick="return js_testacamp();" />

  </form>
  </div>
</body>
</html>
<script type="text/javascript">

function js_testacamp(){

  var matri  = $F("j01_matric");
  var inscr  = $F("q02_inscr");
  var numcgm = $F("z01_numcgm");
  var sani   = $F("y80_codsani");
  var noti   = $F("y30_codnoti");

  if(matri=="" && inscr=="" && numcgm=="" && sani=="" && noti==""){
    alert("Informe um campo para prosseguir!");
    return false;
  }
  if(matri!=""){

    document.form1.rnum.value    = matri;
    document.form1.rorigem.value = 'matr';
  }else if(inscr!=""){

    document.form1.rnum.value    = inscr;
    document.form1.rorigem.value = 'inscr';
  }else if(numcgm!=""){

    document.form1.rnum.value    = numcgm;
    document.form1.rorigem.value = 'cgm';
  }else if(sani!=""){

    document.form1.rnum.value    = sani;
    document.form1.rorigem.value = 'sani';

  }else if(noti!=""){

    document.form1.rnum.value    = noti;
    document.form1.rorigem.value = 'noti';
  }
  document.form1.submit();
}

function js_sanitario(mostra){
  var sani=document.form1.y80_codsani.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_sanitario','func_sanitario.php?funcao_js=parent.js_preenchesanitario|y80_codsani|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_sanitario','func_sanitario.php?pesquisa_chave='+sani+'&funcao_js=parent.js_preenchesanitario1','Pesquisa',false);
  }
}
function js_preenchesanitario(chave,chave1){
  document.form1.y80_codsani.value = chave;
  document.form1.z01_nomesani.value = chave1;
  db_iframe_sanitario.hide();
}
function js_preenchesanitario1(chave,chave1,erro){
  document.form1.z01_nomesani.value = chave1;
  if(erro==true){
    document.form1.y80_codsani.focus();
    document.form1.y80_codsani.value = '';
  }
}
function js_matri(mostra){
  var matri=document.form1.j01_matric.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe3','func_iptubase.php?funcao_js=parent.js_mostramatri|j01_matric|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe3','func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostramatri1','Pesquisa',false);
  }
}
function js_mostramatri(chave1,chave2){
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nomematri.value = chave2;
  db_iframe3.hide();
}
function js_mostramatri1(chave,erro){
  document.form1.z01_nomematri.value = chave;
  if(erro==true){
    document.form1.j01_matric.focus();
    document.form1.j01_matric.value = '';
  }
}

function js_inscr(mostra){
  var inscr=document.form1.q02_inscr.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe_issbase','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe_issbase','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.q02_inscr.value = chave1;
  document.form1.z01_nomeinscr.value = chave2;
  db_iframe_issbase.hide();
}
function js_mostrainscr1(chave,erro){
  document.form1.z01_nomeinscr.value = chave;
  if(erro==true){
    document.form1.q02_inscr.focus();
    document.form1.q02_inscr.value = '';
  }
}

function js_cgm(mostra){
  var cgm=document.form1.z01_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('','func_nome','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','func_nome','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  func_nome.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nomecgm.value = chave;
  if(erro==true){
    document.form1.z01_numcgm.focus();
    document.form1.z01_numcgm.value = '';
  }
}
function js_noti(mostra){
  var noti=document.form1.y30_codnoti.value;
  if(mostra==true){
    js_OpenJanelaIframe('','db_iframe','func_fiscal.php?funcao_js=parent.js_mostrafiscal|y30_codnoti|y30_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe','func_fiscal.php?pesquisa_chave='+noti+'&funcao_js=parent.js_mostrafiscal1','Pesquisa',false);
  }
}
function js_mostrafiscal(chave1,chave2){
  document.form1.y30_codnoti.value = chave1;
  document.form1.z01_nomenoti.value = chave2;
  db_iframe3.hide();
}
function js_mostrafiscal1(chave,erro){
  document.form1.z01_nomenoti.value = chave;
  if(erro==true){
    document.form1.y30_codnoti.focus();
    document.form1.y30_codnoti.value = '';
  }
}

$("z01_numcgm").focus();
</script>