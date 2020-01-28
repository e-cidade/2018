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
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");

db_postmemory($HTTP_POST_VARS);
$oGet = db_utils::postMemory($_GET);
$iTipo = "pad";
if (isset($oGet->tipo) && $oGet->tipo == "mgs") {
  $iTipo = "mgs";
}

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">

<script>
 function js_marcatodos(){
   doc = document.form1;
   for(x=0; x < doc.length;x++){ 
     if (doc[x].type =='checkbox'){
        doc[x].checked = true;
     } 
   }
 } 
 function js_limpatodos(){
   doc = document.form1;
   for(x=0; x < doc.length;x++){ 
     if (doc[x].type =='checkbox'){
        doc[x].checked = false;
     } 
   }
 }
 function js_seleciona(sTipo){
   doc = document.form1;
   var lista ='';
   for(x=0; x < doc.length; x++){
     if (doc[x].type =='checkbox'){
        if (doc[x].checked == true){
	  lista = doc[x].name+'.'+lista; 
	}  
     } 
   }
   // pega datas , dependendo d tipo passado 
   if (sTipo == "pad") {

     if(doc.periodopad.value == '1'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-01-31';
       data_pro = '<?=db_getsession("DB_anousu")?>-02-28';
     }else if(doc.periodopad.value == '2'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       dia = Calendar_get_daysofmonth(1,<?=db_getsession("DB_anousu")?>);
       data_fim = '<?=db_getsession("DB_anousu")?>-02-'+dia;
       data_pro = '<?=db_getsession("DB_anousu")?>-03-31';
     }else if(doc.periodopad.value == '3'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-03-31';
       data_pro = '<?=db_getsession("DB_anousu")?>-04-30';
     }else if(doc.periodopad.value == '4'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-04-30';
       data_pro = '<?=db_getsession("DB_anousu")?>-05-31';
     }else if(doc.periodopad.value == '5'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-05-31';
       data_pro = '<?=db_getsession("DB_anousu")?>-06-30';
     }else if(doc.periodopad.value == '6'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-06-30';
       data_pro = '<?=db_getsession("DB_anousu")?>-07-31';
     }else if(doc.periodopad.value == '7'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-07-31';
       data_pro = '<?=db_getsession("DB_anousu")?>-08-31';
     }else if(doc.periodopad.value == '8'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-08-31';
       data_pro = '<?=db_getsession("DB_anousu")?>-09-30';
     }else if(doc.periodopad.value == '9'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-09-30';
       data_pro = '<?=db_getsession("DB_anousu")?>-10-31';
     }else if(doc.periodopad.value == '10'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-10-31';
       data_pro = '<?=db_getsession("DB_anousu")?>-11-30';
     }else if(doc.periodopad.value == '11'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-11-30';
       data_pro = '<?=db_getsession("DB_anousu")?>-12-31';
     }else if(doc.periodopad.value == '12'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-12-31';
       data_pro = '<?=db_getsession("DB_anousu")+1?>-01-01';
     }

   } else if (sTipo == "mgs") {

     if(doc.periodopad.value == '1'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-01-31';
       data_pro = '<?=db_getsession("DB_anousu")?>-02-28';
     }else if(doc.periodopad.value == '2'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       dia = Calendar_get_daysofmonth(1,<?=db_getsession("DB_anousu")?>);
       data_fim = '<?=db_getsession("DB_anousu")?>-02-'+dia;
       data_pro = '<?=db_getsession("DB_anousu")?>-03-31';
     }else if(doc.periodopad.value == '3'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-03-31';
       data_pro = '<?=db_getsession("DB_anousu")?>-04-30';
     }else if(doc.periodopad.value == '4'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-04-30';
       data_pro = '<?=db_getsession("DB_anousu")?>-05-31';
     }else if(doc.periodopad.value == '5'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-05-31';
       data_pro = '<?=db_getsession("DB_anousu")?>-06-30';
     }else if(doc.periodopad.value == '6'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-06-30';
       data_pro = '<?=db_getsession("DB_anousu")?>-07-31';
     }else if(doc.periodopad.value == '7'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-07-31';
       data_pro = '<?=db_getsession("DB_anousu")?>-08-31';
     }else if(doc.periodopad.value == '8'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-08-31';
       data_pro = '<?=db_getsession("DB_anousu")?>-09-30';
     }else if(doc.periodopad.value == '9'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-09-30';
       data_pro = '<?=db_getsession("DB_anousu")?>-10-31';
     }else if(doc.periodopad.value == '10'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-10-31';
       data_pro = '<?=db_getsession("DB_anousu")?>-11-30';
     }else if(doc.periodopad.value == '11'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-11-30';
       data_pro = '<?=db_getsession("DB_anousu")?>-12-31';
     }else if(doc.periodopad.value == '12'){
       data_ini = '<?=db_getsession("DB_anousu")?>-01-01';
       data_fim = '<?=db_getsession("DB_anousu")?>-12-31';
       data_pro = '<?=db_getsession("DB_anousu")+1?>-01-01';
     }


   }
   iframe_processapad.location.href='con4_processapad.php?processar=true&tipo=mgs&data_pro='+data_pro+'&data_ini='+data_ini+'&data_fim='+data_fim+'&lista='+lista;

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
<table width="790" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="430" align="left" valign="top" bgcolor="#CCCCCC"> 
    <center>
    <?
	include("forms/db_frmcongeramsg.php");
    ?>
    </center>
	</td>
  </tr>
</table>
<? db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit")); ?>
</body>
</html>