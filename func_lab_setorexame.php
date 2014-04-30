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
include("classes/db_lab_setorexame_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cllab_setorexame = new cl_lab_setorexame;
$cllab_setorexame->rotulo->label("la09_i_codigo");
$cllab_setorexame->rotulo->label("la08_c_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tla09_i_codigo?>">
              <?=$Lla09_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("la09_i_codigo",10,$Ila09_i_codigo,true,"text",4,"","chave_la09_i_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tla08_c_descr?>">
              <b>Descrição</b>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("la08_c_descr",50,@$Ila08_c_descr,true,"text",4,"","chave_la08_c_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_lab_setorexame.hide();">
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
           if(file_exists("funcoes/db_func_lab_setorexame.php")==true){
             include("funcoes/db_func_lab_setorexame.php");
           }else{
           $campos = "lab_setorexame.*";
           }
        }
        $campos = "lab_setorexame.la09_i_codigo,lab_setor.la23_i_codigo,lab_setor.la23_c_descr,lab_exame.la08_i_codigo,lab_exame.la08_c_descr";
        $where1="";
        $where2="";
        $sep1="";
        $sep2="";
        
        if(isset($la02_i_codigo)){
           $where1=" la02_i_codigo=$la02_i_codigo ";
           $sep1=" and ";
        }
        if(isset($la24_i_codigo)){
        	 $where2 = " la09_i_labsetor = $la24_i_codigo " ;
           $sep2=" and "; 
        }
      
        if(isset($chave_la09_i_codigo) && (trim($chave_la09_i_codigo)!="") ){
	         $sql = $cllab_setorexame->sql_query(null,$campos,"la08_i_codigo"," la08_i_codigo = $chave_la09_i_codigo $sep1 $where1 $sep2 $where2");
        }else if(isset($chave_la08_c_descr) && (trim($chave_la08_c_descr)!="") ){
	         $sql = $cllab_setorexame->sql_query("",$campos,"la08_c_descr"," la08_c_descr like '$chave_la08_c_descr%'  $sep1$where1$sep2$where2 ");
        }else{

          if(trim($where1) == '') {
            $sep2 = '';
          }
           $sql = $cllab_setorexame->sql_query("",$campos,"la08_i_codigo"," $where1 $sep2 $where2");

        }
        $repassa = array();
        if(isset($chave_la09_i_codigo)){
          $repassa = array("chave_la09_i_codigo"=>$chave_la09_i_codigo,"chave_la09_i_codigo"=>$chave_la09_i_codigo);
        }
        //echo $sql;
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){

        if(isset($la02_i_codigo)){
           $where1=" la02_i_codigo=$la02_i_codigo ";
           $sep1=" and ";
        }
        if(isset($la24_i_codigo)){
        	 $where2 = " la09_i_labsetor = $la24_i_codigo " ;
           $sep2=" and "; 
        }
 
          $result = $cllab_setorexame->sql_record($cllab_setorexame->sql_query(null,'*',null, "la08_i_codigo = $pesquisa_chave $sep1 $where1 $sep2 $where2"));
          if($cllab_setorexame->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$la08_c_descr',false,$la09_i_codigo);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") não Encontrado',true,'');</script>";
          }
        }else{
	       echo "<script>".$funcao_js."('',false,'');</script>";
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
js_tabulacaoforms("form2","chave_la09_i_codigo",true,1,"chave_la09_i_codigo",true);
</script>