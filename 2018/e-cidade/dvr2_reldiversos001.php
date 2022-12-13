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
include("dbforms/db_funcoes.php");
include("classes/db_issbase_classe.php");
include("classes/db_iptubase_classe.php");
include("classes/db_cgm_classe.php");
db_postmemory($HTTP_SERVER_VARS);
db_postmemory($HTTP_POST_VARS);
$db_botao=1;
$db_opcao=1;
$cliptubase = new cl_iptubase;
$clrotulo = new rotulocampo;
$clrotulo->label("q02_inscr");
$clrotulo->label("z01_nome");
$clrotulo->label("dv05_numcgm");
$clrotulo->label("j01_matric");
$clrotulo->label("dv05_coddiver");
$clrotulo->label("dv09_procdiver");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/prototype.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>

function js_desabilitarTodosCheck() {
  var aIput = document.getElementsByTagName('input');
  for(var i=0; i < aIput.length; i++) {
    if(aIput[i].type == 'checkbox' && aIput[i].name != 'inconsistente') {
      aIput[i].checked = false;
    }
  }  
}

function js_relatorio() {

  //situ = document.form1.situacao;

  var sSituacoesDiverso = "";
  var sSeparadorDiverso = "";
  var sSituacoesDebito  = "";
  var sSeparadorDebito  = "";
  var aIput             = document.getElementsByTagName('input');
  for(var i=0; i < aIput.length; i++) {
    if(aIput[i].type == 'checkbox') {
      if (aIput[i].className == 'debito' && aIput[i].checked == true) {
        sSituacoesDebito += sSeparadorDebito+aIput[i].name;
        sSeparadorDebito = "|";
      }
    }  
  } 

//  alert("Diverso : "+sSituacoesDiverso+" Debito : "+sSituacoesDebito );
//  return false;
  var cgm         = document.form1.dv05_numcgm.value;
  var coddiver    = document.form1.dv05_coddiver.value;
  var inscr       = document.form1.q02_inscr.value;
  var matric      = document.form1.j01_matric.value;
  var proced      = document.form1.dv09_procdiver.value;
  var dataini     = document.form1.dataini_ano.value+'-'+document.form1.dataini_mes.value+'-'+document.form1.dataini_dia.value;
  var datafim     = document.form1.datafim_ano.value+'-'+document.form1.datafim_mes.value+'-'+document.form1.datafim_dia.value;
  var descrproced = document.form1.dv09_procdiver.value+' - '+document.form1.z01_nomeproc.value;
  var tipovenc    = document.form1.tipovenc.value;
  var sQuery      =  'cgm='+cgm+'&coddiver='+coddiver+'&inscr='+inscr+'&matric='+matric+'&proced='+proced+'&situacaodebito='+sSituacoesDebito;
  sQuery          += '&dataini='+dataini+'&datafim='+datafim+'&descrproced='+descrproced+'&tipovenc='+tipovenc;
  
  jan = window.open('dvr2_reldiversos002.php?'+sQuery,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}  

function js_erro(tipo,valor){
  jan.window.close();
  alert(_M("tributario.diversos.dvr2_reldiversos001.sem_registro"));
}
</script>
</head>
<body bgcolor=#CCCCCC>

<form class="container" name="form1" method="post" action="dvr2_reldiversos002.php"  onSubmit="return js_verifica_campos_digitados();" >
  <fieldset>
    <legend>Relatório de Diversos :</legend>
     <table class="form-container">
      <tr>   
        <td>
          <?
            db_ancora($Ldv05_coddiver,'js_diver(true); ',1);
          ?>
        </td>
        <td nowrap> 
          <?
             db_input('dv05_coddiver',10,$Idv05_coddiver,true,'text',1,"onchange='js_diver(false)'");
             db_input('z01_nome',50,0,true,'text',3,"","z01_nomediver");
          ?>
        </td>
      </tr>
      <tr>   
        <td nowrap>
          <?
            db_ancora($Ldv05_numcgm,' js_cgm(true); ',1);
          ?>
        </td>
        <td nowrap> 
          <?
            db_input('dv05_numcgm',10,$Idv05_numcgm,true,'text',1,"onchange='js_cgm(false)'","dv05_numcgm");
            db_input('z01_nome',50,0,true,'text',3,"","z01_nomecgm");
          ?>
        </td>
      </tr>
      <tr>   
        <td nowrap>
          <?
            db_ancora($Lj01_matric,' js_matri(true); ',1);
          ?>
        </td>
        <td nowrap> 
          <?
            db_input('j01_matric',10,$Ij01_matric,true,'text',1,"onchange='js_matri(false)'");
            db_input('z01_nome',50,0,true,'text',3,"","z01_nomematri");
          ?>
        </td>
      </tr>
      <tr>   
        <td nowrap>
          <?
            db_ancora($Lq02_inscr,' js_inscr(true); ',1);
          ?>
        </td>
        <td nowrap>  
          <?
            db_input('q02_inscr',10,$Iq02_inscr,true,'text',1,"onchange='js_inscr(false)'");
            db_input('z01_nome',50,0,true,'text',3,"","z01_nomeinscr");
          ?>
        </td>
      </tr>
      <tr>   
        <td nowrap>
          <?
            db_ancora($Ldv09_procdiver,'js_proc(true); ',1);
          ?>
        </td>
        <td nowrap> 
          <?
           db_input('dv09_procdiver',10,$Idv09_procdiver,true,'text',1,"onchange='js_proc(false)'");
           db_input('z01_nome',50,0,true,'text',3,"","z01_nomeproc");
          ?>
        </td>
      </tr>
      <tr>
        <td nowrap title="À partir de qual data">
          Data inicial:
        </td>
        <td nowrap> 
          <?
            db_inputdata('dataini',"","","",true,'text',1)
          ?> 
          <b>até</b> 
          <?
            db_inputdata('datafim',"","","",true,'text',1)
          ?> 
        </td>
      </tr>
      <tr>
        <td nowrap>
          Situação : 
        </td>
        <td style='padding-left:0px' nowrap>
          <table class="form-container">
            <tr align='left'>
              <td nowrap align='left' width='33%' style='padding-left:0px' > <input class='debito' type="checkbox" name="parcelado"     id="parcelado"     value=""><b>Parcelado </b></td>
              <td nowrap align='left' width='33%' style='padding-left:0px' > <input class='debito' type="checkbox" name="importado"     id="importado"     value=""><b>Importado </b></td>
              <td nowrap align='left' width='33%' style='padding-left:0px' > <input class='debito' type="checkbox" name="inconsistente" id="inconsistente" value="" onClick='js_desabilitarTodosCheck();'><b>Inconsistente </b></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
        </td>
        <td style='padding-left:0px' nowrap >
          <table class="form-container">
            <tr align='left' >
              <td nowrap align='left' style='padding-left:0px' width='33%'> <input class='debito' type="checkbox" name="pago"      id="pago"><b>Pago</b></td>
              <td nowrap align='left' style='padding-left:0px' width='33%'> <input class='debito' type="checkbox" name="naopago"   id="naopago"><b>Devido</b> </td>
              <td nowrap align='left' style='padding-left:0px' width='33%'> <input class='debito' type="checkbox" name="cancelado" id="cancelado"><b>Cancelado &ensp;&ensp; </b></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>
				  Tipo:
				</td>
        <td>
          <select name="tipovenc" id="">
            <option value="t" selected>Todos</option>
		        <option value="v" >Somente os vencidos</option>
		        <option value="n" >Somente os não vencidos</option>
	        </select> 
				</td>
			</tr>
    </table> 	 
  </fieldset>
  <input type="button" name="gerar" value="Gerar Relatório" onclick="js_relatorio();">
</form>

<?
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>
function js_proc(mostra){
  var proc=document.form1.dv09_procdiver.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_procdiver.php?funcao_js=parent.js_mostraproc|dv09_procdiver|dv09_descr','Pesquisa',true);
  }else{
    if (proc == '') {
      document.form1.z01_nomeproc.value = '';            
      return false;
    }
    js_OpenJanelaIframe('top.corpo','db_iframe','func_procdiver.php?pesquisa_chave='+proc+'&funcao_js=parent.js_mostraproc1','Pesquisa',false);
  }
}
function js_mostraproc(chave1,chave2){
  document.form1.dv09_procdiver.value = chave1;
  document.form1.z01_nomeproc.value = chave2;
  db_iframe.hide();
}

