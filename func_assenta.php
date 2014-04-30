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
include("classes/db_assenta_classe.php");
db_postmemory($HTTP_POST_VARS);
//db_postmemory($HTTP_SERVER_VARS,2);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$classenta = new cl_assenta;
$clrotulo = new rotulocampo;
$classenta->rotulo->label("h16_codigo");
$classenta->rotulo->label("h16_regist");
$clrotulo->label("z01_numcgm");
$clrotulo->label("z01_nome");
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
            <td width="4%" align="right" nowrap title="<?=$Th16_codigo?>">
              <?=$Lh16_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h16_codigo",6,$Ih16_codigo,true,"text",4,"","chave_h16_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Th16_regist?>">
              <b>Matricula :</b>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("h16_regist",6,$Ih16_regist,true,"text",4,"","chave_h16_regist");
		       ?>
            </td>
          </tr>
<!--          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_numcgm?>">
              <?=$Lz01_numcgm?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("z01_numcgm",10,$Iz01_numcgm,true,"text",4,"","chave_z01_numcgm");
		       ?>
            </td>
          </tr>-->
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tz01_nome?>">
            <?=$Lz01_nome?>
            </td>
            <td width="96%" align="left" nowrap colspan='3'> 
            <?
            db_input("z01_nome",80,$Iz01_nome,true,"text",4,"","chave_z01_nome");
	        ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_assenta.hide();">
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
           if(file_exists("funcoes/db_func_assenta.php")==true){
             include("funcoes/db_func_assenta.php");
           }else{
           $campos = "assenta.*";
           }
        }
        if(isset($chave_h16_codigo) && (trim($chave_h16_codigo)!="") ){
	         $sql = $classenta->sql_query($chave_h16_codigo,$campos,"h16_codigo");
        }else if(isset($chave_h16_regist) && (trim($chave_h16_regist)!="") ){
	         $sql = $classenta->sql_query("",$campos,"h16_regist"," h16_regist = '$chave_h16_regist' ");
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
	         $sql = $classenta->sql_query("",$campos,"h16_regist"," z01_nome like '$chave_z01_nome%' ");
        //}else{
        //   $sql = $classenta->sql_query("",$campos,"h16_codigo","");
        }
        $repassa = array();
        if(isset($chave_h16_regist) && (trim($chave_h16_regist)!="")  ){
          $repassa = array("chave_h16_regist"=>$chave_h16_regist,"chave_h16_regist"=>$chave_h16_regist);
        }else if(isset($chave_z01_nome) && (trim($chave_z01_nome)!="") ){
          $repassa = array("chave_z01_nome"=>$chave_z01_nome,"chave_z01_nome"=>$chave_z01_nome);
        }else if(isset($chave_h16_codigo) && (trim($chave_h16_codigo)!="") ){
          $repassa = array("chave_h16_codigo"=>$chave_h16_codigo,"chave_h16_codigo"=>$chave_h16_codigo);
        }
	//echo $sql;
	      if(isset($sql) && trim($sql) != ""){
          db_lovrot($sql,15,"()","",$funcao_js,"","NoMe",$repassa);
        }
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $classenta->sql_record($classenta->sql_query($pesquisa_chave));
          if($classenta->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$h16_regist',false);</script>";
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
js_tabulacaoforms("form2","chave_z01_nome",true,1,"chave_z01_nome",true);
</script>