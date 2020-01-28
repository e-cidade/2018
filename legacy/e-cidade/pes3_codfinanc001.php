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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("libs/db_sql.php");
parse_str($HTTP_SERVER_VARS['QUERY_STRING']);
$clrotulo = new rotulocampo;
$clrotulo->label('rh27_rubric');
$clrotulo->label('rh27_descr');
$clrotulo->label('DBtxt23');
$clrotulo->label('DBtxt25');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="if(document.form1.rh27_rubric)document.form1.rh27_rubric.focus();">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br>
<center>
<form name="form1" method="post">
 <fieldset style="width:440px;">
 <legend>Ficha Financeira por Código</legend>
 <table border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td align="left" valign="top" bgcolor="#CCCCCC">
	  <table border="0">
        <tr>
          <td align="left" nowrap title="Digite o Ano / Mes de competência" >
            <strong>Ano / Mês :&nbsp;&nbsp;</strong>
          </td>
          <td>
            <?
            $ano = db_anofolha();
            db_input('ano',4,$IDBtxt23,true,'text',2,'')
            ?>
            &nbsp;/&nbsp;
            <?
            $mes = db_mesfolha();
            db_input('mes',2,$IDBtxt25,true,'text',2,'')
            ?>
          </td>
        </tr>
        <tr> 
          <td align="right" title="<?=$Trh27_rubric?>"> 
            <?
            db_ancora(@ $Lrh27_rubric, "js_pesquisarrubric(true);", 1);
    		?>
          </td>
          <td> 
            <?
            db_input('rh27_rubric', 8, $Irh27_rubric, true, 'text', 1, " onchange='js_pesquisarrubric(false);'")
            ?>
            <?
            db_input('rh27_descr', 30, $Irh27_descr, true, 'text', 3, '');
            ?>
          </td>
        </tr>
        <tr> 
        </tr>
      </table>
    </td>
  </tr>
</table>
</fieldset>
<br>
      <input type="button" value="Pesquisar" name="pesquisar" onclick="js_abrejan();">
</form>
</center>
<? 
 db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>
<script>

function js_abrejan(){
  if(document.form1.rh27_rubric.value == ""){
  	alert("Informe rubrica a pesquisar.");
  }else{
    location.href = 'pes3_codfinanc002.php?rubrica='+document.form1.rh27_rubric.value+'&ano='+document.form1.ano.value+'&mes='+document.form1.mes.value;
  }
}
function js_pesquisarrubric(mostra){
  if(mostra==true){
    js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?funcao_js=parent.js_mostrarubricas1|rh27_rubric|rh27_descr','Pesquisa',true);
  }else{
     if(document.form1.rh27_rubric.value != ''){
       quantcaracteres = document.form1.rh27_rubric.value.length;
       for(i=quantcaracteres;i<4;i++){
         document.form1.rh27_rubric.value = "0"+document.form1.rh27_rubric.value;
       }
       js_OpenJanelaIframe('top.corpo','db_iframe_rhrubricas','func_rhrubricas.php?pesquisa_chave='+document.form1.rh27_rubric.value+'&funcao_js=parent.js_mostrarubricas','Pesquisa',false);
     }else{
       document.form1.rh27_descr.value = ''; 
     }
  }
}
function js_mostrarubricas(chave,erro){
  document.form1.rh27_descr.value  = chave;
  if(erro==true){
    document.form1.rh27_rubric.value = '';
    document.form1.rh27_rubric.focus();
  }
}
function js_mostrarubricas1(chave1,chave2){
  document.form1.rh27_rubric.value  = chave1;
  document.form1.rh27_descr.value  = chave2;
  db_iframe_rhrubricas.hide();
}
</script>