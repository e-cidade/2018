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
include("classes/db_itbitransacao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clitbitransacao = new cl_itbitransacao;
$clitbitransacao->rotulo->label("it04_codigo");
$clitbitransacao->rotulo->label("it04_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tit04_codigo?>">
              <?=$Lit04_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("it04_codigo",10,$Iit04_codigo,true,"text",4,"","chave_it04_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tit04_descr?>">
              <?=$Lit04_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("it04_descr",100,$Iit04_descr,true,"text",4,"","chave_it04_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_itbitransacao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      
      $sWhere 	  = "1=1";
       
      if (isset($validadata) && $validadata ) {
		 $sWhere .= " and (   it04_datalimite > '".date('Y-m-d',db_getsession('DB_datausu'))."'";
		 $sWhere .= "      or it04_datalimite is null )";
      }
      
      if(!isset($pesquisa_chave)){
      	
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_itbitransacao.php")==true){
             include("funcoes/db_func_itbitransacao.php");
           }else{
           $campos = "itbitransacao.*";
           }
        }
        
        if(isset($chave_it04_codigo) && (trim($chave_it04_codigo)!="") ){
          $sql = $clitbitransacao->sql_query(null,$campos,"it04_codigo",$sWhere." and it04_codigo = {$chave_it04_codigo}");
        }else if(isset($chave_it04_descr) && (trim($chave_it04_descr)!="") ){
          $sql = $clitbitransacao->sql_query(null,$campos,"it04_descr" ,$sWhere." and it04_descr like '$chave_it04_descr%' ");
        }else{
           $sql = $clitbitransacao->sql_query(null,$campos,"it04_codigo",$sWhere);
        }
        $repassa = array();
        if(isset($chave_it04_descr)){
          $repassa = array("chave_it04_codigo"=>$chave_it04_codigo,"chave_it04_descr"=>$chave_it04_descr);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clitbitransacao->sql_record($clitbitransacao->sql_query(null,"*",null,$sWhere." and it04_codigo = {$pesquisa_chave}"));
          if($clitbitransacao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$it04_descr',false);</script>";
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
js_tabulacaoforms("form2","chave_it04_descr",true,1,"chave_it04_descr",true);
</script>