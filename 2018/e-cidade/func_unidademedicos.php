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
include("classes/db_unidademedicos_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("rh70_estrutural");

$clunidademedicos = new cl_unidademedicos;
$clunidademedicos->rotulo->label("sd04_i_medico");
$clunidademedicos->rotulo->label("sd04_i_codigo");
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
            <td width="4%" align="right" nowrap title="<?=$Tsd04_i_medico?>">
              <?=$Lsd04_i_medico?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("sd04_i_medico",10,$Isd04_i_medico,true,"text",4,"","chave_sd04_i_medico");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z01_nome",10,$Iz01_nome,true,"text",4,"","chave_z01_nome");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh70_estrutural?>">
              <?=$Lrh70_estrutural?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh70_estrutural",10,$Irh70_estrutural,true,"text",4,"","chave_rh70_estrutural");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_unidademedicos.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where = '';
      if( isset($chave_sd04_i_unidade)){
         $where .= " sd04_i_unidade = ".(int)$chave_sd04_i_unidade;
      }
      if( isset($chave_sd04_i_medico)){
         $where .= !empty($where)?" and ":"";
         $where .= " sd04_i_medico = ".(int)$chave_sd04_i_medico;
      }
      
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_unidademedicos.php")==true){
             include("funcoes/db_func_unidademedicos.php");
           }else{
           $campos = "unidademedicos.*";
           }
        }
        //if(isset($chave_sd04_i_medico) && (trim($chave_sd04_i_medico)!="") ){
	      //   $sql = $clunidademedicos->sql_query($chave_sd04_i_medico,$campos,"sd04_i_medico", "$where");
        //}else 
        if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
           $where = ( !empty($where)?' and ':'').$where;
	         $sql = $clunidademedicos->sql_query("",$campos,"z01_nome"," z01_nome like '$chave_z01_nome%' $where ");
        }else if(isset($chave_rh70_estrutural) && (trim($chave_rh70_estrutural)!="") ){
           $where = (!empty($where)?' and ':'').$where;
	         $sql = $clunidademedicos->sql_query("",$campos,"z01_nome"," rh70_estrutural = '$chave_rh70_estrutural' $where ");          
        }else{
           $sql = $clunidademedicos->sql_query("",$campos,"sd04_i_codigo","$where");
        }
        $repassa = array();
        if(isset($chave_sd04_i_codigo)){
          $repassa = array("chave_sd04_i_codigo"=>$chave_sd04_i_codigo,"chave_sd04_i_codigo"=>$chave_sd04_i_codigo);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $where = !empty($where)?' and ':''.$where;
          $result = $clunidademedicos->sql_record($clunidademedicos->sql_query($pesquisa_chave.$where));
          die( $clunidademedicos->sql_query($pesquisa_chave.$where) );
          if($clunidademedicos->numrows!=0){
            db_fieldsmemory($result,0);
            die('$rh70_estrutural, $rh70_descr, $sd04_i_codigo' );
            echo "<script>".$funcao_js."(false,'$rh70_descr','$rh70_estrutural','$sd04_i_codigo');</script>";
          }else{
	         echo "<script>".$funcao_js."(true,'Chave(".$pesquisa_chave.") não Encontrado');</script>";
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
js_tabulacaoforms("form2","chave_sd04_i_codigo",true,1,"chave_sd04_i_codigo",true);
</script>