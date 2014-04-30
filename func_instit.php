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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_config_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$cldbconfig = new cl_db_config;

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
    <form name="form2" method="post" action="">
    <table width="35%" border="0" align="center" cellspacing="0">
    <tr> 
      <td colspan="4" align="center"> 
       <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_instit.hide();"></td>
    </tr>
    </form>
    </table>
    </td>
    </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where_db_config=" case when ( select count(*) from db_usuarios where id_usuario = " . db_getsession("DB_id_usuario") . " and administrador = 1 ) = 1 then true else codigo in ( select id_instit from db_userinst where id_usuario = " . db_getsession("DB_id_usuario") . " ) end ";
      if(!isset($pesquisa_chave)){
        $campos = "codigo,nomeinst";        
        $sql = $cldbconfig->sql_query(null,$campos,"",$where_db_config);
        db_lovrot($sql,15,"()","",$funcao_js);
      } else {
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $where_db_config .= " and codigo = $pesquisa_chave";
      	  $result = $cldbconfig->sql_record($cldbconfig->sql_query(null,"codigo,nomeinst",null,$where_db_config));
          if($cldbconfig->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$nomeinst',false);</script>";
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