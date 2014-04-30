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
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrotulo = new rotulocampo;
$clrotulo->label('rh01_regist');
$clrotulo->label('z01_nome');
$clrotulo->label('i01_codigo');
$clrotulo->label('i01_descr');
$clrotulo->label('r08_codigo');
$clrotulo->label('r08_descr');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="25%" height="18">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
    <td width="25%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
      <center>
      <form name="form1" method="post" action="">
        <table border="0">
          <tr>
            <td nowrap align="right" title="<?=@$Trh01_regist?>">
              <?
              db_ancora($Lrh01_regist,"js_pesquisarh01_regist(true);",1);
              ?>
            </td>
            <td colspan="3" nowrap>
              <?
              db_input('rh01_regist',6,$Irh01_regist,true,'text',1,"onChange='js_pesquisarh01_regist(false);'");
              db_input('z01_nome',50,$Irh01_regist,true,'text',3,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="<?=@$Ti01_codigo?>">
              <?
              db_ancora($Li01_codigo,"js_pesquisai01_codigo(true);",1);
              ?>
            </td>
            <td colspan="3" nowrap>
              <?
              db_input('i01_codigo',6,$Ii01_codigo,true,'text',1,"onChange='js_pesquisai01_codigo(false);'");
              db_input('i01_descr',50,$Ii01_descr,true,'text',3,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Período de competência">
              <b>Período:</b>
            </td>
            <td colspan="3" nowrap>
              <?
              if(!isset($anof)){
                $anof = db_anofolha();
              }
              if(!isset($mesf)){
                $mesf = db_mesfolha();
              }
              if(!isset($anoi)){
                $anoi = $anof - 10;
              }
              if(!isset($mesi)){
                $mesi = $mesf;
              }
              db_input('anoi',4,1,true,'text',1,"");
              ?>
              <b>/</b>
              <?
              db_input('mesi',2,1,true,'text',1,"");
              ?>
              <b>&nbsp;a&nbsp;</b>
              <?
              db_input('anof',4,1,true,'text',1,"");
              ?>
              <b>/</b>
              <?
              db_input('mesf',2,1,true,'text',1,"");
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Data para correção">
              <b>Data para Correção</b>
            </td>
            <td nowrap>
              <?
              if(!isset($datacor)){
                $datacor_dia = date("d",db_getsession("DB_datausu"));
                $datacor_mes = date("m",db_getsession("DB_datausu"));
                $datacor_ano = date("Y",db_getsession("DB_datausu"));
              }
              ?>
              <?
              db_inputdata("datacor", @$datacor_dia, @$datacor_mes, @$datacor_ano, true, 'text', 1);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Índice de correção">
              <b>Índice de Correção</b>
            </td>
            <td nowrap>
              <?
              if(!isset($indcor)){
                $indcor = "80" ;
              }
              db_input('indcor',6,1,true,'text',1);
              ?>
              
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Número da Portaria Ministerial">
              <b>Portaria Ministerial:</b>
            </td>
            <td nowrap>
              <?
              db_input('nrport',6,1,true,'text',1);
              ?>
              
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Data da Portarial Ministerial">
              <b>Data da Portaria Ministerial:</b>
            </td>
            <td nowrap>
              <?
              db_inputdata("datanrport", @$datanrport_dia, @$datanrport_mes, @$datanrport_ano, true, 'text', 1);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="Descartar menores valores corrigidos ou menores valores históriocos">
              <b>Descartar por:</b>
            </td>
            <td colspan="3" nowrap>
              <?
	      $arr_descart = array('t'=>'Valor corrigido','f'=>'Valor histórico');
              db_select("descart", $arr_descart, true, 1);
              ?>
            </td>
          </tr>
          <tr>
            <td nowrap align="right" title="" width="40%"><b>
              <?
              db_ancora('Rubricas Dedução da Base:',"js_pesquisabase01(true)",2);
              ?>
	      </b>
            </td>
            <td nowrap> 
              <?

              db_input('r08_codigo',4,$Ir08_codigo,true,'text',2,"onchange='js_pesquisabase01(false)'");
              db_input("r08_descr",30,$Ir08_descr,true,"text",3,"","descr_base01");
              ?>
            </td>
          </tr>
        </table>
        <input name="gerar" type="button" id="gerar" value="Gerar relatório" onclick="js_emite();">
      </form>
      </center>
    </td>
  </tr>
</table>
<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_emite(){
  if(document.form1.rh01_regist.value == ""){
    alert("Informe a matrícula.");
    document.form1.rh01_regist.focus();
  }else if(document.form1.i01_codigo.value == ""){
    alert("Informe o código do inflator.");
    document.form1.i01_codigo.focus();
  }else if(document.form1.anoi.value == "" || document.form1.mesi.value == ""){
    alert("Informe o ano / mês do período inicial.");
    document.form1.anoi.select();
    document.form1.anoi.focus();
  }else if(document.form1.anof.value == "" || document.form1.mesf.value == ""){
    alert("Informe o ano / mês do período final.");
    document.form1.anof.select();
    document.form1.anof.focus();
  }else if(document.form1.datacor_ano.value == "" || document.form1.datacor_mes.value == "" || document.form1.datacor_dia.value == ""){
    alert("Informe a data para correção.");
    document.form1.datacor_dia.select();
    document.form1.datacor_dia.focus();
  }else if(document.form1.indcor.value == ""){
    alert("Informe o índice de correção.");
    document.form1.indcor.focus();
  }else{
    qry  = "?regis="+document.form1.rh01_regist.value;
	  qry += "&base1="+document.form1.r08_codigo.value;
    qry += "&infla="+document.form1.i01_codigo.value;
    qry += "&anoi="+document.form1.anoi.value;
    qry += "&mesi="+document.form1.mesi.value;
    qry += "&anof="+document.form1.anof.value;
    qry += "&mesf="+document.form1.mesf.value;
    qry += "&indi="+document.form1.indcor.value;
    qry += "&data="+document.form1.datacor_ano.value+"-"+document.form1.datacor_mes.value+"-"+document.form1.datacor_dia.value;
    qry += "&dadosvalor="+document.form1.descart.value;
    qry += "&nrport="+document.form1.nrport.value;
    qry += "&datanrport="+document.form1.datanrport_ano.value+"-"+document.form1.datanrport_mes.value+"-"+document.form1.datanrport_dia.value;

    if(confirm("Incluir décimo terceiro no relatório?")){
      qry += "&i13o=s";
    }

    jan = window.open('pes2_aposentadoria002.php'+qry,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
}
function js_pesquisarh01_regist(mostra){
  if(mostra==true){
    js_OpenJanelaIframe("top.corpo","db_iframe_rhpessoal","func_rhpessoal.php?funcao_js=parent.js_mostraregist1|rh01_regist|z01_nome","Pesquisa",true,"20");
  }else{
     if(document.form1.rh01_regist.value != ""){ 
       js_OpenJanelaIframe("top.corpo","db_iframe_rhpessoal","func_rhpessoal.php?pesquisa_chave="+document.form1.rh01_regist.value+"&funcao_js=parent.js_mostraregist","Pesquisa",false,"20");
     }else{
       document.form1.z01_nome.value = ""; 
     }
  }
}
function js_mostraregist(chave,erro){
  document.form1.z01_nome.value = chave; 
  if(erro==true){ 
    document.form1.rh01_regist.focus(); 
    document.form1.rh01_regist.value = ""; 
  }
}
function js_mostraregist1(chave1,chave2){
  document.form1.rh01_regist.value = chave1;
  document.form1.z01_nome.value = chave2;
  db_iframe_rhpessoal.hide();
}
function js_pesquisai01_codigo(mostra){
  if(mostra==true){
    js_OpenJanelaIframe("top.corpo","db_iframe_inflan","func_inflan.php?funcao_js=parent.js_mostracodigo1|i01_codigo|i01_descr","Pesquisa",true,"20");
  }else{
     if(document.form1.i01_codigo.value != ""){ 
       js_OpenJanelaIframe("top.corpo","db_iframe_inflan","func_inflan.php?pesquisa_chave="+document.form1.i01_codigo.value+"&funcao_js=parent.js_mostracodigo","Pesquisa",false,"20");
     }else{
       document.form1.i01_descr.value = ""; 
     }
  }
}
function js_mostracodigo(chave,erro){
  document.form1.i01_descr.value = chave; 
  if(erro==true){ 
    document.form1.i01_codigo.focus(); 
    document.form1.i01_codigo.value = ""; 
  }
}
function js_mostracodigo1(chave1,chave2){
  document.form1.i01_codigo.value = chave1;
  document.form1.i01_descr.value = chave2;
  db_iframe_inflan.hide();
}
function js_pesquisabase01(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_bases','func_bases.php?funcao_js=parent.js_mostrabase011|r08_codigo|r08_descr','Pesquisa',true);
  }else{
    if(document.form1.r08_codigo.value != ''){ 
      js_OpenJanelaIframe('top.corpo','db_iframe_base01','func_bases.php?pesquisa_chave='+document.form1.r08_codigo.value+'&funcao_js=parent.js_mostrabase01','Pesquisa',false);
    }else{
      document.form1.descr_base01.value = ''; 
    }
  }
}
function js_mostrabase01(chave,erro){
  document.form1.descr_base01.value = chave; 
  if(erro==true){ 
    document.form1.base01.focus(); 
    document.form1.r08_codigo.value = ''; 
  }
}
function js_mostrabase011(chave1,chave2){
  document.form1.r08_codigo.value = chave1;
  document.form1.descr_base01.value = chave2;
  db_iframe_bases.hide();
}
js_tabulacaoforms("form1","rh01_regist",true,1,"rh01_regist",true);
</script>