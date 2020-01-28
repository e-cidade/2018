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
include("classes/db_ruas_classe.php");
db_postmemory($HTTP_POST_VARS);
parse_str($HTTP_SERVER_VARS["QUERY_STRING"]);
$clruas = new cl_ruas;
$clruas->rotulo->label("j14_codigo");
$clruas->rotulo->label("j14_nome");
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
            <td width="4%" align="right" nowrap title="<?=$Tj14_codigo?>">
              <?=$Lj14_codigo?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("j14_codigo",7,$Ij14_codigo,true,"text",4,"","chave_j14_codigo");
		       ?>
            </td>
          </tr>
          <tr> 
            <td width="4%" align="right" nowrap title="<?=$Tj14_nome?>">
              <?=$Lj14_nome?>
            </td>
            <td width="96%" align="left" nowrap> 
              <?
		       db_input("j14_nome",40,$Ij14_nome,true,"text",4,"","chave_j14_nome");
		       ?>
            </td>
          </tr>
          <tr> 
            <td colspan="2" align="center"> 
              <input name="pesquisar" type="submit" id="pesquisar2" value="Pesquisar"> 
              <input name="limpar" type="reset" id="limpar" value="Limpar" >
              <input name="Fechar" type="button" id="fechar" value="Fechar" onClick="parent.db_iframe.hide();">
             </td>
          </tr>
        </form>
        </table>
      </td>
  </tr>
  <tr> 
    <td align="center" valign="top"> 
      <?
      if(isset($nomerua) && !isset($pesquisar)){
	echo "<script>document.form2.chave_j14_nome.value = '$nomerua'</script>";
      }
      if(isset($rural)){
	$rural = "";
      }else{
	$rural = " j14_rural = 'f'";
      }
      if(!isset($pesquisa_chave)){
        $sql = "select distinct j14_codigo,j14_nome from iptubase 
		inner join lote on j34_idbql = j01_idbql 
		inner join testpri on j49_idbql = j34_idbql 
		inner join ruas on j49_codigo = j14_codigo"; 
        if(isset($chave_j14_codigo) && (trim($chave_j14_codigo)!="") ){
	  if(isset($rural))
            $rural = ($rural == ""?"":" and " . $rural);
	  $sql2 = " where j14_codigo = ".$chave_j14_codigo.$rural."";
        }else if(isset($chave_j14_nome) && (trim($chave_j14_nome)!="") ){
	  if(isset($rural))
            $rural = ($rural == ""?"":" and " . $rural);
	  $sql2 = " where j14_nome like '$chave_j14_nome%' $rural";
        }else{
          $sql2 = "";
        }
	$sql = $sql.$sql2;
        db_lovrot($sql,15,"()","",$funcao_js);
	echo "<script>document.form2.chave_j14_nome.value = ''</script>";
	echo "<script>document.form2.chave_j14_codigo.value = ''</script>";
      }else{
        if($pesquisa_chave!=null && $pesquisa_chave!=""){
        $sql = "select distinct j14_nome from iptubase 
		inner join lote on j34_idbql = j01_idbql 
		inner join testpri on j49_idbql = j34_idbql 
		inner join ruas on j49_codigo = j14_codigo
		where j14_codigo = $pesquisa_chave"; 
          $result = $clruas->sql_record($sql);
          if($clruas->numrows!=0){
            db_fieldsmemory($result,0);
            echo "<script>".$funcao_js."('$j14_nome',false);</script>";
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