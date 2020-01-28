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
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_exemplar_classe.php");
include("classes/db_impexemplar_classe.php");
include("classes/db_impexemplaritem_classe.php");
include("dbforms/db_funcoes.php");
db_postmemory($HTTP_POST_VARS);
$clexemplar = new cl_exemplar;
$climpexemplar = new cl_impexemplar;
$climpexemplaritem = new cl_impexemplaritem;
$db_opcao = 1;
$db_botao = true;
$depto = db_getsession("DB_coddepto");
$sql = "SELECT bi17_codigo,bi17_nome FROM biblioteca WHERE bi17_coddepto = $depto";
$result = pg_query($sql);;
$linhas = pg_num_rows($result);
if($linhas!=0){
 db_fieldsmemory($result,0);
}
if(isset($processar)){
 db_inicio_transacao();
 $result2 = pg_query("DELETE FROM impexemplaritem WHERE bi25_impexemplar = $bi24_codigo AND bi25_exemplar in ($exemplares)");
 db_fim_transacao();
 $result3 = $climpexemplaritem->sql_record($climpexemplaritem->sql_query("","bi25_codigo",""," bi25_impexemplar = $bi24_codigo"));
 if($climpexemplaritem->numrows==0){
  $climpexemplar->excluir($bi24_codigo);
  db_msgbox("Todos exemplares desta impressão foram marcados como NÃO IMPRESSOS");
 }
 $bi24_data_dia = substr($bi24_data,0,2);
 $bi24_data_mes = substr($bi24_data,3,2);
 $bi24_data_ano = substr($bi24_data,6,4);
 db_redireciona("bib4_cancimpressao001.php");
 exit;
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<style>
.titulo{
 font-size: 11;
 color: #DEB887;
 background-color:#444444;
 font-weight: bold;
 border: 1px solid #f3f3f3;
}
.cabec1{
 font-size: 11;
 color: #000000;
 background-color:#999999;
 font-weight: bold;
}
.aluno{
 color: #000000;
 font-family : Tahoma;
 font-size: 10;
 font-weight: bold;
}
.aluno1{
 color: #000000;
 font-family : Tahoma;
 font-weight: bold;
 text-align: center;
 font-size: 10;
}
</style>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
<table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <tr>
  <td width="360" height="18">&nbsp;</td>
  <td width="263">&nbsp;</td>
  <td width="25">&nbsp;</td>
  <td width="140">&nbsp;</td>
 </tr>
</table>
<?MsgAviso(db_getsession("DB_coddepto"),"biblioteca",""," bi17_coddepto = ".db_getsession("DB_coddepto")."");?>
<br>
<center>
<fieldset align="center" style="width:95%"><legend><b>Cancelar Impressão de Etiquetas</b></legend>
<form name="form1" method="post" action="" onsubmit="return js_cancelarimp()">
<table align="center">
 <tr>
  <td >&nbsp;</td>
  <td >&nbsp;</td>
 </tr>
 <tr>
  <td align="left" nowrap title="Ordem Alfabética/Numérica" >
   <?db_ancora("<b>Impressão:</b>","js_pesquisabi24_codigo();",$db_opcao);?><br>
  </td>
  <td>
   <?db_input('bi24_codigo',10,@$Ibi06_aquisicao,true,'text',3,'')?>
   <?db_input('bi24_modelo',10,@$Ibi24_modelo,true,'text',3,"")?>
   <?db_inputdata('bi24_data',@$bi24_data_dia,@$bi24_data_mes,@$bi24_data_ano,true,'text',3,'')?>
   <?db_input('bi24_hora',5,@$Ibi24_hora,true,'text',3,"")?>
  </td>
 </tr>
 <?if(isset($bi24_codigo)&&$bi24_codigo!=""){?>
 <tr>
  <td colspan="2">
   <br>
   Exemplares impressos nesta impressão:<br>
   <table border="1" cellspacing="0" cellpading="2">
    <tr class="titulo">
     <td><input type="button" value="D" name="todos" onclick="js_marcatodos(this.value);" style="width:20px;"></td>
     <td>Código</td>
     <td>Exemplar</td>
     <td>Cód.Barras</td>
    </tr>
    <?
    $result = $climpexemplaritem->sql_record($climpexemplaritem->sql_query("","bi23_codigo,bi06_titulo,bi23_codbarras","bi06_titulo"," bi25_impexemplar = $bi24_codigo"));
    for($t=0;$t<$climpexemplaritem->numrows;$t++){
     db_fieldsmemory($result,$t);
     ?>
     <tr class="aluno">
      <td><input type="checkbox" value="<?=$bi23_codigo?>" name="marca" checked></td>
      <td><?=$bi23_codigo?></td>
      <td><?=$bi06_titulo?></td>
      <td><?=$bi23_codbarras?></td>
     </tr>
     <?
    }
    ?>
   </table>
  </td>
 </tr>
 <tr>
  <td colspan="2" align="center">
   <br><br>
   <?	
   	 db_input('exemplares', 10, true, 1, 'hidden');
   ?>
   <input type="submit" name="processar" value="Cancelar Impressão">
  </td>
 </tr>
 <?}?>
</table>
</form>
</fieldset>
</center>
<?db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));?>
</body>
</html>
<script>
function js_pesquisabi24_codigo(){
 js_OpenJanelaIframe('','db_iframe_impexemplar','func_impexemplar.php?funcao_js=parent.js_mostraimpexemplar1|bi24_codigo|bi24_data|bi24_hora|bi24_modelo','Pesquisa de Impressões',true);
}
function js_mostraimpexemplar1(chave1,chave2,chave3,chave4){
 data = chave2.split("-");
 document.form1.bi24_codigo.value = chave1;
 document.form1.bi24_data.value = data[2]+"/"+data[1]+"/"+data[0];
 document.form1.bi24_data_dia.value = data[2];
 document.form1.bi24_data_mes.value = data[1];
 document.form1.bi24_data_ano.value = data[0];
 document.form1.bi24_hora.value = chave3;
 document.form1.bi24_modelo.value = chave4;
 document.form1.submit();
 db_iframe_impexemplar.hide();
}
function js_marcatodos(valor){
 tam = document.form1.marca.length;
 tam = tam==undefined?1:tam;
 for(i=0;i<tam;i++){
  if(valor=="D"){
   if(tam==1){
    document.form1.marca.checked = false;
   }else{
    document.form1.marca[i].checked = false;
   }
  }else{
   if(tam==1){
    document.form1.marca.checked = true;
   }else{
    document.form1.marca[i].checked = true;
   }
  }
 }
 if(valor=="D"){
  document.form1.todos.value = "M";
 }else{
  document.form1.todos.value = "D";
 }
}
function js_cancelarimp(){
 if(confirm('Confirmar cancelamento da impressão de etiquetas para os ítens selecionados?')){
  if(document.form1.marca){
   tam = document.form1.marca.length;
   tam = tam==undefined?1:tam;
   exemplares = "";
   sep = "";
   for(i=0;i<tam;i++){
    if(tam==1){
     if(document.form1.marca.checked==true){
      exemplares += sep+document.form1.marca.value;
      sep = ",";
     }
    }else{
     if(document.form1.marca[i].checked==true){
      exemplares += sep+document.form1.marca[i].value;
      sep = ",";
     }
    }
   }

   if(exemplares==""){
    alert("Informe algum exemplar para cancelar a impressão!");
    return false;
   }

   //acerto temporario para resolver tamanho da url enviada por get
   document.form1.exemplares.value = exemplares;
   
  }else{
  }
 } else {
   return false;
 }
}
</script>