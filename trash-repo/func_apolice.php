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
include("classes/db_apolice_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clapolice = new cl_apolice;
$clapolice->rotulo->label("t81_codapo");
$clapolice->rotulo->label("t81_codseg");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" width="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tt81_codapo?>">
              <?=$Lt81_codapo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("t81_codapo",10,$It81_codapo,true,"text",4,"","chave_t81_codapo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tt81_codseg?>">
              <?=$Lt81_codseg?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("t81_codseg",10,$It81_codseg,true,"text",4,"","chave_t81_codseg");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_apolice.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
       $where_instit = "t81_instit = ".db_getsession("DB_instit");
       if(isset($campos)==false){
         if(file_exists("funcoes/db_func_apolice.php")==true){
           include("funcoes/db_func_apolice.php");
         }else{
           $campos = "apolice.*";
         }
      }
      $campos = " distinct $campos ";
      if(!isset($pesquisa_chave)){

        if(isset($chave_t81_codapo) && (trim($chave_t81_codapo)!="") ){
	         $sql = $clapolice->sql_query(null,$campos,"t81_codapo","t81_codapo = $chave_t81_codapo and $where_instit");
        }else if(isset($chave_t81_codseg) && (trim($chave_t81_codseg)!="") ){
	         $sql = $clapolice->sql_query("",$campos,"t81_codseg"," t81_codseg like '$chave_t81_codseg%' and $where_instit");
        }else{
           $sql = $clapolice->sql_query("",$campos,"t81_codapo","$where_instit");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clapolice->sql_record($clapolice->sql_query(null,"*",null,"t81_codapo = $pesquisa_chave and $where_instit"));
          if($clapolice->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$t81_codseg',false);</script>";
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