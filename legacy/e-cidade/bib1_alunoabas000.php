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

require("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("dbforms/db_classesgenericas.php");
$clcriaabas = new cl_criaabas;
$db_opcao = 1;
function NomeAluno($aluno){
 if($aluno!=""){
  $result = pg_query("SELECT ed47_v_nome FROM aluno WHERE ed47_i_codigo = $aluno");
  return trim(pg_result($result,0,0));
 }else{
  return "";
 }
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
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" >
<form name="formaba">
<table valign="top" marginwidth="0" width="100%" border="0" cellspacing="0" cellpadding="0">
 <tr>
  <td align="left" valign="top" bgcolor="#CCCCCC">
   <?
   $clcriaabas->identifica = array("a1"=>"Dados Pessoais","a2"=>"Documentos");
   $clcriaabas->sizecampo  = array("a1"=>"15","a2"=>"10");
   $clcriaabas->src        = array("a1"=>"bib1_alunodados002.php?leitor&chavepesquisa=$chavepesquisa","a2"=>"");
   $clcriaabas->disabled   = array("a2"=>"true");
   $clcriaabas->cordisabled = "#9b9b9b";
   $clcriaabas->iframe_height = "600";
   $clcriaabas->iframe_width = "100%";
   $clcriaabas->cria_abas();
   ?>
  </td>
 </tr>
</table>
</form>
</body>
</html>
<script>
 parent.db_iframe_alteradados.liberarJanBTFechar('false');
 parent.db_iframe_alteradados.liberarJanBTMinimizar('false');
 parent.db_iframe_alteradados.liberarJanBTMaximizar('false');
</script>