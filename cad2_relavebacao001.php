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

require ("libs/db_stdlib.php");
require ("libs/db_conecta.php");
include ("libs/db_sessoes.php");
include ("libs/db_usuariosonline.php");
include ("dbforms/db_funcoes.php");

include ("classes/db_cgm_classe.php");
include ("classes/db_iptubase_classe.php");
include ("classes/db_averbacao_classe.php");
include ("classes/db_ruas_classe.php");
include ("classes/db_averbatipo_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS ['QUERY_STRING']);

$clcgm = new cl_cgm();
$clcgm->rotulo->label("z01_numcgm");
$clcgm->rotulo->label("z01_nome");

$cliptubase = new cl_iptubase();
$cliptubase->rotulo->label("j01_matric");
$cliptubase->rotulo->label("z01_nomepropri");

$claverbacao = new cl_averbacao;
$claverbacao->rotulo->label("j75_codigo");

$clruas = new cl_ruas();
$clruas->rotulo->label("j14_codigo");
$clruas->rotulo->label("j14_nome");

$claverbatipo = new cl_averbatipo();
$claverbatipo->rotulo->label("j93_codigo");
$claverbatipo->rotulo->label("j93_descr");

$clrotulo = new rotulocampo();
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
$clrotulo->label("j01_matric");
$clrotulo->label("z01_nomepropri");
$clrotulo->label("j75_codigo");
$clrotulo->label("j14_codigo");
$clrotulo->label("j14_nome");
$clrotulo->label("j93_codigo");
$clrotulo->label("j93_descr");

$info = "";
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#CCCCCC">
<form name="form1" method="post" action="cad3_consultaitbinew001.php">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<center>
<table border="0">
  <tr>
    <td colspan="2" align="center"><br>
    <br>
    <strong></strong><br>
    <br>
    
    
    <td>
  
  </tr>
</table>
<table>
  <tr>
    <td>
    <fieldset><legend align="left"><b>Relatório de Averbação</b></legend>
    <table border="0">
      <tr>
        <td align="right" nowrap title="Nome do Contribuinte">
	       <?
        db_ancora("<b>Nome do Contribuinte:</b>", "js_pesquisanome(true);", 1);
        ?>
      </td>
        <td align="left">
		     <?
      db_input('z01_numcgm', 10, $Iz01_numcgm, true, 'text', 1, "onChange='js_pesquisanome(false);'");
      db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3, "");
      ?>
      </td>
      </tr>
      <tr>
        <td align="right" nowrap title="Matricula">
	       <?
        db_ancora("<b>Matricula:</b>", "js_pesquisamatricula(true);", 1);
        ?>
        </td>
        <td align="left"> 
			   <?
      db_input('j01_matric', 10, $Ij01_matric, true, 'text', 1, "onChange='js_pesquisamatricula(false);'");
      db_input('z01_nomepropri', 40, $Ij14_nome, true, 'text', 3, "");
      ?>
	    </td>
      </tr>
      <tr>
        <td align="right" nowrap title="Averbação">
			       <?
          db_ancora("<b>Averbação:</b>", "js_pesquisaaverbacao(true);", 1);
          ?>
        </td>
        <td>
				    <?
        db_input('j75_codigo', 10, $Ij75_codigo, true, 'text', 1, "onChange='js_pesquisaaverbacao(false);'");
        ?>    
	       </td>
      </tr>
      <tr>
        <td align="right" nowrap title="Nome da Rua">
	       <?
        db_ancora("<b>Nome da Rua:</b>", "js_mostrarruas(true);", 1);
        ?>
      </td>
        <td align="left" colspan="4"> 
	     <?
      db_input('j14_codigo', 10, $Ij14_codigo, true, 'text', 1, "onChange='js_mostrarruas(false);'");
      db_input('j14_nome', 40, $Ij14_nome, true, 'text', 3, "");
      ?>
    </td>
      </tr>
      <tr>
      
      
      <tr>
        <td align="right" nowrap title="Tipo de Averbação">
       <?
      db_ancora("<b>Tipo de Averbação:</b>", "js_mostrartipoaverba(true);", 1);
      ?>
    </td>
        <td align="left"> 
     <?
    db_input('j93_codigo', 10, $Ij93_codigo, true, 'text', 1, "onChange='js_mostrartipoaverba(false);'");
    db_input('j93_descr', 40, $Ij93_descr, true, 'text', 3, "");
    ?>
    </td>
      </tr>
      <tr>
        <td align="right" nowrap title="Processados"><strong>Processados : </strong></td>
        <td align="left" nowrap title="Verificar o campo processados por: Processados ou Não Processados">
     <?
    $sProcessados = array (       
                                  "S" => "Processados",                                   
                                  "N" => "Não Processados"
    );
    db_select('processados', $sProcessados, true, 1, "onClick=''");
    ?>
    </td>
      </tr>
      <tr>
        <td align="right" nowrap title="Data da Averbação"><b>Data da Averbação : </b></td>
        <td align="left">
		      <?
        db_inputdata("dataini", @$dataini_dia, @$dataini_mes, @$dataini_ano, true, 'text', 1);
        ?>
          <b> até </b>
		      <?
        db_inputdata("datafim", @$datafim_dia, @$datafim_mes, @$datafim_ano, true, 'text', 1);
        ?>      
        </td>
      </tr>
      <tr>
        <td align="right" nowrap title="Ordem"><strong>Ordem : </strong></td>
        <td align="left" nowrap title="Ordenados por: Matricula, Averbação, Rua, Tipo de Averbação ou Data">
     <?
    $ordem = array (
      
                                  "M" => "Matricula", 
                                  "A" => "Averbação", 
                                  "R" => "Rua", 
                                  "T" => "Tipo de Averbação", 
                                  "D" => "Data" 
    );
    db_select('ordem', $ordem, true, 1, "onClick=''");
    ?>
    </td>
      </tr>
      <tr>
    
    </table>
    </fieldset>
    </td>
  </tr>
