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
include("libs/db_usuariosonline.php");
include("classes/db_setor_classe.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
include("classes/db_sanitario_classe.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$clsetor = new cl_setor;
$cliframe_seleciona = new cl_iframe_seleciona;
$clsetor->rotulo->label();
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
<form name="form1" method="post" action="" target="rel">
<center>
<table border="0">
  <tr>
    <td align="top" colspan="2">
   <?
      $cliframe_seleciona->campos  = "j30_codi,j30_descr";
      $cliframe_seleciona->legenda="LOTES";
      $cliframe_seleciona->sql=$clsetor->sql_query(""," * ","j30_codi");
      $cliframe_seleciona->textocabec ="darkblue";
      $cliframe_seleciona->textocorpo ="black";
      $cliframe_seleciona->fundocabec ="#aacccc";
      $cliframe_seleciona->fundocorpo ="#ccddcc";
      $cliframe_seleciona->iframe_height ="250";
      $cliframe_seleciona->iframe_width ="700";
      $cliframe_seleciona->iframe_nome ="setor";
      $cliframe_seleciona->chaves ="j30_codi,j30_descr";
      $cliframe_seleciona->dbscript ="onClick='parent.js_nome(this)'";
      $cliframe_seleciona->js_marcador="parent.js_nome()";
      $cliframe_seleciona->iframe_seleciona(@$db_opcao);    
   ?>
   </td>
 </tr>
 <script>
 function js_nome(obj){
 
   var j34_setor = "";
   var vir 		 = "";
   var x         = 0;
   
   for(i=0;i<setor.document.form1.length;i++){
   
     if(setor.document.form1.elements[i].type == "checkbox"){
    
       if(setor.document.form1.elements[i].checked == true){
       
         valor = setor.document.form1.elements[i].value.split("_")
		 j34_setor += vir + valor[0];
		 vir  	    = ",";
		 x   	   += 1; 
       }
     }
   }
   
   /*if(x == 1){
     parent.iframe_g4.location.href = '../cad2_iptuconstr006.php?j34_setor='+j34_setor;
     parent.document.formaba.g1.disabled = true;
     parent.document.formaba.g2.disabled = true;
     parent.document.formaba.g3.disabled = true;
     parent.document.formaba.g5.disabled = true;
     parent.mo_camada('g4');
   }*/
   
   parent.iframe_g4.location.href = '../cad2_loteam006.php?j34_setor='+j34_setor;
   if(j34_setor == ""){
     parent.iframe_g1.document.form1.quadra.value = '';
     parent.iframe_g1.document.form1.setor.value = '';
   }
 }
 </script>
  </table>
  </center>
</form>
<script>
</script>
    </center>
	</td>
  </tr>
</table>
</body>
</html>