function js_mostraproc1(chave,erro){
  document.form1.z01_nomeproc.value = chave; 
  if(erro==true){ 
    document.form1.dv09_procdiver.focus(); 
    document.form1.dv09_procdiver.value = ''; 
    document.form1.z01_nomeproc.value = ''; 
  }
}
function js_diver(mostra){
  var diver=document.form1.dv05_coddiver.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_diversos.php?funcao_js=parent.js_mostradiver|dv05_coddiver|z01_nome','Pesquisa',true);
  }else{
    js_OpenJanelaIframe('top.corpo','db_iframe','func_diversos.php?pesquisa_chave='+diver+'&funcao_js=parent.js_mostradiver1','Pesquisa',false);
  }
}
function js_mostradiver(chave1,chave2){
  document.form1.dv05_coddiver.value = chave1;
  document.form1.z01_nomediver.value = chave2;
  db_iframe.hide();
}
function js_mostradiver1(chave,erro){
  document.form1.z01_nomediver.value = chave; 
  if(erro==true){ 
    document.form1.dv05_coddiver.focus(); 
    document.form1.dv05_coddiver.value = ''; 
  }
}



function js_matri(mostra){
  var matri=document.form1.j01_matric.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_iptubase.php?funcao_js=parent.js_mostramatri|j01_matric|z01_nome','Pesquisa',true);
  }else{
    if (matri == '') {
      document.form1.z01_nomematri.value = ''; 
      return false;
    }
    js_OpenJanelaIframe('top.corpo','db_iframe','func_iptubase.php?pesquisa_chave='+matri+'&funcao_js=parent.js_mostramatri1','Pesquisa',false);
  }
}
function js_mostramatri(chave1,chave2){
  document.form1.j01_matric.value = chave1;
  document.form1.z01_nomematri.value = chave2;
  db_iframe.hide();
}
function js_mostramatri1(chave,erro){
  document.form1.z01_nomematri.value = chave; 
  if(erro==true){ 
    document.form1.j01_matric.focus(); 
    document.form1.j01_matric.value = ''; 
    document.form1.z01_nomematri.value = ''; 
  }
}


