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

$clrotulo = new rotulocampo;
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");


?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_pesquisa(){
  js_OpenJanelaIframe('top.corpo','db_iframe_cgm','func_nome.php?funcao_js=parent.js_preenchepesquisa|z01_numcgm|z01_nome','Pesquisa',true);
}
function js_preenchepesquisa(numcgm,nome){
  document.form1.z01_numcgm.value = numcgm;
  document.form1.z01_nome.value = nome;
  db_iframe_cgm.hide();
}
function js_verifica(){

  if(document.form1.z01_numcgm.value == ''){
    alert('Número do CGM não encontrado.');
    return false;
  }
 
  document.form1.target = 'safo' ;
  jan = window.open('','safo','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
  setTimeout("document.form1.submit()",1000);
 
}
function js_limpa(){
  document.form1.z01_numcgm.value = "";
  document.form1.z01_nome.value = "";
  document.form1.valor_anular.value = "";
  document.form1.historico_anular.value = "";
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
    <tr> 
      <td width="360" height="18">&nbsp;</td>
      <td width="263">&nbsp;</td>
      <td width="25">&nbsp;</td>
      <td width="140">&nbsp;</td>
    </tr>
  </table>
<form name="form1" method="post" action='emp2_anularreceita002.php' ><br><br>
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td nowrap align="right" title="<?=@$Tz01_numcgm?>">
      <?
      db_ancora($Lz01_numcgm,"js_pesquisa()",2)
      ?>        
    </td>
    <td align="left"> 
      <?
      db_input('z01_numcgm', 13, $Iz01_numcgm, true, 'text', 3);
      db_input('z01_nome', 40, $Iz01_nome, true, 'text', 3);
      ?>
    </td>
  </tr>
  <tr> 
    <td nowrap align="right" title="Valor a Anular"><strong>Valor do Anular:</strong>
    </td>
    <td align="left"> 
      <?
      db_input('valor_anular', 13, 4, true, 'text', 2)
      ?>
    </td>
  </tr>
   <tr> 
    <td nowrap align="right" title="Histórico">
    <strong>Histórico:</strong>
    </td>
    <td align="left"> 
      <?
      db_textarea('historico_anular', 6,60,0,true,'text', 2)
      ?>
    </td>
  </tr>
 
  <tr>
    <td colspan=2 align="center">
    <input name="pesquisa" value="Pesquisa" type="button" onclick='js_verifica()'>
    <input name="limpa" value="Novo" type="button" onclick='js_limpa()'>
    </td>
  </tr>
</table>
</form>
<?
db_menu(db_getsession("DB_id_usuario"), db_getsession("DB_modulo"), db_getsession("DB_anousu"), db_getsession("DB_instit"));
?>
</body>
</html>