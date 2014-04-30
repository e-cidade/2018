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
include("classes/db_lab_labsetor_classe.php");
include("classes/db_lab_requisicao_classe.php");
include("classes/db_lab_exame_classe.php");
require_once('libs/db_utils.php');
$cllab_labsetor = new cl_lab_labsetor;
$cllab_requisicao = new cl_lab_requisicao;
$cllab_exame = new cl_lab_exame;
$clrotulo = new rotulocampo;
$clrotulo->label("la09_i_exame");
$clrotulo->label("la24_i_setor");
$clrotulo->label("la02_i_codigo");
$clrotulo->label("la24_c_descr");

$iUsuario = db_getsession('DB_login');
$iDepto = db_getsession('DB_coddepto');

$oDaolab_labusuario

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >

<table valign="top" marginwidth="0" width="100%" height="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="center" valign="top" bgcolor="#CCCCCC">
  <center>
  <br><br>
  <fieldset style='width: 75%;'> <legend><b>Mapa de Trabalho</b></legend>
    <form name='form1'>
    <table>
     <td align="left" >
       <b> Período:</b>
     </td>
     <td>
       <?db_inputdata('data1',@$dia1,@$mes1,@$ano1,true,'text',1,"")?>
        A
       <?db_inputdata('data2',@$dia2,@$mes2,@$ano2,true,'text',1,"")?>
     </td>
     </tr>
  <tr>
    <td nowrap title="Laborat&oacute;rio">
       <?
       db_ancora('<b>Laborat&oacute;rio:</b>',"js_pesquisala02_i_laboratorio(true);","");
       ?>
    </td>
    <td> 
       <?
       db_input('la02_i_codigo',10,$Ila02_i_codigo,true,'text',""," onchange='js_pesquisala02_i_laboratorio(false);'");
       db_input('la02_c_descr',50,@$Ila02_c_descr,true,'text',3,'');
       ?>
    </td>
 
  </tr>
  <tr>
    <td nowrap title="<?=@$Tla24_i_setor?>">
       <?
       db_ancora(@$Lla24_i_setor,"js_pesquisala24_i_setor(true);","");
       ?>
    </td>
   <td> 
       <?
       db_input('la24_i_setor',10,$Ila24_i_setor,true,'text',""," onchange='js_pesquisala24_i_setor(false);'");
       db_input('la24_i_codigo',10,'',true,'hidden','','');
       db_input('la23_c_descr',50,@$Ila23_c_descr,true,'text',3,'');
       ?>
    </td>
  </tr>
     <tr>
		<td nowrap title="<?=@$Tla09_i_exame?>">
      <?
			db_ancora ( @$Lla09_i_exame, "js_pesquisala09_i_exame(true);", "" );
		  ?>
    </td>
		<td> 
      <?
      db_input('la09_i_exame', 10, @$Ila09_i_exame, true, 'text',"", " onchange='js_pesquisala09_i_exame(false);'" );
      db_input('la09_i_codigo',10,'',true,'hidden','','');
			db_input('la08_c_descr', 50, @$Ila08_c_descr, true, 'text', 3, '' );
      ?>
    </td>
	</tr>
	<tr>
	   <td>
	       <stronger><b>Atributos:</b></stronger>
	   </td>
	   <td>
	      <? $aParam=Array("2"=>"NÃO","1"=>"SIM");
	         db_select("atributos",$aParam,"",1,""); ?>
	   </td>
	</tr>
     <tr>
       <td colspan='6' align='center' >
         <input name='start' type='button' value='Gerar' onclick="js_mandaDados()">
       </td>
     </tr>
    </table>
    </form>
    </fieldset>
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

function js_limpaCamposTrocaLab() {
 
  document.form1.la24_i_setor.value = '';
  document.form1.la24_i_codigo.value = '';
  document.form1.la23_c_descr.value = '';
  js_limpaCamposTrocaSetor();

}
function js_limpaCamposTrocaSetor() {

  document.form1.la09_i_exame.value = '';
  document.form1.la08_c_descr.value = '';

}


function js_pesquisala02_i_laboratorio(mostra) {
  if (mostra==true) {
    js_OpenJanelaIframe('','db_iframe_laboratorio','func_lab_laboratorio.php?checkLaboratorio=true&funcao_js=parent.js_mostralaboratorio1|la02_i_codigo|la02_c_descr','Pesquisa',true);
  }else{
     if (document.form1.la02_i_codigo.value != '') { 
        js_OpenJanelaIframe('','db_iframe_laboratorio','func_lab_laboratorio.php?checkLaboratorio=true&pesquisa_chave='+document.form1.la02_i_codigo.value+'&funcao_js=parent.js_mostralaboratorio','Pesquisa',false);
     } else {
         document.form1.la02_c_descr.value = ''; 
     }
  }
}
function js_mostralaboratorio(chave,erro) {
  document.form1.la02_c_descr.value = chave; 
  if (erro==true) { 
    document.form1.la02_i_codigo.focus(); 
    document.form1.la02_i_codigo.value = ''; 
  }
  js_limpaCamposTrocaLab();
}

function js_mostralaboratorio1(chave1,chave2) {
  document.form1.la02_i_codigo.value = chave1;
  document.form1.la02_c_descr.value = chave2;
  db_iframe_laboratorio.hide();
  js_limpaCamposTrocaLab();

}

