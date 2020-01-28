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
include("classes/db_db_versaotarefa_classe.php");

parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_versaotarefa = new cl_db_versaotarefa;
?>
<html>
<head>
<title>DBSeller Inform&aacute;tica Ltda - P&aacute;gina Inicial</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta http-equiv="Expires" CONTENT="0">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
<link href="estilos.css" rel="stylesheet" type="text/css">
<script>
function js_mostratarefa(codigo){
  
 js_OpenJanelaIframe('','db_iframe_tarefa','ate2_contarefa001.php?menu=false&chavepesquisa='+codigo);

}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" onLoad="js_trocacordeselect()" >
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC"> 
    <form name='form1' method='post'>
    <input name='pesquisa_tarefas' value='<?=(!isset($pesquisa_tarefas) || $pesquisa_tarefas=='Tarefas'?'Procedimento':'Tarefas')?>' type='submit' >
    </form>
  </td>
  </tr>
  <tr> 
    <td height="100%" align="center" valign="top" bgcolor="#CCCCCC"> 
    <?
    if( !isset($pesquisa_tarefas) || $pesquisa_tarefas=='Tarefas'  ){
      if(!isset($filtro_query)){
        $sql = $cldb_versaotarefa->sql_query( null,'db29_seqvertar,db29_tarefa,at40_descr',null," db29_codver = $db29_codver ");
      }
      $jspesquisa='js_mostratarefa|db29_tarefa';
    }else{
      if(!isset($filtro_query)){
        $sql = $cldb_versaotarefa->sql_query_proced( null,'nomemod,descrproced,count(*) as dl_Quantidade','nomemod'," db29_codver = $db29_codver ", "nomemod,descrproced");
      }    
      $jspesquisa='';
    }
    db_lovrot(@$sql,30,'()','',$jspesquisa);
    ?>
	</td>
  </tr>
</table>
</body>
</html>