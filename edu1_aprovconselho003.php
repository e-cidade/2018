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

require("libs/db_stdlib.php");
require("libs/db_utils.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("classes/db_aprovconselho_classe.php");
include("classes/db_regencia_classe.php");
include("classes/db_diariofinal_classe.php");
include("dbforms/db_funcoes.php");
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
db_postmemory($HTTP_POST_VARS);
$claprovconselho = new cl_aprovconselho;
$cldiariofinal = new cl_diariofinal;
$clregencia = new cl_regencia;
$db_botao = false;
$db_opcao = 33;
if(isset($excluir)){
 $db_opcao = 3;
 db_inicio_transacao();
 $claprovconselho->excluir($ed253_i_codigo);
 $result2 = $cldiariofinal->sql_record($cldiariofinal->sql_query_file("","ed74_i_codigo",""," ed74_i_diario = $ed253_i_diario"));
 db_fieldsmemory($result2,0);
 $cldiariofinal->ed74_c_resultadofinal = "R";
 $cldiariofinal->ed74_i_codigo = $ed74_i_codigo;
 $cldiariofinal->alterar($ed74_i_codigo);
 db_fim_transacao();
 $claprovconselho->erro(true,false);
 ?>
 <script>
  parent.parent.location.href = "edu1_diariofinal001.php?regencia=<?=$regencia?>&iTrocaTurma=<?=$iTrocaTurma?>";
  parent.parent.db_iframe_alteraresultado.hide();
 </script>
 <?
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
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="a=1" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td height="430" align="left" valign="top" bgcolor="#CCCCCC">
   <center>
    <?include("forms/db_frmaprovconselhoexc.php");?>
   </center>
  </td>
 </tr>
</table>
</body>
</html>
<script>
js_tabulacaoforms("form1","ed253_i_diario",true,1,"ed253_i_diario",true);
</script>