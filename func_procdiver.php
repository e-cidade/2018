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


require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");

require_once("dbforms/db_funcoes.php");

require_once("classes/db_procdiver_classe.php");

db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);

$clprocdiver = new cl_procdiver;
$clprocdiver->rotulo->label("dv09_procdiver");
$clprocdiver->rotulo->label("dv09_descr");

$iInstituicao = db_getsession("DB_instit");
$dtHoje       = date("Y-m-d", db_getsession("DB_datausu"));

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
            <td width="4%" align="right" nowrap title="<?=$Tdv09_procdiver?>">
              <?=$Ldv09_procdiver?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("dv09_procdiver",5,$Idv09_procdiver,true,"text",4,"","chave_dv09_procdiver");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tdv09_descr?>">
              <?=$Ldv09_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("dv09_descr",40,$Idv09_descr,true,"text",4,"","chave_dv09_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_procdiver.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if (!isset($pesquisa_chave)) {

        if(isset($campos)==false){

          if(file_exists("funcoes/db_func_procdiver.php")==true){
            include("funcoes/db_func_procdiver.php");
          }else{
            $campos = "procdiver.*";
          }
        }
        
        if(isset($chave_dv09_procdiver) && (trim($chave_dv09_procdiver)!="") ){
          $sql = $clprocdiver->sql_query($chave_dv09_procdiver,$campos,"dv09_procdiver","     dv09_procdiver = $chave_dv09_procdiver
	                                                                                         and dv09_instit    = {$iInstituicao} 
	                                                                                         and (dv09_dtlimite is null or dv09_dtlimite >= '{$dtHoje}')");
        }else if(isset($chave_dv09_descr) && (trim($chave_dv09_descr)!="") ){
          $sql = $clprocdiver->sql_query("",$campos,"dv09_descr"," dv09_descr like '$chave_dv09_descr%' and dv09_instit = {$iInstituicao}
	         																																															 and (dv09_dtlimite is null or dv09_dtlimite >= '{$dtHoje}')");
        } else if (isset($chave_mostratodas) && trim($chave_mostratodas) != ""){
          $sql = $clprocdiver->sql_query("",$campos,"dv09_procdiver"," dv09_instit = {$iInstituicao}");
        }
        else{
          $sql = $clprocdiver->sql_query("",$campos,"dv09_procdiver"," dv09_instit = {$iInstituicao} and (dv09_dtlimite is null or dv09_dtlimite >= '{$dtHoje}')");
        }
        $repassa = array();
        if(isset($chave_dv09_descr)){
          $repassa = array("chave_dv09_procdiver"=>$chave_dv09_procdiver,"chave_dv09_descr"=>$chave_dv09_descr);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        
      } else {
        
        if ( !empty($pesquisa_chave) ) {
          
          $result = $clprocdiver->sql_record($clprocdiver->sql_query($pesquisa_chave,"*",null,"    dv09_procdiver = $pesquisa_chave
                                                                                               and dv09_instit    = {$iInstituicao} 
	         																																										 and (dv09_dtlimite is null or dv09_dtlimite >= '{$dtHoje}')"));
          if ( $clprocdiver->numrows != 0 ) {
            
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$dv09_descr',false);</script>";
          } else {
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
js_tabulacaoforms("form2","chave_dv09_descr",true,1,"chave_dv09_descr",true);
</script>