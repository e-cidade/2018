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
require("libs/db_conecta.php");
include("libs/db_sessoes.php");
include("libs/db_usuariosonline.php");
include("dbforms/db_funcoes.php");
include("classes/db_tipoasse_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$cltipoasse = new cl_tipoasse;
$cltipoasse->rotulo->label("h12_codigo");
$cltipoasse->rotulo->label("h12_assent");
$cltipoasse->rotulo->label("h12_descr");

if (isset($chave_h12_assent)) {
  $chave_h12_assent = stripslashes($chave_h12_assent);
}

if (isset($chave_h12_descr)) {
  $chave_h12_descr = stripslashes($chave_h12_descr);
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <?
  if(!isset($consulta)){
  ?>
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	     <form name="form2" method="post" action="" >
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th12_codigo?>">
              <?=$Lh12_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h12_codigo",6,$Ih12_codigo,true,"text",4,"","chave_h12_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th12_assent?>">
              <?=$Lh12_assent?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h12_assent",5,$Ih12_assent,true,"text",4,"","chave_h12_assent");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th12_descr?>">
              <?=$Lh12_descr?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h12_descr",40,$Ih12_descr,true,"text",4,"","chave_h12_descr");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_tipoasse.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <?
  }
  ?>
  <tr> 
    <td align="center" valign="top"> 
      <?


      if (isset($chave_h12_assent)) {
        $chave_h12_assent = addslashes($chave_h12_assent);
      }

      if (isset($chave_h12_descr)) {
        $chave_h12_descr = addslashes($chave_h12_descr);
      }

      $dbwhere = "";
      $campos = "tipoasse.*";
      if(isset($chave_h12_tipo) && trim($chave_h12_tipo) != ""){
	$dbwhere = " h12_tipo = '".$chave_h12_tipo."'";
      }
      if(!isset($pesquisa_chave) && !isset($chave_assent)){
        if(isset($campos)==false){
           if(file_exists("funcoes/db_func_tipoasse.php")==true && !isset($consulta)){
             include("funcoes/db_func_tipoasse.php");
	   //}else if(isset($consulta)){
           //  include("funcoes/db_func_tipoassecons.php");
           //}else{
           $campos = "tipoasse.*";
           }
        }
        if(isset($chave_h12_codigo) && (trim($chave_h12_codigo)!="") ){
	         $sql = $cltipoasse->sql_query(null,$campos,"h12_codigo","h12_codigo = ".$chave_h12_codigo.($dbwhere != "" ? " and " : "").$dbwhere);
        }else if(isset($chave_h12_assent) && (trim($chave_h12_assent)!="") ){
	         $sql = $cltipoasse->sql_query(null,$campos,"h12_assent"," h12_assent = '".$chave_h12_assent."' ".($dbwhere != "" ? " and " : "").$dbwhere);
        }else if(isset($chave_h12_descr) && (trim($chave_h12_descr)!="") ){
	         $sql = $cltipoasse->sql_query(null,$campos,"h12_descr"," h12_descr like '".$chave_h12_descr."%' ".($dbwhere != "" ? " and " : "").$dbwhere);
        }else{
           $sql = $cltipoasse->sql_query(null,$campos,"h12_codigo",$dbwhere);
        }
        $repassa = array();
        if(isset($chave_h12_assent)){
          $repassa = array("chave_h12_codigo"=>$chave_h12_codigo,"chave_h12_assent"=>$chave_h12_assent);
        }
//	echo $sql;
        db_lovrot($sql,20,"()","",$funcao_js,"","NoMe",$repassa);
      }else{

        if(isset($pesquisa_chave) && $pesquisa_chave!=null && $pesquisa_chave!=""){

          $result = $cltipoasse->sql_record($cltipoasse->sql_query(null,"*","","h12_codigo = ".$pesquisa_chave.($dbwhere != "" ? " and " : "").$dbwhere));

          if($cltipoasse->numrows!=0){

            db_fieldsmemory($result,0);
            
            if (isset($lConsultaAssentamento) and $lConsultaAssentamento) {
							echo "<script>".$funcao_js."('$h12_descr', false);</script>";
						} else {
            	echo "<script>".$funcao_js."('$h12_assent',false, '$h12_descr');</script>";
						}
            
          } else{
	         echo "<script>".$funcao_js."('Chave(".$pesquisa_chave.") n�o Encontrado',true);</script>";
          }
        } else if(isset($chave_assent) && $chave_assent!=null && $chave_assent!="") {
          $result = $cltipoasse->sql_record($cltipoasse->sql_query("","*",""," trim(h12_assent) = '$chave_assent' "));

          if ($cltipoasse->numrows!=0) {
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$h12_assent', '$h12_descr', false, '$h12_codigo', '$h12_vinculaperiodoaquisitivo');</script>";
          } else {
	          echo "<script>".$funcao_js."(true,'Chave(".$chave_assent.") n�o Encontrado',true);</script>";
          }
        } else {
	        echo "<script>".$funcao_js."('','',false);</script>";
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
js_tabulacaoforms("form2","chave_h12_assent",true,1,"chave_h12_assent",true);
</script>