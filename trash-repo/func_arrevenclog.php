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
include("classes/db_arrevenclog_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clarrevenclog = new cl_arrevenclog;
$clarrevenclog->rotulo->label("k75_sequencial");
$clarrevenclog->rotulo->label("k75_usuario");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tk75_sequencial?>">
              <?=$Lk75_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k75_sequencial",10,$Ik75_sequencial,true,"text",4,"","chave_k75_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tk75_usuario?>">
              <?=$Lk75_usuario?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k75_usuario",10,$Ik75_usuario,true,"text",4,"","chave_k75_usuario");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_arrevenclog.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(!isset($pesquisa_chave)){
        $where = "where k75_instit = ".db_getsession("DB_instit") ;
				
        if(isset($chave_k75_sequencial) && (trim($chave_k75_sequencial)!="") ){
        	$where .= " and k75_sequencial = {$chave_k75_sequencial} " ;
	         
        }else if(isset($chave_k75_usuario) && (trim($chave_k75_usuario)!="") ){
        	$where .= "and k75_usuario like '$chave_k75_usuario%' ";
	         
        }
				$sql = "select distinct k75_sequencial,k75_usuario,login,k75_data,k75_hora,k00_numpre 
				from arrevenclog 
				inner join arrevenc on k00_arrevenclog=k75_sequencial
				inner join db_usuarios on db_usuarios.id_usuario = arrevenclog.k75_usuario 
				$where
				order by k75_sequencial desc";
				
        $repassa = array();
        if(isset($chave_k75_usuario)){
          $repassa = array("chave_k75_sequencial"=>$chave_k75_sequencial,"chave_k75_usuario"=>$chave_k75_usuario);
        }
				//die($sql);
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clarrevenclog->sql_record($clarrevenclog->sql_query($pesquisa_chave));
          if($clarrevenclog->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$k75_usuario',false);</script>";
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
<script>
js_tabulacaoforms("form2","chave_k75_usuario",true,1,"chave_k75_usuario",true);
</script>