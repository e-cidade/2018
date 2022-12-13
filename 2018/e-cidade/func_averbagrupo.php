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
include("classes/db_averbagrupo_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$claverbagrupo = new cl_averbagrupo;
$claverbagrupo->rotulo->label("j105_sequencial");
$claverbagrupo->rotulo->label("j105_descr");
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
            <td width="4%" align="right" nowrap title="<?=$Tj105_sequencial?>">
              <?=$Lj105_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("j105_sequencial",10,$Ij105_sequencial,true,"text",4,"","chave_j105_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tj105_descr?>">
              <?=$Lj105_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("j105_descr",40,$Ij105_descr,true,"text",4,"","chave_j105_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_averbagrupo.hide();">
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
           if(file_exists("funcoes/db_func_averbagrupo.php")==true){
             include("funcoes/db_func_averbagrupo.php");
           }else{
           $campos = "averbagrupo.*";
           }
        }
        if(isset($chave_j105_sequencial) && (trim($chave_j105_sequencial)!="") ){
	         $sql = $claverbagrupo->sql_query($chave_j105_sequencial,$campos,"j105_sequencial");
        }else if(isset($chave_j105_descr) && (trim($chave_j105_descr)!="") ){
	         $sql = $claverbagrupo->sql_query("",$campos,"j105_descr"," j105_descr like '$chave_j105_descr%' ");
        }else{
           $sql = $claverbagrupo->sql_query("",$campos,"j105_sequencial","");
        }
        $repassa = array();
        if(isset($chave_j105_descr)){
          $repassa = array("chave_j105_sequencial"=>$chave_j105_sequencial,"chave_j105_descr"=>$chave_j105_descr);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $claverbagrupo->sql_record($claverbagrupo->sql_query($pesquisa_chave));
          if($claverbagrupo->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$j105_descr',false);</script>";
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
js_tabulacaoforms("form2","chave_j105_descr",true,1,"chave_j105_descr",true);
</script>