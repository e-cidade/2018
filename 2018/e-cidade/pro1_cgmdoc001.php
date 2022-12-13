<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2014  DBSeller Servicos de Informatica             
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
include("classes/db_cgmdoc_classe.php");
include("dbforms/db_funcoes.php");
include("classes/db_cgm_classe.php");
db_postmemory($HTTP_POST_VARS);
$clcgmdoc = new cl_cgmdoc;
$clcgm = new cl_cgm;
$db_opcao = 1;
$db_botao = true;

if ( isset($z02_i_cgm) && $z02_i_cgm != '' ) {

  $result = $clcgmdoc->sql_record($clcgmdoc->sql_query("","*","","z02_i_cgm = $z02_i_cgm"));
  if( $clcgmdoc->numrows > 0 ){
   db_fieldsmemory($result,0);
   $db_opcao = 2;
  }
}

if(isset($incluir)){
 db_inicio_transacao();
 $clcgmdoc->incluir($z02_i_sequencial);
 db_fim_transacao();
}
if(isset($alterar)){
 db_inicio_transacao();
 $clcgmdoc->alterar($z02_i_sequencial);
 db_fim_transacao();
}
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <br>
   <center>
    <?include("forms/db_frmcgmdoc.php");?>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","z02_i_pis",true,1,"z02_i_pis",true);
</script>
<?
if(isset($incluir) || isset($alterar)){
 if($clcgmdoc->erro_status=="0"){
  $clcgmdoc->erro(true,false);
  $db_botao=true;
  echo "<script> document.form1.db_opcao.disabled=false;</script>  ";
  if($clcgmdoc->erro_campo!=""){
   echo "<script> document.form1.".$clcgmdoc->erro_campo.".style.backgroundColor='#99A9AE';</script>";
   echo "<script> document.form1.".$clcgmdoc->erro_campo.".focus();</script>";
  }
 }else{
  $clcgmdoc->erro(true,false);
  db_redireciona("pro1_cgmdoc001.php?z02_i_cgm=$z02_i_cgm&z01_nome=$z01_nome");
 }
}
?>