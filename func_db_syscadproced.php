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
include("classes/db_db_syscadproced_classe.php");
include("classes/db_db_sysmodulo_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_syscadproced = new cl_db_syscadproced;
$cldb_sysmodulo = new cl_db_sysmodulo;
$cldb_syscadproced->rotulo->label("codproced");
$cldb_syscadproced->rotulo->label("descrproced");
$cldb_syscadproced->rotulo->label("codmod");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js">
function js_enviar() {
  return true;
}
</script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form1" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tcodmod?>">
              <?=$Lcodmod?>
            </td>
						<td>
					<?
						db_selectrecord('modulo',($cldb_sysmodulo->sql_record($cldb_sysmodulo->sql_query(null,"*", "nomemod"))),true,1,"", "", "", "0-Todos", "js_enviar()");
          ?>
					</td>
          </tr> 
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tcodproced?>">
              <?=$Lcodproced?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("codproced",10,$Icodproced,true,"text",4,"","chave_codproced");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tdescrproced?>">
              <?=$Ldescrproced?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("descrproced",60,$Idescrproced,true,"text",4,"","chave_descrproced");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_db_syscadproced.hide();">
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
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_db_syscadproced.php")==true){
             include("funcoes/db_func_db_syscadproced.php");
           }else{
           $campos = "db_syscadproced.*";
           }
        }
				$where="";
				if (isset($modulo) and $modulo > 0) {
					$where = " db_syscadproced.codmod = $modulo";
				}
        if(isset($chave_codproced) && (trim($chave_codproced)!="") ){
	         $sql = $cldb_syscadproced->sql_query($chave_codproced,$campos,"codproced", $where);
        }else if(isset($chave_descrproced) && (trim($chave_descrproced)!="") ){
	         $sql = $cldb_syscadproced->sql_query("",$campos,"descrproced"," descrproced like '$chave_descrproced%' ", $where);
        }else{
           $sql = $cldb_syscadproced->sql_query("",$campos,"codproced", $where);
        }
        $repassa = array();
        if(isset($chave_descrproced)){
          $repassa = array("chave_codproced"=>$chave_codproced,"chave_descrproced"=>$chave_descrproced);
        }

        db_lovrot($sql,50,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldb_syscadproced->sql_record($cldb_syscadproced->sql_query($pesquisa_chave));
          if($cldb_syscadproced->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$descrproced',false);</script>";
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
js_tabulacaoforms("form1","chave_descrproced",true,1,"chave_descrproced",true);
</script>