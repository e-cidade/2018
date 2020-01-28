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

require("libs/db_stdlib.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_rhtipoparticipacaocurso_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhtipoparticipacaocurso = new cl_rhtipoparticipacaocurso;
$clrhtipoparticipacaocurso->rotulo->label("h67_descricao");
$clrhtipoparticipacaocurso->rotulo->label("h67_sequencial");
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
            <td width="4%" align="right" nowrap title="<?=$Th67_descricao?>">
              <?=$Lh67_descricao?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h67_descricao",150,$Ih67_descricao,true,"text",4,"","chave_h67_descricao");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th67_sequencial?>">
              <?=$Lh67_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h67_sequencial",10,$Ih67_sequencial,true,"text",4,"","chave_h67_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhtipoparticipacaocurso.hide();">
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
           if(file_exists("funcoes/db_func_rhtipoparticipacaocurso.php")==true){
             include("funcoes/db_func_rhtipoparticipacaocurso.php");
           }else{
           $campos = "rhtipoparticipacaocurso.*";
           }
        }
        if(isset($chave_h67_descricao) && (trim($chave_h67_descricao)!="") ){
	         $sql = $clrhtipoparticipacaocurso->sql_query($chave_h67_descricao,$campos,"h67_descricao");
        }else if(isset($chave_h67_sequencial) && (trim($chave_h67_sequencial)!="") ){
	         $sql = $clrhtipoparticipacaocurso->sql_query("",$campos,"h67_sequencial"," h67_sequencial like '$chave_h67_sequencial%' ");
        }else{
           $sql = $clrhtipoparticipacaocurso->sql_query("",$campos,"h67_descricao","");
        }
        $repassa = array();
        if(isset($chave_h67_sequencial)){
          $repassa = array("chave_h67_descricao"=>$chave_h67_descricao,"chave_h67_sequencial"=>$chave_h67_sequencial);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhtipoparticipacaocurso->sql_record($clrhtipoparticipacaocurso->sql_query($pesquisa_chave));
          if($clrhtipoparticipacaocurso->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$h67_sequencial',false);</script>";
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
js_tabulacaoforms("form2","chave_h67_sequencial",true,1,"chave_h67_sequencial",true);
</script>