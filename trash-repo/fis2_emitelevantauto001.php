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


$clrotulo = new rotulocampo;
$clrotulo->label("y60_codlev");
$clrotulo->label("z01_nome");
$clrotulo->label("z01_numcgm");
$clrotulo->label("q02_inscr");
$db_opcao = 1;
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="if(document.form1) document.form1.elements[0].focus()" >
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<table width="790" height="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
	<center>
        <form name="form1" method="post" action="">
          <table border="0" cellspacing="0" cellpadding="0">
      <tr>
      <br>
        <td nowrap title="<?=@$Tz01_numcgm?>">
        <?
          db_ancora(@$Lz01_numcgm,"js_pesquisaz01_numcgm(true);",$db_opcao);
        ?>
        </td>	
        <td>	
      <?
      db_input('z01_numcgm',6,$Iy60_codlev,true,'text',$db_opcao," onchange='js_pesquisaz01_numcgm(false);'");
      db_input('z01_nome',40,$Iz01_nome,true,'text',3);
         ?>
        </td>
      </tr>
      <tr>
      <br>
        <td nowrap title="<?=@$Tq02_inscr?>">
        <?
          db_ancora(@$Lq02_inscr,"js_mostrainscricao(true);",$db_opcao);
        ?>
        </td>	
        <td>	
      <?
      db_input('q02_inscr',6,$Iq02_inscr,true,'text',$db_opcao," onchange='js_mostrainscricao(false);'");
      db_input('z01_nome2',40,$Iz01_nome,true,'text',3);
         ?>
        </td>
      </tr>
      <tr>
      <br>
        <td nowrap title="<?=@$Ty60_codlev?>">
        <?
          db_ancora(@$Ly60_codlev,"js_lev(true);",$db_opcao);
        ?>
        </td>	
        <td>	
      <?
      db_input('y60_codlev',6,$Iy60_codlev,true,'text',$db_opcao," onchange='js_lev(false);'");
      db_input('z01_nome3',40,$Iz01_nome,true,'text',3);
         ?>
        </td>
      </tr>
      <tr>
      <br>
        <td>	
	<strong>Enquadramento legal :</strong>
        </td>
        <td>
        <?
          db_textarea("enqlegal","5","46","",true,"",2,"","","");
        ?>
        </td>	
      </tr>
      <tr>
      <br>
        <td>	
	<strong>Receita tributável :</strong>
        </td>
        <td>
        <?
          db_textarea("rectrib","5","46","",true,"",2,"","","");
        ?>
        </td>	
      </tr>
          <tr>
              <td colspan="2" align="center"  height="25" nowrap>
	      <input name="importar" type="button"  onClick="return js_mandadados();" value="Emite Levantamento">
	      </td>
              <td>
            </tr>
          </table>
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

function js_mandadados() {
  jan = window.open('fis2_emitelevantauto002.php?codlev='+document.form1.y60_codlev.value+'&numcgm='+document.form1.z01_numcgm.value+'&codinscr='+document.form1.q02_inscr.value+'&enqlegal='+document.form1.enqlegal.value+'&rectrib='+document.form1.rectrib.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  jan.moveTo(0,0);
}

function js_lev(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe','func_levanta02.php?todos=true&funcao_js=parent.js_mostralev1|y60_codlev|z01_nome','Pesquisa',true);
  }else{
    lev = document.form1.y60_codlev.value;
    if(lev != ''){
      js_OpenJanelaIframe('top.corpo','db_iframe','func_levanta02.php?todos=true&pesquisa_chave='+lev+'&funcao_js=parent.js_mostralev','Pesquisa',false);
    }else{
       document.form1.z01_nome3.value='';
    }     
  }
}

function js_pesquisaz01_numcgm(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?funcao_js=parent.js_mostranumcgm1|z01_numcgm|z01_nome','Pesquisa',true);
  }else{
    if(document.form1.z01_numcgm.value != ''){
       js_OpenJanelaIframe('top.corpo','func_nome','func_nome.php?pesquisa_chave='+document.form1.z01_numcgm.value+'&funcao_js=parent.js_mostranumcgm','Pesquisa',false);
     }else{
	document.form1.z01_nome.value = "";
     }
  }
}
function js_mostranumcgm1(chave1,chave2)
{
  document.form1.z01_numcgm.value = chave1;
  document.form1.z01_nome.value   = chave2;
  func_nome.hide();
}
function js_mostranumcgm(erro,chave){
  document.form1.z01_nome.value = chave;
  if(erro==true){
    document.form1.z01_numcgm.value = '';
    document.form1.z01_numcgm.focus();
  }
}
function js_mostrainscricao(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','func_issbase','func_issbase.php?funcao_js=parent.js_preencheinscricao|0|1','Pesquisa');
  }else{
    if(document.form1.z01_numcgm2.value != ''){
       js_OpenJanelaIframe('top.corpo','func_issbase','func_issbase.php?pesquisa_chave='+document.form1.q02_inscr.value+'&funcao_js=parent.js_preencheinscricao','Pesquisa');
     }else{
	document.form1.z01_nome2.value = "";
     }
  }
}
function js_preencheinscricao(chave,chave1){
  document.form1.q02_inscr.value = chave;
  document.form1.z01_nome2.value = chave1;
  //func_nome.hide();
  func_issbase.hide();
}

function js_mostralev(chave,erro){
  if(erro==true){ 
    alert('Levantamento inválido.');  
    document.form1.y60_codlev.value=""; 
    document.form1.y60_codev.focus(); 
  } else{
    document.form1.z01_nome3.value = chave;
  } 
}
function js_mostralev1(chave1,chave2){
  document.form1.y60_codlev.value = chave1;
  document.form1.z01_nome3.value = chave2;
  db_iframe.hide();
}
</script>