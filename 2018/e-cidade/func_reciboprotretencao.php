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
include("libs/db_utils.php");
include("dbforms/db_funcoes.php");
include("classes/db_arretipo_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clarretipo = new cl_arretipo;
$clarretipo->rotulo->label("k00_tipo");
$clarretipo->rotulo->label("k00_descr");

$oGet = db_utils::postMemory($_GET);

if(isset($oGet->k03_tipo)){
	$wheretipo = " and arretipo.k03_tipo in($oGet->k03_tipo) ";
}else{
	$wheretipo = "";
}
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
            <td width="4%" align="right" nowrap title="<?=$Tk00_tipo?>">
              <?=$Lk00_tipo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k00_tipo",4,$Ik00_tipo,true,"text",4,"","chave_k00_tipo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tk00_descr?>">
              <?=$Lk00_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("k00_descr",40,$Ik00_descr,true,"text",4,"","chave_k00_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_arretipo.hide();">
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
           if(file_exists("funcoes/db_func_arretipo.php")==true){
             include("funcoes/db_func_arretipo.php");
           }else{
           $campos = "arretipo.*";
           }
        }
        if(isset($chave_k00_tipo) && (trim($chave_k00_tipo)!="") ){
        	
	         $sql = $clarretipo->sql_query($chave_k00_tipo,$campos,"k00_tipo","k00_tipo = $chave_k00_tipo and arretipo.k00_instit = ".db_getsession('DB_instit'). $wheretipo );
        }else if(isset($chave_k00_descr) && (trim($chave_k00_descr)!="") ){
	         $sql = $clarretipo->sql_query("",$campos,"k00_descr"," k00_descr like '$chave_k00_descr%' and arretipo.k00_instit = ".db_getsession('DB_instit') . $wheretipo);
        }else{
           $sql = $clarretipo->sql_query("",$campos,"k00_tipo"," arretipo.k00_instit = ".db_getsession('DB_instit') . $wheretipo);
        }
        $repassa = array();
        if(isset($chave_k00_descr)){
          $repassa = array("chave_k00_tipo"=>$chave_k00_tipo,"chave_k00_descr"=>$chave_k00_descr);
        }
				//die( $sql);
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clarretipo->sql_record($clarretipo->sql_query(null,"*","k00_tipo","arretipo.k00_tipo = $pesquisa_chave and arretipo.k00_instit = ".db_getsession('DB_instit') . $wheretipo));
          if($clarretipo->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$k00_descr',false);</script>";
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
js_tabulacaoforms("form2","chave_k00_descr",true,1,"chave_k00_descr",true);
</script>