</table>
<table width="790" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table border="0">
  <tr>
    <td colspan="2" align="center"><input name="imprimir" type="button" value="Imprimir" onClick="js_enviadados();"></td>
  </tr>
</table>
</center>
</form>
<?
db_menu(db_getsession("DB_id_usuario"),
db_getsession("DB_modulo"), 
db_getsession("DB_anousu"), 
db_getsession("DB_instit"));
?>
<script>

/*function js_limpacampos(){

  document.form1.dataini.value          = '';
  document.form1.datafim.value          = '';
     
}*/

function js_pesquisanome(lMostra){
  
  if (lMostra) {
    
	  var sUrl = 'func_nome.php?funcao_js=parent.js_mostranome1|z01_nome|z01_numcgm';  
    js_OpenJanelaIframe('','db_iframe_nome',sUrl,'Pesquisa',true);
    
  } else {
    
    if (document.form1.z01_numcgm.value != '') {
        
    	sUrl = 'func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostranome';
      js_OpenJanelaIframe('','db_iframe_nome',sUrl,'Pesquisa',false);
      
    } else {
      document.form1.z01_numcgm.value       = '';
      document.form1.z01_nome.value         = '';
       
    }
  }  
}

function js_mostranome(erro,chave){
    document.form1.z01_nome.value = chave; 
    if(erro==true){ 
      document.form1.z01_numcgm.focus(); 
      document.form1.z01_numcgm.value       = '';
      document.form1.z01_nome.value         = '';      
    }
  }
  
  function js_mostranome1(chave1,chave2){
  
    document.form1.z01_nome.value = chave1;
    document.form1.z01_numcgm.value = chave2;
    db_iframe_nome.hide();
  }

 /////////////////////////////////////////////////////////
  
function js_pesquisamatricula(lMostra){
  
    if (lMostra) {
        
    var	sUrl = 'func_iptubase.php?funcao_js=parent.js_mostramatricula1|j01_matric|z01_nome';
      js_OpenJanelaIframe('','db_iframe_matricula',sUrl,'Pesquisa',true);
      
    } else {
        
    if (document.form1.j01_matric.value != '') {
         
    var sUrl = 'func_iptubase.php?pesquisa_chave='+document.form1.j01_matric.value+'&funcao_js=parent.js_mostramatricula'; 
     js_OpenJanelaIframe('','db_iframe_matricula',sUrl,'Pesquisa',false);
     
    } else {
      document.form1.z01_nomepropri.value   = '';
      document.form1.j01_matric.value       = '';        
      }
    }  
  }

  function js_mostramatricula(chave,erro){
  
      document.form1.z01_nomepropri.value = chave; 
      if (erro==true) { 
        document.form1.j01_matric.focus(); 
        document.form1.z01_nomepropri.value   = '';
        document.form1.j01_matric.value       = '';
      }
    }
    
    function js_mostramatricula1(chave1,chave2){
    
      document.form1.j01_matric.value = chave1;
      document.form1.z01_nomepropri.value = chave2;
      db_iframe_matricula.hide();
    }  

 /////////////////////////////////////////////////////////

function js_pesquisaaverbacao(lMostra){
  
  if (lMostra) {
    
   var sUrl = 'func_averbacao.php?funcao_js=parent.js_mostraaverbacao1|j75_codigo';
    js_OpenJanelaIframe('','db_iframe_matricula',sUrl,'Pesquisa',true);
    
  } else {
    
   if (document.form1.j75_codigo.value != '') {
      
   var sUrl = 'func_averbacao.php?pesquisa_chave='+document.form1.j75_codigo.value+'&funcao_js=parent.js_mostraaverbacao';
    js_OpenJanelaIframe('','db_iframe_matricula',sUrl,'Pesquisa',false);
    
  } else {
     document.form1.j75_codigo.value = '';
  }
 }  
}

