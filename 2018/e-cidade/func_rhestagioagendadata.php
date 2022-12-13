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
include("classes/db_rhestagioagendadata_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clrhestagioagendadata = new cl_rhestagioagendadata;
$clrhestagioagendadata->rotulo->label("h64_sequencial");
$clrhestagioagendadata->rotulo->label("h64_data");
$clrotulo = new rotulocampo;
$clrotulo->label("h50_sequencial");
$clrotulo->label("z01_nome");
$clrotulo->label("rh01_regist");

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
            <td width="4%" align="right" nowrap title="<?=$Th64_sequencial?>">
              <?=$Lh64_sequencial?>
            </td>
            <td width="96%" align="left" nowrap> 
           <?
		       db_input("h64_sequencial",10,$Ih64_sequencial,true,"text",4,"","chave_h64_sequencial");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh01_regist?>">
              <?=$Lrh01_regist?>
            </td>
            <td width="96%" align="left" nowrap> 
           <?
		       db_input("rh01_regist",10,$Irh01_regist,true,"text",4,"","chave_rh01_regist");
		       ?>
            </td>
          </tr>
          <tr> 
           <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
              <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z01_nome",40,$Iz01_nome,true,"text",4,"","chave_z01_nome");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_rhestagioagendadata.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      $sWhere = " h57_instit = ".db_getsession("DB_instit");
      if(!isset($pesquisa_chave)){
        $campos  = "h64_sequencial,h57_regist,cgm.z01_nome,h50_descr,h64_data,h56_sequencial as db_h56_sequencial,"; 
        $campos .= " case when h56_sequencial is not null then 'Aplicada' ";
        $campos .= "      when h56_sequencial is null then 'N�o Aplicada' end as dl_Situa��o"; 
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_rhestagioagendadata.php")==true){
             include("funcoes/db_func_rhestagioagendadata.php");
           }else{
           $campos = "rhestagioagendadata.*";
           }
        }
        if(isset($chave_h64_sequencial) && (trim($chave_h64_sequencial)!="") ){
	         $sql = $clrhestagioagendadata->sql_query_nome(null,$campos,"h64_sequencial","h64_sequencial = {$chave_h64_sequencial} and {$sWhere}");
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
	         $sql = $clrhestagioagendadata->sql_query_nome("",$campos,"cgm.z01_nome,h64_data","cgm.z01_nome like '$chave_z01_nome%' and {$sWhere}");
        }else if(isset($chave_rh01_regist) && (trim($chave_rh01_regist)!="") ){
	         $sql = $clrhestagioagendadata->sql_query_nome("",$campos,"cgm.z01_nome,h64_data"," rhpessoal.rh01_regist = '$chave_rh01_regist' and {$sWhere} ");
        }else{
           $sql = $clrhestagioagendadata->sql_query_nome("",$campos,"h64_sequencial",$sWhere);
        }
        $repassa = array("chave_h64_sequencial"=>@$chave_h64_sequencial,"chave_rh01_regist"=>@$chave_rh01_regist,"chave_z01_nome" => @$chave_z01_nome);
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clrhestagioagendadata->sql_record($clrhestagioagendadata->sql_query_nome($pesquisa_chave));
          if($clrhestagioagendadata->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$h64_data',false);</script>";
          }else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
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
js_tabulacaoforms("form2","chave_h64_data",true,1,"chave_h64_data",true);
</script>