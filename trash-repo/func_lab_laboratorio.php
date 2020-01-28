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
include("classes/db_lab_laboratorio_classe.php");
require_once('libs/db_utils.php');
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cllab_laboratorio = new cl_lab_laboratorio;
$cllab_laboratorio->rotulo->label("la02_i_codigo");
$cllab_laboratorio->rotulo->label("la02_c_descr");

$iUsuario = db_getsession('DB_id_usuario');
$iDepto = db_getsession('DB_coddepto');
$oLab_labusuario = db_utils::getdao('lab_labusuario');
$oLab_labdepart = db_utils::getdao('lab_labdepart');
 
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
            <td width="4%" align="right" nowrap title="<?=$Tla02_i_codigo?>">
              <?=$Lla02_i_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("la02_i_codigo",10,$Ila02_i_codigo,true,"text",4,"","chave_la02_i_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tla02_c_descr?>">
              <?=$Lla02_c_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("la02_c_descr",50,$Ila02_c_descr,true,"text",4,"","chave_la02_c_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_lab_laboratorio.hide();">
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
           if(file_exists("funcoes/db_func_lab_laboratorio.php")==true){
             include("funcoes/db_func_lab_laboratorio.php");
           }else{
           $campos = "lab_laboratorio.*";
           }
        }
        
        // Traz somente os laboratorios do usuario logado. Se nao tiver nenhum, verifica se o departamento que esta logado eh uma laboratorio.
        if(isset($checkLaboratorio)) {

          if(isset($chave_la02_i_codigo) && (trim($chave_la02_i_codigo)!="") ){

	          $sql = $oLab_labusuario->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", "la02_i_codigo = $chave_la02_i_codigo and la05_i_usuario = $iUsuario");
            $oLab_labusuario->sql_record($sql);
            if($oLab_labusuario->numrows == 0) {
              $sql = $oLab_labdepart->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", "la02_i_codigo = $chave_la02_i_codigo and la03_i_departamento = $iDepto");
            }

          }else if(isset($chave_la02_c_descr) && (trim($chave_la02_c_descr)!="") ){

	          $sql = $oLab_labusuario->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", "la02_c_descr like '$chave_la02_c_descr%' and la05_i_usuario = $iUsuario");
            $oLab_labusuario->sql_record($sql);
            if($oLab_labusuario->numrows == 0) {
              $sql = $oLab_labdepart->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", "la02_c_descr like '$chave_la02_c_descr%' and la03_i_departamento = $iDepto");
            }

          }else{

	          $sql = $oLab_labusuario->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", "la05_i_usuario = $iUsuario");
            $oLab_labusuario->sql_record($sql);
            if($oLab_labusuario->numrows == 0) {
              $sql = $oLab_labdepart->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", "la03_i_departamento = $iDepto");
            }

          }
 
        } else {

          if(isset($chave_la02_i_codigo) && (trim($chave_la02_i_codigo)!="") ){
	          $sql = $cllab_laboratorio->sql_query($chave_la02_i_codigo,$campos,"la02_i_codigo");
          }else if(isset($chave_la02_c_descr) && (trim($chave_la02_c_descr)!="") ){
	          $sql = $cllab_laboratorio->sql_query("",$campos,"la02_c_descr"," la02_c_descr like '$chave_la02_c_descr%' ");
          }else{
            $sql = $cllab_laboratorio->sql_query("",$campos,"la02_i_codigo","");
          }

        }
        //echo $sql;
        $repassa = array();
        if(isset($chave_la02_i_codigo)){
          $repassa = array("chave_la02_i_codigo"=>$chave_la02_i_codigo,"chave_la02_c_descr"=>$chave_la02_c_descr);
        }
        db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
      }else{

        if($pesquisa_chave!=null && $pesquisa_chave!=""){

          // Traz somente os laboratorios do usuario logado. Se nao tiver nenhum, verifica se o departamento que esta logado eh uma laboratorio.
          if(isset($checkLaboratorio)) {
	          
            $sql = $oLab_labusuario->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", "la02_i_codigo = $pesquisa_chave and la05_i_usuario = $iUsuario");
            $oLab_labusuario->sql_record($sql);
            if($oLab_labusuario->numrows == 0) {
              $sql = $oLab_labdepart->sql_query(null,'la02_i_codigo, la02_c_descr',"la02_i_codigo", "la02_i_codigo = $pesquisa_chave and la03_i_departamento = $iDepto");
            }

          } else {
            $sql = $cllab_laboratorio->sql_query($pesquisa_chave);
          }

          $result = $cllab_laboratorio->sql_record($sql);
          if($cllab_laboratorio->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$la02_c_descr',false);</script>";
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
js_tabulacaoforms("form2","chave_la02_i_codigo",true,1,"chave_la02_i_codigo",true);
</script>