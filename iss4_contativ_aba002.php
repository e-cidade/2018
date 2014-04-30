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

set_time_limit(0);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include_once("dbforms/db_classesgenericas.php");

$cliframe_seleciona = new cl_iframe_seleciona;

//db_postmemory($HTTP_POST_VARS,2);//exit;
db_postmemory($HTTP_POST_VARS);
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
<style type="text/css">
th {
   font-family: Arial, Helvetica, sans-serif;
   font-size: 11px;
}
td {
   font-family: Arial, Helvetica, sans-serif;
   font-size: 11px;
}
</style>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<form name="form1" id="form1" method="post">
<input name="chaves1" id="emite" type="hidden" value="" onClick="">
<table valign="top" border="0" cellspacing="1" cellpadding="0">
<tr align="top">
  <td width="43%" height="30" colspan="6" bordercolor="#FFFFCC">
    <input name="ivar" id="emite" type="hidden" value="" onClick="">
    <input name="chaves2" id="emite" type="hidden" value="" onClick="">
    
    <input name="dados1"  id="emite" type="hidden"  value=""  onClick="">
    <input name="dados2"  id="emite" type="hidden"  value=""  onClick="">
    <input name="dados3"  id="emite" type="hidden"  value=""  onClick="">
    <input name="dados4"  id="emite" type="hidden"  value=""  onClick="">
    <input name="dados5"  id="emite" type="hidden"  value=""  onClick="">
    <input name="dados6"  id="emite" type="hidden"  value=""  onClick="">
    <input name="dados7"  id="emite" type="hidden"  value=""  onClick="">
    <input name="dados8"  id="emite" type="hidden"  value=""  onClick="">
    <input name="dados9"  id="emite" type="hidden"  value=""  onClick="">
    <input name="dados10" id="emite" type="hidden"  value=""  onClick="">
    <input name="teste1" id="teste1" type="hidden">
    
  </td>
</tr>
</table>
<?   
      $sql = "select q03_ativ,q03_descr from ativid inner join clasativ on q82_ativ = q03_ativ where q82_classe in ($chaves1) order by q03_ativ";
      //echo($sql)."<br><br>";
      $cliframe_seleciona->sql=$sql;
      @$codigo = @$dados1.@$dados2.@$dados3.@$dados4.@$dados5.@$dados6.@$dados7.@$dados8.@$dados9.@$dados10;
      if(isset($codigo) && $codigo != ""){
     	 $sql2 = "select q03_ativ,q03_descr from ativid inner join clasativ on q82_ativ = q03_ativ where q82_classe in ($chaves1) and q03_ativ in ($dados1) order by q03_ativ";
     	 //echo($sql2)."<br><br>";
     	 $cliframe_seleciona->sql_marca=$sql2;
      }
      $cliframe_seleciona->campos  = "q03_ativ,q03_descr";
      $cliframe_seleciona->legenda = "Atividades";
      $cliframe_seleciona->textocabec ="darkblue";
      $cliframe_seleciona->textocorpo ="black";
      $cliframe_seleciona->fundocabec ="#aacccc";
      $cliframe_seleciona->fundocorpo ="#ccddcc";
      $cliframe_seleciona->iframe_height ='400px';
      $cliframe_seleciona->iframe_width ='100%';
      $cliframe_seleciona->iframe_nome ="ativ";
      $cliframe_seleciona->chaves ="q03_ativ";
      $cliframe_seleciona->dbscript = "onClick='parent.js_mandadados2();'";
      $cliframe_seleciona->js_marcador = 'parent.js_mandadados2();';
      $cliframe_seleciona->iframe_seleciona($db_opcao);
?>


</form>
</body>
</html>
<script>

function js_mandadados2(){

   var virgula = '';
   var passa   = 'f';
   var dados   = '';
   var dados1  = '';
   var dados2  = '';
   var dados3  = '';
   var dados4  = '';
   var dados5  = '';
   var dados6  = '';
   var dados7  = '';
   var dados8  = '';
   var dados9  = '';
   var dados10 = '';
   
   
   for(i = 0;i < ativ.document.form1.length;i++) {
        if(ativ.document.form1.elements[i].type == "checkbox" &&  ativ.document.form1.elements[i].checked){
	        dados += virgula+ativ.document.form1.elements[i].value;
		    virgula = ', ';
		    passa = 't';
        }
    }
   
    dados1  = dados.substr(0,2000);
    dados2  = dados.substr(2000,2000);
    dados3  = dados.substr(4000,2000);
    dados4  = dados.substr(6000,2000);
    dados5  = dados.substr(8000,2000);
    dados6  = dados.substr(10000,2000);
    dados7  = dados.substr(12000,2000);
    dados8  = dados.substr(14000,2000);
    dados9  = dados.substr(16000,2000);
    dados10 = dados.substr(18000,2000);
    
    //alert(parent.iframe_g3.document.form1.dados1.value);
    
    if(passa == 'f'){
       //parent.document.formaba.g2.disabled = true;
       //parent.document.formaba.g3.disabled = true;
    }
    if(passa == 't'){
	   parent.document.formaba.g2.disabled = false;
	   parent.document.formaba.g3.disabled = false;
	   
	   //variaveis da aba 3
	   parent.iframe_g3.document.form1.dados1.value  = dados1;
	   parent.iframe_g3.document.form1.dados2.value  = dados2;
	   parent.iframe_g3.document.form1.dados3.value  = dados3;
	   parent.iframe_g3.document.form1.dados4.value  = dados4;
	   parent.iframe_g3.document.form1.dados5.value  = dados5;
	   parent.iframe_g3.document.form1.dados6.value  = dados6;
	   parent.iframe_g3.document.form1.dados7.value  = dados7;
	   parent.iframe_g3.document.form1.dados8.value  = dados8;
	   parent.iframe_g3.document.form1.dados9.value  = dados9;
	   parent.iframe_g3.document.form1.dados10.value = dados10;
	   
	   //variaveis da aba 1
	   parent.iframe_g1.document.form1.dados1.value  = dados1;
	   parent.iframe_g1.document.form1.dados2.value  = dados2;
	   parent.iframe_g1.document.form1.dados3.value  = dados3;
	   parent.iframe_g1.document.form1.dados4.value  = dados4;
	   parent.iframe_g1.document.form1.dados5.value  = dados5;
	   parent.iframe_g1.document.form1.dados6.value  = dados6;
	   parent.iframe_g1.document.form1.dados7.value  = dados7;
	   parent.iframe_g1.document.form1.dados8.value  = dados8;
	   parent.iframe_g1.document.form1.dados9.value  = dados9;
	   parent.iframe_g1.document.form1.dados10.value = dados10;
	   
	   //variaveis da aba 2
	   document.form1.dados1.value  = dados1;
	   document.form1.dados2.value  = dados2;
	   document.form1.dados3.value  = dados3;
	   document.form1.dados4.value  = dados4;
	   document.form1.dados5.value  = dados5;
	   document.form1.dados6.value  = dados6;
	   document.form1.dados7.value  = dados7;
	   document.form1.dados8.value  = dados8;
	   document.form1.dados9.value  = dados9;
	   document.form1.dados10.value = dados10;
	   
	   parent.iframe_g3.document.form1.submit();
	   
	   
   }
}
</script>