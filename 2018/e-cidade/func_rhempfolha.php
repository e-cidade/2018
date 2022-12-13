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
include("classes/db_rhempfolha_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhempfolha = new cl_rhempfolha;
$clrhempfolha->rotulo->label("rh40_anousu");
$clrhempfolha->rotulo->label("rh40_mesusu");
$clrhempfolha->rotulo->label("rh40_orgao");
$clrhempfolha->rotulo->label("rh40_unidade");
$clrhempfolha->rotulo->label("rh40_projativ");
$clrhempfolha->rotulo->label("rh40_recurso");
$clrhempfolha->rotulo->label("rh40_codele");
$clrhempfolha->rotulo->label("rh40_rubric");
$clrhempfolha->rotulo->label("rh40_rubric");
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
            <td width="4%" align="right" nowrap title="<?=$Trh40_mesusu?>">
              <?=$Lrh40_mesusu?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh40_mesusu",2,$Irh40_mesusu,true,"text",4,"","chave_rh40_mesusu");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh40_orgao?>">
              <?=$Lrh40_orgao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh40_orgao",2,$Irh40_orgao,true,"text",4,"","chave_rh40_orgao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh40_unidade?>">
              <?=$Lrh40_unidade?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh40_unidade",2,$Irh40_unidade,true,"text",4,"","chave_rh40_unidade");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh40_projativ?>">
              <?=$Lrh40_projativ?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh40_projativ",4,$Irh40_projativ,true,"text",4,"","chave_rh40_projativ");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh40_recurso?>">
              <?=$Lrh40_recurso?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh40_recurso",4,$Irh40_recurso,true,"text",4,"","chave_rh40_recurso");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh40_codele?>">
              <?=$Lrh40_codele?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh40_codele",6,$Irh40_codele,true,"text",4,"","chave_rh40_codele");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh40_rubric?>">
              <?=$Lrh40_rubric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh40_rubric",4,$Irh40_rubric,true,"text",4,"","chave_rh40_rubric");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh40_rubric?>">
              <?=$Lrh40_rubric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh40_rubric",4,$Irh40_rubric,true,"text",4,"","chave_rh40_rubric");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhempfolha.hide();">
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
           if(file_exists("funcoes/db_func_rhempfolha.php")==true){
             include("funcoes/db_func_rhempfolha.php");
           }else{
           $campos = "rhempfolha.*";
           }
        }
        if(isset($chave_rh40_mesusu) && (trim($chave_rh40_mesusu)!="") ){
	         $sql = $clrhempfolha->sql_query(db_getsession('DB_anousu'),$chave_rh40_mesusu,$chave_rh40_orgao,$chave_rh40_unidade,$chave_rh40_projativ,$chave_rh40_recurso,$chave_rh40_codele,$chave_rh40_rubric,$campos,"rh40_mesusu");
        }else if(isset($chave_rh40_rubric) && (trim($chave_rh40_rubric)!="") ){
	         $sql = $clrhempfolha->sql_query(db_getsession('DB_anousu'),"","","","","","","",$campos,"rh40_rubric"," rh40_rubric like '$chave_rh40_rubric%' ");
        }else{
           $sql = $clrhempfolha->sql_query(db_getsession('DB_anousu'),"","","","","","","",$campos,"rh40_anousu#rh40_mesusu#rh40_orgao#rh40_unidade#rh40_projativ#rh40_recurso#rh40_codele#rh40_rubric","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhempfolha->sql_record($clrhempfolha->sql_query(db_getsession("DB_anousu"),$pesquisa_chave));
          if($clrhempfolha->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$rh40_rubric',false);</script>";
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