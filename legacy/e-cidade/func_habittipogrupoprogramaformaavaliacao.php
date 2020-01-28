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
include("classes/db_habittipogrupoprogramaformaavaliacao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clhabittipogrupoprogramaformaavaliacao = new cl_habittipogrupoprogramaformaavaliacao;
$clhabittipogrupoprogramaformaavaliacao->rotulo->label("ht06_sequencial");
$clhabittipogrupoprogramaformaavaliacao->rotulo->label("ht06_habittipogrupoprograma");
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
            <td width="4%" align="right" nowrap title="<?=$Tht06_sequencial?>">
              <?=$Lht06_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ht06_sequencial",10,$Iht06_sequencial,true,"text",4,"","chave_ht06_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tht06_habittipogrupoprograma?>">
              <?=$Lht06_habittipogrupoprograma?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("ht06_habittipogrupoprograma",10,$Iht06_habittipogrupoprograma,true,"text",4,"","chave_ht06_habittipogrupoprograma");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_habittipogrupoprogramaformaavaliacao.hide();">
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
           if(file_exists("funcoes/db_func_habittipogrupoprogramaformaavaliacao.php")==true){
             include("funcoes/db_func_habittipogrupoprogramaformaavaliacao.php");
           }else{
           $campos = "habittipogrupoprogramaformaavaliacao.*";
           }
        }
        if(isset($chave_ht06_sequencial) && (trim($chave_ht06_sequencial)!="") ){
	         $sql = $clhabittipogrupoprogramaformaavaliacao->sql_query($chave_ht06_sequencial,$campos,"ht06_sequencial");
        }else if(isset($chave_ht06_habittipogrupoprograma) && (trim($chave_ht06_habittipogrupoprograma)!="") ){
	         $sql = $clhabittipogrupoprogramaformaavaliacao->sql_query("",$campos,"ht06_habittipogrupoprograma"," ht06_habittipogrupoprograma like '$chave_ht06_habittipogrupoprograma%' ");
        }else{
           $sql = $clhabittipogrupoprogramaformaavaliacao->sql_query("",$campos,"ht06_sequencial","");
        }
        $repassa = array();
        if(isset($chave_ht06_habittipogrupoprograma)){
          $repassa = array("chave_ht06_sequencial"=>$chave_ht06_sequencial,"chave_ht06_habittipogrupoprograma"=>$chave_ht06_habittipogrupoprograma);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clhabittipogrupoprogramaformaavaliacao->sql_record($clhabittipogrupoprogramaformaavaliacao->sql_query($pesquisa_chave));
          if($clhabittipogrupoprogramaformaavaliacao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$ht06_habittipogrupoprograma',false);</script>";
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
js_tabulacaoforms("form2","chave_ht06_habittipogrupoprograma",true,1,"chave_ht06_habittipogrupoprograma",true);
</script>