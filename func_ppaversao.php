<?
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2013  DBselller Servicos de Informatica             
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

require_once("libs/db_stdlib.php");
require_once("libs/db_conecta.php");
require_once("libs/db_sessoes.php");
require_once("libs/db_usuariosonline.php");
require_once("dbforms/db_funcoes.php");
require_once("classes/db_ppaversao_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clppaversao = new cl_ppaversao;
$clppaversao->rotulo->label("o119_sequencial");
$clppaversao->rotulo->label("o119_versao");
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
            <td width="4%" align="right" nowrap title="<?=$To119_sequencial?>">
              <?=$Lo119_sequencial?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("o119_sequencial",10,$Io119_sequencial,true,"text",4,"","chave_o119_sequencial");
		       ?>
            </td>
          </tr>
          <tr>
            <td width="4%" align="right" nowrap title="<?=$To119_versao?>">
              <?=$Lo119_versao?>
            </td>
            <td width="96%" align="left" nowrap>
              <?
		       db_input("o119_versao",10,$Io119_versao,true,"text",4,"","chave_o119_versao");
		       ?>
            </td>
          </tr>
          <tr>
            <td colspan="2" align="center">
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar">
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_ppaversao.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr>
    <td align="center" valign="top">
      <?
      $sWhere = "1=1";
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_ppaversao.php")==true){
             include("funcoes/db_func_ppaversao.php");
           }else{
           $campos = "ppaversao.*";
           }
        }

        $campos .= " , case when o119_ativo is true ";
        $campos .= "    then 'Sim' ";
        $campos .= "    else 'Não' ";
        $campos .= "   end as dl_Ativo ";

        if(isset($chave_o119_sequencial) && (trim($chave_o119_sequencial)!="") ){
	         $sql = $clppaversao->sql_query(null,"*" ,"o119_versao",
	                                        "o119_sequencial = {$chave_o119_sequencial}");
        }else if(isset($chave_o119_versao) && (trim($chave_o119_versao)!="") ){
	         $sql = $clppaversao->sql_query("",$campos,"o119_versao"," o119_versao like '$chave_o119_versao%' and {$sWhere} ");
        }else{
           $sql = $clppaversao->sql_query("",$campos,"o119_sequencial","{$sWhere}");
        }
        $repassa = array();
        if(isset($chave_o119_versao)){
          $repassa = array("chave_o119_sequencial"=>$chave_o119_sequencial,"chave_o119_versao"=>$chave_o119_versao);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clppaversao->sql_record($clppaversao->sql_query($pesquisa_chave));
          if($clppaversao->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$o119_versao',false);</script>";
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
js_tabulacaoforms("form2","chave_o119_versao",true,1,"chave_o119_versao",true);
</script>