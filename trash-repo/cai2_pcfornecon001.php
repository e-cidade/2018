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
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");


//---  parser POST/GET
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);

//---- instancia classes

$aux = new cl_arquivo_auxiliar;

//--- cria rotulos e labels

//----
$cliframe_seleciona = new cl_iframe_seleciona;
$clrotulo = new rotulocampo;

?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<script>
function js_emite() {

 
   bloqueado=document.form1.bloqueado.value;
   ordem=document.form1.ordem.value;
   forne=document.form1.forne.value;
   
   jan = window.open('cai2_pcfornecon002.php?bloqueado='+bloqueado+'&ordem='+ordem+'&forne='+forne,'','width='+(screen.availWidth-5)+',height='+(screen.availHeight-40)+',scrollbars=1,location=0 ');
   jan.moveTo(0,0);
 
}
</script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor=#CCCCCC bgcolor="#CCCCCC"  >

 <table width="790" border="0" cellpadding="0" cellspacing="0" bgcolor="#5786B2">
 <!--
  <tr> 
    <td width="360" height="18">&nbsp;</td>
    <td width="263">&nbsp;</td>
    <td width="25">&nbsp;</td>
    <td width="140">&nbsp;</td>
  </tr>
</table>-->
      <br><br><br><br>
     <form name="form1" method="post" action="">
      <table border="0" align='center'>
          <tr>
          <td nowrap align='right'>
           <b>
             Bloqueados :
           </b>
           </td>
           <td nowrap>  <?
           $tipo_blo = array("t"=>"Todos","s"=>"Sim","b"=>"N�o");
	       db_select("bloqueado",$tipo_blo,true,2); ?>
            </td>
                   
                 </tr>
                 <tr>
          <td nowrap align='right'>
           <b>
             Ordem :
           </b>
           </td>
           <td nowrap>  <?
           $tipo_ordem = array("a"=>"Alfab�tica","n"=>"Numerica");
	       db_select("ordem",$tipo_ordem,true,2); ?>
            </td>
                   
                 </tr>
                 <tr>
          <td nowrap align='right'>
           <b>
             Fornecedores:
           </b>
           </td>
           <td nowrap>  <?
           $tipo = array("t"=>"Todos","c"=>"Com conta","s"=>"Sem conta");
	       db_select("forne",$tipo,true,2); ?>
            </td>
                   
                 </tr>
       <tr>
       <td colspan=2 align='center'>
       <input name="relatorio" type="button" value="Emitir Relat�rio" onClick="js_emite();">
       </td>
       </tr> 
       </table>
  
        
       
       <?
  
  db_menu(db_getsession("DB_id_usuario"),db_getsession("DB_modulo"),db_getsession("DB_anousu"),db_getsession("DB_instit"));
       ?>
       
       </form>
  
<script>
</script>
</body>
</html>