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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_issbase_classe.php");
require_once("classes/db_iptubase_classe.php");
require_once("classes/db_cgm_classe.php");
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
$clcgm->rotulo->label("z01_nome");
$clcgm->rotulo->label("v07_parcel");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<!-- <style type="text/css">
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
</style> -->
</head>
<body bgcolor=#CCCCCC >


<script>
function js_testacamp(){
  var matri = document.form1.k00_matric.value;
  var inscr = document.form1.k00_inscr.value;
  var numcgm = document.form1.z_numcgm.value;
  if(matri=="" && inscr=="" && numcgm==""){
    alert("Informe um campo para pesquisa!");   
    return false;  
  }
  
  return true;  
}   
</script>
  
  <form class="container" name="form1" id="form1" method="post" action="dvr3_diversos005.php?pri=true"  onSubmit="return js_verifica_campos_digitados();" >
   <fieldset>
   <legend>Consulta Parcelamentos</legend>
   <table class="form-container" >
     <tr>   
      <td>
      <?
       db_ancora($Lz01_nome,' js_cgm(true); ',1);
      ?>
       </td>
       <td> 
      <?
       db_input('z01_numcgm',5,$Iz01_numcgm,true,'text',1,"class='pesquisa' onchange='js_cgm(false);jsLimpa(this.value, this.id);'");
       db_input('z01_nome',30,0,true,'text',3,"class='label'","z01_nomecgm");
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
       db_input('k00_matric',5,$Ij01_matric,true,'text',1,"class='pesquisa' onchange='js_matri(false);jsLimpa(this.value, this.id);'");
       db_input('z01_nome',30,0, true,'text',3,"class='label'","z01_nomematri");
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
       db_input('k00_inscr',5,$Iq02_inscr,true,'text',1,"class='pesquisa' onchange='js_inscr(false);jsLimpa(this.value, this.id);'");
       db_input('z01_nome',30,0          ,true,'text',3,"class='label'","z01_nomeinscr");
      ?>
       </td>
     </tr>
     <tr>   
       <td>
      <b>Parcelamento:</b>
       </td>
       <td> 
      <?
        db_input('v07_parcel',5,0,true,'text',1,"class='pesquisa' onchange='jsLimpa(this.value, this.id);' ","v07_parcel");
      ?>
       </td>
     </tr>

    </table>   
    </fieldset>
    <center>
      <input name="pesquisar" id="pesquisar"
             value="Pesquisar" onclick="return js_pesquisaParcelamento();"  type="button">
    </center>
  </form>
<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_matri(mostra){
  var matri=document.form1.k00_matric.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe3','func_iptubase.php?funcao_js=parent.js_mostramatri|0|2','Pesquisa',true);
  }else{
    if (matri != "") {
	    js_OpenJanelaIframe('top.corpo','db_iframe3','func_iptubase.php?pesquisa_chave='+matri+
	                                                                  '&funcao_js=parent.js_mostramatri1','Pesquisa',false);
    }                                                                 
  }
}
function js_mostramatri(chave1,chave2){
  document.form1.k00_matric.value = chave1;
  document.form1.z01_nomematri.value = chave2;
  $('z01_numcgm').value   = '';
  $('k00_inscr').value  = '';
  $('v07_parcel').value = '';  
  db_iframe3.hide();
}
function js_mostramatri1(chave,erro){
  document.form1.z01_nomematri.value = chave; 
  if(erro==true){ 
    document.form1.k00_matric.focus(); 
    document.form1.k00_matric.value = ''; 
  }
}


function js_inscr(mostra){
  var inscr=document.form1.k00_inscr.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_issbase.php?pesquisa_chave='+inscr+
                                                                  '&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
  }
}
function js_mostrainscr(chave1,chave2){
  document.form1.k00_inscr.value = chave1;
  document.form1.z01_nomeinscr.value = chave2;
  $('z01_numcgm').value   = '';
  $('k00_matric').value = '';
  $('v07_parcel').value = '';  
  
  db_iframe.hide();
}
function js_mostrainscr1(chave,erro){
  document.form1.z01_nomeinscr.value = chave; 
  if(erro==true){ 
    document.form1.k00_inscr.focus(); 
    document.form1.k00_inscr.value = ''; 
  }
}


function js_cgm(mostra){
  var cgm=document.form1.z01_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe2','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe2','func_nome.php?pesquisa_chave=' + cgm 
                                                                  + '&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}
function js_mostracgm(chave1,chave2){
  $('z01_numcgm')   .value = chave1;
  $('z01_nomecgm').value = chave2;
  $('k00_matric').value = '';
  $('k00_inscr').value  = '';
  $('v07_parcel').value = '';  
  db_iframe2.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nomecgm.value = chave; 
  if(erro==true){ 
    document.form1.z01_numcgm.focus(); 
    document.form1.z01_numcgm.value = ''; 
  }
}
</script>
<?
if(isset($dado) && $dado=="inscr"){
  db_msgbox("Inscrição inválida.");
}  
if(isset($dado) && $dado=="matric"){
  db_msgbox("Matrícula inválida.");
}  
if(isset($dado) && $dado=="numcgm"){
  db_msgbox("NUMCGM inválido.");
}  

?>

<script>

function js_mostraParcelamento(parcelamento){
  js_OpenJanelaIframe('top.corpo','db_iframe_consultaparc'+parcelamento,'div3_consultaParcelamento.php?parcelamento='+
                                                                             parcelamento,'Consulta Pacelamentos',true);
}

function jsParcelamentos(sFiltro, sValor) {
  js_OpenJanelaIframe('top.corpo','db_iframe_consultaparc'+sFiltro,'div3_consultaParcelamentoFiltro.php?sFiltro='+
                                                                sFiltro+'&sValor='+sValor,'Consulta Pacelamentos',true);
}

function jsLimpa(iValorCampo, iIdCampo) {

  var aText = $('form1').getInputs('text');   
  aText.each(function (oText, id) {  
    $(oText).value = '';
  });  
  $(iIdCampo).value = iValorCampo
}

function js_pesquisaParcelamento(){

  var iCgm          = $F('z01_numcgm');
  var iMatricula    = $F('k00_matric');
  var iInscricao    = $F('k00_inscr');
  var iParcelamento = $F('v07_parcel');

  if(iCgm != "") {
  
    jsParcelamentos('z01_numcgm', iCgm);    // por CGM
  } else if(iMatricula != "") {
  
    jsParcelamentos('k00_matric', iMatricula); // Por Matricula
  } else if(iInscricao != "") {
  
    jsParcelamentos('k00_inscr', iInscricao); // Por Inscrição
  } else if(iParcelamento != "") {
  
    js_mostraParcelamento(iParcelamento);    // Por Parcelamentos
  } else {
  
    alert("ESCOLHA UM CAMPO PARA PESQUISA");
    return false;
  }
}
</script>

<script>

$("z01_numcgm").addClassName("field-size2");
$("z01_nomecgm").addClassName("field-size7");
$("k00_matric").addClassName("field-size2");
$("z01_nomematri").addClassName("field-size7");

//$("z01_nome").addClassName("field-size5");
$("k00_inscr").addClassName("field-size2");
$("z01_nomeinscr").addClassName("field-size7");
$("v07_parcel").addClassName("field-size9");



</script>