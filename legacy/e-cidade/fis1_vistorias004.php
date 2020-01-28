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

include("classes/db_issbase_classe.php");
include("classes/db_iptubase_classe.php");
include("classes/db_cgm_classe.php");
include("classes/db_sanitario_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$db_botao=1;
$db_opcao=1;
$cliptubase = new cl_iptubase;
$cliptubase->rotulo->label();
$clissbase = new cl_issbase;
$clissbase->rotulo->label("q02_inscr");
$clcgm = new cl_cgm;
$clcgm->rotulo->label("z01_numcgm");
$clsanitario = new cl_sanitario;
$clsanitario->rotulo->label("y80_codsani");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
td {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
}
input {
        font-family: Arial, Helvetica, sans-serif;
        font-size: 12px;
        height: 17px;
        border: 1px solid #999999;
}
-->
</style>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<center>
<table align="center" valign="top" cellspacing="0" cellpadding="0" bgcolor="#cccccc" style="margin-top:15px;">
  <tr>
  <td align="center" valign="top" bgcolor="#cccccc">

  <form name="form1" method="post" action="fis1_vistorias001.php?pri=true&abas=1"  onSubmit="return js_verifica_campos_digitados();" >
  <center>

  <fieldset>
  <legend><b>&nbsp;Vistorias&nbsp;</b></legend>

   <table align="center" border="0" cellspacing="0" cellpadding="0">
   <?
   $sim = 2;
   if(isset($cgm)){
     $sim = 1;
   ?>

      <td  >
      <?
       db_ancora($Lz01_numcgm,' js_cgm(true); ',1);
      ?>
       </td>
       <td>
      <?
       db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',1,"onchange='js_cgm(false)'");
       db_input('z01_nome',30,0,true,'text',3,"","z01_nomecgm");
      ?>
       </td>
     </tr>
<script>
onLoad=document.form1.z01_numcgm.focus();
function js_testacamp(){
  var numcgm = document.form1.z01_numcgm.value;
  if(numcgm==""){
    alert("Informe um campo para prosseguir!");
    return false;
  }
  document.form1.action += "&cgm=1";
  document.form1.submit();
}
</script>
   <?
   }elseif(isset($matric)){
     $sim = 1;
   ?>
     <tr align="center" >
       <td>
      <?
       db_ancora($Lj01_matric,' js_matri(true); ',1);
      ?>
       </td>
       <td>
      <?
       db_input('j01_matric',5,$Ij01_matric,true,'text',1,"onchange='js_matri(false)'");
      db_input('z01_nome',30,0,true,'text',3,"","z01_nomematri");
      ?>
       </td>
     </tr>
<script>
onLoad=document.form1.j01_matric.focus();
function js_testacamp(){
  var matri = document.form1.j01_matric.value;
  if(matri==""){
    alert("Informe um campo para prosseguir!");
    return false;
  }
  document.form1.action += "&matric=1";
  document.form1.submit();
}
</script>
   <?
   }elseif(isset($inscr)){
     $sim = 1;
   ?>

     <tr align="center" >
       <td>
      <?
       db_ancora($Lq02_inscr,' js_inscr(true); ',1);
      ?>
       </td>
       <td>
      <?
       db_input('q02_inscr',5,$Iq02_inscr,true,'text',1,"onchange='js_inscr(false)'");
       db_input('z01_nome',30,0,true,'text',3,"","z01_nomeinscr");
       echo "<script>parent.document.formaba.calculo.disabled = true</script>";
      ?>
       </td>
     </tr>
<script>
onLoad=document.form1.q02_inscr.focus();
function js_testacamp(){
  var inscr = document.form1.q02_inscr.value;
  if(inscr==""){
    alert("Informe um campo para prosseguir!");
    return false;
  }
  document.form1.action += "&inscr=1";
  document.form1.submit();
}
</script>
   <?
   }elseif(isset($sani)){
     $sim = 1;
   ?>
   <tr align="center" >
      <td nowrap title="<?=@$Ty80_codsani?>">
         <?
         db_ancora(@$Ly80_codsani,"js_sanitario(true);",1);
         ?>
      </td>
      <td>
        <?
        db_input('y80_codsani',5,$Iy80_codsani,true,'text',1,"onchange='js_sanitario(false)'");
        db_input('z01_nome',30,0,true,'text',3,"","z01_nomesani");
        echo "<script>parent.document.formaba.calculo.disabled = true</script>";
        ?>
      </td>
    </tr>
<script>
onLoad=document.form1.y80_codsani.focus();
function js_testacamp(){
  var sani = document.form1.y80_codsani.value;
  if(sani==""){
    alert("Informe um campo para prosseguir!");
    return false;
  }
  document.form1.action += "&sani=1";
  document.form1.submit();
}
</script>
    <?
    }
    ?>

    </table>
    </fieldset>

    <table align=center>
    <tr align="center" >
       <td colspan="2" align="center">
     <br>
   <input type="button" name="vistoria" <?=($sim == "1"?"":"disabled")?>  value="Incluir Vistoria" onclick="return js_testacamp();" onblur='js_setatabulacao();'>
       </td>
     </tr>
    </table>
    </center>

  </form>

  </td>
  </tr>
</table>
</center>
</body>
</html>
<script>
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
    js_OpenJanelaIframe('','db_iframe3','func_iptubase.php?funcao_js=parent.js_mostramatri|0|1','Pesquisa',true);
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
    js_OpenJanelaIframe('','db_iframe','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.q02_inscr.value = chave1;
  document.form1.z01_nomeinscr.value = chave2;
  db_iframe.hide();
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
    js_OpenJanelaIframe('','db_iframe2','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('','db_iframe2','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  db_iframe2.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nomecgm.value = chave;
  if(erro==true){
    document.form1.z01_numcgm.focus();
    document.form1.z01_numcgm.value = '';
  }
}

function js_setatabulacao(){
  <?
  if(isset($cgm)){
    echo 'js_tabulacaoforms("form1","z01_numcgm",true,1,"z01_numcgm",true);';
  }elseif(isset($matric)){
    echo 'js_tabulacaoforms("form1","j01_matric",true,1,"j01_matric",true);';
  }elseif(isset($inscr)){
    echo 'js_tabulacaoforms("form1","q02_inscr",true,1,"q02_inscr",true);';
  }elseif(isset($sani)){
    echo 'js_tabulacaoforms("form1","y80_codsani",true,1,"y80_codsani",true);';
  }
  ?>
}
js_setatabulacao();
</script>