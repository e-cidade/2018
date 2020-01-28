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
include("classes/db_rhrubricas_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhrubricas = new cl_rhrubricas;
$clrhrubricas->rotulo->label("rh27_rubric");
$clrhrubricas->rotulo->label("rh27_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Trh27_rubric?>">
              <?=$Lrh27_rubric?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh27_rubric",4,$Irh27_rubric,true,"text",4,"","chave_rh27_rubric");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh27_descr?>">
              <?=$Lrh27_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh27_descr",30,$Irh27_descr,true,"text",4,"","chave_rh27_descr");
		       ?>
            </td>
          </tr>
          <tr> 
             <td width="4%" align="right" nowrap title="selecionar todos, ativos ou inativos"><b>sele��o por:</b></td>
             <td width="96%" align="left" nowrap>
             <?
             if(!isset($opcao)){
	           $opcao = "t";
             }
             if(!isset($opcao_bloq)){
             	$opcao_bloq = 1;
             }
             $arr_opcao = array("i"=>"todos","t"=>"ativos","f"=>"inativos");
             db_select('opcao',$arr_opcao,true,$opcao_bloq); 
             ?>
             </td>
          </tr>

          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhrubricas.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $dbwhere = "";
      if(isset($instit) && trim($instit)!=""){
      	$dbwhere = " and rh27_instit = $instit ";
      }
      $dbwhere = " and rh27_instit = ".db_getsession("DB_instit");

      $where_ativo = "";
      if(isset($opcao) && trim($opcao)!="i"){
        $where_ativo = " and rh27_ativo='$opcao' ";
      }

      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
          if(file_exists("funcoes/db_func_rhrubricas.php")==true){
            include("funcoes/db_func_rhrubricas.php");
          }else{
            $campos = "rhrubricas.*";
          }
        }
        if(isset($chave_rh27_rubric) && (trim($chave_rh27_rubric)!="") ){
	       $sql = $clrhrubricas->sql_query(null,null,$campos,"rh27_rubric"," rh27_rubric = '$chave_rh27_rubric' ".$dbwhere.$where_ativo);
        }else if(isset($chave_rh27_descr) && (trim($chave_rh27_descr)!="") ){
	       $sql = $clrhrubricas->sql_query("",null,$campos,"rh27_descr"," rh27_descr like '$chave_rh27_descr%' ".$dbwhere.$where_ativo);
        }else{
           $sql = $clrhrubricas->sql_query("",null,$campos,"rh27_rubric"," 1=1 ".$dbwhere.$where_ativo);
        }
        // echo $sql;
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhrubricas->sql_record($clrhrubricas->sql_query(null,null,"*,case when trim(rh27_form)='' then 'f' else 't' end as formula ",""," rh27_rubric = '$pesquisa_chave' ".$dbwhere));
          if($clrhrubricas->numrows!=0){
            db_fieldsmemory($result,0);
	    if(!isset($ret)){
              echo "<script>".$funcao_js."('$rh27_descr','$rh27_limdat','$formula','$rh27_obs','$rh27_presta','$rh27_tipo',false);</script>";
	    }else{
              echo "<script>".$funcao_js."('$rh27_descr','$rh27_limdat','$formula','$rh27_obs','$rh27_pd','$rh27_presta','$rh27_tipo',false);</script>";
	    }
          }else{
	    if(!isset($ret)){
	      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true,true,true,true,true);</script>";
	    }else{
	      echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true,true,true,true,true,true);</script>";
	    }
          }
        }else{
	   if(!isset($ret)){
	      echo "<script>".$funcao_js."('',true,true,true,false);</script>";
	   }else{
	      echo "<script>".$funcao_js."('',true,true,true,true,false);</script>";
	   }
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