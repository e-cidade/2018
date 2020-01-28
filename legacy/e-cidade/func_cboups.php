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

require("libs/db_stdlib.php");
require("libs/db_stdlibwebseller.php");
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_especmedico_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clespecmedico = new cl_especmedico;
$clespecmedico->rotulo->label("sd27_i_codigo");

$clrotulo = new rotulocampo;
$clrotulo->label("z01_nome");
$clrotulo->label("sd04_i_medico");
$clrotulo->label("rh70_estrutural");
$clrotulo->label("rh70_descr");


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
            <td width="4%" align="right" nowrap title="<?=$Trh70_estrutural?>">
              <?=$Lrh70_estrutural?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh70_estrutural",10,$Irh70_estrutural,true,"text",4,"","chave_rh70_estrutural");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Trh70_descr?>">
              <?=$Lrh70_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("rh70_descr",60,$Irh70_descr,true,"text",4,"","chave_rh70_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="button" id="limpar" value="Limpar" onClick="js_limpar();">
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_cboups.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?

      $where = " sd27_c_situacao = 'A' ";
      if( isset($chave_sd04_i_unidade) && (int)$chave_sd04_i_unidade != 0){
         $where .= " and sd04_i_unidade = ".(int)$chave_sd04_i_unidade;
      }
         if(isset($chave_rh70_estrutural) && (int)$chave_rh70_estrutural != 0 ){
         	$where .= " and rh70_estrutural = '$chave_rh70_estrutural' ";
         }
         
      if( isset($chave_sd04_i_medico) && (int)$chave_sd04_i_medico != 0 ){
         $where .= !empty($where)?" and ":"";
         $where .= " sd04_i_medico = ".(int)$chave_sd04_i_medico;
      }      
      if( isset($lApenasCotas) && $lApenasCotas == 1 ){
         $where .=  " AND EXISTS (SELECT * FROM sau_cotasagendamento as agd ";
         $where .=  "WHERE agd.s163_i_rhcbo = rh70_sequencial AND agd.s163_i_upssolicitante = $iUpssolicitante ";
         $where .=    "AND agd.s163_i_upsprestadora = $iUpsprestadora)";
      }   
      if(!isset($pesquisa_chave)){
        if(isset($campos)==false){
        	if( isset($chave_sd04_i_medico)){
        		$campos = "sd27_i_codigo, sd03_i_codigo, a.z01_nome";
        	}else{
        		$campos = "distinct rh70_sequencial, rh70_estrutural, rh70_descr";
        	}
        }

        if(isset($chave_rh70_descr) && (trim($chave_rh70_descr)!="") ){
           $where = ( !empty($where)?' and ':'').$where;
	         $sql = $clespecmedico->sql_query("",$campos,"rh70_descr"," rh70_descr like '$chave_rh70_descr%' $where");
        }else if(isset($chave_rh70_estrutural) && (trim($chave_rh70_estrutural)!="") && !isset($chave_sd04_i_medico) ){
           $where = (!empty($where)?' and ':'').$where;         
	         $sql = $clespecmedico->sql_query("",$campos,"rh70_estrutural"," rh70_estrutural = '$chave_rh70_estrutural' $where ");          
        }else{
           $sql = $clespecmedico->sql_query("",$campos,"","$where");
        }

        $repassa = array();
        if(isset($chave_sd27_i_codigo)){
          $repassa = array("chave_sd27_i_codigo"=>$chave_sd27_i_codigo,"chave_sd27_i_codigo"=>$chave_sd27_i_codigo);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $where = !empty($where)?' and ':''.$where;
          $result = $clespecmedico->sql_record($clespecmedico->sql_query($pesquisa_chave.$where));
          if($clespecmedico->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$sd27_i_codigo',false);</script>";
          }else{
	         echo "<script>".$funcao_js."(true, 'Chave(".$pesquisa_chave.") n�o Encontrado');</script>";
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
function js_limpar(){
document.form2.chave_sd04_i_medico.value="";
document.form2.chave_z01_nome.value="";	
document.form2.chave_rh70_estrutural.value="";
}
js_tabulacaoforms("form2","chave_sd27_i_codigo",true,1,"chave_sd27_i_codigo",true);
</script>