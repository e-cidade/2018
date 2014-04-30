<?
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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_db_departorg_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_departorg = new cl_db_departorg;
$cldb_departorg->rotulo->label("db01_coddepto");
$cldb_departorg->rotulo->label("db01_anousu");
$cldb_departorg->rotulo->label("db01_coddepto");
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
            <td width="4%" align="right" nowrap title="<?=$Tdb01_coddepto?>">
              <?=$Ldb01_coddepto?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("db01_coddepto",0,$Idb01_coddepto,true,"text",1,"","chave_db01_coddepto");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_db_departorg.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhere = "";
      if (isset($orgaos) && $orgaos != "") {
        
        $sWhere .= " db01_orgao  in({$orgaos})";
        $sWhere .= " and db01_anousu = ".db_getsession("DB_anousu");
        
      }
      if (isset($unidades) && $unidades != "") {
        
        if ($sWhere != "") {
          $sWhere .= " and ";
        }
        
        $sWhere .= " db01_unidade in({$unidades})";
        $sWhere .= " and db01_anousu = ".db_getsession("DB_anousu");
        
      }
      if($sWhere == "") {
        
        $sWhere  = " instit             = ". db_getsession("DB_instit");
        $sWhere .= " and    db01_anousu = ".db_getsession("DB_anousu");
        
      }
      if(!isset($pesquisa_chave) || $pesquisa_chave == ""){
        
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_db_departorg.php")==true){
             include("funcoes/db_func_db_departorg.php");
           }else{
           $campos = "db_departorg.*";
           }
        }
        $campos = "distinct coddepto, descrdepto";
        if(isset($chave_db01_coddepto) && (trim($chave_db01_coddepto)!="") ) {
          
	         $sql = $cldb_departorg->sql_query(null, 
	                                           $chave_db01_anousu,
	                                           $campos,
	                                           null,
	                                           "db01_coddepto = {$chave_db01_coddepto} and {$sWhere}");
	                                           
        }else if(isset($chave_db01_coddepto) && (trim($chave_db01_coddepto)!="") ){
          
	         $sql = $cldb_departorg->sql_query("",
	                                           "",
	                                           $campos, 
	                                           null,
	                                           " db01_coddepto like '$chave_db01_coddepto%' and {$sWhere}");
        }else{
           $sql = $cldb_departorg->sql_query("", 
                                             "",
                                             $campos,null, $sWhere);
        }
        
        $repassa = array();
        if(isset($chave_db01_coddepto)){
          $repassa = array("chave_db01_coddepto"=>$chave_db01_coddepto,"chave_db01_coddepto"=>$chave_db01_coddepto);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $sql = $cldb_departorg->sql_query(null, 
                                            null,
                                             "*",
                                             "db01_coddepto",
                                             "db01_coddepto = {$pesquisa_chave} and {$sWhere}");
          $result = $cldb_departorg->sql_record($sql);
          if($cldb_departorg->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$descrdepto',false);</script>";
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
js_tabulacaoforms("form2","chave_db01_coddepto",true,1,"chave_db01_coddepto",true);
</script>