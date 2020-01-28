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
include("classes/db_pcparam_classe.php");
$clpcparam = new cl_pcparam;
$clrotulo = new rotulocampo;
$clrotulo->label("pc10_numero");
$db_opcao = 1;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_abre(){
   obj = document.form1;
   query='';
   query += "&ini="+obj.pc10_numero_ini.value;
   query += "&fim="+obj.pc10_numero_fim.value;
   query += "&departamento=<?=db_getsession("DB_coddepto")?>";
   jan = window.open('com2_compilacaoregistro002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');
   <?
   /*
   $result_emissao = $clpcparam->sql_record($clpcparam->sql_query_file(db_getsession("DB_instit"),"pc30_tipoemiss"));
   if($clpcparam->numrows>0){
     db_fieldsmemory($result_emissao,0);
   }else{
     echo "alert('Usuário:\\n\\nParâmetros do módulo compras não configurados.\\n\\nAdministrador:');";
   }
   if(isset($pc30_tipoemiss) && trim($pc30_tipoemiss)!=""){
     if($pc30_tipoemiss=="t"){
       echo "jan = window.open('com2_emitesolicita002.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');";
     }else{
       echo "jan = window.open('com2_emitesolicita003.php?'+query,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0');";
     }
     echo "jan.moveTo(0,0);";
   }
	*/
   ?>

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onload="document.form1.pc10_numero_ini.focus();" >
<table width="790" height='18'  border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
  <tr> 
    <td width="360">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>

<table valign="top" marginwidth="0" width="300" border="0" cellspacing="0" cellpadding="0" style="margin-top: 15px;" align="center">
<tr align="center">
<td>
	<fieldset>
		<legend><b>Emite Compilação</b></legend>
	
	<table valign="top" marginwidth="0" width="300" border="0" cellspacing="0" cellpadding="0">
 <tr> 
  <td  align="center" valign="top" bgcolor="#CCCCCC"> 
    <form name='form1'>
    <table>    
      <tr>
	<td nowrap title="<?=@$Tpc10_numero?>">
	   <strong>Solicitações de </strong>
	</td>
	<td> 
	   <? db_input('pc10_numero',8,$Ipc10_numero,true,'text',$db_opcao," onchange='js_copiacampo();'","pc10_numero_ini")  ?>
	</td>
	<td> <strong> à </strong></td> 
	<td> 
	   <? db_input('pc10_numero',8,$Ipc10_numero,true,'text',$db_opcao,"","pc10_numero_fim")  ?>
	</td>
      </tr>
     <tr>
       <td colspan='4' align='center'>
         <input name='pesquisar' type='button' value='Gerar relatório' onclick='js_abre();'>      
       </td>
     </tr>
    </table>    
    </form>
  </td>
 </tr>
</table>
</fieldset>
</td>
</tr>
</table>
    <? 
      db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
    ?>
</body>
</html>
<script>
function js_copiacampo(){
  if(document.form1.pc10_numero_fim.value== ""){
    document.form1.pc10_numero_fim.value = document.form1.pc10_numero_ini.value;
  }
  document.form1.pc10_numero_fim.focus();
}
</script>