function js_inscr(mostra){
  var inscr=document.form1.q02_inscr.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_issbase.php?funcao_js=parent.js_mostrainscr|q02_inscr|z01_nome','Pesquisa',true);
  }else{
    if (inscr == '') {
      document.form1.z01_nomeinscr.value = '';
      return false;
    }
    js_OpenJanelaIframe('top.corpo','db_iframe','func_issbase.php?pesquisa_chave='+inscr+'&funcao_js=parent.js_mostrainscr1','Pesquisa',false);
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
    document.form1.z01_nomeinscr.value = ''; 
  }
}


function js_cgm(mostra){
  var cgm = document.form1.dv05_numcgm.value;
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?funcao_js=parent.js_mostracgm|0|1','Pesquisa',true);
  }else{
    if (cgm == '') {
      document.form1.z01_nomecgm.value = ''; 
      return false;      
    }
    js_OpenJanelaIframe('top.corpo','db_iframe','func_nome.php?pesquisa_chave='+cgm+'&funcao_js=parent.js_mostracgm1','Pesquisa',false);
  }
}
function js_mostracgm(chave1,chave2){
  document.form1.dv05_numcgm.value = chave1;
  document.form1.z01_nomecgm.value = chave2;
  db_iframe.hide();
}
function js_mostracgm1(erro,chave){
  document.form1.z01_nomecgm.value = chave; 
  if(erro==true){ 
    document.form1.dv05_numcgm.focus(); 
    document.form1.dv05_numcgm.value = ''; 
    document.form1.z01_nomecgm.value = ''; 
  }
}

</script>
<?
if(isset($dado) && $dado=="inscr"){
  db_msgbox(_M("tributario.diversos.dvr2_reldiversos001.inscricao_invalida"));
}  
if(isset($dado) && $dado=="matric"){
  db_msgbox(_M("tributario.diversos.dvr2_reldiversos001.matricula_invalida"));
}  
if(isset($dado) && $dado=="numcgm"){
  db_msgbox(_M("tributario.diversos.dvr2_reldiversos001.numcgm_invalido"));
}  
$func_iframe = new janela('db_iframe','');
$func_iframe->posX=1;
$func_iframe->posY=20;
$func_iframe->largura=780;
$func_iframe->altura=430;
$func_iframe->titulo='Pesquisa';
$func_iframe->iniciarVisivel = false;
$func_iframe->mostrar();
?>
<script>

$("dv05_coddiver").addClassName("field-size2");
$("z01_nomediver").addClassName("field-size7");
$("dv05_numcgm").addClassName("field-size2");
$("z01_nomecgm").addClassName("field-size7");
$("j01_matric").addClassName("field-size2");
$("z01_nomematri").addClassName("field-size7");
$("q02_inscr").addClassName("field-size2");
$("z01_nomeinscr").addClassName("field-size7");
$("dv09_procdiver").addClassName("field-size2");
$("z01_nomeproc").addClassName("field-size7");
$("dataini").addClassName("field-size2");
$("datafim").addClassName("field-size2");

</script>