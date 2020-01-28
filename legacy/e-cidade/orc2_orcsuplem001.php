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
include("libs/db_liborcamento.php");
require("classes/db_orcsuplem_classe.php");  // declaração da classe orcreserva

$clorcsuplem = new cl_orcsuplem ; // instancia classe orcsuplem
$clorcsuplem->rotulo->label();



db_postmemory($HTTP_POST_VARS);
$db_opcao=1;
?>

<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>

<script>

function emite(){
  jan = window.open('orc2_orcsuplem002.php?o46_codlei='+document.form1.o46_codlei.value,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
  jan.moveTo(0,0);
 
}
</script>  
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" bgcolor="#cccccc">
  <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr>
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<br><br>
  <table  align="center">
   <form name="form1" method="post" action="" >
  
  <!---
    <tr>
      <td nowrap title="<?=@$To46_codsup?>"> <?=@$Lo46_codsup?> </td>
      <td> <? db_input('o46_codsup',4,$Io46_codsup,true,'text',$db_opcao) ?> </td> 
   </tr>
   --->
   <tr>
      <td nowrap title="<?=@$To46_codlei?>"><? db_ancora(@$Lo46_codlei,"",$db_opcao);  ?> </td>
      <td> <? db_input('o46_codlei',8,$Io46_codlei,true,'text',$db_opcao,"") ?>  </td>
  </tr>

 <!---
  <tr>
    <td nowrap title="<?=@$To46_tiposup?>">Tipo Suplementação :  </td>
    <td> <? db_input('o46_tiposup',8,$Io46_tiposup,true,'text',$db_opcao,""); ?>       
    </td>
  </tr>
  <tr>
     <td nowrap title="<?=@$To46_data?>">Data inicial </td>
     <td> <? db_inputdata('o46_data',@$o46_data_dia,@$o46_data_mes,@$o46_data_ano,true,'text',$db_opcao,"")?> </td>
  </tr>
  <tr>
     <td nowrap title="<?=@$To46_data?>"> Data_final :  </td>
     <td> <? db_inputdata('o46_data1',@$o46_data_dia,@$o46_data_mes,@$o46_data_ano,true,'text',$db_opcao,"")?> </td>
  </tr>
  --->

 <table>
   <center>
      <input name="emitir" type="button" value="Emitir" onclick="emite();">
   </center>
 </form>

<?
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
?>
</body>
</html>