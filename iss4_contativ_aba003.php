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

set_time_limit(0);
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_sql.php");
include("dbforms/db_funcoes.php");
include_once("dbforms/db_classesgenericas.php");

$cliframe_seleciona = new cl_iframe_seleciona;

db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_POST_VARS,2);// exit;
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
<table valign="top" border="0" cellspacing="1" cellpadding="0">
<tr align="top" >
<td width="43%" height="30" colspan="6" bordercolor="#FFFFCC"><div align="center"><font size="2">
</td>
</tr>
<tr align="top">
<td width="43%" height="30" colspan="6" bordercolor="#FFFFCC"><div align="center"><font size="2">

</td>
</tr>
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

  </td>
</tr>
<tr>
   <td>
        <?
        db_input('recruas1',"","","",'hidden',"","",'recruas1',"");
		db_input('recruas2',"","","",'hidden',"","",'recruas2',"");
		db_input('recruas3',"","","",'hidden',"","",'recruas3',"");
		db_input('recruas4',"","","",'hidden',"","",'recruas4',"");
		db_input('recruas5',"","","",'hidden',"","",'recruas5',"");
		db_input('recruas6',"","","",'hidden',"","",'recruas6',"");
		db_input('recruas7',"","","",'hidden',"","",'recruas7',"");
		db_input('recruas8',"","","",'hidden',"","",'recruas8',"");
		db_input('recruas9',"","","",'hidden',"","",'recruas9',"");
		db_input('recruas10',"","","",'hidden',"","",'recruas10',"");
        ?>
   </td>
</tr>
<?
      @$chaves2 = @$dados1.@$dados2.@$dados3.@$dados4.@$dados5.@$dados6.@$dados7.@$dados8.@$dados9.@$dados10;
      $sql = "select distinct ruas.j14_codigo, ruas.j14_nome
              from ruas
                  inner join issruas  on issruas.j14_codigo = ruas.j14_codigo
                  inner join tabativ  on tabativ.q07_inscr  = issruas.q02_inscr
	              inner join ativid   on ativid.q03_ativ    = tabativ.q07_ativ ";
//	  if(isset($chaves2) && trim($chaves2)!=""){
//   	    $sql.= " where q03_ativ in ($chaves2) ";
//	  }
	//  echo($sql)."<br><br>";
	$sql .= " order by ruas.j14_nome asc ";
	  $cliframe_seleciona->sql=$sql;
	  @$codigo = @$recruas1.@$recruas2.@$recruas3.@$recruas4.@$recruas5.@$recruas6.@$recruas7.@$recruas8.@$recruas9.@$recruas10;
      if(isset($codigo) && $codigo != ""){
	      $sql2 = "select distinct ruas.j14_codigo, ruas.j14_nome
	              from ruas
	                  inner join issruas  on issruas.j14_codigo = ruas.j14_codigo
	                  inner join tabativ  on tabativ.q07_inscr  = issruas.q02_inscr
		              inner join ativid   on ativid.q03_ativ    = tabativ.q07_ativ ";
		  if(isset($chaves2) && trim($chaves2)!=""){
	   	    $sql2.= " where ruas.j14_codigo in ($codigo)";
	   	    $sql2 .= " order by ruas.j14_nome asc ";
	   	    //q03_ativ in ($chaves2) and
		  }
    //  	  echo($sql2)."<br><br>";
     	  $cliframe_seleciona->sql_marca=$sql2;

      }
      $cliframe_seleciona->campos  = "j14_codigo,j14_nome";
      $cliframe_seleciona->legenda = "Ruas";
      $cliframe_seleciona->textocabec ="darkblue";
      $cliframe_seleciona->textocorpo ="black";
      $cliframe_seleciona->fundocabec ="#aacccc";
      $cliframe_seleciona->fundocorpo ="#ccddcc";
      $cliframe_seleciona->iframe_height ='400px';
      $cliframe_seleciona->iframe_width ='100%';
      $cliframe_seleciona->iframe_nome ="ruas";
      $cliframe_seleciona->chaves ="j14_codigo";
      $cliframe_seleciona->dbscript = "onClick='parent.js_mandaruas2();'";
      $cliframe_seleciona->js_marcador = 'parent.js_mandaruas2();';
      $cliframe_seleciona->iframe_seleciona($db_opcao);
?>
<script>
function js_mandaruas2(){

   var virgula   = '';
   var codruas   = '';
   var ruas1     = '';
   var ruas2     = '';
   var ruas3     = '';
   var ruas4     = '';
   var ruas5     = '';
   var ruas6     = '';
   var ruas7     = '';
   var ruas8     = '';
   var ruas9     = '';
   var ruas10    = '';
   var passa     = 'f';

   for(i = 0;i < ruas.document.form1.length;i++) {
        if(ruas.document.form1.elements[i].type == "checkbox" &&  ruas.document.form1.elements[i].checked){
	        codruas += virgula+ruas.document.form1.elements[i].value;
		    virgula = ', ';
		    passa = 't';

        }
    }
    ruas1  = codruas.substr(0,2000);
    ruas2  = codruas.substr(2000,2000);
    ruas3  = codruas.substr(4000,2000);
    ruas4  = codruas.substr(6000,2000);
    ruas5  = codruas.substr(8000,2000);
    ruas6  = codruas.substr(10000,2000);
    ruas7  = codruas.substr(12000,2000);
    ruas8  = codruas.substr(14000,2000);
    ruas9  = codruas.substr(16000,2000);
    ruas10 = codruas.substr(18000,2000);

    parent.iframe_g1.document.form1.recruas1.value  = ruas1;
	parent.iframe_g1.document.form1.recruas2.value  = ruas2;
	parent.iframe_g1.document.form1.recruas3.value  = ruas3;
	parent.iframe_g1.document.form1.recruas4.value  = ruas4;
	parent.iframe_g1.document.form1.recruas5.value  = ruas5;
	parent.iframe_g1.document.form1.recruas6.value  = ruas6;
	parent.iframe_g1.document.form1.recruas7.value  = ruas7;
	parent.iframe_g1.document.form1.recruas8.value  = ruas8;
	parent.iframe_g1.document.form1.recruas9.value  = ruas9;
	parent.iframe_g1.document.form1.recruas10.value = ruas10;

	document.form1.recruas1.value  = ruas1;
	document.form1.recruas2.value  = ruas2;
	document.form1.recruas3.value  = ruas3;
	document.form1.recruas4.value  = ruas4;
	document.form1.recruas5.value  = ruas5;
	document.form1.recruas6.value  = ruas6;
	document.form1.recruas7.value  = ruas7;
	document.form1.recruas8.value  = ruas8;
	document.form1.recruas9.value  = ruas9;
	document.form1.recruas10.value = ruas10;


   }
</script>