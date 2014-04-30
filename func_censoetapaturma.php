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

//MODULO: educação
include("libs/db_stdlibwebseller.php");
require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_censoetapa_classe.php");
db_postmemory($HTTP_POST_VARS);
$clcensoetapa = new cl_censoetapa;
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor="#CCCCCC" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
 <tr>
  <td align="center" valign="top">
   <?
   if(!isset($pesquisa_chave)){
    if(isset($campos)==false){
     if(file_exists("funcoes/db_func_censoetapa.php")==true){
      include("funcoes/db_func_censoetapa.php");
     }else{
      $campos = "censoetapa.*";
     }
    }
    $condicao = " ed266_i_codigo in (12,13,22,23,51,56,58)";
    if(trim($abrevtipoensino)=="ER"){
     $condicao .= " AND ed266_c_regular = 'S'";
    }elseif(trim($abrevtipoensino)=="ES"){
     $condicao .= " AND ed266_c_especial = 'S'";
    }elseif(trim($abrevtipoensino)=="EJ"){
     $condicao .= " AND ed266_c_eja = 'S'";
    }
    $repassa = array();
    $sql = $clcensoetapa->sql_query("",$campos,"ed266_c_descr"," $condicao ");
    db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
   }
   ?>
  </td>
 </tr>
</table>
</body>
</html>