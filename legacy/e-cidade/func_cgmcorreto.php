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
include("classes/db_cgmcorreto_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clcgmcorreto = new cl_cgmcorreto;
$clcgmcorreto->rotulo->label("z10_codigo");
$clcgmcorreto->rotulo->label("z10_numcgm");
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
            <td width="4%" align="right" nowrap title="<?=$Tz10_codigo?>">
              <?=$Lz10_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z10_codigo",10,$Iz10_codigo,true,"text",4,"","chave_z10_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz10_numcgm?>">
              <?=$Lz10_numcgm?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z10_numcgm",8,$Iz10_numcgm,true,"text",4,"","chave_z10_numcgm");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhere = " and z10_proc is false ";
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_cgmcorreto.php")==true){
             include("funcoes/db_func_cgmcorreto.php");
           }else{
           $campos = "cgmcorreto.*";
           }
        }
        if(isset($chave_z10_codigo) && (trim($chave_z10_codigo)!="") ){
	         $sql = $clcgmcorreto->sql_query($chave_z10_codigo,$campos,"z10_codigo"," z10_codigo = $chave_z10_codigo and z10_instit = ".db_getsession('DB_instit')." {$sWhere} ");
        }else if(isset($chave_z10_numcgm) && (trim($chave_z10_numcgm)!="") ){
	         $sql = $clcgmcorreto->sql_query("",$campos,"z10_numcgm"," z10_numcgm like '$chave_z10_numcgm%' and z10_instit = ".db_getsession('DB_instit')." {$sWhere} ");
        }else{
           $sql = $clcgmcorreto->sql_query("",$campos,"z10_codigo"," z10_instit = ".db_getsession('DB_instit')." {$sWhere} ");
      	   //$sql = "";
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clcgmcorreto->sql_record($clcgmcorreto->sql_query($pesquisa_chave,"*",null," z10_codigo = $pesquisa_chave and z10_instit = ".db_getsession('DB_instit')." {$sWhere} "));
          if($clcgmcorreto->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$z10_numcgm',false);</script>";
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