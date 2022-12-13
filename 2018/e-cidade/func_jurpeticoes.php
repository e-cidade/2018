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
include("classes/db_jurpeticoes_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cljurpeticoes = new cl_jurpeticoes;
$cljurpeticoes->rotulo->label("v60_peticao");
$cljurpeticoes->rotulo->label("v60_inicial");
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
            <td width="4%" align="right" nowrap title="<?=$Tv60_peticao?>">
              <?=$Lv60_peticao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("v60_peticao",10,$Iv60_peticao,true,"text",4,"","chave_v60_peticao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="Inicial">
              <?=$Lv60_inicial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("v60_inicial",10,$Iv60_inicial,true,"text",4,"","chave_v60_inicial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_jurpeticoes.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $where="";
      if (isset($tipo)&&$tipo!=""){
      	if ($tipo=='p'){
      	   $where=" v60_tipopet = 1 ";	      		
      	}else if ($tipo=='q'){
      		$where=" v60_tipopet = 2 ";
      	}
      }
      $and="";
      if ($where!=""){
      	$and=' and ';
      }
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_jurpeticoes.php")==true){
             include("funcoes/db_func_jurpeticoes.php");
           }else{
           $campos = "jurpeticoes.*";
           }
        }
        if(isset($chave_v60_peticao) && (trim($chave_v60_peticao)!="") ){
	         $sql = $cljurpeticoes->sql_query($chave_v60_peticao,$campos,"v60_peticao","v60_peticao=$chave_v60_peticao $and $where");
        }else if(isset($chave_v60_inicial) && (trim($chave_v60_inicial)!="") ){
	         $sql = $cljurpeticoes->sql_query("",$campos,"v60_peticao"," v60_inicial like '$chave_v60_inicial%' $and $where ");
        }else{
           $sql = $cljurpeticoes->sql_query("",$campos,"v60_peticao","$where");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $cljurpeticoes->sql_record($cljurpeticoes->sql_query($pesquisa_chave,"*",null,"v60_peticao = $pesquisa_chave $and $where"));
          if($cljurpeticoes->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$v60_peticao','$v60_inicial',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado','',true);</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('','',false);</script>";
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