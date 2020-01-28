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

require_once ("libs/db_stdlib.php");
require_once ("libs/db_stdlibwebseller.php");
require_once ("libs/db_conecta.php");
require_once ("libs/db_sessoes.php");
require_once ("libs/db_usuariosonline.php");
require_once ("dbforms/db_funcoes.php");
require_once ("classes/db_leitor_classe.php");

$clleitor = new cl_leitor;
$clleitor->rotulo->label('bi10_codigo');
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<br>
<?MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");?>
<form name="form1" method="post">
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td valign="top" align="center">
   <br>
   <fieldset style="width:95%"><legend><b>Consulta de Empréstimos por Leitor</b></legend>
   <table border="0" cellpadding="0" cellspacing="0" bgcolor="#CCCCCC" align="center">
    <tr>
     <td nowrap><b>Informe o período: <small><i>(Opcional)</i></small></b></td>
    </tr>
    <tr>
     <td>
      &nbsp;&nbsp;&nbsp;
      De:
      <?db_inputdata('data_ini','','','',true,'text',1,"")?>
      Até:
      <?db_inputdata('data_fim','','','',true,'text',1,"")?>
     </td>
    </tr>
    <tr>
     <td nowrap><b>Selecione o leitor:</b></td>
    </tr>
    <tr>
     <td>
     &nbsp;&nbsp;&nbsp;
     <?db_ancora("<b>Carteira:</b>", "js_pesquisabi10_codigo(true);", 1);?>
     <?db_input('bi10_codigo', 10, @$Ibi10_codigo, true, 'text', 1, " onchange='js_pesquisabi10_codigo(false);'")?>
     <?db_input('ov02_nome', 40, @$Iov02_nome, true, 'text', 3, '')?>
     <input name="pesquisar" type="button" id="pesquisar" value="Pesquisar" onclick="js_pesquisa();">
     <br><br>
     </td>
    </tr>
   </table>
   <table width="100%">
    <tr height="350">
     <td align="center" colspan="3">
      <iframe src="bib3_leitorcons002.php" name="framemarca" id="framemarca" width="100%" height="100%" frameborder="0"></iframe>
     </td>
    </tr>
   </table>
   </fieldset>
  </td>
 </tr>
</table>
</form>
<script>
function js_pesquisa(){
  
  if (document.form1.bi10_codigo.value == "") {
    
    alert("Selecione um leitor para pesquisa");
    document.form1.bi10_codigo.style.backgroundColor="#99A9AE";
    document.form1.bi10_codigo.focus();
  } else {
    framemarca.location.href="bib3_leitorcons002.php?todos=false"
                           +"&valor="+document.form1.bi10_codigo.value
                           +"&data_ini="+document.form1.data_ini_ano.value
                                        +"-"+document.form1.data_ini_mes.value
                                        +"-"+document.form1.data_ini_dia.value
                           +"&data_fim="+document.form1.data_fim_ano.value
                                        +"-"+document.form1.data_fim_mes.value
                                        +"-"+document.form1.data_fim_dia.value;
  }
}

function js_pesquisabi10_codigo(mostra) {
  
  if(mostra == true) {
    js_OpenJanelaIframe('top.corpo',
                        'db_iframe_leitor',
                        'func_leitorproc.php?funcao_js=parent.js_mostranumcgm1|bi16_codigo|ov02_nome',
                        'Pesquisa',
                        true
                       );
  } else {
    
    if (document.form1.bi10_codigo.value != '') {
      js_OpenJanelaIframe('top.corpo',
                          'db_iframe_leitor',
                          'func_leitorproc.php?pesquisa_chave='+document.form1.bi10_codigo.value
                                            +'&funcao_js=parent.js_mostranumcgm',
                          'Pesquisa',
                          false
                         );
    } else {
      document.form1.bi10_codigo.value = '';
    }
  }
}

function js_mostranumcgm(chave,erro) {
  
  document.form1.ov02_nome.value = chave;
  if (erro == true) {
    
    document.form1.bi10_codigo.focus();
    document.form1.bi10_codigo.value = '';
    return false;
  }
  document.form1.pesquisar.onclick();
}

function js_mostranumcgm1(chave1,chave2) {
  
  document.form1.bi10_codigo.value = chave1;
  document.form1.ov02_nome.value   = chave2;
  document.form1.pesquisar.onclick();
  db_iframe_leitor.hide();
}
</script>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>