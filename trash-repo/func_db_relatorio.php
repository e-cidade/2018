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
include("classes/db_db_relatorio_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cldb_relatorio = new cl_db_relatorio;
$cldb_relatorio->rotulo->label("db63_sequencial");
$cldb_relatorio->rotulo->label("db63_db_gruporelatorio");
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
            <td width="4%" align="right" nowrap title="<?=$Tdb63_sequencial?>">
              <?=$Ldb63_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("db63_sequencial",10,$Idb63_sequencial,true,"text",4,"","chave_db63_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tdb63_db_gruporelatorio?>">
              <?=$Ldb63_db_gruporelatorio?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("db63_db_gruporelatorio",10,$Idb63_db_gruporelatorio,true,"text",4,"","chave_db63_db_gruporelatorio");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_db_relatorio.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      
      $sWhere = '1=1';
      
      if ( isset($lTemplate)) {
	      if ( $lTemplate ) {
	      	$sWhere .= 'and db63_db_tiporelatorio = 2';
	      } else {
	      	$sWhere .= 'and db63_db_tiporelatorio = 1';
	      }
      }
      
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_db_relatorio.php")==true){
             include("funcoes/db_func_db_relatorio.php");
           }else{
           $campos = "db_relatorio.*";
           }
        }
        if(isset($chave_db63_sequencial) && (trim($chave_db63_sequencial)!="") ){
	         $sql = $cldb_relatorio->sql_query(null,$campos,"db63_sequencial",$sWhere." and db63_sequencial = {$chave_db63_sequencial}");
        }else if(isset($chave_db63_db_gruporelatorio) && (trim($chave_db63_db_gruporelatorio)!="") ){
	         $sql = $cldb_relatorio->sql_query(null,$campos,"db63_db_gruporelatorio",$sWhere." and db63_db_gruporelatorio like '$chave_db63_db_gruporelatorio%' ");
        }else{
           $sql = $cldb_relatorio->sql_query(null,$campos,"db63_sequencial",$sWhere);
        }
        $repassa = array();
        if(isset($chave_db63_db_gruporelatorio)){
          $repassa = array("chave_db63_sequencial"=>$chave_db63_sequencial,"chave_db63_db_gruporelatorio"=>$chave_db63_db_gruporelatorio);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cldb_relatorio->sql_record($cldb_relatorio->sql_query(null,"*",null,$sWhere." and db63_sequencial = {$pesquisa_chave}"));
          if($cldb_relatorio->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$db63_nomerelatorio',false);</script>";
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
js_tabulacaoforms("form2","chave_db63_db_gruporelatorio",true,1,"chave_db63_db_gruporelatorio",true);
</script>