function js_pesquisala24_i_setor(mostra) {

  if(document.form1.la02_i_codigo.value == '') {

    alert('Escolha um laboratorio primeiro.');
    js_limpaCamposTrocaLab();
    return false;

  }
  sPesq = 'la24_i_laboratorio='+document.form1.la02_i_codigo.value+'&';
 
  if (mostra==true) {
    js_OpenJanelaIframe('','db_iframe_lab_labsetor','func_lab_labsetor.php?'+sPesq+'funcao_js=parent.js_mostralab_labsetor1|la24_i_setor|la23_c_descr|la24_i_codigo','Pesquisa',true);
  }else{
     if(document.form1.la24_i_setor.value != ''){ 
       js_OpenJanelaIframe('','db_iframe_lab_labsetor','func_lab_labsetor.php?'+sPesq+'pesquisa_chave='+document.form1.la24_i_setor.value+'&funcao_js=parent.js_mostralab_labsetor','Pesquisa',false);
     }else{
        document.form1.la23_c_descr.value = ''; 
        document.form1.la24_i_codigo.value = ''; 
     }
  }
}
function js_mostralab_labsetor(chave,erro,chave2) {
  document.form1.la23_c_descr.value = chave; 
  document.form1.la24_i_codigo.value = chave2; 
  if(erro==true){ 
    document.form1.la24_i_setor.focus(); 
    document.form1.la24_i_setor.value = ''; 
    document.form1.la24_i_codigo.value = ''; 
  }
  js_limpaCamposTrocaSetor();
}

function js_mostralab_labsetor1(chave1,chave2,chave3) {

  document.form1.la24_i_setor.value = chave1;
  document.form1.la24_i_codigo.value = chave3;
  document.form1.la23_c_descr.value = chave2;
  db_iframe_lab_labsetor.hide();
  js_limpaCamposTrocaSetor();

}
	
function js_pesquisala09_i_exame(mostra) {

  if (document.form1.la24_i_setor.value != '') {
    sPesq = 'la24_i_codigo='+document.form1.la24_i_codigo.value+'&';
  } else {
    sPesq = 'la02_i_codigo='+document.form1.la02_i_codigo.value+'&';
  }

  if (document.form1.la24_i_setor.value != '') {
    if(mostra==true){
      js_OpenJanelaIframe('','db_iframe_lab_setorexame','func_lab_setorexame.php?'+sPesq+'&funcao_js=parent.js_mostralab_exame1|la08_i_codigo|la08_c_descr|la09_i_codigo','Pesquisa',true);
    } else {
       if(document.form1.la09_i_exame.value != ''){ 
         js_OpenJanelaIframe('','db_iframe_lab_setorexame','func_lab_setorexame.php?'+sPesq+'pesquisa_chave='+document.form1.la09_i_exame.value+'&funcao_js=parent.js_mostralab_exame','Pesquisa',false);
       } else {
          document.form1.la08_c_descr.value = ''; 
          document.form1.la09_i_codigo.value = ''; 
       }
    }
  } 
}

function js_mostralab_exame(chave,erro,chave2) {
  document.form1.la08_c_descr.value = chave; 
  document.form1.la09_i_codigo.value = chave2; 
  if (erro==true) { 

    document.form1.la09_i_exame.focus(); 
    document.form1.la09_i_exame.value = ''; 

  }
}

function js_mostralab_exame1(chave1,chave2,chave3) {

  document.form1.la09_i_exame.value = chave1;
  document.form1.la08_c_descr.value = chave2;
  document.form1.la09_i_codigo.value = chave3;
  db_iframe_lab_setorexame.hide();

}

function js_mandaDados() {

  if (document.form1.data1.value=="" && document.form1.data2.value=="") {

     alert("Preencha o Período");
     document.form1.data1.focus();
     return false;

  }

  if (document.form1.la02_i_codigo.value=="") {

    alert("Pesquise um laboratório");
    document.form1.la02_i_codigo.focus();
    return false;

  }
 
  oF = document.form1;
  sDatas = 'datas='+oF.data1.value+','+oF.data2.value;
  iLaboratorio = '&laboratorio='+oF.la02_i_codigo.value;
  iLabsetor = '&labsetor='+oF.la24_i_codigo.value;
  iExame = '&exame='+oF.la09_i_exame.value;
  sNomesetor = '&nomesetor='+oF.la23_c_descr.value;
  sAtributo = '&iAtributo='+oF.atributos.value;
  jan = window.open('lab4_mapatrabalho002.php?'+sDatas+sAtributo+iLaboratorio+iLabsetor+iExame+sNomesetor,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
 
}



function js_validadata() {
	
  if(document.form1.data1.value != ''  && document.form1.data2.value != '' ) {

    aIni = document.form1.data1.value.split('/');
    aFim = document.form1.data2.value.split('/');
    dIni = new Date(aIni[2], aIni[1], aIni[0]);
    dFim = new Date(aFim[2], aFim[1], aFim[0]);

    if(dFim < dIni) {
		
      alert("Data final nao pode ser menor que a data inicial.");
      document.form1.data2.value = '';
      return false;

    }
    return true;
  } else {

    alert('Preencha o período.');
    return false

  }

}
</script>