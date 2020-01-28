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
include("classes/db_rhestagioresultado_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhestagioresultado = new cl_rhestagioresultado;
$clrhestagioresultado->rotulo->label("h65_sequencial");
$clrhestagioresultado->rotulo->label("h65_data");
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
            <td width="4%" align="right" nowrap title="<?=$Th65_sequencial?>">
              <?=$Lh65_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h65_sequencial",10,$Ih65_sequencial,true,"text",4,"","chave_h65_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th65_data?>">
              <?=$Lh65_data?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h65_data",10,$Ih65_data,true,"text",4,"","chave_h65_data");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhestagioresultado.hide();">
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

        $campos  = " h57_sequencial, h57_regist, z01_nome, rh01_admiss,h55_nroaval, ";
        $campos .= " count(distinct h56_sequencial) as \"dl_total realizadas\",fc_calculapontosestagio(h57_sequencial,'t') as dl_Pontos";
        $sOrder  = "h57_regist";
        $sGroup  = "group by h55_nroaval, ";
        $sGroup .= "         h57_regist, ";
        $sGroup .= "         h57_sequencial, ";
        $sGroup .= "         z01_nome, ";
        $sGroup .= "         rh01_admiss "; 
        $sGroup .= "  having count(distinct h56_sequencial)= h55_nroaval"; 
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rhestagioresultado.php")==true){
             include("funcoes/db_func_rhestagioresultado.php");
           }else{
           $campos = "rhestagioresultado.*";
           }
        }
        if(isset($chave_h65_sequencial) && (trim($chave_h65_sequencial)!="") ){
	         $sql = $clrhestagioresultado->sql_query_resultado(null,$campos,"{$sOrder}","h65_sequencial = $chave_h65_sequencial {$sGroup}");
        }else if(isset($chave_h65_data) && (trim($chave_h65_data)!="") ){
	         $sql = $clrhestagioresultado->sql_query_resultado("",$campos,"h65_data"," h65_data like '$chave_h65_data%' $sGroup");
        }else{
           $sql = $clrhestagioresultado->sql_query_resultado("",$campos,"{$sOrder}"," 1=1 {$sGroup}");
        }
        $repassa = array();
        if(isset($chave_h65_data)){
          $repassa = array("chave_h65_sequencial"=>$chave_h65_sequencial,"chave_h65_data"=>$chave_h65_data);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhestagioresultado->sql_record($clrhestagioresultado->sql_query_resultado($pesquisa_chave));
          if($clrhestagioresultado->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$h65_data',false);</script>";
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
js_tabulacaoforms("form2","chave_h65_data",true,1,"chave_h65_data",true);
</script>