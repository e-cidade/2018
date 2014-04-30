<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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
$clrotulo->label("k02_codigo");
$clrotulo->label("k02_drecei");
?>
<html>
<head>
  <title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <meta http-equiv="Expires" CONTENT="0">
  <script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
  <link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#cccccc" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<script>
function abra(){
 window.open("cai2_relrecibo002.php?k02_codigo="+document.form1.k02_codigo.value+"&dataini="+document.form1.data1_ano.value+'-'+document.form1.data1_mes.value+'-'+document.form1.data1_dia.value+'&datafim='+document.form1.data2_ano.value+'-'+document.form1.data2_mes.value+'-'+document.form1.data2_dia.value+"&ordem="+document.form1.ordem.value+"&busca="+document.form1.busca.value,"Relatório","toolbar=no,menubar=no,scrollbars=no,resizable=yes,location=no,directories=no,status=no");
}
function js_pesquisatabrec(mostra){
     if(mostra==true){
       js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?funcao_js=parent.js_mostratabrec1|k02_codigo|k02_drecei','Pesquisa',true,'15');
     }else{
       js_OpenJanelaIframe('top.corpo','db_iframe_tabrec','func_tabrec.php?pesquisa_chave='+document.form1.k02_codigo.value+'&funcao_js=parent.js_mostratabrec','Pesquisa',false);
     }
}
function js_mostratabrec(chave,erro){
  document.form1.k02_drecei.value = chave;
  if(erro==true){
     document.form1.k02_codigo.focus();
     document.form1.k02_codigo.value = '';
  }
}
function js_mostratabrec1(chave1,chave2){
     document.form1.k02_codigo.value = chave1;
     document.form1.k02_drecei.value = chave2;
     db_iframe_tabrec.hide();
}
</script>
<table width="790" height='18'  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>
<br>
<table border="0" cellpadding="0" align="center" cellspacing="0" bgcolor="#cccccc"><br><br>
  <form name="form1" method="post">
  <tr> 
    <td align="right">
      <br>
      <?
      db_ancora(@$Lk02_codigo,"js_pesquisatabrec(true);",4);
      ?>
    </td>
    <td align="right"> 
      <br>
      <?
      db_input('k02_codigo',4,$Ik02_codigo,true,'text',4,"onchange='js_pesquisatabrec(false);'");
      db_input('k02_drecei',40,$Ik02_drecei,true,'text',3);
      ?>
    </td>
  </tr>
  <tr>
    <td align="right">
      <br>
      <b> Período: </b> 
    </td>
    <td>
      <br>
      <?
      db_inputdata('data1','','','',true,'text',1,"");
      echo "<b> a</b>";
      db_inputdata('data2','','','',true,'text',1,"");
      ?>
      &nbsp;
    </td>
  </tr>
  <tr>
    <td align="right">
      <br>
      <strong>Ordenar por:</strong>
    </td>
    <td>
      <br>
      <? 
      $tipo_ordem = array("n"=>"Nome","d"=>"Data de operação","e"=>"Data de pagamento");
      db_select("ordem",$tipo_ordem,true,2); 
      ?>
    </td>
  </tr>
  <tr>
    <td align="right">
      <br>
      <strong>Situação:</strong>
    </td>
    <td>
      <br>
      <? 
      $tipo_busca = array("t"=>"Todos","p"=>"Pagos","n"=>"Não Pagos");
      db_select("busca",$tipo_busca,true,2); 
      ?>
    </td>
  </tr>
  <tr>
    <td height="40" align="center" colspan="2">
      <input type="button" name="abrir" value="Gerar Relatório" onclick="abra()">
    </td>
  </tr>
  </form>
</table>  
</body>
</html>
<? 
db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>