<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2012  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_orcdotacao_classe.php");
require_once("libs/db_liborcamento.php");
require_once("classes/db_orcparametro_classe.php");
db_postmemory($HTTP_POST_VARS);
db_postmemory($HTTP_GET_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clorcdotacao   = new cl_orcdotacao;
$clestrutura    = new cl_estrutura;
$clorcparametro = new cl_orcparametro;
$clorcdotacao->rotulo->label("o58_anousu");
$clorcdotacao->rotulo->label("o58_coddot");
$clorcdotacao->rotulo->label("o58_orgao");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<br>
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">

  <tr> 
    <td align="center" valign="top"> 
<?
if(!isset($pesquisa_chave)){
  
  if(isset($campos)==false){
     if(file_exists("funcoes/db_func_orcdotacao.php")==true){
       require_once("funcoes/db_func_orcdotacao.php");
     }else{
	    $campos = "orcdotacao.*";
     }
  }
  
  $dbwhere = '';

  /* quando a instituição é prefeitura, é permitido selecionar dotações de outras instituições */
  $where_instit = "o58_instit=".db_getsession("DB_instit");
  $sql_instit = "select prefeitura  /* campo boolean */
                 from db_config 
		 where codigo = ".db_getsession("DB_instit");
  $res_instit = $clorcdotacao->sql_record($sql_instit);   
  if ($clorcdotacao->numrows !=0){
      db_fieldsmemory($res_instit,0);
      if  ($prefeitura =='t')
	$where_instit ="1=1 ";
  }    
  /* --- */  
  
  $sql  = " select fc_estruturaldotacao({$iAnoUsu} ,o58_coddot) as dl_estrutural,                                     ";
  $sql .= "          o56_elemento,                                                                                    ";
	$sql .= "          o55_descr::text,                                                                                 ";
	$sql .= "          e.o56_descr,                                                                                     ";
	$sql .= "          o58_coddot,                                                                                      ";
	$sql .= "          o58_instit                                                                                       ";
  $sql .= "         from orcdotacao d                                                                                 ";
 	$sql .= "        inner join orcprojativ p on p.o55_anousu ={$iAnoUsu} and p.o55_projativ = d.o58_projativ           ";
	$sql .= "        inner join orcelemento e on e.o56_codele = d.o58_codele and o56_anousu = o58_anousu                ";
  $sql .= "         where  $where_instit                                                                              ";
  $sql .= "          and substr(o56_elemento,1, 7) = '{$iElemento}'                                                   ";
  $sql .= "          and o58_anousu                = {$iAnoUsu}                                                       ";
  $sql .= "          $dbwhere                                                                                         ";
  $sql .= "         order by dl_estrutural                                                                            ";

  db_lovrot($sql,15,"()","",$funcao_js);
   
}else{
  if($pesquisa_chave!=null && $pesquisa_chave!=""){
      // Dim result as RecordSet
      $result = $clorcdotacao->sql_record($clorcdotacao->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
      if($clorcdotacao->numrows!=0){
         db_fieldsmemory($result,0);
         echo "<script>".$funcao_js."('$o56_descr',false);</script>";
      }else{
         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true);</script>";
      }
  }else{
       echo "<script>".$funcao_js."('',false);</script>";
  }
}
?>
     </td>
   </tr>
</table>
</form>
</body>
</html>
<?
if(!isset($pesquisa_chave)){
  ?>
<script>
</script>
  <?
}
?>