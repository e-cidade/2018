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
include("classes/db_seguradoras_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clseguradoras = new cl_seguradoras;
$clseguradoras->rotulo->label("t80_segura");
$clseguradoras->rotulo->label("t80_numcgm");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="estilos.css" rel="stylesheet" type="text/css">
<script language="JavaScript" type="text/javascript" src="scripts/scripts.js"></script>
</head>
<body bgcolor=#CCCCCC leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table height="100%" width="100%" border="0"  align="center" cellspacing="0" bgcolor="#CCCCCC">
  <tr> 
    <td height="63" align="center" valign="top">
        <table width="35%" border="0" align="center" cellspacing="0">
	  <form name="form2" method="post" action="">
          <tr> 
            <td align="right" nowrap title="<?=$Tt80_segura?>">
              <?=$Lt80_segura?>
            </td>
            <td align="left" nowrap> 
              <?
		       db_input("t80_segura",8,$It80_segura,true,"text",4,"","chave_t80_segura");
		       ?>
            </td>
          </tr>
          <tr> 
            <td align="right" nowrap title="<?=$Tt80_numcgm?>">
              <?=$Lt80_numcgm?>
            </td>
            <td align="left" nowrap> 
              <?
		       db_input("t80_numcgm",8,$It80_numcgm,true,"text",4,"","chave_t80_numcgm");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe_seguradoras.hide();">
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
           if(file_exists("funcoes/db_func_seguradoras.php")==true){
             include("funcoes/db_func_seguradoras.php");
           }else{
           $campos = "seguradoras.*";
           }
        }
        if(isset($chave_t80_segura) && (trim($chave_t80_segura)!="") ){
	         $sql = $clseguradoras->sql_query($chave_t80_segura,$campos,"t80_segura");
        }else if(isset($chave_t80_numcgm) && (trim($chave_t80_numcgm)!="") ){
	         $sql = $clseguradoras->sql_query("",$campos,"t80_numcgm"," t80_numcgm like '$chave_t80_numcgm%' ");
        }else{
           $sql = $clseguradoras->sql_query("",$campos,"t80_segura","");
        }
        db_lovrot($sql,15,"()","",$funcao_js);
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
          $result = $clseguradoras->sql_record($clseguradoras->sql_query($pesquisa_chave));
          if($clseguradoras->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$t80_contato',false);</script>";
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