function js_mostraaverbacao(chave,erro){
  
    document.form1.j75_codigo.value = chave;
     
    if (erro==true) {
         
      document.form1.j75_codigo.focus(); 
      document.form1.j75_codigo.value = '';
    }
  }
  
  function js_mostraaverbacao1(chave1){
    document.form1.j75_codigo.value = chave1;
    //document.form1.z01_nomepropri.value = chave2;
    db_iframe_matricula.hide();
  }     

  /////////////////////////////////////////////////////////

function js_mostrarruas(lMostra){
    if (lMostra) {
        
       var sUrl = 'func_ruas.php?funcao_js=parent.js_mostaruas1|j14_codigo|j14_nome';
        js_OpenJanelaIframe('','db_iframe_matricula',sUrl,'Pesquisa',true);

    } else {
        
       if (document.form1.j14_codigo.value != '') {
            
       var sUrl = 'func_ruas.php?pesquisa_chave='+document.form1.j14_codigo.value+'&funcao_js=parent.js_mostaruas';
        js_OpenJanelaIframe('','db_iframe_matricula',sUrl,'Pesquisa',false);
        
       } else {
          document.form1.j14_nome.value         = '';
          document.form1.j14_codigo.value       = '';
    }
  }  
}

function js_mostaruas(chave,erro){
    
    document.form1.j14_nome.value = chave;
     
    if (erro==true) {
         
      document.form1.j14_codigo.focus(); 
      document.form1.j14_nome.value         = '';
      document.form1.j14_codigo.value       = '';       
    }
  }
  
  function js_mostaruas1(chave1,chave2){
    document.form1.j14_codigo.value = chave1;
    document.form1.j14_nome.value = chave2;
    db_iframe_matricula.hide();
  }     

  /////////////////////////////////////////////////////////

function js_mostrartipoaverba(lMostra){

	if (lMostra) {

	var sUrl = 'func_averbatipo?funcao_js=parent.js_mostatipoaverba1|j93_codigo|j93_descr';
   js_OpenJanelaIframe('','db_iframe_matricula',sUrl,'Pesquisa',true);
                
} else {

	  
if (document.form1.j93_codigo.value != ''){ 

 var sUrl = 'func_averbatipo.php?pesquisa_chave='+document.form1.j93_codigo.value+'&funcao_js=parent.js_mostatipoaverba';
 js_OpenJanelaIframe('','db_iframe_matricula',sUrl,'Pesquisa',false);
     
   } else {
      document.form1.j93_descr.value        = '';  
      document.form1.j93_codigo.value       = '';
   }
 }  
}

function js_mostatipoaverba(chave,erro){
    document.form1.j93_descr.value = chave; 
    
    if (erro==true) {
         
      document.form1.j93_codigo.focus();  
      document.form1.j93_descr.value        = '';  
      document.form1.j93_codigo.value       = '';
     }
  }
  
  function js_mostatipoaverba1(chave1,chave2){
    document.form1.j93_codigo.value = chave1;
    document.form1.j93_descr.value = chave2;
    db_iframe_matricula.hide();
  }    

 /////////////////////////////////////////////////////////

function js_enviadados(){

 var sUrl1 = 'cad2_relaverbacao002.php?z01_numcgm='+document.form1.z01_numcgm.value+'&z01_nome='+document.form1.z01_nome.value;
     sUrl1+= '&z01_nome='+document.form1.z01_nome.value;
     sUrl1+= '&j01_matric='+document.form1.j01_matric.value;
     sUrl1+= '&z01_nomepropri='+document.form1.z01_nomepropri.value;
     sUrl1+= '&j75_codigo='+document.form1.j75_codigo.value;
     sUrl1+= '&j14_codigo='+document.form1.j14_codigo.value;
     sUrl1+= '&j14_nome='+document.form1.j14_nome.value;
     sUrl1+= '&j93_codigo='+document.form1.j93_codigo.value;
     sUrl1+= '&j93_descr='+document.form1.j93_descr.value;
     sUrl1+= '&processados='+document.form1.processados.value;
     sUrl1+= '&dataini='+document.form1.dataini_ano.value+'-'+document.form1.dataini_mes.value+'-'+document.form1.dataini_dia.value;
     sUrl1+= '&datafim='+document.form1.datafim_ano.value+'-'+document.form1.datafim_mes.value+'-'+document.form1.datafim_dia.value;
     sUrl1+= '&ordem='+document.form1.ordem.value;
	  
    jan = window.open(sUrl1,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
    jan.moveTo(0,0);
  }
                        
</script>